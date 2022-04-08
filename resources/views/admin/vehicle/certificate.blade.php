<html>
<head>
</head>
<body style="font-family:sans-serif; font-size:14px">
<div style="margin-left:auto; margin-right:auto">
    <div style="width:100%; padding-bottom: 5px;">
        @if($catType->logoType === \App\Models\CatType::LOGO_TYPE_TQA)
            <img style="float: right;max-height:80px;width:auto;display:block"
                 src="https://storage.googleapis.com/sa-certs/cert_tqa.jpg"/>
        @else
            <img style="float: right;max-height:80px;width:auto;display:block"
                 src="https://storage.googleapis.com/sa-certs/cert_tsc.png"/>
        @endif
    </div>

    <h1 style="float:left; position:relative;
font-size: 26px;
font-weight: bold;
font-family: sans-serif;">Installation Certificate</h1>

    <div style="position:relative;top:10px;clear:both;">
        <p>This is to certify that:</p>


        <table style="font-size: 18px">
            <tr>
                <td style="font-weight:bold; padding-right:20px">Vehicle Registration:
                </td>
                <td>
                    {{ $vehicle->registration  }}
                </td>
            </tr>

            <tr>
                <td style="font-weight:bold; padding-right:20px">Vehicle Make:
                </td>
                <td>
                    {{ $vehicle->make  }}
                </td>
            </tr>

            <tr>
                <td style="font-weight:bold; padding-right:20px">Vehicle Model:
                </td>
                <td>
                    {{ $vehicle->model  }}
                </td>
            </tr>

            <tr>
                <td style="font-weight:bold; padding-right:20px">VIN:
                </td>
                <td>
                    {{ $vehicle->vin  }}
                </td>
            </tr>


            <tr>
                <td style="font-weight:bold; padding-right:20px">Unit ID:
                </td>
                <td>
                    {{ $unit->unitId  }}
                </td>
            </tr>


            <tr>
                <td style="font-weight:bold; padding-right:20px">Unit Type:
                </td>
                <td>
                    {{ $unit->type  }}
                </td>
            </tr>
        </table>

        <p>Has been fitted with an insurance approved<br><br>
            <span style="font-size: 34px;font-weight: bold"> {{ $catType->productName }}  </span> <br><br>
            As designed and manufactured in the UK by Scorpion Automotive Ltd <br>
            Assessed and found to perform in compliance with<br><br>
            <span style="font-size: 34px;font-weight: bold"> {{ $catType->approvalStandardText }} </span> <br><br>
            Quote: Thatcham Security Certification (TSC) No. {{ $catType->approvalNo }}</p>
    </div>

    <div>
        <p style="font-weight:bold;padding-top:20px;">The System was fitted on behalf of:</p>

        <p>
            {{ $customer->company }} <br/>
            {{ $customer->address }} <br/>
            {!! !empty($customer->address2) ? $customer->address2 . "</br>"  : ""  !!}
            {{ $customer->postCode  }}
        </p>

        <table style="margin-top:10px; margin-bottom:10px; padding-right:30px; font-size: 18px;">
            <tr>
                <td style="font-weight:bold; padding-right:20px">Date issued:
                </td>
                <td>
                    {{ $subDateIssued }}
                </td>
            </tr>

            <tr>
                <td style="font-weight:bold;padding-right:20px">Date Of Expiry:
                </td>
                <td>
                    {{ $subDateExpires }}
                </td>
            </tr>
            <tr>
                <td style="font-weight:bold;padding-right:20px">Certificate Number:
                </td>
                <td>
                    {{ $vehicle->unitId }}
                </td>
            </tr>

            <div style="margin-top:10px; ">
                @if($brand !== \App\Models\Brand::BRAND_REWIRE)
                    <p>Signed on behalf of Scorpion Automotive Ltd</p>
                    <img src="https://storage.googleapis.com/sa-certs/md-sig.png" style="width:200px"/>
                    <p style="margin-top: 5px; ">Mark Downing<br/>Managing Director</p>
                @endif
            </div>

            @if($brand === \App\Models\Brand::BRAND_REWIRE)
                <img style="position: relative; top:-55px;  float:right; width:150px;"
                     src="https://storage.googleapis.com/sa-certs/rewire_logo.jpg"/>
            @endif
        </table>
    </div>
</div>
</body>
</html>
