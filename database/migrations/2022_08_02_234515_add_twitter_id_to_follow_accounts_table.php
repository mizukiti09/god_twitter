<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTwitterIdToFollowAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('follow_accounts', function (Blueprint $table) {
            $table->bigInteger('twitterId')->after('screen_name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('follow_accounts', function (Blueprint $table) {
            $table->dropColumn('twitterId');
        });
    }
}
