<template>
    <div>
        <div class="row">
            <div class="col-lg-8">
                <weather
                    api-key="5e14967469e4b7a3099ea65c11cb443c"
                    title="Bratislava"
                    latitude="48.1486"
                    longitude="17.1077"
                    language="sk"
                    units="ca"
                    bar-color="#FFF"
                    text-color="#404040"
                >
                </weather>
                <div class="overview-item overview-item--c3">
                    <div class="overview__inner">
                        <div class="overview-box clearfix">
                            <div class="icon">
                                <router-link :to="{ name: 'myevents' }">

                                <i class="zmdi zmdi-calendar-note"></i>
                                </router-link>

                            </div>
                            <div class="text">
                                <h2>Kolízie</h2>
                                <span v-for="c in coll"> {{ c.name }} | {{ c.start | moment("DD.MM.YYYY HH:mm ") }} | počet kolízii: {{ c.collisions }} <br></span>
                                <span>{{message}}</span>
                                <hr>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="au-card au-card--no-shadow au-card--no-pad m-b-40">
                    <div class="au-task js-list-load">
                        <div class="au-task__title">
                            <h3>Najbližšie udalosti</h3>
                        </div>
                        <div class="au-task-list js-scrollbar3">
                            <div class="au-task__item au-task__item--warning"  v-for="event in events">
                                <div class="au-task__item-inner">
                                    <h5 class="task">
                                        <a href="#">{{ event.name }}</a>
                                    </h5>
                                    <span class="time">{{ event.start | moment("DD.MM.YYYY | HH:mm ") }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>


</template>

<script>
    import VueWeatherWidget from 'vue-weather-widget';
    import 'vue-weather-widget/dist/css/vue-weather-widget.css';
    import auth from "../auth/index.js";


    export default {
        components: {
            'weather': VueWeatherWidget
        },
        data() {
            return {
                auth: auth,
                events: [],
                coll: [],
                message: null
            }
        },
        mounted() {
            this.all();
            this.collisions();
        },
        methods: {
            all() {
                axios
                    .get("/nearEvents", {
                        headers: {Authorization: "Bearer " + this.auth.getToken()}
                    })
                    .then(response => {
                        this.events = response.data;
                    });
            },
            collisions() {
                axios
                    .get("/myCollisions", {
                        headers: {Authorization: "Bearer " + this.auth.getToken()}
                    })
                    .then(response => {
                        this.coll = response.data;
                        if (this.coll.length != 0){
                            this.message = 'Skontrolujte si prosím svoje udalosti kliknutím na ikonu kalendára!'
                        }else this.message = 'V systéme neevidujeme žiadne kolízie';
                    })
            }
        }
    }


</script>
