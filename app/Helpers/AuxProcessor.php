<?php
namespace App\Helpers;

class AuxProcessor
{

    const BIT_0_OUTPUT = 0;                             //?????????0 -- GOOD
    const BIT_0_INPUT = 1;                              //?????????1 -- GOOD
//    const BIT_1_ANALOG = 2;
//    const BIT_2_ANALOG = 4;
//    const BIT_1_2_RISING = 0;                           //???????00? -- GOOD
//    const BIT_1_2_FALLING = 2;                          //???????01? -- GOOD
//    const BIT_1_2_CODED = 4;                            //???????10? -- GOOD
    const BIT_1_RISING = 2;
    const BIT_2_FALLING = 4;
    const BIT_1_2_RISING_FALLING = 6;
    const BIT_3_PULLUP = 8;                             //??????1??? -- GOOD
    const BIT_4_PULLDOWN = 16;                          //?????1???? -- GOOD
    const BIT_5_SMS = 32;                               //????1????? -- GOOD
    const BIT_6_ALERT = 64;                             //???1?????? -- GOOD
    const BIT_7_PORT = 128;                             //??1??????? -- GOOD
    const BIT_8_9_OUT_ON_PERM = 768;
    const BIT_8_9_OUT_ON_IGNON = 256;                   //?1???????? -- GOOD
    const BIT_8_9_OUT_ON_IGNOFF = 512;                  //1????????? -- GOOD
    const BIT_10_IO_NOT_AVAILABLE = 1024;
    const AUX_TYPE_INPUT = "Input";
    const AUX_TYPE_OUTPUT = "Output";
    const AUX_TYPE_DISABLED = "Disabled";
    const AUX_INPUT_CONFIG1_NONE = "None";
    const AUX_INPUT_CONFIG1_RISING = "Rising Edge";
    const AUX_INPUT_CONFIG1_FALLING = "Falling Edge";
    const AUX_INPUT_CONFIG1_RISING_AND_FALLING = "Rising/Falling Edge";
    //const AUX_INPUT_CONFIG1_CODED = "Coded Trigger";
    //const AUX_INPUT_CONFIG1_ANALOG = "Analog";
    const AUX_INPUT_CONFIG2_PULLUP = "Pull Up";
    const AUX_INPUT_CONFIG2_PULLDOWN = "Pull Down";
    const AUX_INPUT_CONFIG2_NEITHER = "Neither";
    const AUX_OUTPUT_CONFIG_ON_PERM = "On Permanently";

    //Comment for ticket number #64
    const AUX_OUTPUT_CONFIG_ON_IGNON = "On - IGN On";
    const AUX_OUTPUT_CONFIG_ON_IGNOFF = "On - IGN Off";
    const AUX_OUTPUT_CONFIG_OFF_PERM = "Off Permanently";
    const AUX_PORTDATA_CONFIG_ON = "On";
    const AUX_PORTDATA_CONFIG_OFF = "Off";

    private $CI;
    private $auxArray;

    function __construct()
    {
        $this->auxArray = [];
        $this->CI       = &get_instance();
    }

    public function process()
    {

    }

    //BIT #0 - Input or Output set

    public function isNeitherInput($configFlags)
    {
        if ($this->isInput($configFlags)) {
            if (!$this->isPullUpInput($configFlags) && !$this->isPullDownInput($configFlags)) {
                return true;
            }
        }

        return false;
    }

    //BIT #1 - Rising Edge Set

    public function isInput($configFlags)
    {
        if (($configFlags & AuxProcessor::BIT_0_INPUT) == AuxProcessor::BIT_0_INPUT) {
            return true;
        }

        return false;
    }

    //BIT #2 - Falling Edge Set

    public function isPullUpInput($configFlags)
    {
        if ($this->isInput($configFlags)) {
            if (($configFlags & AuxProcessor::BIT_3_PULLUP) == AuxProcessor::BIT_3_PULLUP) {
                return true;
            }
        }

        return false;
    }

    //BIT #1 and BIT #2 - Both Rising Edge AND Falling Edge Set

