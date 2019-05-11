<!--inline-template-->

<script>
    import Replies from '../components/Replies';
    import SubscribeButton from '../components/SubscibeButton';

    export default {
        components: {
            Replies, SubscribeButton
        },

        props: {
            iniRepliesCount: {
                type: Number
            },
        },

        data() {
            return {
                repliesCount: this.iniRepliesCount
            }
        },
    }
</script>
