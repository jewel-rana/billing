<template>
      <section id="contentSection">
        <div class="container-fluid">
          <div class="row">
            <div class="col-sm-12 text-right">
              <h2 class="page-title">{{ title }}</h2>
              <ul class="nav nav-tabs menuTab" id="myTab" role="tablist">
                <!-- <li class="nav-item searchbox">
                  <div class="input-group">
                    <input type="text" class="form-control" placeholder="Search here..." name="search">
                    <span class="input-group-append">
                      <button class="btn btn-sm btn-primary"><i class="fa fa-search"></i> Search</button>
                    </span>
                  </div>
                </li> -->
                <li class="nav-item">
                  <a class="nav-link active" @click.prevent="fetchData('All')" href="#" role="tab">All</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" @click.prevent="createModal" href="#"><span class="icon icon-user"></span> Add new</a>
                </li>
              </ul>
            </div>
            <div class="col-lg-12">
          <div class="table-responsive">
            <div class="tab-content" id="myTabContent">
              <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                    <table class="table table-striped table-bordered table-sm">
                      <thead>
                        <tr>
                          <th>#</th>
                          <th>Area Name</th>
                          <th>Area Code</th>
                          <th>Childs</th>
                          <th>Customers</th>
                          <th>Action</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr v-for="area in areas.data" :key="area.id">
                          <th scope="row">{{ area.id }}</th>
                          <td>{{ area.name }}</td>
                          <td>{{ area.code }}</td>
                          <td>{{ area.childs_count }}</td>
                          <td>{{ area.customers_count }}</td>
                          <td>
                            <router-link to="/dashboard/area/view/:key" class="btn btn-primary btn-sm"><span class="fa fa-eye"></span></router-link>
                            <button @click="editModal( area )" class="btn btn-success btn-sm"><span class="fa fa-edit"></span></button>
                            <button @click="deleteArea( area.id )" class="btn btn-danger btn-sm"><span class="fa fa-times"></span></button>
                          </td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>


            <div class="modal colored-header colored-header-success custom-width fade" id="appModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
              <div class="modal-dialog" role="document">  
                    <div class="modal-content">
                      <form @submit.prevent="editmode ? update() : create()">
                      <div class="modal-header modal-header-colored">
                        <h3 v-show="!editmode" class="modal-title">Create new area</h3>
                        <h3 v-show="editmode" class="modal-title">Update area</h3>
                        <button class="close modal-close" type="button" data-dismiss="modal" aria-hidden="true"><span class="mdi mdi-close"></span></button>
                      </div>
                      <div class="modal-body form">
                        <div class="form-group">
                          <input v-model="form.name" type="text" name="name" placeholder="Area Name" 
                            class="form-control" :class="{ 'is-invalid': form.errors.has('name') }" required>
                          <has-error :form="form" field="name"></has-error>
                        </div>

                        <div class="form-group">
                          <input v-model="form.code" type="text" name="code" placeholder="Area Code" 
                            class="form-control" :class="{ 'is-invalid': form.errors.has('code') }">
                          <has-error :form="form" field="code"></has-error>
                        </div>
                        <div class="form-group">
                            <select v-model="form.parent" class="form-control" :class="{ 'is-invalid': form.errors.has('parent') }">
                              <option value="0" selected="selected">Select Parent</option>
                              <option></option>
                            </select>
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
      </section>
    </template>
<script>
    export default {
        data(){
            return {
              title : 'All Areas',
              editmode : false,
              areas : {},
              form: new Form({
                id : '',
                name : '',
                code : '',
                parent : 0
              })
            }
        },
        methods: {
          load(){
            axios.get('/api/area').then(( data ) => { this.areas = data.data } );
          },
          fetchData( type ){
            this.load();
          },
          createModal(){
            this.editmode = false;
            this.form.reset();
            $('#appModal').modal('show');
          },
          editModal( area ){
            this.editmode = true;
            this.form.reset();
            $('#appModal').modal('show');
            this.form.fill( area );
          },
          create(){
            this.$Progress.start();
            this.form.post('/api/area').then((response) => {
              if( response.data.success == true ) {
                //form is success
                $('#appModal').modal('hide');
                //show notification
                toast({
                  type: 'success',
                  title: 'User created'
                });

                Fire.$emit('AfterAction');
              } else {
                toast({
                  type: 'error',
                  title: response.data.msg
                });
              }
              //Finish ProgressBar
              this.$Progress.finish();
            }).catch((data) => {
              //catching errors
              console.log( data );
            });
          },
          update(){
            this.$Progress.start();
            this.form.put('/api/area/' + this.form.id).then(() => {
              //form is success
              $('#appModal').modal('hide');
              //show notification
              toast({
                type: 'success',
                title: 'Area information updated.'
              });

              Fire.$emit('AfterAction');
              //Finish ProgressBar
              this.$Progress.finish();
            }).catch(() => {
              //catching errors
              this.$Progress.fail();
            });
          },
          deleteArea( id ){
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
                axios.delete('/api/area/' + id)
                .then(() => {
                  Fire.$emit('AfterAction');
                  swal(
                    'Deleted!',
                    'Area has been deleted.',
                    'success'
                  )
                }).catch(() => {})
              }
            });
          }
        },
        mounted() {
          this.load();
          Fire.$on('AfterAction', () => { this.load() });
        }
    }
</script>
