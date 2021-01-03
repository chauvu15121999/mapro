<?php

namespace App\Models;
use Avatar;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Jenssegers\Mongodb\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Jenssegers\Mongodb\Eloquent\Model;
use Illuminate\Contracts\Auth\MustVerifyEmail;
// use Laravel\Fortify\TwoFactorAuthenticatable;
// use Laravel\Jetstream\HasProfilePhoto;
// use Laravel\Sanctum\HasApiTokens;
class User extends Authenticatable implements MustVerifyEmail
{
    // use HasApiTokens;
    use HasFactory;
    // use HasProfilePhoto;
    use Notifiable;
    // use TwoFactorAuthenticatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $collection = 'users';
    protected $fillable = [
        'user_name',
        'email',
        'password',
        'google_id',
        'email_verified_at',
        'Activity',
        'password_gg',
        'avatar',
        // 'avatar_original',
    ];
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    public function broads()
        {
          return $this->hasMany(Broads::class);
        }
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
        /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    //protected $appends = [
    //     'profile_photo_url',
    // ];
        protected $appends = [
            'avatar_path'
        ];

        public function getAvatarPathAttribute()
        {
            if (empty($this->attributes['avatar'])) {
                return Avatar::create($this->attributes['user_name'])
                    ->setDimension(30, 30)
                    ->setFontSize(10)
                    ->setShape('square')
                    ->toBase64();
            }

            return $this->attributes['avatar']; 
        }
}
