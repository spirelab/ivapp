<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PayoutMethod extends Model
{
    protected $guarded = ['id'];
    protected $table = 'payout_methods';
    protected $appends = ['imageUrl'];

    protected $casts = [
        'input_form' => 'object',
        'bank_name' => 'object',
        'banks' => 'array',
        'parameters' => 'object',
        'extra_parameters' => 'object',
        'convert_rate' => 'object',
        'currency_lists' => 'object',
        'supported_currency' => 'object',
    ];

    public function getImageUrlAttribute()
    {
        return getFile(config('location.withdraw.path') . $this->image);
    }
}
