<template>
    <div>
        <template v-if="auth">
            <!--<form method="POST" action="{{ route('threads.replies.store', [$thread->channel->id, $thread->id]) }}">-->

                <div class="form-group">
                    <textarea   class="form-control"
                                name="body"
                                placeholder="Have something to say?"
                                rows="3"
                                v-model="body"
                                @keypress.shift.enter="addReply"
                                required>
                    </textarea>
                </div>

                <button class="btn btn-primary" @click="addReply">Post</button>
        </template>
        <template v-else>
            <p class="text-center">Please <a href="/login">sign in</a> to participate in this discussion.</p>
        </template>
    </div>
</template>

<script>
    export default {

        data() {
            return {
                body: '',
                endpoint: location.pathname + '/replies'
            }
        },

        computed: {
            auth() {
                return window.App.signedIn;
            }
        },

        methods: {
            addReply() {
                axios
                    .post(this.endpoint, { body: this.body })
                    .then(({data}) => {
                        this.body = '';

                        console.log(data);

                        this.$emit('reply-created', data);

                        flash('Your reply has been posted.');
                    })
                    .catch(error => {
                        flash(error.response.data, 'danger');
                    })
            }
        },

    }
</script>

<style scoped>

</style>
