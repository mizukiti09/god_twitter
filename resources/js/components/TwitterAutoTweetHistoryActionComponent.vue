<template>
<div v-if="tweeted_list.length">
    <div class="jsModal" v-show="resetAction === true">
        <div class="c-overlay">
            <div class="c-overlay__contents">
                <div class="c-overlay__ttl">ツイート履歴をリセットしますか？</div>
                <div class="c-overlay__btnContainer">
                    <button class="c-appBtn" v-on:click="okReset()">はい</button>
                    <button class="c-appBtn" v-on:click="resetCancel()">いいえ</button>
                </div>
            </div>
        </div>
    </div>
    <div class="c-appBtn">
        <button v-on:click="resetOpen()" class="c-appBtn--none p-reset__button">
            履歴リセット
        </button>
    </div>
    <ul class="p-tweetList">
        <li v-for="(tweet, i) in tweeted_list" :key="i"
            :id="'tweet_data' + i" 
            class="p-tweetList__item p-tweetList__item--tweeted" 
        >
            <div class="p-tweetList__item__datePlan">
                {{tweet.tweetTime}}
            </div>
            <div>
                {{tweet.tweetText}}
            </div> 
        </li>
        
    </ul>
</div>
</template>

<script>

export default {
    props: ['user_id', 'auth_screen_name', 'auto_tweet_flg', 'tweeted_list'],
    data: function () {
        return {
            resetAction: false,
        }
    },
    methods: {
        resetOpen: function() {
            this.resetAction = true;
        },
        resetCancel: function() {
            this.resetAction = false;
        },
        okReset: async function() {
            const formData = new FormData();
            
            formData.append('user_id', this.user_id);
            formData.append('screen_name', this.auth_screen_name);

            try {
                await this.$axios.post('/api/twitter/tweetHistoryReset', formData);
                window.location.reload(false);
            } catch (error) {
                alert('予期せぬシステムエラーです。');
            }
        },
    },
}
</script>

<style>
    .p-reset__button {
        margin: 20px 0 10px;
        float: right;
        padding: 6px;
        border-radius: 10px;
    }
</style>