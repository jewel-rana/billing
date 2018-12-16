<template>
    <div class="row">
        <div class="col-md-3">
            left sidebar
        </div>
        <div class="col-md-8">
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
    </div>
</template>

<script src="{{ asset('assets/lib/jquery.nestable/jquery.nestable.js') }}" type="text/javascript"></script>
<script>
    export default {
        mounted() {
            console.log('Component mounted.')
        }
    }
    $(document).ready(function(){
        //-initialize the javascript
        App.uiNestableLists();
      });
</script>
