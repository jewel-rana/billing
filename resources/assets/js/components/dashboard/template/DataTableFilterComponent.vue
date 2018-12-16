<template>
          <div class="row">
            <div class="col-md-12">
              <div class="card card-table">
                <div class="row table-filters-container">
                  <div class="col-12 col-lg-12 col-xl-6">
                    <div class="row">
                      <div class="col-12 col-lg-6 table-filters pb-0 pb-xl-4"><span class="table-filter-title">Milestone progress</span>
                        <div class="filter-container">
                          <form>
                            <label class="control-label d-block"><span id="slider-value">0% - 100%</span></label>
                            <input class="bslider form-control" id="milestone_slider" type="text" data-slider-value="[0,100]" data-slider-step="1" data-slider-max="100" data-slider-min="0" value="50">
                          </form>
                        </div>
                      </div>
                      <div class="col-12 col-lg-6 table-filters pb-0 pb-xl-4"><span class="table-filter-title">Proyect</span>
                        <div class="filter-container">
                          <label class="control-label">Select a proyect:</label>
                          <form>
                            <select class="select2">
                              <option value="All">All</option>
                              <option value="Bootstrap">Bootstrap Admin</option>
                              <option value="CLI">CLI Connector</option>
                              <option value="Back-end">Back-end Manager</option>
                            </select>
                          </form>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="col-12 col-lg-12 col-xl-6">
                    <div class="row">
                      <div class="col-12 col-lg-6 table-filters pb-0 pb-xl-4"><span class="table-filter-title">Date</span>
                        <div class="filter-container">
                          <form>
                            <div class="row">
                              <div class="col-6">
                                <label class="control-label">Since:</label>
                                <input class="form-control form-control-sm datetimepicker" id="dateSince" data-min-view="2" data-date-format="yyyy-mm-dd">
                              </div>
                              <div class="col-6">
                                <label class="control-label">To:</label>
                                <input class="form-control form-control-sm datetimepicker" id="dateTo" data-min-view="2" data-date-format="yyyy-mm-dd">
                              </div>
                            </div>
                          </form>
                        </div>
                      </div>
                      <div class="col-12 col-lg-6 table-filters pb-xl-4"><span class="table-filter-title">Status</span>
                        <div class="filter-container">
                          <form>
                            <div class="row">
                              <div class="col-6">
                                <div class="custom-controls-stacked">
                                  <label class="custom-control custom-checkbox">
                                    <input class="custom-control-input" id="toDo" type="checkbox"><span class="custom-control-label">To Do</span>
                                  </label>
                                  <label class="custom-control custom-checkbox">
                                    <input class="custom-control-input" id="inReview" type="checkbox"><span class="custom-control-label">In review</span>
                                  </label>
                                </div>
                              </div>
                              <div class="col-6">
                                <div class="custom-controls-stacked">
                                  <label class="custom-control custom-checkbox">
                                    <input class="custom-control-input" id="inProgress" type="checkbox"><span class="custom-control-label">In progress</span>
                                  </label>
                                  <label class="custom-control custom-checkbox">
                                    <input class="custom-control-input" id="done" type="checkbox"><span class="custom-control-label">Done</span>
                                  </label>
                                </div>
                              </div>
                            </div>
                          </form>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="card-body">
                  <div class="noSwipe">
                    <table class="table table-striped table-hover be-table-responsive" id="table1">
                      <thead>
                        <tr>
                          <th style="width:5%;">
                            <label class="custom-control custom-control-sm custom-checkbox">
                              <input class="custom-control-input" type="checkbox"><span class="custom-control-label"></span>
                            </label>
                          </th>
                          <th style="width:20%;">User</th>
                          <th style="width:17%;">Last Commit</th>
                          <th style="width:15%;">Milestone</th>
                          <th style="width:10%;">Branch</th>
                          <th style="width:10%;">Date</th>
                          <th style="width:10%;"></th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr class="success done">
                          <td>
                            <label class="custom-control custom-control-sm custom-checkbox">
                              <input class="custom-control-input" type="checkbox"><span class="custom-control-label"></span>
                            </label>
                          </td>
                          <td class="user-avatar cell-detail user-info"><img class="mt-0 mt-md-2 mt-lg-0" src="assets/img/avatar6.png" alt="Avatar"><span>Penelope Thornton</span><span class="cell-detail-description">Developer</span></td>
                          <td class="cell-detail" data-project="Bootstrap"><span>Initial commit</span><span class="cell-detail-description">Bootstrap Admin</span></td>
                          <td class="milestone" data-progress="0,45"><span class="completed">8 / 15</span><span class="version">v1.2.0</span>
                            <div class="progress">
                              <div class="progress-bar progress-bar-primary" style="width: 45%;"></div>
                            </div>
                          </td>
                          <td class="cell-detail"><span>master</span><span class="cell-detail-description">63e8ec3</span></td>
                          <td class="cell-detail"><span class="date">May 6, 2018</span><span class="cell-detail-description">8:30</span></td>
                          <td class="text-right">
                            <div class="btn-group btn-hspace">
                              <button class="btn btn-secondary dropdown-toggle" type="button" data-toggle="dropdown">Done <span class="icon-dropdown mdi mdi-chevron-down"></span></button>
                              <div class="dropdown-menu" role="menu"><a class="dropdown-item" href="#">Action</a><a class="dropdown-item" href="#">Another action</a><a class="dropdown-item" href="#">Something else here</a>
                                <div class="dropdown-divider"></div><a class="dropdown-item" href="#">Separated link</a>
                              </div>
                            </div>
                          </td>
                        </tr>
                        <tr class="primary to-do">
                          <td data-status="in-progress">
                            <label class="custom-control custom-control-sm custom-checkbox">
                              <input class="custom-control-input" type="checkbox"><span class="custom-control-label"></span>
                            </label>
                          </td>
                          <td class="user-avatar cell-detail user-info"><img class="mt-0 mt-md-2 mt-lg-0" src="assets/img/avatar4.png" alt="Avatar"><span>Benji Harper</span><span class="cell-detail-description">Designer</span></td>
                          <td class="cell-detail" data-project="CLI"><span>Main structure markup</span><span class="cell-detail-description">CLI Connector</span></td>
                          <td class="milestone" data-progress="0,75"><span class="completed">22 / 30</span><span class="version">v1.1.5</span>
                            <div class="progress">
                              <div class="progress-bar progress-bar-primary" style="width: 75%;"></div>
                            </div>
                          </td>
                          <td class="cell-detail"><span>develop</span><span class="cell-detail-description">4cc1bc2</span></td>
                          <td class="cell-detail"><span class="date">April 22, 2018</span><span class="cell-detail-description">14:45</span></td>
                          <td class="text-right">
                            <div class="btn-group btn-hspace">
                              <button class="btn btn-secondary dropdown-toggle" type="button" data-toggle="dropdown">To Do <span class="icon-dropdown mdi mdi-chevron-down"></span></button>
                              <div class="dropdown-menu" role="menu"><a class="dropdown-item" href="#">Action</a><a class="dropdown-item" href="#">Another action</a><a class="dropdown-item" href="#">Something else here</a>
                                <div class="dropdown-divider"></div><a class="dropdown-item" href="#">Separated link</a>
                              </div>
                            </div>
                          </td>
                        </tr>
                        <tr class="danger in-review">
                          <td>
                            <label class="custom-control custom-control-sm custom-checkbox">
                              <input class="custom-control-input" type="checkbox"><span class="custom-control-label"></span>
                            </label>
                          </td>
                          <td class="user-avatar cell-detail user-info"><img class="mt-0 mt-md-2 mt-lg-0" src="assets/img/avatar5.png" alt="Avatar"><span>Justine Myranda</span><span class="cell-detail-description">Designer</span></td>
                          <td class="cell-detail" data-project="Back-end"><span>Left sidebar adjusments</span><span class="cell-detail-description">Back-end Manager</span></td>
                          <td class="milestone" data-progress="0,33"><span class="completed">10 / 30</span><span class="version">v1.1.3</span>
                            <div class="progress">
                              <div class="progress-bar progress-bar-primary" style="width: 33%;"></div>
                            </div>
                          </td>
                          <td class="cell-detail"><span>develop</span><span class="cell-detail-description">5477993</span></td>
                          <td class="cell-detail"><span class="date">April 15, 2018</span><span class="cell-detail-description">10:00</span></td>
                          <td class="text-right">
                            <div class="btn-group btn-hspace">
                              <button class="btn btn-secondary dropdown-toggle" type="button" data-toggle="dropdown">In review <span class="icon-dropdown mdi mdi-chevron-down"></span></button>
                              <div class="dropdown-menu" role="menu"><a class="dropdown-item" href="#">Action</a><a class="dropdown-item" href="#">Another action</a><a class="dropdown-item" href="#">Something else here</a>
                                <div class="dropdown-divider"></div><a class="dropdown-item" href="#">Separated link</a>
                              </div>
                            </div>
                          </td>
                        </tr>
                        <tr class="warning in-progress">
                          <td>
                            <label class="custom-control custom-control-sm custom-checkbox">
                              <input class="custom-control-input" type="checkbox"><span class="custom-control-label"></span>
                            </label>
                          </td>
                          <td class="user-avatar cell-detail user-info"><img class="mt-0 mt-md-2 mt-lg-0" src="assets/img/avatar3.png" alt="Avatar"><span>Sherwood Clifford</span><span class="cell-detail-description">Developer</span></td>
                          <td class="cell-detail" data-project="Bootstrap"><span>Topbar dropdown style</span><span class="cell-detail-description">Bootstrap Admin</span></td>
                          <td class="milestone" data-progress="0,55"><span class="completed">25 / 40</span><span class="version">v1.0.4</span>
                            <div class="progress">
                              <div class="progress-bar progress-bar-primary" style="width: 55%;"></div>
                            </div>
                          </td>
                          <td class="cell-detail"><span>master</span><span class="cell-detail-description">8cb98ec</span></td>
                          <td class="cell-detail"><span class="date">April 8, 2018</span><span class="cell-detail-description">17:24</span></td>
                          <td class="text-right">
                            <div class="btn-group btn-hspace">
                              <button class="btn btn-secondary dropdown-toggle" type="button" data-toggle="dropdown">In progress <span class="icon-dropdown mdi mdi-chevron-down"></span></button>
                              <div class="dropdown-menu" role="menu"><a class="dropdown-item" href="#">Action</a><a class="dropdown-item" href="#">Another action</a><a class="dropdown-item" href="#">Something else here</a>
                                <div class="dropdown-divider"></div><a class="dropdown-item" href="#">Separated link</a>
                              </div>
                            </div>
                          </td>
                        </tr>
                        <tr class="primary to-do">
                          <td>
                            <label class="custom-control custom-control-sm custom-checkbox">
                              <input class="custom-control-input" type="checkbox"><span class="custom-control-label"></span>
                            </label>
                          </td>
                          <td class="user-avatar cell-detail user-info"><img class="mt-0 mt-md-2 mt-lg-0" src="assets/img/avatar.png" alt="Avatar"><span>Kristopher Donny</span><span class="cell-detail-description">Designer</span></td>
                          <td class="cell-detail" data-project="CLI"><span>Right sidebar adjusments</span><span class="cell-detail-description">CLI Connector</span></td>
                          <td class="milestone" data-progress="0,98"><span class="completed">38 / 40</span><span class="version">v1.0.1</span>
                            <div class="progress">
                              <div class="progress-bar progress-bar-primary" style="width: 98%;"></div>
                            </div>
                          </td>
                          <td class="cell-detail"><span>master</span><span class="cell-detail-description">65bc2da</span></td>
                          <td class="cell-detail"><span class="date">March 18, 2018</span><span class="cell-detail-description">13:02</span></td>
                          <td class="text-right">
                            <div class="btn-group btn-hspace">
                              <button class="btn btn-secondary dropdown-toggle" type="button" data-toggle="dropdown">To Do <span class="icon-dropdown mdi mdi-chevron-down"></span></button>
                              <div class="dropdown-menu" role="menu"><a class="dropdown-item" href="#">Action</a><a class="dropdown-item" href="#">Another action</a><a class="dropdown-item" href="#">Something else here</a>
                                <div class="dropdown-divider"></div><a class="dropdown-item" href="#">Separated link</a>
                              </div>
                            </div>
                          </td>
                        </tr>
                        <tr class="danger in-review">
                          <td>
                            <label class="custom-control custom-control-sm custom-checkbox">
                              <input class="custom-control-input" type="checkbox"><span class="custom-control-label"></span>
                            </label>
                          </td>
                          <td class="user-avatar cell-detail user-info"><img src="assets/img/avatar6.png" alt="Avatar"><span>Justin Adams</span><span class="cell-detail-description">Developer</span></td>
                          <td class="cell-detail" data-project="CLI"><span>Topbar dropdown style</span><span class="cell-detail-description">CLI Connector</span></td>
                          <td class="milestone" data-progress="0,25"><span class="completed">15 / 70</span><span class="version">v1.0.4</span>
                            <div class="progress">
                              <div class="progress-bar progress-bar-primary" style="width: 25%;"></div>
                            </div>
                          </td>
                          <td class="cell-detail"><span>master</span><span class="cell-detail-description">8cb98ec</span></td>
                          <td class="cell-detail"><span class="date">Jun 2, 2018</span><span class="cell-detail-description">17:24</span></td>
                          <td class="text-right">
                            <div class="btn-group btn-hspace">
                              <button class="btn btn-secondary dropdown-toggle" type="button" data-toggle="dropdown">In review <span class="icon-dropdown mdi mdi-chevron-down"></span></button>
                              <div class="dropdown-menu" role="menu"><a class="dropdown-item" href="#">Action</a><a class="dropdown-item" href="#">Another action</a><a class="dropdown-item" href="#">Something else here</a>
                                <div class="dropdown-divider"></div><a class="dropdown-item" href="#">Separated link</a>
                              </div>
                            </div>
                          </td>
                        </tr>
                        <tr class="primary to-do">
                          <td>
                            <label class="custom-control custom-control-sm custom-checkbox">
                              <input class="custom-control-input" type="checkbox"><span class="custom-control-label"></span>
                            </label>
                          </td>
                          <td class="user-avatar cell-detail user-info"><img src="assets/img/avatar4.png" alt="Avatar"><span>Ryan Lawrence</span><span class="cell-detail-description">Designer</span></td>
                          <td class="cell-detail" data-project="Back-end"><span>Main structure</span><span class="cell-detail-description">Back-end Manager</span></td>
                          <td class="milestone" data-progress="0,80"><span class="completed">30 / 34</span><span class="version">v1.1.5</span>
                            <div class="progress">
                              <div class="progress-bar progress-bar-primary" style="width: 80%;"></div>
                            </div>
                          </td>
                          <td class="cell-detail"><span>develop</span><span class="cell-detail-description">4cc1bc2</span></td>
                          <td class="cell-detail"><span class="date">Jan 22, 2018</span><span class="cell-detail-description">14:45</span></td>
                          <td class="text-right">
                            <div class="btn-group btn-hspace">
                              <button class="btn btn-secondary dropdown-toggle" type="button" data-toggle="dropdown">To Do <span class="icon-dropdown mdi mdi-chevron-down"></span></button>
                              <div class="dropdown-menu" role="menu"><a class="dropdown-item" href="#">Action</a><a class="dropdown-item" href="#">Another action</a><a class="dropdown-item" href="#">Something else here</a>
                                <div class="dropdown-divider"></div><a class="dropdown-item" href="#">Separated link</a>
                              </div>
                            </div>
                          </td>
                        </tr>
                        <tr class="warning in-progress">
                          <td>
                            <label class="custom-control custom-control-sm custom-control-sm custom-checkbox">
                              <input class="custom-control-input" type="checkbox"><span class="custom-control-label"></span>
                            </label>
                          </td>
                          <td class="user-avatar cell-detail user-info"><img src="assets/img/avatar5.png" alt="Avatar"><span>Benji Miller</span><span class="cell-detail-description">Designer</span></td>
                          <td class="cell-detail" data-project="Bootstrap"><span>Left sidebar adjusments</span><span class="cell-detail-description">Bootstrap Admin</span></td>
                          <td class="milestone" data-progress="0,63"><span class="completed">25 / 43</span><span class="version">v1.1.3</span>
                            <div class="progress">
                              <div class="progress-bar progress-bar-primary" style="width: 63%;"></div>
                            </div>
                          </td>
                          <td class="cell-detail"><span>develop</span><span class="cell-detail-description">5477993</span></td>
                          <td class="cell-detail"><span class="date">May 10, 2018</span><span class="cell-detail-description">10:00</span></td>
                          <td class="text-right">
                            <div class="btn-group btn-hspace">
                              <button class="btn btn-secondary dropdown-toggle" type="button" data-toggle="dropdown">In progress <span class="icon-dropdown mdi mdi-chevron-down"></span></button>
                              <div class="dropdown-menu" role="menu"><a class="dropdown-item" href="#">Action</a><a class="dropdown-item" href="#">Another action</a><a class="dropdown-item" href="#">Something else here</a>
                                <div class="dropdown-divider"></div><a class="dropdown-item" href="#">Separated link</a>
                              </div>
                            </div>
                          </td>
                        </tr>
                        <tr class="success done">
                          <td>
                            <label class="custom-control custom-control-sm custom-checkbox">
                              <input class="custom-control-input" type="checkbox"><span class="custom-control-label"></span>
                            </label>
                          </td>
                          <td class="user-avatar cell-detail user-info"><img class="mt-0 mt-md-2 mt-lg-0" src="assets/img/avatar3.png" alt="Avatar"><span>John Peterson</span><span class="cell-detail-description">Developer</span></td>
                          <td class="cell-detail" data-project="CLI"><span>Initial commit</span><span class="cell-detail-description">CLI Connector</span></td>
                          <td class="milestone" data-progress="0,7"><span class="completed">3 / 20</span><span class="version">v1.2.0</span>
                            <div class="progress">
                              <div class="progress-bar progress-bar-primary" style="width: 7%;"></div>
                            </div>
                          </td>
                          <td class="cell-detail"><span>master</span><span class="cell-detail-description">63e8ec3</span></td>
                          <td class="cell-detail"><span class="date">April 6, 2018</span><span class="cell-detail-description">8:30</span></td>
                          <td class="text-right">
                            <div class="btn-group btn-hspace">
                              <button class="btn btn-secondary dropdown-toggle" type="button" data-toggle="dropdown">Done <span class="icon-dropdown mdi mdi-chevron-down"></span></button>
                              <div class="dropdown-menu" role="menu"><a class="dropdown-item" href="#">Action</a><a class="dropdown-item" href="#">Another action</a><a class="dropdown-item" href="#">Something else here</a>
                                <div class="dropdown-divider"></div><a class="dropdown-item" href="#">Separated link	</a>
                              </div>
                            </div>
                          </td>
                        </tr>
                        <tr class="primary to-do">
                          <td>
                            <label class="custom-control custom-control-sm custom-checkbox">
                              <input class="custom-control-input" type="checkbox"><span class="custom-control-label"></span>
                            </label>
                          </td>
                          <td class="user-avatar cell-detail user-info"><img class="mt-0 mt-md-2 mt-lg-0" src="assets/img/avatar.png" alt="Avatar"><span>Brett Harris</span><span class="cell-detail-description">Designer</span></td>
                          <td class="cell-detail" data-project="Bootstrap"><span>Right sidebar adjusments</span><span class="cell-detail-description">Bootstrap Admin</span></td>
                          <td class="milestone" data-progress="0,15"><span class="completed">7 / 50</span><span class="version">v1.0.1</span>
                            <div class="progress">
                              <div class="progress-bar progress-bar-primary" style="width: 15%;"></div>
                            </div>
                          </td>
                          <td class="cell-detail"><span>master</span><span class="cell-detail-description">65bc2da</span></td>
                          <td class="cell-detail"><span class="date">Jun 10, 2018</span><span class="cell-detail-description">13:02</span></td>
                          <td class="text-right">
                            <div class="btn-group btn-hspace">
                              <button class="btn btn-secondary dropdown-toggle" type="button" data-toggle="dropdown">To Do <span class="icon-dropdown mdi mdi-chevron-down"></span></button>
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
    <script src="assets/lib/jquery-ui/jquery-ui.min.js" type="text/javascript"></script>
    <script src="assets/lib/select2/js/select2.min.js" type="text/javascript"></script>
    <script src="assets/lib/select2/js/select2.full.min.js" type="text/javascript"></script>
    <script src="assets/lib/bootstrap-slider/bootstrap-slider.min.js" type="text/javascript"></script>
    <script src="assets/lib/datetimepicker/js/bootstrap-datetimepicker.min.js" type="text/javascript"></script>
    <script src="assets/lib/datatables/datatables.net/js/jquery.dataTables.js" type="text/javascript"></script>
    <script src="assets/lib/datatables/datatables.net-bs4/js/dataTables.bootstrap4.js" type="text/javascript"></script>
    <script src="assets/lib/datatables/datatables.net-buttons/js/dataTables.buttons.min.js" type="text/javascript"></script>
    <script src="assets/lib/datatables/datatables.net-buttons/js/buttons.flash.min.js" type="text/javascript"></script>
    <script src="assets/lib/datatables/jszip/jszip.min.js" type="text/javascript"></script>
    <script src="assets/lib/datatables/pdfmake/pdfmake.min.js" type="text/javascript"></script>
    <script src="assets/lib/datatables/pdfmake/vfs_fonts.js" type="text/javascript"></script>
    <script src="assets/lib/datatables/datatables.net-buttons/js/buttons.colVis.min.js" type="text/javascript"></script>
    <script src="assets/lib/datatables/datatables.net-buttons/js/buttons.print.min.js" type="text/javascript"></script>
    <script src="assets/lib/datatables/datatables.net-buttons/js/buttons.html5.min.js" type="text/javascript"></script>
    <script src="assets/lib/datatables/datatables.net-buttons-bs4/js/buttons.bootstrap4.min.js" type="text/javascript"></script>
    <script src="assets/lib/datatables/datatables.net-responsive/js/dataTables.responsive.min.js" type="text/javascript"></script>
    <script src="assets/lib/datatables/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js" type="text/javascript"></script>
    <script type="text/javascript">
      $(document).ready(function(){
        //-initialize the javascript
        App.init();
        App.tableFilters();
      });
    </script>
<script>
    export default {
        mounted() {
            console.log('Component mounted.')
        }
    }
</script>