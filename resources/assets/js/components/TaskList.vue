<template>
<div>
    <div class="row">
        <div class="col-md-8">
            <h3 v-text="dataProject.project_name"></h3>

            <ul>
                <li v-for="task in dataProject.tasks" v-text="task.body"></li>
            </ul>

            <input type="text" v-model="newTask" @blur="save" @keydown="tagPeers" />
            <span v-if="activePeer" v-text="activePeer.name + ' is typing...' "></span>
        </div>
        <div class="col-md-4">
            <h4>Active participants</h4>
            <ul>
                <li v-for="participant in participants" v-text="participant.name"></li>
            </ul>
        </div>
    </div>
</div>
</template>
<script>
    export default {
        props: ['dataProject'],
        data() {
            return {
                project: this.dataProject,
                newTask: '',
                participants: [],
                activePeer: false,
                typingTimer: false,
            }
        },
        computed:{
            channel(){
                return window.Echo.join('tasks' + this.project.id);
            }
        },
        created() {
            this.channel
            .here(users => {
                this.participants = users;
            })
            .joining(user => {
                this.participants.push(user);
            })
            .leaving(user => {
                this.participants.splice(this.participants.indexOf(user), 1);
            })
            .listen('TaskCreated', e => {
                this.project.tasks.push(e.task);
            })
            .listenForWhisper("typing", this.flashActivePeer)
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
