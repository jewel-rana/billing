<template>
      <section id="contentSection">
        <div class="container-fluid">
          <div class="row">
            <div class="col-sm-12 text-right">
              <h2 class="page-title">{{ title }}</h2>
              <ul class="nav nav-tabs menuTab" id="myTab" role="tablist">
                <li class="nav-item">
                  <a class="nav-link tabbed-menu" @click.prevent="fetchData('All')" href="#" role="tab">All</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link tabbed-menu active" @click.prevent="fetchData('Active')" href="#">Active</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link tabbed-menu" @click.prevent="fetchData('Inactive')" href="#">Inactive</a>
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
                <div class="pull-right">
                  <div class="input-group input-group-sm mb-2">
                    <input type="text" class="form-control" placeholder="Search here..." name="search">
                    <span class="input-group-append">
                      <button class="btn btn-sm btn-primary"><i class="fa fa-search"></i> Search</button>
                    </span>
                    <div class="input-group-btn">
                      <button class="btn btn-outline-secondary btn-sm bg-white"> Filter by type <i class="fa fa-angle-down"></i></button>
                    </div>
                  </div>
                </div>
                    <table class="table table-striped table-bordered table-sm">
                      <thead>
                        <tr>
                          <th>#</th>
                          <th>Customer Name</th>
                          <th>Customer Type</th>
                          <th>Location</th>
                          <th>Packege</th>
                          <th>Current Due</th>
                          <th>Status</th>
                          <th>Action</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr v-for="customer in customers" :key="customer.id">
                          <th scope="row">{{ customer.id }}</th>
                          <td>{{ customer.name }}</td>
                          <td>{{ customer.type }}</td>
                          <td>{{ customer.address }}</td>
                          <td>{{ customer.package_id }} ({{ customer.package_id }})</td>
                          <td>{{ customer.package_id }}</td>
                          <td>{{ customer.status }}</td>
                          <td>
                            <a href="#" class="btn btn-default btn-sm"><span class="fa fa-eye"></span></a>
                            <button class="btn btn-primary btn-sm"><i class="fa fa-edit"></i></button>
                            <button class="btn btn-danger btn-sm"><i class="fa fa-times"></i></button>
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
                        <div class="row no-margin-y">
                          <div class="form-group col-sm-6">
                            <input v-model="form.fathers_name" class="form-control" type="text" placeholder="Fathers Name" :class="{ 'is-invalid': form.errors.has('fathers_name') }">
                          </div>
                          <div class="form-group col-sm-6">
                            <input v-model="form.mothers_name" class="form-control" type="text" placeholder="Mothers Name" :class="{ 'is-invalid': form.errors.has('mothers_name') }">
                          </div>
                        </div>
                        <div class="row no-margin-y">
                          <div class="form-group col-sm-6">
                            <input v-model="form.username" class="form-control" type="text" placeholder="Username" :class="{ 'is-invalid': form.errors.has('username') }">
                          </div>
                          <div class="form-group col-sm-6">
                            <input v-model="form.email" class="form-control" type="text" placeholder="Email" :class="{ 'is-invalid': form.errors.has('email') }">
                          </div>
                        </div>
                        <div class="row no-margin-y">
                          <div class="form-group col-sm-6">
                            <input v-model="form.cell_1" class="form-control" placeholder="Contact number (Home)" :class="{ 'is-invalid': form.errors.has('cell_1') }">
                          </div>
                          <div class="form-group col-sm-6">
                            <input v-model="form.cell_2" class="form-control" placeholder="Contact Number (Office)" :class="{ 'is-invalid': form.errors.has('cell_2') }">
                          </div>
                        </div>
                        <div class="row no-margin-y">
                          <div class="form-group col-sm-6">
                            <input v-model="form.mikrotik_id" class="form-control" placeholder="Mikrotik ID" :class="{ 'is-invalid': form.errors.has('mikrotik_id') }">
                          </div>
                          <div class="form-group col-sm-6">
                            
                          </div>
                        </div>
                        <div class="row no-margin-y">
                          <div class="form-group col-sm-6">
                            <input v-model="form.cell_1" class="form-control" placeholder="Remote IP" :class="{ 'is-invalid': form.errors.has('cell_1') }">
                          </div>
                          <div class="form-group col-sm-6">
                            <input v-model="form.cell_2" class="form-control" placeholder="Remote Mac" :class="{ 'is-invalid': form.errors.has('cell_2') }">
                          </div>
                        </div>
                        <div class="row no-margin-y">
                          <div class="form-group col-sm-6">
                            <input v-model="form.cell_1" class="form-control" placeholder="Contact number (Home)" :class="{ 'is-invalid': form.errors.has('cell_1') }">
                          </div>
                          <div class="form-group col-sm-6">
                            <input v-model="form.cell_2" class="form-control" placeholder="Contact Number (Office)" :class="{ 'is-invalid': form.errors.has('cell_2') }">
                          </div>
                        </div>
                        <div class="form-group">
                          <textarea v-model="form.address" name="address" placeholder="Address" 
                            class="form-control" :class="{ 'is-invalid': form.errors.has('address') }"></textarea>
                          <has-error :form="form" field="email"></has-error>
                        </div>
                        <div class="form-group">
                          <select v-model="form.packege" name="packege" placeholder="Billing Package" 
                            class="form-control" :class="{ 'is-invalid': form.errors.has('packege') }" required v-for="package in packages" :key="package.id">
                            <option v-bind="package.id" value="">{{ package.name }} {{ package.price }}</option>
                          </select>
                          <has-error :form="form" field="packege"></has-error>
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
              title : 'Active Customers',
              type : 'Active',
              editmode : false,
              customers : {},
              packages : {},
              form: new Form({
                name : '',
                code : '',
                parent : '',
                package : '',
              })
            }
        },
        methods: {
          load(){
            axios.get('/api/customer/?type=' + this.type )
            .then(( data ) => {
              console.log(data)
              this.customers = data.data.customers;
              this.packages = data.data.packages;
            } )
            .catch(() => {});
          },
          fetchData( type ){
            this.type = type;
            this.title = type + ' Customers';
            this.load();
          },
          createModal(){
            this.editmode = true;
            $('#appModal').modal('show');
          },
          editModal(){
            this.editmode = true;
          },
          create(){
            console.log( this.form );
          },
          update(){
            console.log(this.form);
          }
        },
        mounted() {
          this.load();
            console.log('Component mounted.')


        $('.tabbed-menu').on("click", function(e){
          //make current menu active
            var parent = $(this).parents('.nav-tabs');
            $(parent).find('.tabbed-menu').removeClass('active');
            $(this).addClass('active');
        });
        }
    }
</script>
