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

namespace App\Support;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

/**
 * The Bench support class
 *
 * @author Miles Croxford <hello@milescroxford.com>
 */
class Bench
{
    /**
     * @var \Ubench
     */
    private $bench;

    /**
     * Bench constructor.
     */
    public function __construct()
    {
        $this->start();
    }

    /**
     * Starts the benchmark
     */
    public function start(): void
    {
        if (Config::get('app.showDebug', false) && !$this->bench) {
            $this->bench = new \Ubench();
            $this->bench->start();

            DB::connection('mysql')->enableQueryLog();
        }
    }

    /**
     * Ends the benchmark
     */
    public function stop(): void
    {
        if (Config::get('app.showDebug', false) && $this->bench) {
            $this->bench->end();
        }
    }

    /**
     * @return array
     */
    public function getBodyStats(): array
    {
        $debug = [
            'stats' => [
                'time'         => $this->bench->getTime(),
                'memory_peak'  => $this->bench->getMemoryPeak(),
                'memory_usage' => $this->bench->getMemoryUsage(),
            ],
        ];

        foreach (['mysql'] as $db) {
            $queries = DB::connection($db)->getQueryLog();

            $debug['dbs'][$db]['count']      = count($queries);
            $debug['dbs'][$db]['total_time'] = 0;

            if ($queries) {
                foreach ($queries as $query) {
                    $debug['dbs'][$db]['total_time'] += $query['time'];
                }

                $debug['dbs'][$db]['total_time'] = $debug['dbs'][$db]['total_time'] . 'ms';
            }

            $debug['dbs'][$db]['queries'] = $queries;
        }

        return $debug;
    }
}
