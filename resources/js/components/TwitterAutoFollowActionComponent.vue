<template>
    <div>
        <div class="jsModal" v-show="autoFollow">
            <div class="c-overlay">
                <div class="c-overlay__contents">
                    <div class="c-overlay__ttl">{{ autoFollow }}</div>
                    <div class="c-overlay__description"><span class="u-red">*</span>Keywordを入力するとそのKeywordをもとにフォローします。
                    </div>
                    <div class="c-overlay__db">
                        <table class="c-overlay__db__table">
                            <tbody class="c-overlay__db__tbody">
                                <tr class="c-overlay__db__tr">
                                    <th class="c-overlay__db__th">登録Keyword</th>

                                    <td class="c-overlay__db__td">{{db_text}}</td>
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
                            <label class="ef">
                                <input type="text" class="c-search__input" name="keyword" placeholder="Keyword"
                                    v-model="add_keyword" />
                                <input class="c-search__submit" type="submit" value="追加" v-on:click="addSearchText()" />
                            </label>
                            <div v-if="getCookie()" class="c-search__keywords">
                                <nav class="c-solidMenu">
                                    <ul>
                                        <li v-for="(keyword, i) in getCookie()" :key="i" :id="keyword"><a
                                                href="javascript:void(0)"
                                                id="cookiesDom"><span>{{keyword}}</span></a><input
                                                class="c-search__submit" type="submit" value="削除"
                                                v-on:click="deleteSearchTextCookie(keyword)" /></li>

                                    </ul>
                                </nav>
                            </div>
                        </div>
                        <select v-on:blur="setCookieCondition()" class="c-appBtn" name="follow_conditions"
                            id="condition-select">
                            <option v-if="!isCookieCondition" selected disabled value="NO">選択してください</option>
                            <option v-else selected disabled :value="isCookieCondition">{{ isCookieCondition }}</option>

                            <option v-for="item in conditions" :value="item.value" :key="item.value">
                                {{ item.label }}
                            </option>
                        </select>
                        <button class="c-appBtn" v-on:click="searchAutoFollowSave()">更新</button>
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
                    <div class="c-overlay__description"><span class="u-red">*</span>フォローした日時から7日以上経過してフォローバックがないアカウント<br>
                        又は非アクティブユーザー（15日以上投稿なし)を自動アンフォローする</div>

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
        <div class="c-appBtn"><a class="c-appBtn--none" :class="{'c-appBtn--auto': auto_follow_flg}"
                v-on:click="autoFollowAction('Auto Follow Search Keyword')"><span
                    v-if="auto_follow_flg">自動フォロー中</span><span v-else>自動フォローする</span></a></div>
        <div class="c-appBtn"><a class="c-appBtn--none" :class="{'c-appBtn--auto': auto_un_follow_flg}"
                v-on:click="autoUnFollowAction('Auto UnFollow')"><span v-if="auto_un_follow_flg">自動アンフォロー中</span><span
                    v-else>自動アンフォローする</span></a></div>
    </div>
</template>

