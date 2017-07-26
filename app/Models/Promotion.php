<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Promotion extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'promotion';

    public function field()
    {
        return $this->belongsToMany('App\Models\Field');
    }
}
