<template>
    <div class="c-structure__article__panel__group">
        <div class="jsModal" v-show="deleteAction === true">
            <div class="c-overlay">
                <div class="c-overlay__contents">
                    <div class="c-overlay__ttl">こちらのアカウントを外しますか？</div>
                    <div class="c-overlay__btnContainer">
                        <button class="c-appBtn" v-on:click="okDelete()">はい</button>
                        <button class="c-appBtn" v-on:click="deleteCancel()">いいえ</button>
                    </div>
                </div>
            </div>
        </div>
        <div v-for="(account, i) in accounts" :key="i" class="c-structure__article__panel" :class="{ authFlgActive: account.auth_flg}">
            <div class="c-structure__article__panel__status" v-show="account.auth_flg" :class="{ authFlgActive: account.auth_flg}">
                稼働中</div>
            <div class="c-structure__article__panel__upDatedAt">更新日時: {{ account.updated_at }}</div>
            <i class="fas fa-trash-restore u-trash c-structure__trash " v-on:click="deleteAccount(account.screen_name)"></i>
            <div class="c-structure__article__panel__leftInfo">
                <div class="c-structure__article__panel__photo">
                    <img :src="account.profile_photo_path.replace( '_normal.', '.')" alt="twitter photo img">
                </div>
            </div>
            <div class="c-structure__article__panel__rightInfo">
                <div class="c-structure__article__panel__name c-structure__article__panel__item">
                    <p class="c-structure__article__panel__head">アカウント名</p>
                    <p class="c-structure__article__panel__info">{{ account.screen_name }}</p>
                </div>
                <div class="c-structure__article__panel__follow c-structure__article__panel__item">
                    <p class="c-structure__article__panel__head">フォロー数</p>
                    <p class="c-structure__article__panel__info">{{ account.follow }}</p>
                </div>
                <div class="c-structure__article__panel__follower c-structure__article__panel__item">
                    <p class="c-structure__article__panel__head">フォロワー数</p>
                    <p class="c-structure__article__panel__info">{{ account.follower }}</p>
                </div>
                <div class="c-structure__article__panel__follower c-structure__article__panel__item">
                    <p class="c-structure__article__panel__head">本日の自動フォローした数</p>
                    <p class="c-structure__article__panel__info">{{ account.follow_count }}</p>
                </div>
                <div class="c-structure__article__panel__follower c-structure__article__panel__item">
                    <p class="c-structure__article__panel__head">本日のフォローされた数</p>
                    <p class="c-structure__article__panel__info">{{ account.follower_count }}</p>
                </div>
                <div class="c-structure__article__panel__follower c-structure__article__panel__item">
                    <p class="c-structure__article__panel__head">本日の自動アンフォロー数</p>
                    <p class="c-structure__article__panel__info">{{ account.unFollow_count }}</p>
                </div>
                <div class="c-structure__article__panel__follower c-structure__article__panel__item">
                    <p class="c-structure__article__panel__head">本日の自動いいねした数</p>
                    <p class="c-structure__article__panel__info">{{ account.like_count }}</p>
                </div>
                <div class="c-structure__article__panel__follower c-structure__article__panel__item">
                    <p class="c-structure__article__panel__head">本日のいいねされた数</p>
                    <p class="c-structure__article__panel__info">{{ account.like_count_get }}</p>
                </div>
                <div class="c-structure__article__panel__follower c-structure__article__panel__item">
                    <p class="c-structure__article__panel__head">本日の自動ツイート数</p>
                    <p class="c-structure__article__panel__info">{{ account.tweet_count }}</p>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: ['accounts'],
    data: function() {
        return {
            deleteAction: false,
            deleteName: '',
        }
    },
    methods: {
        deleteAccount: function(screen_name) {
            this.deleteAction = true;
            this.deleteName = screen_name;
        },
        deleteCancel: function() {
            this.deleteAction = false;
            this.deleteName = '';
        },
        okDelete: function() {
            const formData = new FormData();
            
            formData.append('screen_name', this.deleteName);

            this.$axios.post('/api/twitter/account/delete', formData)
                .then((res) => {
                    window.location.reload(false);
                })
                .catch((error) => {
                    console.log('okDeleteは正常に動いてません');
                    console.log(error)
                })
        }
    },
}
</script>
