<template>
    <div>
        <div v-for="(reply, index) in items" :key="reply.id">
            <reply :reply="reply" @reply-deleted="remove(index)"></reply>
        </div>

        <new-reply @reply-created="add"></new-reply>

        <paginator :data-set="dataSet" @page-changed="fetchReplies"></paginator>
    </div>
</template>

<script>
    import Reply from './Reply.vue';
    import NewReply from '../components/NewReply.vue';
    import collection from '../mixins/collection'

    export default {
        components: {
            Reply, NewReply
        },

        mixins: [collection],

        data() {
            return {
                dataSet: {},
            }
        },

        methods: {
            fetchReplies(page) {
                axios
                    .get(this.url(page))
                    .then((response) => this.refresh(response))
            },

            url(page) {

                if (! page) {
                    let params = new URLSearchParams(window.location.search);
                    page = params.has('page') ? params.get('page') : 1;
                }

                return `${location.pathname}/replies?page=${page}`;
            },

            refresh({data}) {
                this.dataSet = data;
                this.items = data.data;

                window.scrollTo(0, 0);
            },
        },

        created() {
            this.fetchReplies();
        }
    }
</script>

<style scoped>

</style>
