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
       <!-- <div class="row">
            <div class="col-lg-4"></div>
            <div class="col-lg-6">
                <input class="au-input au-input&#45;&#45;xl" type="text" v-model="keyword" name="search" placeholder="Zadajte výraz ..." />
                <button class="btn btn-link btn-lg" type="submit" v-on:click="select()">
                    <i class="zmdi zmdi-search"></i>
                </button>
            </div>
            <div class="col-lg-2"></div>
        </div>-->
            <div class="table-responsive table-responsive-data2">
                <table class="table table-data2" id="myTable">
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
                                <button type="button" class="btn btn-outline-danger" v-on:click="remove(item.id)">Odhlásiť sa z eventu</button>

                            </div>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
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
                errors: [],
                keyword: ''
            };
        },
        mounted() {
            this.all();
        },
        methods: {
            all() {
                axios
                    .get("/myevents", {
                        headers: { Authorization: "Bearer " + this.auth.getToken() }
                    })
                    .then(response => {
                        this.items = response.data;
                    });
            },
            remove(id) {
                axios
                    .delete("/removeFromEvent/"+id,
                        {
                        headers: { Authorization: "Bearer " + this.auth.getToken() }
                    })
                    .then(response => {
                        this.all();
                        this.messageSuccess =
                            "Boli ste úspešne odhlásený z udalosti, autor udalosti bude o tejto akcii informovaný!";
                    })
                    .catch(error => {
                        this.messageFail = "Nepodarilo sa úspešne odhlásiť z udalosti!";
                    });
            },
            closeAlert() {
                this.messageFail = null;
                this.messageSuccess = null;
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
