<template>
    <div v-if="shouldPaginate" class="d-flex justify-content-center mt-5">
        <nav aria-label="...">
            <ul class="pagination">

                <li class="page-item" :class="{ disabled: currentPage === 1 }">
                    <a class="page-link" href="#" rel="prev" tabindex="-1" aria-disabled="true" @click.prevent="currentPage--">Previous</a>
                </li>


                <li v-for="page in total" class="page-item" :class="{ active: currentPage === page}" :aria-current="currentPage">
                    <a class="page-link" href="#" @click.prevent="currentPage = page">{{ page }}</a>
                </li>

                <!--<li class="page-item" aria-current="page">-->
                    <!--<a class="page-link" href="#">2-->
                        <!--<span class="sr-only">(current)</span>-->
                    <!--</a>-->
                <!--</li>-->

                <!--<li class="page-item">-->
                    <!--<a class="page-link" href="#">3</a>-->
                <!--</li>-->

                <li class="page-item" :class="{ disabled: currentPage === total }">
                    <a class="page-link" href="#" rel="next" @click.prevent="currentPage++">Next</a>
                </li>

            </ul>
        </nav>
    </div>
</template>

<script>
    export default {
        props: {
            dataSet: {
                type: [Object, Array],
                required: true
            }
        },

        data() {
            return {
                prevUrl: false,
                nextUrl: false,
                currentPage: 1
            }
        },

        computed: {
            shouldPaginate() {
                return !! this.prevUrl || !! this.nextUrl;
            }
        },

        watch: {
            dataSet() {
                this.currentPage = this.dataSet.current_page;
                this.prevUrl = this.dataSet.prev_page_url;
                this.nextUrl = this.dataSet.next_page_url;
                this.total = Math.ceil(this.dataSet.total / this.dataSet.per_page);
            },

            currentPage() {
                this.broadcast().updateUrl();
            }
        },

        methods: {
            broadcast() {
                return this.$emit('page-changed', this.currentPage);
            },

            updateUrl() {
                history.pushState(null, null, '?page=' + this.currentPage);
            }
        },

    }
</script>

<style scoped>

</style>
