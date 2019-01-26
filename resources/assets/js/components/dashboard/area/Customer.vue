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
                  <router-link to="/dashboard/area" class="nav-link" role="tab">Back</router-link>
                </li>
                <li class="nav-item">
                  <a class="nav-link" @click.prevent="createModal" href="#"><span class="icon icon-user"></span> Bill Pay</a>
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
                          <th>Customer Name</th>
                          <th>Package</th>
                          <th>Dues</th>
                          <th>Action</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr v-for="customer in customers" :key="customer.id">
                          <th scope="row">{{ customer.id }}</th>
                          <td>{{ customer.name }}</td>
                          <td>{{ customer.package }}</td>
                          <td>{{ customer.dues }}</td>
                          <td>
                            <router-link :to="'/dashboard/customer/info/' + customer.id" class="btn btn-primary btn-sm"><span class="fa fa-eye"></span></router-link>
                            <button @click="deleteCustomer( customer.id )" class="btn btn-danger btn-sm"><span class="fa fa-times"></span></button>
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
                      <form @submit.prevent="payment()">
                      <div class="modal-header modal-header-colored">
                        <h3 class="modal-title">Bill Pay</h3>
                        <button class="close modal-close" type="button" data-dismiss="modal" aria-hidden="true"><span class="mdi mdi-close"></span></button>
                      </div>
                      <div class="modal-body form">

                        <div class="form-group">
                          <input v-model="form.amount" type="text" name="amount" placeholder="Amount" 
                            class="form-control" :class="{ 'is-invalid': form.errors.has('amount') }">
                          <has-error :form="form" field="amount"></has-error>
                        </div>

                        <div class="form-group">
                          <input v-model="form.discount" type="text" name="discount" placeholder="Discount" 
                            class="form-control" :class="{ 'is-invalid': form.errors.has('discount') }">
                          <has-error :form="form" field="discount"></has-error>
                        </div>
                        <div class="form-group">
                          <textarea v-model="form.remarks" class="form-control" :class="{ 'is-invalid': form.errors.has('remarks')}" placeholder="Remarks"></textarea>
                        </div>
                      </div>
                      <div class="modal-footer">
                        <button class="btn btn-secondary modal-close" type="button" data-dismiss="modal">Cancel</button>
                        <button class="btn btn-success" type="submit">Pay now</button>
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
              title : 'Customers in Area',
              info : {},
              customers : {},
              id : this.$route.id,
              form: new Form({
                id : '',
                amount : '',
                discount : '',
                reciept_no : '',
                remarks : ''
              })
            }
        },
        methods: {
          load(){
            axios.get('/api/area/customer/1?area=' + this.$route.params.id ).then(( data ) => {
              this.customers = data.data.customers 
              this.info = data.data.info;
              this.title = data.info.name;
            } );
          },
          fetchData( type ){
            this.load();
          },
          billPay(){
            this.editmode = false;
            this.form.reset();
            $('#appModal').modal('show');
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
                  title: 'Area created'
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
          deleteCustomer( id ){
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
                axios.delete('/api/customer/' + id)
                .then(() => {
                  Fire.$emit('AfterAction');
                  swal(
                    'Deleted!',
                    'Customer has been deleted.',
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
