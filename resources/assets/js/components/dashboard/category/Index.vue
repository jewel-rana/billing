<template>
          <div class="row">
            <div class="col-sm-12 text-right">
              <a href="#">
                <p><i class="icon icon-plus"></i></p>
                <p>Add new</p>
              </a>
            </div>
            <div class="col-sm-12">
              <div class="card card-table">
                <div class="card-header">Responsive Table
                  <div class="tools dropdown">
                    <a href="#" class="btn btn-success mr-2">
                        <span class="icon mdi mdi-plus text-white"></span> Add new
                    </a>
                    <a class="dropdown-toggle" href="#" role="button" data-toggle="dropdown">
                        <span class="icon mdi mdi-more-vert"></span>
                    </a>
                    <div class="dropdown-menu" role="menu">
                        <a class="dropdown-item" href="#">Action</a>
                        <a class="dropdown-item" href="#">Another action</a>
                        <a class="dropdown-item" href="#">Something else here</a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="#">Separated link</a>
                    </div>
                  </div>
                </div>
                <div class="card-body">
                  <div class="table-responsive noSwipe">
                    <table class="table table-striped table-hover">
                      <thead>
                        <tr>
                          <th style="width:4%;">
                            <label class="custom-control custom-control-sm custom-checkbox">
                              <input class="custom-control-input" type="checkbox"><span class="custom-control-label"></span>
                            </label>
                          </th>
                          <th style="width:20%;">User</th>
                          <th style="width:17%;">Email</th>
                          <th style="width:15%;">Status</th>
                          <th style="width:10%;">Date</th>
                          <th style="width:10%;"></th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr>
                          <td>
                            <label class="custom-control custom-control-sm custom-checkbox">
                              <input class="custom-control-input" type="checkbox"><span class="custom-control-label"></span>
                            </label>
                          </td>
                          <td class="user-avatar cell-detail user-info"><img src="assets/img/avatar6.png" alt="Avatar"><span>Penelope Thornton</span><span class="cell-detail-description">Developer</span></td>
                          <td class="cell-detail"> <span>Initial commit</span><span class="cell-detail-description">Bootstrap Admin</span></td>
                          <td class="milestone"><span class="completed">8 / 15</span><span class="version">v1.2.0</span>
                            <div class="progress">
                              <div class="progress-bar progress-bar-primary" style="width: 45%;"></div>
                            </div>
                          </td>
                          <td class="cell-detail"><span>May 6, 2018</span><span class="cell-detail-description">8:30</span></td>
                          <td class="text-right">
                            <div class="btn-group btn-hspace">
                              <button class="btn btn-secondary dropdown-toggle" type="button" data-toggle="dropdown">Open <span class="icon-dropdown mdi mdi-chevron-down"></span></button>
                              <div class="dropdown-menu" role="menu"><a class="dropdown-item" href="#">Action</a><a class="dropdown-item" href="#">Another action</a><a class="dropdown-item" href="#">Something else here</a>
                                <div class="dropdown-divider"></div><a class="dropdown-item" href="#">Separated link</a>
                              </div>
                            </div>
                          </td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>
          </div>
</template>

<script>
    export default {
        data(){
            return {
                name : '',
                email : '',
                password : '',
                password_confirm : '',
                gender : ''
            }
        },
        mounted() {
            console.log('Component mounted.')
        }
    }
</script>
