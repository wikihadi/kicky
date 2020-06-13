<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">Activity</div>

                    <div class="card-body">
                        <form v-on:submit.prevent="addActivity" method="post" v-if="lastActivity===1">
                            <!--<div class="form-group">-->
                                <!--<label for="title">Title</label>-->
                                <div class="input-group mb-3">
                                    <input type="text" name="title" id="title" placeholder="What are you doing!" class="form-control" v-model="title">
                                    <div class="input-group-append">
                                        <button class="btn btn- secondary" type="button" v-on:click="more=!more"><i class="fa fa-plus" v-if="!more"></i><i class="fa fa-minus" v-else></i></button>
                                        <button class="btn btn-success" type="submit"><i class="fa fa-play"></i></button>
                                    </div>
                                </div>
                            <input v-if="more" type="text" name="description" id="description" placeholder="description" class="form-control" v-model="description">


                            <!--<small id="titleHelp" class="form-text text-muted">Save what are you doing...</small>-->
                            <!--</div>-->
                        </form>
                        <div v-else class="mb-5">
                            <h1 class="text-center" v-if="lastActivity.title!=null">{{lastActivity.title}}</h1>
                            <h1 class="text-center" v-else>Add Title</h1>
                            <h2 class="text-center">{{diffAll}}</h2>
                            <div class="text-center"><button class="btn btn-outline-danger btb-sm" type="button" v-on:click="stopActivity"><i class="fa fa-stop"></i></button></div>

                        </div>
                        <div>
                            <table class="table" dir="rtl">
                                <thead>
                                <tr>
                                    <!--<th scope="col">#</th>-->
                                    <th scope="col" class="text-right">عنوان</th>
                                    <!--<th scope="col">Description</th>-->
                                    <th scope="col" class="text-left">مدت زمان</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr v-for="a in activities" class="">
                                    <!--<th scope="row">{{a.id}}</th>-->
                                    <td v-if="a.title!=null" scope="row" class="text-right">{{a.title}}</td>
                                    <td v-else scope="row" class="text-right">افزون عنوان</td>
                                    <!--<td>{{a.description}}</td>-->
                                    <td class="text-left">{{a.duration}}</td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "Dashboard",
        props:['user'],
        data(){
            return{
                activities:'',
                title:'',
                lastActivity:'',
                description:'',
                more:false,
                timer:false,
                diffDay:'',
                diffH:'',
                diffM:'',
                diffS:'',
                diffAll:''
            }
        },
        mounted() {
         this.lastActivityGet();
        },
        methods:{
            stopActivity: function(){
                axios.post('/activity/stop/post', {
                    id: this.lastActivity.id,
                    user_id: this.user.id
                })
                    .then(response => {
                        this.lastActivityGet()
                    })
                    .catch(e => {
                        this.errors.push(e)
                    })
            },
            activitiesGet: function(){
                axios.get('/activities/get')
                    .then(response => {
                        this.activities = response.data;
                    })
                    .catch(e => {
                        this.errors.push(e)
                    });
            },
            lastActivityGet: function(){
                axios.get('/activity/last/get')
                    .then(response => {
                        this.lastActivity = response.data;
                        if (this.lastActivity!==1){
                            if (!this.timer) {
                                this.timer = setInterval( () => {
                                    this.lastActivity.diffS++;
                                    this.diffDay = parseInt(this.lastActivity.diffS / 86400);
                                    this.diffH = parseInt((this.lastActivity.diffS % 86400) / 3600);
                                    this.diffM = parseInt((this.lastActivity.diffS % 86400) / 60) % 60;
                                    this.diffS = parseInt((this.lastActivity.diffS)) % 60;
                                    if (this.diffH<10){
                                        this.diffH = '0' + this.diffH;
                                    };
                                    if (this.diffM<10){
                                        this.diffM = '0' + this.diffM;
                                    };
                                    if (this.diffS<10){
                                        this.diffS = '0' + this.diffS;
                                    };
                                    this.diffAll = this.diffDay + ':' + this.diffH + ':' + this.diffM + ':' + this.diffS;
                                }, 1000 )
                            }
                        }
                    })
                    .catch(e => {
                        this.errors.push(e)
                    });
                this.activitiesGet();

            },
            resetForm: function(){
                this.title='';
                this.description='';
                this.lastActivityGet();
            },
            addActivity: function () {
                axios.post('/activity/add/post', {
                        title: this.title,
                        description: this.description,
                    user_id: this.user.id
                })
                        .then(response => {
                            this.resetForm()
                        })
                        .catch(e => {
                            this.errors.push(e)
                        })
            }
        }
    }
</script>

<style scoped>

</style>