    public function isPullDownInput($configFlags)
    {
        if ($this->isInput($configFlags)) {
            if (($configFlags & AuxProcessor::BIT_4_PULLDOWN) == AuxProcessor::BIT_4_PULLDOWN) {
                return true;
            }
        }

        return false;
    }

    //BIT #3 and BIT #4 - Pull Up Set

    public function isOutputPermanentlyOff($configFlags)
    {
        if (!$this->isInput($configFlags)) {
            if (!$this->isOutputOnIgnOn($configFlags) && !$this->isOutputOnIgnOff($configFlags) && !$this->isOutputPermanentlyOn($configFlags)) {
                return true; //off 00
            }
        }

        return false;
    }

    //BIT #3 and BIT #4 - Pull Down Set

    public function isOutputOnIgnOn($configFlags)
    {
        if (!$this->isInput($configFlags)) {
            if (($configFlags & AuxProcessor::BIT_8_9_OUT_ON_IGNON) == AuxProcessor::BIT_8_9_OUT_ON_IGNON) {
                return true; //ign on 01
            }
        }

        return false;
    }

    //BIT #3 and BIT #4 - Neither Pull Up or Pull Down Set

    public function isOutputOnIgnOff($configFlags)
    {
        if (!$this->isInput($configFlags)) {
            if (($configFlags & AuxProcessor::BIT_8_9_OUT_ON_IGNOFF) == AuxProcessor::BIT_8_9_OUT_ON_IGNOFF && !$this->isOutputPermanentlyOn($configFlags)) {
                return true; //ign off 10
            }
        }

        return false;
    }

    //BIT #5 //TODO: SMS ALERT
    //BIT #6 //TODO: SVR ALERT
    //BIT #7 PORT DATA

    public function isOutputPermanentlyOn($configFlags)
    {

        if (!$this->isInput($configFlags)) {

            if (($configFlags & AuxProcessor::BIT_8_9_OUT_ON_PERM) == AuxProcessor::BIT_8_9_OUT_ON_PERM) {
                return true; //on 11
            }
        }

        return false;
    }

    //BIT #8 to BIT #9 - Output On When IGN On

