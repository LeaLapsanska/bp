<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $fillable = ['name', 'start', 'end', 'room_id'];
    
    public function room()
    {
        return $this->belongsTo(Room::class);
	}
	
	public function users()
    {
        return $this->belongsToMany(User::class);
	}
	
	public function author()
	{
		return $this->belongsTo(User::class, 'user_id');
	}

}
