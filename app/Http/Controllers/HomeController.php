<?php

namespace App\Http\Controllers;

use App\LuckyWinner;
use App\Prize;
use App\User;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $prizes = Prize::orderBy('prize_no')->orderBy('prize_order')->pluck('name','id');
        $generate_randomly = ['1'=>'Yes','0'=>'No'];

        $lucky_draw_winners = Prize::leftjoin('lucky_winners','lucky_winners.prize_id','=','prizes.id')
            ->leftjoin('users','users.id','=','lucky_winners.user_id')
            ->select('prizes.id as id',
                'prizes.name as prize_name',
                'prizes.prize_no',
                'prizes.prize_order',
                'users.id as user_id',
                'users.name as user_name',
                'users.winning_number_count as winning_number_count',
                'lucky_winners.created_at as date_time_drawn',
                'lucky_winners.draw_method as draw_method'
                )->orderBy('prizes.prize_no')
            ->orderBy('prizes.prize_order')
            ->get();

        $members = User::whereHas('roles', function($q){$q->where('name','member');})->with('winning_numbers')->orderBy('name')->get();

        return view('home',compact('prizes','generate_randomly','lucky_draw_winners','members'));
    }
}
