<?php

namespace App\Models;
use Avatar;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Illuminate\Database\Eloquent\Model;
use Jenssegers\Mongodb\Auth\User as Authenticatable;
use Jenssegers\Mongodb\Eloquent\Model;

class admin extends Model
{
    use HasFactory;
   	protected $collection = 'admin';
}
