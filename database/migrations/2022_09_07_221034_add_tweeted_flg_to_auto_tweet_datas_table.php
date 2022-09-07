<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTweetedFlgToAutoTweetDatasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('auto_tweet_datas', function (Blueprint $table) {
            $table->boolean('tweeted_flg')->after('tweetTime')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('auto_tweet_datas', function (Blueprint $table) {
            $table->dropColumn('tweeted_flg');
        });
    }
}
