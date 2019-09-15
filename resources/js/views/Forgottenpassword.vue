<template>
    <div class="page-wrapper">
        <div class="page-content--bge5 bg-imagePassword">
            <div class="container">
                <div class="login-wrap">
                    <div class="login-content">
                        <div class="login-form">
                            <div v-if="message" class="alert alert-info" role="alert">
                                {{message}} Opätovne sa môžeš<button class="btn btn-outline-link" v-on:click="login()">
                                <i class="fa fa-map-marker"></i> PRIHLÁSIŤ</button>
                            </div>
                            <form @submit.prevent="send()">
                                <div class="form-group">
                                    <label>Email</label>
                                    <input class="au-input au-input--full form-control" type="text" v-model="email" v-bind:class=invalidMail>
                                    <span class="help-block">{{ emailMessage }}</span>
                                </div>
                                <button class="au-btn au-btn--block au-btn--green m-b-20" type="submit">Zaslať nové heslo</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
    export default{
        data() {
            return {
                email: null,
                message: null,
                errors: [],
                emailMessage: '',
                invalidMail: ''
            };
        },
        methods: {
            send() {
                axios
                    .post('forgottenpassword',{
                    email: this.email
                })
                    .then(response => {(
                        this.message = "Na zadanú emailovú adresu bolo zaslané nové heslo.",
                        this.email = null
                    )})
                    .catch(error => {
                        console.log(error.response);
                        this.message = "V našom systéme nie je registrovaný používateľ s danou emailovou adresou!";
                        this.errors = error.response.data.errors ? error.response.data.errors : [];
                        if (this.errors.email != undefined)
                        {
                            this.invalidMail = "is-invalid";
                            this.emailMessage = 'Zadaj správny formát emailu john.doe@example.sk';
                        }
                        else {
                            this.invalidMail = "";
                            this.emailMessage = "";
                        }

                    });
            },
            login() {
                this.$router.push('/login');
            }
        }
    }
</script>

