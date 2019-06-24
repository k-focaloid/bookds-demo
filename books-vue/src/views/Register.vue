<template>
    <div class="row justify-content-center">
        <div class="col-lg-5 col-md-7">
            <div class="card bg-secondary shadow border-0">

                <div class="card-body px-lg-5 py-lg-5">
                    <div class="text-center text-muted mb-4">
                        <small>Sign up with credentials</small>
                    </div>
                    <form role="form" ref="form" @submit.prevent="register($event)">

                        <base-input class="input-group-alternative mb-3"
                                    placeholder="Name"
                                    name="name"
                                    required="required"
                                    addon-left-icon="ni ni-hat-3"
                                    v-model="model.name">
                        </base-input>

                        <base-input class="input-group-alternative mb-3"
                                    name="email"
                                    required="required"
                                    type="email"
                                    placeholder="Email"
                                    addon-left-icon="ni ni-email-83"
                                    v-model="model.email">
                        </base-input>

                        <base-input class="input-group-alternative"
                                    placeholder="Password"
                                    required="required"
                                    name="password"
                                    type="password"
                                    addon-left-icon="ni ni-lock-circle-open"
                                    v-model="model.password">
                        </base-input>

                        <div class="text-center">
                            <button type="submit" class="btn btn-md btn-primary">Create account</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <modal :show.sync="modals.success">
            <template slot="header">
                <h5 class="modal-title" id="exampleModalLabel">Account Created !</h5>
            </template>
            <div>
            <p>Hurray !, your account is created and now active, please login to continue.</p>
            </div>
            <template slot="footer">
                <base-button type="primary" @click="$router.push({ path: `/login` });">Login</base-button>
            </template>
        </modal>

        <!-- ERROR MODAL -->
         <modal :show.sync="modals.error"
               gradient="danger"
               modal-classes="modal-danger modal-dialog-centered">
            <h6 slot="header" class="modal-title" id="modal-title-notification">Error</h6>
            <div class="py-3 text-center">
                <i class="ni ni-bell-55 ni-3x"></i>
                <h4 class="heading mt-4">An Error Occured!</h4>
                <p class="text-left">Please correct the following error(s):</p>
                <p>
                  <ul>
                  <li v-for="error in Object.keys(errors)" v-bind:key="error" class="text-danger text-left">{{ errors[error].join(",") }}</li>
                  </ul>
                </p>
            </div>
            <template slot="footer">
                <base-button type="white" @click="modals.error = false">Ok, Got it</base-button>
            </template>
        </modal>
    </div>
</template>
<script>
import axios from 'axios';
  export default {
    name: 'register',
    data() {
      return {
        errors:{},
        modals:{
          error:false,
          success:false
        },
        model: {
          name: '',
          email: '',
          password: ''
        }
      }
    },
    methods:{
      register(event){
         event.preventDefault();
         this.errors=[];
        if (!this.$refs.form.checkValidity()) {
            this.$refs.form.reportValidity();
            return true;
          }
          let baseApi = this.$baseAPI;
          axios.post(baseApi + 'signup',{
            name:this.model.name,
            email:this.model.email,
            password:this.model.password
          },
              {
                  headers: {
                        'Content-Type': 'application/json',
                  }
              }
          )
          .then(function(data){
            this.modals.success = true;
          }.bind(this))
          .catch(function(data){
            if(data.response && data.response.data && data.response.data.errors){
              console.log(data.response.data.errors);
              this.errors = JSON.parse(JSON.stringify(data.response.data.errors));
            }
            else{
              this.errors={error: ["Network Error !"]};
            }
            this.modals.error = true;
          }.bind(this));

      }
    }
  }
</script>
<style>
</style>
