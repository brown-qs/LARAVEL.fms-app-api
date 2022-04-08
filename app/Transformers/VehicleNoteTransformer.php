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

use App\Models\VehicleNote;

/**
 * VehicleTransformer
 *
 * @package App\Transformers
 * @author  Miles Croxford <hello@milescroxford.com>
 */
class VehicleNoteTransformer extends DefaultTransformer
{
    /**
     * @param VehicleNote $vehicleNote
     *
     * @return array
     */
    public function transform(VehicleNote $vehicleNote): array
    {
        $this->withData([
            'id'         => $vehicleNote->noteId,
            'user_id'    => $vehicleNote->userId,
            'vehicle_id' => $vehicleNote->vehicleId,
            'noteType'   => $vehicleNote->noteType,
            'note'       => $vehicleNote->note,
            'timestamp'  => carbon_timestamp($vehicleNote->timestamp),
            'deleted'    => $vehicleNote->deleted,
            'read'       => $vehicleNote->read,
            'visibility' => $vehicleNote->visibility,
        ]);

        return $this->build();
    }
}
