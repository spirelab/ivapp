<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Investment extends Model
{
    protected $guarded = ['id'];

    protected $appends = ['nextPayment'];



    public function getNextPaymentAttribute()
    {
        $start = new \DateTime(($this->formerly) ?: $this->created_at);
        $end = new \DateTime($this->afterward);
        $current = new \DateTime();

        if ($current < $start) {
            // If the current date is before the start date
            $percent = 0.0;
        } elseif ($current > $end) {
            // If the current date is after the end date
            $percent = 1.0;
        } else {
            // If the current date is between the start and end dates
            $totalInterval = $end->diff($start);
            $elapsedInterval = $current->diff($start);

            // Calculate the progress percentage based on elapsed time
			if($totalInterval->format('%a') != 0){
				$percent = $elapsedInterval->format('%a') / $totalInterval->format('%a');	
			}else{
				$percent = 0;
			}
        }

        return sprintf('%.2f%%', $percent * 100);
    }


    public function plan()
    {
        return $this->belongsTo(ManagePlan::class,'plan_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }

}
