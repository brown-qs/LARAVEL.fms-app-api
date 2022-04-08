<?php declare(strict_types=1);


namespace App\Transformers;


use App\Models\HealthCheck;
use App\Models\OTAUpgrade;

/**
 * Class OTAUpgradeTransformer
 *
 * @package App\Transformers
 * @author
 */
class OTAUpgradeTransformer extends DefaultTransformer
{
    /**
     * @param HealthCheck $healthCheck
     *
     * @return array
     */
    public function transform(OTAUpgrade $OTAUpgrade)
    {
        $this->withData([
            'fromVersion' => $OTAUpgrade->fromVersion,
            'toVersion' => $OTAUpgrade->toVersion,
            'model' => $OTAUpgrade->model,
            'vehicleId' => $OTAUpgrade->vehicleId,
            'unitId' => $OTAUpgrade->unitId,
            'checks' => $OTAUpgrade->checks,
        ]);

        return $this->build();
    }
}
