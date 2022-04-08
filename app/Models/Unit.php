<?php declare(strict_types=1);

namespace App\Models;

use App\Services\VTSService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Support\Facades\DB;

/**
 * Class Unit
 *
 * @package App\Models
 * @author  Miles Croxford <hello@milescroxford.com>
 */
class Unit extends Model
{
    public const TYPE_ST50    = "ST50";
    public const TYPE_ST50MT  = "ST50MT";
    public const TYPE_ST51    = "ST51";
    public const TYPE_ST52    = "ST52";
    public const TYPE_ST55    = "ST55";
    public const TYPE_ST60    = "ST60";
    public const TYPE_ST61    = "ST61";
    public const TYPE_ST62    = "ST62";
    public const TYPE_ST63    = "ST63";
    public const TYPE_ST65    = "ST65";
    public const TYPE_ST80    = "ST80";
    public const TYPE_UNKNOWN = "UNKNOWN";
    public const TYPE_STX70   = "STX70";
    public const TYPE_STX71   = "STX71";
    public const TYPE_STX71F  = "STX71F";
    public const TYPE_ST71X   = "ST71X";
    public const TYPE_ST70    = "ST70";
    public const TYPE_STX50   = "STX50";
    public const TYPE_STX61   = "STX61";
    public const TYPE_ST71    = "ST71";

    /**
     * {@inheritDoc}
     */
    public $timestamps = false;

    /**
     * {@inheritDoc}
     */
    protected $primaryKey = 'unitId';

    /**
     * {@inheritDoc}
     */
    protected $table = 'Unit';

    /**
     * @return BelongsTo
     */
    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class, 'unitId', 'unitId');
    }

    /**
     * @return BelongsTo
     */
    public function subscription(): BelongsTo
    {
        return $this->belongsTo(Subscription::class, 'unitId', 'unitId');
    }


    /**
     * @return BelongsToMany
     */
    public function vtsTags()
    {
        return $this->belongsToMany(VtsTag::class, 'UnitToVTSTag', 'unitId', 'vtsId', null, 'vtsId');
    }

    /**
     * Check to determine whether a unit is capable of g-sense.
     *
     * @return bool
     */
    public function canGSense()
    {
        return $this->type === 'STX71' || $this->type === 'STX71F';
    }

    public function assignUnitToCustomer($unit, $customer)
    {
        // Check if unit is already assigned

        $assignedUnit = DB::table('AssignedUnits')
            ->where('unitId', $unit->unitId)
            ->where('customerId', $customer->customerId)
            ->first();
            ;

        if (!$assignedUnit) {
            // Assign the unit to the customer if it isn't
            DB::table('AssignedUnits')
                ->insert([
                    'customerId' => $customer->customerId,
                    'unitId' => $unit->unitId
                ]);
        }

        DB::table('AssignedUnitsDealership')
            ->where('unitId', $unit->unitId)
            ->delete();

        DB::table('Unit')
            ->where('unitId', $unit->unitId)
            ->update(['stock' => 0])
        ;

    }

    public function checkSoftFobReadiness($unitId)
    {
        $vehicleUnit = Unit::select(['Unit.*', 'Vehicle.state', 'Vehicle.driverOptions'])->join('Vehicle', 'Vehicle.unitId', '=' ,'Unit.unitId')->where('Unit.unitId', $unitId)->first();

        if (strpos($vehicleUnit->type, 'STM') === false) {
            return 'Unit must be an STM';
        }

        if ($vehicleUnit->state !== 'UNSET') {
            return 'Unit state must be unset';
        }

        $vts = new VTSService($vehicleUnit->driverOptions);
        if ($vts->getState('vtsState') !== 'Unset') {
            return 'VTS state must be unset';
        }

        if ($vehicleUnit->vtsTags->count() < 1) {
            return 'At least one VTS tag must be on this unit';
        }

        return true;



    }
}
