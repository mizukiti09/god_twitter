<template>
    <div>
        <div class="jsModal" v-show="autoFollow">
            <div class="c-overlay">
                <div class="c-overlay__contents">
                    <div class="c-overlay__ttl">{{ autoFollow }}</div>
                    <div class="c-overlay__description"><span class="u-red">*</span>Keywordを入力するとそのKeyword<br>をもとにフォローします。
                    </div>
                    <div class="c-overlay__db">
                        <table class="c-overlay__db__table">
                            <tbody class="c-overlay__db__tbody">
                                <tr class="c-overlay__db__tr">
                                    <th class="c-overlay__db__th">登録Keyword</th>
    
                                    <td class="c-overlay__db__td">
                                        <div class="c-overlay__db__td__text" v-for="(text, i) in db_text" :key="i">{{text}}</div>
                                    </td>
                                </tr>
                                <tr class="c-overlay__db__tr">
                                    <th class="c-overlay__db__th">登録Condition</th>
    
                                    <td class="c-overlay__db__td">{{db_condition}}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="c-overlay__btnContainer c-overlay__btnContainer--auto c-overlay__db__border">
                        <div class="c-search">
                            <div class="c-overlay__errMsg" v-show="err_msg">{{err_msg}}</div>
                            <label class="ef">
                                        <input type="text" class="c-search__input" name="keyword" placeholder="Keyword"
                                            v-model="add_keyword" />
                                        <input class="c-search__submit" type="submit" value="追加" v-on:click="addSearchText()" />
                                    </label>
                            <div v-if="cookieData" class="c-search__keywords">
                                <nav class="c-solidMenu">
                                    <ul>
                                        <li v-for="(keyword, i) in cookieData" :key="i" :id="keyword">
                                            <a href="javascript:void(0)">
                                                <span>{{keyword}}</span>
                                            </a>
                                            <input 
                                                class="c-search__submit" 
                                                type="submit" 
                                                value="削除" 
                                                v-on:click="deleteSearchTextCookie(keyword)" />
                                        </li>
                                    </ul>
                                </nav>
                            </div>
                        </div>
                        <select v-on:blur="blurValidCondition()"
                            style="text-align:center;" 
                            class="c-appBtn"
                            name="follow_conditions" 
                            id="condition-follow-select"
                            v-model="selectValue"
                            >
                            <option selected disabled value="">選択してください</option>
                            <option value="NOT">NOT</option>
                            <option value="AND">AND</option>
                            <option value="OR">OR</option>
                        </select>
                        <button class="c-appBtn" v-on:click="searchAutoFollowSave()">更新</button>
                        <button class="c-appBtn" v-on:click="reset()">リセット</button>
                        <button v-show="!auto_follow_flg" class="c-appBtn" v-on:click="searchAutoFollowStart()">自動フォロー
                                    ON</button>
                        <button v-show="auto_follow_flg" class="c-appBtn" v-on:click="searchAutoFollowStop()">自動フォロー
                                    OFF</button>
                        <button class="c-appBtn" v-on:click="autoFollowCancel()">閉じる</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="jsModal" v-show="autoUnFollow">
            <div class="c-overlay">
                <div class="c-overlay__contents">
                    <div class="c-overlay__ttl">{{ autoUnFollow }}</div>
                    <div class="c-overlay__description"><span class="u-red">*</span>フォローした日時から7日以上経過して<br class="u-sp_br">フォローバックがないアカウント<br> 又は非アクティブユーザー（15日以上投稿なし)
                        <br class="u-sp_br">を自動アンフォローします。</div>
    
                    <div class="c-overlay__btnContainer c-overlay__btnContainer--auto">
                        <button v-show="!auto_un_follow_flg" class="c-appBtn" v-on:click="autoUnFollowStart()">自動アンフォロー
                                    ON</button>
                        <button v-show="auto_un_follow_flg" class="c-appBtn" v-on:click="autoUnFollowStop()">自動アンフォロー
                                    OFF</button>
                        <button class="c-appBtn" v-on:click="autoUnFollowCancel()">閉じる</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="c-appBtn"><a class="c-appBtn--none" :class="{'c-appBtn--auto': auto_follow_flg}" v-on:click="autoFollowAction('自動フォロー')"><span
                            v-if="auto_follow_flg">自動フォロー中</span><span v-else>自動フォローする</span></a></div>
        <div class="c-appBtn"><a class="c-appBtn--none" :class="{'c-appBtn--auto': auto_un_follow_flg}" v-on:click="autoUnFollowAction('自動アンフォロー')"><span v-if="auto_un_follow_flg">自動アンフォロー中</span><span
                            v-else>自動アンフォローする</span></a></div>
    </div>
</template>

