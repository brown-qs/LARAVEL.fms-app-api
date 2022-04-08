<?php

/**
 * This file is part of the Scorpion API
 * (c) Hare Digital
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @package     scorpion/api
 * @version     0.1.0
 * @copyright   Copyright (c) Hare Digital
 * @license     LICENSE
 * @link        README.MD Documentation
 */

namespace App\Http\Traits;

use App\Serializers\DefaultSerializer;
use App\Support\Auth;
use App\Support\Facades\Bench;
use App\Transformers\DefaultTransformer;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Config;
use League\Fractal\Serializer\SerializerAbstract;
use League\Fractal\TransformerAbstract;
use RuntimeException;

/**
 * The trait to make generic API responses
 *
 * @author Miles Croxford <hello@milescroxford.com>
 */
trait ApiResponseTrait
{
    /**
     * Trait to log requests
     */
    use RequestLoggingTrait;

    /**
     * The fractal serializer
     *
     * @var string
     */

    protected $serializer;
    /**
     * The fractal transformer
     *
     * @var string
     */
    protected $transformer;

    /**
     * The benchmark object
     *
     * @var \Ubench
     */
    protected $bench;

    /**
     * The body of the response
     *
     * @var array
     */
    protected $body = [];

    /**
     * The headers of the response
     *
     * @var array
     */
    protected $headers = [];

    /**
     * The status code of the response
     *
     * @var int
     */
    private $statusCode;

    /**
     * The status description of the response
     *
     * @var string
     */
    private $statusDesc;

    /**
     * Get the status code for the response
     *
     * @return int
     */
    public function getStatusCode(): int
    {
        return $this->statusCode ?? Response::HTTP_OK;
    }

    /**
     * Sets the status code for the response
     *
     * @param int $statusCode
     *
     * @return $this
     */
    public function setStatusCode(int $statusCode)
    {
        $this->statusCode = $statusCode;

        return $this;
    }

