<template>
<div>
    <ul>
        <li v-for="task in dataProject.tasks" v-text="task.body"></li>
    </ul>

    <input type="text" v-model="newTask" @blur="save" />
</div>
</template>
<script>
    export default {
        props: ['dataProject'],
        data() {
            return {
                project: this.dataProject,
                newTask: '',
            }
        },
        created() {
            window.Echo.channel('tasks' + this.project.id).listen('TaskCreated', e => {
                this.project.tasks.push(e.task);
            });
        },

        methods:{
            save() {
                axios.post(`/api/projects/${this.project.id}/tasks`, {body: this.newTask})
                .then(response => response.data)
                .then(this.addTask);
            },
            addTask(task) {
                this.project.tasks.push(task);
                this.newTask = '';
            }
        }
    }
</script>
