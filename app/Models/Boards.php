<?php

namespace App\Models;
use Avatar;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Illuminate\Database\Eloquent\Model;
use Jenssegers\Mongodb\Auth\User as Authenticatable;
use Jenssegers\Mongodb\Eloquent\Model;

class Boards extends Model
{
    use HasFactory;
    protected $collection = 'broads';
    // public function Team()
    // {
    // 	# code...
    // 	return $this->belongsTo(\App\Models\Team::class , 'team');
    // }
        public function user()
        {
          return $this->belongsTo(User::class);
        }


    // protected $fillable = [
    //     'broad_name',
    //     'team',
    //     'background',
    //     'activity',
    //     'by_user',
    //     'members',
    //     'status',
    //     // 'avatar_original',
    // ];
}
