<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
    public function quit(Request $request)
    {
        $user = \Auth::user();
        $room_id = $user->room_id;
        $user->room_id = NULL;
        $user->save();
        
        \App\Room::destroy($room_id);

        return back();
    }
}