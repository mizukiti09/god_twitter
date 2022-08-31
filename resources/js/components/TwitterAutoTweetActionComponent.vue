<template>
    <div>
        <div class="jsModal" v-show="autoTarget">
            <div class="c-overlay">
                <div class="c-overlay__contents" style="padding-bottom: 78px;">
                    <div class="c-overlay__ttl">{{ autoTarget }}</div>
                    <div class="c-overlay__description"><span class="u-red">*</span>自動ツイートしたい内容を<br class="u-sp_br">特定の日時にツイートします。</div>
                    <div class="c-overlay__btnContainer c-overlay__btnContainer--auto">
                        <div class="c-overlay__errMsg" v-show="err_msg">{{err_msg}}</div>
                        <div class="c-search">
                            <div class="c-search__keywords">
                                <nav class="c-solidMenu">
                                    <ul>
                                        <li>
                                            <div class="c-solidMenu__date">
                                                <vue-ctk-date-time-picker class="overlay-date-time-picker" v-model="dateValue" label="日時を選択">
                                                </vue-ctk-date-time-picker>
                                                <a class="c-solidMenu__date__a" href="javascript:void(0)"
                                                    id="cookiesDom">
                                                    <textarea placeholder="tweet内容"
                                                        v-model="tweetText" id="tweet_textarea" class="c-solidMenu__textarea" name=""
                                                        cols="30" rows="8"></textarea>
                                                </a>
                                            </div>
                                        </li>
                                    </ul>
                                </nav>
                            </div>
                        </div>
                        <button class="c-appBtn" v-on:click="autoTweet()">登録</button>
                        <button v-show="!auto_tweet_flg" class="c-appBtn" v-on:click="autoTweetOn()">自動tweet ON</button>
                        <button v-show="auto_tweet_flg" class="c-appBtn" v-on:click="autoTweetStop()">自動tweet
                            OFF</button>
                        <button class="c-appBtn" v-on:click="autoCancel()">閉じる</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="c-appBtn"><a class="c-appBtn--none" id="js-tweet-btn" :class="{'c-appBtn--auto': auto_tweet_flg}"
                v-on:click="autoAction('自動ツイート')"><span v-if="auto_tweet_flg">自動ツイート中</span><span
                    v-else>自動ツイートする</span></a></div>
    </div>
</template>

<script>
export default {
    props: ['user_id', 'auth_screen_name', 'auto_tweet_flg'],
    data: function () {
        return {
            autoTarget: '',
            add_keyword: 0,
            keywords: '',
            cookiesData: '',
            tweetText: '',
            dateValue: '',
            err_msg: '',
        }
    },
    methods: {
        validMaxText: function(num, msg) {
            this.err_msg = '';
            if (this.tweetText.length > num) {
                this.err_msg = msg;
            }
        },
        validRequired: function(data, msg) {
            this.err_msg = '';
            if (!data) {
                this.err_msg = msg;
            }
        },
        autoAction: function (targetName) {
            this.autoTarget = targetName;
        },
        autoCancel: function () {
            this.autoTarget = '';
            var url = $(location).attr('href');
            if (url.indexOf("twitter/tweetList") >= 0 && this.add_keyword > 0) {
                window.location.reload(false)
            } 
        },
        autoTweet: async function () {
            this.validRequired(this.tweetText, 'ツイート内容を入力してください。');

            if (!this.err_msg) {
                this.validMaxText(140, 'Keywordは140文字以下でご入力ください。');

                if (!this.err_msg) {
                    const formData = new FormData();
                    formData.append('user_id', this.user_id);
                    formData.append('screen_name', this.auth_screen_name);
                    formData.append('tweet_text', this.tweetText);
                    formData.append('date_value', this.dateValue);

                    try {
                        await this.$axios.post('/api/twitter/autoTweet', formData);
                        this.add_keyword = this.add_keyword + 1;
                        this.dateValue = '';
                        this.tweetText = '';
                        alert('ツイート内容を登録しました。');
                    } catch (error) {
                        alert('予期せぬシステムエラーです。');
                    }
                }
            }
        },
        autoTweetOn: async function () {
            const formData = new FormData();
            formData.append('user_id', this.user_id);
            formData.append('screen_name', this.auth_screen_name);

            try {
                await this.$axios.post('/api/twitter/autoTweetOn', formData);
                window.location.reload(false);
            } catch (error) {
                alert('予期せぬシステムエラーです。');
            }
        },
        autoTweetStop: async function () {
            const formData = new FormData();
            formData.append('user_id', this.user_id);
            formData.append('screen_name', this.auth_screen_name);

            try {
                await this.$axios.post('/api/twitter/autoTweetStop', formData);
                window.location.reload(false);
            } catch (error) {
                alert('予期せぬシステムエラーです。');
            }
        },
    },
}
</script>
