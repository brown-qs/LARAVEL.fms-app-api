<?php declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * Class
 *
 * @package App\Models
 * @author
 */
class OTAUpgrade extends Model
{
    /**
     * {@inheritDoc}
     */
    public $timestamps = false;

    /**
     * {@inheritDoc}
     */
    protected $table = 'OTAUpgrade';

    /**
     * {@inheritDoc}
     */
    protected $primaryKey = 'id';


}
