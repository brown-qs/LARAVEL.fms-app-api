<?php declare(strict_types=1);

/**
 * This file is part of the Scorpion API
 *
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

use App\Models\PendingCommand;
use App\Models\SoftTag;
use App\Models\Vehicle;
use App\Models\VtsTag;
use League\Fractal\Resource\ResourceAbstract;

/**
 * VtsTagTransformer
 *
 * @package App\Transformers
 */
class SoftTagTransformer extends DefaultTransformer
{
    /**
     * {@inheritDoc}
     */
    protected $availableIncludes = [];

    public function formatEndian($endian, $format = 'N') {
        $endian = intval($endian, 16);      // convert string to hex
        $endian = pack('L', $endian);       // pack hex to binary sting (unsinged long, machine byte order)
        $endian = unpack($format, $endian); // convert binary sting to specified endian format

        return sprintf("%'.08x", $endian[1]); // return endian as a hex string (with padding zero)
    }

    /**
     * @param VtsTag $vtsTag
     *
     * @return array
     */
    public function transform(SoftTag $softTag): array
    {
        $this->withData([
            'softTagId'      => $softTag->softTagId,
            'unitId'    => $softTag->unitId,
            'tagKey'     => strtoupper($this->formatEndian(dechex($softTag->tagKey), 'N')),
            'created'      => $softTag->created,
        ]);

        return $this->build();
    }
}
