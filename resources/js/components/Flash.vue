<template>
    <div v-show="show" class="alert alert-flash" :class="'alert-'+level" role="alert">
        {{ body }}
    </div>
</template>

<script>
    export default {
        props: {
            message: {
                type: String,
                required: false,
                default: ''
            }
        },

        data() {
            return {
                body: this.message,
                level: 'success',
                show: false
            }
        },

        methods: {
            flash(data) {
                this.body = data.message;
                this.level = data.level;
                this.show = true;

                this.hide();
            },

            hide() {
                setTimeout(() => {
                    this.show = false;
                }, 3000);
            }
        },

        created() {
            if (this.message) {
                this.flash(this.message);
            }

            window.events.$on('flash', data => {
                this.flash(data);
            })
        }
    }
</script>

<style>
    .alert-flash {
        position: fixed;
        right: 25px;
        bottom: 8px;
    }
</style>
