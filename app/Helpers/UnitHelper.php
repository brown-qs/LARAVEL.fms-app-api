<?php
if (!function_exists('getInternalUnitID')) {
    /**
     * @param int $unitId
     * @return int
     */
    function getInternalUnitID(string $unitId): int
    {
        if (strlen($unitId) > 9 && strlen($unitId) < 11) {
            $unitId = substr($unitId, 4);

            while ($unitId[0] == 0) {
                $unitId = substr($unitId, 1);
            }
        }

        if (strlen($unitId) === 12) {
            //Full length of an STM unit, substr the last 6 digits
            $unitId = substr($unitId, 6);
        }

        return (int)$unitId;
    }
}
