<template>
  <section id="contentSection">
    <div class="container-fluid">
      <!-- ******HEADER****** -->
      <header class="header pt-3 pb-0">
        <div class="container">
          <div class="row">
            <div class="col-md-3"> <!-- Image -->
              <a href="#"> <img class="rounded-circle" src="images/kamal.jpg" alt="Kamal" style="width:200px;height:200px"></a>
            </div>

            <div class="col-md-5"> <!-- Rank & Qualifications -->
              <h2 style="font-size:38px"><strong>AHM Kamal</strong></h2>
              <h5 style="color:#3AAA64">Associate Professor</h5>
              <p>Address: Namapara, Trishal, Mymensingh</p>
            </div>

            <div class="col-md-4 text-center"> <!-- Phone & Social -->
              <span class="number" style="font-size:16px">Home:<strong>{{ customer.home_phone}}</strong></span><br />
              <span class="number" style="font-size:16px">Office:<strong>{{ customer.office_phone}}</strong></span><br />
              <span class="number" style="font-size:16px">Email:<strong>{{ customer.email }}</strong></span>
              <!-- <div class="button" style="padding-top:18px">
                <a href="mailto:ahmkctg@yahoo.com" class="btn btn-outline-success btn-block">Send Email</a>
              </div> -->
            </div>
          </div>
        </div>
      </header>
      <!--End of Header-->

      <!-- Main container -->
      <div class="row">
            <div class="col-sm-12 text-right">
              <h2 class="page-title">{{ customer.name }}</h2>
              <ul class="nav nav-tabs menuTab" id="myTab" role="tablist">
                <li class="nav-item">
                  <router-link to="/dashboard/requests" class="nav-link tabbed-menu bg-info" href="#" role="tab" title="Action Request"><i class="fa fa-user"></i> Profile</router-link>
                </li>
                <li class="nav-item">
                  <a class="nav-link tabbed-menu" @click.prevent="fetchData('All')" href="#" role="tab">Account log</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link tabbed-menu active" @click.prevent="fetchData('Active')" href="#">Payments</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link tabbed-menu" @click.prevent="fetchData('Pending')" href="#">Billing history</a>
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
                          <div class="input-group input-group mb-2">
                          </div>
                        </div>
                      </div>
                      <table class="table table-striped table-bordered table-sm">
                        <thead>
                          <tr>
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
                          <tr v-for="bill in billings" :key="bill.id">
                            <th scope="row">{{ bill.id }}</th>
                            <td>{{ bill.name }}</td>
                            <td>{{ bill.type }}</td>
                            <td>{{ bill.address }}</td>
                            <td>{{ bill.package_id }} ({{ bill.package_id }})</td>
                            <td>{{ bill.package_id }}</td>
                            <td>{{ bill.status }}</td>
                            <td>
                              <router-link :to="'/dashboard/bill/show/' + bill.id" class="btn btn-default btn-sm"><span class="fa fa-eye"></span></router-link>
                              <button class="btn btn-primary btn-sm" @click=""><i class="fa fa-edit"></i></button>
                              <button class="btn btn-danger btn-sm"><i class="fa fa-times"></i></button>
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
        customer : {},
        billings : {},
        payments : {},
        dateRange : ''
      }
    },
    methods: {
      load(){
        axios.get('/api/customer/show/?id=' + this.$route.params.id )
        .then(( data ) => {
          this.info = data.data.info;
          this.billings = data.data.billings;
          this.payments = data.data.payments;
        } )
        .catch(() => {});
      },
      paymentModal(){
        this.editmode = false;
        $('#appModal').modal('show');
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