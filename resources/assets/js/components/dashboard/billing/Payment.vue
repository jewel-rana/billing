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
                  <router-link class="nav-link tabbed-menu" to="/dashboard/billing/pay">Bill Pay</router-link>
                </li>
                <li class="nav-item">
                  <router-link class="nav-link tabbed-menu" to="/dashboard/billing/due/list">Due List</router-link>
                </li>
                <li class="nav-item">
                  <router-link class="nav-link tabbed-menu active" to="/dashboard/billing/payment/list"><span class="icon icon-user"></span> Payment Lists</router-link>
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
                            <th>Customer ID</th>
                            <th>Location</th>
                            <th>Packege</th>
                            <th>Current Due</th>
                            <th>Paid Amount</th>
                            <th class="text-center" style="text-align:center; width:120px;">Action</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr v-for="payment in payments" :key="payment.id">
                            <th scope="row">
                              <input v-model="ids" type="checkbox" name="ids[]" :value="payment.id">
                            </th>
                            <th scope="row">{{ payment.id }}</th>
                            <td>{{ payment.customer.name }}</td>
                            <td>{{ payment.customer.username }}</td>
                            <td>{{ payment.address }}</td>
                            <td>{{ payment.package.name }} ({{ payment.package.price }})</td>
                            <td>{{ payment.customer.dues }}</td>
                            <td>{{ payment.amount }}</td>
                            <td>
                              <router-link :to="'/dashboard/payment/show/' + payment.id" class="btn btn-default btn-sm"><span class="fa fa-eye"></span></router-link>
                              <button class="btn btn-primary btn-sm" @click=""><i class="fa fa-edit"></i></button>
                              <button class="btn btn-danger btn-sm"><i class="fa fa-times"></i></button>
                            </td>
                          </tr>
                        <tr v-if="payments.length === 0">
                          <td colspan="9" class="alert alert-info text-center">
                            <h4>No payments found</h4>
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
              title : 'Payments',
              type : 'Active',
              keyword : '',
              payments : {},
              ids : [],
            }
        },
        methods: {
          load(){
            axios.get('/api/billing/payment/list')
            .then(( data ) => {
              console.log(data)
              this.payments = data.data.data;
            } )
            .catch(() => {});
          },
          fetchData( type ){
            this.type = type;
            this.title = type + ' Customers';
            this.load();
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
