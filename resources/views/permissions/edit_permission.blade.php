@extends('layouts.master')
@section('main-content')
@section('page-css')
@endsection

<div class="breadcrumb">
  <h1>{{ __('translate.Edit_Permissions') }}</h1>
</div>

<div class="separator-breadcrumb border-top"></div>

<div class="row" id="section_Permission_Edit">
  <div class="col-lg-12 mb-3">
    <div class="card">

      <!--begin::form-->
      <form @submit.prevent="Update_Permission()">
         <div class="card-body">

            <div class="row">

              <div class="col-md-6">
                <label for="name">{{ __('translate.Role_Name') }} <span class="field_required">*</span></label>
                <input type="text" v-model="role.name" required name="role_name" class="form-control" name="name" id="name"
                  placeholder="{{ __('translate.Enter_Role_Name') }}">

              </div>

              <div class="col-md-6">
                <label for="description">{{ __('translate.Description') }}</label>
                <input type="text" v-model="role.description" name="role_description" class="form-control" name="description" id="description"
                  placeholder="{{ __('translate.Enter_description') }}">
              </div>
            </div>

            <div class="row mt-4">
              <div class="col-md-12">
                <div class="table-responsive">
                <table class="table table-bordered table_permissions">
                  <tbody>

                    <tr>
                      <th>{{ __('translate.Dashboard') }}</th>
                      <td>
                        <div class="pt-3">

                          <div class="form-check form-check-inline w-100">
                            <label class="checkbox checkbox-primary" for="dashboard">
                              <input type="checkbox" v-model="permissions" id="dashboard"
                                value="dashboard"><span>{{ __('translate.Dashboard') }}</span><span class="checkmark"></span>
                            </label>
                          </div>

                        </div>
                      </td>
                    </tr>

                    <tr>
                      <th>{{ __('translate.Users') }}</th>
                      <td>
                        <div class="pt-3">
                          <div class="form-check form-check-inline w-100">
                            <label class="checkbox checkbox-primary" for="user_view">
                              <input type="checkbox" v-model="permissions" id="user_view" value="user_view"><span>{{ __('translate.View user') }}</span><span class="checkmark"></span>
                            </label>
                          </div>

                          <div class="form-check form-check-inline w-100">
                            <label class="checkbox checkbox-primary" for="user_add">
                              <input type="checkbox" v-model="permissions" id="user_add" value="user_add"><span>{{ __('translate.Add user') }}</span><span class="checkmark"></span>
                            </label>
                          </div>

                          <div class="form-check form-check-inline w-100">
                            <label class="checkbox checkbox-primary" for="user_edit">
                              <input type="checkbox" v-model="permissions" id="user_edit" value="user_edit"><span>{{ __('translate.Edit user') }}</span><span class="checkmark"></span>
                            </label>
                          </div>

                          <div class="form-check form-check-inline w-100">
                            <label class="checkbox checkbox-primary" for="user_delete">
                              <input type="checkbox" v-model="permissions" id="user_delete"
                                value="user_delete"><span>{{ __('translate.Delete user') }}</span><span class="checkmark"></span>
                            </label>
                          </div>

                        </div>
                      </td>
                    </tr>

                    <tr>
                        <th>{{ __('translate.Roles') }}</th>
                        <td>
                          <div class="pt-3">
                            <div class="form-check form-check-inline w-100">
                              <label class="checkbox checkbox-primary" for="group_permission">
                                <input type="checkbox" v-model="permissions" id="group_permission"
                                  value="group_permission"><span>{{ __('translate.Roles') }}</span><span class="checkmark"></span>
                              </label>
                            </div>
  
                          </div>
                        </td>
                      </tr>

                    <tr>
                      <th>{{ __('translate.Products') }}</th>
                      <td>
                        <div class="pt-3">


                          <div class="form-check form-check-inline w-100">
                            <label class="checkbox checkbox-primary" for="products_view">
                              <input type="checkbox" v-model="permissions" id="products_view"
                                value="products_view"><span>{{ __('translate.View Product') }}</span><span class="checkmark"></span>
                            </label>
                          </div>

                          <div class="form-check form-check-inline w-100">
                            <label class="checkbox checkbox-primary" for="products_add">
                              <input type="checkbox" v-model="permissions" id="products_add"
                                value="products_add"><span>{{ __('translate.Add Product') }}</span><span class="checkmark"></span>
                            </label>
                          </div>

                          <div class="form-check form-check-inline w-100">
                            <label class="checkbox checkbox-primary" for="products_edit">
                              <input type="checkbox" v-model="permissions" id="products_edit"
                                value="products_edit"><span>{{ __('translate.Edit Product') }}</span><span class="checkmark"></span>
                            </label>
                          </div>

                          <div class="form-check form-check-inline w-100">
                            <label class="checkbox checkbox-primary" for="products_delete">
                              <input type="checkbox" v-model="permissions" id="products_delete"
                                value="products_delete"><span>{{ __('translate.Delete Product') }}</span><span class="checkmark"></span>
                            </label>
                          </div>

                          <div class="form-check form-check-inline w-100">
                            <label class="checkbox checkbox-primary" for="print_labels">
                              <input type="checkbox" v-model="permissions" id="print_labels"
                                value="print_labels"><span>{{ __('translate.Print Labels') }}</span><span class="checkmark"></span>
                            </label>
                          </div>

                        </div>
                      </td>
                    </tr>

                    <tr>
                      <th>{{ __('translate.Category') }}</th>
                      <td>
                        <div class="pt-3">
                          <div class="form-check form-check-inline w-100">
                            <label class="checkbox checkbox-primary" for="category">
                              <input type="checkbox" v-model="permissions" id="category"
                                value="category"><span>{{ __('translate.Category') }}</span><span class="checkmark"></span>
                            </label>
                          </div>

                        </div>
                      </td>
                    </tr>

                    <tr>
                      <th>{{ __('translate.Brand') }}</th>
                      <td>
                        <div class="pt-3">
                          <div class="form-check form-check-inline w-100">
                            <label class="checkbox checkbox-primary" for="brand">
                              <input type="checkbox" v-model="permissions" id="brand"
                                value="brand"><span>{{ __('translate.Brand') }}</span><span class="checkmark"></span>
                            </label>
                          </div>

                        </div>
                      </td>

                    <tr>
                      <th>{{ __('translate.Unit') }}</th>
                      <td>
                        <div class="pt-3">
                          <div class="form-check form-check-inline w-100">
                            <label class="checkbox checkbox-primary" for="unit">
                              <input type="checkbox" v-model="permissions" id="unit" value="unit"><span>{{ __('translate.Unit') }}</span><span
                                class="checkmark"></span>
                            </label>
                          </div>

                        </div>
                      </td>
                    </tr>

                    <tr>
                      <th>{{ __('translate.Warehouse') }}</th>
                      <td>
                        <div class="pt-3">
                          <div class="form-check form-check-inline w-100">
                            <label class="checkbox checkbox-primary" for="warehouse">
                              <input type="checkbox" v-model="permissions" id="warehouse"
                                value="warehouse"><span>{{ __('translate.Warehouse') }}</span><span class="checkmark"></span>
                            </label>
                          </div>

                        </div>
                      </td>
                    </tr>

                    <tr>
                      <th>{{ __('translate.Adjustments') }}</th>
                      <td>
                        <div class="pt-3">

                          <label class="radio radio-primary">
                            <input type="radio" name="radio_option[adjustment_view]" value="adjustment_view_all">
                            <span>{{ __('translate.View all Adjustments') }}</span><span class="checkmark"></span>
                          </label>

                          <label class="radio radio-primary">
                            <input type="radio" name="radio_option[adjustment_view]" value="adjustment_view_own">
                            <span>{{ __('translate.View own Adjustments') }}</span><span class="checkmark"></span>
                          </label>

                          <div class="form-check form-check-inline w-100">
                            <label class="checkbox checkbox-primary" for="adjustment_add">
                              <input type="checkbox" v-model="permissions" id="adjustment_add"
                                value="adjustment_add"><span>{{ __('translate.Add Adjustment') }}</span><span class="checkmark"></span>
                            </label>
                          </div>

                          <div class="form-check form-check-inline w-100">
                            <label class="checkbox checkbox-primary" for="adjustment_edit">
                              <input type="checkbox" v-model="permissions" id="adjustment_edit"
                                value="adjustment_edit"><span>{{ __('translate.Edit Adjustment') }}</span><span class="checkmark"></span>
                            </label>
                          </div>
                          <div class="form-check form-check-inline w-100">
                            <label class="checkbox checkbox-primary" for="adjustment_delete">
                              <input type="checkbox" v-model="permissions" id="adjustment_delete"
                                value="adjustment_delete"><span>{{ __('translate.Delete Adjustment') }}</span><span class="checkmark"></span>
                            </label>
                          </div>
                          <div class="form-check form-check-inline w-100">
                            <label class="checkbox checkbox-primary" for="adjustment_details">
                              <input type="checkbox" v-model="permissions" id="adjustment_details"
                                value="adjustment_details"><span>{{ __('translate.Adjustment details') }}</span><span class="checkmark"></span>
                            </label>
                          </div>
                        </div>
                      </td>
                    </tr>

                    <tr>
                      <th>{{ __('translate.transfers') }}</th>
                      <td>
                        <div class="pt-3">

                          <label class="radio radio-primary">
                            <input type="radio" name="radio_option[transfer_view]" value="transfer_view_all">
                            <span>{{ __('translate.View all Transfers') }}</span><span class="checkmark"></span>
                          </label>

                          <label class="radio radio-primary">
                            <input type="radio" name="radio_option[transfer_view]" value="transfer_view_own">
                            <span>{{ __('translate.View own Transfers') }}</span><span class="checkmark"></span>
                          </label>

                          <div class="form-check form-check-inline w-100">
                            <label class="checkbox checkbox-primary" for="transfer_add">
                              <input type="checkbox" v-model="permissions" id="transfer_add"
                                value="transfer_add"><span>{{ __('translate.Add Transfer') }}</span><span class="checkmark"></span>
                            </label>
                          </div>
                          <div class="form-check form-check-inline w-100">
                            <label class="checkbox checkbox-primary" for="transfer_edit">
                              <input type="checkbox" v-model="permissions" id="transfer_edit"
                                value="transfer_edit"><span>{{ __('translate.Edit Transfer') }}</span><span class="checkmark"></span>
                            </label>
                          </div>
                          <div class="form-check form-check-inline w-100">
                            <label class="checkbox checkbox-primary" for="transfer_delete">
                              <input type="checkbox" v-model="permissions" id="transfer_delete"
                                value="transfer_delete"><span>{{ __('translate.Delete Transfer') }}</span><span class="checkmark"></span>
                            </label>
                          </div>
                        </div>
                      </td>
                    </tr>

                    <tr>
                      <th>{{ __('translate.Sales') }}</th>
                      <td>
                        <div class="pt-3">

                          <label class="radio radio-primary">
                              <input type="radio" name="radio_option[sales_view]" value="sales_view_all">
                              <span>{{ __('translate.View all Sales') }}</span><span class="checkmark"></span>
                          </label>
    
                          <label class="radio radio-primary">
                              <input type="radio" name="radio_option[sales_view]" value="sales_view_own">
                              <span>{{ __('translate.View own Sales') }}</span><span class="checkmark"></span>
                          </label>

                          <div class="form-check form-check-inline w-100">
                            <label class="checkbox checkbox-primary" for="sales_add">
                              <input type="checkbox" v-model="permissions" id="sales_add" value="sales_add"><span>{{ __('translate.Add Sell') }}</span><span class="checkmark"></span>
                            </label>
                          </div>
                          <div class="form-check form-check-inline w-100">
                            <label class="checkbox checkbox-primary" for="sales_edit">
                              <input type="checkbox" v-model="permissions" id="sales_edit" value="sales_edit"><span>{{ __('translate.Edit Sell') }}</span><span class="checkmark"></span>
                            </label>
                          </div>
                          <div class="form-check form-check-inline w-100">
                            <label class="checkbox checkbox-primary" for="sales_delete">
                              <input type="checkbox" v-model="permissions" id="sales_delete"
                                value="sales_delete"><span>{{ __('translate.Delete Sell') }}</span><span class="checkmark"></span>
                            </label>
                          </div>
                          <div class="form-check form-check-inline w-100">
                            <label class="checkbox checkbox-primary" for="sales_details">
                              <input type="checkbox" v-model="permissions" id="sales_details"
                                value="sales_details"><span>{{ __('translate.Sell details') }}</span><span class="checkmark"></span>
                            </label>
                          </div>
                        </div>
                      </td>
                    </tr>

                    <tr>
                      <th>{{ __('translate.Purchases') }}</th>
                      <td>
                        <div class="pt-3">

                          <label class="radio radio-primary">
                              <input type="radio" name="radio_option[purchases_view]" value="purchases_view_all">
                              <span>{{ __('translate.View all Purchases') }}</span><span class="checkmark"></span>
                          </label>
      
                          <label class="radio radio-primary">
                              <input type="radio" name="radio_option[purchases_view]" value="purchases_view_own">
                              <span>{{ __('translate.View own Purchases') }}</span><span class="checkmark"></span>
                          </label>
                
                          <div class="form-check form-check-inline w-100">
                            <label class="checkbox checkbox-primary" for="purchases_add">
                              <input type="checkbox" v-model="permissions" id="purchases_add"
                                value="purchases_add"><span>{{ __('translate.Add Purchase') }}</span><span class="checkmark"></span>
                            </label>
                          </div>
                          <div class="form-check form-check-inline w-100">
                            <label class="checkbox checkbox-primary" for="purchases_edit">
                              <input type="checkbox" v-model="permissions" id="purchases_edit"
                                value="purchases_edit"><span>{{ __('translate.Edit Purchase') }}</span><span class="checkmark"></span>
                            </label>
                          </div>
                          <div class="form-check form-check-inline w-100">
                            <label class="checkbox checkbox-primary" for="purchases_delete">
                              <input type="checkbox" v-model="permissions" id="purchases_delete"
                                value="purchases_delete"><span>{{ __('translate.Delete Purchase') }}</span><span class="checkmark"></span>
                            </label>
                          </div>

                          <div class="form-check form-check-inline w-100">
                            <label class="checkbox checkbox-primary" for="purchases_details">
                              <input type="checkbox" v-model="permissions" id="purchases_details"
                                value="purchases_details"><span>{{ __('translate.Purchase details') }}</span><span class="checkmark"></span>
                            </label>
                          </div>

                        </div>
                      </td>
                    </tr>

                    <tr>
                      <th>{{ __('translate.sales_return') }}</th>
                      <td>
                        <div class="pt-3">

                          <label class="radio radio-primary">
                              <input type="radio" name="radio_option[sale_returns_view]" value="sale_returns_view_all">
                              <span>{{ __('translate.View all Sell Return') }}</span><span class="checkmark"></span>
                          </label>
      
                          <label class="radio radio-primary">
                              <input type="radio" name="radio_option[sale_returns_view]" value="sale_returns_view_own">
                              <span>{{ __('translate.View own Sell Return') }}</span><span class="checkmark"></span>
                          </label>

                          <div class="form-check form-check-inline w-100">
                            <label class="checkbox checkbox-primary" for="sale_returns_add">
                              <input type="checkbox" v-model="permissions" id="sale_returns_add"
                                value="sale_returns_add"><span>{{ __('translate.Add Sell Return') }}</span><span class="checkmark"></span>
                            </label>
                          </div>
                          <div class="form-check form-check-inline w-100">
                            <label class="checkbox checkbox-primary" for="sale_returns_edit">
                              <input type="checkbox" v-model="permissions" id="sale_returns_edit"
                                value="sale_returns_edit"><span>{{ __('translate.Edit Sell Return') }}</span><span class="checkmark"></span>
                            </label>
                          </div>
                          <div class="form-check form-check-inline w-100">
                            <label class="checkbox checkbox-primary" for="sale_returns_delete">
                              <input type="checkbox" v-model="permissions" id="sale_returns_delete"
                                value="sale_returns_delete"><span>{{ __('translate.Delete Sell Return') }}</span><span
                                class="checkmark"></span>
                            </label>
                          </div>
                        </div>
                      </td>
                    </tr>

                    <tr>
                      <th>{{ __('translate.purchases_return') }}</th>
                      <td>
                        <div class="pt-3">

                            <label class="radio radio-primary">
                                <input type="radio" name="radio_option[purchase_returns_view]" value="purchase_returns_view_all">
                                <span>{{ __('translate.View all Purchase Return') }}</span><span class="checkmark"></span>
                            </label>
        
                            <label class="radio radio-primary">
                                <input type="radio" name="radio_option[purchase_returns_view]" value="purchase_returns_view_own">
                                <span>{{ __('translate.View own Purchase Return') }}</span><span class="checkmark"></span>
                            </label>
                        
                          <div class="form-check form-check-inline w-100">
                            <label class="checkbox checkbox-primary" for="purchase_returns_add">
                              <input type="checkbox" v-model="permissions" id="purchase_returns_add"
                                value="purchase_returns_add"><span>{{ __('translate.Add Purchase Return') }}</span><span
                                class="checkmark"></span>
                            </label>
                          </div>
                          <div class="form-check form-check-inline w-100">
                            <label class="checkbox checkbox-primary" for="purchase_returns_edit">
                              <input type="checkbox" v-model="permissions" id="purchase_returns_edit"
                                value="purchase_returns_edit"><span>{{ __('translate.Edit Purchase Return') }}</span><span
                                class="checkmark"></span>
                            </label>
                          </div>
                          <div class="form-check form-check-inline w-100">
                            <label class="checkbox checkbox-primary" for="purchase_returns_delete">
                              <input type="checkbox" v-model="permissions" id="purchase_returns_delete"
                                value="purchase_returns_delete"><span>{{ __('translate.Delete Purchase Return') }}</span><span
                                class="checkmark"></span>
                            </label>
                          </div>
                        </div>
                      </td>
                    </tr>

                    <tr>
                      <th>{{ __('translate.Supplier') }}</th>
                      <td>
                        <div class="pt-3">

                            <label class="radio radio-primary">
                                <input type="radio" name="radio_option[suppliers_view]" value="suppliers_view_all">
                                <span>{{ __('translate.View all Suppliers') }}</span><span class="checkmark"></span>
                            </label>
        
                            <label class="radio radio-primary">
                                <input type="radio" name="radio_option[suppliers_view]" value="suppliers_view_own">
                                <span>{{ __('translate.View own Suppliers') }}</span><span class="checkmark"></span>
                            </label>

                          <div class="form-check form-check-inline w-100">
                            <label class="checkbox checkbox-primary" for="suppliers_add">
                              <input type="checkbox" v-model="permissions" id="suppliers_add"
                                value="suppliers_add"><span>{{ __('translate.Add Supplier') }}</span><span class="checkmark"></span>
                            </label>
                          </div>
                          <div class="form-check form-check-inline w-100">
                            <label class="checkbox checkbox-primary" for="suppliers_edit">
                              <input type="checkbox" v-model="permissions" id="suppliers_edit"
                                value="suppliers_edit"><span>{{ __('translate.Edit Supplier') }}</span><span class="checkmark"></span>
                            </label>
                          </div>
                          <div class="form-check form-check-inline w-100">
                            <label class="checkbox checkbox-primary" for="suppliers_delete">
                              <input type="checkbox" v-model="permissions" id="suppliers_delete"
                                value="suppliers_delete"><span>{{ __('translate.Delete Supplier') }}</span><span class="checkmark"></span>
                            </label>
                          </div>
                          <div class="form-check form-check-inline w-100">
                              <label class="checkbox checkbox-primary" for="supplier_details">
                                <input type="checkbox" v-model="permissions" id="supplier_details"
                                  value="supplier_details"><span>{{ __('translate.Supplier Details') }}</span><span class="checkmark"></span>
                              </label>
                            </div>
                          </div>
                      </td>
                    </tr>

                    <tr>
                      <th>{{ __('translate.Settings') }}</th>
                      <td>
                        <div class="pt-3">

                          <div class="form-check form-check-inline w-100">
                            <label class="checkbox checkbox-primary" for="settings">
                              <input type="checkbox" v-model="permissions" id="settings"
                                value="settings"><span>{{ __('translate.Settings') }}</span><span class="checkmark"></span>
                            </label>
                          </div>

                            <div class="form-check form-check-inline w-100">
                                <label class="checkbox checkbox-primary" for="notification_template">
                                  <input type="checkbox" v-model="permissions" id="notification_template"
                                    value="notification_template"><span>{{ __('translate.Notification Template') }}</span><span class="checkmark"></span>
                                </label>
                              </div>

                          <div class="form-check form-check-inline w-100">
                            <label class="checkbox checkbox-primary" for="backup">
                              <input type="checkbox" v-model="permissions" id="backup"
                                value="backup"><span>{{ __('translate.backup') }}</span><span class="checkmark"></span>
                            </label>
                          </div>

                        </div>
                      </td>
                    </tr>

                    <tr>
                      <th>{{ __('translate.Reports') }}</th>
                      <td>
                        <div class="pt-3">

                          <div class="form-check form-check-inline w-100">
                            <label class="checkbox checkbox-primary" for="report_inventaire">
                              <input type="checkbox" v-model="permissions" id="report_inventaire"
                                value="report_inventaire"><span>{{ __('translate.Inventory report') }}</span><span class="checkmark"></span>
                            </label>
                          </div>

                          <div class="form-check form-check-inline w-100">
                            <label class="checkbox checkbox-primary" for="report_products">
                              <input type="checkbox" v-model="permissions" id="report_products"
                                value="report_products"><span>{{ __('translate.Product report') }}</span><span class="checkmark"></span>
                            </label>
                          </div>

                          <div class="form-check form-check-inline w-100">
                            <label class="checkbox checkbox-primary" for="report_fournisseurs">
                              <input type="checkbox" v-model="permissions" id="report_fournisseurs"
                                value="report_fournisseurs"><span>{{ __('translate.Supplier Report') }}</span><span class="checkmark"></span>
                            </label>
                          </div>

                          <div class="form-check form-check-inline w-100">
                            <label class="checkbox checkbox-primary" for="purchase_reports">
                              <input type="checkbox" v-model="permissions" id="purchase_reports"
                                value="purchase_reports"><span>{{ __('translate.Purchase report') }}</span><span class="checkmark"></span>
                            </label>
                          </div>

                          <div class="form-check form-check-inline w-100">
                            <label class="checkbox checkbox-primary" for="sale_reports">
                              <input type="checkbox" v-model="permissions" id="sale_reports"
                                value="sale_reports"><span>{{ __('translate.Sell report') }}</span><span class="checkmark"></span>
                            </label>
                          </div>

                          <div class="form-check form-check-inline w-100">
                            <label class="checkbox checkbox-primary" for="reports_alert_qty">
                              <input type="checkbox" v-model="permissions" id="reports_alert_qty"
                                value="reports_alert_qty"><span>{{ __('translate.Quantity Alerts Report') }}</span><span
                                class="checkmark"></span>
                            </label>
                          </div>

                        </div>
                      </td>
                    </tr>

                  </tbody>
                </table>
                </div>
              </div>
            </div>

            <div class="row mt-3">
              <div class="col-lg-6">
              <button @click="Update_Permission" class="btn btn-primary" :disabled="SubmitProcessing">
                <span v-if="SubmitProcessing" class="spinner-border spinner-border-sm" role="status"
                  aria-hidden="true"></span> <i class="far fa-check-circle me-2 font-weight-bold"></i> {{ __('translate.Submit') }}
              </button>
              </div>
            </div>
          </div>
      </form>

      <!-- end::form -->
    </div>
  </div>

</div>

@endsection

@section('page-js')


<script>
  var app = new Vue({
    el: '#section_Permission_Edit',
    data: {
        SubmitProcessing:false,
        errors:[],
        permissions: @json($permissions),
        role: @json($role),
       
    },
   
   
    methods: {

        //------------------------ Update Permissions ---------------------------\\
        Update_Permission() {
            var self = this;
            self.SubmitProcessing = true;
            axios.put("/user-management/permissions/" + self.role.id, {
                name: self.role.name,
                description: self.role.description,
                permissions: self.permissions,
               
            }).then(response => {
                    self.SubmitProcessing = false;
                    window.location.href = '/user-management/permissions'; 
                    toastr.success('{{ __('translate.Updated_in_successfully') }}');
                    self.errors = {};
            })
            .catch(error => {
                self.SubmitProcessing = false;
                if (error.response.status == 422) {
                    self.errors = error.response.data.errors;
                }
                toastr.error('{{ __('translate.There_was_something_wronge') }}');
            });
        },

     

    },
    //-----------------------------Autoload function-------------------
    created () {
      
    },

})

</script>

@endsection