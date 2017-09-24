<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Meeting extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'meeting';
    public function customer()
    {
        return $this->belongsTo('App\Models\Customer');
    }

    public function schedules()
    {
        return $this->hasMany('App\Models\Schedule');
    }

    public function teams()
    {
        return $this->hasMany('App\Models\MeetingTeam');
    }
}
