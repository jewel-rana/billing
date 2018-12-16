@extends('dashboard.layouts.app')

@section('content')
<div class="row">
            <div class="col-lg-6">
              <div class="card">
                <div class="card-header card-header-divider">Basic List<span class="card-subtitle">Drag & drop hierarchical list with mouse and touch compatibility</span></div>
                <div class="card-body">
                  <div class="dd" id="list1">
                    <ol class="dd-list">
                      <li class="dd-item" data-id="1">
                        <div class="dd-handle">Item 1</div>
                      </li>
                      <li class="dd-item" data-id="2">
                        <div class="dd-handle">Item 2</div>
                      </li>
                      <li class="dd-item" data-id="3">
                        <div class="dd-handle">Item 3</div>
                        <ol class="dd-list">
                          <li class="dd-item" data-id="4">
                            <div class="dd-handle">Item 4</div>
                          </li>
                          <li class="dd-item" data-id="5">
                            <div class="dd-handle">Item 5</div>
                          </li>
                        </ol>
                      </li>
                    </ol>
                  </div>
                  <div class="mt-6">
                    <h4>Serialized Output:</h4>
                    <pre><code id="out1"></code></pre>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-lg-6">
              <div class="card">
                <div class="card-header card-header-divider">Draggable Handles<span class="card-subtitle">Drag & drop hierarchical list with mouse and touch compatibility</span></div>
                <div class="card-body">
                  <div class="dd" id="list2">
                    <ol class="dd-list">
                      <li class="dd-item dd3-item" data-id="13">
                        <div class="dd-handle dd3-handle"></div>
                        <div class="dd3-content">Item 13</div>
                        <ol class="dd-list">
                          <li class="dd-item dd3-item" data-id="14">
                            <div class="dd-handle dd3-handle"></div>
                            <div class="dd3-content">Item 14</div>
                          </li>
                          <li class="dd-item dd3-item" data-id="15">
                            <div class="dd-handle dd3-handle"></div>
                            <div class="dd3-content">Item 15</div>
                            <ol class="dd-list">
                              <li class="dd-item dd3-item" data-id="16">
                                <div class="dd-handle dd3-handle"></div>
                                <div class="dd3-content">Item 16</div>
                              </li>
                              <li class="dd-item dd3-item" data-id="17">
                                <div class="dd-handle dd3-handle"></div>
                                <div class="dd3-content">Item 17</div>
                              </li>
                              <li class="dd-item dd3-item" data-id="18">
                                <div class="dd-handle dd3-handle"></div>
                                <div class="dd3-content">Item 18</div>
                              </li>
                            </ol>
                          </li>
                        </ol>
                      </li>
                    </ol>
                  </div>
                  <div class="mt-6">
                    <h4>Serialized Output:</h4>
                    <pre><code id="out2"></code></pre>
                  </div>
                </div>
              </div>
            </div>
          </div>
@endsection

@section('extrajs')
    <script src="assets/lib/jquery.nestable/jquery.nestable.js" type="text/javascript"></script>
@endsection