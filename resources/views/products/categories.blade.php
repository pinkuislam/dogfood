@extends('layouts.master')
@section('main-content')
@section('page-css')
<link rel="stylesheet" href="{{asset('assets/vendor/datatables.min.css')}}">
@endsection

<div class="breadcrumb">
  <h1>{{ __('translate.Categories') }}</h1>
</div>

<div class="separator-breadcrumb border-top"></div>


<div class="row" id="section_Category_list">
  <div class="col-md-12">
    <div class="card">
      <div class="card-body">
        <div class="text-end mb-3">
          <a class="new_category btn btn-outline-primary btn-md m-1"><i class="fas fa-plus-circle me-2 font-weight-bold"></i>
            {{ __('translate.Create') }}</a>
        </div>

        <div class="table-responsive">
          <table id="category_table" class="display table">
            <thead>
              <tr>
                <th>ID</th>
                {{--<th>{{ __('translate.ID') }}</th>--}}
                <th>{{ __('translate.Name') }}</th>
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
  <!-- Modal Add & Edit category -->
  <div class="modal fade" id="modal_Category" tabindex="-1" role="dialog" aria-labelledby="modal_Category"
    aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 v-if="editmode" class="modal-title">{{ __('translate.Edit') }}</h5>
          <h5 v-else class="modal-title">{{ __('translate.Create') }}</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
  
          <form @submit.prevent="editmode?Update_Category():Create_Category()" enctype="multipart/form-data">
            <div class="row">
  
              {{--<div class="form-group col-md-12">
                <label for="code">{{ __('translate.Code_of_category') }}<span class="field_required">*</span></label>
                <input type="text" v-model="category.code" class="form-control" name="code" id="code"
                  placeholder="{{ __('translate.Enter_Code_category') }}">
                <span class="error" v-if="errors && errors.code">
                  @{{ errors.code[0] }}
                </span>
              </div>--}}
  
              <div class="form-group col-md-12">
                <label for="name">{{ __('translate.Name_of_category') }}<span class="field_required">*</span></label>
                <input type="text" v-model="category.name" class="form-control" name="name" id="name"
                  placeholder="{{ __('translate.Enter_name_category') }}">
                <span class="error" v-if="errors && errors.name">
                  @{{ errors.name[0] }}
                </span>
              </div>

              <div class="form-group col-md-12">
                  <label>{{ __('translate.Parent') }} </label>
                  <v-select @input="Selected_Parent" placeholder="{{ __('translate.None') }}"
                    v-model="category.parent_id" :reduce="label => label.value"
                    :options="parents.map(parents => ({label: parents.name, value: parents.id}))">
                  </v-select>

                  <span class="error" v-if="errors && errors.parent_id">
                      @{{ errors.parent_id[0] }}
                  </span>
              </div>
  
            </div>
            <div class="row mt-3">
  
              <div class="col-md-6">
                <button type="submit" class="btn btn-primary" :disabled="SubmitProcessing">
                  <span v-if="SubmitProcessing" class="spinner-border spinner-border-sm" role="status"
                    aria-hidden="true"></span> <i class="far fa-check-circle me-2 font-weight-bold"></i> {{ __('translate.Submit') }}
                </button>
              </div>
            </div>
  
  
          </form>
  
        </div>
  
      </div>
    </div>
  </div>
</div>


@endsection

@section('page-js')

<script src="{{asset('assets/vendor/datatables.min.js')}}"></script>

