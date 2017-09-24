<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Friend extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'friend';
    public function customer()
    {
        return $this->belongsTo('App\Models\Customer', 'customer_id');
    }
    public function friend()
    {
        return $this->belongsTo('App\Models\Customer', 'friend_id');
    }
}
