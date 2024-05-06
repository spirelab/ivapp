<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class FireBaseNotify extends Model
{
    use HasFactory;

    protected $table = 'fire_base_notifies';
    protected $guarded = ['id'];

    protected static function boot()
    {
        parent::boot();
        static::saved(function () {
            Cache::forget('FireBaseNotifySet');
        });
    }
}
