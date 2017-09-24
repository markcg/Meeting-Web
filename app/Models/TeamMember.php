<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TeamMember extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'team_member';

    public function team()
    {
        return $this->belongsTo('App\Models\Team');
    }

    public function member()
    {
        return $this->belongsTo('App\Models\Customer', 'customer_id');
    }
}
