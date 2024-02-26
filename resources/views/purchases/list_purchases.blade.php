@extends('layouts.master')
@section('main-content')
@section('page-css')
<link rel="stylesheet" href="{{asset('assets/vendor/datatables.min.css')}}">
<link rel="stylesheet" href="{{asset('assets/vendor/datepicker.min.css')}}">
<link rel="stylesheet" href="{{asset('assets/vendor/flatpickr.min.css')}}">

@endsection

<div class="breadcrumb">
  <h1>{{ __('translate.Purchases_Order') }}</h1>
</div>

<div class="separator-breadcrumb border-top"></div>

<div id="section_purchase_list">
  <div class="card">
    <div class="card-body">
      <div class="row">
        <div class="col-md-12">
          <div class="text-end mb-3">
            @can('purchases_add')
            <a href="/purchase/purchases/create" class="btn btn-outline-primary btn-md m-1"><i class="fas fa-plus-circle me-2 font-weight-bold"></i>
              {{ __('translate.Create') }}</a>
            @endcan
            <a class="btn btn-outline-success btn-md m-1" id="Show_Modal_Filter"><i class="fas fa-filter me-2 font-weight-bold"></i>
              {{ __('translate.Filter') }}</a>
          </div>

          <div class="table-responsive">
            <table id="purchase_table" class="display table table_heightx">
              <thead>
                <tr>
                  <th>ID</th>
                  <th>{{ __('translate.Date') }}</th>
                  <th>{{ __('translate.Reference') }}</th>
                  <th>{{ __('translate.Supplier') }}</th>
                  <th>{{ __('translate.warehouse') }}</th>
                  <th>{{ __('translate.Total') }}</th>
                  {{--<th>{{ __('translate.Paid') }}</th>
                  <th>{{ __('translate.Due') }}</th>--}}
                  <th>{{ __('translate.Payment_Status') }}</th>
                  <th class="not_show">{{ __('translate.Action') }}</th>
                </tr>
              </thead>
              <tbody>
              </tbody>
            </table>
          </div>

        </div>
      </div>
    </div>
  </div>


  <!-- Modal Filter -->
  <div class="modal fade" id="filter_purchase_modal" tabindex="-1" role="dialog" aria-labelledby="filter_purchase_modal"
    aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">{{ __('translate.Filter') }}</h5>
         <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">

          <form method="POST" id="filter_purchase">
            @csrf
            <div class="row">

              <div class="form-group col-md-6">
                <label for="start_date">{{ __('translate.From_Date') }}
                </label>
                <input type="text" class="form-control date" name="start_date" id="start_date"
                  placeholder="{{ __('translate.From_Date') }}" value="">
              </div>

              <div class="form-group col-md-6">
                <label for="end_date">{{ __('translate.To_Date') }} </label>
                <input type="text" class="form-control date" name="end_date" id="end_date"
                  placeholder="{{ __('translate.To_Date') }}" value="">
              </div>

              <div class="form-group col-md-6">
                <label for="Ref">{{ __('translate.Reference') }}
                </label>
                <input type="text" class="form-control" name="Ref" id="Ref"
                  placeholder="{{ __('translate.Reference') }}">
              </div>

              <div class="form-group col-md-6">
                <label for="supplier_id">{{ __('translate.Supplier') }}
                </label>
                <select name="supplier_id" id="supplier_id" class="form-control">
                  <option value="0">{{ __('translate.All') }}</option>
                  @foreach ($suppliers as $supplier)
                  <option value="{{$supplier->id}}">{{$supplier->name}}</option>
                  @endforeach
                </select>
              </div>

              <div class="form-group col-md-6">
                <label for="warehouse_id">{{ __('translate.warehouse') }}
                </label>
                <select name="warehouse_id" id="warehouse_id" class="form-control">
                  <option value="0">{{ __('translate.All') }}</option>
                  @foreach ($warehouses as $warehouse)
                  <option value="{{$warehouse->id}}">{{$warehouse->name}}</option>
                  @endforeach
                </select>
              </div>

            </div>

            <div class="row mt-3">

              <div class="col-md-6">
                <button type="submit" class="btn btn-primary" type="button">
                  <i class="fas fa-filter me-2 font-weight-bold"></i> {{ __('translate.Filter') }}
                </button>
                <button id="Clear_Form" class="btn btn-danger" type="button">
                   <i class="fas fa-power-off me-2 font-weight-bold"></i> {{ __('translate.Clear') }}
                </button>
              </div>
            </div>


          </form>

        </div>

      </div>
    </div>
  </div>

  <!-- Modal Show_payment -->
  <div class="modal fade" id="Show_payment" tabindex="-1" role="dialog" aria-labelledby="Show_payment"
    aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">{{ __('translate.Show_Payments') }}</h5>
         <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-md-12 mt-3">
              <div class="table-responsive">
                <table class="table table-hover table-bordered table-md">
                  <thead>
                    <tr>
                      <th scope="col">{{ __('translate.date') }}</th>
                      <th scope="col">{{ __('translate.Reference') }}</th>
                      <th scope="col">{{ __('translate.Amount') }}</th>
                      <th scope="col">{{ __('translate.Action') }}</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-if="payments.length <= 0">
                      <td colspan="5">{{ __('translate.No_data_Available') }}</td>
                    </tr>
                    <tr v-for="payment in payments">
                      <td>@{{payment.date}}</td>
                      <td>@{{payment.Ref}}</td>
                      <td>{{$currency}} @{{formatNumber(payment.amount,2)}}</td>
                      <td>
                        <div role="group" aria-label="Basic example" class="btn-group">
                          @can('payment_purchases_edit')
                          <span title="Edit" class="btn btn-icon btn-success btn-sm" @click="Edit_Payment(payment)">
                            <i class="far fa-edit"></i>
                          </span>
                          @endcan
                          
                          @can('payment_purchases_delete')
                          <span title="Delete" class="btn btn-icon btn-danger btn-sm" @click="Remove_Payment(payment.id)">
                            <i class="far fa-times-circle"></i>
                          </span>
                          @endcan
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
    </div>
  </div>

  <!-- Modal add_payment -->
  <validation-observer ref="Add_payment">
    <div class="modal fade" id="Add_Payment" tabindex="-1" role="dialog" aria-labelledby="Add_Payment"
      aria-hidden="true">
      <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 v-if="EditPaymentMode" class="modal-title">{{ __('translate.Edit') }}</h5>
            <h5 v-else class="modal-title">{{ __('translate.Create') }}</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <form @submit.prevent="Submit_Payment()">
              <div class="row">

                <div class="col-md-6">
                    <validation-provider name="date" rules="required" v-slot="validationContext">
                      <div class="form-group">
                        <label for="picker3">{{ __('translate.Date') }}</label>
    
                        <input type="text" 
                          :state="getValidationState(validationContext)" 
                          aria-describedby="date-feedback" 
                          class="form-control" 
                          placeholder="{{ __('translate.Select_Date') }}"  
                          id="datetimepicker" 
                          v-model="payment.date">
    
                        <span class="error">@{{  validationContext.errors[0] }}</span>
                      </div>
                    </validation-provider>
                  </div>

                <!-- Paying_Amount -->
                <div class="form-group col-md-6">
                  <validation-provider name="Paying Amount" :rules="{ required: true , regex: /^\d*\.?\d*$/}"
                    v-slot="validationContext">
                    <label for="Paying_Amount">{{ __('translate.Paying_Amount') }}
                      <span class="field_required">*</span></label>
                    <input @keyup="Verified_paidAmount(payment.amount)" :state="getValidationState(validationContext)"
                      aria-describedby="Paying_Amount-feedback" v-model.number="payment.amount"
                      placeholder="{{ __('translate.Paying_Amount') }}" type="text" class="form-control">
                    <div class="error">@{{ validationContext.errors[0] }}</div>
                  </validation-provider>
                </div>

                <div class="form-group col-md-6">
                      <validation-provider name="Payment Method" rules="required"
                          v-slot="{ valid, errors }">
                          <label> {{ __('translate.Payment_choice') }}<span
                                  class="field_required">*</span></label>
                          <v-select @input="Selected_Payment_Method" 
                                placeholder="{{ __('translate.Choose_Payment_Choice') }}"
                              :class="{'is-invalid': !!errors.length}"
                              :state="errors[0] ? false : (valid ? true : null)"
                              v-model="payment.payment_method_id" :reduce="(option) => option.value" 
                              :options="payment_methods.map(payment_methods => ({label: payment_methods.title, value: payment_methods.id}))">

                          </v-select>
                          <span class="error">@{{ errors[0] }}</span>
                      </validation-provider>
                  </div>

                <div class="form-group col-md-12">
                  <label for="note">{{ __('translate.Please_provide_any_details') }}
                  </label>
                  <textarea type="text" v-model="payment.notes" class="form-control" name="note" id="note"
                    placeholder="{{ __('translate.Please_provide_any_details') }}"></textarea>
                </div>

                <div class="col-lg-6">
                  <button type="submit" class="btn btn-primary" :disabled="paymentProcessing">
                    <span v-if="paymentProcessing" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> <i class="far fa-check-circle me-2 font-weight-bold"></i>
                    {{ __('translate.Submit') }}
                  </button>

                </div>

              </div>

            </form>
          </div>
        </div>
      </div>
    </div>
  </validation-observer>

