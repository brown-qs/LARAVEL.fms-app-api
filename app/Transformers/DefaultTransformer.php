<?php declare(strict_types=1);

/**
 * This file is part of the Scorpion API
 *
 * (c) Hare Digital
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @package     scorpion/api
 * @version     0.1.0
 * @copyright   Copyright (c) Hare Digital
 * @license     LICENSE
 * @link        README.MD Documentation
 */

namespace App\Transformers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use League\Fractal\Resource\ResourceAbstract;
use League\Fractal\TransformerAbstract;

/**
 * Default transformer
 *
 * @package App\Transformers
 * @author  Miles Croxford <hello@milescroxford.com>
 */
class DefaultTransformer extends TransformerAbstract
{
    /**
     * @var array
     */
    protected $data = [];

    /**
     * @var array
     */
    protected $links = null;

    /**
     * @param Collection $collection
     * @param string     $transformer
     *
     * @return ResourceAbstract
     */
    protected function returnCollection(?Collection $collection, string $transformer): ResourceAbstract
    {
        return !is_null($collection) ?
            $this->collection($collection, new $transformer) : $this->null();
    }

    /**
     * @param Model  $item
     * @param string $transformer
     *
     * @return ResourceAbstract
     */
    protected function returnItem(?Model $item, string $transformer): ResourceAbstract
    {
        return !is_null($item) ?
            $this->item($item, new $transformer) : $this->null();
    }

    /**
     * @param array $data
     *
     * @return $this
     */
    protected function withData(array $data)
    {
        $this->data = $data;

        return $this;
    }

    /**
     * @param array $links
     *
     * @return $this
     */
    protected function withLinks(array $links)
    {
        $this->links = $links;

        return $this;
    }

    /**
     * @return array
     */
    protected function build(): array
    {
        $data          = $this->data;
        $data['links'] = $this->links;

        return $data;
    }
}
