@extends('layouts.master')
@section('main-content')
@section('page-css')
@endsection

<div class="breadcrumb">
  <h1>{{ __('translate.Adjustment') }}</h1>
</div>

<div class="separator-breadcrumb border-top"></div>


<div class="row" id="section_edit_adjustment">
  <div class="col-lg-12 mb-3">

        <div class="card">
          <div class="card-body">
            <div class="row">

              <div class="col-md-6">
                  <div>{{ __('translate.date') }} : @{{adjustment.date}}</div>
              </div>

              <div class="col-md-6">
                <div>{{ __('translate.warehouse') }} : @{{adjustment.warehouse}}</div>
              </div>
            </div>
          </div>
        </div>

        <div class="card mt-5">
          <div class="card-body">
            <div class="row">

              <!-- Products -->
              <div class="col-md-12">
                <div class="table-responsive">
                  <table class="table table-hover table-md">
                    <thead class="bg-gray-300">
                      <tr>
                        <th scope="col">#</th>
                        <th scope="col">{{ __('translate.Product_Name') }}</th>
                        <th scope="col">{{ __('translate.Code_Product') }}</th>
                        <th scope="col">{{ __('translate.Quantity') }}</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr v-if="details.length <=0">
                        <td colspan="7">{{ __('translate.No_data_Available') }}</td>
                      </tr>
                      <tr v-for="detail in details" :key="detail.detail_id">
                        <td>@{{detail.detail_id}}</td>
                        <td>@{{detail.name}}</td>
                        <td>@{{detail.sku}}</td>
                        <td>
                          <div class="d-flex align-items-center">
                            @{{detail.type=='add'?'+':'-'}}@{{detail.quantity}} @{{detail.unit}}
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

        <div class="card mt-5">
          <div class="card-body">
            <div class="row">

              <div class="form-group col-md-12">
                <label for="note">{{ __('translate.Please_provide_any_details') }} </label><br>
                @{{adjustment.notes}}
              </div>

            </div>
          </div>
        </div>
  </div>
</div>

@endsection

@section('page-js')


<script type="text/javascript">
    $(function () {
        "use strict";
    });
</script>
<script>
    Vue.component('v-select', VueSelect.VueSelect)

    var app = new Vue({
        el: '#section_edit_adjustment',
        data: {
            focused: false,
            timer:null,
            search_input:'',
            product_filter:[],
            isLoading: true,
            SubmitProcessing:false,
            warehouses: @json($warehouses),
            products: @json($products),
            details: @json($details),
            errors:[],
            adjustment: @json($adjustment),
            product: {
                id: "",
                sku: "",
                current: "",
                quantity: 1,
                name: "",
                product_id: "",
                detail_id: "",
                product_variant_id: "",
                unit: ""
            },
        },
        methods: {
        },
        created() {
        }
    })
</script>
@endsection