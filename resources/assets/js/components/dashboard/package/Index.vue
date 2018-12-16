<template>
      <section id="contentSection">
        <div class="container-fluid">
          <div class="row">
            <div class="col-sm-12 text-right">
              <h2 class="page-title">{{ title }}</h2>
              <ul class="nav nav-tabs menuTab" id="myTab" role="tablist">
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
                          <th>Name</th>
                          <th>Price</th>
                          <th>Type</th>
                          <th>Bandwidth</th>
                          <th>Customers</th>
                          <th>Action</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr v-for="packege in packages.data" :key="packege.id">
                          <th scope="row">{{ packege.id }}</th>
                          <td>{{ packege.name }}</td>
                          <td>{{ packege.price }}</td>
                          <td>{{ packege.type }}</td>
                          <td>{{ packege.bandwidth }}</td>
                          <td>{{ packege.customers_count }}</td>
                          <td>
                            <router-link to="/dashboard/package/view/:key" class="btn btn-primary btn-sm"><span class="fa fa-eye"></span></router-link>
                            <button @click="editModal( packege )" class="btn btn-success btn-sm"><span class="fa fa-edit"></span></button>
                            <button @click="deletePackage( packege.id )" class="btn btn-danger btn-sm"><span class="fa fa-times"></span></button>
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
                        <h3 v-show="!editmode" class="modal-title">Create new package</h3>
                        <h3 v-show="editmode" class="modal-title">Update package</h3>
                        <button class="close modal-close" type="button" data-dismiss="modal" aria-hidden="true"><span class="mdi mdi-close"></span></button>
                      </div>
                      <div class="modal-body form">
                        <div class="form-group">
                          <input v-model="form.name" type="text" name="name" placeholder="Package Name" 
                            class="form-control" :class="{ 'is-invalid': form.errors.has('name') }" required>
                          <has-error :form="form" field="name"></has-error>
                        </div>

                        <div class="form-group">
                          <input v-model="form.bandwidth" type="text" name="bandwidth" placeholder="Bandwidth" 
                            class="form-control" :class="{ 'is-invalid': form.errors.has('bandwidth') }">
                          <has-error :form="form" field="bandwidth"></has-error>
                        </div>

                        <div class="form-group">
                          <input v-model="form.speed" type="text" name="speed" placeholder="Browsing / Download Speed" 
                            class="form-control" :class="{ 'is-invalid': form.errors.has('speed') }">
                          <has-error :form="form" field="speed"></has-error>
                        </div>
                        <div class="form-group">
                            <select v-model="form.type" class="form-control" :class="{ 'is-invalid': form.errors.has('type') }" required>
                              <option value="" selected="selected">Select Type</option>
                              <option>Home</option>
                              <option>Office</option>
                              <option>Store</option>
                              <option>Bank/Bima</option>
                              <option>Booth</option>
                            </select>
                        </div>

                        <div class="form-group">
                          <input v-model="form.price" type="text" name="price" placeholder="Price" 
                            class="form-control" :class="{ 'is-invalid': form.errors.has('price') }">
                          <has-error :form="form" field="price"></has-error>
                        </div>
                      </div>
                      <div class="modal-footer">
                        <button class="btn btn-secondary modal-close" type="button" data-dismiss="modal">Cancel</button>
                        <button v-show="!editmode" class="btn btn-success" type="submit">Save package</button>
                        <button v-show="editmode" class="btn btn-success" type="submit">Update package</button>
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
              title : 'All Packages',
              editmode : false,
              packages : {},
              form: new Form({
                id : '',
                name : '',
                price : '',
                type : '',
                bandwidth : '',
                speed : ''
              })
            }
        },
        methods: {
          load(){
            axios.get('/api/package').then(( data ) => { this.packages = data.data } );
          },
          createModal(){
            this.editmode = false;
            this.form.reset();
            $('#appModal').modal('show');
          },
          editModal( packege ){
            this.editmode = true;
            this.form.reset();
            $('#appModal').modal('show');
            this.form.fill( packege );
          },
          create(){
            this.$Progress.start();
            this.form.post('/api/package').then((response) => {
              if( response.data.success == true ) {
                //form is success
                $('#appModal').modal('hide');
                //show notification
                toast({
                  type: 'success',
                  title: 'Package has been created.'
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
            this.form.put('/api/package/' + this.form.id).then(() => {
              //form is success
              $('#appModal').modal('hide');
              //show notification
              toast({
                type: 'success',
                title: 'Package information updated.'
              });

              Fire.$emit('AfterAction');
              //Finish ProgressBar
              this.$Progress.finish();
            }).catch(() => {
              //catching errors
              this.$Progress.fail();
            });
          },
          deletePackage( id ){
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
                axios.delete('/api/package/' + id)
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