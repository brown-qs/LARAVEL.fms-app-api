<?php

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

namespace App\Serializers;

use League\Fractal\Serializer\DataArraySerializer;

/**
 * Class DefaultSerializer
 * @package App\Serializers
 * @author  Miles Croxford <hello@milescroxford.com>
 */
class DefaultSerializer extends DataArraySerializer
{
    /**
     * @inheritdoc
     */
    public function collection($resourceKey, array $data)
    {
        return ['data' => $data];
    }

    /**
     * @inheritdoc
     */
    public function item($resourceKey, array $data)
    {
        return ['data' => $data];
    }

    /**
     * Serialize null resource.
     *
     * @return array
     */
    public function null()
    {
        return ['data' => null];
    }
}
