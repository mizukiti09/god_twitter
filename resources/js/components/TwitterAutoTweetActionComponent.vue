<template>
    <div>
        <div id="jsModal" v-show="autoTarget">
            <div class="c-overlay">
                <div class="c-overlay__contents">
                    <div class="c-overlay__ttl">{{ autoTarget }}</div>
                    <div class="c-overlay__description"><span class="u-red">*</span>自動ツイートしたい内容を特定の日時にツイート出来ます。</div>
                    <div class="c-overlay__btnContainer c-overlay__btnContainer--auto">
                        <div class="c-search">
                            <label class="ef">
                                    <input 
                                        type="text" 
                                        class="c-search__input"
                                        name="keyword" 
                                        placeholder="自動ツイートしたい内容"
                                        v-model="add_keyword"
                                        />
                                    <input
                                        class="c-search__submit"
                                        type="submit"
                                        value="設定"
                                        v-on:click="addTweetTextDB()"
                                        />
                            </label>
                            <div v-if="getCookie()" class="c-search__keywords">
                                <nav class="c-solidMenu">
                                    <ul>
                                        <li v-for="(keyword, i) in getCookie()" :key="i" :id="'tweet_' + keyword">
                                            <div class="c-solidMenu__date">
                                                <vue-ctk-date-time-picker 
                                                    :enabledDates="enabledDates"
                                                    v-model="dateValues['dateValue' + i]"
                                                    label="日時を選択" 
                                                ></vue-ctk-date-time-picker>
                                                <a class="c-solidMenu__date__a" href="javascript:void(0)" id="cookiesDom">
                                                    <textarea v-on:blur="editCookieValue(i, keyword)" :id="'textarea'+i" class="c-solidMenu__textarea" name="" cols="30" rows="8">{{ keyword }}</textarea>
                                                </a>
                                            </div>
                                            
                                            <input
                                            class="c-search__submit"
                                            type="submit"
                                            value="削除"
                                            v-on:click="deleteTweetTextCookie(keyword)"/>
                                        </li>
                                    </ul>
                                </nav>
                            </div>
                        </div>
                        <button class="c-appBtn" v-on:click="autoTweet()">自動tweet</button>
                        <button v-show="auto_follow_flg" class="c-appBtn" v-on:click="autoTweetStop()">自動tweetストップ</button>
                        <button class="c-appBtn" v-on:click="autoCancel()">閉じる</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="c-appBtn"><a class="c-appBtn--none" :class="{'c-appBtn--auto': auto_follow_flg}" v-on:click="autoAction('Auto Tweet')">自動ツイートする</a></div>
    </div>
</template>