    function getInputConfigurationHtml($vehicle, $type, $values)
    {
        /**
         * AUX 0 ARRAYS
         */
        //only input
        $AUX_0_TYPES = [
            AuxProcessor::AUX_TYPE_INPUT => AuxProcessor::AUX_TYPE_INPUT,
        ];

        //all input types, rising, falling, both, none
        $AUX_0_INPUT_CONFIG1 = [
            AuxProcessor::AUX_INPUT_CONFIG1_NONE => AuxProcessor::AUX_INPUT_CONFIG1_NONE,
        ];

        //only pull up
        $AUX_0_INPUT_CONFIG2 = [
            AuxProcessor::AUX_INPUT_CONFIG2_PULLUP => AuxProcessor::AUX_INPUT_CONFIG2_PULLUP,
        ];

        //not used by aux 0, used by hidden... (hmmmmm)
        $AUX_0_OUTPUT_CONFIG = [
            AuxProcessor::AUX_OUTPUT_CONFIG_OFF_PERM => AuxProcessor::AUX_OUTPUT_CONFIG_OFF_PERM,
            //Comment for ticket number #64
            /* AuxProcessor::AUX_OUTPUT_CONFIG_ON_IGNON => AuxProcessor::AUX_OUTPUT_CONFIG_ON_IGNON,
            AuxProcessor::AUX_OUTPUT_CONFIG_ON_IGNOFF => AuxProcessor::AUX_OUTPUT_CONFIG_ON_IGNOFF, */
            AuxProcessor::AUX_OUTPUT_CONFIG_ON_PERM  => AuxProcessor::AUX_OUTPUT_CONFIG_ON_PERM,
        ];

        //all types, on, off
        $AUX_0_PORTDATA_CONFIG = [
            AuxProcessor::AUX_PORTDATA_CONFIG_OFF => AuxProcessor::AUX_PORTDATA_CONFIG_OFF,
            AuxProcessor::AUX_PORTDATA_CONFIG_ON  => AuxProcessor::AUX_PORTDATA_CONFIG_ON,
        ];

        /*
         * AUX 1 ARRAYS
         */
        //all types, input, output
        $AUX_1_TYPES = [
            //AuxProcessor::AUX_TYPE_DISABLED => AuxProcessor::AUX_TYPE_DISABLED,
            AuxProcessor::AUX_TYPE_OUTPUT => AuxProcessor::AUX_TYPE_OUTPUT,
        ];

        //all input types, rising, falling, both, none // These will be enabled on a future software release
        $AUX_1_INPUT_CONFIG1 = [
            AuxProcessor::AUX_INPUT_CONFIG1_NONE => AuxProcessor::AUX_INPUT_CONFIG1_NONE,
            //            AuxProcessor::AUX_INPUT_CONFIG1_RISING => AuxProcessor::AUX_INPUT_CONFIG1_RISING . "  (+VE)",
            //            AuxProcessor::AUX_INPUT_CONFIG1_FALLING => AuxProcessor::AUX_INPUT_CONFIG1_FALLING . "  (-VE)",
            //            AuxProcessor::AUX_INPUT_CONFIG1_RISING_AND_FALLING => AuxProcessor::AUX_INPUT_CONFIG1_RISING_AND_FALLING . "  (+VE) or (-VE)",
        ];

        //all types, neither, pull up, pull down
        $AUX_1_INPUT_CONFIG2 = [
            AuxProcessor::AUX_INPUT_CONFIG2_NEITHER  => AuxProcessor::AUX_INPUT_CONFIG2_NEITHER,
            AuxProcessor::AUX_INPUT_CONFIG2_PULLDOWN => AuxProcessor::AUX_INPUT_CONFIG2_PULLDOWN,
            AuxProcessor::AUX_INPUT_CONFIG2_PULLUP   => AuxProcessor::AUX_INPUT_CONFIG2_PULLUP,
        ];

        //all types, off perm, onignon, onignoff, on perm
        $AUX_1_OUTPUT_CONFIG = [
            AuxProcessor::AUX_OUTPUT_CONFIG_OFF_PERM => AuxProcessor::AUX_OUTPUT_CONFIG_OFF_PERM,
            //Comment for ticket number #64
            /* AuxProcessor::AUX_OUTPUT_CONFIG_ON_IGNON => AuxProcessor::AUX_OUTPUT_CONFIG_ON_IGNON,
            AuxProcessor::AUX_OUTPUT_CONFIG_ON_IGNOFF => AuxProcessor::AUX_OUTPUT_CONFIG_ON_IGNOFF, */
            AuxProcessor::AUX_OUTPUT_CONFIG_ON_PERM  => AuxProcessor::AUX_OUTPUT_CONFIG_ON_PERM,
        ];

        //all types, on, off
        $AUX_1_PORTDATA_CONFIG = [
            AuxProcessor::AUX_PORTDATA_CONFIG_OFF => AuxProcessor::AUX_PORTDATA_CONFIG_OFF,
            AuxProcessor::AUX_PORTDATA_CONFIG_ON  => AuxProcessor::AUX_PORTDATA_CONFIG_ON,
        ];

        /**
         * AUX 2 ARRAYS
         */
        //all types, input, output
        $AUX_2_TYPES = [
            AuxProcessor::AUX_TYPE_INPUT  => AuxProcessor::AUX_TYPE_INPUT,
            AuxProcessor::AUX_TYPE_OUTPUT => AuxProcessor::AUX_TYPE_OUTPUT,
        ];

        //all input types, rising, falling, both, none
        $AUX_2_INPUT_CONFIG1 = [
            AuxProcessor::AUX_INPUT_CONFIG1_NONE               => AuxProcessor::AUX_INPUT_CONFIG1_NONE,
            AuxProcessor::AUX_INPUT_CONFIG1_RISING             => AuxProcessor::AUX_INPUT_CONFIG1_RISING,
            AuxProcessor::AUX_INPUT_CONFIG1_FALLING            => AuxProcessor::AUX_INPUT_CONFIG1_FALLING,
            AuxProcessor::AUX_INPUT_CONFIG1_RISING_AND_FALLING => AuxProcessor::AUX_INPUT_CONFIG1_RISING_AND_FALLING,
        ];

        //only neither
        $AUX_2_INPUT_CONFIG2 = [
            AuxProcessor::AUX_INPUT_CONFIG2_NEITHER => AuxProcessor::AUX_INPUT_CONFIG2_NEITHER,
        ];

        //all output types, off perm, onignon, onignoff, on perm
        $AUX_2_OUTPUT_CONFIG = [
            AuxProcessor::AUX_OUTPUT_CONFIG_OFF_PERM => AuxProcessor::AUX_OUTPUT_CONFIG_OFF_PERM,
            //AuxProcessor::AUX_OUTPUT_CONFIG_ON_IGNON => AuxProcessor::AUX_OUTPUT_CONFIG_ON_IGNON,
            //AuxProcessor::AUX_OUTPUT_CONFIG_ON_IGNOFF => AuxProcessor::AUX_OUTPUT_CONFIG_ON_IGNOFF,
            AuxProcessor::AUX_OUTPUT_CONFIG_ON_PERM  => AuxProcessor::AUX_OUTPUT_CONFIG_ON_PERM,
        ];

        //all types, on, off
        $AUX_2_PORTDATA_CONFIG = [
            AuxProcessor::AUX_PORTDATA_CONFIG_OFF => AuxProcessor::AUX_PORTDATA_CONFIG_OFF,
            AuxProcessor::AUX_PORTDATA_CONFIG_ON  => AuxProcessor::AUX_PORTDATA_CONFIG_ON,
        ];


        $HELPTEXT_NAME = "Auxiliary";

        //TABLE HEAD
        $html = '<div>';
        if ($type == "Edit" || $type == "EditCustomer") {
            $html .= '<table class="blacktable auxtable">';
        } else {
            $html .= '<table class="blacktable auxtable_view">';
        }
        $html .= '<tr>';
        //AUX
        $html .= '<th><span class="vertical_align">Aux</span></th>';
        //TYPE
        if ($type == "Edit") {
            $html .= '<th><span class="vertical_align">Type</span><br />' . constructAuxHelpImage($HELPTEXT_NAME,
                    "type") . '</th>';
        } else {
            $html .= '<th>Type</th>';
        }
        //NAME
        if ($type == "Edit" || $type == "EditCustomer") {
            $html .= '<th><span class="vertical_align">Name</span><br />' . constructAuxHelpImage($HELPTEXT_NAME,
                    "name") . '</th>';
        } else {
            $html .= '<th>Name</th>';
        }
        //HIGH
        if ($type == "Edit" || $type == "EditCustomer") {
            $html .= '<th><span class="vertical_align">Status Text: High (+ve) </span><br />' . constructAuxHelpImage($HELPTEXT_NAME,
                    "status_high") . '</th>';
        } else {
            $html .= '<th><span class="vertical_align">Status Text: High (+ve) </span></th>';
        }
        //LOW
        if ($type == "Edit" || $type == "EditCustomer") {
            $html .= '<th><span class="vertical_align">Status Text: Low (-ve)</span><br />' . constructAuxHelpImage($HELPTEXT_NAME,
                    "status_low") . '</th>';
        } else {
            $html .= '<th><span class="vertical_align">Status Text: Low (-ve)</span></th>';
        }
        //CONFIG 1
        if ($type == "Edit") {
            $html .= '<th><span class="vertical_align">Config 1</span><br />' . constructAuxHelpImage($HELPTEXT_NAME,
                    "config_one") . '</th>';
        } else {
            $html .= '<th><span class="vertical_align">Config 1</span></th>';
        }
        //CONFIG 2
        $html .= '<th><span class="vertical_align">Config 2</span></th>';
        //PORT DATA
        if ($type == "Edit") {
            $html .= '<th><span class="vertical_align">Port Data</span><br />' . constructAuxHelpImage($HELPTEXT_NAME,
                    "portdata") . '</th>';
        } else {
            $html .= '<th><span class="vertical_align">Port Data</span></th>';
        }
        $html .= '</tr>';

        //BEGIN AUX ROW PER AUX
        for ($i = 0; $i < MAX_INPUTS; $i++) {
            //db string variables (each aux - variable variable)
            $auxNameField      = "aux" . $i . "Name";
            $auxStringOnField  = "aux" . $i . "StringOn";
            $auxStringOffField = "aux" . $i . "StringOff";

            $aux_i_ConfigFlags = $values[$i];

            //aux specific dropdowns (drop downs above for each aux - variable variable)
            $aux_i_TypesField          = "AUX_" . $i . "_TYPES";
            $aux_i_InputConfig1Field   = "AUX_" . $i . "_INPUT_CONFIG1";
            $aux_i_InputConfig2Field   = "AUX_" . $i . "_INPUT_CONFIG2";
            $aux_i_OutputConfigField   = "AUX_" . $i . "_OUTPUT_CONFIG";
            $aux_i_PortDataConfigField = "AUX_" . $i . "_PORTDATA_CONFIG";

            $html .= "<tr>";
            $html .= "<th style='width:25px;'> AUX " . $i;
            if ($i == 0) {
                $html .= " <span style='color:blue' > (Blue) </th>";
            } else {
                $html .= " <span style='color:brown' > (Brown) </th>";
            }


            $html .= "</th>";

            //TYPE COLUMN
            $html .= "<td>";
            if ($type == "Edit" && !$this->isIOUnavailable($aux_i_ConfigFlags)) {
                //edting and IO is available so show aux type field
                $html .= form_dropdown("fm-aux" . $i . "Type", $$aux_i_TypesField,
                    $this->_getAuxType($aux_i_ConfigFlags),
                    ' onchange="rebuildAuxConfigForm(' . $i . ',this.value)"  style="width:70px;" ');
            } else {
                if ($this->isIOUnavailable($aux_i_ConfigFlags)) {
                    $html .= "Unavailable";
                } else //not editing so just show type value
                {
                    $html .= $this->_getAuxType($aux_i_ConfigFlags);
                }
            }
            $html .= "</td>";

            //NAME COLUMN
            $html .= "<td>";
            if ($type == "Edit" || $type == "EditCustomer") //&& !$this->isIOUnavailable($aux_i_ConfigFlags)
            {
                //edting and IO is available so show string name input field
                //empty check is used to default the box if its empty for dealers editing or creating vehicles
                $html .= form_input("fm-aux" . $i . "Name", set_value('fm-aux' . $i . 'Name',
                    (empty($vehicle->$auxNameField) ? "Aux $i" : $vehicle->$auxNameField)), ' class="stringConfig" ');
            } else {
                //not editing so just show name value
                $html .= $vehicle->$auxNameField;
            }
            $html .= "</td>";

            //STRING ON COLUMN
            $html .= "<td>";
            if ($type == "Edit" || $type == "EditCustomer") //&& !$this->isIOUnavailable($aux_i_ConfigFlags)
            {

                //edting and IO is available so show string on input field
                $html .= form_input("fm-aux" . $i . "StringOn", set_value('fm-aux' . $i . 'StringOn',
                    (empty($vehicle->$auxStringOnField) ? "ON" : $vehicle->$auxStringOnField)),
                    ' class="stringConfig" ');
            } else {
                //not editing so just show string on value
                $html .= $vehicle->$auxStringOnField;
            }
            $html .= "</td>";

            //STRING OFF COLUMN
            $html .= "<td>";
            if ($type == "Edit" || $type == "EditCustomer")//&& !$this->isIOUnavailable($aux_i_ConfigFlags)
            {
                //edting and IO is available so show string off input field
                $html .= form_input("fm-aux" . $i . "StringOff", set_value('fm-aux' . $i . 'StringOff',
                    (empty($vehicle->$auxStringOffField) ? "OFF" : $vehicle->$auxStringOffField)),
                    ' class="stringConfig" ');
            } else {
                //not editing so just show string off value
                $html .= $vehicle->$auxStringOffField;
            }
            $html .= "</td>";

            //----------------------------------------------
            //DYNAMIC FIELDS BASED ON EITHER INPUT OR OUTPUT
            //CONFIG FLAGS ARE SET TO OUTPUT
            if ($this->_getAuxType($aux_i_ConfigFlags) == AuxProcessor::AUX_TYPE_OUTPUT) {
                //CONFIG 1 COLUMN: OUTPUT
                $html .= "<td>";
                if ($type == "Edit" && !$this->isIOUnavailable($aux_i_ConfigFlags)) {
                    //actual output config
                    $html .= form_dropdown("fm-aux" . $i . "OutputConfig", $$aux_i_OutputConfigField,
                        $this->_getOutputConfig($aux_i_ConfigFlags),
                        ' id="aux' . $i . 'OutputConfig" class="column1Config" ');

                    //hidden input config 1 (show when type is changed from output to input - input has different config 1)
                    $html .= form_dropdown("fm-aux" . $i . "InputConfig1", $$aux_i_InputConfig1Field,
                        $this->_getInputConfig1($aux_i_ConfigFlags),
                        ' id="aux' . $i . 'InputConfig1" class="column1Config" disabled="disabled" style="display:none;" ');
                } else {
                    //not editing so just output value of output config
                    $html .= $this->_getOutputConfig($aux_i_ConfigFlags);
                }
                $html .= "</td>";

                //CONFIG 2 COLUMN: OUTPUT
                $html .= "<td>";
                if ($type == "Edit" && !$this->isIOUnavailable($aux_i_ConfigFlags)) {
                    //hidden input config 2 (show when type is changed from output to input - input has config 2)
                    $html .= form_dropdown("fm-aux" . $i . "InputConfig2", $$aux_i_InputConfig2Field,
                        $this->_getInputConfig2($aux_i_ConfigFlags),
                        ' id="aux' . $i . 'InputConfig2" disabled="disabled" class="column2Config" style="display:none;" ');
                }
                //always show NA here (edit+view) as output does not have config 2
                $html .= "<span id='aux" . $i . "Config2-NA'>N/A</span>";
                $html .= "</td>";

                //PORT DATA COLUMN: OUTPUT
                $html .= "<td>";
                if ($type == "Edit" && !$this->isIOUnavailable($aux_i_ConfigFlags)) {
                    //actual port data
                    $html .= form_dropdown("fm-aux" . $i . "PortDataConfig", $$aux_i_PortDataConfigField,
                        $this->_getPortDataConfig($aux_i_ConfigFlags),
                        ' id="aux' . $i . 'PortDataConfig" class="portDataConfig" ');
                } else {
                    //not editing so just output value of portdata
                    $html .= $this->_getPortDataConfig($aux_i_ConfigFlags);
                }

                $html .= "</td>";
            } //CONFIG FLAGS ARE SET TO INPUT
            else {
                if ($this->_getAuxType($aux_i_ConfigFlags) == AuxProcessor::AUX_TYPE_INPUT) {
                    //CONFIG 1 COLUMN: INPUT
                    $html .= "<td>";
                    if ($type == "Edit" && !$this->isIOUnavailable($aux_i_ConfigFlags)) {
                        //actual input config 1
                        $html .= form_dropdown("fm-aux" . $i . "InputConfig1", $$aux_i_InputConfig1Field,
                            $this->_getInputConfig1($aux_i_ConfigFlags),
                            ' id="aux' . $i . 'InputConfig1" class="column1Config" ');

                        //hidden output config (show when type is changed from input to output - output has different config1)
                        $html .= form_dropdown("fm-aux" . $i . "OutputConfig", $$aux_i_OutputConfigField,
                            $this->_getOutputConfig($aux_i_ConfigFlags),
                            ' id="aux' . $i . 'OutputConfig" class="column1Config" disabled="disabled" style="display:none;" ');
                    } else {
                        //not editing so just output value of input config1
                        $html .= $this->_getInputConfig1($aux_i_ConfigFlags);
                    }
                    $html .= "</td>";

                    //CONFIG 2 COLUMN: INPUT
                    $html .= "<td>";
                    if ($type == "Edit" && !$this->isIOUnavailable($aux_i_ConfigFlags)) {
                        //actual input config 2
                        $html .= form_dropdown("fm-aux" . $i . "InputConfig2", $$aux_i_InputConfig2Field,
                            $this->_getInputConfig2($aux_i_ConfigFlags),
                            ' id="aux' . $i . 'InputConfig2" class="column2Config" ');

                        //hidden NA (show when type is changed from input to output - output has no config2)
                        $html .= "<span style='display:none' id='aux" . $i . "Config2-NA'>N/A</span>";
                    } else {
                        //not editing so just output value of input config2
                        $html .= $this->_getInputConfig2($aux_i_ConfigFlags);
                    }
                    $html .= "</td>";

                    //PORT DATA COLUMN
                    $html .= "<td>";
                    if ($type == "Edit" && !$this->isIOUnavailable($aux_i_ConfigFlags)) {
                        //actual port data
                        $html .= form_dropdown("fm-aux" . $i . "PortDataConfig", $$aux_i_PortDataConfigField,
                            $this->_getPortDataConfig($aux_i_ConfigFlags),
                            ' id="aux' . $i . 'PortDataConfig" class="portDataConfig" ');
                    } else {
                        //not editing so just output value of portdata
                        $html .= $this->_getPortDataConfig($aux_i_ConfigFlags);
                    }
                    $html .= "</td>";
                }
            }

            $html .= "</tr>";
        }

        $html .= '</table>';
        $html .= '</div>';

        return $html;
    }

