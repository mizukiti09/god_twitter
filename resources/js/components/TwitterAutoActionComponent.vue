<template>
    <div>
        <div id="jsModal" v-show="autoTarget">
            <div class="c-overlay">
                <div class="c-overlay__contents">
                    <div class="c-overlay__ttl">{{ autoTarget }}&nbsp;Search Keyword</div>
                    <div class="c-overlay__description"><span class="u-red">*</span>Keywordを入力するとそのKeywordをもとにフォローします。<br>何も入力しないとKeywordなしでフォローします。</div>
                    <div class="c-overlay__btnContainer c-overlay__btnContainer--auto">
                        <div class="c-search">
                            <label class="ef">
                                    <input 
                                        type="text" 
                                        name="keyword" 
                                        placeholder="Keyword"
                                        v-model="add_keyword"
                                        />
                                    <input
                                        class="c-search__submit"
                                        type="submit"
                                        value="追加"
                                        v-on:click="addSearchTextDB()"
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
                        <button class="c-appBtn" v-on:click="searchAutoFollow()">自動フォロー</button>
                        <button class="c-appBtn" v-on:click="autoCancel()">閉じる</button>
    
                    </div>
                </div>
            </div>
    
        </div>
        <div class="c-appBtn"><a class="c-appBtn--auto" v-on:click="autoAction('自動フォロー')">自動フォロー中</a></div>
        <div class="c-appBtn"><a class="c-appBtn--auto" v-on:click="autoAction('自動アンフォロー')">自動アンフォロー中</a></div>
        <div class="c-appBtn"><a class="c-appBtn--auto" v-on:click="autoAction('自動いいね')">自動いいね中</a></div>
        <div class="c-appBtn"><a class="c-appBtn--none" v-on:click="autoAction('自動ツイート')">自動ツイート</a></div>
    </div>
</template>

<script>
export default {
    props: ['user_id', 'auth_screen_name'],
    data: function() {
        return {
            autoTarget: '',
            add_keyword: '',
            keywords: '',
            cookiesData: this.getCookie()
        }
    },
    computed: {
        showButton: function() {
            return ((this.cookiesData == null)) ? true : false;
        },
    },
    methods: {
        autoAction: function(targetName) {
            this.autoTarget = targetName;
        },
        autoCancel: function() {
            this.autoTarget = '';
        },
        addSearchTextDB: function() {
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
                    this.$vueCookies.config(60 * 60 * 24 * 30,'');
                    this.keywords = this.add_keyword;
                    this.$vueCookies.set('SearchText' + this.user_id + this.auth_screen_name, this.add_keyword);
                    this.add_keyword = '';
                    console.log(this.$vueCookies.get('SearchText' + this.user_id + this.auth_screen_name));
                }
            }
        },
        deleteSearchTextCookie: function(keyword) {
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
           if ( this.$vueCookies.isKey('SearchText' + this.user_id + this.auth_screen_name)) {
            var cookieData = this.$vueCookies.get('SearchText' + this.user_id + this.auth_screen_name);
            var arrayCookieData = cookieData.split( ',' );
            return arrayCookieData;
           } else {
            return ;
           }
        },
        searchAutoFollow: function() {
            var cookieData = this.$vueCookies.get('SearchText' + this.user_id + this.auth_screen_name);
            var arrayCookieData = cookieData.split( ',' );

            const formData = new FormData()
            formData.append('user_id', this.user_id)
            formData.append('screen_name', this.auth_screen_name)
            formData.append('array_search_text', arrayCookieData)

            this.$axios.post('/api/twitter/autoFollow', formData)
                .then((res) => {
                    console.log(res)
                    window.location.reload(false)
                })
                .catch((error) => {
                    console.log('searchAutoFollowは正常に起動していません。')
                    console.log(error)
                })
        }
    },
}
</script>
