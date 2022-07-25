<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAutoTweetFlgAndAutoUnFollowFlgAndAutoLikeFlgToUserTwitterAccounts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_twitter_accounts', function (Blueprint $table) {
            $table->boolean('auto_unFollow_flg')->after('auto_follow_flg')->default(0);
            $table->boolean('auto_tweet_flg')->after('auto_unFollow_flg')->default(0);
            $table->boolean('auto_like_flg')->after('auto_tweet_flg')->default(0);
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
            $table->dropColumn('auto_unFollow_flg');
            $table->dropColumn('auto_tweet_flg');
            $table->dropColumn('auto_like_flg');
        });
    }
}
