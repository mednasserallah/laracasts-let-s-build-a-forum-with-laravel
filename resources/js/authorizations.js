let user = window.App.user;

module.exports = {
    updateReply(reply) {
        return reply.user_id === user.id;
    },

    owns(model, ownerProp = 'user_id') {
        return model[ownerProp] === user.id;
    },

    isAdmin() {
        return ['Nasmed', 'Sof'].includes(user.name);
    }
};
