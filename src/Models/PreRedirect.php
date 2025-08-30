<?php

namespace Newnet\Seo\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

/**
 * Newnet\Seo\Models\PreRedirect
 *
 * @property int $id
 * @property string $from_path
 * @property string $to_url
 * @property int|null $status_code
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|PreRedirect newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PreRedirect newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PreRedirect query()
 * @method static \Illuminate\Database\Eloquent\Builder|PreRedirect whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PreRedirect whereFromPath($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PreRedirect whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PreRedirect whereStatusCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PreRedirect whereToUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PreRedirect whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class PreRedirect extends Model
{
    protected $table = 'seo__pre_redirects';

    protected $fillable = [
        'from_path',
        'to_url',
        'status_code',
    ];

    protected $casts = [
        'status_code' => 'int',
    ];

    protected static function booted()
    {
        static::created(function () {
            Cache::forget('active_pre_redirects');
        });

        static::updated(function () {
            Cache::forget('active_pre_redirects');
        });

        static::deleted(function () {
            Cache::forget('active_pre_redirects');
        });
    }

    public function getStatusCodeAttribute($value)
    {
        return $value ?: 302;
    }
}
