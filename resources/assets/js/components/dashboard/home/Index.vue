<template>
<div>    
    <!-- Counts Section -->
    <section class="dashboard-counts section-padding">
        <div class="container-fluid">
          <div class="row">
            <!-- Count item widget-->
            <div class="col-xl-3 col-md-4 col-6">
              <div class="wrapper count-title d-flex">
                <div class="icon"><i class="icon-user"></i></div>
                <div class="name">
                    <strong class="text-uppercase">Customers</strong>
                    <span>Active Customers</span>
                    <div class="count-number">{{ stats.totalCustomer }}</div>
                </div>
              </div>
            </div>
            <!-- Count item widget-->
            <div class="col-xl-3 col-md-4 col-6">
              <div class="wrapper count-title d-flex">
                <div class="icon"><i class="icon-padnote"></i></div>
                <div class="name">
                    <strong class="text-uppercase">Bills</strong>
                    <span>Current month bills</span>
                  <div class="count-number">{{ stats.totalBill }}</div>
                </div>
              </div>
            </div>
            <!-- Count item widget-->
            <div class="col-xl-3 col-md-4 col-6">
              <div class="wrapper count-title d-flex">
                <div class="icon"><i class="icon-check"></i></div>
                <div class="name">
                    <strong class="text-uppercase">Dues</strong>
                    <span>Current Month dues</span>
                  <div class="count-number">{{ stats.totalDue }}</div>
                </div>
              </div>
            </div>
            <!-- Count item widget-->
            <div class="col-xl-3 col-md-4 col-6">
              <div class="wrapper count-title d-flex">
                <div class="icon"><i class="icon-bill"></i></div>
                <div class="name">
                    <strong class="text-uppercase">Collection</strong>
                    <span>Current month collections</span>
                  <div class="count-number">{{ stats.totalCollection }}</div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>
      <!-- Header Section-->
      <section class="dashboard-header section-padding">
        <div class="container-fluid">
          <div class="row d-flex align-items-md-stretch">
            <div class="col-sm-12">
              <h4>Pending Requests</h4>
              <table class="table table-sm table-bordered table-striped">
                <thead>
                  <tr>
                    <th>#</th>
                    <th>Customer Name</th>
                    <th>Location</th>
                    <th>Type</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="request in requests" :key="request.id">
                    <td>{{ request.id }}</td>
                    <td>{{ request.customer.name }}</td>
                    <td>{{ request.customer.area.name }}</td>
                    <td>{{ request.type }}</td>
                    <td>
                      <router-link to="/requests/view/1" class="btn btn-default"><i class="fa fa-eye"></i></router-link>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </section>
      <!-- Statistics Section-->
      <section class="statistics">
        <div class="container-fluid">
          <div class="row d-flex">
            <div class="col-lg-4">
              <!-- Income-->
              <div class="card income text-center">
                <div class="icon"><i class="icon-line-chart"></i></div>
                <div class="number">{{ stats.totalCustomer }}</div><strong class="text-primary">Total Customers</strong>
              </div>
            </div>
            <div class="col-lg-4">
              <!-- Monthly Usage-->
              <div class="card data-usage">
                <h2 class="display h4">Active Customers</h2>
                <div class="row d-flex align-items-center">
                  <div class="col-sm-6">
                    <div id="progress-circle" class="d-flex align-items-center justify-content-center"></div>
                  </div>
                  <div class="col-sm-6"><strong class="text-primary">{{ stats.activeCustomer }}</strong><small>Current Plan</small><span>{{ stats.totalCustomer }} Gb Monthly</span></div>
                </div>
              </div>
            </div>
            <div class="col-lg-4">
              <!-- User Actibity-->
              <div class="card user-activity">
                <h2 class="display h4">New Customer</h2>
                <div class="number">{{ stats.newCoustomer }}</div>
                <h3 class="h4 display">New customer of this month</h3>
                <div class="progress">
                  <div role="progressbar" style="width: 25%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100" class="progress-bar progress-bar bg-primary"></div>
                </div>
                <div class="page-statistics d-flex justify-content-between">
                  <div class="page-statistics-left"><span>Closed Connections</span><strong>{{ stats.closedConnection }}</strong></div>
                  <div class="page-statistics-right"><span>New Connections</span><strong>{{ stats.newConnection }}</strong></div>
                </div>
              </div>
            </div>
          </div>
        </div>
    </section>
</div>
</template>

<script>
    export default {
      data() {
        return {
          stats: {},
        }
      },
      methods:{
        load(){
          console.log('test');
        }
      },
      mounted() {
        this.load();
      }
    }
</script>
