<template>
    <li
        class="list-group-item"
        @mouseenter.stop="hover = true"
        @mouseleave.stop="hover = false"
    >
        <div class="row">
            <div class="col-sm-1 col-md-1 col-lg-1">
                <input type="checkbox" v-model="task.done">
            </div>
            <div class="col-sm-9  col-md-9 col-lg-9">
                <div class="form-group" v-if="editing">
                    <input
                        :ref="'descriptionInput'"
                        type="text"
                        class="form-control"
                        v-model="description"
                        @keyup.enter="submit"
                        @keyup.esc="cancel"
                        @blur="cancel"
                        autofocus
                    >
                </div>

                <span v-else @dblclick="edit">
                    {{ task.description }}
                </span>
            </div>
            <div class="col-sm-2  col-md-2 col-lg-2">
                <div v-if="hover" class="pull-right">
                    <span class="glyphicon glyphicon-pencil" aria-hidden="true" @click="edit"></span>
                    <span class="glyphicon glyphicon-trash" aria-hidden="true"></span>
                </div>
            </div>
        </div>
    </li>
</template>

<script>
export default {
    props: ['task'],

    data() {
        return {
            hover: false,
            editing: false,
            description: null,
        }
    },

    created() {
        this.description = this.task.description;
    },

    methods: {
        edit() {
            this.description = this.task.description;
            this.editing = true;
            this.$refs.descriptionInput.focus();
        },

        submit() {
            this.task.description = this.description;
            this.editing = false;
            console.log('OK');
        },

        cancel() {
            this.editing = false;
        }
    }
}
</script>

<style scoped>
    .form-group {
        margin: 0;
    }
</style>
