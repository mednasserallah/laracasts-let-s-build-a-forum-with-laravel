<!--inline-template-->

<script>
    import Replies from '../components/Replies';
    import SubscribeButton from '../components/SubscibeButton';

    export default {
        components: {
            Replies, SubscribeButton
        },

        props: {
            thread: {
                type: [Object, Array]
            },
        },

        data() {
            return {
                title: this.thread.title,
                body: this.thread.body,

                repliesCount: this.thread.replies_count,
                isLocked: this.thread.is_locked,
                editing: false,

                form: this.resetForm()
            }
        },

        methods: {
            resetForm() {
                return {
                    title: this.thread.title,
                    body: this.thread.body
                }
            },

            lock() {
                let url = '/lock-threads/' + this.thread.slug;

                axios
                    .post(url)
                    .then(() => {
                        this.isLocked = true;
                    })
            },

            unlock() {
                axios
                    .delete('/lock-threads/' + this.thread.slug)
                    .then(() => {
                        this.isLocked = false;
                    })
            },

            toggleLock() {
                if (this.isLocked) {
                    this.unlock()
                } else {
                    this.lock()
                }
            },

            update() {
                let url = `/threads/${this.thread.channel.slug}/${this.thread.slug}`;

                axios
                    .patch(url, this.form)
                    .then(() => {
                        this.title = this.form.title;
                        this.body = this.form.body;
                        this.editing = false;
                        flash('Your thread has been updated!');
                    });

            },

            editOrCancel() {
                if (this.editing) {
                    this.form = this.resetForm();
                }

                this.editing = !this.editing;
            }
        },
    }
</script>
