<template>
    <div>
        <div class="mb-2 input-group">
            <img class="mr-2" :src="avatar" alt="user avatar" width="50">
            <h1>{{ user.name }}</h1>
        </div>

        <form class="form-group mt-3" v-if="canUpdate" method="POST" enctype="multipart/form-data">
            <image-upload name="avatar" @image-loaded="onLoad"></image-upload>
        </form>
    </div>
</template>

<script>
    import ImageUpload from './ImageUpload.vue';

    export default {
        components: {
            ImageUpload,
        },

        props: {
            user: {
                type: [Object, Array],
                required: false
            },
        },

        data() {
            return {
                avatar: this.user.avatar
            }
        },

        computed: {
            canUpdate() {
                return this.authorize(user => user.id === this.user.id);
            }
        },

        methods: {
            onLoad(avatar) {
                this.avatar = avatar.src;

                this.persist(avatar.file);
            },

            persist(avatar) {

                let data = new FormData();

                data.append('avatar', avatar);

                axios
                    .post(`/api/users/${this.user.name}/avatar`, data)
                    .then(() => flash('Avatar uploaded!'));
            }
        },
    }
</script>

<style scoped>

</style>
