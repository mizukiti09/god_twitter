<template>
    <div>
        <div id="jsModal" v-show="autoTarget">
            <div class="c-overlay">
                <div class="c-overlay__contents">
                    <div class="c-overlay__ttl">{{ autoTarget }}&nbsp;Search Keyword</div>
                    <div class="c-overlay__btnContainer c-overlay__btnContainer--auto">
                        <!-- <input class="c-search__text" type="text" name="search_text1"> -->
                        <div class="c-search">
                            <label class="ef">
                                    <input 
                                        type="text" 
                                        name="keyword" 
                                        placeholder="検索基準となるKeywordを追加してください"
                                        v-model="add_keyword"
                                        />
                                    <input
                                        class="c-search__submit"
                                        type="submit"
                                        value="追加"
                                        v-on:click="addSearchTextDB()"
                                        />
                            </label>
                            <!-- <pre v-html="getCookie()"></pre> -->
                            <ul v-if="getCookie()" class="c-search__keywords">
                                <li v-for="(keyword, i) in getCookie()" :key="i">{{keyword}}</li>
                            </ul>
                        </div>
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
            keywords: [],
        }
    },
    methods: {
        autoAction: function(targetName) {
            this.autoTarget = targetName;
        },
        autoCancel: function() {
            this.autoTarget = '';
        },
        addSearchTextDB: function() {
            if ( this.$vueCookies.isKey('SearchText' + this.user_id + this.auth_screen_name)) {
                var cookieData = this.$vueCookies.get('SearchText' + this.user_id + this.auth_screen_name);
                
                var arrayCookieData = cookieData.split( ',' );

                arrayCookieData.push(this.add_keyword);
                this.add_keyword = '';

                this.$vueCookies.set('SearchText' + this.user_id + this.auth_screen_name, arrayCookieData);
                
            } else {
                this.keywords.push(this.add_keyword);
                this.add_keyword = '';

                this.$vueCookies.config(60 * 60 * 24 * 30,'');
                this.$vueCookies.set('SearchText' + this.user_id + this.auth_screen_name, this.keywords);
            }
        },
        getCookie: function(){
           if ( this.$vueCookies.isKey('SearchText' + this.user_id + this.auth_screen_name)) {
            var cookieData = this.$vueCookies.get('SearchText' + this.user_id + this.auth_screen_name);
                
            var arrayCookieData = cookieData.split( ',' );

            return arrayCookieData;
           } else {
            return ;
           }
    
        },
    },
}
</script>
