<template>
      <section id="contentSection">
        <div class="container-fluid">
          <div class="row">
            <div class="col-sm-12 text-right">
              <h2 class="page-title">{{ title }}</h2>
              <ul class="nav nav-tabs menuTab" id="myTab" role="tablist">
                <li class="nav-item">
                  <router-link class="nav-link tabbed-menu" to="/dashboard/customer" role="tab"> <i class="fa fa-chevron-left"></i> back to Customers</router-link>
                </li>
                <li class="nav-item">
                  <a class="nav-link tabbed-menu active" @click.prevent="fetchData('Pending')" href="#">Pending</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link tabbed-menu" @click.prevent="fetchData('Accepted')" href="#">Accepted</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link tabbed-menu" @click.prevent="fetchData('Suspended')" href="#">Suspended</a>
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
                        </div>
                        <div class="col-md-9 pull-right">
                        </div>
                      </div>
                      <table class="table table-striped table-bordered table-sm">
                        <thead>
                          <tr>
                            <th>#</th>
                            <th sortable="true">Customer Name</th>
                            <th>Request Type</th>
                            <th>Package</th>
                            <th>Status</th>
                            <th class="text-center" style="text-align:center; width:120px;">Action</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr v-for="item in requests" :key="item.id">
                            <th scope="row">{{ item.id }}</th>
                            <td>{{ item.customer_name }}</td>
                            <td>{{ item.type }}</td>
                            <td>{{ item.customer_package }} ({{ item.package_price }} Tk)</td>
                            <td>{{ item.status_label }}</td>
                            <td>
                              <button class="btn btn-primary btn-sm" @click.prevent="requestView(item.id, 'cancel')"><i class="fa fa-eye"></i></button>
                              <button v-show="item.status === 0" class="btn btn-success btn-sm" @click.prevent="requestAction(item.id, 'accept')"><i class="fa fa-check"></i></button>
                              <button v-show="item.status === 0" class="btn btn-danger btn-sm" @click.prevent="requestAction(item.id, 'cancel')"><i class="fa fa-times"></i></button>
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
              title : 'Pending Requests',
              type : 'Pending',
              keyword : '',
              editmode : false,
              requests : {}
            }
        },
        methods: {
          load(){
            axios.get('/api/requests/?type=' + this.type )
            .then(( data ) => {
              console.log(data)
              this.requests = data.data.requests;
            } )
            .catch(() => {});
          },
          fetchData( type ){
            this.type = type;
            this.title = type + ' Requests';
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
          requestAction( requestID, type ) {
            swal({
              title: 'Are you sure to ' + type + ' this request?',
              text: "Action will be taken as per your confirmation.",
              type: 'warning',
              showCancelButton: true,
              confirmButtonColor: '#3085d6',
              cancelButtonColor: '#d33',
              confirmButtonText: 'Yes, ' + type
            }).then((result) => {
              if (result.value) {
                axios.get('/api/requests/action/' + requestID + '?type=' + type)
                .then(( response ) => {
                  console.log( response );
                  Fire.$emit('AfterAction');

                  if( response.data.success == true ) {
                    swal(
                      'Success!',
                      'Your action has been successfully proccesed.',
                      'success'
                    )
                  } else {
                    swal(
                      'Failed!',
                      'Something went wrong while taking action.',
                      'error'
                    )
                  }
                }).catch(() => {})
              }
            });
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
