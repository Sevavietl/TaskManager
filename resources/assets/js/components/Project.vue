<template>
    <div class="panel panel-primary">
        <div    
            class="panel-heading"
            @mouseenter.stop="hover = true"
            @mouseleave.stop="hover = false"
        >
            <div class="row">
                <div class="col-sm-1 col-md-1 col-lg-1">
                    <span class="glyphicon glyphicon-calendar" aria-hidden="true"></span>
                </div>
                <div class="col-sm-9  col-md-9 col-lg-9">
                    <div class="form-group" :class="{'has-error': errors.title}" v-show="editing">
                        <input
                            ref="titleInput"
                            type="text"
                            class="form-control"
                            v-model="title"
                            @keyup.enter="submit"
                            @keyup.esc="cancel"
                            @focusout="cancel"
                            autofocus
                        >
                        <span v-if="errors.title" class="help-block">{{ errors.title.join(' ') }}</span>
                    </div>

                    <span v-show="!editing" @dblclick="edit">
                        {{ project.title }}
                    </span>
                </div>
                <div class="col-sm-2  col-md-2 col-lg-2">
                    <div v-if="hover" class="pull-right">
                        <span class="glyphicon glyphicon-pencil" aria-hidden="true" @click="edit"></span>
                        <span class="glyphicon glyphicon-trash" aria-hidden="true" @click="remove"></span>
                    </div>
                </div>
            </div>
        </div>


        <div class="panel-body">
            <task-form></task-form>

            <ul class="list-group">
                <task
                    v-for="task in project.tasks"
                    :key="task.id"
                    :task.sync="task"
                ></task>    
            </ul>
        </div>
    </div>
</template>

<script>
import TaskForm from './TaskForm.vue';
import Task from './Task.vue';

export default {
    components: {
        TaskForm,
        Task
    },

    props: ['project'],

    data() {
        return {
            hover: false,
            editing: false,
            title: null,
            errors: {},
        };
    },

    created() {
        this.title = this.project.title;
    },

    methods: {
        edit() {
            this.title = this.project.title;
            this.editing = true;
            this.$refs.titleInput.focus();
            this.$refs.titleInput.select();
        },

        submit() {
            this.$http.put(
                `/projects/${this.project.id}`,
                {title: this.title}
            ).then(response => {
                this.project.title = response.data.title;
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
            this.$http.delete(`/projects/${this.project.id}`).then(response => {
                this.$emit('remove');
            }, response => {

            });
        }
    }
}
</script>

<style scoped>
    .list-group {
        margin: 0;
    }
</style>
