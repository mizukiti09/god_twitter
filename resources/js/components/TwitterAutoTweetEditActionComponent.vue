<template>
<div v-if="tt.length">
    <div id="jsModal" v-show="deleteAction === true">
        <div class="c-overlay">
            <div class="c-overlay__contents">
                <div class="c-overlay__ttl">こちらのをTweetを削除しますか？</div>
                <div class="c-overlay__btnContainer">
                    <button class="c-appBtn" v-on:click="okDelete()">はい</button>
                    <button class="c-appBtn" v-on:click="deleteCancel()">いいえ</button>
                </div>
            </div>
        </div>
    </div>
    <div id="jsModal" v-show="editAction === true">
        <div class="c-overlay">
            <div class="c-overlay__contents">
                <div class="c-overlay__ttl">ツイート内容を更新しますか？</div>
                <div class="c-overlay__btnContainer">
                    <button class="c-appBtn" v-on:click="okEdit()">はい</button>
                    <button class="c-appBtn" v-on:click="editCancel()">いいえ</button>
                </div>
            </div>
        </div>
    </div>
    <ul class="p-tweetList">
        <li v-for="(tweet, i) in tweet_list" :key="i"
            :id="'tweet_data' + i" 
            class="p-tweetList__item" 
            :class="{'exceed-time': checkTime(tweet.tweetTime)}"
        >
            <i v-on:click="deleteOpen(ti[i], i)" class="fas fa-trash-restore u-trash p-tweetList__item__trash"></i>
            <div class="p-tweetList__item__datePlan">
                <vue-ctk-date-time-picker 
                    v-on:change="editOpen(i, tweet.id)"
                    v-model="dt[i]" 
                    label="日時を選択">
                </vue-ctk-date-time-picker>
            </div>
            <textarea 
                placeholder="tweet内容" 
                v-on:change="editOpen(i, tweet.id)" 
                :id="'tweet-area' + i" 
                class="c-solidMenu__textarea" 
                name="" cols="30" rows="8" 
                v-model="tt[i]">
            </textarea>
        </li>
        {{changeData}}
        
    </ul>
</div>
</template>

<script>

export default {
    props: ['user_id', 'auth_screen_name', 'auto_tweet_flg', 'tweet_list'],
    data: function () {
        return {
            lastDay: new Date(this.year, this.month, 0).getDate(),
            dateValue: '',
            dt: this.dateTimes(),
            tt: this.tweetData(),
            ti: this.userIds(),
            deleteAction: false,
            target_id: '',
            target_dom_key: '',
            editAction: false,
            del_keys: [],
            target_key: '',
            target_tweet_id: '',
            formal_text: '',
            formal_time: '',
        }
    },
    computed: {
        changeData: function () {
            
            for (var key = 0; key < this.tweet_list.length; key++) {
                if (this.tweet_list[key].tweetTime !== this.dt[key]) {
                    
                    this.editAction = true;
                    this.target_key = key;
                    this.target_tweet_id = this.tweet_list[key].id;
                    
                    this.formal_text = this.tweet_list[key].tweetText;
                    this.formal_time = this.tweet_list[key].tweetTime;
                }
            }
            
        },
    },
    methods: {
        checkTime: function(time) {
            var dataUnixTime = Date.parse(time);
            
            var date = new Date() ;
            var currentUnixTime = date.getTime() ;

            if (dataUnixTime < currentUnixTime) {
                return true;
            } else {
                return false;
            }
        },
        deleteOpen: function(tweet_id, key) {
            this.deleteAction = true;
            this.target_id = tweet_id;
            this.target_dom_key = key
        },
        editOpen: function(key, tweet_id) {
                this.editAction = true;
                this.target_key = key;
                this.target_tweet_id = tweet_id;
                
                this.formal_text = this.tweet_list[key].tweetText;
                this.formal_time = this.tweet_list[key].tweetTime;
        },
        deleteCancel: function() {
            this.deleteAction = false;
            this.target_id = '';
            this.target_dom_key = '';
        },
        editCancel: function() {
            this.tt[this.target_key] = this.formal_text;
            this.dt[this.target_key] = this.formal_time;
            this.editAction = false;
            this.target_key = '';
            this.target_tweet_id = '';
        },
        okDelete: function() {
            const formData = new FormData();
            
            formData.append('tweet_id', this.target_id);

            this.$axios.post('/api/twitter/tweetDelete', formData)
                .then((res) => {
                    const tweetDom = document.getElementById('tweet_data' + this.target_dom_key);
                    this.del_keys.push(this.target_dom_key);
                    this.target_dom_key = '';
                    this.deleteAction = false;
                    tweetDom.remove();
                    var tweetCount = $("#js-tweet-count").text();
                    tweetCount = Number(tweetCount) - 1;
                    $("#js-tweet-count").text(tweetCount);

                    console.log('okDeleteは正常に起動しました。')
                })
                .catch((error) => {
                    console.log(error)
                    console.log('okDeleteは正常に起動していません。')
                })
        },
        okEdit: function () {
            var textareaValue = this.tt[this.target_key];
            var timeValue = this.dt[this.target_key];
            const formData = new FormData();

            if (textareaValue.length == 0) {
                if(!confirm('textareaの中身が空です。削除されますか？')){
                    /* キャンセルの時の処理 */

                    this.tt[this.target_key] = this.formal_text;
                    this.editAction = false;
                    this.target_key = '';
                    this.target_tweet_id = '';
                    this.formal_text = '';
                    this.formal_time = '';
                } else {
                    formData.append('tweet_id', this.target_tweet_id);

                    this.$axios.post('/api/twitter/tweetDelete', formData)
                        .then((res) => {
                            const tweetDom = document.getElementById('tweet_data' + this.target_key);
                            tweetDom.remove();
                            var tweetCount = $("#js-tweet-count").text();
                            tweetCount = Number(tweetCount) - 1;
                            $("#js-tweet-count").text(tweetCount);

                            this.editAction = false;
                            this.target_key = '';
                            this.target_tweet_id = '';
                            this.formal_text = '';
                            this.formal_time = '';
                            console.log('editTweetValueは正常に起動しました。')
                        })
                        .catch((error) => {
                            console.log(error)
                            console.log('editTweetValueは正常に起動していません。')
                        })
                }
            } else if(textareaValue.length > 140) {
                alert('tweet内容は140文字以下でご入力ください');
            } else {
                formData.append('tweet_id', this.target_tweet_id);
                formData.append('tweet_text', textareaValue);
                formData.append('tweet_time', timeValue);

                this.$axios.post('/api/twitter/tweetEdit', formData)
                    .then((res) => {
                        this.tweet_list[this.target_key].tweetText = textareaValue;
                        this.tweet_list[this.target_key].tweetTime = timeValue;

                        this.tt[this.target_key] = textareaValue;
                        this.dt[this.target_key] = timeValue;
                        this.editAction = false;
                        this.target_key = '';
                        this.target_tweet_id = '';
                        console.log('editOkは正常に起動しました。')
                    })
                    .catch((error) => {
                        console.log(error)
                        console.log('editOkは正常に起動していません。')
                    })
            }
        },
        dateTimes: function () {
            var array = [];
            
            $.each(this.tweet_list, function(key, value) {

                array.push(value.tweetTime);
            
            })

            return array;
        },
        tweetData: function () {
            var array = [];
            
            $.each(this.tweet_list, function(key, value) {

                array.push(value.tweetText);
            
            })

            return array;
        },
        userIds: function () {
            var array = [];
            
            $.each(this.tweet_list, function(key, value) {

                array.push(value.id);
            
            })

            return array;
        },
    },
}
</script>