</div>
@endsection

@section('page-js')

<script src="{{asset('assets/vendor/datatables.min.js')}}"></script>
<script src="{{asset('assets/vendor/flatpickr.min.js')}}"></script>
<script src="{{asset('assets/vendor/datepicker.min.js')}}"></script>


<script type="text/javascript">
    $(function () {
        "use strict";

        $(document).ready(function () {
            flatpickr("#datetimepicker", {
                enableTime: true,
                dateFormat: "Y-m-d H:i"
            });

            $("#start_date,#end_date").datepicker({
                format: 'yyyy-mm-dd',
                changeMonth: true,
                changeYear: true,
                autoclose: true,
                todayHighlight: true,
            });

            var end_date = new Date();
            var start_date = new Date();

            end_date.setDate(end_date.getDate() + 365);
            $("#end_date").datepicker("setDate" , end_date);

            start_date.setDate(start_date.getDate() - 365);
            $("#start_date").datepicker("setDate" , start_date);

            //init datatable
            purchase_datatable();
        });


        //Get Data
        function purchase_datatable(start_date ='', end_date ='', Ref ='', supplier_id ='',warehouse_id ='', payment_status =''){
            var table = $('#purchase_table').DataTable({
                processing: true,
                serverSide: true,
                "order": [[ 0, "desc" ]],
                    'columnDefs': [
                        {
                            'targets': [0],
                            'visible': false,
                            'searchable': false,
                        },
                        {
                            'targets': [3,4,5,6,7],
                            "orderable": false,
                        },
                    ],
                ajax: {
                    url: "{{ route('get_purchases_datatable') }}",
                    data: {
                        start_date: start_date === null?'':start_date,
                        end_date: end_date === null?'':end_date,
                        Ref: Ref === null?'':Ref,
                        supplier_id: supplier_id == '0'?'':supplier_id,
                        warehouse_id: warehouse_id == '0'?'':warehouse_id,
                        payment_status: payment_status == '0'?'':payment_status,
                        "_token": "{{ csrf_token()}}"
                    },
                    dataType: "json",
                    type:"post"
                },
                columns: [
                    {data: 'id', className: "d-none"},
                    {data: 'date'},
                    {data: 'Ref'},
                    {data: 'supplier_name'},
                    {data: 'warehouse_name'},
                    {data: 'GrandTotal'},
                    //{data: 'paid_amount'},
                    //{data: 'due'},
                    {data: 'payment_status'},
                    {data: 'action'},
                
                ],
            
                lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
                dom: "<'row'<'col-sm-12 col-md-7'lB><'col-sm-12 col-md-5 p-0'f>>rtip",
                oLanguage: {
                    sEmptyTable: "{{ __('datatable.sEmptyTable') }}",
                    sInfo: "{{ __('datatable.sInfo') }}",
                    sInfoEmpty: "{{ __('datatable.sInfoEmpty') }}",
                    sInfoFiltered: "{{ __('datatable.sInfoFiltered') }}",
                    sInfoThousands: "{{ __('datatable.sInfoThousands') }}",
                    sLengthMenu: "_MENU_", 
                    sLoadingRecords: "{{ __('datatable.sLoadingRecords') }}",
                    sProcessing: "{{ __('datatable.sProcessing') }}",
                    sSearch: "",
                    sSearchPlaceholder: "{{ __('datatable.sSearchPlaceholder') }}",
                    oPaginate: {
                        sFirst: "{{ __('datatable.oPaginate.sFirst') }}",
                        sLast: "{{ __('datatable.oPaginate.sLast') }}",
                        sNext: "{{ __('datatable.oPaginate.sNext') }}",
                        sPrevious: "{{ __('datatable.oPaginate.sPrevious') }}",
                    },
                    oAria: {
                        sSortAscending: "{{ __('datatable.oAria.sSortAscending') }}",
                        sSortDescending: "{{ __('datatable.oAria.sSortDescending') }}",
                    }
                },
                buttons: [
                    {
                        extend: 'collection',
                        text: "{{ __('translate.EXPORT') }}",
                        buttons: [
                          {
                            extend: 'print',
                            text: 'Print',
                            exportOptions: {
                                columns: ':visible:Not(.not_show)',
                                rows: ':visible'
                            },
                            title: function(){
                                return 'Purchases List';
                            },
                          },
                          {
                            extend: 'pdf',
                            text: 'Pdf',
                            exportOptions: {
                                columns: ':visible:Not(.not_show)',
                                rows: ':visible'
                            },
                            title: function(){
                                return 'Purchases List';
                            },
                          },
                          {
                            extend: 'excel',
                            text: 'Excel',
                            exportOptions: {
                                columns: ':visible:Not(.not_show)',
                                rows: ':visible'
                            },
                            title: function(){
                                return 'Purchases List';
                            },
                          },
                          {
                            extend: 'csv',
                            text: 'Csv',
                            exportOptions: {
                                columns: ':visible:Not(.not_show)',
                                rows: ':visible'
                            },
                            title: function(){
                                return 'Purchases List';
                            },
                          },
                        ]
                    }]
            });
        }

        // Clear Filter
        $('#Clear_Form').on('click' , function (e) {
            var end_date = new Date();
            var start_date = new Date();

            end_date.setDate(end_date.getDate() + 365);
            $("#end_date").datepicker("setDate" , end_date);

            start_date.setDate(start_date.getDate() - 365);
            $("#start_date").datepicker("setDate" , start_date);

            var Ref = $('#Ref').val('');
            var payment_status = $('#payment_status').val('0');
            let supplier_id = $('#supplier_id').val('0');
            let warehouse_id = $('#warehouse_id').val('0');
        });


        // Show Modal Filter
        $('#Show_Modal_Filter').on('click' , function (e) {
            $('#filter_purchase_modal').modal('show');
        });

        // Submit Filter
        $('#filter_purchase').on('submit' , function (e) {
            e.preventDefault();
            var start_date = $('#start_date').val();
            var end_date = $('#end_date').val();
            var Ref = $('#Ref').val();
            var payment_status = $('#payment_status').val();
            let supplier_id = $('#supplier_id').val();
            let warehouse_id = $('#warehouse_id').val();
      
            $('#purchase_table').DataTable().destroy();
            purchase_datatable(start_date, end_date, Ref, supplier_id ,warehouse_id , payment_status);

            $('#filter_purchase_modal').modal('hide');
        });

        //email purchase
        $(document).on('click', '.send_email', function () {
            var id = $(this).attr('id');
            app.SendEmail(id);
        });

        //Invoice_PDF
        $(document).on('click', '.download_pdf', function () {
            var id = $(this).attr('id');
            var Ref = $(this).attr('Ref');
            app.Invoice_PDF(Ref , id);
        });
       
        //Delete purchase
        $(document).on('click', '.delete', function () {
            var id = $(this).attr('id');
            app.Remove_Purchase(id);
        });

        // event delete purchase (reload Datatatble)
        $(document).bind('Delete_Purchase', function (e) {
            $('#purchase_table').DataTable().destroy();
            purchase_datatable();
        });


        //Show_Payments
        $(document).on('click', '.Show_Payments', function () {
            var id = $(this).attr('id');
            app.Show_Payments(id);
        });

        //New_Payment
        $(document).on('click', '.New_Payment', function () {
            var id = $(this).attr('id');
            var payment_status = $(this).attr('payment_status');
            app.New_Payment(payment_status , id);
        });

        // event Create_Function_purchase
        $(document).bind('Create_Function_purchase', function (e) {
            $('#Add_Payment').modal('hide');
            $('#purchase_table').DataTable().destroy();
            purchase_datatable();
            NProgress.done();
        });

        // event Update_Function_purchase
        $(document).bind('Update_Function_purchase', function (e) {
            $('#Add_Payment').modal('hide');
            $('#Show_payment').modal('hide');
            $('#purchase_table').DataTable().destroy();
            purchase_datatable();
            NProgress.done();
        });
     
        // event Delete_Function_purchase
        $(document).bind('Delete_Function_purchase', function (e) {
            $('#Show_payment').modal('hide');
            $('#purchase_table').DataTable().destroy();
            purchase_datatable();
        });
    });
