@extends('layouts.master')
@section('main-content')
@section('page-css')
@endsection

<div class="breadcrumb">
    <h1>{{ __('translate.Edit_Product') }}</h1>
</div>

<div class="separator-breadcrumb border-top"></div>

<div class="row" id="section_edit_product">
    <div class="col-lg-12 mb-3">
        <!--begin::form-->
        <form @submit.prevent="Update_Product()">
            <div class="card">
                <div class="card-body">
                    <div class="row">

                    <div class="form-group col-md-4">
                            <label for="name">{{ __('translate.Product_Name') }} <span
                                    class="field_required">*</span></label>
                            <input type="text" class="form-control" id="name"
                                placeholder="{{ __('translate.Enter_Name_Product') }}" v-model="product.name">
                            <span class="error" v-if="errors && errors.name">
                                @{{ errors.name[0] }}
                            </span>
                        </div>

                        <div class="form-group col-md-4">
                            <label for="sku">{{ __('translate.Product_Code') }} <span class="field_required">*</span></label>

                            <div class="input-group">
                                <div class="input-group mb-3">
                                    <input v-model.number="product.sku" type="text" class="form-control" placeholder="{{ __('translate.Product_Code') }}" aria-label="Product SKU" aria-describedby="basic-addon2">
                                    <span class="input-group-text cursor-pointer" id="basic-addon2" @click="generateNumber()"><i class="fas fa-barcode"></i></span>
                                </div>
                            </div>
                            <span class="error" v-if="errors && errors.sku">
                                @{{ errors.sku[0] }}
                            </span>
                        </div>

                        <div class="form-group col-md-4">
                            <label for="image">{{ __('translate.Image') }} </label>
                            <input name="image" @change="onFileSelected" type="file" class="form-control" id="image">
                            <span class="error" v-if="errors && errors.image">
                                @{{ errors.image[0] }}
                            </span>
                        </div>

                        <div class="form-group col-md-4">
                            <label>{{ __('translate.Category') }} </label>
                            <v-select placeholder="{{ __('translate.Choose_Category') }}" v-model="product.category_id"
                                :reduce="(option) => option.value"
                                :options="categories.map(categories => ({label: categories.name, value: categories.id}))">
                            </v-select>

                            <span class="error" v-if="errors && errors.category_id">
                                @{{ errors.category_id[0] }}
                            </span>
                        </div>

                        <div class="form-group col-md-4">
                            <label>{{ __('translate.Brand') }} </label>
                            <v-select placeholder="{{ __('translate.Choose_Brand') }}" v-model="product.brand_id"
                                :reduce="(option) => option.value"
                                :options="brands.map(brands => ({label: brands.name, value: brands.id}))">
                            </v-select>
                        </div>

                        <div class="form-group col-md-12 mb-4">
                            <label for="note">{{ __('translate.Description') }} </label>
                            <textarea type="text" v-model="product.note" class="form-control" name="note" id="note"
                                placeholder="{{ __('translate.Description') }}"></textarea>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mt-5">
                <div class="card-body">
                    <div class="row">

                        <div class="form-group col-md-4 mb-3" v-if="product.type == 'is_single'">
                            <label for="type" >{{ __('translate.Product_Type') }} <span class="field_required">*</span></label>
                            <input type="text" class="form-control" id="type" placeholder="Standard Product" value="Standard Product" disabled>

                            <span class="error" v-if="errors && errors.type">
                                @{{ errors.type[0] }}
                            </span>
                        </div>

                        <div class="form-group col-md-4 mb-3" v-if="product.type == 'is_raw'">
                            <label for="type" >{{ __('translate.Product_Type') }} <span class="field_required">*</span></label>
                            <input type="text" class="form-control" id="type" placeholder="Raw Material" value="Raw Material" disabled>

                            <span class="error" v-if="errors && errors.type">
                                @{{ errors.type[0] }}
                            </span>
                        </div>

                        <div class="form-group col-md-4 mb-3" v-if="product.type == 'is_finish'">
                            <label for="type" >{{ __('translate.Product_Type') }} <span class="field_required">*</span></label>
                            <input type="text" class="form-control" id="type" placeholder="Finish Product" value="Finish Product" disabled>

                            <span class="error" v-if="errors && errors.type">
                                @{{ errors.type[0] }}
                            </span>
                        </div>

                        <div class="form-group col-md-4 mb-3" v-if="product.type == 'is_variant'">
                            <label for="type" >{{ __('translate.Product_Type') }} <span class="field_required">*</span></label>
                            <input type="text" class="form-control" id="type" placeholder="Variable Product" value="Variable Product" disabled>

                            <span class="error" v-if="errors && errors.type">
                                @{{ errors.type[0] }}
                            </span>
                        </div>

                        <div class="form-group col-md-4" v-if="product.type == 'is_single'">
                            <label for="cost">{{ __('translate.Product_Cost') }} <span class="field_required">*</span></label>
                            <input type="text" class="form-control" id="cost" placeholder="{{ __('translate.Enter_Product_Cost') }}"
                                v-model="product.cost">

                            <span class="error" v-if="errors && errors.cost">
                                @{{ errors.cost[0] }}
                            </span>
                        </div>
    
                        <div class="form-group col-md-4" v-if="product.type == 'is_single' || product.type == 'is_service'">
                            <label for="price">{{ __('translate.Product_Price') }} <span class="field_required">*</span></label>
                            <input type="text" class="form-control" id="price" placeholder="{{ __('translate.Enter_Product_Price') }}"
                                v-model="product.price">

                            <span class="error" v-if="errors && errors.price">
                                @{{ errors.price[0] }}
                            </span>
                        </div>

                        <div class="form-group col-md-4" v-if="product.type != 'is_service'">
                            <label>{{ __('translate.Unit_Product') }} <span class="field_required">*</span></label>
                            <v-select @input="Selected_Unit" placeholder="{{ __('translate.Choose_Unit_Product') }}"
                                v-model="product.unit_id" :reduce="label => label.value"
                                :options="units.map(units => ({label: units.name, value: units.id}))">
                            </v-select>

                            <span class="error" v-if="errors && errors.unit_id">
                                @{{ errors.unit_id[0] }}
                            </span>
                        </div>

                        <div class="form-group col-md-4" v-if="product.type != 'is_service'">
                            <label for="stock_alert">{{ __('translate.Stock_Alert') }} </label>
                            <input type="text" class="form-control" id="stock_alert" placeholder="{{ __('translate.Enter_Stock_alert') }}" v-model="product.stock_alert">
                        </div>

                        <div class="col-md-9 mb-3 mt-3" v-if="product.type == 'is_finish'">
                            <div class="row">
                                <div class="col-md-12"><h4>{{ __('translate.raw_materials') }}</h4></div>
                            </div>
                            <div class="row">
                                <div class="col-md-8">
                                    <v-select placeholder="{{ __('translate.select_material') }}" v-model="material_id" :reduce="label => label.value" :options="raw_materials.map(raw_material => ({label: raw_material.name, value: raw_material.id}))">
                                    </v-select>
                                </div>
                                <div class="col-md-4">
                                    <a @click="add_raw_material(material_id)" class="btn btn-md btn-primary">
                                        {{ __('translate.Add_raw_material') }}
                                    </a>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-9 mb-2 " v-if="product.type == 'is_finish'">
                            <div class="table-responsive">
                                <table class="table table-hover table-sm">
                                    <thead class="bg-gray-300">
                                        <tr>
                                            <th scope="col">{{ __('translate.raw_material') }}</th>
                                            <th scope="col">{{ __('translate.Unit') }}</th>
                                            <th scope="col">{{ __('translate.Product_Cost') }}</th>
                                            <th scope="col">{{ __('translate.Product_Price') }}</th>
                                            <th scope="col"></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-if="materials.length <=0">
                                            <td colspan="5">{{ __('translate.No_data_Available') }}</td>
                                        </tr>
                                        <tr v-for="material in materials">
                                            <td>
                                                <input required class="form-control d-none" v-model="material.id">
                                                <div>@{{ material.name }}</div>
                                            </td>
                                            <td>
                                                <div>@{{ material.unit }}</div>
                                            </td>
                                            <td>
                                                <div v-if="material.cost > 0">{{$currency}} @{{formatNumber(material.cost,2)}}</div>
                                            </td>
                                            <td>
                                                <div v-if="material.price > 0">{{$currency}} @{{formatNumber(material.price,2)}}</div>
                                            </td>
                                            <td>
                                                <a @click="delete_material(material.id)" class="btn btn-danger" title="Delete"> <i class="far fa-times-circle"></i></a>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="col-md-9 mb-3 mt-3" v-if="product.type == 'is_finish'">
                            <div class="row">
                                <div class="col-md-12"><h4>{{ __('translate.Variant') }}</h4></div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <label>{{ __('translate.Weight_Size') }}</label>
                                    <input placeholder="{{ __('translate.Weight_Size') }}" type="text" name="variant" v-model="tag" class="form-control">
                                </div>
                                <div class="col-md-4">
                                    {{--<label>{{ __('translate.Plan') }}</label>
                                    <v-select placeholder="{{ __('translate.Choose_Plan') }}" v-model="material_id" :reduce="label => label.value" :options="subscription_plans.map(res => ({label: res.name, value: res.value}))">
                                    </v-select>--}}
                                </div>
                                <div class="col-md-4">
                                    <a @click="add_variant(tag)" class="mt-3 ms-3 btn btn-md btn-primary">
                                        {{ __('translate.Add') }}
                                    </a>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-9 mb-2 " v-if="product.type == 'is_finish'">
                            <div class="table-responsive">
                                <table class="table table-hover table-sm">
                                    <thead class="bg-gray-300">
                                        <tr>
                                            <th scope="col">{{ __('translate.Variant_code') }}</th>
                                            <th scope="col">{{ __('translate.Variant_Name') }}</th>
                                            <th scope="col">{{ __('translate.Variant_Size') }}</th>
                                            <th scope="col">{{ __('translate.Variant_Cost') }}</th>
                                            <th scope="col">{{ __('translate.Variant_Price') }}</th>
                                            <th scope="col"></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-if="variants.length <=0">
                                            <td colspan="6">{{ __('translate.No_data_Available') }}</td>
                                        </tr>
                                        <tr v-for="variant in variants">
                                           <td>
                                                <input required class="form-control" v-model="variant.sku">
                                            </td>
                                            <td>
                                                <input required class="form-control" v-model="variant.text">
                                            </td>
                                            <td>
                                                <input required class="form-control" v-model="variant.size">
                                            </td>
                                            <td>
                                                <input required class="form-control" v-model="variant.cost">
                                            </td>
                                            <td>
                                                <input required class="form-control" v-model="variant.price">
                                            </td>
                                            <td>
                                                <a @click="delete_variant(variant.var_id)" class="btn btn-danger"
                                                    title="Delete">
                                                    <i class="far fa-times-circle"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                            
                    </div>
                </div>
            </div>

            <div class="row mt-3">
                <div class="col-lg-6">
                    <button type="submit" class="btn btn-primary" :disabled="SubmitProcessing">
                        <span v-if="SubmitProcessing" class="spinner-border spinner-border-sm" role="status"
                            aria-hidden="true"></span> <i class="far fa-check-circle me-2 font-weight-bold"></i> {{ __('translate.Submit') }}
                    </button>

                </div>
            </div>
        </form>

        <!-- end::form -->
    </div>

