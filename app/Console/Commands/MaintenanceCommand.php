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

use Illuminate\Console\Command;

/**
 * The abstract maintenance command
 *
 * @author Miles Croxford <hello@milescroxford.com>
 */
abstract class MaintenanceCommand extends Command
{
    protected function maintenanceFileExists()
    {
        return file_exists($this->getMaintenanceFile());
    }

    protected function getMaintenanceFile()
    {
        return storage_path() . '/framework/maintenance';
    }
}
