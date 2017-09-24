<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'team';

    public function customer()
    {
        return $this->belongsTo('App\Models\Customer');
    }

    public function members()
    {
        return $this->hasMany('App\Models\TeamMember');
    }

    public function meetings()
    {
        return $this->hasMany('App\Models\MeetingTeam');
    }
}
