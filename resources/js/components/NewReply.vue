<template>
    <div>
        <template v-if="auth">
            <!--<form method="POST" action="{{ route('threads.replies.store', [$thread->channel->id, $thread->id]) }}">-->

                <div class="form-group">
                    <at-ta :members="members">
                        <textarea   class="form-control"
                                    name="body"
                                    placeholder="Have something to say?"
                                    rows="3"
                                    v-model="body"
                                    @keypress.shift.enter="addReply"
                                    required>
                        </textarea>
                    </at-ta>
                </div>

                <button class="btn btn-primary" @click="addReply">Post</button>
        </template>
        <template v-else>
            <p class="text-center">Please <a href="/login">sign in</a> to participate in this discussion.</p>
        </template>
    </div>
</template>

<script>
    import AtTa from 'vue-at/dist/vue-at-textarea';

    export default {

        components: {
            AtTa,
        },

        data() {
            return {
                body: '',
                endpoint: location.pathname + '/replies',

                members: []
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
            },

            fetchUsernames(chunk = '') {
                axios
                    .get(`/api/users?username=${chunk}`)
                    .then(({data}) => {
                        return this.members = data;
                    })
            },


            // fetchFromRemote: _.debounce(function (chunk) {
            //     if (chunk) {
            //         axios
            //             .get(`/api/users?username=${chunk}`)
            //             .then(({data}) => {
            //                 this.members = data;
            //             })
            //     }
            // }, 500) // delay 500ms after each keystroke

            // async handleAt (chunk) {
            //     this.members = await this.fetchFromRemote(chunk)
            // }
        },

        created() {
            this.members = this.fetchUsernames();
        }

    }
</script>

<style scoped>

</style>
