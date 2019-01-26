<template>
      <section id="contentSection">
        <div class="container-fluid">
          <div class="row">
            <div class="col-sm-12 text-right">
              <h2 class="page-title">{{ title }}</h2>
              <ul class="nav nav-tabs menuTab" id="myTab" role="tablist">
                <li class="nav-item">
                  <router-link class="nav-link tabbed-menu" to="/dashboard/billing" role="tab">Summary</router-link>
                </li>
                <li class="nav-item">
                  <router-link class="nav-link tabbed-menu" to="/dashboard/billing/info">Payment Info</router-link>
                </li>
                <li class="nav-item">
                  <router-link class="nav-link tabbed-menu active" to="/dashboard/billing/pay">Bill Pay</router-link>
                </li>
                <li class="nav-item">
                  <router-link class="nav-link tabbed-menu" to="/dashboard/billing/due/list">Due List</router-link>
                </li>
                <li class="nav-item">
                  <router-link class="nav-link tabbed-menu" to="/dashboard/billing/payment/list"><span class="icon icon-user"></span> Payment Lists</router-link>
                </li>
              </ul>
            </div>
            <div class="col-lg-12">
              <div class="table-responsive">
                <div class="tab-content" id="myTabContent">
                  <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                    <form method="POST" id="customerForms">
                      <div class="row">
                        <div class="col-md-3">
                          <div class="dropdown">
                            <button class="btn btn-outline-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> Bulk Action </button>
                              <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                <a class="dropdown-item" href="#"><i class="fa fa-check"></i> Active</a>
                                <a class="dropdown-item" href="#"><i class="fa fa-ban"></i> Suspend</a>
                                <a class="dropdown-item" href="#"><i class="fa fa-times"></i> Delete</a>
                              </div>
                          </div>
                        </div>
                        <div class="col-md-9 pull-right">
                          <div class="input-group input-group mb-2">
                            <input v-model="keyword" type="text" class="form-control" placeholder="Search here..." name="search">
                            <span class="input-group-append">
                              <button class="btn btn-sm btn-primary" type="button"><i class="fa fa-search"></i> Search</button>
                            </span>
                            <div class="input-group-btn dropdown">
                              <button class="btn btn-outline-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> Sort by </button>
                              <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                <a class="dropdown-item" href="#">Most Dues</a>
                                <a class="dropdown-item" href="#">Less Dues</a>
                                <a class="dropdown-item" href="#">Newest on Top</a>
                                <a class="dropdown-item" href="#">Oldest on Top</a>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                      <table class="table table-striped table-bordered table-sm">
                        <thead>
                          <tr>
                            <th>
                              <input type="checkbox" name="" id="checkedall">
                            </th>
                            <th>#</th>
                            <th sortable="true">Customer Name</th>
                            <th>Customer Type</th>
                            <th>Location</th>
                            <th>Packege</th>
                            <th>Current Due</th>
                            <th>Status</th>
                            <th class="text-center" style="text-align:center; width:120px;">Action</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr v-for="customer in customers" :key="customer.id">
                            <th scope="row">
                              <input v-model="ids" type="checkbox" name="ids[]" :value="customer.id">
                            </th>
                            <th scope="row">{{ customer.id }}</th>
                            <td>{{ customer.name }}</td>
                            <td>{{ customer.type }}</td>
                            <td>{{ customer.address }}</td>
                            <td>{{ customer.package_id }} ({{ customer.package_id }})</td>
                            <td>{{ customer.package_id }}</td>
                            <td>{{ customer.status }}</td>
                            <td>
                              <router-link :to="'/dashboard/customer/show/' + customer.id" class="btn btn-default btn-sm"><span class="fa fa-eye"></span></router-link>
                              <button class="btn btn-primary btn-sm" @click=""nm                                                                                                              bbbbbbbbbbbbbb ><i class="fa fa-edit"></i></button>
                              <button class="btn btn-danger btn-sm"><i class="fa fa-times"></i></button>
                            </td>
                          </tr>
                        <tr v-if="customers.length === 0">
                          <td colspan="9" class="alert alert-info text-center">
                            <h4>No customers found</h4>
                          </td>
                        </tr>
                        </tbody>
                      </table>
                    </form>
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
                        <button class="close modal-close" type="button" data-dismiss="modal" aria-hidden="true"><span class="fa fa-times"></span></button>
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
                            <select v-model="form.type" name="type" class="form-control" required>
                              <option value="" selected="selected">Select Type</option>
                              <option value="home">Home</option>
                              <option value="office">Office</option>
                              <option value="store">Store</option>
                              <option value="bank">Bank / Bima / NGO</option>
                              <option value="atm_booth">ATM Booth</option>
                            </select>
                          </div>
                          <div class="form-group col-sm-6">
                            <input v-model="form.mikrotik_id" class="form-control" placeholder="Mikrotik ID" :class="{ 'is-invalid': form.errors.has('mikrotik_id') }">
                          </div>
                        </div>
                        <div class="row no-margin-y">
                          <div class="form-group col-sm-6">
                            <input v-model="form.remote_ip" class="form-control" placeholder="Remote IP" :class="{ 'is-invalid': form.errors.has('remote_ip') }">
                          </div>
                          <div class="form-group col-sm-6">
                            <input v-model="form.remote_mac" class="form-control" placeholder="Remote Mac" :class="{ 'is-invalid': form.errors.has('remote_mac') }">
                          </div>
                        </div>
                        <div class="row no-margin-y">
                          <div class="form-group col-sm-6">
                            <select v-model="form.zone_id" name="zone_id" @change="loadChildZone" class="form-control" required>
                              <option value="">Select Zone</option>
                              <option v-for="zone in zones" :key="zone.id" :value="zone.id">{{ zone.name }}</option>
                            </select>
                          </div>
                          <div class="form-group col-sm-6">
                            <select v-model="area_id" name="area_id" class="form-control">
                              <option value="">Select Area</option>
                              <option v-for="area in areas" :key="area.id" :value="area.id">{{ area.name }}</option>
                            </select>
                          </div>
                        </div>
                        <div class="form-group">
                          <textarea v-model="form.address" name="address" placeholder="Address" 
                            class="form-control" :class="{ 'is-invalid': form.errors.has('address') }"></textarea>
                          <has-error :form="form" field="email"></has-error>
                        </div>
                        <div class="form-group">
                          <select v-model="form.package_id" name="packege" placeholder="Billing Package" 
                            class="form-control" :class="{ 'is-invalid': form.errors.has('packege') }" required>
                            <option value="">Select Package</option>
                            <option v-for="package in packages" :key="package.id" :value="package.id">{{ package.name }} {{ package.price }}</option>
                          </select>
                          <has-error :form="form" field="packege"></has-error>
                        </div>
                        <div class="row no-margin-y">
                          <div class="form-group col-sm-6">
                            <input v-model="form.monthly_discount" class="form-control" type="text" placeholder="Monthly Discount" :class="{ 'is-invalid': form.errors.has('monthly_discount') }">
                          </div>
                          <div class="form-group col-sm-6">
                            <input v-model="form.cable_cost" class="form-control" type="text" placeholder="Cable Cost" :class="{ 'is-invalid': form.errors.has('cable_cost') }">
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
      </section>
    </template>
