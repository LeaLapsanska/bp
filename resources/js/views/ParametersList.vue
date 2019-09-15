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

        <template v-if="!showAddForm && !showEditForm">
        <div class="table-data__tool">
                <button class="au-btn au-btn-icon au-btn--green au-btn--block" v-on:click="add()">
                    <i class="zmdi zmdi-plus"></i>Pridať parameter</button>

        </div>
        <div class="table-responsive table-responsive-data2">
            <table class="table table-data2" id="myTable">
                <thead>
                <tr>
                    <th>Názov parametra</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="item in items" ref="datatable">
                    <td>
                        <span class="block-email">{{ item.name }}</span>
                    </td>
                    <td>
                        <div class="table-data-feature">
                            <button class="item" data-toggle="tooltip" data-placement="top" title="Upraviť" v-on:click="edit(item.id)">
                                <i class="zmdi zmdi-edit"></i>
                            </button>
                            <button class="item" data-toggle="tooltip" data-placement="top" title="Vymazať" v-on:click="destroy(item.id)">
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

        <!-- edit form -->
        <template v-if="showEditForm">
            <div class="card">
                <div class="card-header">
                    <strong>Detail parametra</strong>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"
                            v-on:click="closeModal()">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="card-body card-block">
                    <form @submit.prevent="editParameter()">
                        <div class="form-group">
                            <div class="input-group">
                                <div class="input-group-addon">
                                    <i class="fa fa-user"></i>
                                </div>
                                <input type="text" v-model="editItem.name" class="form-control" v-bind:class="invalidName"
                                >
                            </div>
                            <span class="help-block" v-if="errors.name">Písmenkami nešetri a toto pole nenechaj prázdne</span>
                        </div>
                            <button type="submit" class="btn btn-info btn-block">
                                Uprav parameter
                            </button>
                    </form>
                </div>
            </div>
        </template>
        <!-- end edit form -->

        <!-- add form -->
        <template v-if="showAddForm">
            <div class="card">
                <div class="card-header">
                    <strong>Pridať parameter</strong>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"
                            v-on:click="closeModal()">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="card-body card-block">
                    <form @submit.prevent="addParameter()">
                        <div class="form-group">
                            <div class="input-group">
                                <div class="input-group-addon">
                                    <i class="fa fa-user"></i>
                                </div>
                                <input type="text" v-model="addItem.name" class="form-control" v-bind:class="invalidName"
                                >
                            </div>
                            <span class="help-block" v-if="errors.name">Písmenkami nešetri a toto pole nenechaj prázdne</span>
                        </div>
                            <button type="submit" class="btn btn-info btn-block">
                                Pridaj parameter
                            </button>
                    </form>
                </div>
            </div>
        </template>
        <!-- end add form -->
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
                messageFail: null,
                showEditForm: false,
                editItem: {
                    id: null,
                    name: null,
                 },
                id: null,
                errors: [],
                invalidName: null,
                invalidMail: null,
                addItem: {
                    id: null,
                    name: null,

                },
                showAddForm: false,
                invalidPassword: null
            };
        },
        mounted() {
            this.all()
        },
        methods: {
            all() {
                axios
                    .get("/parameters", {
                        headers: {Authorization: "Bearer " + this.auth.getToken()}
                    })
                    .then(response => {
                        this.items = response.data;
                    });
            },
            destroy(id) {
                axios
                    .delete("/parameters/" + id, {
                        headers: {Authorization: "Bearer " + this.auth.getToken()}
                    })
                    .then(response => {
                        this.messageSuccess = "Parameter bol úspešne odstranený!";
                        this.all();
                        window.scrollTo(0, 0);

                    })
                    .catch(response => {
                        this.messageFail = "Pri vymazávaní nastala chyba!";
                        window.scrollTo(0, 0);

                    });
            },
            closeAlert() {
                this.messageFail = null;
                this.messageSuccess = null;
            },
            edit(id) {
                axios
                    .get("/parameters/" + id, {
                        headers: {Authorization: "Bearer " + this.auth.getToken()}
                    })
                    .then(response => {
                        this.editItem = response.data;
                        this.showEditForm = true;
                    });
            },
            editParameter() {
                axios
                    .put(
                        "/parameters/" + this.editItem.id,
                        {
                            name: this.editItem.name
                        },
                        {
                            headers: {
                                Authorization: "Bearer " + this.auth.getToken()
                            }
                        }
                    )
                    .then(response => {
                        this.errors = [];
                        this.invalidName = "";
                        this.messageFail = "";
                        this.showEditForm = false;
                        this.all();
                        this.messageSuccess =
                            "Údaje parametra " + this.editItem.name + " boli zmenené!";
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
                        window.scrollTo(0, 0);

                    });
            },
            closeModal() {
                this.showEditForm = false;
                this.showAddForm = false;
                this.invalidName = "";
                this.messageFail = null;
                this.errors = [];
            },
            add(){
                this.showAddForm = true;
            },
            addParameter() {
                axios
                    .post(
                        "/parameters",
                        {
                            name: this.addItem.name,
                        },
                        {
                            headers: {
                                Authorization: "Bearer " + this.auth.getToken()
                            }
                        }
                    )
                    .then(response => {
                        this.invalidName = "";
                        this.showAddForm = false;
                        this.messageFail = "";
                        this.all();
                        this.messageSuccess =
                            "Bol pridaný nový parameter: " + this.addItem.name;
                        this.addItem = {
                            id: null,
                            name: null,
                        };
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
                        window.scrollTo(0, 0);
                    });
            },

        }
    }
</script>

