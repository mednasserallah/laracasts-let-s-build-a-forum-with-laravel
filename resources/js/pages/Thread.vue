<!--inline-template-->

<script>
    import Replies from '../components/Replies';
    import SubscribeButton from '../components/SubscibeButton';

    export default {
        components: {
            Replies, SubscribeButton
        },

        props: {
            dataRepliesCount: {
                type: Number
            },

            dataIsLocked: {
                type: Boolean
            }
        },

        data() {
            return {
                repliesCount: this.dataRepliesCount,
                isLocked: this.dataIsLocked
            }
        },
    }
</script>
