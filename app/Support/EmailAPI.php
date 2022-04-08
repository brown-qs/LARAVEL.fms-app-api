<?php declare(strict_types=1);

namespace App\Support;

use App\Models\Customer;
use App\Models\User;
use Exception;
use Firebase\JWT\JWT;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

/**
 * Class EmailAPI
 * @package App\Support
 */
class EmailAPI
{
    const BASIC_EMAIL_ADDRESS_CHANGE_ROUTE = "basic-email-address-change";
    const BASIC_PASSWORD_RECOVERY_ROUTE    = "basic-password-recovery";

    const ADMIN_NEW_ADMIN_ACCOUNT_ROUTE                  = "admin-new-admin-account";
    const ADMIN_NEW_CUSTOMER_NOTE_ROUTE                  = "admin-new-customer-note";
    const ADMIN_DRIVER_TAG_ORDER_DEALERSHIP_ROUTE        = "admin-driver-tag-order-dealership"; // NOTE THIS IS ACTUALLY A DEALERSHIP EMAIL, DON'T KNOW WHY IT WAS GROUPED IN WITH THE ADMIN EMAIL VIEWS
    const ADMIN_DRIVER_TAG_ORDER_WAREHOUSE_ROUTE         = "admin-driver-tag-order-warehouse";
    const ADMIN_UNIT_ORDER_DEALERSHIP_ROUTE              = "admin-unit-order-dealership";
    const ADMIN_UNIT_ORDER_DEALERSHIP_FOR_CUSTOMER_ROUTE = "admin-unit-order-dealership-for-customer";
    const ADMIN_UNIT_ORDER_WAREHOUSE_ROUTE               = "admin-unit-order-warehouse";
    const ADMIN_SCHEDULED_REPORT_ROUTE                   = "admin-scheduled-report";

    const CUSTOMER_CALENDAR_EVENT_REMINDER_ROUTE         = "customer-calendar-event-reminder";
    const CUSTOMER_CALENDAR_EVENT_POST_EVENT_ALERT_ROUTE = "customer-calendar-event-post-event-alert";
    const CUSTOMER_CONTACT_SUPPORT_ROUTE                 = "customer-contact-support";
    const CUSTOMER_EXPIRED_SUBSCRIPTIONS_ROUTE           = "customer-expired-subscriptions";
    const CUSTOMER_NEW_CUSTOMER_ROUTE                    = "customer-new-customer";
    const CUSTOMER_NEW_CUSTOMER_USER_ROUTE               = "customer-new-customer-user";
    const CUSTOMER_NEW_USER_NOTIFICATION_ROUTE           = "customer-new-user-notification";
    const CUSTOMER_NEW_DRIVER_NOTIFICATION_ROUTE         = "customer-new-driver-notification";
    const CUSTOMER_ALERT_TRIGGERED_NOTIFICATION_ROUTE    = "customer-alert-triggered-notification";
    const CUSTOMER_PAY_PAL_CANCEL_ROUTE                  = "customer-pay-pal-cancel";
    const CUSTOMER_PAY_PAL_SUBSCRIPTION_ROUTE            = "customer-pay-pal-subscription";
    const CUSTOMER_REAL_SAFE_SUBSCRIPTION_ROUTE          = "customer-real-safe-subscription";
    const CUSTOMER_SCHEDULED_REPORT_ROUTE                = "customer-scheduled-report";
    const CUSTOMER_SMS_TOP_UP_ROUTE                      = "customer-sms-top-up";
    const CUSTOMER_INSTALL_COMPLETE_ROUTE                = "customer-install-complete";
    const CUSTOMER_NEW_ACCOUNT_STEALTH                   = "customer-new-account-stealth";
    const CUSTOMER_INSTALL_COMPLETE_BMW                  = "customer-install-complete-bmw";
    const CUSTOMER_WELCOME_TO_DATATOOL                   = "customer-welcome-to-datatool";

    const DEALERSHIP_NEW_DEALERSHIP_ROUTE      = "dealership-new-dealership";
    const DEALERSHIP_NEW_DEALERSHIP_USER_ROUTE = "dealership-new-dealership-user";
    const DEALERSHIP_NEW_PENDING_DEALERSHIP = "dealership-new-pending-dealership";
    const DEALERSHIP_CONTACT_SUPPORT_ROUTE = "dealership-contact-support";

