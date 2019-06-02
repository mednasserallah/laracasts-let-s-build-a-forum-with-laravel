<template>
        <div :id="'reply-' + reply.id" class="card mb-3" :class="isBest ? 'border-success' : 'border-light'">
            <div class="card-header level">
                <h5 class="float-left">
                    <a href="#">
                        {{ reply.owner.name }}
                    </a>
                    said {{ ago }}
                </h5>

                <div class="float-right">
                    <template v-if="signedIn">
                        <favorite :reply="reply"></favorite>
                    </template>
                    <template v-else>
                        <div>
                            <span class="fas fa-heart mr-1"></span>
                            <span>{{ reply.favorites_count }}</span>
                        </div>
                    </template>
                </div>
            </div>
            <div class="card-body" :class="isBest ? 'text-success' : ''">

                <div v-if="editing">
                    <form action="" @submit.prevent="updateReply">
                        <div class="form-group">
                            <textarea class="form-control"
                                      name="body"
                                      v-model="reply.body"
                                      @keydown.shift.enter="updateReply"
                                      required
                            ></textarea>
                        </div>

                        <button class="btn btn-sm btn-link" @click="cancelEdit" type="button">Cancel</button>
                        <button class="btn btn-sm btn-primary" type="submit">Update</button>
                    </form>
                </div>

                <div v-else>
                    <p class="card-text" v-html="reply.body"></p>
                </div>

            </div>

            <!--@can ('delete', $reply)-->
                <div class="card-footer">
                    <template v-if="authorize('updateReply', reply)">
                        <button class="btn btn-outline-primary btn-sm float-left mr-2"
                                @click="editing = true"
                                v-if="!editing">Edit
                        </button>

                        <button class="btn btn-danger btn-sm float-left mr-2"
                                @click="deleteReply"
                                v-if="!editing">Delete
                        </button>
                    </template>

                    <button class="btn btn-success btn-sm float-right mr-2"
                            v-show="signedIn && ! isBest && ! editing"
                            @click="markBestReply">Best Reply
                    </button>
                </div>
            <!--@endcan-->
        </div>
</template>

<script>
    import moment from 'moment';

    export default {
        props: {
            data: {
                type: [Object, Array],
                required: true
            },
        },

        data() {
            return {
                editing: false,
                reply: this.data,
                isBest: false
            }
        },

        computed: {
            user() {
                return window.App.user;
            },

            ago() {
                return moment(this.reply.created_at).fromNow();
            }
        },

        methods: {
            updateReply() {
                axios
                    .patch('/replies/' + this.data.id, {
                        body: this.reply.body
                    })
                    .then(() => {
                        this.data.body = this.reply.body;
                        this.editing = false;

                        flash('Your data has been updated !');
                    }).catch(error => {
                        flash(error.response.data, 'danger');
                    });
            },

            cancelEdit() {
                this.reply.body = this.data.body;

                this.editing = false;
            },

            deleteReply() {
                axios
                    .delete('/replies/' + this.data.id)
                    .then(() => {
                        this.$emit('reply-deleted');

                        flash('Your reply has been deleted');
                    })
            },

            markBestReply() {
                this.isBest = true;
                //
                // axios
                //     .post('/replies/' + this.data.id + '/best')
                //     .then(() => {
                //         this.isBest = true;
                //         this.$emit('reply-marked-best');
                //
                //         // flash('Your reply has been deleted');
                //     })
            }

        },
    }
</script>

<style scoped>
    [v-cloak] {
        display: none;
    }
</style>
