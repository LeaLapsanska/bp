<template>
    <div class="col-md-12">
        <div v-if="messageSuccess" class="sufee-alert alert with-close alert-success alert-dismissible fade show">
            {{messageSuccess}}
            <button v-on:click="closeAlert()" type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">×</span>
            </button>
        </div>
        <div v-if="messageFail" class="sufee-alert alert with-close alert-danger alert-dismissible fade show">
            {{messageFail}}
            <button v-on:click="closeAlert()" type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">×</span>
            </button>
        </div>

        <template v-if="!showAddForm && !showEditForm && !showEventList">
            <div class="table-data__tool">
                <button class="au-btn au-btn-icon au-btn--green au-btn--block" v-on:click="add()">
                    <i class="zmdi zmdi-plus"></i>Pridaj novú miestnosť
                </button>

            </div>
            <div class="row">
                <div class="col-lg-4"></div>
                <div class="col-lg-6">
                    <input class="au-input au-input--xl" type="text" v-model="keyword" name="search" placeholder="Zadajte výraz ..."/>
                    <button class="btn btn-link btn-lg" type="submit" v-on:click="select()">
                        <i class="zmdi zmdi-search"></i>
                    </button>
                </div>
                <div class="col-lg-2"></div>
            </div>
            <div class="table-responsive table-responsive-data2">
                <table class="table table-data2" id="myTable">
                    <thead>
                    <tr>
                        <th>Názov</th>
                        <th>Kapacita</th>
                        <th>Aktívna</th>
                        <th>Parametre</th>
                        <th></th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="item in items" ref="datatable">
                        <td>{{ item.name }}</td>
                        <td>
                            {{ item.capacity }}
                        </td>
                        <td v-if="item.isActive">
                            <span class="status--process">
                                <i class="fa fa-check"></i>
                            </span>
                        </td>
                        <td v-else="item.isActive">
                            <span class="status--denied">
                                <i class="fa fa-minus-circle"></i>
                            </span>
                        </td>
                        <td>
                            <p v-for="parameter in item.parameters">
                                {{ parameter.name }}
                            </p>
                        </td>
                        <td>
                            <div class="table-data-feature">
                                <button class="item" data-toggle="tooltip" data-placement="top" title="Upraviť"
                                        v-on:click="edit(item.id)">
                                    <i class="zmdi zmdi-edit"></i>
                                </button>
                                <button class="item" data-toggle="tooltip" data-placement="top" title="Vymazať"
                                        v-on:click="destroy(item.id)">
                                    <i class="zmdi zmdi-delete"></i>
                                </button>
                                <button class="item" data-toggle="tooltip" data-placement="top"
                                        data-original-title="Aktivovať" v-on:click="activate(item.id)">
                                    A
                                </button>
                                <button class="item" data-toggle="tooltip" data-placement="top" title="Deaktivovať"
                                        v-on:click="deactivate(item.id)">
                                    D
                                </button>
                            </div>
                        </td>
                        <td>
                            <button type="button" class="btn btn-outline-warning" v-on:click="roomEvent(item.id)">
                                Spravovať udalosti
                            </button>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </template>
        <!-- END DATA TABLE -->

        <!-- modal medium -->
        <template v-if="showEditForm">
            <div class="card">
                <div class="card-header">
                    <strong>Upraviť miestnosť</strong>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"
                            v-on:click="closeModal()">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="card-body card-block">
                    <form @submit.prevent="editRoom()">
                        <div class="form-group">
                            <div class="input-group">
                                <div class="input-group-addon">
                                    Názov
                                </div>
                                <input type="text" v-model="editItem.name" class="form-control"
                                       v-bind:class="invalidName">
                            </div>
                            <span class="help-block" v-if="errors.name">
                                    Písmenkami nešetri a toto pole nenechaj prázdne
                                </span>
                        </div>
                        <div class="form-group">
                            <div class="input-group">
                                <div class="input-group-addon">
                                    Kapacita
                                </div>
                                <input type="text" v-model="editItem.capacity" class="form-control"
                                       v-bind:class="invalidCapacity">
                            </div>
                            <span class="help-block" v-if="errors.capacity">
                                    Zadaj v číslenom formáte kapacitu miestnosti
                                </span>
                        </div>
                        <div class="row form-group">
                            <div class="col col-md-3">
                                <strong>Parametre</strong>
                            </div>
                            <div class="col col-md-9">
                                <label v-for="p in pms" class="block-label">
                                    <template v-if="editItemParametersIDs.includes(p.id)">
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
                        <div class="row form-group">
                            <div class="col col-md-3">
                                <strong>Zoznam udalosti, ktoré sa budú konať v miestnosti</strong>
                            </div>
                            <div class="col col-md-9">
                                <div class="form-check" v-for="event in editItem.events">
                                    <label class="form-check-label">
                                        <i class="fa fa-clock-o"></i> {{ event.start | moment("DD.MM.YYYY, HH:mm ") }} |
                                        {{ event.name }}
                                    </label>
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-info btn-block">Uprav údaje miestnosti</button>
                    </form>
                </div>
            </div>
        </template>
        <!-- end modal medium -->

        <!-- modal medium -->
        <template v-if="showAddForm">
            <div class="card">
                <div class="card-header">
                    <strong>Vytvorenie miestnosti</strong>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"
                            v-on:click="closeModal()">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="card-body card-block">
                    <form @submit.prevent="addRoom()">
                        <div class="form-group">
                            <div class="input-group">
                                <div class="input-group-addon">
                                    <i class="fa fa-user"></i>
                                </div>
                                <input type="text" v-model="addItem.name" class="form-control"
                                       v-bind:class="invalidName">
                            </div>
                            <span class="help-block" v-if="errors.name">
                                    Písmenkami nešetri a toto pole nenechaj prázdne
                                </span>
                        </div>
                        <div class="form-group">
                            <div class="input-group">
                                <div class="input-group-addon">
                                    Kapacita
                                </div>
                                <input type="text" v-model="addItem.capacity" class="form-control"
                                       v-bind:class="invalidCapacity">
                            </div>
                            <span class="help-block" v-if="errors.capacity">
                                    Zadaj v číslenom formáte kapacitu miestnosti</span>
                        </div>
                        <div class="row form-group">
                            <div class="col col-md-3">
                                <label class="form-control-label"><strong>Aktívna miestnosť</strong></label>
                            </div>
                            <div class="col col-md-9">
                                <div class="form-check-inline form-check">
                                    <label class="form-check-label">
                                        <input type="checkbox" class="form-check-input" v-model="addItem.isActive">
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="row form-group">
                            <div class="col col-md-3">
                                <strong>Parametre</strong>
                            </div>
                            <div class="col col-md-9">
                                <div class="form-check" v-for="p in pms">
                                    <label class="form-check-label">
                                        <input type="checkbox" class="form-check-input" :value="p.id" :name="p.id"
                                               v-model="checkedAddItems">
                                        {{ p.name }}
                                    </label>
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-info btn-block">Pridaj miestnosť</button>
                    </form>
                </div>
            </div>
        </template>
        <!-- end modal medium -->

        <template v-if="showEventList">
            <div class="user-data m-b-30">
                <h3 class="title-3">
                    <i class="zmdi zmdi-calendar"></i>{{ removeRoom.name }}</h3>

                <div class="table-responsive table-data">
                    <table class="table">
                        <thead>
                        <tr>
                            <td>Názov</td>
                            <td>Začiatok</td>
                            <td>Koniec</td>
                            <td></td>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="e in removeEvents">
                            <td>
                                {{ e.name }}
                            </td>
                            <td>
                                {{ e.start | moment("DD.MM.YYYY, HH:mm ") }}
                            </td>
                            <td>
                                {{ e.end | moment("DD.MM.YYYY, HH:mm ") }}
                            </td>
                            <td>
                                <span class="more">
                                    <i class="zmdi zmdi-delete" v-on:click="deleteEvent(e.id)"></i>
                                </span>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
                <div class="user-data__footer">
                    <div class="filters m-b-45">
                        <button type="submit" class="btn btn-danger btn-block" v-on:click="removeAll()">Zrušiť všetky udalosti</button>
                        <button class="btn btn-secondary btn-block" v-on:click="closeModal()">Zatvoriť zoznam</button>

                    </div>
                </div>
            </div>
        </template>
    </div>