    const DRIVER_NEW_DRIVER_ROUTE = "driver-new-driver";

    const FITTER_VEHICLE_ASSIGNED_NOTIFICATION_ROUTE = "fitter-vehicle-assigned-notification";
    const FITTER_INSTALL_COMPLETE_ROUTE              = "fitter-install-complete";

    const ADMIN_NEW_CUSTOMER_VEHICLE_NOTE = "admin-new-customer-vehicle-note";
    const CUSTOMER_CERTIFICATE = "customer-certificate";


    /**
     * @var Client
     */
    private $http_client;

    /**
     * @var
     */
    private $auth_key;

    /**
     * @param Customer $customer
     * @param User $user
     */
    public function sendNewCustomerUserAccountCustomerNotificationEmail($customer, $user, $brand = 'fleet')
    {
        $recipients = [
            "to"  => [$customer->email],
        ];

        $data = [
            "user_name"           => $user->firstName . " " . $user->lastName,
            "customer_user_email" => $user->email,
            "customer_company"    => $customer->company,
        ];

        $this->makeEmailApiCall(self::CUSTOMER_NEW_USER_NOTIFICATION_ROUTE, $customer->brand, $recipients, $data);
    }

    /**
     * @param User $user
     */
    public function sendCustomerWelcomeToDatatool($user, $customer)
    {
        $recipients = [
            "to"  => [$user->email],
        ];

        $data = [
            "customer_company" => $user->firstName . " " . $user->lastName,
            "email"          => $user->email,
        ];

        $this->makeEmailApiCall(self::CUSTOMER_WELCOME_TO_DATATOOL, $customer->brand, $recipients, $data);
    }

    /**
     * @param User $user
     * @param $password
     * @param Customer $customer
     */
    public function sendNewAccountStealthEmail($user, $password, $customer)
    {
        $recipients = [
            "to"  => [$user->email],
        ];

        $data = [
            "customer_company" => $user->firstName . " " . $user->lastName,
            "email"            => $user->email,
            "password"         => $password,
            "login_link"       => "https://app.datatool.co.uk/",
        ];

        $brand = ($customer && $customer->brand) ? $customer->brand : 'fleet';

        $this->makeEmailApiCall(self::CUSTOMER_NEW_ACCOUNT_STEALTH, $brand, $recipients, $data);
    }

    public function customerCertificate(array $recipients, string $name, string $registration, string $file, string $brand = 'fleet')
    {
        $data = [
            'name' => $name,
            'registration' => $registration,
        ];

        $this->makeEmailApiCall(self::CUSTOMER_CERTIFICATE, $brand, $recipients, $data, [$file]);
    }

    public function sendAdminNewCustomerVehicleNote(
        array $recipients,
        string $customer_name,
        string $registration,
        int $vehicleId,
        string $replyEmail,
        string $note,
        string $brand)
    {
        $data = [
            "customer_name" => $customer_name,
            "user_email"    => $replyEmail,
            "registration"  => $registration,
            "vehicle_id"    => $vehicleId,
            "note"          => $note,
        ];

        $this->makeEmailApiCall(self::ADMIN_NEW_CUSTOMER_VEHICLE_NOTE, $brand, $recipients, $data);
    }


    /**
     * @param Customer $customer
     * @param User $user
     * @param $password
     * @param bool $sendSecondEmail
     */
    public function sendNewCustomerEmail($customer, $user, $password, $sendSecondEmail = true)
    {
        $recipients = [
            "to"  => [$customer->email],
        ];

        $data = [
            "customer_company"    => $customer->company,
            "customer_user_email" => $user->email,
        ];

        $this->makeEmailApiCall(self::CUSTOMER_NEW_CUSTOMER_ROUTE, $customer->brand, $recipients, $data);

        if ($sendSecondEmail) {
            $this->sendNewCustomerUserAccountEmail($customer, $user, $password);
        }
    }

    /**
     * @param Customer $company
     * @param User $user
     * @param string $password
     */
    public function sendNewCustomerUserAccountEmail($customer, $user, $password)
    {
        $recipients = [
            "to"  => [$user->email],
        ];

        $data = [
            "user_name" => $user->firstName . " " . $user->lastName,
            "company"   => $customer->company,
            "password"  => $password,
        ];

        $this->makeEmailApiCall(self::CUSTOMER_NEW_CUSTOMER_USER_ROUTE, $customer->brand, $recipients, $data);
    }


