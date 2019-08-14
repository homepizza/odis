<template>
    <article class="c-stage" style="min-height: 355px;">
        <div class="c-stage__header o-media u-justify-start">
            <div class="c-stage__icon o-media__img">
                <i class="fa fa-laptop"></i>
            </div>
            <div class="c-stage__header-title o-media__body">
                <h6 class="u-mb-zero">Заголовок</h6>
                <p class="u-text-xsmall u-text-mute">Была в работе: 05.08.2019 | Ожидается через: 10 дней(-я)</p>
            </div>
        </div>

        <div class="c-stage__panel u-p-medium">

            <p class="u-text-mute u-text-uppercase u-text-small u-mb-xsmall">Описание</p>
            <div class="u-mb-medium u-text-mute u-text-small" style="list-style-type: revert !important;">
                Контент
            </div>

            <p class="u-text-mute u-text-uppercase u-text-small u-mb-xsmall">Прикрепленные файлы</p>
            <div class="row u-mb-medium">
                <div class="col-md-6 col-lg-4">
                    <ul>
                        <li class="u-mb-xsmall u-text-small u-color-primary">
                            <i class="fa fa-file-image-o u-text-mute u-mr-xsmall"></i>
                            <a href="">Пример отчета.png</a>
                        </li>
                    </ul>
                </div>

                <div class="col-md-6 col-lg-8">
                    <ul>
                        <li class="u-mb-xsmall u-text-small u-color-primary">
                            <i class="fa fa-file-text-o u-text-mute u-mr-xsmall"></i>
                            <a href="">Отчет.xlsx</a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="row" style="margin-top: 20px;"></div>
        </div>
    </article>
</template>

<script>
    export default {
        name: "Task"
    }
</script>

<style>

</style>