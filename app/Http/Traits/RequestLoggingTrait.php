<?php

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

namespace App\Http\Traits;

use Monolog\Formatter\LineFormatter;
use Monolog\Handler\RotatingFileHandler;
use Monolog\Logger;

/**
 * The trait to log requests
 *
 * @author Miles Croxford <hello@milescroxford.com>
 */
trait RequestLoggingTrait
{

    protected function logRequest(): void
    {
        $request_time = (new \DateTime)->setTimestamp($this->request->server->get('REQUEST_TIME'));

        $request = [
            'uid'         => substr(hash('md5', uniqid('', true)), 0, 7),
            'date'        => $request_time->format('Y-m-d H:i:s [e]'),
            'request'     => [
                'headers'     => $this->request->headers->all(),
                'url'         => sprintf(
                    "%s://%s:%s%s",
                    $this->request->server->get('REQUEST_SCHEME'),
                    $this->request->server->get('SERVER_NAME'),
                    $this->request->server->get('SERVER_PORT'),
                    $this->request->server->get('REQUEST_URI')
                ),
                'ip'          => $this->request->server->get('REMOTE_ADDR'),
                'http_method' => $this->request->server->get('REQUEST_METHOD'),
                'server'      => $this->request->server->get('SERVER_NAME'),
                'referrer'    => $this->request->server->get('HTTP_REFERER'),
                'parameters'  => $this->request->all(),
                'other'       => [
                    'controller' => $this->request->get('_controller'),
                    'action'     => $this->request->get('_action'),
                    'driver_id'  => $this->request->get('driver_id'),
                    'user_id'    => $this->request->get('user_id'),
                ],
            ],
            //            'response'    => $response,
            'environment' => [
                'stage'      => $this->request->server->get('APP_DEBUG') ? 'dev' : 'prod',
                'show_debug' => $this->request->server->get('APP_SHOW_DEBUG'),
                'root'       => $this->request->server->get('DOCUMENT_ROOT'),
            ],
        ];

        // $branches = `git branch -v --no-abbrev`;
        //
        // if (preg_match('{^\* (.+?)\s+([a-f0-9]{40})(?:\s|$)}m', $branches, $matches)) {
        //     $request['git'] = [
        //         'branch' => $matches[1],
        //         'commit' => $matches[2],
        //     ];
        // }

        $request['debug'] = [
            'usage'      => $this->formatBytes(memory_get_usage()),
            'peak_usage' => $this->formatBytes(memory_get_peak_usage()),
        ];

        $stream = new RotatingFileHandler(storage_path('logs/requests.log'), 5, Logger::DEBUG);
        $stream->setFormatter(new LineFormatter(null, null, true, true));

        $log = new Logger('request');
        $log->pushHandler($stream);

//        $request['are']['you']['having']['fun'] = 'm8>? -- by Sarah Craig 2016';

        $log->info(json_encode($request, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
    }

    /**
     * @param $bytes
     *
     * @return string
     */
    protected function formatBytes($bytes): string
    {
        $bytes = (int)$bytes;

        if ($bytes > 1024 * 1024) {
            return round($bytes / 1024 / 1024, 2) . ' MB';
        } elseif ($bytes > 1024) {
            return round($bytes / 1024, 2) . ' KB';
        }
        return $bytes . ' B';
    }
}
