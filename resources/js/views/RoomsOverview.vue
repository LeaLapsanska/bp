<template>
    <div>
        <template v-if="showCalendar">
            <div>
                <modal name="event">
                    <div class="card">
                        <div class="card-header">
                            <div class="row">
                                <div class="col"></div>
                                <div class="col">
                                    <strong>{{ clickedEvent.name }}</strong> <br>
                                </div>
                                <div class="col">

                                    <button v-on:click="hide()" type="button" class="close" data-dismiss="alert"
                                            aria-label="Close">
                                        <span aria-hidden="true">×</span>
                                    </button>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col"><i class="fa fa-calendar-check-o"></i> {{ clickedEvent.start |
                                    moment("DD.MM.YYYY")}}
                                </div>
                                <div class="col"><i class="fa fa-clock-o"></i> {{ clickedEvent.start | moment("HH:mm")}} - {{
                                    clickedEvent.end | moment("HH:mm ")}}
                                </div>
                                <div class="col" v-for="room in clickedEvent.room"><i class="fa fa-location-arrow"></i>
                                    {{ room.name }}
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <form>
                                <div class="row form-group">
                                    <div class="col col-md-3">
                                        <strong>Pozvaní používatelia</strong>
                                    </div>
                                    <div class="col col-md-9">
                                        <div class="form-check">
                                            <label class="form-check-label">
                                                <p v-for="user in clickedEvent.users">
                                                    <i class="fa fa-user"></i>
                                                    {{ user.name }}
                                                </p>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </modal>
                <div>
                    <!-- tomuto divku potrebujem dať classu card, lenže po pridaní
                     classy sa kalendár už nezobrazí čomu vcelku vôbec nerozumiem a neviem čo s tým
                        HELP
                      -->
                    <button v-on:click="closeModal()" type="button" class="close" data-dismiss="alert"
                            aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                    <vue-cal
                        hide-weekends locale="sk"
                        :disable-views="['years', 'year']"
                        :events="events"
                        :time-from="7 * 60"
                        :time-to="20 * 60"
                        default-view="month"
                        events-on-month-view="short"
                        :on-event-click="eventClick"
                    ></vue-cal>
                </div>
            </div>
        </template>

        <template v-if="!showCalendar">
            <div>
                <div class="row">
                    <div class="col-lg-4"></div>
                    <div class="col-lg-6">
                        <input class="au-input au-input--xl" type="text" v-model="keyword" name="search" placeholder="Zadajte výraz ..." />
                        <button class="btn btn-link btn-lg" type="submit" v-on:click="select()">
                            <i class="zmdi zmdi-search"></i>
                        </button>
                    </div>
                    <div class="col-lg-2"></div>
                </div>
                <div class="table-responsive table-responsive-data2">
                    <table class="table table-data2">
                        <thead>
                        <tr>
                            <th>Názov</th>
                            <th>Kapacita</th>
                            <th>Parametre</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="item in items" ref="datatable">
                            <td>{{ item.name }}</td>
                            <td>
                                {{ item.capacity }}
                            </td>
                            <td>
                                <p v-for="parameter in item.parameters">
                                    {{ parameter.name }}
                                </p>
                            </td>
                            <td>
                                <div class="table-data-feature">
                                    <button type="button" class="btn btn-outline-success" v-on:click="calendar(item.id)">
                                        <i class="fa fa-calendar"></i> Vyťaženosť
                                    </button>
                                </div>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </template>

    </div>
</template>

<script>
    import auth from "../auth/index.js";
    import VueCal from 'vue-cal'
    import 'vue-cal/dist/vuecal.css'

    export default {
        components: {
            VueCal,
        },
        data() {
            return {
                auth: auth,
                items: {},
                showCalendar: false,
                events: [],
                clicked: false,
                clickedEvent: {
                    name: null,
                    start: null,
                    end: null,
                    room_id: null,
                    users: {}
                },
                event: null,
                keyword: ''
            };
        },
        mounted() {
            this.all();
        },
        methods: {
            all() {
                axios
                    .get("/activeRooms", {
                        headers: {Authorization: "Bearer " + this.auth.getToken()}
                    })
                    .then(response => {
                        this.items = response.data;
                    })
            },
            calendar(id){
                this.showCalendar = true;
                this.event = id;
                this.data();
            },
            data() {
                axios
                    .get("/calendar/" + this.event, {
                        headers: {Authorization: "Bearer " + this.auth.getToken()}
                    })
                    .then(response => {
                        this.events = response.data;
                    });
            },
            'eventClick'(event) {
                axios
                    .get("/events/" + event.id, {
                        headers: {Authorization: "Bearer " + this.auth.getToken()}
                    })
                    .then(response => {
                        this.clickedEvent = response.data;
                        this.show();
                    });

            },
            show() {
                this.$modal.show('event');
            },
            hide() {
                this.$modal.hide('event');
            },
            closeModal() {
                this.showCalendar = false;
            },
            select() {
                console.log(this.keyword);
                axios
                    .post('/searchRoom',
                        {
                        keyword: this.keyword
                        },
                        {
                        headers: {Authorization: "Bearer " + this.auth.getToken()}
                    })
                    .then(response => {
                        this.items = response.data;
                    })
            }
        }
    }
</script>