<script>
export default {
    props: ['user_id', 'auth_screen_name', 'auto_follow_flg', 'auto_un_follow_flg', 'db_search_text_condition'],
    data: function() {
        return {
            db_text: this.dbSearchText(),
            db_condition: this.dbCondition(),
            autoFollow: '',
            autoUnFollow: '',
            add_keyword: '',
            keywords: '',
            err_msg: '',
            selectValue: '',
            cookieData: this.getCookie(),
        }
    },
    methods: {
        validMaxText: function(num, msg) {
            this.err_msg = '';
            if (this.add_keyword.length > num) {
                this.err_msg = msg;
            }
        },
        validExist: function(data, keyword, msg) {
            this.err_msg = '';
            if (data.includes(keyword)) {
                this.err_msg = msg;
                this.add_keyword = '';
            } else {
                this.err_msg = '';
            }
        },
        validCondition: function(condition, data, num, msg1, msg2) {
            this.err_msg = '';
            if (num === 0) {
                if (condition === 'NOT') {
                    if (data.length > 1) {
                        this.err_msg = msg1;
                    }
                } else if ((condition === 'AND') || (condition === 'OR')) {
                    if (data.length <= 1) {
                        this.err_msg = msg2;
                    }
                }
            } else if (num === 1) {
                if (data.length > 1 && condition === 'NOT') {
                    this.err_msg = msg1;
                } else if ((condition === 'AND') || (condition === 'OR')) {
                    if (data.length <= 1) {
                        this.err_msg = msg2;
                    }
                }
            }
        },
        blurValidCondition: function () {
            var select = this.selectValue;
            this.$vueCookies.config(60 * 60 * 24 * 30, '');

            if (this.$vueCookies.get('SearchText' + this.user_id + this.auth_screen_name)) {
                var cookieData = this.$vueCookies.get('SearchText' + this.user_id + this.auth_screen_name);
                var arrayCookieData = cookieData.split( ',' );
                this.validCondition(
                    select,
                    arrayCookieData,
                    0,
                    'NOT の場合はKeywordを一つにする必要があります。',
                    'AND か OR の場合はKeywordを複数にする必要があります。'
                );
            }
        },
        validSelectedCondition: function(msg) {
            this.err_msg = '';
            var select = this.selectValue;
            if (select === '') {
                this.err_msg = msg;
            }
        },
        validRequired: function(data, msg) {
            this.err_msg = '';
            if (!data) {
                this.err_msg = msg;
            }
        },
        validAutoMode: function(autoFlg, msg) {
            this.err_msg = '';

            if (autoFlg === 1) {
                this.err_msg = msg;
            }
        },
        dbSearchText: function() {
            if (this.db_search_text_condition) {
                var array_search_text = this.db_search_text_condition.search_text.split(',');
                return array_search_text;
            } else {
                return '';
            }
        },
        dbCondition: function() {
            if (this.db_search_text_condition) {
                return this.db_search_text_condition.follow_condition;
            } else {
                return '';
            }
        },
        autoFollowAction: function(targetName) {
            this.autoFollow = targetName;
        },
        autoUnFollowAction: function(targetName) {
            this.autoUnFollow = targetName;
        },
        autoFollowCancel: function() {
            this.autoFollow = '';
        },
        autoUnFollowCancel: function() {
            this.autoUnFollow = '';
        },
        addSearchText: function() {
            this.$vueCookies.config(60 * 60 * 24 * 30, '');

            if (this.add_keyword) {

                this.validMaxText(140, 'Keywordは140文字以下でご入力ください。');

                if (!this.err_msg) {
                    if (this.$vueCookies.get('SearchText' + this.user_id + this.auth_screen_name)) {
                        var cookieData = this.$vueCookies.get('SearchText' + this.user_id + this.auth_screen_name);
                        var arrayCookieData = cookieData.split(',');

                        // 既にあるクッキーの中に入力した値があったら追加しない、なければ追加
                        this.validExist(arrayCookieData, this.add_keyword, '既にあるKeywordは追加できません。');

                        if (!this.err_msg) {
                            arrayCookieData.push(this.add_keyword);
                            this.keywords = arrayCookieData;
                            this.add_keyword = '';
                            this.$vueCookies.set('SearchText' + this.user_id + this.auth_screen_name, arrayCookieData);
                        }
                    } else {
                        this.keywords = this.add_keyword;
                        this.$vueCookies.set('SearchText' + this.user_id + this.auth_screen_name, this.add_keyword);
                        this.add_keyword = '';
                    }
                }
            }
            this.cookieData = this.getCookie();
        },
        deleteSearchTextCookie: function(keyword) {

            this.$vueCookies.config(60 * 60 * 24 * 30, '');
            var cookieData = this.$vueCookies.get('SearchText' + this.user_id + this.auth_screen_name);
            var arrayCookieData = cookieData.split(',');
            var index = arrayCookieData.indexOf(keyword);
            arrayCookieData.splice(index, 1);
            this.keywords = arrayCookieData;
            this.$vueCookies.set('SearchText' + this.user_id + this.auth_screen_name, arrayCookieData);

            if (this.$vueCookies.get('SearchText' + this.user_id + this.auth_screen_name) === null) {
                $cookies.remove('SearchText' + this.user_id + this.auth_screen_name);
            }
            this.cookieData = this.getCookie();
        },
        getCookie: function() {
            this.$vueCookies.config(60 * 60 * 24 * 30, '');
            if (this.$vueCookies.isKey('SearchText' + this.user_id + this.auth_screen_name)) {
                var cookieData = this.$vueCookies.get('SearchText' + this.user_id + this.auth_screen_name);
                var arrayCookieData = cookieData.split(',');
                return arrayCookieData;
            } else {
                return;
            }
        },
        searchAutoFollowSave: async function() {

            this.$vueCookies.config(60 * 60 * 24 * 30, '');

            if (!$cookies.isKey('SearchText' + this.user_id + this.auth_screen_name)) {
                this.err_msg = 'Keywordは1つ以上設定してください。';
                return;
            }
            this.validSelectedCondition('選択の項目を選択してください。');

            if (!this.err_msg) {
                // Keyword 有り
                var cookieData = this.$vueCookies.get('SearchText' + this.user_id + this.auth_screen_name);
                var arrayCookieData = cookieData.split(',');
                var condition = this.selectValue;

                this.validCondition(
                    condition,
                    arrayCookieData,
                    1,
                    'NOT の場合はKeywordを一つにする必要があります。',
                    'AND か OR の場合はKeywordを複数にする必要があります。'
                );
                if (!this.err_msg) {
                    const formData = new FormData();
                    formData.append('user_id', this.user_id);
                    formData.append('screen_name', this.auth_screen_name);
                    formData.append('array_search_text', arrayCookieData);
                    formData.append('condition', condition);

                    await this.$axios.post('/api/twitter/autoFollowSave', formData)
                        .then((res) => {
                            this.db_text = arrayCookieData;
                            this.db_condition = condition;
                            alert('フォローKeywordを更新しました。')
                        })
                        .catch((error) => { alert('予期せぬシステムエラーです。') })
                }
            }
        },
        reset: async function() {
            this.validAutoMode(this.auto_follow_flg, 'リセットする場合は自動フォローを止めてからリセットしてください。');

            if (!this.err_msg) {
                if (!confirm('本当にリセットしますか？')) {
                    return;
                } else {
                    const formData = new FormData();
                    formData.append('user_id', this.user_id);
                    formData.append('screen_name', this.auth_screen_name);

                    try {
                        await this.$axios.post('/api/twitter/autoFollowReset', formData);
                        this.db_text = '';
                        this.db_condition = '';
                        $cookies.remove('SearchText' + this.user_id + this.auth_screen_name);
                        this.cookieData = [];
                    } catch (error) {
                        alert('予期せぬシステムエラーです。');
                    }
                }
            }
        },
        searchAutoFollowStart: async function() {
            this.validRequired((this.db_text.length || this.db_search_text_condition !== null), 'Keywordは1つ以上設定してください。');

            if (!this.err_msg) {
                const formData = new FormData();
                formData.append('user_id', this.user_id);
                formData.append('screen_name', this.auth_screen_name);

                try {
                    await this.$axios.post('/api/twitter/autoFollowStart', formData);
                    window.location.reload(false);
                } catch (error) {
                    alert('予期せぬシステムエラーです。');
                }
            }
        },
        searchAutoFollowStop: async function() {

            const formData = new FormData();
            formData.append('user_id', this.user_id);
            formData.append('screen_name', this.auth_screen_name);

            try {
                await this.$axios.post('/api/twitter/autoFollowStop', formData);
                window.location.reload(false);
            } catch (error) {
                alert('予期せぬシステムエラーです。');
            } 
        },
        autoUnFollowStart: async function() {

            const formData = new FormData();
            formData.append('user_id', this.user_id);
            formData.append('screen_name', this.auth_screen_name);

            try {
                await this.$axios.post('/api/twitter/autoUnFollowStart', formData);
                window.location.reload(false)
            } catch (error) {
                alert('予期せぬシステムエラーです。');
            } 
        },
        autoUnFollowStop: async function() {

            const formData = new FormData();
            formData.append('user_id', this.user_id);
            formData.append('screen_name', this.auth_screen_name);

            try {
                await this.$axios.post('/api/twitter/autoUnFollowStop', formData);
                window.location.reload(false);
            } catch (error) {
                alert('予期せぬシステムエラーです。');
            } 
        },
    },
}
</script>
