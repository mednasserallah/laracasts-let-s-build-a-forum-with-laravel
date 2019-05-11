<template>
    <div class="dropleft" v-if="notifications.length">
        <button class="btn" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <span class="fa fa-bell"></span>
        </button>
        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
            <a  v-for="(notification, index) in notifications"
                class="dropdown-item"
                :href="notification.data.link"
                @click="markAsRead(notification.id)">
                {{ notification.data.message }}
            </a>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                notifications: []
            }
        },

        methods: {
            markAsRead(notificationId) {
                axios.delete('/profiles/' + window.App.user.name + '/notifications/' + notificationId);
            }
        },

        created() {
            axios.get('/profiles/' + window.App.user.name + '/notifications')
                .then(response => this.notifications = response.data);
        }
    }
</script>
