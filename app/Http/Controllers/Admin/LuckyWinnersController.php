<?php

namespace App\Http\Controllers\Admin;

use App\LuckyWinner;
use App\Prize;
use App\Role;
use App\User;
use App\WinningNumber;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class LuckyWinnersController extends Controller
{

    public function draw (Request $request){

        $validatedData = $request->validate([
            'generate_randomly' => 'required',
            'prize' => 'required',
        ]);

        if($validatedData){
            if($request->generate_randomly == 0){
                if(!$request->has('winning_number')){
                    return redirect()->back()->with('error','Winning number is required.');
                }else{
                    if($request->get('winning_number') == null){
                        return redirect()->back()->with('error','Winning number is required.');
                    }
                }
            }
        }

        $prize_id = $request->prize;
        if($request->generate_randomly == 1){
            /*RANDOMLY GENERATED*/
            $most_lucky_numbers_users =  User::whereHas('roles', function($q){$q->where('name', 'member');})->orderBy('winning_number_count','desc')->first();
            $most_lucky_numbers_count = 0;
            if($most_lucky_numbers_users){
                $most_lucky_numbers_count = $most_lucky_numbers_users->winning_number_count;
            }else{
                return redirect()->back()->with('error','Please generate a user and create winning numbers first before you draw.');
            }
            $prize = Prize::find($prize_id);
            if($prize){
                if($prize->prize_no == 1){
                    $first_prize_lucky_winner = LuckyWinner::where('prize_id',$prize_id)->first();
                    $new_winner = User::whereHas('roles', function($q){$q->where('name', 'member');})->where('winning_number_count',$most_lucky_numbers_count)->inRandomOrder()->first();
                    if($first_prize_lucky_winner){
                        if(!$first_prize_lucky_winner->delete()){
                            return redirect()->back()->with('error','Could not delete existing First Prize winner.');
                        }
                    }
                    $new_first_prize_lucky_winner = new LuckyWinner();
                    $new_first_prize_lucky_winner->user_id = $new_winner->id;
                    $new_first_prize_lucky_winner->prize_id = $prize_id;
                    $new_first_prize_lucky_winner->draw_method = 'Randomly Generated';
                    if($new_first_prize_lucky_winner->save()){
                        return redirect()->back()->with('success','Successful draw for the first prize winner.');
                    }else{
                        return redirect()->back()->with('error','Could not draw '. $prize->name );
                    }
                }else{
                    $existing_prize_winner = LuckyWinner::where('prize_id',$prize_id)->first();
                    if($existing_prize_winner){
                        if(!$existing_prize_winner->delete()){
                            return redirect()->back()->with('error','Could not delete existing '. $prize->name );
                        }
                    }
                    $existing_winner_ids = LuckyWinner::pluck('user_id')->toArray();
                    $random_winner = User::whereHas('roles', function($q){$q->where('name', 'member');})->whereNotIn('id',$existing_winner_ids)->inRandomOrder()->first();
                    if(!$random_winner){
                        return redirect()->back()->with('error','Could not draw '. $prize->name . '.Please generate more users.' );
                    }
                    $existing_winner = LuckyWinner::where('prize_id',$prize_id)->first();
                    if($existing_winner){
                        if(!$existing_winner->delete()){
                            return redirect()->back()->with('error','Could not delete existing '. $prize->name );
                        }
                    }
                    $new_lucky_winner = new LuckyWinner();
                    $new_lucky_winner->user_id = $random_winner->id;
                    $new_lucky_winner->prize_id = $prize_id;
                    $new_lucky_winner->draw_method = 'Randomly Generated';
                    if($new_lucky_winner->save()){
                        return redirect()->back()->with('success','Successful draw for the '.$prize->name);
                    }else{
                        return redirect()->back()->with('error','Could not draw '. $prize->name );
                    }
                }
            }else{
                return redirect()->back()->with('error','No prize found.');
            }
        }else{
            /*NOT RANDOMLY GENERATED*/
            $winning_number = $request->winning_number;
            $check_for_qualifiers =  User::whereHas('roles', function($q){$q->where('name', 'member');})->where('winning_number_count','!=',0)->first();
            if(!$check_for_qualifiers) {
                return redirect()->back()->with('error','Please generate a user and create winning numbers first before you draw.');
            }
            $prize = Prize::find($prize_id);
            $winning_numbers = WinningNumber::where('winning_number',$winning_number)->pluck('user_id')->toArray();
            if(count($winning_numbers) == 0){
                return redirect()->back()->with('error','Could find this winning number');
            }
            $draw_method = ' Randomly picked from users with the winning number :'.$winning_number;
            if(count($winning_numbers) == 1){
                $draw_method = 'Generated via winning number: '. $winning_number .'currently a unique winning number to all users at the time of draw.';
            }
            $existing_winner_ids = LuckyWinner::pluck('user_id')->toArray();
            $random_winner = User::whereHas('roles', function($q){$q->where('name', 'member');})->whereIn('id',$existing_winner_ids)->inRandomOrder()->first();
            if(!$random_winner){
                return redirect()->back()->with('error','Could not draw '. $prize->name . '.Please generate more users.' );
            }
            $prize_lucky_winner = LuckyWinner::where('prize_id',$prize_id)->first();
            if($prize_lucky_winner){
               if(!$prize_lucky_winner->delete()){
                   return redirect()->back()->with('error','Could not delete existing '.$prize->name);
               }
            }

            $new_lucky_winner = new LuckyWinner();
            $new_lucky_winner->user_id = $random_winner->id;
            $new_lucky_winner->prize_id = $prize_id;
            $new_lucky_winner->draw_method = $draw_method;
            if($new_lucky_winner->save()){
                return redirect()->back()->with('success','Successful draw for the '.$prize->name);
            }else{
                return redirect()->back()->with('error','Could not draw '. $prize->name );
            }
        }
    }
}
