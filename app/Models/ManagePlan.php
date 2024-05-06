<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ManagePlan extends Model
{
    protected $guarded = ['id'];

    protected $appends = ['price'];

    /*
     * Price With Currency
     */
    public function getPriceAttribute()
    {
        if ($this->fixed_amount == 0) {
            return config('basic.currency_symbol') . $this->minimum_amount . ' - ' . config('basic.currency_symbol') . $this->maximum_amount;
        }
        return config('basic.currency_symbol') . $this->fixed_amount;
    }


    public function getStatusMessageAttribute()
    {
        if ($this->status == 0) {
            return '<span class="badge badge-warning">' . trans('In-active') . '</span>';
        }
        return '<span class="badge badge-success">' . trans('Active') . '</span>';
    }

    public function getFeaturedMessageAttribute()
    {
        if ($this->featured == 0) {
            return '<span class="badge badge-warning">' . trans('No') . '</span>';
        }
        return '<span class="badge badge-success">' . trans('Yes') . '</span>';
    }

    public function profitFor()
    {
        $time = ManageTime::where('time', $this->schedule)->first();
        if ($time) {
            return $time->name;
        }
    }

    public function capitalCal()
    {
        if ($this->is_lifetime == 0) {
            if ($this->profit_type == 1) {
                if ($this->is_capital_back == 1) {
                    $capitalEarning = 'Total ' . ($this->profit * $this->repeatable) . '% + Capital';
                } else {
                    $capitalEarning = 'Total ' . ($this->profit * $this->repeatable) . ' ' . config('basic.currency');
                }
            } else {
                if ($this->is_capital_back == 1) {
                    $capitalEarning = 'Total ' . ($this->profit * $this->repeatable) . ' ' . config('basic.currency') . ' + Capital';
                } else {
                    $capitalEarning = 'Total ' . ($this->profit * $this->repeatable) . ' ' . config('basic.currency');
                }
            }
        } else {
            $capitalEarning = trans('Lifetime Earning');
        }
        return $capitalEarning;
    }

}
