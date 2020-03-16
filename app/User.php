<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'room_id', 'ammount', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function Room()
    {
        return Room::where('id', $this->room_id)->first();
    }

    public function isInARoom() 
    {
        return $this->room_id != NULL;
    }

    public function assignRoom(Room $room)
    {
        $this->room_id = $room->id;
        $this->save();
    }

    public function spend($ammount)
    {
        $this->ammount = $this->ammount - $ammount;
        $this->save();
    }

    public function recive($ammount)
    {
        $this->ammount = $this->ammount + $ammount;
        $this->save();
    }
}