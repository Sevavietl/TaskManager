<template>
    <div class="input-group">
        <span class="input-group-addon">
            <span class="glyphicon glyphicon-plus text-success" aria-hidden="true"></span>
        </span>
        
        <input type="text" class="form-control" placeholder="Start typing here to create task..." v-model="description">
        
        <span class="input-group-btn">
            <button class="btn btn-success" type="button">Add Task</button>
        </span>
    </div>
</template>

<script>
export default {
    data() {
        return {
            description: ''
        }
    }
}
</script>

<style scoped>
    div {
        margin-bottom: 15px;
    }
</style>
