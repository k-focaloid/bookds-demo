<template>
        <div class="row justify-content-center">
            <div class="col-lg-5 col-md-7">
                <div class="card bg-secondary shadow border-0">
                    <div class="card-body px-lg-5 py-lg-5">
                        <div class="text-center text-muted mb-4">
                            <small>Sign in with credentials</small>
                        </div>
                        <form ref="form" validate @submit.prevent="login($event)">
                            <base-input class="input-group-alternative mb-3"
                                        placeholder="Email"
                                        required = "required"
                                        name="email"
                                        type="email"
                                        addon-left-icon="ni ni-email-83"
                                        v-model="model.email">
                            </base-input>

                            <base-input class="input-group-alternative"
                                        placeholder="Password"
                                        required = "required"
                                        name="password"
                                        type="password"
                                        addon-left-icon="ni ni-lock-circle-open"
                                        v-model="model.password">
                            </base-input>

                            <div class="text-center">
                                 <button type="submit" class="btn btn-md btn-primary">Login</button>
                            </div>
                            <div class="text-center">
                                <base-button type="link" @click="$router.push({ path: `/register` });">No Account ? Register here</base-button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        <!-- ERROR MODAL -->
         <modal :show.sync="modals.error"
               gradient="danger"
               modal-classes="modal-danger modal-dialog-centered">
            <h6 slot="header" class="modal-title" id="modal-title-notification">Invalid Credentials</h6>
            <div class="py-3 text-center">
                <i class="ni ni-bell-55 ni-3x"></i>
                <h4 class="heading mt-4">Login Failed!</h4>
                <p>Please check the email and password !</p>
            </div>
            <template slot="footer">
                <base-button type="white" @click="modals.error = false">Ok, Got it</base-button>
            </template>
        </modal>

        </div>
</template>
<script>
  export default {
    name: 'login',
    data() {
      return {
        modals:{
          error:false
        },
        model: {
          email: '',
          password: ''
        }
      }
    },
    methods:{
      login(event){
        event.preventDefault();
        if (!this.$refs.form.checkValidity()) {
            this.$refs.form.reportValidity();
            return true;
          }

          this.$auth.login(this.model.email,this.model.password)
          .then(function(data){
            this.$router.push({ path: `/books/` });
          }.bind(this))
          .catch(function(data){
            this.modals.error = true;
          }.bind(this));
      }
    },
    mounted(){
      
    }
  }
</script>
<style>
</style>
