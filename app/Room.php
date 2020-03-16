<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'password'
    ];

    static function destroy($id)
    {
        $room = Room::find($id);
        
        if (! count($room->Users())) {
            $transactions = $room->Transactions();
            
            foreach ($transactions as $transaction) {
                $transaction->delete();
            }
            
            $room->delete();
        }
    }

    public function Users()
    {
        return User::where('room_id', $this->id)->orderBy('name', 'asc')->get();
    }

    public function Transactions()
    {
        return Transaction::where('room_id', $this->id)->orderBy('created_at', 'desc')->get();
    }
}