<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Genre extends Model
{
	protected $hidden = [
		'created_at',
		'updated_at'
	];
    public function books()
    {
        return $this->hasMany(\App\Book::class,'genre_id');
    }
    public function getIdAttribute($value)
    {
        return encrypt($value);
    }
}
