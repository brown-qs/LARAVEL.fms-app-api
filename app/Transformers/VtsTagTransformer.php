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
use App\Models\Vehicle;
use App\Models\VtsTag;
use League\Fractal\Resource\ResourceAbstract;

/**
 * VtsTagTransformer
 *
 * @package App\Transformers
 */
class VtsTagTransformer extends DefaultTransformer
{
    /**
     * {@inheritDoc}
     */
    protected $availableIncludes = [];

    /**
     * @param VtsTag $vtsTag
     *
     * @return array
     */
    public function transform(VtsTag $vtsTag): array
    {
        $this->withData([
            'vtsTag' => $vtsTag,
        ]);
        return $this->build();
    }
}