    /**
     * Responding with JSON shortcut.
     *
     * @return JsonResponse|Response
     */
    public function respond(): JsonResponse
    {
        if (isset($this->request) &&
            $this->request->get('return_token') &&
            !$this->request->get('mocking') &&
            !$this->request->get('is_api_token')
        ) {
            $this->addReturnToken($this->request->get('return_token'));
        }

        $body = [
            'status'      => $this->getStatusCode(),
            'status_desc' => $this->getStatusDesc(),
        ];

        $body += $this->body;

        if (Config::get('app.showDebug', false)) {
            $body = $this->debugBody($body);
        }

        if (Config::get('app.logRequest', false)) {
            $this->logRequest(json_encode($body, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
        }

        return response()->json($body, $this->getStatusCode(), $this->headers);
    }

    /**
     * Responding with not found (420) shortcut.
     *
     * @param null|string|array $error
     * @param null|string       $statusDesc
     *
     * @return JsonResponse
     */
    public function respondWithPassiveAggression($error = null, $statusDesc = null): JsonResponse
    {
        return $this->respondWithError(420,
            $error ?? 'Enhance Your Calm',
            $statusDesc ?? 'Enhance Your Calm'
        );
    }

    /**
     * Responding with not found (404) shortcut.
     *
     * @param null|string|array $error
     * @param null|string       $statusDesc
     *
     * @return JsonResponse
     */
    public function respondWithNotFound($error = null, $statusDesc = null): JsonResponse
    {
        return $this->respondWithError(Response::HTTP_NOT_FOUND,
            $error ?? Response::$statusTexts[Response::HTTP_NOT_FOUND],
            $statusDesc ?? Response::$statusTexts[Response::HTTP_NOT_FOUND]
        );
    }

    /**
     * Responding with forbidden (403) shortcut.
     *
     * @param null|string|array $error
     * @param null|string       $statusDesc
     *
     * @return JsonResponse
     */
    public function respondWithForbidden($error = null, $statusDesc = null): JsonResponse
    {
        return $this->respondWithError(Response::HTTP_FORBIDDEN,
            $error ?? Response::$statusTexts[Response::HTTP_FORBIDDEN],
            $statusDesc ?? Response::$statusTexts[Response::HTTP_FORBIDDEN]
        );
    }

    /**
     * Responding with invalid request (422) shortcut.
     *
     * @param null|string|array $error
     * @param null|string       $statusDesc
     *
     * @return JsonResponse
     */
    public function respondWithInvalidRequest($error = null, $statusDesc = null): JsonResponse
    {
        return $this->respondWithError(Response::HTTP_UNPROCESSABLE_ENTITY,
            $error ?? Response::$statusTexts[Response::HTTP_UNPROCESSABLE_ENTITY],
            $statusDesc ?? Response::$statusTexts[Response::HTTP_UNPROCESSABLE_ENTITY]
        );
    }

    /**
     * Responding with error
     *
     * @param null|string|array $error
     * @param null|string       $statusDesc
     *
     * @return JsonResponse
     */
    public function respondWithRateLimited($error = null, $statusDesc = null): JsonResponse
    {
        return $this->respondWithError(Response::HTTP_TOO_MANY_REQUESTS,
            $error ?? Response::$statusTexts[Response::HTTP_TOO_MANY_REQUESTS],
            $statusDesc ?? Response::$statusTexts[Response::HTTP_TOO_MANY_REQUESTS]
        );
    }

    /**
     * Responding with error
     *
     * @param null|int          $statusCode
     * @param null|string|array $error
     * @param null|string       $statusDesc
     *
     * @return JsonResponse
     */
    public function respondWithError(?int $statusCode = null, $error = null, $statusDesc = null): JsonResponse
    {
        return $this->setStatusCode($statusCode ?? Response::HTTP_BAD_REQUEST)
            ->setStatusDesc($statusDesc ?? Response::$statusTexts[$this->getStatusCode()])
            ->appendErrors((is_string($error) || is_array($error)) ? $error : $this->getStatusDesc())
            ->respond();
    }

    /**
     * Responding with JWT shortcut.
     *
     * @param Auth $auth
     *
     * @return $this
     */
    public function addReturnToken(Auth $auth)
    {
        $this->request->return_token = $auth;

        return $this->transformItem($auth);
    }

    /**
     * Transforming item
     *
     * @param Model|array|string $data              The model data
     * @param string|array       $includes          The included data
     * @param string|null        $key               The key to encapsulate the data
     * @param bool               $useGetTransformer If to use the getTransformer() function or to use autoTransformer
     *
     * @return $this
     */
    public function transformItem($data,
                                  $includes = null,
                                  ?string $key = null,
                                  bool $useGetTransformer = false)
    {
        if (!is_null($includes)) {
            $includes = (is_string($includes)) ? [$includes] : $includes;
        }

        if (!is_object($data)) {
            if (!$key) {
                throw new RuntimeException('The key parameter is required if the data passed is not a Model object');
            }

            $useGetTransformer = true;
        } else {
            $class           = getClassName($data);
            $className       = classNameToUnderscore($data);
            $autoTransformer = sprintf("App\\Transformers\\%sTransformer", $class);
        }

        $item = fractal()
            ->serializeWith($this->getSerializer())
            ->parseIncludes($includes)
            ->item($data,
                $useGetTransformer ? $this->getTransformer() : new $autoTransformer)
            ->toArray();

        return $this->appendBody($key ?? $className, $item);
    }

    /**
     * Transforming collection
     *
     * @param mixed        $data              The model data
     * @param string|array $includes          The included data
     * @param string|null  $key               The key to encapsulate the data
     * @param bool         $useGetTransformer If to use the getTransformer() function or to use autoTransformer
     *
     * @return $this
     */
    public function transformCollection($data,
                                        $includes = null,
                                        ?string $key = null,
                                        bool $useGetTransformer = false)
    {
        $hasPagination = $data instanceof LengthAwarePaginator || $data instanceof Paginator;

        if (($hasPagination && count($data->items()) === 0) || count($data) === 0) {
            if (!$key) {
                throw new RuntimeException('The key parameter is required if the data passed can be empty');
            }

            $this->body[$key] = ['data' => []];

            if ($hasPagination) {
                return $this->withPagination($key, $data);
            }

            return $this;
        }

        if (!is_null($includes)) {
            $includes = (is_string($includes)) ? [$includes] : $includes;
        }

        if (!is_object($data)) {
            if (!$key) {
                throw new RuntimeException('The key parameter is required if the data passed is not a Model object');
            }

            $useGetTransformer = true;
        } else {
            $class           = getClassName($data->first());
            $className       = classNameToUnderscore($data->first());
            $autoTransformer = sprintf("App\\Transformers\\%sTransformer", $class);
        }

        $collection = fractal()
            ->collection($data,
                $useGetTransformer ? $this->getTransformer() : new $autoTransformer)
            ->serializeWith($this->getSerializer())
            ->parseIncludes($includes)
            ->toArray();

        $key = $key ?? $className . 's';

        $this->appendBody($key, $collection);

        if ($hasPagination) {
            $this->withPagination($key, $data);
        }

        return $this;
    }

    /**
     * Gets the transformer for this class
     *
     * @return mixed
     */
    public function getTransformer(): TransformerAbstract
    {
        return (is_null($this->transformer)) ? $this->getDefaultTransformer() : $this->transformer;
    }

    /**
     * Sets the transform for this class
     *
     * @param TransformerAbstract|string $transformer
     *
     * @return $this
     */
    public function setTransformer($transformer)
    {
        if (is_string($transformer)) {
            $transformer = new $transformer();
        }

        $this->transformer = $transformer;

        return $this;
    }

    /**
     * Returns the default transformer
     *
     * @return null|TransformerAbstract
     */
    public function getDefaultTransformer(): TransformerAbstract
    {
        $class = $this->getDefaultTransformerClassName();

        return class_exists($class) ? new $class() : new DefaultTransformer();
    }

    /**
     * Gets the default transformer string
     *
     * @return string
     */
    public function getDefaultTransformerClassName(): string
    {
        return $class = "App\\Transformers\\" . ucfirst($this->request->get('_controller')) . "Transformer";
    }

    /**
     * Gets the serializer for this class
     *
     * @return mixed
     */
    public function getSerializer(): SerializerAbstract
    {
        return (is_null($this->serializer)) ? $this->getDefaultSerializer() : new $this->serializer();
    }

    /**
     * Sets the serializer for this class
     *
     * @param string $serializer
     */
    public function setSerializer($serializer): void
    {
        $this->serializer = $serializer;
    }

    /**
     * Gets the default serializer
     *
     * @return SerializerAbstract
     */
    public function getDefaultSerializer(): SerializerAbstract
    {
        return new DefaultSerializer();
    }

    /**
     * Appends a key => data to the body
     *
     * @param string       $key  The key to use
     * @param array|string $data The data to use
     *
     * @return $this
     */
    public function appendBody(string $key, $data)
    {
        $this->body[$key] = $data;

        return $this;
    }

    /**
     * Sets an error messages
     *
     * @param array|string $errors
     *
     * @return $this
     */
    public function appendErrors($errors)
    {
        return $this->appendBody('errors', is_array($errors) ? $errors : [$errors]);
    }

    /**
     * Gets the status description for the response
     *
     * @return string
     */
    public function getStatusDesc(): string
    {
        if (isset($this->statusDesc)) {
            return $this->statusDesc;
        }

        $statusCode = $this->getStatusCode();

        return Response::$statusTexts[$statusCode] ?? Response::$statusTexts[Response::HTTP_FORBIDDEN];
    }

    /**
     * Sets the status description for the response
     *
     * @param string $statusDesc
     *
     * @return $this
     */
    public function setStatusDesc(string $statusDesc)
    {
        $this->statusDesc = $statusDesc;

        return $this;
    }

    /**
     * Adds the debug values to the body
     *
     * @param array|string $body
     *
     * @return array
     */
    public function debugBody($body): array
    {
        Bench::stop();

        $body['debug'] = array_merge(
            ['request' => $this->request->route()[1]],
            Bench::getBodyStats()
        );

        $controller      = $this->request->get('_controller');
        $action          = $this->request->get('_action');
        $rules           = Config::get('validation');
        $validationRules = [];

        if (isset($rules[$controller])) {
            if (isset($rules[$controller]['*'])) {
                $validationRules = $rules[$controller]['*'];
            }

            if (isset($rules[$controller][$action])) {
                $validationRules = array_merge($validationRules, $rules[$controller][$action]);
            }
        }

        $body['debug']['request']['validation'] = [
            'key'   => $this->request->get('_controller') !== null && $this->request->get('_action') !== null ?
                $this->request->get('_controller') . '.' . $this->request->get('_action') : null,
            'rules' => $validationRules,
        ];

        return $body;
    }

    /**
     * Allocate $request to this request
     *
     * @param mixed $request The request
     *
     * @return $this
     */
    public function withRequest(Request $request)
    {
        $this->request = $request;

        return $this;
    }

    /**
     * Paginate the $data
     *
     * @param string $key  the key for the data
     * @param mixed  $data the data to paginate
     *
     * @return $this
     */
    public function withPagination(string $key, $data)
    {
        $this->body[$key]['meta'] = [
            'total_items'  => $data->total(),
            'item_count'   => count($data->items()),
            'total_pages'  => $data->lastPage(),
            'current_page' => $data->currentPage(),
        ];

        return $this;
    }
}
