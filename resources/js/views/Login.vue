<template>
    <div class="page-wrapper">
        <div class="page-content--bge5 bg-imageLogin">
            <div class="container">
                <div class="login-wrap">
                    <div class="login-content">
                        <div class="login-logo">
                            <a href="#">
                                <img src="images/logo.png" alt="Room Management System">
                            </a>
                        </div>
                        <div class="login-form">
                            <div v-if="message" class="alert alert-danger" role="alert">
                                {{message}}
                            </div>
                            <form @submit.prevent="login()">
                                <div class="form-group">
                                    <label>Email</label>
                                    <input class="au-input au-input--full form-control" type="text" v-model="email"
                                           v-bind:class=invalidMail>
                                    <span class="help-block">{{ emailMessage }}</span>
                                </div>
                                <div class="form-group">
                                    <label>Heslo</label>
                                    <input class="au-input au-input--full form-control" type="password"
                                           v-model="password" v-bind:class=invalidName>
                                    <span class="help-block">{{ nameMessage }}</span>
                                </div>
                                <button class="au-btn au-btn--block au-btn--green m-b-20" type="submit">Prihlásiť sa
                                </button>
                                <div class="social-login-content">
                                    <div class="social-button">
                                        <button class="au-btn au-btn--block au-btn--blue2"
                                                v-on:click="forgottenpassword()">Zabudnuté heslo
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import auth from "../auth/index.js";

    export default {
        data() {
            return {
                email: null,
                password: null,
                auth: auth,
                message: null,
                errors: [],
                invalidMail: '',
                invalidName: '',
                emailMessage: '',
                nameMessage: ''
            };
        },
        methods: {
            forgottenpassword() {
                this.$router.push("/forgottenpassword");
            },
            login() {
                axios
                    .post("login", {
                        email: this.email,
                        password: this.password
                    })
                    .then(response => {
                        auth.setToken(response.data.data.token);
                        axios
                            .get("/actuall", {
                                headers: {Authorization: "Bearer " + this.auth.getToken()}
                            })
                            .then(response => {
                                auth.user(JSON.stringify(response.data));
                                // console.log(JSON.stringify(response.data));
                                this.$router.push("/");
                                window.location.reload();
                            })
                    })
                    .catch(error => {
                        console.log(error.response);
                        this.message = 'Zadané údaje nie sú správne!';
                        this.errors = error.response.data.errors ? error.response.data.errors : [];
                        if (this.errors.email != undefined) {
                            this.invalidMail = "is-invalid";
                            this.emailMessage = 'Toto pole nevynechaj a nezabudni na správny formát emailu john.doe@example.sk';
                        } else {
                            this.invalidMail = "";
                            this.emailMessage = "";
                            this.message = 'Zadané heslo je nesprávne!';
                        }
                        if (this.errors.password != undefined) {
                            this.invalidName = "is-invalid";
                            this.nameMessage = 'Zadané heslo je nesprávne';
                        }
                    });

            }
        }
    };
</script>
