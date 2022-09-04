<?php

namespace App\Http\ViewComposers;

use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use packages\Domain\Domain\TargetAccountsRepositoryInterface;
use packages\Domain\Domain\User\AutoTweetDatasRepositoryInterface;
use packages\Domain\Domain\User\AutoFollowDatasRepositoryInterface;
use packages\Domain\Domain\User\AutoLikeDatasRepositoryInterface;
use packages\Domain\Domain\User\UserTwitterAccountsRepositoryInterface;

class TwitterCommonComposer
{
    protected $u_repository;
    protected $ta_repository;
    protected $a_repository;
    protected $t_repository;
    protected $l_repository;

    public function __construct(
        UserTwitterAccountsRepositoryInterface $u_repository,
        TargetAccountsRepositoryInterface $ta_repository,
        AutoFollowDatasRepositoryInterface $a_repository,
        AutoTweetDatasRepositoryInterface $t_repository,
        AutoLikeDatasRepositoryInterface $l_repository
    ) {
        $this->u_repository = $u_repository;
        $this->ta_repository = $ta_repository;
        $this->a_repository = $a_repository;
        $this->t_repository = $t_repository;
        $this->l_repository = $l_repository;
    }

    public function compose(View $view)
    {
        $auth_account = array();
        $target = array();
        $target_accounts = array();
        $tweetList = array();
        $tweetedList = array();
        $followSearchTextAndCondition = array();
        $likeSearchTextAndCondition = array();
        $auth_screen_name = 'MyPage_auth_screen_name_null';

        $accounts = $this->u_repository->find();
        if (!empty($accounts[0])) {
            foreach ($accounts as $account) {
                if ($account->auth_flg) {
                    $auth_account = $account;
                    $auth_screen_name = $account->screen_name;
                    $tweetList = $this->t_repository->getAllUserTweets($account->id);
                    $tweetedList = $this->t_repository->getAllUserTweeted($account->id);
                    $target_accounts = $this->ta_repository->getAllTargetAccounts($account->id);
                    $target = $this->a_repository->getTarget($account->id);
                    $followSearchTextAndCondition = $this->a_repository->getSearchTextAndCondition($account->id);
                    $likeSearchTextAndCondition = $this->l_repository->getSearchTextAndCondition($account->id);
                    if (isset($target_accounts[0])) {
                        $target_accounts = $target_accounts;
                    } else {
                        $target_accounts = [];
                    }
                    continue;
                }
            }
        } else {
            $accounts = array();
        }

        $user_id = Auth::id();

        $view->with([
            'accounts'                     => $accounts,
            'auth_account'                 => $auth_account,
            'user_id'                      => $user_id,
            'auth_screen_name'             => $auth_screen_name,
            'target_accounts'              => $target_accounts,
            'target'                       => $target,
            'tweetList'                    => $tweetList,
            'tweetedList'                    => $tweetedList,
            'followSearchTextAndCondition' => $followSearchTextAndCondition,
            'likeSearchTextAndCondition'   => $likeSearchTextAndCondition
        ]);
    }
}