    //BIT #8 to BIT #9 - Ouput On When IGN Off

    public function isIOUnavailable($configFlags)
    {
        if (($configFlags & AuxProcessor::BIT_10_IO_NOT_AVAILABLE) == AuxProcessor::BIT_10_IO_NOT_AVAILABLE) {
            return true;
        }

        return false;
    }

    //BIT #8 to BIT #9 - Output PERM On

    public function _getAuxType($configFlags)
    {
        if ($this->isInput($configFlags)) {
            return AuxProcessor::AUX_TYPE_INPUT;
        } else {
            return AuxProcessor::AUX_TYPE_OUTPUT;
        }
    }

    //BIT #8 to BIT #9 - Output PERM OFF

    public function _getOutputConfig($configFlags)
    {
        if ($this->isOutputPermanentlyOn($configFlags)) {
            return AuxProcessor::AUX_OUTPUT_CONFIG_ON_PERM;
        } else {
            if ($this->isOutputOnIgnOn($configFlags)) {
                return AuxProcessor::AUX_OUTPUT_CONFIG_ON_IGNON;
            } else {
                if ($this->isOutputOnIgnOff($configFlags)) {
                    return AuxProcessor::AUX_OUTPUT_CONFIG_ON_IGNOFF;
                } else {
                    return AuxProcessor::AUX_OUTPUT_CONFIG_OFF_PERM;
                }
            }
        }
    }

