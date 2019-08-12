<template>
    <div class="c-card u-p-medium u-mb-medium">
        <h5 class="u-h6 u-mb-medium">Детали задачи</h5>

        <table class="u-width-100">
            <tbody>
            <tr>
                <td class="u-color-primary u-text-small" style="width: 308px;">Приоритет</td>
                <td class="u-text-mute u-text-small">
                    <v-select label="name" @input="setPriority" :options="priorities" v-model="sPriority"></v-select>
                </td>
            </tr>
            <tr>
                <td class="u-color-primary u-text-small">Тип</td>
                <td class="u-text-mute u-text-small">
                    <v-select label="name" @input="setType" :options="types" v-model="sType"></v-select>
                </td>
            </tr>
            <tr>
                <td class="u-color-primary u-text-small">Направление</td>
                <td class="u-text-mute u-text-small">
                    <v-select label="name" @input="setArea" :options="areas" v-model="sArea"></v-select>
                </td>
            </tr>
            </tbody>
        </table>
    </div>
</template>

<script>
    export default {
        name: "DetailsCreate",
        data: function () {
            return {
                priorities: [],
                types: [],
                areas: [],
                sPriority: null,
                sType: null,
                sArea: null
            }
        },
        created() {
            this.loadData();
        },
        methods: {
            loadData: function () {
                this.$http.get('/task/priorities').then(response => (
                    this.priorities = response.data
                ));
                this.$http.get('/task/types').then(response => (
                    this.types = response.data
                ));
                this.$http.get('/task/areas').then(response => (
                    this.areas = response.data
                ));
            },
            setPriority: function () {
                this.$store.commit('setPriority', this.sPriority)
            },
            setType: function () {
                this.$store.commit('setType', this.sType)
            },
            setArea: function () {
                this.$store.commit('setArea', this.sArea)
            }
        }
    }
</script>

<style>

</style>