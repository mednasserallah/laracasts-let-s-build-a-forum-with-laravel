export default {
    data() {
        return {
            items: []
        }
    },
    
    methods: {
        remove(index) {
            this.items.splice(index, 1);

            this.$emit('reply-removed');
        },

        add($reply) {
            this.items.push($reply);

            this.$emit('reply-added');
        }
    }
}
