<?php

namespace App\Models;
use Avatar;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Illuminate\Database\Eloquent\Model;
use Jenssegers\Mongodb\Auth\User as Authenticatable;
use Jenssegers\Mongodb\Eloquent\Model;

class cards extends Model
{
    use HasFactory;
    protected $collection = 'cards';
    protected $fillable = [
        'cart_name',
        'list_id',
        'by_user',
        'order',
        'broad_id',
        'checklist',
		'time_duo',
		'members',
        // 'avatar_original',
    ];
}