<script type="text/javascript">
  $(function () {
      "use strict";

        $(document).ready(function () {
          //init datatable
          Category_datatable();
        });

        //Get Data
        function Category_datatable(){
            var table = $('#category_table').DataTable({
                processing: true,
                serverSide: true,
                "order": [[ 0, "desc" ]],
                'columnDefs': [
                  {
                      'targets': [0],
                      'visible': false,
                      'searchable': false,
                  },
                ],
                ajax: "{{ route('categories.index') }}",
                columns: [
                    {data: 'id' , name: 'id',className: "d-none"},
                    //{data: 'parent', name: 'parent'},
                    {data: 'name', name: 'name'},
                    {data: 'action', name: 'action', orderable: false, searchable: false},
                
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
                            text: 'print',
                            exportOptions: {
                                columns: ':visible:Not(.not_show)',
                                rows: ':visible'
                            },
                          },
                          {
                            extend: 'pdf',
                            text: 'pdf',
                            exportOptions: {
                                columns: ':visible:Not(.not_show)',
                                rows: ':visible'
                            },
                          },
                          {
                            extend: 'excel',
                            text: 'excel',
                            exportOptions: {
                                columns: ':visible:Not(.not_show)',
                                rows: ':visible'
                            },
                          },
                          {
                            extend: 'csv',
                            text: 'csv',
                            exportOptions: {
                                columns: ':visible:Not(.not_show)',
                                rows: ':visible'
                            },
                          },
                        ]
                    }]
            });
        }

        // event reload Datatatble
        $(document).bind('event_category', function (e) {
            $('#modal_Category').modal('hide');
            $('#category_table').DataTable().destroy();
            Category_datatable();
        });


        //Create Category
        $(document).on('click', '.new_category', function () {
            NProgress.start();
            NProgress.set(0.1);
            app.editmode = false;
            app.reset_Form();
            app.Get_Data_Create();
            setTimeout(() => {
                NProgress.done()
                $('#modal_Category').modal('show');
            }, 500);
        });

        //Edit Category
        $(document).on('click', '.edit', function () {
            NProgress.start();
            NProgress.set(0.1);
            app.editmode = true;
            app.reset_Form();
            var id = $(this).attr('id');
            app.Get_Data_Edit(id);
           
            setTimeout(() => {
                NProgress.done()
                $('#modal_Category').modal('show');
            }, 500);
        });

        //Delete Category
        $(document).on('click', '.delete', function () {
            var id = $(this).attr('id');
            app.Remove_Category(id);
        });
    });
</script>

<script>
  Vue.component('v-select', VueSelect.VueSelect);
  var app = new Vue({
        el: '#section_Category_list',
        data: {
            editmode: false,
            SubmitProcessing:false,
            errors:[],
            categories: [], 
            parents: [],
            category: {
                id: "",
                name: "",
                parent_id: ""
            }
        },
       
        methods: {
            Selected_Parent(value) {
                if (value == null) {
                } else {
                }
            },

            //------------------------------ Modal  (create category) -------------------------------\\
            New_category() {
                this.reset_Form();
                this.editmode = false;
                $('#modal_Category').modal('show');
            },

            //--------------------------- reset Form ----------------\\
            reset_Form() {
                this.category = {
                    id: "",
                    name: "",
                    parent_id: ""
                };
                this.errors = {};
            },

            //---------------------------------------- Set To Strings-------------------------\\
            setToStrings() {
                // Simply replaces null values with strings=''
                if (this.category.parent_id === null) {
                    this.category.parent_id = 0;
                }
            },
          
            //---------------------- Get_Data_Create  ------------------------------\\
            Get_Data_Create() {
                axios
                .get("{{ url('products/categories/create') }}")
                .then(response => {
                    this.parents = response.data.parents;
                })
                .catch(error => {
                });
            },

            //---------------------- Get_Data_Edit  ------------------------------\\
            Get_Data_Edit(id) {
                axios
                .get("{{ url('products/categories') }}/"+id+"/edit")
                .then(response => {
                    this.category = response.data.category;
                    this.parents = response.data.parents;
                })
                .catch(error => {  
                });
            },

            //------------------------ Create_Category---------------------------\\
            Create_Category() {
                var self = this;
                self.SubmitProcessing = true;
                self.setToStrings();
                axios
                .post("{{ url('products/categories') }}", {
                    name: self.category.name,
                    parent_id: self.category.parent_id
                })
                .then(response => {
                    self.SubmitProcessing = false;
                    $.event.trigger('event_category');
                    toastr.success('{{ __('translate.Created_in_successfully') }}');
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

            //----------------------- Update_Category ---------------------------\\
            Update_Category() {
                var self = this;
                self.SubmitProcessing = true;
                self.setToStrings();
                axios
                .put("{{ url('products/categories') }}/" + this.category.id, {
                    name: self.category.name,
                    parent_id: self.category.parent_id
                })
                .then(response => {
                    self.SubmitProcessing = false;
                    $.event.trigger('event_category');
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

            //--------------------------------- Remove_Category ---------------------------\\
            Remove_Category(id) {
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
                    .delete("{{ url('products/categories') }}/" + id)
                    .then(() => {
                        $.event.trigger('event_category');
                        toastr.success('{{ __('translate.Deleted_in_successfully') }}');

                    })
                    .catch(() => {
                        toastr.error('{{ __('translate.There_was_something_wronge') }}');
                    });
                }).catch(() => {  
                });
            },
        },
        //-----------------------------Autoload function-------------------
        created() {
        }

    })

</script>
@endsection