<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLikeTweetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('like_tweets', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_twitter_account_id');
            $table
                ->foreign('user_twitter_account_id')
                ->references('id')
                ->on('user_twitter_accounts')
                ->onDelete('cascade');
            $table->bigInteger('tweetId');
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
        Schema::dropIfExists('like_tweets');
    }
}