<script>
    export default {
        data(){
            return {
              title : 'Active Customers',
              type : 'Active',
              keyword : '',
              editmode : false,
              customers : {},
              packages : {},
              zones : {},
              areas : {},
              ids : [],
              form: new Form({
                id : '',
                name : '',
                fathers_name : '',
                mothers_name : '',
                username : '',
                email : '',
                cell_1 : '',
                cell_2 : '',
                package_id : '',
                type : '',
                zone_id : '',
                area_id : '',
                mikrotik_id : '',
                remote_ip : '',
                remote_mac : '',

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
              this.zones = data.data.areas;
            } )
            .catch(() => {});
          },
          fetchData( type ){
            this.type = type;
            this.title = type + ' Customers';
            this.load();
          },
          loadChildZone(){
            axios.get('/api/area/childs/' + this.form.zone_id )
            .then((data) => {
              this.areas = data.data.childs;
            })
            .catch(() => {});
          },
          createModal(){
            this.editmode = false;
            $('#appModal').modal('show');
          },
          editModal( customer ){
            this.editmode = true;
            this.form.reset();
            $('#appModal').modal('show');
            this.form.fill( customer );
          },
          create(){
            this.$Progress.start();
            this.form.post('/api/customer').then((response) => {
              if( response.data.success == true ) {
                //form is success
                $('#appModal').modal('hide');
                //show notification
                toast({
                  type: 'success',
                  title: 'Customer has been created.'
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
            this.form.put('/api/customer/' + this.form.id).then(() => {
              //form is success
              $('#appModal').modal('hide');
              //show notification
              toast({
                type: 'success',
                title: 'Customer information updated.'
              });

              Fire.$emit('AfterAction');
              //Finish ProgressBar
              this.$Progress.finish();
            }).catch(() => {
              //catching errors
              this.$Progress.fail();
            });
          }
        },
        mounted() {
          this.load();
          $('.tabbed-menu').on("click", function(e){
            //make current menu active
              var parent = $(this).parents('.nav-tabs');
              $(parent).find('.tabbed-menu').removeClass('active');
              $(this).addClass('active');
          });
        }
    }
</script>
