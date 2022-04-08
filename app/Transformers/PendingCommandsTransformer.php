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

use App\Models\PendingCommand;
use App\Models\Unit;
use League\Fractal\Resource\ResourceAbstract;

/**
 * PendingCommandsTransformer
 *
 * @package App\Transformers
 * @author  Kirk
 */
class PendingCommandsTransformer extends DefaultTransformer
{

    /**
     * @param PendingCommand $pendingCommand
     *
     * @return array
     */
    public function transform(PendingCommand $pendingCommand): array
    {

        return [
            'name'           => PendingCommand::getFriendlyName($pendingCommand->command),
            'command'        => $pendingCommand->command,
            'command_value'  => $pendingCommand->commandValue,
            'pending_status' => PendingCommand::getFriendlyValue($pendingCommand->command, $pendingCommand->commandValue),
            'status'         => $pendingCommand->status,
            'result'         => $pendingCommand->result,
        ];

    }


}
