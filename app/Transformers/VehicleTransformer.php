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
use App\Models\Vehicle;
use League\Fractal\Resource\ResourceAbstract;

/**
 * VehicleTransformer
 *
 * @package App\Transformers
 * @author  Miles Croxford <hello@milescroxford.com>
 */
class VehicleTransformer extends DefaultTransformer
{
    /**
     * {@inheritDoc}
     */
    protected $availableIncludes = ['customer', 'unit', 'groups', 'journeys', 'latest_journey', 'latest_position', 'pending_commands', 'kenya_meta', 'cat_type'];

    /**
     * @param Vehicle $vehicle
     *
     * @return array
     */
    public function transform(Vehicle $vehicle): array
    {
        $unitId = $vehicle->unitId === 0 ? null : $vehicle->unitId;

        $this->withData([
            'id'                      => $vehicle->vehicleId,
            'customer_id'             => $vehicle->customerId,
            'unit_id'                 => $unitId,
            'dealership_id'           => $vehicle->dealershipId,
            'fitter_id'               => $vehicle->fitterId,
            'install_complete'        => $vehicle->installComplete,
            'timestamp'               => carbon_timestamp($vehicle->timestamp),
            'installed'               => carbon_timestamp($vehicle->installed),
            'alias'                   => $vehicle->alias,
            'vin'                     => $vehicle->vin,
            'registration'            => $vehicle->registration,
            'make'                    => $vehicle->make,
            'model'                   => $vehicle->model,
            'colour'                  => $vehicle->colour,
            'description'             => $vehicle->description,
            'fuel_type'               => $vehicle->fuelType,
            'type'                    => $vehicle->type,
            'odometer'                => $vehicle->odometer,
            'aux_0_name'              => $vehicle->aux0Name,
            'aux_0_string_on'         => $vehicle->aux0StringOn,
            'aux_0_string_off'        => $vehicle->aux0StringOff,
            'aux_0_config_flags'      => $vehicle->aux0ConfigFlags,
            'aux_1_name'              => $vehicle->aux1Name,
            'aux_1_string_on'         => $vehicle->aux1StringOn,
            'aux_1_string_off'        => $vehicle->aux1StringOff,
            'aux_1_config_flags'      => $vehicle->aux1ConfigFlags,
            'aux_2_name'              => $vehicle->aux2Name,
            'aux_2_string_on'         => $vehicle->aux2StringOn,
            'aux_2_string_off'        => $vehicle->aux2StringOff,
            'aux_2_config_flags'      => $vehicle->aux2ConfigFlags,
            'aux_3_name'              => $vehicle->aux3Name,
            'aux_3_string_on'         => $vehicle->aux3StringOn,
            'aux_3_string_off'        => $vehicle->aux3StringOff,
            'aux_3_config_flags'      => $vehicle->aux3ConfigFlags,
            'g_sense'                 => $vehicle->canGSense(),
            'g_sense_number'          => $vehicle->fnlNum1,
            'garage_mode_begin'       => ($vehicle->garageModeBegin != null) ? strtotime($vehicle->garageModeBegin) : null,
            'garage_mode_end'         => ($vehicle->garageModeEnd != null) ? strtotime($vehicle->garageModeEnd) : null,
            'transport_mode_begin'    => ($vehicle->transportModeBegin) ? strtotime($vehicle->transportModeBegin) : null,
            'transport_mode_end'      => ($vehicle->transportModeEnd) ? strtotime($vehicle->transportModeEnd) : null,
            'no_alert_start'    => ($vehicle->noAlertStart) ? strtotime($vehicle->noAlertStart) : null,
            'no_alert_end'      => ($vehicle->noAlertEnd) ? strtotime($vehicle->noAlertEnd) : null,
            'ewm_enabled'             => PendingCommand::testEwmStatus($vehicle, $unitId),
            'sms_number'              => $vehicle->smsPhoneNum,
            'last_odo'                => carbon_timestamp($vehicle->lastOdo),
            'privacy_mode_enabled'    => $vehicle->privacyModeEnabled,
            'zero_speed_mode_enabled' => $vehicle->zeroSpeedModeEnabled,
            'battery_type'            => $vehicle->batteryType,
            'avgMpg'                  => $vehicle->avgMpg,
            'co2'                     => $vehicle->co2,
            'lastService'             => $vehicle->lastService,
            'smsAlertStatus'          => $vehicle->smsAlertStatus,
            'immobiliser'             => $vehicle->immobiliser(),
        ]);

        $this->withLinks([
            'self'     => customerRoute('vehicles.show', ['vehicleId' => $vehicle->vehicleId]),
            'journeys' => route('vehicles.show-journeys', ['vehicleId' => $vehicle->vehicleId]),
        ]);

        return $this->build();
    }

    /**
     * @param Vehicle $vehicle
     *
     * @return ResourceAbstract
     */
    public function includeCustomer(Vehicle $vehicle): ResourceAbstract
    {
        return $this->returnItem($vehicle->customer, CustomerTransformer::class);
    }

    /**
     * @param Vehicle $vehicle
     *
     * @return ResourceAbstract
     */
    public function includeUnit(Vehicle $vehicle): ResourceAbstract
    {
        if ($vehicle->unitId === 0) $vehicle->unit = null;

        return $this->returnItem($vehicle->unit, UnitTransformer::class);
    }

    /**
     * @param Vehicle $vehicle
     *
     * @return ResourceAbstract
     */
    public function includeGroups(Vehicle $vehicle): ResourceAbstract
    {
        return $this->returnCollection($vehicle->groups, VehicleGroupTransformer::class);
    }

    /**
     * @param Vehicle $vehicle
     *
     * @return ResourceAbstract
     */
    public function includeJourneys(Vehicle $vehicle): ResourceAbstract
    {
        return $this->returnCollection($vehicle->journeys, VehicleJourneyTransformer::class);
    }

    /**
     * @param Vehicle $vehicle
     *
     * @return ResourceAbstract
     */
    public function includePendingCommands(Vehicle $vehicle): ResourceAbstract
    {
        return $this->returnCollection($vehicle->pendingCommands, PendingCommandsTransformer::class);
    }

    /**
     * @param Vehicle $vehicle
     *
     * @return ResourceAbstract
     */
    public function includeLatestJourney(Vehicle $vehicle): ResourceAbstract
    {
        return $this->returnItem($vehicle->latestJourney, VehicleJourneyTransformer::class);
    }

    /**
     * @param Vehicle $vehicle
     *
     * @return ResourceAbstract
     */
    public function includeLatestPosition(Vehicle $vehicle): ResourceAbstract
    {
        return $this->returnItem($vehicle->latestPosition, VehiclePositionTransformer::class);
    }

    /**
     * @param Vehicle $vehicle
     *
     * @return ResourceAbstract
     */
    public function includeKenyaMeta(Vehicle $vehicle): ResourceAbstract
    {
        return $this->returnItem($vehicle->kenyaMeta, KenyaMetaTransformer::class);
    }

    /**
     * @param Vehicle $vehicle
     *
     * @return ResourceAbstract
     */
    public function includeCatType(Vehicle $vehicle): ResourceAbstract
    {
        return $this->returnItem($vehicle->catType, CatTypeTransformer::class);
    }
}
