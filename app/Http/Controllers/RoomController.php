<?php

namespace App\Http\Controllers;

use \App\Room;
use \App\User;

use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (\Auth::user()->isInARoom()) 
            {
                Session::flash('error', 'You are in room already!'); 
                return back();
            }
            
            return $next($request);
        });
    }

    public function create(Request $request)
    {
        if ($room = Room::where('name', $request->name)->first()) {
            Session::flash('message', 'You have succesfully joined this room!');
        } else {
             $room = Room::create([
                'name' => $request->name,
                'password' => $request->password
            ]);
            
            Session::flash('message', 'Room succesfully created!');
        }


        \Auth::user()->assignRoom($room);

        return back();
    }
}