<template>
    <div class="row">
        <div class="col m-5 p-5 text-center">
            <span style="font-size: 5rem; color:#cccccc">{{time}}</span>
        </div>
    </div>
</template>

<script>
    export default {
        name: "timer",
        data(){
            return{
                timer:'',
                sec:0,
                Day:0,
                H:0,
                M:0,
                S:0,
                time:''
            }
        },

        mounted(){
            this.timing()
        },

        methods:{
            timing: function () {
                this.timer = setInterval( () => {
                    this.sec++;
                    this.Day = parseInt(this.sec / 86400);
                    this.H = parseInt((this.sec % 86400) / 3600);
                    this.M = parseInt((this.sec % 86400) / 60) % 60;
                    this.S = parseInt((this.sec)) % 60;
                    if (this.H<10){
                        this.H = '0' + this.H;
                    };
                    if (this.M<10){
                        this.M = '0' + this.M;
                    };
                    if (this.S<10){
                        this.S = '0' + this.S;
                    };
                    this.time = this.H + ':' + this.M + ':' + this.S;
                }, 1000 )
            }
        }
    }
</script>

<style scoped>

</style>
