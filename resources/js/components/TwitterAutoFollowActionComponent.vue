<template>
    <div>
        <div id="jsModal" v-show="autoTarget">
            <div class="c-overlay">
                <div class="c-overlay__contents">
                    <div class="c-overlay__ttl">{{ autoTarget }}</div>
                    <div class="c-overlay__description"><span class="u-red">*</span>Keywordを入力するとそのKeywordをもとにフォローします。<br>何も入力しないとKeywordなしでフォローします。</div>
                    <div class="c-overlay__btnContainer c-overlay__btnContainer--auto">
                        <div class="c-search">
                            <label class="ef">
                                    <input 
                                        type="text" 
                                        class="c-search__input"
                                        name="keyword" 
                                        placeholder="Keyword"
                                        v-model="add_keyword"
                                        />
                                    <input
                                        class="c-search__submit"
                                        type="submit"
                                        value="追加"
                                        v-on:click="addSearchText()"
                                        />
                            </label>
                            <div v-if="getCookie()" class="c-search__keywords">
                                <nav class="c-solidMenu">
                                    <ul>
                                        <li v-for="(keyword, i) in getCookie()" :key="i" :id="keyword"><a href="javascript:void(0)" id="cookiesDom"><span>{{keyword}}</span></a><input
                                        class="c-search__submit"
                                        type="submit"
                                        value="削除"
                                        v-on:click="deleteSearchTextCookie(keyword)"
                                        /></li>
                                        
                                    </ul>
                                </nav>
                            </div>
                        </div>
                        <select v-on:blur="setCookieCondition()" class="c-appBtn" name="follow_conditions" id="condition-select">
                            <option v-if="!isCookieCondition" selected disabled value="NO">選択してください</option>
                            <option v-else selected disabled :value="isCookieCondition">{{ isCookieCondition }}</option>

                            <option 
                                v-for="item in conditions" 
                                :value="item.value" 
                                :key="item.value"
                            >
                                {{ item.label }}
                            </option>
                        </select>
                        <button class="c-appBtn" v-on:click="searchAutoFollowSave()">更新</button>
                        <button v-show="!auto_follow_flg" class="c-appBtn" v-on:click="searchAutoFollowStart()">自動フォロー ON</button>
                        <button v-show="auto_follow_flg" class="c-appBtn" v-on:click="searchAutoFollowStop()">自動フォロー OFF</button>
                        <button class="c-appBtn" v-on:click="autoCancel()">閉じる</button>
                    </div>
                </div>
            </div>
    
        </div>
        <div class="c-appBtn"><a class="c-appBtn--none" :class="{'c-appBtn--auto': auto_follow_flg}" v-on:click="autoAction('Auto Follow Search Keyword')"><span v-if="auto_follow_flg">自動フォロー中</span><span v-else>自動フォローする</span></a></div>
        <div class="c-appBtn"><a class="c-appBtn--auto" v-on:click="autoAction('自動アンフォロー')">自動アンフォロー中</a></div>
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
        autoAction: function(targetName) {
            this.autoTarget = targetName;
        },
        autoCancel: function() {
            this.autoTarget = '';
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
                var arrayCookieData = cookieData.split( ',' );

            } else {
                // Keyword 無し
                var arrayCookieData = '';
            }
            
            if (arrayCookieData.length > 1 && this.$vueCookies.get('Condition' + this.user_id + this.auth_screen_name) == 'NOT') {
                alert('NOT の場合はKeywordを一つにする必要があります');
            } else if ( arrayCookieData.length <= 1 &&  this.$vueCookies.get('Condition' + this.user_id + this.auth_screen_name) == ('AND' || 'OR')) {
                if (arrayCookieData.length <= 1) {
                    alert('AND か OR の場合はKeywordを複数にする必要があります');
                }
            } else {
                const formData = new FormData();
                formData.append('user_id', this.user_id);
                formData.append('screen_name', this.auth_screen_name);
                formData.append('array_search_text', arrayCookieData);
                formData.append('condition', this.$vueCookies.get('Condition' + this.user_id + this.auth_screen_name));

                this.$axios.post('/api/twitter/autoFollowSave', formData)
                    .then((res) => {
                        console.log(res);
                        alert('フォローKeywordを更新しました。')
                    })
                    .catch((error) => {
                        console.log('searchAutoFollowSaveは正常に起動していません。')
                        console.log(error)
                    })
            }   
        },
        searchAutoFollowStart: function () {
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
        },
        searchAutoFollowStop: function() {
            const formData = new FormData();
            formData.append('user_id', this.user_id);

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
    },
}
</script>