    //BIT #10 - IO Available

    private function _getInputConfig1($configFlags)
    {


        if ($this->isRisingAndFallingInput($configFlags)) {
            return AuxProcessor::AUX_INPUT_CONFIG1_RISING_AND_FALLING;
        } else {
            if ($this->isRisingInput($configFlags)) {
                return AuxProcessor::AUX_INPUT_CONFIG1_RISING;
            } else {
                if ($this->isFallingInput($configFlags)) {
                    return AuxProcessor::AUX_INPUT_CONFIG1_FALLING;
                } else {
                    return AuxProcessor::AUX_INPUT_CONFIG1_NONE;
                }
            }
        }
    }

    //returns textual description of type (input/output/disabled)

    public function isRisingAndFallingInput($configFlags)
    {
        if ($this->isInput($configFlags)) {
            if (($configFlags & AuxProcessor::BIT_1_2_RISING_FALLING) == AuxProcessor::BIT_1_2_RISING_FALLING) {
                return true;
            }
        }

        return false;
    }

    //returns textual description of output config (on perm, on ign on, on ign off, off perm)

    public function isRisingInput($configFlags)
    {
        if ($this->isInput($configFlags)) {
            if (($configFlags & AuxProcessor::BIT_1_RISING) == AuxProcessor::BIT_1_RISING && !$this->isRisingAndFallingInput($configFlags)) {
                return true;
            }
        }

        return false;
    }

