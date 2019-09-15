<template>
    <div class="col-lg-12">
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

        <div class="au-card au-card--no-shadow au-card--no-pad m-b-40">
            <div class="au-card-title" style="background-image:url('images/title.jpg');">
                <div class="bg-overlay bg-overlay--blue"></div>
                <h3>
                    <i class="zmdi zmdi-plus"></i>Vytvorenie udalosti</h3>
            </div>
            <div class="au-task js-list-load">
                <div class="au-task__title">

                <!-- <div v-if="messageModal" class="sufee-alert alert with-close alert-danger alert-dismissible fade show">
                    {{messageModal}}
                </div>-->
                <form @submit.prevent="addEvent()">
                    <div class="form-group">
                        <div class="input-group">
                            <div class="input-group-addon">
                                Názov
                            </div>
                            <input v-model="addItem.name" type="text" class="form-control" >
                        </div>
                        <span class="help-block">
                                    Písmenkami nešetri a toto pole nenechaj prázdne
                                </span>
                    </div>
                    <div class="row form-group">
                        <div class="col-12">
                            <select name="select" id="select" class="form-control" v-model="selectedRoom" v-on:change="reserved()">
                                <option value="none">Vyber miestnosť</option>
                                <option v-for="room in rooms" :value="room.id">{{room.name}}</option>
                            </select>
                        </div>
                    </div>
                    <full-calendar :events="events" locale="sk" @eventClick="eventClick" v-if="selectedE"></full-calendar>
                    <div class="row form-group" v-if="selectedP">
                        <div class="col col-md-3">
                            <strong>Zoznam parametrov, ktorými zvolená miestnosť disponuje</strong>
                        </div>
                        <div class="col col-md-9">
                            <div class="form-check" v-for="parameter in roomsProperty.parameters">
                                <label  class="form-check-label">
                                    <i class="fa fa-check-circle"></i> {{ parameter.name }}
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label for="cc-exp" class="control-label mb-1">Začiatok</label>
                                <input id="cc-exp" name="cc-exp" type="tel" class="form-control cc-exp" v-model="addItem.start" placeholder="DD/MM/YYYY hh:mm">
                                <span class="help-block" data-valmsg-for="cc-exp" data-valmsg-replace="true"></span>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label for="cc-exp2" class="control-label mb-1">Koniec</label>
                                <input id="cc-exp2" name="cc-exp" type="tel" class="form-control cc-exp" v-model="addItem.end" placeholder="DD/MM/YYYY hh:mm">
                                <span class="help-block" data-valmsg-for="cc-exp" data-valmsg-replace="true"></span>
                            </div>
                        </div>
                    </div>
                    <div class="row form-group">
                        <div class="col col-md-3">
                            <strong>Účastníci</strong>
                        </div>
                        <div class="col col-md-9">
                            <div class="form-check" v-for="user in users">
                                <label class="form-check-label">
                                    <input type="checkbox" class="form-check-input" :value ="user.id" :name = "user.id" v-model="checkedItems">
                                    {{ user.name }}
                                </label>
                            </div>
                        </div>
                    </div>
<!--
                    <datetime type="datetime" v-model="datetime"></datetime>
-->
                    <div class="au-task__footer">
                        <button type="submit" class="au-btn au-btn-load">
                            Vytvor udalosť
                        </button>
                    </div>
                </form>
            </div>
            </div>
        </div>
    </div>

</template>

<script>
    import auth from "../auth/index.js";
    // import { Datetime } from 'vue-datetime';

    export default {
        components: {
            "full-calendar": require("vue-fullcalendar"),
            // datetime: Datetime
        },
        data() {
            return {
                auth: auth,
                messageFail: null,
                messageSuccess: null,
                errors: [],
                addItem: {
                    id: null,
                    name: null,
                    room_id: null,
                    start: null,
                    end: null,
                    invitees: {}
                },
                showAddForm: false,
                invalidName: "",
                invalidCapacity: "",
                rooms: {},
                users: {},
                checkedItems: [],
                selectedRoom: 'none',
                selectedE: false,
                selectedP: false,
                roomsProperty: {},
                events: [],
                clickedEvent: {
                    name: null,
                    start: null,
                    end: null,
                    room_id: null,
                    users: {}
                }
            };
        },
        mounted() {
            this.data();
        },
        methods: {
            data() {
                axios
                    .get("/rooms",{
                        headers: {Authorization: "Bearer " + this.auth.getToken()}
                    })
                    .then(response => {
                        this.rooms = response.data;
                    });
                axios
                    .get("/users",{
                        headers: {Authorization: "Bearer " + this.auth.getToken()}

                    })
                    .then(response => {
                        this.users = response.data;
                    })
            },
            addEvent() {
            },
            reserved() {
                this.selectedE = true;
                this.selectedP = true;
                axios
                    .get("/rooms/" + this.selectedRoom, {
                        headers: {Authorization: "Bearer " + this.auth.getToken()}
                    })
                    .then(response => {
                        this.roomsProperty = response.data;
                    });
                axios
                    .get("/calendar/"+this.selectedRoom, {
                        headers: {Authorization: "Bearer " + this.auth.getToken()}
                    })
                    .then(response => {
                        this.events = response.data;
                    })
            },
            'eventClick'(event) {
                axios
                    .get("/events/" + event.id, {
                        headers: {Authorization: "Bearer " + this.auth.getToken()}
                    })
                    .then(response => {
                        this.clickedEvent = response.data;
                        this.show();
                    })
                    .catch(error => {
                        if (error.response.status == 409) {
                            this.selectedE = false;
                        }
                    })
            },
            show() {
                this.$modal.show('event');
            },
            hide() {
                this.$modal.hide('event');
            },
            addEvent(){
                axios
                    .post(
                        "/events",
                        {
                            name: this.addItem.name,
                            room_id: this.selectedRoom,
                            start: this.addItem.start,
                            end: this.addItem.end,
                            invitees: this.checkedItems
                        },
                        {
                            headers: {
                                Authorization: "Bearer " + this.auth.getToken()
                            }
                        })
                    .then(response =>{
                        this.messageSuccess = "Miestnosť bola úspešne pridaná!";
                        this.addItem = {
                            id: null,
                            name: null,
                            room_id: null,
                            start: null,
                            end: null,
                            invitees: {}
                        };
                    })
                    .catch(error => {

                    })
            }
        }
    }
</script>

