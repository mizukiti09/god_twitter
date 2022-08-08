<template>
    <div>
        <div class="jsModal" v-show="deleteAction === true">
            <div class="c-overlay">
                <div class="c-overlay__contents">
                    <div class="c-overlay__ttl">こちらのをターゲットアカウントを削除しますか？</div>
                    <div class="c-overlay__targetName">{{delete_screen_name}}</div>
                    <div class="c-overlay__btnContainer">
                        <button class="c-appBtn" v-on:click="okDelete()">はい</button>
                        <button class="c-appBtn" v-on:click="deleteCancel()">いいえ</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="p-targetAccount__current">
            【Current Target: {{target.screen_name}}】
        </div>
        <div class="p-targetAccount__current">
            【Follower: {{target.follower}}】
        </div>
        <div class="p-targetAccount__current">
            【Load : {{load_count}}%】
        </div>
        <ul class="p-targetAccount__list">
            <li v-for="(account, i) in target_accounts" :key="i" class="p-targetAccount__list__item"
                :id="'target_data' + i" :class="{ 'p-targetAccount__list__item--on': checkLoad(account.screen_name)}">
                <i v-on:click="deleteOpen(account.id, account.screen_name,i)"
                    class="fas fa-trash-restore u-trash p-targetAccount__list__item__trash"></i>
                【AccountName: <a class="u-twitter-link"
                    :href="'https://twitter.com/' + account.screen_name">{{account.screen_name}}</a>】,【Follower:
                {{account.follower}}】
            </li>
        </ul>
    </div>
</template>

<script>

export default {
    props: ['user_id', 'auth_screen_name', 'auto_follow_flg', 'target_accounts', 'target'],
    data: function () {
        return {
            load_count: this.loadPercent(),
            deleteAction: false,
            target_id: '',
            target_dom_key: '',
            delete_screen_name: '',
        }
    },
    computed: {
    
    },
    methods: {
        loadPercent: function () {
            if (this.target.length == 0) {
                return '';
            } else {
                if (this.target.cursor_count == 0) {
                    return '';
                } else {
                    var result = this.target.cursor_count / this.target.follower * 100;

                    if (result >= 100) {
                        return result = 100;
                    }

                    // return Math.floor(result);
                    return result;
                }
            }
        },
        checkLoad: function (check_name) {
            if ((this.load_count == 100) && (check_name == this.target.screen_name)) {
                return true;
            } else {
            return false;
            }

        },
        deleteOpen: function (target_id, screen_name, key) {
            this.deleteAction = true;
            this.target_id = target_id;
            this.delete_screen_name = screen_name;
            this.target_dom_key = key
        },
        deleteCancel: function () {
            this.deleteAction = false;
            this.target_id = '';
            this.target_dom_key = '';
        },
        okDelete: function () {
            const formData = new FormData();
            var reset_auto_follow_flg = false;

            if (this.delete_screen_name == this.target.screen_name && this.auto_follow_flg) {
                if (!confirm('こちらのターゲットアカウントで現在、自動フォローモードONになっています。自動フォローモードOFFの状態であれば削除できます。自動フォローモードをOFFにして削除しますか？')) {
                    /* キャンセルの時の処理 */
                    this.deleteAction = false;
                    this.target_id = '';
                    this.target_dom_key = '';
                } else {
                    reset_auto_follow_flg = true;
                }
            }

            formData.append('user_id', this.user_id);
            formData.append('auth_screen_name', this.auth_screen_name);
            formData.append('target_id', this.target_id);
            formData.append('reset_auto_follow_flg', reset_auto_follow_flg);

            this.$axios.post('/api/twitter/deleteTargetAccount', formData)
                .then((res) => {
                    console.log(res)
                    if (this.target_accounts.length == 1) {
                        $cookies.remove('SearchText' + this.user_id + this.auth_screen_name);
                    }
                    window.location.reload(false)
                })
                .catch((error) => {
                    console.log('autoTweetStopは正常に起動していません。')
                    console.log(error)
                })
        },
    },
}
</script>
