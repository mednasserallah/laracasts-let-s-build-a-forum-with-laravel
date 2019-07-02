<template>
    <button :class="btnClasses" @click="toggleSubscription">
        {{ btnName }}
    </button>
</template>

<script>
    export default {
        props: {
            isSubscribed: {
                type: Boolean,
                required: true
            },
        },

        data() {
            return {
                active: this.isSubscribed
            }
        },

        computed: {
            btnClasses() {
                return ['btn mt-2', this.active ? 'btn-success' : 'btn-secondary'];
            },

            btnName() {
                return this.active ? 'Unsubscribe' : 'Subscribe';
            }
        },

        methods: {
            subscribe() {
                axios
                    .post(this.url())
                    .then(() => {
                        this.active = true;
                        flash('Subscribed');
                    });
            },

            unsubscribe() {
                axios
                    .delete(this.url())
                    .then(() => {
                        this.active = false;
                        flash('Unsubscribed');
                    });
            },

            toggleSubscription() {
                this.active ? this.unsubscribe() : this.subscribe();
            },

            url() {
                return location.pathname + '/subscriptions';
            }
        },
    }
</script>

<style scoped>

</style>
