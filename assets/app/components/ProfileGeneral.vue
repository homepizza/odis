<template>
    <div class="row">
        <div class="col-lg-2 u-text-center">
            <div class="c-avatar c-avatar--xlarge u-inline-block">
                <img class="c-avatar__img" src="/media/theme/user1.png" alt="Аватар">
            </div>

            <a class="u-block u-color-primary" href="#" v-if="false">Редактировать</a>
        </div>
        <div class="col-lg-5">
            <div class="c-field u-mb-small">
                <label class="c-field__label" for="firstName">Имя</label>
                <input class="c-input" type="text" id="firstName" placeholder="Введите ваше имя и фамилию">
            </div>

            <div class="c-field u-mb-small">
                <label class="c-field__label" for="bio">О себе</label>
                <textarea class="c-input" id="bio" placeholder="Пару слов о себе"></textarea>
            </div>

            <div class="c-field u-mb-small">
                <label class="c-field__label" for="lastName">Телефон</label>
                <input class="c-input" type="text" id="lastName" placeholder="Введите ваш номер телефона">
            </div>


            <div class="c-field u-mb-small">
                <label class="c-field__label" for="email">E-mail</label>
                <input class="c-input" id="email" type="email" placeholder="Введите ваш e-mail">
            </div>
        </div>

        <div class="col-lg-5">
            <div class="c-field u-mb-small">
                <label class="c-field__label" for="companyName">Должность в компании</label>
                <input class="c-input" id="companyName" type="text" placeholder="Администратор">
            </div>

            <div class="c-field u-mb-small">
                <label class="c-field__label" for="website">День рождения</label>
                <input class="c-input" id="website" type="text" placeholder="01.01.1994">
            </div>

            <label class="c-field__label" style="margin-top: 30px;">Уведомления о задачах</label>
            <div class="c-field has-addon-left">
                <div class="c-choice c-choice--checkbox">
                    <input class="c-choice__input" id="email-notify" name="checkboxes" type="checkbox">
                    <label class="c-choice__label" for="email-notify">E-mail</label>
                </div>
                <div class="c-choice c-choice--checkbox" style="margin-left: 13px;">
                    <input class="c-choice__input" id="telegram-notify" name="checkboxes" type="checkbox">
                    <label class="c-choice__label" for="telegram-notify">Telegram</label>
                </div>
            </div>
            <label class="c-field__label" style="margin-top: 30px;">
                Для того, чтобы приходили уведомления в telegram
                вы должны авторизоваться у бота!
            </label>
        </div>
    </div>
</template>

<script>
    export default {
        name: "ProfileGeneral",
        data: function () {
            return {
                name: '',
                about: '',
                phone: '',
                email: '',
                jobPosition: '',
                birthday: '',
                emailNotify: false,
                telegramNotify: false
            }
        },
        methods: {

        }
    }
</script>

<style>

</style>