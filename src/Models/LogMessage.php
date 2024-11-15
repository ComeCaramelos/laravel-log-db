<?php

namespace Yoeriboven\LaravelLogDb\Models;

use Illuminate\Database\Eloquent\Casts\AsArrayObject;
use Illuminate\Database\Eloquent\MassPrunable;
use Illuminate\Database\Eloquent\Model;

class LogMessage extends Model
{
    use MassPrunable;

    protected $fillable = [
        'level',
        'level_name',
        'message',
        'created_at',
        'context',
        'extra',
    ];

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    const UPDATED_AT = null;

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'context' => AsArrayObject::class,
        'extra' => AsArrayObject::class,
    ];

    public function getConnectionName(): string
    {
        return $this->connection ?: config('logging.channels.db.connection') ?: config('database.default');
    }

    /**
     * Get the prunable model query.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function prunable()
    {
        return static::where('created_at', '<=', now()->subDays(config('logging.channels.db.days')));
    }
}
