<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Illuminate\Database\Eloquent\Model;
use Jenssegers\Mongodb\Auth\User as Authenticatable;
use Jenssegers\Mongodb\Eloquent\Model;

class Team extends Model
{
    use HasFactory;
    protected $collection = 'teams';
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    // public function broads()
    // {
    //     return $this->hasMany(Item::class);
    // }
}
