<template>
    <li
        class="list-group-item"
        @mouseenter.stop="hover = true"
        @mouseleave.stop="hover = false"
    >
        <div class="row">
            <div class="col-sm-1 col-md-1 col-lg-1">
                <input type="checkbox" v-model="task.done" :disabled="editing" @change="submit">
            </div>
            <div class="col-sm-9  col-md-9 col-lg-9">
                <div class="form-group" :class="{'has-error': errors.description}" v-if="editing">
                    <input
                        :ref="'descriptionInput'"
                        type="text"
                        class="form-control"
                        v-model="description"
                        @keyup.enter="submit"
                        @keyup.esc="cancel"
                        @focus="removeErrors('description')"
                    >
                </div>

                <span v-else @dblclick="edit">
                    {{ task.description }}
                </span>
            </div>
            <div class="col-sm-2  col-md-2 col-lg-2">
                <div v-if="hover" class="pull-right">
                    <span v-if="editing" class="glyphicon glyphicon-ban-circle text-danger" aria-hidden="true" @click="cancel"></span>
                    <span v-else class="glyphicon glyphicon-pencil" aria-hidden="true" @click="edit"></span>

                    <span class="glyphicon glyphicon-trash" aria-hidden="true" @click="remove"></span>
                </div>
            </div>
        </div>
    </li>
</template>

<script>
import ErrorsMixin from './ErrorsMixin.js';

export default {
    mixins: [
        ErrorsMixin
    ],

    props: ['task'],

    data() {
        return {
            hover: false,
            editing: false,
            description: null
        }
    },

    created() {
        this.description = this.task.description;
    },

    methods: {
        edit() {
            this.description = this.task.description;
            this.editing = true;
        },

        submit() {
            const task = Object.assign({}, this.task, {description: this.description});

            this.$http.put(`/projects/${this.task.project_id}/tasks/${task.id}`, task).then(response => {
                this.task.description = response.data.description;
                this.editing = false;
            }, error => {
                const response = error.response;

                if (response.status === 422) {
                    this.errors = response.data;
                }
            });
        },

        cancel() {
            this.editing = false;
        },

        remove() {
            this.$http.delete(`/projects/${this.task.project_id}/tasks/${this.task.id}`).then(response => {
                this.$emit('remove');
            }, error => {

            });
        },
    }
}
</script>

<style scoped>
    .form-group {
        margin: 0;
    }

    .form-group input {
        padding: 0;
        height: 20px;
    } 
</style>
