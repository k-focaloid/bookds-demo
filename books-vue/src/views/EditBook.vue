<template>
    <div>
        <div class="container-fluid mt-2">
            <div class="row">
                <div class="col-12 order-xl-1">
                    <card shadow type="secondary">
                        <div slot="header" class="bg-white border-0">
                            <div class="row align-items-center">
                                <div class="col-8">
                                    <h3 class="mb-0">Update Book</h3>
                                </div>
                                <div class="col-4 text-right">
                                    <a href="#!" class="btn btn-sm btn-primary" @click="navigateToList($event)">Back</a>
                                </div>
                            </div>
                        </div>
                        <template>
                            <form @submit.prevent @submit="submitForm($event)" ref="formBook">

                                 <p v-if="errors.length">
                                    <b>Please correct the following error(s):</b>
                                    <ul>
                                    <li v-for="error in errors" v-bind:key="error" class="text-danger">{{ error }}</li>
                                    </ul>
                                </p>

                                <h6 class="heading-small text-muted mb-4">Book details</h6>
                                <div class="pl-lg-4">
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <base-input alternative=""
                                                        name="book_name"
                                                        label="Book Title"
                                                        placeholder="Book Title"
                                                        input-classes="form-control-alternative"
                                                        v-model="model.book_name"
                                            />
                                        </div>
                                      
                                        <div class="col-lg-6">
                                              <label class="form-control-label">Genre</label>
                                                    <select 
                                                    name="book_genre"
                                                    v-model="model.book_genre" 
                                                    class="form-control form-control-alternative">
                                                    <option disabled value="">Please select a genre</option>
                                                        <option
                                                        v-for="(item,index) in genre_list"
                                                        v-bind:value="item.id"
                                                        v-bind:key="index"
                                                        >{{ item.name }}</option>
                                                    </select>                 
                                        </div>
                                    </div>

                                </div>
                                <hr class="my-4" />
                                <!-- Description -->
                                <h6 class="heading-small text-muted mb-4">Book Details</h6>
                                <div class="pl-lg-4">
                                    <div class="form-group">
                                        <base-input alternative=""
                                                    label="About the Book">
                                            <textarea rows="4" v-model="model.book_description"  class="form-control form-control-alternative" placeholder="A few words about your book ..."></textarea>
                                        </base-input>
                                    </div>
                                </div>
                                <hr class="my-4" />
                                <div class="col-12 text-right">
                                    <button type="submit" class="btn btn-md btn-primary">Save</button>
                                </div>
                            </form>
                        </template>
                    </card>
                </div>
            </div>
        </div>
        <!-- ERROR MODAL -->
         <modal :show.sync="modals.error"
               gradient="danger"
               modal-classes="modal-danger modal-dialog-centered">
            <h6 slot="header" class="modal-title" id="modal-title-notification">Your attention is required</h6>
            <div class="py-3 text-center">
                <i class="ni ni-bell-55 ni-3x"></i>
                <h4 class="heading mt-4">Book Updation Failed!</h4>
                <p>Opps, Something went wrong while trying to make the request, please try again later !</p>
            </div>
            <template slot="footer">
                <base-button type="white" @click="modals.error = false">Ok, Got it</base-button>
            </template>
        </modal>
        <!-- SUCCESS MODAL -->
        <modal :show.sync="modals.success">
            <template slot="header">
                <h5 class="modal-title" id="exampleModalLabel">Book Updated !</h5>
            </template>
            <div>
            <p>Hurray !, the book is updated and added to the published list.</p>
            </div>
            <template slot="footer">
                <base-button type="secondary" @click="navigateToList($event);">Continue to List</base-button>
            </template>
        </modal>
    </div>
</template>
<script>
import axios from 'axios';
  export default {
    name: 'addbook',
    data() {
      return {
        modals:{
            error:false,
            success:false
        },
        errors: [],
        genre_list:[],
        model: {
          book_name: '',
          book_genre: '',
          book_description: '',
          book_id:''
        }
      }
    },
    methods:{
        navigateToList(event){
            this.$router.push({ path: `/books/` });
            event.preventDefault();
        },
        submitForm(event){
           // event
           
            this.errors = [];
            if (!this.model.book_name) this.errors.push("Book title is required.");
            if (!this.model.book_genre) this.errors.push('Book genre is required.');
            if (!this.model.book_description) this.errors.push('Book description is required.');

            if (!this.errors.length) {
                
            let formData=new FormData();
            formData.append("_method","PUT");
            formData.append("book_name",this.model.book_name);
            formData.append("book_genre",this.model.book_genre);
            formData.append("book_description",this.model.book_description);
            formData.append("book_id",this.model.book_id);
            let baseApi = this.$baseAPI;
            axios.post(baseApi + 'book/update',formData,
                        {
                            headers: {
                                 'Content-Type': 'multipart/form-data',
                            }
                        }
                    ).then(function() {
                       this.modals.success=true;
                    }.bind(this)).catch(function() {
                        this.modals.error=true;
            }.bind(this));
            return true;
            }
            event.preventDefault();
        },
        loadBook(){

          let baseApi = this.$baseAPI;
          axios.get(baseApi + 'book/' + this.$route.params.id)
          .then(function(data){
              this.model.book_name = data.data.data.name;
              this.model.book_description = data.data.data.description;
              this.model.book_genre = data.data.genre.filter(item=>item.selected)[0].id;
              this.model.book_id = data.data.data.id;
              this.genre_list = data.data.genre;
              
            //this.modals.success = true;
          }.bind(this))
          .catch(function(){
            this.modals.error = true;
          }.bind(this));
        }
    },
    mounted(){
         this.loadBook();
    }
  };
</script>
<style></style>
