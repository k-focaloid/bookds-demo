<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Book extends Model
{
    use SoftDeletes;
    protected $hidden = [
		'deleted_at',
		'created_at',
		'updated_at'
	];
	/*
	protected $fillable = [
		'name',
		'description',
		'genre'
	];*/
	public function author()
	{
		return $this->belongsTo(\App\User::class,'user_id');
	}

	public function genre()
	{
		return $this->belongsTo(\App\Genre::class,'genre_id');
	}

	public function getIdAttribute($value)
    {
        return encrypt($value);
    }
    public function getBookLinkAttribute($value)
    {
        return env('BASE_URL').'images/books/'.$value;
    }
}
