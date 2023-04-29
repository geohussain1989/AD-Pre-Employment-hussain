<?php

namespace App\Models;

use App\Http\Requests\RegisterUserRequest;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Http\Request;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
    
        'first_name',
        'last_name',
        'address',
        'date_of_birth',
        'verified',
        'email',
        'email_verified_at',
        'password',
        'remember_token',
        'created_at',
        'updated_at'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function setDateOfBirthAttribute($date)
    {
        $this->attributes['date_of_birth'] =  Carbon::parse($date)->format('Y-m-d');
    }

    public function setPasswordAttribute($password)
    {
        $this->attributes['password'] =  bcrypt($password);
    }


    public function interests()
    {
        return $this->belongsToMany(Interest::class, 'interest_user');
    }

    public static function Register(RegisterUserRequest $request)
    {
        return self::create($request->only(
            'first_name', 'last_name','address','date_of_birth','email','password'
        ));
    }

    public static function getProfile(Request $request)
    {
        return self::where('id',auth()->user()->id)->firstOrFail();
    }
}
