<template>
    <div>
        <div v-if="message" class="sufee-alert alert with-close alert-success alert-dismissible fade show">
            {{messageSuccess}}
            <button v-on:click="closeAlert()" type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">×</span>
            </button>
        </div>
        <div v-if="message" class="sufee-alert alert with-close alert-danger alert-dismissible fade show">
            {{messageFail}}
            <button v-on:click="closeAlert()" type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">×</span>
            </button>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                messageFail: null,
                messageSuccess: null
            }
        },
        methods: {
            closeAlert() {
                this.messageFail = null;
                this.messageSuccess = null;
            }
        }
    }
</script>
