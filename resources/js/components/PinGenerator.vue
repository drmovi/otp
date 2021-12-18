<template>
    <div class="card">
        <div class="card-header">
            Pin Generator
        </div>
        <div class="card-body">
            <h1 v-for="pin in pins" class="pin">{{ pin }}</h1>
            <div class="row g-3 mt-2 d-flex justify-content-center">
                <div class="col-auto">
                    <label for="pinCount" class="visually-hidden" >Pin Count</label>
                    <input type="number" class="form-control" id="pinCount" v-model="count">
                </div>
                <div class="col-auto">
                    <button type="button" class="btn btn-primary mb-3" @click="generate">Generate</button>
                </div>
            </div>
            <div class="progress">
                <div class="progress-bar" role="progressbar" :style="{width:`${progress}%`}" :aria-valuenow="progress"
                     aria-valuemin="0" aria-valuemax="100"></div>
            </div>
        </div>
    </div>
</template>

<script>
export default {

    data() {
        return {
            count: 1,
            pins: [],
            progress: 100,
            interval: null,
            loading: false,
        }
    },
    methods: {
        async fetch() {
            this.loading = true;
            const response = await axios.get(`api/user-pins?count=${this.count}`)
            this.pins = response.data.pins;
            this.loading = false;
        },
        async generate() {
            if (this.interval) {
                clearInterval(this.interval);
            }
            this.progress = 100;
            await this.fetch();
            this.countDown();
        },
        countDown() {
            this.interval = setInterval(() => {
                if (this.progress === 0) {
                    this.fetch();
                    this.progress = 100;
                } else {
                    this.progress--;
                }
            }, 100);
        }
    },
    created() {
        this.fetch();
    },
    mounted() {
        this.countDown();
    }
}
</script>

<style scoped>
.pin {
    letter-spacing: 30px;
    text-align: center;
    margin-bottom: 10px;
}
</style>
