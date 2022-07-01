<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserTwitterAccount extends Model
{
    protected $table = 'user_twitter_accounts';

    protected $fillable = ['user_id', 'screen_name', 'profile_photo_path', 'auth_flg'];
    // モデルからその属性が取り除かれる（カラムが増えてもほとんど変更しなくて良い）
    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
