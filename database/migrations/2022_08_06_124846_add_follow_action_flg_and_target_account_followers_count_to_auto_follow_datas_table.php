<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFollowActionFlgAndTargetAccountFollowersCountToAutoFollowDatasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('auto_follow_datas', function (Blueprint $table) {
            $table->boolean('follow_action_flg')->after('follow_condition')->default(false);
            $table->integer('target_account_followers_count')->after('cursor_count')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('auto_follow_datas', function (Blueprint $table) {
            $table->dropColumn('follow_action_flg');
            $table->dropColumn('target_account_followers_count');
        });
    }
}
