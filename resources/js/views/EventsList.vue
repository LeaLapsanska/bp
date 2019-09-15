<template>
    <div class="col-md-12">
        <div
            v-if="messageSuccess"
            class="sufee-alert alert with-close alert-success alert-dismissible fade show"
        >
            {{messageSuccess}}
            <button
                v-on:click="closeAlert()"
                type="button"
                class="close"
                data-dismiss="alert"
                aria-label="Close"
            >
                <span aria-hidden="true">×</span>
            </button>
        </div>
        <div
            v-if="messageFail"
            class="sufee-alert alert with-close alert-danger alert-dismissible fade show"
        >
            {{messageFail}}
            <button
                v-on:click="closeAlert()"
                type="button"
                class="close"
                data-dismiss="alert"
                aria-label="Close"
            >
                <span aria-hidden="true">×</span>
            </button>
        </div>
        <template v-if="!showAddForm && !showEditForm">
            <div class="table-data__tool">
                <button
                    class="au-btn au-btn-icon au-btn--green au-btn--block"
                    v-on:click="add()"
                >
                    <i class="zmdi zmdi-plus"></i>Vytvoriť novú udalosť
                </button>
            </div>
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
                        <th>Začiatok</th>
                        <th>Koniec</th>
                        <th>Miestnosť</th>
                        <th>Účastníci</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="item in items" ref="datatable">
                        <td>
                            <span class="block-email">{{ item.name }}</span>
                        </td>
                        <td>{{ item.start | moment("DD.MM.YYYY, HH:mm") }}</td>
                        <td>{{ item.end | moment("DD.MM.YYYY, HH:mm") }}</td>
                        <td>
                            <p v-for="room in item.room">{{ room.name }}</p>
                        </td>
                        <td>
                            <p v-for="user in item.users">{{ user.name }}</p>
                        </td>
                        <td>
                            <div class="table-data-feature">
                                <button
                                    class="item"
                                    data-toggle="tooltip"
                                    data-placement="top"
                                    title="Upraviť"
                                    v-on:click="edit(item.id)"
                                >
                                    <i class="zmdi zmdi-edit"></i>
                                </button>
                                <button
                                    class="item"
                                    data-toggle="tooltip"
                                    data-placement="top"
                                    title="Vymazať"
                                    v-on:click="destroy(item.id)"
                                >
                                    <i class="zmdi zmdi-delete"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </template>
        <!-- END DATA TABLE -->

        <!-- ADD FORM -->
        <template v-if="showAddForm">
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
                            <div class="col"><i class="fa fa-clock-o"></i> {{ clickedEvent.start | moment("HH:mm")}} -
                                {{
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
                    <button v-on:click="closeModal()" type="button" class="close" data-dismiss="alert"
                            aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
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
                                    <input v-model="addItem.name" type="text" class="form-control"
                                           v-bind:class="invalidName">
                                </div>
                                <span class="help-block" v-if="errors.name">
                                    Písmenkami nešetri a toto pole nenechaj prázdne
                                </span>
                            </div>
                            <div class="row form-group">
                                <div class="col-12">
                                    <select name="select" id="select" class="form-control" v-model="selectedRoom"
                                            v-on:change="reserved()" v-bind:class="invalidRoom">
                                        <option value="none">Vyber miestnosť</option>
                                        <option v-for="room in rooms" :value="room.id">{{room.name}}</option>
                                    </select>
                                    <span class="help-block" v-if="errors.room_id">
                                        Zvoľ jednu z miestností
                                    </span>
                                </div>
                            </div>
                            <vue-cal
                                v-if="selectedRoom"
                                hide-weekends locale="sk"
                                :disable-views="['years', 'year']"
                                :events="events"
                                :time-from="7 * 60"
                                :time-to="20 * 60"
                                default-view="month"
                                events-on-month-view="short"
                                :on-event-click="eventClick"
                            ></vue-cal>
                            <div class="row form-group" v-if="selectedP">
                                <div class="col col-md-3">
                                    <strong>Zoznam parametrov, ktorými zvolená miestnosť disponuje</strong>
                                </div>
                                <div class="col col-md-9">
                                    <div class="form-check" v-for="parameter in roomsProperty.parameters">
                                        <label class="form-check-label">
                                            <i class="fa fa-check-circle"></i> {{ parameter.name }}
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-6">
                                    <div class="form-group">
                                        <label class="control-label mb-1"><b>Začiatok</b></label>
                                        <datetime
                                            v-bind:class="invalidStart"
                                            v-model="addItem.start"
                                            type="datetime"
                                            class="form-control"
                                            value-zone="UTC+2"
                                        >
                                        </datetime>
                                        <span class="help-block" v-if="errors.start">
                                        Zadaj začiatok udalosti
                                    </span>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label class="control-label mb-1"><b>Koniec</b></label>
                                        <datetime
                                            v-bind:class="invalidEnd"
                                            v-model="addItem.end"
                                            type="datetime"
                                            class="form-control"
                                            value-zone="UTC+2"
                                        >
                                        </datetime>
                                        <span class="help-block" v-if="errors.end">
                                        Zadaj koniec udalosti
                                    </span>
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
                                            <input type="checkbox" class="form-check-input" :value="user.id"
                                                   :name="user.id" v-model="checkedItems">
                                            {{ user.name }}
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="au-task__footer">
                                <div
                                    v-if="messageAdd"
                                    class="sufee-alert alert with-close alert-danger alert-dismissible fade show"
                                >
                                    {{messageAdd}}
                                    <button
                                        v-on:click="closeAlert()"
                                        type="button"
                                        class="close"
                                        data-dismiss="alert"
                                        aria-label="Close"
                                    >
                                        <span aria-hidden="true">×</span>
                                    </button>
                                </div>
                                <button type="submit" class="au-btn au-btn-load">
                                    Vytvor udalosť
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </template>
        <!-- END FORM -->

        <!-- EDIT FORM -->
        <template v-if="showEditForm">
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
                            <div class="col"><i class="fa fa-clock-o"></i> {{ clickedEvent.start | moment("HH:mm")}} -
                                {{
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
                <div class="card-header">
                    <strong>Upraviť miestnosť</strong>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"
                            v-on:click="closeModal()">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="card-body card-block">

                    <form @submit.prevent="editEvent()">
                        <div class="form-group">
                            <div class="input-group">
                                <div class="input-group-addon">
                                    Názov
                                </div>
                                <input v-model="editItem.name" type="text" class="form-control"
                                       v-bind:class="invalidName">
                            </div>
                            <span class="help-block" v-if="errors.name">
                                    Písmenkami nešetri a toto pole nenechaj prázdne
                                </span>
                        </div>
                        <div class="row form-group">
                            <div class="col-12">
                                <select name="select" class="form-control" v-model="selectedRoom"
                                        v-on:change="reserved()" v-bind:class="invalidRoom">
                                    <option value="none">Vyber miestnosť</option>
                                    <option v-for="room in rooms" :value="room.id">{{room.name}}</option>
                                </select>
                            </div>
                        </div>
                        <vue-cal
                            v-if="selectedRoom"
                            class="vuecal--green-theme"
                            hide-weekends locale="sk"
                            :disable-views="['years', 'year']"
                            :events="events"
                            :time-from="7 * 60"
                            :time-to="20 * 60"
                            default-view="week"
                            events-on-month-view="short"
                            :on-event-click="eventClick"
                        >
                        </vue-cal>
                        <div class="row form-group" v-if="selectedP">
                            <div class="col col-md-3">
                                <strong>Zoznam parametrov, ktorými zvolená miestnosť disponuje</strong>
                            </div>
                            <div class="col col-md-9">
                                <div class="form-check" v-for="parameter in roomsProperty.parameters">
                                    <label class="form-check-label">
                                        <i class="fa fa-check-circle"></i> {{ parameter.name }}
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-6">
                                <div class="form-group">
                                    <label class="control-label mb-1"><b>Začiatok</b></label>
                                    <datetime
                                        v-bind:class="invalidStart"
                                        v-model="editItem.start"
                                        type="datetime"
                                        class="form-control"
                                        value-zone="UTC+2"
                                    >
                                    </datetime>
                                    <span class="help-block" v-if="errors.start">
                                        Zadaj začiatok udalosti
                                    </span>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label class="control-label mb-1"><b>Koniec</b></label>
                                    <datetime
                                        v-bind:class="invalidEnd"
                                        v-model="editItem.end"
                                        type="datetime"
                                        class="form-control"
                                        value-zone="UTC+2"
                                    >
                                    </datetime>
                                    <span class="help-block" v-if="errors.end">
                                        Zadaj koniec udalosti
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="row form-group">
                            <div class="col col-md-3">
                                <strong>Účastníci</strong>
                            </div>
                            <div class="col col-md-9">
                                <div class="form-check">
                                    <label class="block-label" v-for="p in pms">
                                        <template v-if="editItemUsersIDs.includes(p.id)">
                                            <input type="checkbox" class="form-check-input" :value="p.id" checked
                                                   v-model="checkedEditItems"> {{ p.name }}
                                        </template>
                                        <template v-else>
                                            <input type="checkbox" class="form-check-input" :value="p.id"
                                                   v-model="checkedEditItems"> {{ p.name }}
                                        </template>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-info btn-block">Uprav udalosť</button>
                    </form>
                </div>
            </div>
        </template>
        <!-- END FORM -->

    </div>
</template>

<script>
    import auth from "../auth/index.js";
    import 'vue-cal/dist/vuecal.css'
    import VueCal from 'vue-cal'

    export default {
        components: {
            VueCal,
        },
        data() {
            return {
                auth: auth,
                items: {},
                messageFail: null,
                messageSuccess: null,
                showEditForm: false,
                editItem: {
                    id: null,
                    name: null,
                    start: null,
                    end: null,
                    room_id: null,
                    users: {}
                },
                id: null,
                errors: [],
                invalidName: null,
                invalidRoom: null,
                addItem: {
                    id: null,
                    name: null,
                    start: null,
                    end: null,
                    room_id: null,
                    users: {}
                },
                showAddForm: false,
                invalidStart: null,
                invalidEnd: null,
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
                },
                pms: {},
                checkedEditItems: [],
                editItemUsersIDs: [],
                calendar: false,
                addStart: '',
                regex: '',
                messageAdd: null,
                keyword: ''
            };
        },
        mounted() {
            this.all();
            this.data();
            this.u();
        },
        methods: {
            all() {
                axios
                    .get("/events", {
                        headers: {Authorization: "Bearer " + this.auth.getToken()}
                    })
                    .then(response => {
                        this.items = response.data;
                    });
            },
            destroy(id) {
                axios
                    .delete("/events/" + id, {
                        headers: {Authorization: "Bearer " + this.auth.getToken()}
                    })
                    .then(response => {
                        this.all();
                        this.messageSuccess =
                            "Udalosť bola úspešne vymazaná a všetci účastníci boli o jej zrušení informovaní prostredníctvom emailu!";
                        window.scrollTo(0, 0);
                    })
                    .catch(error => {
                        this.messageFail = "Udalosť sa nepodarilo vymazať!";
                        window.scrollTo(0, 0);
                    });
            },
            closeAlert() {
                this.messageFail = null;
                this.messageSuccess = null;
                this.messageAdd = null;
            },
            data() {
                axios
                    .get("/activeRooms", {
                        headers: {Authorization: "Bearer " + this.auth.getToken()}
                    })
                    .then(response => {
                        this.rooms = response.data;
                    });
                axios
                    .get("/users", {
                        headers: {Authorization: "Bearer " + this.auth.getToken()}

                    })
                    .then(response => {
                        this.users = response.data;
                    })
            },
            add() {
                this.showAddForm = true;
            },
            addEvent() {
                axios
                    .post(
                        "/events",
                        {
                            name: this.addItem.name,
                            room_id: this.selectedRoom,
                            start: this.addItem.start.replace('T', ' ').replace('.000+02:00', ''),
                            end: this.addItem.end.replace('T', ' ').replace('.000+02:00', ''),
                            invitees: this.checkedItems
                        },
                        {
                            headers: {
                                Authorization: "Bearer " + this.auth.getToken()
                            }
                        })
                    .then(response => {
                        this.checkedItems = [];
                        this.selectedRoom = 'none';
                        this.messageSuccess = "Udalosť bola úspešne pridaná, pozvaní používatelia boli notifikovaní emailom!";
                        this.addItem = {
                            id: null,
                            name: null,
                            room_id: null,
                            start: null,
                            end: null,
                            invitees: {}
                        };
                        this.showAddForm = false;
                        this.all();
                        window.scrollTo(0, 0);

                    })
                    .catch(error => {
                        if (error.response.status == 409) {
                            this.messageAdd =
                                "V miestnosti nastala kolízia! Poriadne si prezrite kalendár a uistite sa, že v daný čas nie sú na miestnosť naviazané iné udalosti!";
                        } else {
                            this.messageAdd = "Údaje neboli zadané správne!";
                        }
                        this.errors = error.response.data.errors
                            ? error.response.data.errors
                            : [];
                        if (this.selectedRoom == 'none') {
                            this.messageAdd = "Nebola zvolená miestnosť udalosti!";
                            this.invalidRoom = "is-invalid";
                        } else {
                            this.invalidRoom = "";
                        }
                        if (this.errors.name != undefined) {
                            this.invalidName = "is-invalid";
                        } else {
                            this.invalidName = "";
                        }
                        if (this.errors.start != undefined) {
                            this.invalidStart = "is-invalid";
                        } else {
                            this.invalidStart = "";
                        }
                        if (this.errors.end != undefined) {
                            this.invalidEnd = "is-invalid";
                        } else {
                            this.invalidEnd = "";
                        }
                        window.scrollTo(0, 0);
                    })
            },
            u() {
                axios
                    .get("/users", {
                        headers: {Authorization: "Bearer " + this.auth.getToken()}
                    })
                    .then(response => {
                        this.pms = response.data;
                    })
            },
            edit(id) {
                this.editItemUsersIDs = [];
                axios
                    .get("/events/" + id, {
                        headers: {Authorization: "Bearer " + this.auth.getToken()}
                    })
                    .then(response => {
                        this.editItem = response.data;
                        this.editItem.users.forEach(item => {
                            this.editItemUsersIDs.push(item.id);
                            this.checkedEditItems.push(item.id)
                        });
                        this.selectedRoom = this.editItem.room_id;
                        this.reserved();
                        this.editItem.start = this.editItem.start.replace(' ', 'T');
                        this.editItem.end = this.editItem.end.replace(' ', 'T');
                        this.calendar = true;
                        this.showEditForm = true;
                    })
                    .catch(error => {
                    })
            },
            closeModal() {
                this.showEditForm = false;
                this.showAddForm = false;
                this.invalidName = "";
                this.invalidMail = "";
                this.invalidPassword = "";
                this.messageFail = null;
                this.errors = [];
            },
            reserved() {
                this.selectedP = true;
                console.log(this.selectedRoom);
                axios
                    .get("/rooms/" + this.selectedRoom, {
                        headers: {Authorization: "Bearer " + this.auth.getToken()}
                    })
                    .then(response => {
                        this.roomsProperty = response.data;
                    });
                axios
                    .get("/calendar/" + this.selectedRoom, {
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
            editEvent() {
                axios
                    .put(
                        "/events/" + this.editItem.id,
                        {
                            name: this.editItem.name,
                            room_id: this.selectedRoom,
                            start: this.editItem.start.replace('T', ' ').replace('.000+02:00', ''),
                            end: this.editItem.end.replace('T', ' ').replace('.000+02:00', ''),
                            invitees: this.checkedEditItems
                        },
                        {
                            headers: {
                                Authorization: "Bearer " + this.auth.getToken()
                            }
                        }
                    )
                    .then(response => {
                        this.messageSuccess = "Udalosť bola úspešne zmenená, dotknutí používatelia boli notifikovaní emailom!";
                        this.editItem = {
                            id: null,
                            name: null,
                            room_id: null,
                            start: null,
                            end: null,
                            invitees: {}
                        };
                        this.showEditForm = false;
                        this.all();
                        window.scrollTo(0, 0);
                    })
                    .catch(error => {
                        if (error.response.status == 409) {
                            this.messageFail =
                                "V miestnosti nastala kolízia! Poriadne si prezrite kalendár a uistite sa, že v daný čas nie sú na miestnosť naviazané iné udalosti!";
                        }

                        if (error.response.status == 411) {
                            this.messageFail =
                                "Bola presiahnutá kapacita miestnosti!";
                        }

                        this.errors = error.response.data.errors
                            ? error.response.data.errors
                            : [];
                        if (this.selectedRoom == 'none') {
                            this.messageFail = "Nebola zvolená miestnosť udalosti!";
                        }

                        if (this.errors.name != undefined) {
                            this.invalidName = "is-invalid";
                            this.messageFail = "Nebol správne zadaný názov udalosti!";
                        } else {
                            this.invalidName = "";
                        }
                        window.scrollTo(0, 0);
                    })
            },
            select() {
                console.log(this.keyword);
                axios
                    .post('/searchEvent',
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
    };
</script>