    //returns textual description of input config 1 (rising and falling, rising, falling, none)

    public function isFallingInput($configFlags)
    {
        if ($this->isInput($configFlags)) {
            if (($configFlags & AuxProcessor::BIT_2_FALLING) == AuxProcessor::BIT_2_FALLING && !$this->isRisingAndFallingInput($configFlags)) {
                return true;
            }
        }

        return false;
    }

    //returns textual description of input config 1 (pull up, pull down, neither,)

    private function _getInputConfig2($configFlags)
    {
        if ($this->isPullUpInput($configFlags)) {
            return AuxProcessor::AUX_INPUT_CONFIG2_PULLUP;
        } else {
            if ($this->isPullDownInput($configFlags)) {
                return AuxProcessor::AUX_INPUT_CONFIG2_PULLDOWN;
            } else {
                return AuxProcessor::AUX_INPUT_CONFIG2_NEITHER;
            }
        }
    }

    //returns textual description of port data config (on, off)
    private function _getPortDataConfig($configFlags)
    {
        if ($this->isPortDataOn($configFlags)) {
            return AuxProcessor::AUX_PORTDATA_CONFIG_ON;
        } else {
            return AuxProcessor::AUX_PORTDATA_CONFIG_OFF;
        }
    }

    //returns either form or table with configs

