<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'schedule';
    public function field()
    {
        return $this->belongsTo('App\Models\Field');
    }
}
