<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRestartFollowUnixTimeAndRestartUnFollowUnixTimeAndRestartLikeUnixTimeToUserTwitterAccounts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_twitter_accounts', function (Blueprint $table) {
            $table->integer('restart_follow_unixTime')->after('unFollow_count_first_unix_time')->nullable();
            $table->integer('restart_unFollow_unixTime')->after('restart_follow_unixTime')->nullable();
            $table->integer('restart_like_unixTime')->after('restart_unFollow_unixTime')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_twitter_accounts', function (Blueprint $table) {
            $table->dropColumn('restart_follow_unixTime');
            $table->dropColumn('restart_unFollow_unixTime');
            $table->dropColumn('restart_like_unixTime');
        });
    }
}
