<?php declare(strict_types=1);

namespace App\Support;


use App\Models\Brand;
use Exception;
use Firebase\JWT\JWT;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\GuzzleException;

/**
 * Class SMSAPI
 * @package App\Support
 */
class SMSAPI
{
    const DEFAULT_TEXT_PATH = "default";

    private $client;
    private $auth_key;

    public function initClient()
    {
        $data = [
            'payload' => [
                'service' => getenv('APIS_COMMS_SERVICE_NAME'),
                'api_key' => getenv('APIS_COMMS_KEYS_PUBLIC'),
            ],
            "iat"     => strtotime('-1 hour'),
            "exp"     => strtotime('+5 minute'),
        ];

        $this->auth_key = JWT::encode($data, getenv('APIS_COMMS_KEYS_PRIVATE'));

        $this->client = new Client();
    }

    /**
     * @param array|string $to
     * @param string $body
     * @param int|null $customerId
     * @param string|bool $brand
     * @return int
     * @throws GuzzleException
     */
    public function sendDefault($to, string $body, $customerId = null, $brand = false)
    {
        $this->initClient();

        if (is_string($to)) {
            $to = [$to];
        }

        $customerId = is_null($customerId) || $customerId == 0 || $customerId == "" || empty($customerId) ? 1000 : $customerId;
        $brand = ($brand === false || is_null($brand)) ? getenv('APP_BRAND') : $brand;
        if ($brand === Brand::BRAND_FLEET) {
            $brand = "fleet";
        }

        $locale = "en_GB";
        try {
            $res = $this->client->request('POST', getenv('APIS_COMMS_URL') . '/channels/sms/' . self::DEFAULT_TEXT_PATH,
                [
                    'auth' => [$this->auth_key, ''],
                    'json' => [
                        'to'          => $to,
                        'data'        => [
                            "body" => $body,
                        ],
                        'brand'       => $brand,
                        'locale'      => $locale,
                        'customer_id' => intval($customerId),
                    ],
                ]);

        } catch (ClientException $e) {
            return json_decode($e->getResponse()->getBody()->getContents());
        }
        catch (Exception $e) {
            return json_decode($e->getMessage());
        }
        return json_decode($res->getBody()->getContents());
    }
}