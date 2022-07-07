<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAutoFollowFlgAndAccessTokenAndAccessTokenSecretAndFollowCountFirstUnixTimeToUserTwitterAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_twitter_accounts', function (Blueprint $table) {
            $table->boolean('auto_follow_flg')->after('auth_flg')->default(0);
            $table->string('access_token')->after('auto_follow_flg')->nullable()->default(null);
            $table->string('access_token_secret')->after('access_token')->nullable()->default(null);
            $table->integer('follow_count_first_unix_time')->after('access_token_secret')->nullable();
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
            $table->dropColumn('auto_follow_flg');
            $table->dropColumn('access_token');
            $table->dropColumn('access_token_secret');
            $table->dropColumn('follow_count_first_unix_time');
        });
    }
}