<script>
export default {
    props: ['user_id', 'auth_screen_name', 'auto_follow_flg', 'auto_un_follow_flg', 'db_search_text_condition'],
    data: function() {
        return {
            db_text: this.isDbSearchText(),
            db_condition: this.isDbCondition(),
            autoFollow: '',
            autoUnFollow: '',
            add_keyword: '',
            keywords: '',
            cookiesData: this.getCookie(),
            conditions: [
                {label: "NOT", value: 'NOT'},
                {label: "AND", value: 'AND'},
                {label: "OR", value: 'OR'},
            ],
            isCookieCondition: this.$vueCookies.get('Condition' + this.user_id + this.auth_screen_name),
        }
    },
    methods: {
        isDbSearchText: function () {
            if (this.db_search_text_condition) {
                return this.db_search_text_condition.search_text;
            } else {
                return '';
            }
        },
        isDbCondition: function () {
            if (this.db_search_text_condition) {
                return this.db_search_text_condition.follow_condition;
            } else {
                return '';
            }
        },
        setCookieCondition: function () {
            var select = document.getElementById('condition-select');

            console.log(select.value)
            this.$vueCookies.config(60 * 60 * 24 * 30, '');
            this.$vueCookies.set('Condition' + this.user_id + this.auth_screen_name, select.value);

            if (this.$vueCookies.get('SearchText' + this.user_id + this.auth_screen_name)) {
                var cookieData = this.$vueCookies.get('SearchText' + this.user_id + this.auth_screen_name);
                var arrayCookieData = cookieData.split( ',' );
                if ( select.value == 'NOT') {
                    if (arrayCookieData.length > 1) {
                        alert('NOT の場合はKeywordを一つにする必要があります');
                    }
                } else if ( (select.value == 'AND') || (select.value == 'OR')) {
                    if (arrayCookieData.length <= 1) {
                        alert('AND か OR の場合はKeywordを複数にする必要があります');
                    }
                }
            }
        },
        autoFollowAction: function(targetName) {
            this.autoFollow = targetName;
        },
        autoUnFollowAction: function(targetName) {
            this.autoUnFollow = targetName;
        },
        autoFollowCancel: function () {
            this.autoFollow = '';
        },
        autoUnFollowCancel: function() {
            this.autoUnFollow = '';
        },
        addSearchText: function() {
            this.$vueCookies.config(60 * 60 * 24 * 30, '');
            if (this.add_keyword) {
                if (this.$vueCookies.get('SearchText' + this.user_id + this.auth_screen_name)) {
                    var cookieData = this.$vueCookies.get('SearchText' + this.user_id + this.auth_screen_name);
                    var arrayCookieData = cookieData.split( ',' );
                
                    // 既にあるクッキーの中に入力した値があったら追加しない、なければ追加
                    if (arrayCookieData.includes(this.add_keyword)) {
                        alert(this.add_keyword + 'は既に追加されています');  // アラートされる
                        this.add_keyword = '';
                    } else {
                        arrayCookieData.push(this.add_keyword);
                        this.keywords = arrayCookieData;
                        this.add_keyword = '';
                        this.$vueCookies.set('SearchText' + this.user_id + this.auth_screen_name, arrayCookieData);
                        console.log(this.$vueCookies.get('SearchText' + this.user_id + this.auth_screen_name));
                    }
                } else {
                    this.keywords = this.add_keyword;
                    this.$vueCookies.set('SearchText' + this.user_id + this.auth_screen_name, this.add_keyword);
                    this.add_keyword = '';
                    console.log(this.$vueCookies.get('SearchText' + this.user_id + this.auth_screen_name));
                }
            }
        },
        deleteSearchTextCookie: function(keyword) {
            this.$vueCookies.config(60 * 60 * 24 * 30, '');
            var cookieData = this.$vueCookies.get('SearchText' + this.user_id + this.auth_screen_name);
            var arrayCookieData = cookieData.split( ',' );
            var index = arrayCookieData.indexOf(keyword);
            arrayCookieData.splice(index, 1);
            this.keywords = arrayCookieData;
            this.$vueCookies.set('SearchText' + this.user_id + this.auth_screen_name, arrayCookieData);
            console.log(this.$vueCookies.get('SearchText' + this.user_id + this.auth_screen_name));
            const keywordDom = document.getElementById(keyword);
            keywordDom.remove();

            if (this.$vueCookies.get('SearchText' + this.user_id + this.auth_screen_name) == null) {
                $cookies.remove('SearchText' + this.user_id + this.auth_screen_name);
            }
        },
        getCookie: function() {
            this.$vueCookies.config(60 * 60 * 24 * 30, '');
           if ( this.$vueCookies.isKey('SearchText' + this.user_id + this.auth_screen_name)) {
            var cookieData = this.$vueCookies.get('SearchText' + this.user_id + this.auth_screen_name);
            var arrayCookieData = cookieData.split( ',' );
            return arrayCookieData;
           } else {
            return ;
           }
        },
        searchAutoFollowSave: function() {
            this.$vueCookies.config(60 * 60 * 24 * 30, '');

            if (this.$vueCookies.get('SearchText' + this.user_id + this.auth_screen_name)) {
                // Keyword 有り
                var cookieData = this.$vueCookies.get('SearchText' + this.user_id + this.auth_screen_name);
                var arrayCookieData = cookieData.split(',');
                var cookieCondition = this.$vueCookies.get('Condition' + this.user_id + this.auth_screen_name);

                if (arrayCookieData.length > 1 && this.$vueCookies.get('Condition' + this.user_id + this.auth_screen_name) == 'NOT') {
                    alert('NOT の場合はKeywordを一つにする必要があります');
                } else if (arrayCookieData.length <= 1 && this.$vueCookies.get('Condition' + this.user_id + this.auth_screen_name) == ('AND' || 'OR')) {
                    if (arrayCookieData.length <= 1) {
                        alert('AND か OR の場合はKeywordを複数にする必要があります');
                    }
                } else {
                    const formData = new FormData();
                    formData.append('user_id', this.user_id);
                    formData.append('screen_name', this.auth_screen_name);
                    formData.append('array_search_text', arrayCookieData);
                    formData.append('condition', cookieCondition);

                    this.$axios.post('/api/twitter/autoFollowSave', formData)
                        .then((res) => {
                            console.log(res);
                            this.db_text = cookieData;
                            this.db_condition = cookieCondition;
                            alert('フォローKeywordを更新しました。')
                        })
                        .catch((error) => {
                            console.log('searchAutoFollowSaveは正常に起動していません。')
                            console.log(error)
                        })
                }   
            } else {
                // Keyword 無し
                alert('Keywordは1つ以上設定してください。');
            }
        },
        searchAutoFollowStart: function () {

            if (this.db_text.length || this.db_search_text_condition !== null ) {
                const formData = new FormData();
                formData.append('user_id', this.user_id);
                formData.append('screen_name', this.auth_screen_name);

                this.$axios.post('/api/twitter/autoFollowStart', formData)
                    .then((res) => {
                        console.log(res)
                        window.location.reload(false)
                    })
                    .catch((error) => {
                        console.log('searchAutoFollowStartは正常に起動していません。')
                        console.log(error)
                    })
            } else {
                alert('Keywordは1つ以上登録してから自動フォローしてください。');
            }
        },
        searchAutoFollowStop: function() {
            const formData = new FormData();
            formData.append('user_id', this.user_id);
            formData.append('screen_name', this.auth_screen_name);

            this.$axios.post('/api/twitter/autoFollowStop', formData)
                .then((res) => {
                    console.log(res)
                    window.location.reload(false)
                })
                .catch((error) => {
                    console.log('searchAutoFollowStopは正常に起動していません。')
                    console.log(error)
                })
        },
        autoUnFollowStart: function() {
            const formData = new FormData();
            formData.append('user_id', this.user_id);
            formData.append('screen_name', this.auth_screen_name);

            this.$axios.post('/api/twitter/autoUnFollowStart', formData)
                .then((res) => {
                    console.log(res)
                    window.location.reload(false)
                })
                .catch((error) => {
                    console.log('autoUnFollowStartは正常に起動していません。')
                    console.log(error)
                })
        },
        autoUnFollowStop: function() {
            const formData = new FormData();
            formData.append('user_id', this.user_id);
            formData.append('screen_name', this.auth_screen_name);

            this.$axios.post('/api/twitter/autoUnFollowStop', formData)
                .then((res) => {
                    console.log(res)
                    window.location.reload(false)
                })
                .catch((error) => {
                    console.log('autoUnFollowStopは正常に起動していません。')
                    console.log(error)
                })
        },
    },
}
</script>
