<?php
namespace App\Services;

class VTSService
{

    private static $paddingSize = 16;

    private static $offsets = [
        'vtsState' => 0,
        'immobiliserFitted' => -6,
        'immobiliserState' => -8,
        'immobiliserFeature' => -9,
        'immobiliserOverrideState' => -10,
        'wakeupInput' => -11,
        'driverIdSource' => -12,
        'dallasBuzzerSilence' => -14,
        'dallasBuzzer' => -15,
    ];

    private static $lengths = [
        'vtsState' => 2,
        'immobiliserFitted' => 1,
        'immobiliserState' => 2,
        'immobiliserFeature' => 1,
        'immobiliserOverrideState' => 2,
        'wakeupInput' => 1, //VTS Search
        'driverIdSource' => 2,
        'dallasBuzzerSilence' => 1,
        'dallasBuzzer' => 1,
    ];

    private static $states = [
        'vtsState' => [
            0 => "None",
            1 => "Unset",
            2 => "Set",
            3 => "Invalid",
        ],
        'immobiliserStates' => [
            0 => "Not Installed",
            1 => "Set Pending",
            2 => "Set",
            3 => "Unset",
        ],
        'immobiliserOverrideStates' => [
            0 => "Not Forced",
            1 => "Forced Set",
            2 => "Forced Unset",
            3 => "Reserved",
        ],
    ];
    private $setStates = [];

    private $setupOption;

    public function __construct($driverOptions)
    {
        $this->setStates['vtsState'] = self::getReadableState('vtsState', $driverOptions);
        $this->setStates['hasImmobiliser'] = self::isImmobiliserFitted($driverOptions);
        $this->setStates['immobiliserFeature'] = self::getStateByName('immobiliserFeature', $driverOptions);
        $this->setStates['wakeupInput'] = self::getStateByName('wakeupInput', $driverOptions);
        $this->setStates['driverIdSource'] = self::getStateByName('driverIdSource', $driverOptions);
        $this->setStates['dallasBuzzerSilence'] = self::getStateByName('dallasBuzzerSilence', $driverOptions);
        $this->setStates['dallasBuzzer'] = self::getStateByName('dallasBuzzer', $driverOptions);
        $this->setStates['immobiliserState'] = self::getImmobiliserState($driverOptions);
        $this->setStates['bits'] = self::decimalToBinary($driverOptions);
        $this->setupOption = self::getVTSSetupOption();

    }

    public function getState($name)
    {
        return $this->setStates[$name];
    }

    public function getAllStates()
    {
        return $this->setStates;
    }

    public function getSetupOption()
    {
        return $this->setupOption;
    }

    private function getVTSSetupOption()
    {
        $states = $this->getAllStates();

        //Most specific to least specific options so we don't get incorrect set
        switch (true) {
            case
                $states['dallasBuzzer'] !== 0 &&
                $states['dallasBuzzerSilence'] === 0 &&
                $states['wakeupInput'] !== 0:
                return '0411';
            case
                $states['dallasBuzzer'] !== 0 &&
                $states['wakeupInput'] !== 0:
                return '0410';
            case
                $states['dallasBuzzer'] !== 0 &&
                $states['dallasBuzzerSilence'] === 0:
                return '0401';
            case
                $states['dallasBuzzer'] !== 0:
                return '0400';
            case
                $states['hasImmobiliser'] === true &&
                $states['wakeupInput'] !== 0:
                return '0030';
            case
                $states['hasImmobiliser'] === true:
                return '0020';
            case
                $states['wakeupInput'] !== 0:
                return '0010';
            default:
                return '0000';
        }
    }

    private function decimalToBinary($driverOptions)
    {
        $state = decbin($driverOptions);
        $state = sprintf('%0' . self::$paddingSize . 's', $state);
        return $state;
    }

    private function getStateByName($name, $decimal)
    {
        $binary = self::decimalToBinary($decimal);
        return bindec(substr($binary, self::$offsets[$name], self::$lengths[$name]));
    }

    private function getReadableState($name, $binary)
    {
        $state = self::getStateByName($name, $binary);
        return self::$states[$name][$state];
    }

    private function getImmobiliserState($driverOptions)
    {
        $immobiliserState = self::getStateByName('immobiliserState', $driverOptions);
        $immobiliserOverrideState = self::getStateByName('immobiliserOverrideState', $driverOptions);

        return ($immobiliserOverrideState == 0) ? self::$states['immobiliserStates'][$immobiliserState] : self::$states['immobiliserOverrideStates'][$immobiliserOverrideState];
    }

    private function isImmobiliserFitted($driverOptions)
    {
        $isImmobiliserFitted = self::getStateByName('immobiliserFitted', $driverOptions);
        return $isImmobiliserFitted == 1;
    }
}
