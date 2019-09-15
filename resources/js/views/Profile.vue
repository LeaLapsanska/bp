<template>
    <div>
        <div v-if="message2" class="sufee-alert alert with-close alert-info alert-dismissible fade show">
            {{message2}}
            <button v-on:click="closeAlert()" type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">×</span>
            </button>
        </div>
        <div v-if="message" class="sufee-alert alert with-close alert-danger alert-dismissible fade show">
            {{message}}
            <button v-on:click="closeAlert()" type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">×</span>
            </button>
        </div>
        <div class="card">
            <div class="card-header">
                <strong>Osobné údaje</strong>
            </div>
            <div class="card-body card-block">
                <form @submit.prevent="edit()">
                    <div class="form-group">
                        <div class="input-group">
                            <div class="input-group-addon">Email</div>
                            <input type="text" v-model="email" class="form-control" v-bind:class=invalidMail>
                            <div class="input-group-addon">
                                <i class="fa fa-envelope"></i>
                            </div>
                        </div>
                        <span class="help-block">{{ emailMessage }}</span>
                    </div>
                    <div class="form-group">
                        <div class="input-group">
                            <div class="input-group-addon">Meno a priezvisko</div>
                            <input type="text" v-model="name" class="form-control" v-bind:class=invalidName>
                            <div class="input-group-addon">
                                <i class="fa fa-user"></i>
                            </div>
                        </div>
                        <span class="help-block">{{ nameMessage }}</span>
                    </div>
                    <div class="form-group">
                        <div class="input-group">
                            <div class="input-group-addon">Heslo</div>
                            <input type="password" v-model="password" class="form-control">
                            <div class="input-group-addon">
                                <i class="fa fa-asterisk"></i>
                            </div>
                        </div>
                        <span
                            class="help-block">Svoje heslo si môžeš zmeniť pri úprave profilu. Dobre si ho zapamätaj</span>
                    </div>
                    <button type="submit" class="btn btn-info btn-block">Upraviť profil</button>
                </form>
            </div>
            <div class="card-footer">
                <br>
            </div>
            <div>
            </div>
        </div>
    </div>
</template>

<script>
    import auth from "../auth/index.js";

    export default {
        data() {
            return {
                auth: auth,
                name: null,
                email: null,
                isAdmin: null,
                isManager: null,
                isActive: null,
                password: null,
                message: null,
                message2: null,
                invalidMail: '',
                invalidName: '',
                emailMessage: '',
                nameMessage: '',
                errors: []
            }
        },
        mounted() {
            axios
                .get('/profile', {
                    headers: {Authorization: "Bearer " + this.auth.getToken()}
                })
                .then(response => {
                    (
                        this.name = response.data.name,
                            this.email = response.data.email,
                            this.isAdmin = response.data.isAdmin,
                            this.isActive = response.data.isActive,
                            this.isManager = response.data.isManager
                    )
                })
        },
        methods: {
            edit() {
                axios
                    .patch('/profile', {
                            email: this.email,
                            name: this.name,
                            password: this.password
                        },
                        {
                            headers: {Authorization: "Bearer " + this.auth.getToken()}
                        })
                    .then(response => {
                        (
                            this.name = response.data.name,
                                this.email = response.data.email,
                                this.message = null,
                                this.message2 = "Profil bol úspešne upravený!",
                                this.errors = [],
                                this.invalidName = "",
                                this.invalidMail = "",
                                this.nameMessage = "",
                                this.emailMessage = ""
                        )
                    })
                    .catch(error => {
                        console.log(error.response);
                        this.message = "Údaje neboli zadané správne!";
                        this.errors = error.response.data.errors ? error.response.data.errors : [];
                        if (this.errors.email != undefined) {
                            this.invalidMail = "is-invalid";
                            this.emailMessage = 'Zadaj správny formát emailu john.doe@example.sk';
                        } else {
                            this.invalidMail = "";
                            this.emailMessage = "";
                        }
                        if (this.errors.name != undefined) {
                            this.invalidName = "is-invalid";
                            this.nameMessage = 'Písmenkami nešetri a toto pole nenechaj prázdne';
                        } else
                        {
                            this.invalidName = "";
                            this.nameMessage = "";
                        }
                    });
            },
            closeAlert() {
                this.message2 = null;
                this.message = null;
            }
        }
    }

</script>
