const state = {
    id: null,
    name: '',
    about: '',
    phone: '',
    email: '',
    jobPosition: '',
    birthday: '',
    emailNotify: false,
    telegramNotify: false,
    profileWaitUpdate: false
};

const mutations = {
    setProfileId (state, id) {
        state.id = id;
    },
    setProfileName (state, name) {
        state.name = name;
    },
    setProfileAbout (state, about) {
        state.about = about;
    },
    setProfilePhone (state, phone) {
        state.phone = phone;
    },
    setProfileEmail (state, email) {
        state.email = email;
    },
    setProfileJobPosition (state, jobPosition) {
        state.jobPosition = jobPosition;
    },
    setProfileBirthday (state, birthday) {
        state.birthday = birthday;
    },
    setProfileEmailNotify (state, flag) {
        state.emailNotify = flag;
    },
    setProfileTelegramNotify (state, flag) {
        state.telegramNotify = flag;
    },
    setProfileWaitUpdate (state, flag) {
        state.profileWaitUpdate = flag;
    }
};

const actions = {
    initUser(context, user) {
        context.commit('setProfileId', user.id);
        context.commit('setProfileName', user.fullname);
        context.commit('setProfileAbout', user.about);
        context.commit('setProfilePhone', user.uuid);
        context.commit('setProfileEmail', user.email);
        context.commit('setProfileJobPosition', user.jobPosition);
        context.commit('setProfileBirthday', user.birthday);
        context.commit('setProfileEmailNotify', user.emailNotify);
        context.commit('setProfileTelegramNotify', user.telegramNotify);
    }
};

const getters = {
    getProfile: state => {
        let profile = {
            id: state.id,
            name: state.name,
            about: state.about,
            phone: state.phone,
            email: state.email,
            jobPosition: state.jobPosition,
            birthday: state.birthday,
            emailNotify: state.emailNotify,
            telegramNotify: state.telegramNotify
        };
        return profile;
    }
};

export default {
    state,
    mutations,
    actions,
    getters
}