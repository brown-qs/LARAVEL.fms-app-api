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

use App\Models\KenyaMeta;
use App\Models\VehiclePosition;
use League\Fractal\Resource\ResourceAbstract;

/**
 * KenyaMetaTransformer
 *
 * @package App\Transformers
 * @author
 */
class KenyaMetaTransformer extends DefaultTransformer
{
    /**
     * {@inheritDoc}
     */
    protected $availableIncludes = ['customer', 'driver', 'vehicle', 'positions'];

    /**
     *
     * @return array
     */
    public function transform(KenyaMeta $kenyaMeta): array
    {
        $this->withData([
            'owners_name'      => $kenyaMeta->meta1,
            'owners_id'      => $kenyaMeta->meta2,
            'owners_phone'      => $kenyaMeta->meta3,
            'vehicle_reg'      => $kenyaMeta->meta4,
            'chassis_no'      => $kenyaMeta->meta5,
            'make_and_type'      => $kenyaMeta->meta6,
            'certificate_no'      => $kenyaMeta->meta7,
            'limiter_type'      => $kenyaMeta->meta8,
            'limiter_serial'      => $kenyaMeta->meta9,
            'date_fitted'      => $kenyaMeta->meta10,
            'agent_name'      => $kenyaMeta->meta11,
            'agent_id'      => $kenyaMeta->meta12,
            'name_loc'      => $kenyaMeta->meta13,
            'agent_email'      => $kenyaMeta->meta14,
            'agent_phone'      => $kenyaMeta->meta15,
            'business_reg_no'      => $kenyaMeta->meta16,
        ]);
        return $this->build();
    }
}
