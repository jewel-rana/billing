<template>
      <section id="contentSection">
        <div class="container-fluid">
          <div class="row">
            <div class="col-sm-12 text-right">
              <h2 class="page-title">{{ title }}</h2>
            </div>
            <div class="col-lg-12">
              <div class="table-responsive">
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
              title : 'Active Customers',
              type : 'Active',
              editmode : false,
              users : {},
              form: new Form({
                name : '',
                email : '',
                password : '',
                password_confirm : '',
                gender : '',
                dob_day : '',
                dob_month : '',
                dob_year : ''
              })
            }
        },
        methods: {
          load(){
            console.log( this.type );
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
          }
        },
        mounted() {
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
