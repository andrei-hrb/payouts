<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class TransactionController extends Controller
{
    public function create(Request $request)
    {
        $from = \Auth::user();
        $to = \App\User::find($request->user);
        $ammount = $request->ammount;
        $reason = $request->reason;
        if (!$to) {
            Session::flash('error', 'User doesn\'t exist!');
            return back();
        }

        if ($from->room_id !== $to->room_id) {
            Session::flash('error', 'User is not a part of your room!');
            return back();
        }

        if ($ammount > $from->ammount) {
            Session::flash('error', 'Not enough V-BUCKS!');
            return back();
        }
        
        $transaction = \App\Transaction::create([
            'from' => $from->id,
            'to' => $to->id,
            'ammount' => $ammount,
            'reason' => $reason,
            'room_id' => $to->room_id,
        ]);

        $from->spend($ammount);
        $to->recive($ammount);
        
        Session::flash('message', 'Transaction succesfull!');
        return back();
    }
}