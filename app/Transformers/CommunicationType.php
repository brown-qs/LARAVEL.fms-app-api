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

use App\Models\Customer;
use League\Fractal\Resource\ResourceAbstract;

/**
 * CommunicationType
 *
 * @package App\Transformers
 * @author
 */
class CommunicationType extends DefaultTransformer
{

    /**
     * @param CommunicationType $type
     *
     * @return array
     */
    public function transform(\App\Models\CommunicationType $type): array
    {
        return [
                            "name" => $type->name,
                            "active" => $type->active,
                            "hidden"=> $type->hidden,
        ];
    }
}
