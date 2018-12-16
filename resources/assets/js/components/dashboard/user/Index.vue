<template>
          <div class="row">
            <div class="col-sm-12">
              <div class="card card-table">
                <div class="card-header">Responsive Table
                  <div class="tools dropdown">
                    <a class="dropdown-toggle mr-5" href="#" role="button" data-toggle="dropdown">
                        <span class="icon mdi mdi-more-vert"></span>
                    </a>
                    <div class="dropdown-menu float-right" role="menu">
                        <a class="dropdown-item" href="#">Action</a>
                        <a class="dropdown-item" href="#">Another</a>
                        <a class="dropdown-item" href="#">Something</a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="#">Separated</a>
                    </div>
                    <a href="#" class="btn btn-success md-trigger" @click="createModal">
                        <span class="icon mdi mdi-plus text-white"></span> Add new
                    </a>
                  </div>
                </div>
                <div class="card-body">
                  <div class="table-responsive noSwipe">
                    <table class="table table-striped table-hover">
                      <thead>
                        <tr>
                          <th style="width:4%;">
                            <label class="custom-control custom-control-sm custom-checkbox">
                              <input class="custom-control-input" type="checkbox"><span class="custom-control-label"></span>
                            </label>
                          </th>
                          <th style="width:20%;">User</th>
                          <th style="width:17%;">Email</th>
                          <th style="width:15%;">Status</th>
                          <th style="width:10%;">Date</th>
                          <th style="width:10%;">Action</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr v-for="user in users.data" :key="user.id">
                          <td>
                            <label class="custom-control custom-control-sm custom-checkbox">
                              <input class="custom-control-input" type="checkbox"><span class="custom-control-label"></span>
                            </label>
                          </td>
                          <td class="user-avatar cell-detail user-info">
                            <img src="assets/img/avatar6.png" alt="Avatar">
                            <span>{{ user.name }}</span>
                            <span class="cell-detail-description">Developer</span>
                          </td>
                          <td class="cell-detail"> 
                            <span>{{ user.email | ucFirst }}</span>
                          </td>
                          <td class="milestone">
                            <span>{{ user.name }}</span>
                            <!-- <span class="completed">8 / 15</span><span class="version">v1.2.0</span>
                            <div class="progress">
                              <div class="progress-bar progress-bar-primary" style="width: 45%;"></div>
                            </div> -->
                          </td>
                          <td class="cell-detail">
                            <span>{{ user.created_at | humanDate }}</span>
                            <span class="cell-detail-description">{{ user.created_at | humanTime }}</span>
                          </td>
                          <td class="text-right">
                            <div class="btn-group btn-hspace">
                              <a href="#" class="btn btn-primary" @click="editModal(user)"><i class="icon mdi mdi-user text-white"></i></a>
                              <a href="#" @click="deleteUser(user.id)" class="btn btn-danger"><i class="icon mdi mdi-user-minus text-white"></i></a>
                            </div>
                          </td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>

            <div class="modal colored-header colored-header-success custom-width fade" id="form-success" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
              <div class="modal-dialog" role="document">  
                    <div class="modal-content">
                      <form @submit.prevent="editmode ? updateUser() : createUser">
                      <div class="modal-header modal-header-colored">
                        <h3 v-show="!editmode" class="modal-title">Create new user</h3>
                        <h3 v-show="editmode" class="modal-title">Update user's info</h3>
                        <button class="close modal-close" type="button" data-dismiss="modal" aria-hidden="true"><span class="mdi mdi-close"></span></button>
                      </div>
                      <div class="modal-body form">
                        <div class="form-group">
                          <input v-model="form.name" type="text" name="name" placeholder="Name" 
                            class="form-control" :class="{ 'is-invalid': form.errors.has('name') }">
                          <has-error :form="form" field="name"></has-error>
                        </div>

                        <div class="form-group">
                          <input v-model="form.email" type="email" name="email" placeholder="Email Address" 
                            class="form-control" :class="{ 'is-invalid': form.errors.has('email') }">
                          <has-error :form="form" field="email"></has-error>
                        </div>
                        <div class="row no-margin-y">
                          <div class="form-group col-sm-6">
                            <input v-model="form.password" class="form-control" type="password" placeholder="Password" :class="{ 'is-invalid': form.errors.has('password') }">
                          </div>
                          <div class="form-group col-sm-6">
                            <input v-model="form.password_confirm" class="form-control" type="password" placeholder="Password" :class="{ 'is-invalid': form.errors.has('password_confirm') }">
                          </div>
                        </div>
                        <div class="row no-margin-y">
                          <div class="form-group col-sm-4">
                            <input v-model="form.dob_day" class="form-control" type="text" placeholder="DD" :class="{ 'is-invalid': form.errors.has('dob_day') }">
                          </div>
                          <div class="form-group col-sm-4">
                            <input v-model="form.dob_month" class="form-control" type="text" placeholder="MM" :class="{ 'is-invalid': form.errors.has('dob_month') }">
                          </div>
                          <div class="form-group col-sm-4">
                            <input v-model="form.dob_year" class="form-control" type="text" placeholder="YYYY" :class="{ 'is-invalid': form.errors.has('dob_year') }">
                          </div>
                        </div>
                        <div class="row no-margin-y">
                          <div class="form-group col-sm-6">
                            <input v-model="form.gender" class="form-control" placeholder="Gender" :class="{ 'is-invalid': form.errors.has('gender') }">
                          </div>
                          <div class="form-group col-sm-6">
                            <input v-model="form.type" class="form-control" placeholder="YYYY" :class="{ 'is-invalid': form.errors.has('type') }">
                          </div>
                        </div>
                      </div>
                      <div class="modal-footer">
                        <button class="btn btn-secondary modal-close" type="button" data-dismiss="modal">Cancel</button>
                        <button v-show="!editmode" class="btn btn-success" type="submit">Create user</button>
                        <button v-show="editmode" class="btn btn-success" type="submit">Update user</button>
                      </div>
                      </form>
                    </div>
                  </div>
                </div>
          </div>
