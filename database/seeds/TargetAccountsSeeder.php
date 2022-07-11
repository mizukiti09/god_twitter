<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TargetAccountsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('target_accounts')->insert([
            [
                'screen_name' => 'yousuck2020'
            ],
            [
                'screen_name' => 'matsu_bouzu'
            ],
            [
                'screen_name' => 'ariyoshihiroiki'
            ]
        ]);
    }
}
