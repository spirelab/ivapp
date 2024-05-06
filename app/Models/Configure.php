<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Configure extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    protected $casts = [
        'email_configuration' => 'object',
    ];
    protected static function boot()
    {
        parent::boot();
        static::saved(function () {
            \Cache::forget('GeneralSetting');
        });
    }
}
