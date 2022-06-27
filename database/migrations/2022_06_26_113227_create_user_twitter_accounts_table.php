<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserTwitterAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_twitter_accounts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id');
            $table
                ->foreign('user_id')
                ->references('id')
                ->on('users');
            $table->string('screen_name')->nullable();
            $table->string('profile_photo_path')->nullable();
            $table->integer('follow')->default(0);
            $table->integer('follower')->default(0);
            $table->integer('follow_count')->default(0);
            $table->integer('follower_count')->default(0);
            $table->integer('unFollow_count')->default(0);
            $table->integer('like_count')->default(0);
            $table->integer('like_count_get')->default(0);
            $table->integer('tweet_count')->default(0);
            $table->boolean('auth_flg')->default(false);
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
        Schema::dropIfExists('user_twitter_accounts');
    }
}
