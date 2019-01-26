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
                <div class="tab-content mt-4" id="myTabContent">
                  <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                    <form method="POST" @submit.prevent="pay()" id="customerForms">
                      <div class="row">
                        <div class="col-sm-8">
                          <div class="card">
                            <div class="card-body">
                              <h5 class="card-title">Bill Payment</h5>
                              <div class="form-group">
                                <label for="qry">Customer ID</label>
                                <input type="text" v-model="qry" name="qry" placeholder="Customer ID / Name / Email / Mobile" id="qry" class="form-control" @keyup="autoComplete">
                                <div class="panel-footer" v-if="results.length" style="position: absolute; z-index: 1000;background: #fff; border:1px solid #ddd;width:94.5%;">
                                  <p v-for="(result, index) in results" :key="result.id" @click="itemClicked(index)">@{{ result.name }}</p>
                                </div>
                              </div>
                              <div class="form-group">
                                <div class="row">
                                  <div class="col-sm-6">
                                    <label for="amount">Paid Amount</label>
                                    <input type="text" v-model="form.amount" name="amount" id="amount" class="form-control" placeholder="0.00">
                                  </div>
                                  <div class="col-sm-6">
                                    <label for="discount">Instant Discount</label>
                                    <input type="text" v-model="form.discount" name="discount" id="discount" class="form-control" placeholder="0.00">
                                  </div>
                                </div>
                              </div>
                              <div class="form-group">
                                <label for="reciept_no">Bill / Bhoucher No.</label>
                                <input type="text" v-model="form.reciept_no" name="reciept_no" id="reciept_no" class="form-control" placeholder="exp. 000123">
                              </div>
                              <div class="form-group">
                                <label for="remarks">Remarks</label>
                                <textarea type="text" v-model="form.remarks" name="remarks" id="remarks" class="form-control" placeholder="Remarks"></textarea>
                              </div>
                              <div class="form-group">
                                <button type="submit" class="btn btn-primary">Pay now</button>
                              </div>
                            </div>
                          </div>
                        </div>
                        <div v-show="infomode" class="col-sm-4">
                          <div class="card">
                            <div class="card-image">
                            <img src="/assets/img/avatar.png">
                            <span class="card-title">{{ info.name }}</span>
                          </div>
                            <div class="card-body">
                              <div class="customerInfo">
                                <table class="table">
                                  <tbody>
                                    <tr>
                                      <th>Name</th>
                                      <td>{{ info.name }}</td>
                                    </tr>
                                    <tr>
                                      <th>Email</th>
                                      <td>{{ info.email }}</td>
                                    </tr>
                                    <tr>
                                      <th>Mobile</th>
                                      <td>{{ info.cell_1 }}</td>
                                    </tr>
                                    <tr>
                                      <th>Customer ID</th>
                                      <td>{{ info.id }}</td>
                                    </tr>
                                    <tr>
                                      <th>Dues</th>
                                      <td>{{ info.dues }}</td>
                                    </tr>
                                  </tbody>
                                </table>
                                <input type="hidden" v-model="form.customer_id" name="customer_id" value="">
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </form>
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
              title : 'Bill payment',
              type : 'Active',
              keyword : '',
              infomode : false,
              info : {},
              qry : '',
              results : [],
              form: new Form({
                customer_id : '',
                username : '',
                amount : '',
                reciept_no : '',
                discount : '',
                remarks : ''
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
          autoComplete(){
            this.results = [];
            axios.get('/api/customer/search/suggestion?qry=' + this.qry)
            .then(( response ) => {
              this.results = response.data.data;
            })
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
          pay(){
            this.$Progress.start();
            this.form.post('/api/billing').then((response) => {
              if( response.data.success == true ) {
                //form is success
                $('#appModal').modal('hide');
                //show notification
                toast({
                  type: 'success',
                  title: 'Payment has been success.'
                });
                this.form.reset();
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
          itemClicked(index){
            this.info = this.results[index];
            this.infomode = true;
            this.results = [];
            this.qry = this.info.name;
            this.form.customer_id = this.info.id;
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