<script>
export default {
    props: ['user_id', 'auth_screen_name', 'auto_follow_flg'],
    data: function() {
        return {
            autoTarget: '',
            add_keyword: '',
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
            cookiesData: this.getCookie(),
            dateValues: {
                dateValue0: '',
                dateValue1: '',
                dateValue2: '',
            }
        }
    },
    computed: {
        showButton: function() {
            return this.cookiesData == null ? true : false;
        },
    },
    methods: {
        formatDate: function(dt, plus) {
            var y = dt.getFullYear();
            var m = ('00' + (dt.getMonth()+1)).slice(-2);
            var d = ('00' + dt.getDate()).slice(-2);
            d = Number(d) + plus;
            String(d);
            return (y + '-' + m + '-' + d);
        },
        autoAction: function(targetName) {
            this.autoTarget = targetName;
        },
        autoCancel: function() {
            this.autoTarget = '';
        },
        addTweetTextDB: function() {
            if (this.add_keyword) {
                if (this.$vueCookies.get('TweetText' + this.user_id + this.auth_screen_name)) {
                    var cookieData = this.$vueCookies.get('TweetText' + this.user_id + this.auth_screen_name);
                    var arrayCookieData = cookieData.split( ',' );
                
                    // 既にあるクッキーの中に入力した値があったら追加しない、なければ追加
                    if (arrayCookieData.includes(this.add_keyword)) {
                        alert(this.add_keyword + 'は既に追加されています');  // アラートされる
                        this.add_keyword = '';
                    } else {
                        arrayCookieData.push(this.add_keyword);
                        this.keywords = arrayCookieData;
                        this.add_keyword = '';
                        this.$vueCookies.set('TweetText' + this.user_id + this.auth_screen_name, arrayCookieData);
                        console.log(this.$vueCookies.get('TweetText' + this.user_id + this.auth_screen_name));
                    }
                } else {
                    this.$vueCookies.config(60 * 60 * 24 * 30,'');
                    this.keywords = this.add_keyword;
                    this.$vueCookies.set('TweetText' + this.user_id + this.auth_screen_name, this.add_keyword);
                    this.add_keyword = '';
                    console.log(this.$vueCookies.get('TweetText' + this.user_id + this.auth_screen_name));
                }
            }
        },
        deleteTweetTextCookie: function(keyword) {
            var cookieData = this.$vueCookies.get('TweetText' + this.user_id + this.auth_screen_name);
            var arrayCookieData = cookieData.split( ',' );
            var index = arrayCookieData.indexOf(keyword);
            arrayCookieData.splice(index, 1);
            this.keywords = arrayCookieData;
            this.$vueCookies.set('TweetText' + this.user_id + this.auth_screen_name, arrayCookieData);
            console.log(this.$vueCookies.get('TweetText' + this.user_id + this.auth_screen_name));
            const keywordDom = document.getElementById('tweet_'+keyword);
            keywordDom.remove();

            if (this.$vueCookies.get('TweetText' + this.user_id + this.auth_screen_name) == null) {
                $cookies.remove('TweetText' + this.user_id + this.auth_screen_name);
            }
        },
        getCookie: function() {
            if ( this.$vueCookies.isKey('TweetText' + this.user_id + this.auth_screen_name)) {
                var cookieData = this.$vueCookies.get('TweetText' + this.user_id + this.auth_screen_name);
                var arrayCookieData = cookieData.split( ',' );
                return arrayCookieData;
            } else {
                return ;
            }
        },
        autoTweet: function() {
            var cookieData = this.$vueCookies.get('TweetText' + this.user_id + this.auth_screen_name);
            var arrayCookieData = cookieData.split( ',' );

            var autoTweetDatas = {
                'data0': {'datetime': this.dateValues.dateValue0, 'text': arrayCookieData[0]},
                'data1': {'datetime': this.dateValues.dateValue1, 'text': arrayCookieData[1]},
                'data2': {'datetime': this.dateValues.dateValue2, 'text': arrayCookieData[2]}
            };

            console.log(autoTweetDatas);

            // if (this.$vueCookies.get('TweetText' + this.user_id + this.auth_screen_name)) {
            //     // Keyword 有り
            //     var cookieData = this.$vueCookies.get('TweetText' + this.user_id + this.auth_screen_name);
            //     var arrayCookieData = cookieData.split( ',' );
            // } else {
            //     // Keyword 無し
            //     var arrayCookieData = '';
            // }
            // console.log(arrayCookieData);

            // const formData = new FormData();
            // formData.append('user_id', this.user_id);
            // formData.append('screen_name', this.auth_screen_name);
            // formData.append('array_search_text', arrayCookieData);

            // this.$axios.post('/api/twitter/autoTweet', formData)
            //     .then((res) => {
            //         console.log(res)
            //         window.location.reload(false)
            //     })
            //     .catch((error) => {
            //         console.log('autoTweetは正常に起動していません。')
            //         console.log(error)
            //     })
        },
        autoTweetStop: function() {
            const formData = new FormData();
            formData.append('user_id', this.user_id);

            this.$axios.post('/api/twitter/autoFollowStop', formData)
                .then((res) => {
                    console.log(res)
                    window.location.reload(false)
                })
                .catch((error) => {
                    console.log('autoTweetStopは正常に起動していません。')
                    console.log(error)
                })
        },
        editCookieValue: function(key, keyword) {
            var textareaValue = $('#textarea' + key).val();
            var cookieData = this.$vueCookies.get('TweetText' + this.user_id + this.auth_screen_name);
            var arrayCookieData = cookieData.split( ',' );
            var index = arrayCookieData.indexOf(keyword);

            if (textareaValue) {
                arrayCookieData[key] = textareaValue;
                this.$vueCookies.set('TweetText' + this.user_id + this.auth_screen_name, arrayCookieData);
            } else {
                // アラートを出す
                if (!confirm('テキストエリアが空です。削除しますか？')) {
                    $('#textarea' + key).val(arrayCookieData[key]);
                    return false;
                } else {
                    arrayCookieData.splice(index, 1);
                    this.$vueCookies.set('TweetText' + this.user_id + this.auth_screen_name, arrayCookieData);
                    const keywordDom = document.getElementById('tweet_'+keyword);
                    keywordDom.remove();

                    if (this.$vueCookies.get('TweetText' + this.user_id + this.auth_screen_name) == null) {
                        $cookies.remove('TweetText' + this.user_id + this.auth_screen_name);
                    }
                }
            }
        }
    },
}
</script>
