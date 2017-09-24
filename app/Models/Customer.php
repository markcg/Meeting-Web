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
}
