<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'customer';
    public function meetings()
    {
        return $this->hasMany('App\Models\Meeting');
    }
    public function teams()
    {
        return $this->hasMany('App\Models\Team');
    }
    public function friends()
    {
        return $this->hasMany('App\Models\Friend', 'customer_id');
    }
    public function requests()
    {
        return $this->hasMany('App\Models\Friend', 'friend_id');
    }
    public function reserves()
    {
        return $this->hasMany('App\Models\Schedule');
    }

    public function attendance()
    {
        $reserves = $this->reserves()->where('status', '=', '1')->get();
        $showup = $this->reserves()->where('status', '=', '2')->get();
        $notshowup = $this->reserves()->where('status', '=', '3')->get();

        $total = $showup->count() + $notshowup->count();
        $check = $total > 0 ? ($showup->count() * 100) / $total : 100;
        return $check;
    }
}
