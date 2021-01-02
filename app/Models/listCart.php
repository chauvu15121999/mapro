<?php

namespace App\Models;
use Avatar;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Illuminate\Database\Eloquent\Model;
use Jenssegers\Mongodb\Auth\User as Authenticatable;
use Jenssegers\Mongodb\Eloquent\Model;

class listCart extends Model
{
    use HasFactory;
    protected $collection = 'list';
    protected $fillable = [
        'list_name',
        'broad',
        'by_user',
        'position',
        'watch',
        // 'avatar_original',
    ];
}
