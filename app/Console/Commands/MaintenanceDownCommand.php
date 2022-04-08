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

namespace App\Console\Commands;

/**
 * The maintenance command to send the app into maintenance
 *
 * @author Miles Croxford <hello@milescroxford.com>
 */
class MaintenanceDownCommand extends MaintenanceCommand
{
    /**
     * @var string
     */
    protected $name = 'down';
    /**
     * @var string
     */
    protected $description = 'Put the application into maintenance mode.';

    /**
     * Put the application into maintenance mode.
     */
    public function fire()
    {
        if (!$this->maintenanceFileExists()) {
            file_put_contents($this->getMaintenanceFile(), '¯\_(ツ)_/¯');
            $this->info('The application is now in maintenance mode');
        } else {
            $this->info('The application is already in maintenance mode!');
        }
    }
}
