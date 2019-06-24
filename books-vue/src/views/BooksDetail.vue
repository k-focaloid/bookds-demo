<template>
    <div class="row justify-content-center">
        <div class="col-xs-12 col-xl-6 order-xl-6 my-5 mx-5">

                    <div class="card card-profile shadow">

                        <div class="card-header text-center border-0 pt-8 pt-md-4 pb-0 pb-md-4">
                            <div class="d-flex justify-content-between">
                                <base-button size="sm" type="info" class="mr-4" @click="downloadBook()">Download</base-button>
                                <base-button size="sm" type="default" class="float-right" @click="$router.push({ path: `/books` });">Back</base-button>
                            </div>
                        </div>
                        <div class="card-body pt-0 pt-md-4">

                            <div class="text-center">
                                <h3>{{book_detail.name}}<span class="font-weight-light"></span>
                                </h3>
                                <div class="h5">
                                    Genre: {{book_detail.genre}}, By: {{book_detail.author}}
                                </div>
                                <hr class="my-4" />
                                <p>{{book_detail.description}}.</p>
                            </div>
                        </div>
                    </div>
                </div>
            <modal :show.sync="modals.error"
               gradient="danger"
               modal-classes="modal-danger modal-dialog-centered">
            <h6 slot="header" class="modal-title" id="modal-title-notification">Error</h6>
            <div class="py-3 text-center">
                <i class="ni ni-bell-55 ni-3x"></i>
                <h4 class="heading mt-4">An Error Occured!</h4>
                <p class="text-left">Please make sure you are connected to the network and using correct book id.</p>
            </div>
            <template slot="footer">
                <base-button type="white" @click="modals.error = false;$router.push({ path: `/books` });">Ok, Got it</base-button>
            </template>
        </modal>
    </div>
</template>
<script>
import axios from 'axios';
  export default {
    data(){
        return {
            modals:{
                error:false
            },
            book_detail:{
                author: '',
                book_link: '',
                description: '',
                genre: '',
                name: '',
            }
        };
    },
    methods:{
        downloadBook(){
            if(this.book_detail.book_link) window.location.href = this.book_detail.book_link;
        },
        loadBook(){

          let baseApi = this.$baseAPI;
          axios.get(baseApi + 'book/' + this.$route.params.id)
          .then(function(data){
              this.book_detail = data.data.data;
            //this.modals.success = true;
          }.bind(this))
          .catch(function(data){
            this.modals.error = true;
          }.bind(this));
        }
    },
    mounted() {
        this.loadBook();
    }
  }
</script>
<style>
</style>
