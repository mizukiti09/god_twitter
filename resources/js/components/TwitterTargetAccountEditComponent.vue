<template>
    <div>
        <ul class="p-targetAccount__list">
            <li v-for="(account, i) in target_accounts" :key="i" class="p-targetAccount__list__item">
                【AccountName: {{ account.screen_name }}】,【Follower: {{account.follower}}】
            </li>
        </ul>
    </div>
</template>

<script>

export default {
    props: ['user_id', 'auth_screen_name', 'auto_follow_flg', 'target_accounts'],
    data: function () {
        return {
            
        }
    },
    methods: {
        
    },
}
</script>