    public function isPortDataOn($configFlags)
    {
        if (($configFlags & AuxProcessor::BIT_7_PORT) == AuxProcessor::BIT_7_PORT) {
            return true;
        }

        return false;
    }

    public function changeOutput($configFlags, $output)
    {
        //first we need to remove any flags we currently have
        // Could've also done $configFlags = $configFlags - ( $configFlags & AuxProcessor::BIT_8_9_OUT_ON_PERM)
//        if ($this->isOutputPermanentlyOn($configFlags))
//        {
//            $configFlags = $configFlags - AuxProcessor::BIT_8_9_OUT_ON_PERM;
//        }
//        else if ($this->isOutputOnIgnOn($configFlags))
//        {
//            $configFlags = $configFlags - AuxProcessor::BIT_8_9_OUT_ON_IGNON;
//        }
//        else if ($this->isOutputOnIgnOff($configFlags))
//        {
//            $configFlags = $configFlags - AuxProcessor::BIT_8_9_OUT_ON_IGNOFF;
//        }
        //remove any output currently on
        $configFlags = $configFlags - ($configFlags & AuxProcessor::BIT_8_9_OUT_ON_PERM);

        if ($output == AuxProcessor::AUX_OUTPUT_CONFIG_ON_PERM) {
            return $configFlags + AuxProcessor::BIT_8_9_OUT_ON_PERM;
        } //Comment for ticket number #64
        elseif ($output == AuxProcessor::AUX_OUTPUT_CONFIG_ON_IGNON) {
            return $configFlags + AuxProcessor::BIT_8_9_OUT_ON_IGNON;
        } elseif ($output == AuxProcessor::AUX_OUTPUT_CONFIG_ON_IGNOFF) {
            return $configFlags + AuxProcessor::BIT_8_9_OUT_ON_IGNOFF;
        }

        //add on rising and falling triggers so unit always sends EVENT on rising all falling change
        //$configFlags |= AuxProcessor::BIT_1_RISING;
        //$configFlags |= AuxProcessor::BIT_2_FALLING;

        return $configFlags;
    }

}
