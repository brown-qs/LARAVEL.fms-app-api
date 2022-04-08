<?php declare(strict_types=1);

/**
 * This file is part of the Scorpion API
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class SoftTag
 *
 * @package App\Models
 */
class SoftTag extends Model
{
    /**
     * {@inheritDoc}
     */
    public $timestamps = false;

    /**
     * {@inheritDoc}
     */
    public $incrementing = false;

    /**
     * {@inheritDoc}
     */
    protected $table = 'SoftTag';

    /**
     * {@inheritDoc}
     */
    protected $primaryKey = 'softTagId';

    /**
     * @return BelongsTo
     */
    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class, 'unitId', 'unitId');
    }


}
