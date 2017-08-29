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
                            @focus="removeErrors('title')"
                        >
                    </div>

                    <span v-show="!editing" @dblclick="edit" class="project-title">
                        {{ project.title }}
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
        </div>


        <div class="panel-body">
            <task-form :project-id="project.id" @add="addTask"></task-form>

            <ul class="list-group">
                <task
                    v-for="(task, index) in project.tasks"
                    :key="task.id"
                    :task.sync="task"
                    @remove="removeTask(index)"
                ></task>    
            </ul>
        </div>
    </div>
</template>

<script>
import TaskForm from './TaskForm.vue';
import Task from './Task.vue';
import ErrorsMixin from './ErrorsMixin.js';

export default {
    mixins: [
        ErrorsMixin
    ],

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
        };
    },

    computed: {
        // // const order = this.project.tasks_order || [20, 19, 18];

        // orderedTasks() {
        //     const order = [20, 19, 18];
            
        //     return this.project.tasks.sort((a, b) => {
        //         return order.indexOf(a) - order.indexOf(b);
        //     });
        // }
    },

    created() {
        this.title = this.project.title;
        this.sortTasks();
    },

    methods: {
        edit() {
            this.title = this.project.title;
            this.editing = true;
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
            }, error => {

            });
        },

        addTask(task) {
            if (this.project.tasks === undefined || this.project.tasks === null) {
                Vue.set(this.project, 'tasks', []);
            }

            this.project.tasks.push(task);
        },

        removeTask(index) {
            Vue.delete(this.project.tasks, index);
        },

        sortTasks() {
            const order = this.project.tasks_order || [];

            this.project.tasks && this.project.tasks.sort((a, b) => {
                return order.indexOf(a.id) - order.indexOf(b.id);
            });
        }
    }
}
</script>

<style scoped>
    .panel-heading {
        height: 56px;
    }

    .panel-heading .glyphicon {
        font-size: 2em;
        margin-top: 3px;
    }

    .form-group {
        margin-bottom: 10px;
    }

    .project-title {
        font-size: 22px;
    }

    .list-group {
        margin: 0;
    }
</style>
