<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFollowStatusAutoFollowDatas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('auto_follow_datas', function (Blueprint $table) {
            $table->string('follow_condition')->after('search_text')->default(null)->nullable();
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
            $table->dropColumn('follow_condition');
        });
    }
}
