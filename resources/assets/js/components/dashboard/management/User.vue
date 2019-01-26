<template>
      <section id="contentSection">
        <div class="container-fluid">
          <div class="row">
            <div class="col-sm-12 text-right">
              <h2 class="page-title">{{ title }}</h2>
              <ul class="nav nav-tabs menuTab" id="myTab" role="tablist">
                <li class="nav-item">
                  <router-link class="nav-link tabbed-menu active" to="/dashboard/manage/users" role="tab">User</router-link>
                </li>
                <li class="nav-item">
                  <router-link class="nav-link tabbed-menu" to="/dashboard/manage/admins">Admin</router-link>
                </li>
                <li class="nav-item">
                  <router-link class="nav-link tabbed-menu" to="/dashboard/manage/roles">Roles</router-link>
                </li>
                <li class="nav-item">
                  <router-link class="nav-link tabbed-menu" to="/dashboard/manage/permissions">Permissions</router-link>
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
                            <th sortable="true">Name</th>
                            <th>Email</th>
                            <th>type</th>
                            <th>Status</th>
                            <th class="text-center" style="text-align:center; width:120px;">Action</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr v-for="user in users" :key="user.id">
                            <th scope="row">
                              <input v-model="ids" type="checkbox" name="ids[]" :value="user.id">
                            </th>
                            <td>{{ user.name }}</td>
                            <td>{{ user.email }}</td>
                            <td>{{ user.type }}</td>
                            <td>{{ user.type }}</td>
                            <td>
                              <router-link :to="'/dashboard/user/show/' + user.id" class="btn btn-default btn-sm"><span class="fa fa-eye"></span></router-link>
                              <button class="btn btn-primary btn-sm" @click=""><i class="fa fa-edit"></i></button>
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
      </section>
    </template>
<script>
    export default {
        data(){
            return {
              title : 'Users',
              type : 'user',
              keyword : '',
              editmode : false,
              customers : {},
              users : {},
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
            axios.get('/api/user/?type=' + this.type )
            .then(( data ) => {
              console.log(data)
              this.users = data.data.data;
            } )
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