</template>

<script>
    import auth from "../auth/index.js";

    export default {
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
                    capacity: null,
                    isActive: null,
                    parameters: null,
                    events: null,
                },
                editItemParametersIDs: [],
                id: null,
                errors: [],
                addItem: {
                    id: null,
                    name: null,
                    capacity: null,
                    isActive: false,
                    parameters: null,
                },
                showAddForm: false,
                invalidName: "",
                invalidCapacity: "",
                pms: {},
                checkedAddItems: [],
                checkedEditItems: [],
                showEventList: false,
                removeEvents: {},
                removeRoom: {},
                allEvents: [],
                keyword: ''
            };
        },
        mounted() {
            this.all();
            this.parameters();
        },
        methods: {
            all() {
                axios
                    .get("/rooms", {
                        headers: {Authorization: "Bearer " + this.auth.getToken()}
                    })
                    .then(response => {
                        this.items = response.data;
                    });

            },
            destroy(id) {
                axios
                    .delete("/rooms/" + id, {
                        headers: {Authorization: "Bearer " + this.auth.getToken()}
                    })
                    .then(response => {
                        this.all();
                        this.messageSuccess = "Miestnosť bola úspešne vymazaná!";
                        window.scrollTo(0, 0);
                    })
                    .catch(error => {
                        if (error.response.status == 400) {
                            this.messageFail =
                                "Miestnosť nemôže byť vymazaná, pretože sú na ňu naviazané udalosti! Zrušte predtým všetky tieto udalosti, pomocou tlačidla spravovať udalosti.";
                        } else {
                            this.messageFail =
                                "Miestnosť sa nepodarilo vymazať!";
                        }
                        window.scrollTo(0, 0);
                    })
            },
            edit(id) {
                this.editItemParametersIDs = [];
                axios
                    .get("/rooms/" + id, {
                        headers: {Authorization: "Bearer " + this.auth.getToken()}
                    })
                    .then(response => {
                        this.editItem = response.data;
                        this.editItem.parameters.forEach(item => {
                            this.editItemParametersIDs.push(item.id);
                            this.checkedEditItems.push(item.id)
                        });
                        this.showEditForm = true;
                        window.scrollTo(0, 0);
                    })
                    .catch(error => {
                        this.messageFail = "Miestnosť, ktorá je deaktivovaná nemôže byť zobrazená ani upravovaná!";
                        window.scrollTo(0, 0);
                    })
            },
            parameters() {
                axios
                    .get("/parameters", {
                        headers: {Authorization: "Bearer " + this.auth.getToken()}
                    })
                    .then(response => {
                        this.pms = response.data;
                    })
            },
            closeAlert() {
                this.messageFail = null;
                this.messageSuccess = null;
            },
            closeModal() {
                this.errors = [];
                this.showEditForm = false;
                this.showAddForm = false;
                this.showEventList = false;
            },
            add() {
                this.showAddForm = true;
            },
            addRoom() {
                axios
                    .post(
                        "/rooms",
                        {
                            name: this.addItem.name,
                            capacity: this.addItem.capacity,
                            isActive: this.addItem.isActive,
                            parameters: this.checkedAddItems
                        },
                        {
                            headers: {
                                Authorization: "Bearer " + this.auth.getToken()
                            }
                        }
                    )
                    .then(response => {
                        this.invalidName = "";
                        this.invalidCapacity = "";
                        this.showAddForm = false;
                        this.messageFail = "";
                        this.all();
                        this.parameters();
                        this.messageSuccess =
                            "Bola pridaná nová miestnosť: " + this.addItem.name;
                        this.addItem = {
                            id: null,
                            name: null,
                            capacity: null,
                            isActive: false,
                            parameters: null,
                        };
                        this.checkedAddItems = [];
                        this.errors = [];
                        window.scrollTo(0, 0);
                    })
                    .catch(error => {
                        this.messageFail = "Údaje neboli zadané správne!";
                        this.errors = error.response.data.errors
                            ? error.response.data.errors
                            : [];
                        if (this.errors.name != undefined) {
                            this.invalidName = "is-invalid";
                        } else {
                            this.invalidName = "";
                        }
                        if (this.errors.capacity != undefined) {
                            this.invalidCapacity = "is-invalid";
                        } else {
                            this.invalidCapacity = "";
                        }
                        window.scrollTo(0, 0);
                    });
            },
            activate(id) {
                axios
                    .patch("/rooms/" + id + "/activate", null,
                        {
                            headers: {Authorization: "Bearer " + this.auth.getToken()}
                        }
                    )
                    .then(response => {
                        this.all();
                        this.messageSuccess =
                            "Misetnosť bola úspešne aktivovaná!";
                        this.messageFail = "";
                        window.scrollTo(0, 0);
                    })
                    .catch(error => {
                        this.messageFail = "Miestnosť sa nepodarilo aktivovať!";
                        window.scrollTo(0, 0);
                    })
            },
            deactivate(id) {
                axios
                    .patch("/rooms/" + id + "/deactivate", null,
                        {
                            headers: {Authorization: "Bearer " + this.auth.getToken()}
                        }
                    )
                    .then(response => {
                        this.all();
                        this.messageSuccess =
                            "Misetnosť bola úspešne deaktivovaná!";
                        this.messageFail = "";
                        window.scrollTo(0, 0);
                    })
                    .catch(error => {
                        if (error.response.status == 400) {
                            this.messageFail =
                                "Miestnosť nemôže byť deaktivovaná, pretože sú na ňu naviazané udalosti! Zrušte všetky tieto udalosti, pomocou tlačidla spravovať udalosti.";
                        } else {
                            this.messageFail =
                                "Miestnosť sa nepodarilo deaktivovať!";
                        }
                        window.scrollTo(0, 0);
                    })
            },
            editRoom() {
                axios
                    .put(
                        "/rooms/" + this.editItem.id,
                        {
                            name: this.editItem.name,
                            capacity: this.editItem.capacity,
                            parameters: this.checkedEditItems
                        },
                        {
                            headers: {
                                Authorization: "Bearer " + this.auth.getToken()
                            }
                        }
                    )
                    .then(response => {
                        this.errors = [];
                        this.messageFail = "";
                        this.invalidName = "";
                        this.invalidCapacity = "";
                        this.showEditForm = false;
                        this.all();
                        this.messageSuccess =
                            "Údaje miestnosti " +
                            this.editItem.name +
                            " boli zmenené!";
                        this.editItem = {
                            id: null,
                            name: null,
                            capacity: null,
                            isActive: false,
                            parameters: null,
                        };
                        this.checkedEditItems = [];
                        window.scrollTo(0, 0);
                    })
                    .catch(error => {
                        this.messageFail = "Údaje neboli zadané správne!";
                        this.errors = error.response.data.errors
                            ? error.response.data.errors
                            : [];
                        if (this.errors.capacity != undefined) {
                            this.invalidCapacity = "is-invalid";
                        } else {
                            this.invalidMail = "";
                        }
                        if (this.errors.name != undefined) {
                            this.invalidName = "is-invalid";
                        } else {
                            this.invalidName = "";
                        }
                        window.scrollTo(0, 0);
                    });
            },
            roomEvent(id) {
                this.showEventList = true;
                axios
                    .get("/roomsEvents/" + id, {
                        headers: {
                            Authorization: "Bearer " + this.auth.getToken()
                        }
                    })
                    .then(response => {
                        this.removeEvents = response.data;
                    });
                axios
                    .get("/rooms/" + id, {
                        headers: {
                            Authorization: "Bearer " + this.auth.getToken()
                        }
                    })
                    .then(response => {
                        this.removeRoom = response.data;
                    })
            },
            deleteEvent(id){
                axios
                    .delete("/events/"+id,{
                        headers: {
                            Authorization: "Bearer " + this.auth.getToken()}
                        })
                    .then(response => {
                        this.messageSuccess = "Udalosť bola úspešne odstranená!";
                        this.roomEvent(this.removeRoom.id);
                        window.scrollTo(0, 0);
                    })
            },
            removeAll(){
                this.removeEvents.forEach(item => {
                    axios
                        .delete("/events/"+item.id,{
                            headers: {
                                Authorization: "Bearer " + this.auth.getToken()}
                        })
                });
                this.closeModal();
                this.messageSuccess = "Boli úspešne odstránené všetky udalosti v miestnosti " + this.removeRoom.name + "! Všetci dotknutí používatelia budú informovaní prostredníctvom mailu, miestnosť je pripravené na vymazanie alebo deaktivovanie!"
                window.scrollTo(0, 0);
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
    };
</script>
