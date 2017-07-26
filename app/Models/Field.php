<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Field extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'field';
    public function promotions()
    {
        return $this->hasMany('App\Models\Promotion');
    }
    public function schedules()
    {
        return $this->hasMany('App\Models\Schedule');
    }
}