</template>
<script>
    export default {
        data(){
            return {
              editmode : false,
              users : {},
              form: new Form({
                name : '',
                email : '',
                password : '',
                password_confirm : '',
                gender : '',
                dob_day : '',
                dob_month : '',
                dob_year : ''
              })
            }
        },
        methods: {
          loadUsers(){
            axios.get('/api/user').then( ( { data } ) => { this.users = data } );
          },
          createModal(){
            this.editmode = false;
            $('#form-success').modal('show');
            this.form.reset();
          },
          editModal( user ){
            this.editmode = true;
            $('#form-success').modal('show');
            this.form.reset();
            this.form.fill( user );
          },
          createUser() {
            this.$Progress.start();
            this.form.post('/api/user').then(() => {
              //form is success
              $('#form-success').modal('hide');
              //show notification
              toast({
                type: 'success',
                title: 'User created'
              });

              Fire.$emit('AfterAction');
              //Finish ProgressBar
              this.$Progress.finish();
            }).catch(() => {
              //catching errors
              this.$Progress.fail();
            });
          },
          updateUser(id){

          },
          deleteUser(id) {
            swal({
              title: 'Are you sure?',
              text: "You won't be able to revert this!",
              type: 'warning',
              showCancelButton: true,
              confirmButtonColor: '#3085d6',
              cancelButtonColor: '#d33',
              confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
              if (result.value) {
                axios.delete('/api/user/' + id)
                .then(() => {
                  swal(
                    'Deleted!',
                    'User has been deleted.',
                    'success'
                  )
                }).catch(() => {})
              }
            });
          }
        },
        mounted() {
          this.loadUsers();
          //fire custom event
          Fire.$on('AfterAction', () => { this.loadUsers() } );
          //load data in setInterval Method
          // setInterval(() => this.loadUsers(), 5000 );
        }

        $('.tabbed-menu').on("click", function(e){
          alert('ok');
        });
    }
</script>
