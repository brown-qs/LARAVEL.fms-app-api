<?php declare(strict_types=1);

/**
 * This file is part of the Scorpion API
 *
 * (c)
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @package     scorpion/api
 * @version     0.1.0
 * @copyright   Copyright (c)
 * @license     LICENSE
 * @link        README.MD Documentation
 */

namespace App\Http\Controllers;

use App\Models\HealthCheck;
use App\Models\OTAUpgrade;
use App\Models\OTAUpgradeUnit;
use App\Models\PendingCommand;
use App\Models\Vehicle;
use App\Models\Unit;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Config;
use Carbon\Carbon;

/**
 * UserController
 *
 * @package App\Http\Controllers
 * @author
 */
class OTAController extends AbstractApiController
{

    /**
     * @return JsonResponse
     */
    public function indexAction(): JsonResponse
    {
        $all = OTAUpgrade::all();
        return $this->transformCollection($all)->respond();
    }

    /**
     * @return JsonResponse
     */
    public function getFirmwareUpdate(int $vehicleId): JsonResponse
    {
        $OTA = $this->checkFirmwareUpdate($vehicleId);
        return $this->transformItem($OTA)->respond();
    }

    public function checkFirmwareUpdate($vehicleId) {
        $vehicle = Vehicle::whereRaw('Vehicle.vehicleId = ?', $vehicleId)
            ->whereRaw('Vehicle.customerId = ?', $this->request->get('user')->customerId)
            ->with('groups', 'latestJourney', 'unit')
            ->first();

        if ($this->isAdmin()) {
            $vehicle = Vehicle::whereRaw('Vehicle.vehicleId = ?', $vehicleId)
                ->with('groups', 'latestJourney', 'unit')
                ->first();
        }

        if (!$vehicle) {
            return $this->respondWithError(404,"Vehicle not found");
        }

        $unit = Unit::where('unitId', $vehicle->unitId)->first();
        $healthCheck = HealthCheck::where('unitId', $unit->unitId)->where('vehicleId', $vehicleId)
            ->orderBy('timestamp', 'DESC')->first();

        $firmwareVersion = intval(substr((string)$unit->appId, 2));


        $OTA = OTAUpgrade::whereRaw("? between fromVersion and toVersion", [$firmwareVersion])
            ->where("model", $unit->type)->first();


        if (!$OTA) {
            $OTA = new OTAUpgrade();
            $OTA->firmwareUpdateNotRequired = true;
            $OTA->firmwareVersion = $firmwareVersion;
            $OTA->modelType = $unit->type;
            $checks = new \stdClass();
            $checks->upgradeAvailable = false;
            $OTA['checks'] = $checks;
            return $OTA;
        }

        $otaRequired = $OTA->toVersion !== $firmwareVersion;
        $OTA['vehicleId'] = $vehicleId;
        $OTA['unitId'] = $unit->unitId;


        $checks = new \stdClass();

        $checks->upgradeAvailable = $otaRequired;
        $checks->toVersion = $OTA->toVersion;
        $checks->currentVersion = $firmwareVersion;
        if ($healthCheck) {
            $checks->passHealthCheckDate = strtotime($healthCheck->timestamp->format('Y-m-d H:i:s')) > strtotime('-5 minute', time());
            $checks->lastHealthCheckDate = strtotime($healthCheck->timestamp->format('Y-m-d H:i:s'));
            $checks->internalBatteryPass = $healthCheck->backupVoltage > 3.9;
            $checks->internalBatteryVoltage = $healthCheck->backupVoltage;
            $checks->externalBatteryPass = $healthCheck->batteryVoltage > 12;
            $checks->externalBatteryVoltage = $healthCheck->batteryVoltage;
        } else {
            $checks->passHealthCheckDate = false;
            $checks->internalBatteryPass = false;
            $checks->externalBatteryPass = false;
        }

        if ($vehicle->latest_position) {
            $checks->ignitionOn = strtotime($vehicle->latest_position->timestamp->format('Y-m-d H:i:s')) > strtotime('-5 minute', time()) && $vehicle->latest_position->ignition;
            $checks->lastPosition = strtotime($vehicle->latest_position->timestamp->format('Y-m-d H:i:s'));
            $checks->lastPositionIgnitionStatus = $vehicle->latest_position->ignition;
        } else {
            $checks->ignitionOn = false;
        }
        $checks->safeToUpdate = $checks->upgradeAvailable && $checks->passHealthCheckDate && $checks->internalBatteryPass && $checks->externalBatteryPass && $checks->ignitionOn;


        $OTAUpdateUnit = OTAUpgradeUnit::where("unitId", $unit->unitId)
            ->where("completed", false)
            ->where("deleted", false)->first();
        if ($OTAUpdateUnit) {
            $pendingCommand = PendingCommand::where("commandId", $OTAUpdateUnit->commandId)->first();
            $OTAUpdateUnit->pendingCommand = $pendingCommand;
        }


        $checks->updateInProgress = $OTAUpdateUnit;
        $checks->help = "Must have available update.
         Must have seen a health check in the last 24 hours.
         Must have a decent internal + external battery voltage.
         Must have seen the last position in 5 minutes with ignition on";
        $OTA['checks'] = $checks;
        return $OTA;
    }

    public function startUpdateFirmware($vehicleId): JsonResponse
    {
        $OTA = $this->checkFirmwareUpdate($vehicleId);
        $OTA->checks->safeToUpdate = true;
        if (!$OTA || !$OTA->checks->upgradeAvailable || !$OTA->checks->safeToUpdate || $OTA->checks->updateInProgress) {
            return $this->respondWithPassiveAggression();
        }

        $pendingCommand               = new PendingCommand();
        $pendingCommand->storePendingCommand($OTA->vehicleId, PendingCommand::FMSCONFIG, $OTA->commandValue, $this->request->get('user')->id);

        $OTAUpgradeUnit = new OTAUpgradeUnit();
        $OTAUpgradeUnit->unitId = $OTA->unitId;
        $OTAUpgradeUnit->fromVersion = $OTA->checks->currentVersion;
        $OTAUpgradeUnit->toVersion = $OTA->checks->toVersion;
        $OTAUpgradeUnit->otaUpgradeId = $OTA->id;
        $OTAUpgradeUnit->commandId = $pendingCommand->id;
        $OTAUpgradeUnit->userId = $this->request->get('user')->id;
        $OTAUpgradeUnit->save();


        return $this->respondWithInvalidRequest("Update started");
    }



}
