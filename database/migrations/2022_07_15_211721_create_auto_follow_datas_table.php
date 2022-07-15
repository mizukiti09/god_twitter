<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAutoFollowDatasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('auto_follow_datas', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_twitter_account_id');
            $table
                ->foreign('user_twitter_account_id')
                ->references('id')
                ->on('user_twitter_accounts');
            $table->unsignedBigInteger('target_account_id');
            $table
                ->foreign('target_account_id')
                ->references('id')
                ->on('target_accounts');
            $table->integer('cursor_count')->default(0);
            $table->bigInteger('next_cursor')->default(0);
            $table->string('search_text');
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'));
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('auto_follow_datas');
    }
}