</script>

<script>
    Vue.component('v-select', VueSelect.VueSelect)
    Vue.component('validation-provider', VeeValidate.ValidationProvider);
    Vue.component('validation-observer', VeeValidate.ValidationObserver);

    var app = new Vue({
        el: '#section_purchase_list',
        data: {
            editmode: false,
            EditPaymentMode: false,
            SubmitProcessing:false,
            paymentProcessing: false,
            Submit_Processing_shipment: false,
            payment_methods:[],
            accounts:[],
            purchases: [], 
            shipment: {},
            purchase: {},
            purchase_due:'',
            due:0,
            setting: {
                logo: "",
                CompanyName: "",
                CompanyAdress: "",
                email: "",
                CompanyPhone: ""
            },
            payments: [],
            payment: {},
            Purchase_id: "",
        },
        methods: {
            Selected_Payment_Method(value) {
                if (value === null) {
                    this.payment.payment_method_id = "";
                }
            },
            // keyup paid Amount
            Verified_paidAmount() {
                if (isNaN(this.payment.amount)) {
                  this.payment.amount = 0;

                } else if (this.payment.amount > this.due) {
                  toastr.error('{{ __('translate.Paying_amount_is_greater_than_Grand_Total') }}');
                  this.payment.amount = 0;
                }
            },
            // Validate Form Submit_Payment
            Submit_Payment() {
                this.$refs.Add_payment.validate().then(success => {
                    if (!success) {
                        toastr.error('{{ __('translate.Please_fill_the_form_correctly') }}');
                    }
                    else if (this.payment.amount > this.due) {
                        toastr.error('{{ __('translate.Paying_amount_is_greater_than_Grand_Total') }}');
                        this.payment.amount = 0;
                    }
                    else if (!this.EditPaymentMode) {
                        this.Create_Payment();
                    } 
                    else {
                        this.Update_Payment();
                    }
                });
            },
            getValidationState({ dirty, validated, valid = null }) {
                return dirty || validated ? valid : null;
            },
            Payment_Purchase_PDF(payment, id) {
                NProgress.start();
                NProgress.set(0.1);
              
                axios
                .get("{{ url('Payment_Purchase_PDF') }}/" + id, {
                    responseType: "blob",
                    headers: {
                      "Content-Type": "application/json"
                    }
                })
                .then(response => {
                    const url = window.URL.createObjectURL(new Blob([response.data]));
                    const link = document.createElement("a");
                    link.href = url;
                    link.setAttribute("download", "Payment_" + payment.Ref + ".pdf");
                    document.body.appendChild(link);
                    link.click();

                    setTimeout(() => NProgress.done(), 500);
                })
                .catch(() => {
                    setTimeout(() => NProgress.done(), 500);
                });
            },
            Send_Email_Payment(id) {
                NProgress.start();
                NProgress.set(0.1);
                axios
                .post("{{ url('payment/purchase/send/email') }}", {
                    id: id,
                })
                .then(response => {
                    setTimeout(() => NProgress.done(), 500);
                    toastr.success('{{ __('translate.sent_in_successfully') }}');
                })
                .catch(error => {
                    setTimeout(() => NProgress.done(), 500);
                    toastr.error('{{ __('translate.There_was_something_wronge') }}');
                });
            },
            New_Payment(payment_status , id) {
                if (payment_status == "paid") {
                    toastr.error('{{ __('translate.Payment_Complete') }}');
                } 
                else {
                    NProgress.start();
                    NProgress.set(0.1);
                    this.reset_form_payment();
                    this.EditPaymentMode = false;
                    this.get_data_create(id);
                }
            },
            get_data_create(id) {
                axios.get("{{ url('payment/purchase/get_data_create') }}/" + id)
                .then(response => {
                    this.payment.amount    = response.data.due;
                    this.Purchase_id       = id;
                    this.due               = parseFloat(response.data.due);
                    this.payment.date      = moment().format('YYYY-MM-DD HH:mm');

                    this.payment_methods   = response.data.payment_methods;
                    this.accounts          = response.data.accounts;
                    
                    setTimeout(() => {
                        NProgress.done();
                        $('#Add_Payment').modal('show');
                    }, 1000);
                })
                .catch(() => {
                    setTimeout(() => NProgress.done(), 500);
                });
            },
            Edit_Payment(payment) {
                NProgress.start();
                NProgress.set(0.1);
                this.reset_form_payment();
                this.EditPaymentMode   = true;
                this.payment.id        = payment.id;
                this.payment.date      = payment.date;
                this.payment.payment_method_id = payment.payment_method_id;
                this.payment.account_id = payment.account_id;
                this.payment.amount   = payment.amount;
                this.payment.notes     = payment.notes;
                this.due               = parseFloat(this.purchase_due) + payment.amount;
                setTimeout(() => {
                    NProgress.done();
                    $('#Add_Payment').modal('show');
                }, 1000);
            },
            Get_Payments(id) {
                axios.get("{{ url('purchase/purchases/payments') }}/" + id)
                .then(response => {
                    this.payments = response.data.payments;
                    this.purchase_due  = response.data.due;
                    this.payment_methods   = response.data.payment_methods;
                    this.accounts          = response.data.accounts;

                    setTimeout(() => {
                        NProgress.done();
                        $('#Show_payment').modal('show');
                    }, 1000);
                })
                .catch(() => {
                    setTimeout(() => NProgress.done(), 500);
                });
            },
            Show_Payments(id) {
                NProgress.start();
                NProgress.set(0.1);
                this.reset_form_payment();
                this.Purchase_id  = id;
                this.Get_Payments(id);
            },
            Create_Payment() {
                this.paymentProcessing = true;
                NProgress.start();
                NProgress.set(0.1);
              
                axios
                .post("{{ url('payment/purchase') }}", {
                    purchase_id: this.Purchase_id,
                    date: this.payment.date,
                    amount: parseFloat(this.payment.amount).toFixed(2),
                    payment_method_id: this.payment.payment_method_id,
                    account_id: this.payment.account_id,
                    notes: this.payment.notes,
                })
                .then(response => {
                    this.paymentProcessing = false;
                    $.event.trigger('Create_Function_purchase');
                    toastr.success('{{ __('translate.Created_in_successfully') }}');
                })
                .catch(error => {
                    toastr.error('{{ __('translate.There_was_something_wronge') }}');
                    this.paymentProcessing = false;
                    NProgress.done();
                });
            },
            Update_Payment() {
                this.paymentProcessing = true;
                NProgress.start();
                NProgress.set(0.1);
              
                axios
                .put("{{ url('payment/purchase') }}/" + this.payment.id, {
                    purchase_id: this.Purchase_id,
                    date: this.payment.date,
                    payment_method_id: this.payment.payment_method_id,
                    account_id: this.payment.account_id,
                    amount: parseFloat(this.payment.amount).toFixed(2),
                    notes: this.payment.notes
                })
                .then(response => {
                    this.paymentProcessing = false;
                    $.event.trigger('Update_Function_purchase');
                    toastr.success('{{ __('translate.Updated_in_successfully') }}');  
                })
                .catch(error => {
                    toastr.error('{{ __('translate.There_was_something_wronge') }}');
                    this.paymentProcessing = false;
                    NProgress.done();
                });
            },
            Remove_Payment(id) {
                swal({
                    title: '{{ __('translate.Are_you_sure') }}',
                    text: '{{ __('translate.You_wont_be_able_to_revert_this') }}',
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#0CC27E',
                    cancelButtonColor: '#FF586B',
                    confirmButtonText: '{{ __('translate.Yes_delete_it') }}',
                    cancelButtonText: '{{ __('translate.No_cancel') }}',
                    confirmButtonClass: 'btn btn-primary me-5',
                    cancelButtonClass: 'btn btn-danger',
                    buttonsStyling: false
                }).then(function () {
                    axios
                    .delete("{{ url('payment/purchase') }}/" + id)
                    .then(() => {
                        $.event.trigger('Delete_Function_purchase');
                        toastr.success('{{ __('translate.Deleted_in_successfully') }}');
                    })
                    .catch(() => {
                        toastr.error('{{ __('translate.There_was_something_wronge') }}');
                    });
                }).catch(() => {
                });
            },
            reset_form_payment() {
                this.due = 0;
                this.payment = {
                    id: "",
                    purchase_id: "",
                    date: "",
                    Ref: "",
                    amount: "",
                    received_amount: "",
                    payment_method_id: "",
                    account_id: "",
                    notes: "",
                };
            },
            formatNumber(number, dec) {
                const value = (typeof number === "string"
                    ? number
                    : number.toString()
                ).split(".");
                if (dec <= 0) return value[0];
                let formated = value[1] || "";
                if (formated.length > dec)
                    return `${value[0]}.${formated.substr(0, dec)}`;
                while (formated.length < dec) formated += "0";
                return `${value[0]}.${formated}`;
            },
            SendEmail(id) {
                NProgress.start();
                NProgress.set(0.1);
                axios
                .post("{{ url('purchase/purchases/send/email') }}", {
                    id: id,
                })
                .then(response => {
                    setTimeout(() => NProgress.done(), 500);
                    toastr.success('{{ __('translate.sent_in_successfully') }}');
                })
                .catch(error => {
                    setTimeout(() => NProgress.done(), 500);
                    toastr.error('{{ __('translate.There_was_something_wronge') }}');
                });
            },
            Invoice_PDF(Ref , id) {
                NProgress.start();
                NProgress.set(0.1);
                
                axios
                .get("{{ url('Purchase_PDF') }}/"+ id, {
                    responseType: "blob",
                    headers: {
                        "Content-Type": "application/json"
                    }
                })
                .then(response => {
                    const url = window.URL.createObjectURL(new Blob([response.data]));
                    const link = document.createElement("a");
                    link.href = url;
                    link.setAttribute("download", "Purchase_" + Ref + ".pdf");
                    document.body.appendChild(link);
                    link.click();

                    setTimeout(() => NProgress.done(), 500);
                    })
                .catch(() => {
                    setTimeout(() => NProgress.done(), 500);
                });
            },
            Remove_Purchase(id) {
                swal({
                    title: '{{ __('translate.Are_you_sure') }}',
                    text: '{{ __('translate.You_wont_be_able_to_revert_this') }}',
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#0CC27E',
                    cancelButtonColor: '#FF586B',
                    confirmButtonText: '{{ __('translate.Yes_delete_it') }}',
                    cancelButtonText: '{{ __('translate.No_cancel') }}',
                    confirmButtonClass: 'btn btn-primary me-5',
                    cancelButtonClass: 'btn btn-danger',
                    buttonsStyling: false
                }).then(function () {
                    axios
                    .delete("{{ url('purchase/purchases') }}/" + id)
                    .then(() => {
                        $.event.trigger('Delete_Purchase');
                        toastr.success('{{ __('translate.Deleted_in_successfully') }}');

                    })
                    .catch(() => {
                        toastr.error('{{ __('translate.There_was_something_wronge') }}');
                    });
                })
                .catch(() => {
                });
            },
        },
        //-----------------------------Autoload function-------------------
        created() {
        }
    })
</script>

@endsection