<?php

namespace App\Http\Controllers\Admin;

use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Faker\Generator as Faker;
use Illuminate\Support\Facades\DB;

class UsersController extends Controller
{

    public function generateUsers(Request $request,Faker $faker){

        if(!$request->has('winning_number') || in_array(null,$request->get('winning_number'))){
            return redirect()->back()->with('error', 'Winning number is required.');
        }

        $user = new User();
        $user->name =  $faker->unique()->name;
        $user->email = $faker->unique()->safeEmail;
        $user->password = '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'; // password
        $user->winning_number_count = count($request->winning_number);
        if($user->save()){
            if($user->assignRole('member')){
                $winning_number_unique = [];
                $insert_data = [];
                foreach($request->winning_number as $winning_no)
                {
                    if(in_array($winning_no,$winning_number_unique)){
                        DB::table('users')->find($user->id)->delete();
                        return redirect()->back()->with('error','Winning Numbers must be unique');
                    }else{
                        $winning_number_unique[] = $winning_no;
                        $insert_data[] = ['user_id'=>$user->id,'winning_number'=>$winning_no,'created_at'=>Carbon::now()->toDateTimeString()];
                    }
                }
                if(DB::table('winning_numbers')->insert($insert_data)){
                    return redirect()->back()->with('success','Successfully Created user with winning numbers : '.implode (", ", $winning_number_unique));
                }else{
                    DB::table('winning_numbers')->where('user_id',$user->id)->delete();
                    DB::table('users')->find($user->id)->delete();
                    return redirect()->back()->with('error','Each winning number should be unique for this user');
                }
            }else{
                DB::table('users')->find($user->id)->delete();
                return redirect()->back()->with('error','Problem in creating user as a member.');
            }
        }else{
            return redirect()->back()->with('error','Error in creating a user.');
        }

    }
}
