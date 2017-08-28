<template>
    <div>
        <project
            v-for="(project, index) in projects"
            :key="project.id"
            :project.sync="project"
            @remove="remove(index)"
        >
        </project>

        <div class="text-center">
            <button type="button" class="btn btn-primary" @click="add">
                <span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Add TODO List
            </button>
        </div>
    </div>
</template>

<script>
import Project from './Project.vue';

export default {
    components: {
        Project
    },

    props: ['dataProjects'],

    data() {
        return {
            projects: [],
            boilerplate: {
                tasks: []
            }
        };
    },

    created() {
        this.projects = this.dataProjects;
    },

    methods: {
        add() {
            const project = Object.assign(this.boilerplate, {title: "New TODO List"});
            
            this.$http.post('/projects', project).then(response => {
                this.projects.push(response.data);
            }, response => {
                console.log(`Error: ${response}`);
            });
        },

        remove(index) {
            Vue.delete(this.projects, index);
        }
    }
}
</script>

