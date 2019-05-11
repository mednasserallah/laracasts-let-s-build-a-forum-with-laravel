<template>
    <button type="submit" class="btn" :class="btnFavorite" @click="toggleFavorite">
        <span class="fas fa-heart mr-1"></span>
        <span v-text="favoriteCount"></span>
    </button>
</template>

<script>
    export default {
        props: {
            reply: {
                type: Object,
                required: true
            },
        },

        data() {
            return {
                favoriteCount: this.reply.favorites_count,
                isFavorited: this.reply.is_favorited
            }
        },

        computed: {
            btnFavorite() {
                return [this.isFavorited ? 'btn-primary' : 'btn-default'];
            },

            endpoint() {
                return '/replies/' + this.reply.id + '/favorites';
            }
        },

        methods: {
            toggleFavorite() {
                this.isFavorited ? this.unfavorite() : this.favorite();
                this.isFavorited = ! this.isFavorited;
            },

            unfavorite() {
                axios.delete(this.endpoint);
                this.favoriteCount--;
            },

            favorite() {
                axios.post(this.endpoint);
                this.favoriteCount++;
            }
        }

    }
</script>

<style scoped>

</style>
