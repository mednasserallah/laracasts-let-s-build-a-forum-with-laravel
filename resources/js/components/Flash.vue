<template>
    <div v-show="show" class="alert alert-success alert-flash" role="alert">
        <strong>Success!</strong> {{ body }}
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
                show: false
            }
        },

        methods: {
            flash(message) {
                this.body = message;
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

            window.events.$on('flash', message => {
                this.flash(message);
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
