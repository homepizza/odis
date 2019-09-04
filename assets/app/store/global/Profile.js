const state = {
    name: '',
    about: '',
    phone: '',
    email: '',
    jobPosition: '',
    birthday: '',
    emailNotify: false,
    telegramNotify: false
};

const mutations = {
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
    }
};

const getters = {
    getProfile: state => {
        let profile = {
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
    getters
}