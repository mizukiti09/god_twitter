<template>
<div>
    <div id="jsModal" v-show="autoTarget">
        <div class="c-overlay">
            <div class="c-overlay__contents">
                <div class="c-overlay__ttl">{{ autoTarget }}</div>
                <div class="c-overlay__description"><span class="u-red">*</span>自動ツイートしたい内容を特定の日時にツイート出来ます。</div>
                <div class="c-overlay__btnContainer c-overlay__btnContainer--auto">
                    <div class="c-search">
                        <div class="c-search__keywords">
                            <nav class="c-solidMenu">
                                <ul>
                                    <li>
                                        <div class="c-solidMenu__date">
                                            <vue-ctk-date-time-picker v-model="dateValue" label="日時を選択"></vue-ctk-date-time-picker>
                                            <a class="c-solidMenu__date__a" href="javascript:void(0)" id="cookiesDom">
                                                <textarea placeholder="tweet内容" v-on:blur="editCookieValue()" id="textarea" class="c-solidMenu__textarea" name="" cols="30" rows="8">{{ tweetText }}</textarea>
                                            </a>
                                        </div>
                                    </li>
                                </ul>
                            </nav>
                        </div>
                    </div>
                    <button class="c-appBtn" v-on:click="autoTweet()">登録</button>
                    <button v-show="!auto_tweet_flg" class="c-appBtn" v-on:click="autoTweetOn()">自動tweet ON</button>
                    <button v-show="auto_tweet_flg" class="c-appBtn" v-on:click="autoTweetStop()">自動tweet OFF</button>
                    <button class="c-appBtn" v-on:click="autoCancel()">閉じる</button>
                </div>
            </div>
        </div>
    </div>
    <div class="c-appBtn"><a class="c-appBtn--none" id="js-tweet-btn" :class="{'c-appBtn--auto': auto_tweet_flg}" v-on:click="autoAction('Auto Tweet')"><span v-if="auto_tweet_flg">自動ツイート中</span><span v-else>自動ツイートする</span></a></div>
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
            enabledDates: [
                this.formatDate(new Date(), 0),
                this.formatDate(new Date(), 1),
                this.formatDate(new Date(), 2),
                this.formatDate(new Date(), 3),
                this.formatDate(new Date(), 4),
                this.formatDate(new Date(), 5),
                this.formatDate(new Date(), 6),
                this.formatDate(new Date(), 7),
            ],
            cookiesData: '',
            tweetText: (this.$vueCookies.get('TweetText' + this.user_id + this.auth_screen_name)) ? 
            this.$vueCookies.get('TweetText' + this.user_id + this.auth_screen_name) : '',
            dateValue: '',
        }
    },
    methods: {
        formatDate: function (dt, plus) {
            var y = dt.getFullYear();
            var m = ('00' + (dt.getMonth() + 1)).slice(-2);
            var d = ('00' + dt.getDate()).slice(-2);
            d = Number(d) + plus;
            String(d);
            return (y + '-' + m + '-' + d);
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
        getCookie: function () {
            if (this.$vueCookies.isKey('TweetText' + this.user_id + this.auth_screen_name)) {
                var cookieData = this.$vueCookies.get('TweetText' + this.user_id + this.auth_screen_name);
                console.log(cookieData);
                var arrayCookieData = cookieData.split(',');
                return arrayCookieData;
            } else {
                return;
            }
        },
        autoTweet: function () {
            if (($('#textarea').val().length <= 140) && ($('#textarea').val().length > 0)) {
                var cookieData = this.$vueCookies.get('TweetText' + this.user_id + this.auth_screen_name);

                const formData = new FormData();
                formData.append('user_id', this.user_id);
                formData.append('screen_name', this.auth_screen_name);
                formData.append('tweet_text', cookieData);
                formData.append('date_value', this.dateValue);


                this.$axios.post('/api/twitter/autoTweet', formData)
                    .then((res) => {
                        console.log(res)
                        this.add_keyword = this.add_keyword + 1;
                        $cookies.remove('TweetText' + this.user_id + this.auth_screen_name);
                        this.dateValue = '';
                        $('#textarea').val('');
                        alert('tweet内容を登録しました。');
                    })
                    .catch((error) => {
                        console.log('autoTweetは正常に起動していません。')
                        console.log(error)
                    })
            } else if ($('#textarea').val().length > 140) {
                alert('tweet内容は140文字以下でご入力ください');
            } else if($('#textarea').val().length == 0) {
                alert('textareaが空です');
            }
        },
        autoTweetOn: function () {
            const formData = new FormData();
            formData.append('user_id', this.user_id);
            formData.append('screen_name', this.auth_screen_name);

            this.$axios.post('/api/twitter/autoTweetOn', formData)
                .then((res) => {
                    console.log(res)
                    window.location.reload(false)
                })
                .catch((error) => {
                    console.log('autoTweetStopは正常に起動していません。')
                    console.log(error)
                })
        },
        autoTweetStop: function () {
            const formData = new FormData();
            formData.append('user_id', this.user_id);

            this.$axios.post('/api/twitter/autoTweetStop', formData)
                .then((res) => {
                    console.log(res)
                    $cookies.remove('TweetText' + this.user_id + this.auth_screen_name);
                    window.location.reload(false)
                })
                .catch((error) => {
                    console.log('autoTweetStopは正常に起動していません。')
                    console.log(error)
                })
        },
        editCookieValue: function () {
            var textareaValue = $('#textarea').val();
            this.$vueCookies.set('TweetText' + this.user_id + this.auth_screen_name, textareaValue);
        }
    },
}
</script>
