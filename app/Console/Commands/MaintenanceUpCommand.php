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
class MaintenanceUpCommand extends MaintenanceCommand
{
    /**
     * @var string
     */
    protected $name = 'up';
    /**
     * @var string
     */
    protected $description = 'Bring the application out of maintenance mode.';

    /**
     * Put the application into maintenance mode.
     */
    public function fire()
    {
        if (!$this->maintenanceFileExists()) {
            $this->info('The application was already alive.');
        } else {
            unlink($this->getMaintenanceFile());
            $this->info('The application is now live');
        }
    }
}
