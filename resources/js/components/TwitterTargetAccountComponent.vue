<template>
    <div>
        <div class="jsModal" v-show="add_target">
            <div class="c-overlay">
                <div class="c-overlay__contents">
                    <div class="c-overlay__ttl">{{ add_target }}</div>
                    <div class="c-overlay__description"><span class="u-red">*</span>twitterアカウント名<br class="u-sp_br">を入力してください。
                    </div>
                    <div class="c-overlay__btnContainer c-overlay__btnContainer--auto">
                        <div class="c-search">
                            <label class="ef">
                                <div v-show="loading" class="c-search__loading"><img :src="'/images/ajax-loader.gif'" alt="load">
                                </div>
                                <input type="text" class="c-search__input" name="keyword"
                                    placeholder="アカウント名" v-model="target_screen_name" />
                                <input class="c-search__submit" type="submit" value="登録" v-on:click="addAccount()" />
                            </label>
                        </div>
                        <button class="c-appBtn" v-on:click="addCancel()">閉じる</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="c-appBtn">
            <a class="c-appBtn--none"
                v-on:click="addAction('ターゲットアカウント登録')">
                <span>ターゲットアカウント登録</span>
            </a>
        </div>
    </div>
</template>

<script>
export default {
    props: ['user_id', 'auth_screen_name', 'auto_follow_flg', 'target'],
    data: function () {
        return {
            add_target: '',
            target_screen_name: '',
            loading: false,
        }
    },
    methods: {
        addAction: function (targetName) {
            this.add_target = targetName;
        },
        addCancel: function () {
            this.add_target = '';
        },
        addAccount: async function () {
            if (this.target_screen_name) {
                const formData = new FormData();

                formData.append('user_id', this.user_id);
                formData.append('auth_screen_name', this.auth_screen_name);
                formData.append('target_screen_name', this.target_screen_name);

                this.loading = true;

                await this.$axios.post('/api/twitter/addTargetAccount', formData)
                    .then((res) => {
                        this.loading = false;
                        this.target_screen_nam = '';
                        this.add_target = '';
                        if (!alert('登録完了しました。')) {
                            window.location.reload(false)
                        }
                    })
                    .catch((error) => {
                        this.loading = false;
                        if (error.response.data.message.includes('timed out')) {
                            alert('Twitter API制限の為、少し時間を開けてから登録お願いします。');
                        } else {
                            alert('正しく入力されていないのか、Twitterサービスに登録されていないアカウントかもしれません。登録されているアカウント名を正しくご入力ください。');
                        }
                    })
            }
        },
        
    },
}
</script>
