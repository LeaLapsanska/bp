<template>
    <div class="col-md-12">
        <!-- <div class="modal-backdrop fade show" v-if="showAddForm || showEditForm"></div> -->
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
                            <i class="zmdi zmdi-plus"></i>Pridať používateľa
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
                        <th>Meno a priezvisko</th>
                        <th>E-mail</th>
                        <th>Aktívny</th>
                        <th>Admin</th>
                        <th>Manažér</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="item in items" ref="datatable">
                        <td>{{ item.name }}</td>
                        <td>
                            <span class="block-email">{{ item.email }}</span>
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
                        <td v-if="item.isAdmin">
                                <span class="status--process">
                                    <i class="fa fa-unlock-alt"></i>
                                </span>
                        </td>
                        <td v-else="item.isAdmin">
                                <span class="status--denied">
                                    <i class="fa fa-lock"></i>
                                </span>
                        </td>
                        <td v-if="item.isManager">
                                <span class="status--process">
                                    <i class="fa fa-unlock-alt"></i>
                                </span>
                        </td>
                        <td v-else="item.isManager">
                                <span class="status--denied">
                                    <i class="fa fa-lock"></i>
                                </span>
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
                                <button
                                    class="item"
                                    data-toggle="tooltip"
                                    data-placement="top"
                                    data-original-title="Aktivovať"
                                    v-on:click="activate(item.id)"
                                >A
                                </button>
                                <button
                                    class="item"
                                    data-toggle="tooltip"
                                    data-placement="top"
                                    title="Deaktivovať"
                                    v-on:click="deactivate(item.id)"
                                >D
                                </button>
                            </div>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </template>
        <!-- END DATA TABLE -->

        <!-- start add form -->
        <template v-if="showEditForm">
            <div class="card">
                <div class="card-header">
                    <strong>Upraviť údaje</strong>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"
                            v-on:click="closeModal()">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="card-body card-block">
                    <form @submit.prevent="editUser()">
                        <div class="form-group">
                            <div class="input-group">
                                <div class="input-group-addon">
                                    <i class="fa fa-user"></i>
                                </div>
                                <input
                                    type="text"
                                    v-model="editItem.name"
                                    class="form-control"
                                    v-bind:class="invalidName"
                                >
                            </div>
                            <span
                                class="help-block"
                                v-if="errors.name"
                            >Písmenkami nešetri a toto pole nenechaj prázdne</span>
                        </div>
                        <div class="form-group">
                            <div class="input-group">
                                <div class="input-group-addon">
                                    <i class="fa fa-envelope"></i>
                                </div>
                                <input
                                    type="text"
                                    v-model="editItem.email"
                                    class="form-control"
                                    v-bind:class="invalidMail"
                                >
                            </div>
                            <span
                                class="help-block"
                                v-if="errors.email"
                            >Zadaj správny formát emailu john.doe@example.sk</span>
                        </div>
                        <div class="form-group">
                            <div class="input-group">
                                <div class="input-group-addon">
                                    <i class="fa fa-asterisk"></i>
                                </div>
                                <input
                                    type="password"
                                    v-model="editItem.password"
                                    class="form-control"
                                >
                            </div>
                        </div>
                        <div class="row form-group">
                            <div class="col col-md-3">
                                <label class="form-control-label">Administrátor</label>
                            </div>
                            <div class="col col-md-9">
                                <div class="form-check-inline form-check">
                                    <label for="admin" class="form-check-label">
                                        <input
                                            type="checkbox"
                                            id="admin"
                                            class="form-check-input"
                                            v-model="editItem.isAdmin"
                                        >
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="row form-group">
                            <div class="col col-md-3">
                                <label class="form-control-label">Manažér</label>
                            </div>
                            <div class="col col-md-9">
                                <div class="form-check-inline form-check">
                                    <label for="manager" class="form-check-label">
                                        <input
                                            type="checkbox"
                                            id="manager"
                                            class="form-check-input"
                                            v-model="editItem.isManager"
                                        >
                                    </label>
                                </div>
                            </div>
                        </div>
                        <button
                            type="submit"
                            class="btn btn-info btn-block"
                        >Uprav údaje používateľa
                        </button>
                    </form>
                </div>
            </div>
        </template>
        <!-- end add form -->
        <!-- start add form -->
        <template v-if="showAddForm">
            <div class="card">
                <div class="card-header">
                    <strong>Pridať používateľa</strong>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"
                            v-on:click="closeModal()">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="card-body card-block">
                    <form @submit.prevent="addUser()">
                        <div class="form-group">
                            <div class="input-group">
                                <div class="input-group-addon">
                                    <i class="fa fa-user"></i>
                                </div>
                                <input
                                    type="text"
                                    v-model="addItem.name"
                                    class="form-control"
                                    v-bind:class="invalidName"
                                >
                            </div>
                            <span
                                class="help-block"
                                v-if="errors.name"
                            >Písmenkami nešetri a toto pole nenechaj prázdne</span>
                        </div>
                        <div class="form-group">
                            <div class="input-group">
                                <div class="input-group-addon">
                                    <i class="fa fa-envelope"></i>
                                </div>
                                <input
                                    type="text"
                                    v-model="addItem.email"
                                    class="form-control"
                                    v-bind:class="invalidMail"
                                >
                            </div>
                            <span
                                class="help-block"
                                v-if="errors.email"
                            >Zadaj správny formát emailu john.doe@example.sk</span>
                        </div>
                        <div class="form-group">
                            <div class="input-group">
                                <div class="input-group-addon">
                                    <i class="fa fa-asterisk"></i>
                                </div>
                                <input
                                    type="password"
                                    v-model="addItem.password"
                                    class="form-control"
                                    v-bind:class="invalidPassword"
                                >
                            </div>
                            <span
                                class="help-block"
                                v-if="errors.password"
                            >Heslo musí obsahovať minimálne 6 znakov</span>
                        </div>
                        <div class="row form-group">
                            <div class="col col-md-3">
                                <label class="form-control-label">Aktívny používateľ</label>
                            </div>
                            <div class="col col-md-9">
                                <div class="form-check-inline form-check">
                                    <label class="form-check-label">
                                        <input
                                            type="checkbox"
                                            class="form-check-input"
                                            v-model="addItem.isActive"
                                        >
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="row form-group">
                            <div class="col col-md-3">
                                <label class="form-control-label">Administrátor</label>
                            </div>
                            <div class="col col-md-9">
                                <div class="form-check-inline form-check">
                                    <label class="form-check-label">
                                        <input
                                            type="checkbox"
                                            class="form-check-input"
                                            v-model="addItem.isAdmin"
                                        >
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="row form-group">
                            <div class="col col-md-3">
                                <label class="form-control-label">Manažér</label>
                            </div>
                            <div class="col col-md-9">
                                <div class="form-check-inline form-check">
                                    <label class="form-check-label">
                                        <input
                                            type="checkbox"
                                            class="form-check-input"
                                            v-model="addItem.isManager"
                                        >
                                    </label>
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-info btn-block">Pridaj používateľa</button>
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
                    password: null,
                    email: null,
                    isActive: null,
                    isAdmin: null,
                    isManager: null
                },
                id: null,
                errors: [],
                invalidName: null,
                invalidMail: null,
                addItem: {
                    id: null,
                    name: null,
                    password: null,
                    email: null,
                    isActive: false,
                    isAdmin: false,
                    isManager: false
                },
                showAddForm: false,
                invalidPassword: null,
                keyword: ''
            };
        },
        mounted() {
            this.all();
        },
        methods: {
            all() {
                axios
                    .get("/users", {
                        headers: {Authorization: "Bearer " + this.auth.getToken()}
                    })
                    .then(response => {
                        this.items = response.data;
                    });
            },
            destroy(id) {
                axios
                    .delete("/users/" + id, {
                        headers: {Authorization: "Bearer " + this.auth.getToken()}
                    })
                    .then(response => {
                        this.messageSuccess = "Používateľ bol úspešne odstranený!";
                        this.all();
                        window.scrollTo(0, 0);
                    })
                    .catch(response => {
                        this.messageFail = "Používateľ nemôže vymazať sám seba!";
                        window.scrollTo(0, 0);
                    });
            },
            closeAlert() {
                this.messageFail = null;
                this.messageSuccess = null;
            },
            edit(id) {
                axios
                    .get("/users/" + id, {
                        headers: {Authorization: "Bearer " + this.auth.getToken()}
                    })
                    .then(response => {
                        this.editItem = response.data;
                        this.showEditForm = true;
                    });
            },
            editUser() {
                axios
                    .put(
                        "/users/" + this.editItem.id,
                        {
                            name: this.editItem.name,
                            email: this.editItem.email,
                            password: this.editItem.password,
                            isAdmin: this.editItem.isAdmin,
                            isManager: this.editItem.isManager
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
                        this.invalidMail = "";
                        this.messageFail = "";
                        this.showEditForm = false;
                        this.all();
                        this.messageSuccess =
                            "Údaje používateľa " +
                            this.editItem.name +
                            " boli zmenené";
                        window.scrollTo(0, 0);
                    })
                    .catch(error => {
                        if (error.response.status == 400) {
                            this.messageFail =
                                "Prihlásený používateľ môže svoje údaje meniť len v profile!";
                        } else {
                            this.messageFail = "Údaje neboli zadané správne!";
                        }
                        this.errors = error.response.data.errors
                            ? error.response.data.errors
                            : [];
                        if (this.errors.email != undefined) {
                            this.invalidMail = "is-invalid";
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
            closeModal() {
                this.showEditForm = false;
                this.showAddForm = false;
                this.invalidName = "";
                this.invalidMail = "";
                this.invalidPassword = "";
                this.messageFail = null;
                this.errors = [];
            },
            add() {
                this.showAddForm = true;
            },
            addUser() {
                axios
                    .post(
                        "/users",
                        {
                            email: this.addItem.email,
                            name: this.addItem.name,
                            password: this.addItem.password,
                            isActive: this.addItem.isActive,
                            isAdmin: this.addItem.isAdmin,
                            isManager: this.addItem.isManager
                        },
                        {
                            headers: {
                                Authorization: "Bearer " + this.auth.getToken()
                            }
                        }
                    )
                    .then(response => {
                        this.invalidName = "";
                        this.invalidMail = "";
                        this.invalidPassword = "";
                        this.messageFail = "";
                        this.showAddForm = false;
                        this.all();
                        this.messageSuccess =
                            "Bol pridaný nový používateľ: " + this.addItem.name;
                        this.addItem = {
                            id: null,
                            name: null,
                            password: null,
                            email: null,
                            isActive: false,
                            isAdmin: false,
                            isManager: false
                        };
                        this.showAddForm = false;
                        window.scrollTo(0, 0);
                    })
                    .catch(error => {
                        this.messageFail = "Údaje neboli zadané správne!";
                        this.errors = error.response.data.errors
                            ? error.response.data.errors
                            : [];
                        if (this.errors.email != undefined) {
                            this.invalidMail = "is-invalid";
                        } else {
                            this.invalidMail = "";
                        }
                        if (this.errors.name != undefined) {
                            this.invalidName = "is-invalid";
                        } else {
                            this.invalidName = "";
                        }
                        if (this.errors.password != undefined) {
                            this.invalidPassword = "is-invalid";
                        } else {
                            this.invalidPassword = "";
                        }
                        window.scrollTo(0, 0);
                    });
            },
            activate(id) {
                axios
                    .patch("/users/" + id + "/activate", null, {
                        headers: {Authorization: "Bearer " + this.auth.getToken()}
                    })
                    .then(response => {
                        this.all();
                        this.messageSuccess = "Používateľ bol úspešne aktivovaný!";
                        this.messageFail = "";
                    })
                    .catch(error => {
                        if (error.response.status == 400) {
                            this.messageFail =
                                "Prihlásený používateľ nemôže aktivovať sám seba!";
                        } else {
                            this.messageFail =
                                "Používateľa sa nepodarilo aktivovať!";
                        }
                        window.scrollTo(0, 0);
                    });
            },
            deactivate(id) {
                axios
                    .patch("/users/" + id + "/deactivate", null, {
                        headers: {Authorization: "Bearer " + this.auth.getToken()}
                    })
                    .then(response => {
                        this.all();
                        this.messageSuccess =
                            "Používateľ bol úspešne deaktivovaný!";
                        this.messageFail = "";
                        window.scrollTo(0, 0);
                    })
                    .catch(error => {
                        if (error.response.status == 400) {
                            this.messageFail =
                                "Prihlásený používateľ nemôže deaktivovať sám seba!";
                        } else {
                            this.messageFail =
                                "Používateľa sa nepodarilo deaktivovať!";
                        }
                        window.scrollTo(0, 0);
                    });
            },
            select() {
                console.log(this.keyword);
                axios
                    .post('/searchUser',
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