    /**
     * @param $vehicle
     * @param $reg
     * @param $fitter
     * @param $customer
     */
    public function sendFitterAssignedVehicle($vehicle, $reg, $fitter, $customer)
    {
        $recipients = [
            "to"  => [$fitter->email],
        ];

        $data = [
            "unit_id"          => $vehicle->unitId,
            "make"             => $vehicle->make,
            "model"            => $vehicle->model,
            "colour"           => $vehicle->colour,
            "name"             => $fitter->firstName . " " . $fitter->lastName,
            "customer_company" => $customer->company,
            "customer_email"   => $customer->email,
            "registration"     => $reg,
        ];

        $this->makeEmailApiCall(self::FITTER_VEHICLE_ASSIGNED_NOTIFICATION_ROUTE, $customer->brand, $recipients, $data);
    }


    /**
     * @param       $route
     * @param       $brand
     * @param       $recipients
     * @param       $data
     * @param array $attachments
     *
     * @return int
     */
    private function makeEmailApiCall($route, $brand, $recipients, $data, $attachments = [])
    {
        // Initialise client if it hasn't been created yet
        if ($this->http_client == null) {
            $this->initClient();
        }

        // Ensure only recipient is the test email on all environments other than 'production'
        if (env("APP_ENV") !== 'production') {

            // Reset Recipients
            $recipients = [
                "to" => [],
            ];

            $recipients["to"][] = getenv('TEST_MAIL');
        }

        // Generate the POST body
        $body = [
            'auth' => [$this->auth_key, ''],
        ];

        if (!empty($attachments)) {
            // Encode the simple stuff
            $body['multipart'] = [
                [
                    "name"     => "brand",
                    "contents" => $brand,
                ],
                [
                    "name"     => "locale",
                    "contents" => "en_GB",
                ],
            ];

            // Encode Recipients
            foreach ($recipients["to"] as $index => $recipient) {
                $body['multipart'][] = [
                    "name"     => "recipients[to][{$index}]",
                    "contents" => $recipient,
                ];
            }

            if (!empty($recipients["cc"])) {
                foreach ($recipients["cc"] as $index => $recipient) {
                    $body['multipart'][] = [
                        "name"     => "recipients[cc][{$index}]",
                        "contents" => $recipient,
                    ];
                }
            }

            if (!empty($recipients["bcc"])) {
                foreach ($recipients["bcc"] as $index => $recipient) {
                    $body['multipart'][] = [
                        "name"     => "recipients[bcc][{$index}]",
                        "contents" => $recipient,
                    ];
                }
            }

            // Encode data
            foreach ($data as $key => $value) {
                $body['multipart'][] = [
                    "name"     => "data[{$key}]",
                    "contents" => $value,
                ];
            }

            // Include attachments
            foreach ($attachments as $index => $attachment) {
                $body['multipart'][] = [
                    "name"     => "attachments[{$index}]",
                    "contents" => fopen($attachment, 'r'),
                ];
            }
        } else {
            $body['json'] = [
                'recipients' => $recipients,
                'data'       => $data,
                'brand'      => $brand,
                'locale'     => "en_GB",
            ];
        }

        try {

            $res = $this->http_client->request('POST', env('EMAIL_API_URL') . $route, $body);

        } catch (Exception $e) {
            Log::error("Email Failure : " . $e->getMessage());

            return 500;
        }

        $status = $res->getStatusCode();

        if ($status !== 200) {
            Log::info("Email API Response: " . $res->getBody() . PHP_EOL);
        }

        return $status;
    }

    /**
     *
     */
    private function initClient()
    {
        $data = [
            'payload' => [
                'service' => env('SCORPION_EMAIL_API_SERVICE_NAME'),
                'api_key' => crypt(env('SCORPION_EMAIL_API_PUBLIC_KEY'), env('SCORPION_EMAIL_API_PRIVATE_KEY')),
            ],
            "iat"     => time(),
            "exp"     => strtotime('+100 minute'),
        ];

        $this->auth_key    = JWT::encode($data, env('SCORPION_EMAIL_API_PRIVATE_KEY'));
        $this->http_client = new Client();
    }
}
