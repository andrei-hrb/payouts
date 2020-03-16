@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <style>
                    a {
                        color:rgb(92, 0, 52) !important;
                    }

                    a:hover,
                    a:visited,
                    a:focus,
                    a:active {
                        color: rgb(112,32,77) !important;
                    }

                    .btn-primary {
                        background-color: rgb(92, 0, 52) !important;
                        border-color:rgb(92, 0, 52) !important;
                    }
                    
                    .btn-primary:hover,
                    .btn-primary:active,
                    .btn-primary:focus,
                    .btn-primary:visited {
                        background-color: rgb(112,32,77) !important;
                        border-color: rgb(112,32,77) !important;
                    }
                </style>
                @if (Auth::user()->isInARoom())
                    <?php $room = Auth::user()->Room() 
                        // cringe at me all you want =)) it's just a mini-project done in a evening of boredom
                    ?>
                    <div class="card-header">Dashboard</div>
                    <div class="card-body">
                        @if(Session::has('error'))
                            <div class="alert alert-danger" role="alert">
                                {{ Session::get('error') }}
                            </div>
                        @endif
                        @if(Session::has('message'))
                            <div class="alert alert-success" role="alert">
                                {{ Session::get('message') }}
                            </div>
                        @endif
                        @if(\Auth::user()->ammount > 0 && $room->Users()->count() > 1)
                            <form action=" {{ route('transaction') }}" method="post">
                                @csrf
                                <div class="form-group">
                                    <label for="user">User:</label>
                                    <select class="form-control" id="user" name="user">
                                        @foreach ($room->Users()->except(Auth::id()) as $item)
                                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="ammount">
                                        Ammount: <output id="output">1</output><img class="ml-1" src="/curr.png" alt="currency" width="25px" /></p>
                                        <p class="mb-0 text-muted d-flex align-items-center">
                                            <span>Max: {{ Auth::user()->ammount }}</span>
                                            <img class="ml-1" style="filter: contrast(0.7);" src="/curr.png" alt="currency" width="25px" /></p>
                                    </label>
                                    <input class="custom-range" type="range" min="1" max="{{ Auth::user()->ammount }}" value="1" 
                                        id="ammount" name="ammount" oninput="output.value = ammount.value">
                                </div>
                                <div class="form-group">
                                    <label for="reason">Reason:</label>
                                    <textarea class="form-control" id="reason" name="reason" rows="3" placeholder="Something nice..." required></textarea>
                                </div>
                                <div class="form-group text-center">
                                    <button type="submit" class="btn btn-primary">Submit</button>
                                </div>
                            </form>
                        @elseif (!\Auth::user()->ammount > 0)
                            <div class="alert alert-warning" role="alert">
                                You don't have any V-BUCKS, sorry mate!
                            </div>
                        @else
                            <div class="alert alert-primary" role="alert">
                                No other users here! Invite your friends! Press General info!
                            </div>
                        @endif
                        <hr class="mt-4 mb-4" />
                        <ul class="list-group list-group">
                            @if ($room->Transactions()->count())
                                @foreach ($room->Transactions() as $item)
                                    <li class="list-group-item mb-4 border-top">
                                        <div class="row">
                                            <?php 
                                                $from = \App\User::find($item->from)->id;
                                                $to = \App\User::find($item->to)->id;
                                                $me = \Auth::user()->id;

                                                if ($from !== $me && $to !== $me) {
                                                    $state = '';
                                                } elseif ($from === $me) {
                                                    $state = '-danger';
                                                } else {
                                                    $state = '-success';
                                                }
                                            ?>
                                            <div class="col-4" style="display: flex; align-items: center; justify-content: center;">
                                                <span class="mr-2 text{{ $state }}" style="font-size: 45px;">{{ $item->ammount }}</span>
                                                <img src="/curr{{ $state }}.png" alt="" height="45px" width="45px">
                                            </div>
                                            <div class="col-8">
                                                <p class="mb-1"><span class="text-danger">{{ \App\User::find($item->from)->name }}</span> &rarr; <span class="text-success">{{ \App\User::find($item->to)->name }}</span></p>
                                                <hr>
                                                <p class="mb-1 font-italic">{{ $item->reason }}</p>
                                                <hr>
                                                <p class="text-muted">{{ \Carbon\Carbon::createFromTimeStamp(strtotime($item->created_at))->diffForHumans() }}</p>
                                            </div>
                                        </div>
                                    </li>
                                @endforeach
                            @else
                                <div class="alert alert-primary" role="alert">
                                    No transactions yet!
                                </div>
                            @endif
                        </ul>
                        <hr>
                        <button class="btn btn-primary mb-3" type="button" data-toggle="collapse" data-target="#info" aria-expanded="false" aria-controls="info">
                            General info <span class="ml-1">&#9432;</span>
                        </button>
                        <div class="collapse" id="info">
                            <ul class="list-group list-group">
                                <li class="list-group-item">Your name: <strong>{{ Auth::user()->name }}</strong></li>
                                <li class="list-group-item">Your V-BUCKS: <strong>{{ Auth::user()->ammount }}</strong></li>
                                <li class="list-group-item">Room ID: <strong>{{ $room->id }}</strong></li>
                                <li class="list-group-item">Room name: <strong>{{ $room->name }}</strong></li>
                                <li class="list-group-item">Room password: <strong>{{ $room->password }}</strong></li>
                                <li class="list-group-item">Room created: <strong>{{ \Carbon\Carbon::createFromTimeStamp(strtotime($room->created_at))->diffForHumans() }}</strong></li>
                                <li class="list-group-item">Room's transactions count: <strong>{{ $room->Transactions()->count() }}</strong></li>
                            </ul>
                        </div>
                    </div>
                @else 
                    <div class="card-header">Join or create a Room</div>
                    <div class="card-body">
                        @if(Session::has('error'))
                            <div class="alert alert-danger" role="alert">
                                {{ Session::get('error') }}
                            </div>
                        @endif
                        <form action="{{ route('create_room') }}" method="POST">
                            @csrf
                           <div class="form-group">
                                <label for="name">Name</label>
                                <input type="text" class="form-control" id="name" name="name" aria-describedby="name" placeholder="Enter name">
                                <small id="emailHelp" class="form-text text-muted">If the name won't match with any room, a new one will be created</small>
                            </div>
                            <div class="form-group">
                                <label for="password">Password</label>
                                <input type="text" class="form-control" name="password" id="password" placeholder="Enter password">
                            </div>
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </form>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
