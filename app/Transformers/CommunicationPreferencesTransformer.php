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

use App\Models\CommunicationPreferences;
use App\Models\Vehicle;
use League\Fractal\Resource\ResourceAbstract;

/**
 * CommunicationPreferencesTransformer
 *
 * @package App\Transformers
 * @author
 */
class CommunicationPreferencesTransformer extends DefaultTransformer
{
    /**
     * {@inheritDoc}
     */
    protected $availableIncludes = ['communicationType'];

    /**
     * @param Vehicle $vehicle
     *
     * @return array
     */
    public function transform(CommunicationPreferences $preferences): array
    {
        $this->withData([
                    "preferencesId"=> $preferences->preferencesId,
                    "userId"=> $preferences->userId,
                    "typeId"=> $preferences->typeId,
                    "email"=> $preferences->email,
                    "sms"=> $preferences->sms,
                    "push" => $preferences->push,
        ]);

        return $this->build();
    }


    /**
     * @param Vehicle $vehicle
     *
     * @return ResourceAbstract
     */
    public function includeCommunicationType(CommunicationPreferences $preferences): ResourceAbstract
    {
        return $this->returnItem($preferences->communicationType, CommunicationType::class);
    }


}
