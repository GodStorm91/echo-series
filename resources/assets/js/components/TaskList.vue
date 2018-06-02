<template>
<div>
    <ul>
        <li v-for="task in dataProject.tasks" v-text="task.body"></li>
    </ul>

    <input type="text" v-model="newTask" @blur="save" @keydown="tagPeers" />
    <span v-if="activePeer" v-text="activePeer.name + ' is typing...' "></span>
</div>
</template>
<script>
    export default {
        props: ['dataProject'],
        data() {
            return {
                project: this.dataProject,
                newTask: '',
                activePeer: false,
                typingTimer: false,
            }
        },
        created() {
            window.Echo.private
            ('tasks' + this.project.id).listen('TaskCreated', e => {
                this.project.tasks.push(e.task);
            })
            .listenForWhisper("typing", this.flashActivePeer)
        },

        computed:{
            channel(){
                return window.Echo.private('tasks' + this.project.id);
            }
        },
        methods:{
            flashActivePeer(e) {
                this.activePeer = e;

                if (this.typingTimer) clearTimeout(this.typingTimer);

               this.typingTimer = setTimeout(() => {
                   this.activePeer = false;
               }, 3000);
            },
            tagPeers() {
                this.channel
                .whisper('typing', {
                    name: window.App.user.name,
                })
            },
            save() {
                axios.post(`/api/projects/${this.project.id}/tasks`, {body: this.newTask})
                .then(response => response.data)
                .then(this.addTask);
            },
            addTask(task) {
                this.activePeer = false;
                this.project.tasks.push(task);
                this.newTask = '';
            }
        }
    }
</script>
