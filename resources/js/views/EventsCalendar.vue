<template>
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
            <!-- /# card -->
        </modal>
        <div class="card">
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
                selectedEvent: {},
                showDialog: false,
                auth: auth,
                events: [],
                clicked: false,
                clickedEvent: {
                    name: null,
                    start: null,
                    end: null,
                    room_id: null,
                    users: {}
                }
            }
        },
        mounted() {
            this.all();
        },
        methods: {
            all() {
                axios
                    .get("/calendar", {
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
        }
    }
</script>
