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
class CatTypeTransformer extends DefaultTransformer
{
    /**
     * {@inheritDoc}
     */
    protected $availableIncludes = ['customer', 'driver', 'vehicle', 'positions'];

    /**
     *
     * @return array
     */
    public function transform($catTypeData): array
    {
        /*
            approvalNo
            logoType
            cat
            approvalStandardText
            productName
         */
        $this->withData([
            'approval_no'      => $catTypeData->approvalNo ?? false,
            'logo_type'      => $catTypeData->logoType ?? false,
            'cat'      => $catTypeData->cat ?? false,
            'approval_standard_text'      => $catTypeData->approvalStandardText ?? false,
            'product_name'      => $catTypeData->productName ?? false,
        ]);
        return $this->build();
    }
}
