<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUnFollowCountFirstUnixTimeToUserTwitterAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_twitter_accounts', function (Blueprint $table) {
            $table->integer('unFollow_count_first_unix_time')->after('follow_count_first_unix_time')->nullable();
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
            $table->dropColumn('unFollow_count_first_unix_time');
        });
    }
}
