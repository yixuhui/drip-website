<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Goal extends Model
{
    protected $table = 'goal';
    protected $primaryKey = 'goal_id';
    public $timestamps = true;

    public function events()
    {
        return $this->hasMany('App\Event');
    }

    public function items()
    {
    	return $this->hasMany('App\Item');
    }

    public function users()
    {
        return $this->belongsToMany('App\User', 'user_goal')
                    ->withPivot('id','total_days', 'series_days','energy','expect_days','is_del');;
    }
}
