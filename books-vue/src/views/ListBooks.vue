<template>
    <div>
      <div class="row mt-2">
          <div class="col-xl-12 mb-5 mb-xl-0">
      <div class="card">
        <div class="card-header border-0">
          <div class="row align-items-center">
            <div class="col">
              <h3 class="mb-0">Books</h3>
            </div>
            <div class="col text-right">
              <a href="#!" @click="addBook($event)" class="btn btn-sm btn-primary">Add New</a>&nbsp;|&nbsp;<a href="#!" @click="logout($event)" class="btn btn-sm btn-primary">Logout</a>
            </div>
          </div>
        </div>

        <div class="table-responsive">
          <base-table thead-classes="thead-light"
                      :data="tableData">
            <template slot="columns">
              <th>Book <small>(Click to view)</small></th>
              <th>Author</th>
              <th>Genre</th>
              <th>Actions</th>
            </template>

            <template slot-scope="{row}">
              <th scope="row">
                <a href="#!" @click="bookDetails(row.id,$event)">{{row.name}}</a>
              </th>
              <td>
                {{row.author}}
              </td>
              <td>
                {{row.genre}}
              </td>
              <td>
                <a v-if="row.is_author" href="#!" @click="editBook(row.id,$event)" class="btn btn-sm btn-primary">Edit</a> &nbsp;
                <a v-if="row.is_author" href="#!" @click="deleteBook(row.id,row.name,$event)" class="btn btn-sm btn-danger">Delete</a>
              </td>
            </template>

          </base-table>
        </div>

            <modal :show.sync="modals.delete">
                <h6 slot="header" class="modal-title" id="modal-title-default">Delete Book</h6>

                <p>Are you sure want to delete the the following book ?</p>
                <p><b>{{activeBook.name}}</b></p>

                <template slot="footer">
                    <base-button type="danger" @click="deleteBookConfirm()">Delete</base-button>
                    <base-button type="primary" class="ml-auto" @click="modals.delete = false">No, Thankyou
                    </base-button>
                </template>
            </modal>
      </div>
      </div>
      </div>
    </div>
</template>
<script>
import axios from 'axios';
export default {
    name: 'list-books',
    methods:{
      logout(event){
        event.preventDefault();
          this.$auth.logout()
          .then(function(){
            this.$router.push({ path: `/login` });
          }.bind(this))
          .catch(function(){
             //Something went wrong
          }.bind(this));
      },
      addBook(event){
        this.$router.push({ path: `/addbook` });
        event.preventDefault();
      },
      editBook(id,event){
          event.preventDefault();
          this.$router.push({ path: `/editbook/${id}` });
      },
      bookDetails(id,event){
        event.preventDefault();
        this.$router.push({ path: `/book/${id}` });
      },
      deleteBook(id,name,event){
        event.preventDefault();
        this.activeBook.name = name;
        this.activeBook.id = id;
        this.modals.delete = true;
      },
      deleteBookConfirm(){
            let baseApi = this.$baseAPI;
            axios.post(baseApi + 'book/delete',{book_id:this.activeBook.id,_method:'DELETE'})
                    .then(function() {
                       alert("Book Deleted !");
                       this.modals.delete = false
                       this.listBooks();
                    }.bind(this))
                    .catch(function() {
                        alert('Something went wrong please try again !');
            }.bind(this));
      },
      listBooks(){
        let baseApi = this.$baseAPI;
        axios.get(baseApi + 'books')
                    .then(function(data) {
                       this.tableData = data.data.data;
                    }.bind(this))
                    .catch(function() {
                        alert("Something went wrong, please try again later !");
            }.bind(this));
    }
    },
    data() {
      return {
        modals:{
          delete:false
          },
        activeBook:{
          name:'',
          id:null
          },
        tableData: []
      }
    },
    mounted(){
      this.listBooks();
    }
  }
</script>
<style>
</style>