</div>

@endsection

@section('page-js')
<script src="{{asset('assets/vendor/bootstrap-tagsinput.js')}}"></script>
<script src="{{asset('assets/vendor/vuejs-datepicker.min.js')}}"></script>

<script type="text/javascript">
    $(function () {
        "use strict";
  
        $(document).on('keyup keypress', 'form input[type="text"]', function(e) {
            if(e.keyCode == 13) {
                e.preventDefault();
                return false;
            }
        });
    });
</script>

<script>
    Vue.component('v-select', VueSelect.VueSelect)
    var app = new Vue({
        el: '#section_edit_product',
        components: {
            vuejsDatepicker,
        },
        data: {
            len: 8,
            tag:"",
            SubmitProcessing:false,
            data: new FormData(),
            errors:[],
            categories: @json($categories),
            units: @json($units),
            units_sub: @json($units_sub),
            brands: @json($brands),
            product: @json($product),
            raw_materials: @json($raw_materials),
            material_id:"",
            materials: @json($product['ProductMaterial']),
            variants: @json($product['ProductVariant']),
        },
        methods: {
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
            generateNumber() {
                this.sku_exist = "";
                this.product.sku = Math.floor( Math.pow(10, this.len - 1) + Math.random() * (Math.pow(10, this.len) - Math.pow(10, this.len - 1) - 1) );
            },
            Selected_Brand(value) {
                if (value === null) {
                    this.product.brand_id = "";
                }
            },
            formatDate(d){
                var m1 = d.getMonth()+1;
                var m2 = m1 < 10 ? '0' + m1 : m1;
                var d1 = d.getDate();
                var d2 = d1 < 10 ? '0' + d1 : d1;
                return [d.getFullYear(), m2, d2].join('-');
            },
            add_raw_material(material_id) {
                if (this.materials.length > 0 && this.materials.some(material => material.id === material_id)) {
                    toastr.error('Duplicate Material');
                } else {
                    var res = this.raw_materials.find(x => x.id === material_id);
                    console.log(material_id, res, this.raw_materials);
                    if(this.material_id != ''){
                        var material = {
                            id: res.id,
                            name: res.name,
                            unit: res.unit.name,
                            cost: res.cost,
                            price: res.price,
                        };
                        this.materials.push(material);
                        this.material_id = "";
                    }
                    else{
                        toastr.error('Please Select Material');
                    }
                }
            },
            delete_material(var_id) {
                for (var i = 0; i < this.materials.length; i++) {
                    if (var_id === this.materials[i].id) {
                        this.materials.splice(i, 1);
                    }
                }
            },
            add_variant(tag) {
                if (this.variants.length > 0 && this.variants.some(variant => variant.text === tag)) {
                    toastr.error('Duplicate Variant');
                } else {
                    if(this.tag != ''){
                        var variant_tag = {
                            var_id: this.variants.length + 1,
                            text: tag
                        };
                        this.variants.push(variant_tag);
                        this.tag = "";
                    }
                    else{
                        toastr.error('Please Enter the Variant Title');
                    }
                }
            },
            delete_variant(var_id) {
                for (var i = 0; i < this.variants.length; i++) {
                    if (var_id === this.variants[i].var_id) {
                        this.variants.splice(i, 1);
                    }
                }
            },
            onFileSelected(e){
                let file = e.target.files[0];
                this.product.image = file;
            },
            Get_Units_SubBase(value) {
                axios
                .get("{{ url('products/get_units_subbase?id=') }}" + value)
                .then(({ data }) => (this.units_sub = data));
            },
            Selected_Unit(value) {
                this.units_sub = [];
                this.product.unit_sale_id = "";
                this.product.unit_purchase_id = "";
                this.Get_Units_SubBase(value);
            },
            Update_Product() {
                if ((this.product.type == 'is_variant' || this.product.type == 'is_subscription' || this.product.type == 'is_finish') && this.variants.length <= 0) {
                    toastr.error('The variants array is required.');
                }
                else{
                    NProgress.start();
                    NProgress.set(0.1);
                    var self = this;
                    self.SubmitProcessing = true;
                  
                 
                    if (self.product.type == 'is_variant' && self.variants.length > 0) {
                        self.product.is_variant = true;
                    }else{
                        self.product.is_variant = false;
                    }

                    // append objet product
                    Object.entries(self.product).forEach(([key, value]) => {
                        self.data.append(key, value);
                    });
                    
                    // append array materials
                    if (self.materials.length) {
                        for (var i = 0; i < self.materials.length; i++) {
                            Object.entries(self.materials[i]).forEach(([key, value]) => {
                                self.data.append("materials[" + i + "][" + key + "]", value);
                            });
                        }
                    }

                    //append array variants
                    if (self.variants.length) {
                        for (var i = 0; i < self.variants.length; i++) {
                            Object.entries(self.variants[i]).forEach(([key, value]) => {
                                self.data.append("variants[" + i + "][" + key + "]", value);
                            });
                        }
                    }
  
                    self.data.append("_method", "put");
                  
                    // Send Data with axios
                    axios
                    .post("{{ url('products/products') }}/" + self.product.id, self.data)
                    .then(response => {
                        // Complete the animation of theprogress bar.
                        NProgress.done();
                        self.SubmitProcessing = false;
                        window.location.href = "{{ url('products/products') }}"; 
                        toastr.success('{{ __('translate.Updated_in_successfully') }}');
                        self.errors = {};
                    })
                    .catch(error => {
                        NProgress.done();
                        self.SubmitProcessing = false;

                        
                        if (error.response.status == 422) {
                            self.errors = error.response.data.errors;
                            toastr.error('{{ __('translate.There_was_something_wronge') }}');
                        }

                        if(self.errors.variants && self.errors.variants.length > 0){
                            toastr.error(self.errors.variants[0]);
                        }
                    
                    });
                }
            }
        },
        //-----------------------------Autoload function-------------------
        created() {
        }
    })
</script>

@endsection