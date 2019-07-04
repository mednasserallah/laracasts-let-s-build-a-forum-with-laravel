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
                repliesCount: this.thread.replies_count,
                isLocked: this.thread.is_locked,
                editing: false
            }
        },

        methods: {
            lock() {
                axios
                    .post('/lock-threads/' + this.thread.slug)
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
            }


        },
    }
</script>
