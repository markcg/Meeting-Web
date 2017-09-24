<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MeetingTeam extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'meeting_team';
    public function meeting()
    {
        return $this->belongsTo('App\Models\Meeting');
    }

    public function team()
    {
        return $this->belongsTo('App\Models\Team');
    }
}
