<?php

use Illuminate\Database\Seeder;

class PrizesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $checkIfEmpty = DB::table('prizes')->first();
        $nowDateTimeString = \Carbon\Carbon::now()->toDateTimeString();
        $prizes = [
            [
                'name' => 'First Prize',
                'prize_no' => 1,
                'prize_order'=>1,
                'created_at'=>$nowDateTimeString,
            ],
            [
                'name' => 'Second Prize - 1st Winner',
                'prize_no' => 2,
                'prize_order'=>1,
                'created_at'=>$nowDateTimeString,
            ],
            [
                'name' => 'Second Prize - 2nd Winner',
                'prize_no' => 2,
                'prize_order'=>2,
                'created_at'=>$nowDateTimeString,
            ],
            [
                'name' => 'Third Prize - 1st Winner',
                'prize_no' => 3,
                'prize_order'=>1,
                'created_at'=>$nowDateTimeString,
            ],
            [
                'name' => 'Third Prize - 2nd Winner',
                'prize_no' => 3,
                'prize_order'=>2,
                'created_at'=>$nowDateTimeString,
            ],
            [
                'name' => 'Third Prize - 3rd Winner',
                'prize_no' => 3,
                'prize_order'=>3,
                'created_at'=>$nowDateTimeString,
            ],
        ];

        if(!$checkIfEmpty){
           if(DB::table('prizes')->insert($prizes)){
               $this->command->info('Prizes Successfully Inserted');
           }
        }else{
            if($this->command->confirm('Prizes Already Exist on this table, are you sure you want to clear and seed it again? [y|N]', true)) {
                if(DB::table('prizes')->delete()){
                    if(DB::table('prizes')->insert($prizes)){
                        $this->command->info('Prizes Successfully Inserted');
                    }
                }
            }else{
                $this->command->info('Prizes are still the same.');
            }
        }

    }
}
