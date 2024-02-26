<?php
    $path = Request::path();
    $parentPath = explode("/",$path)[0];
    $setting = DB::table('settings')->where('deleted_at', '=', null)->first();
?>

<!-- start sidebar -->
<div 
    x-data="{ isCompact: false }" 
    :class="isCompact ? 'compact' : ''" 
    class="sidebar-content bg-gray-900 card rounded-0"
>
    <div class="sidebar-header mb-5 d-flex justify-content-between align-items-center">
        <div class="d-flex align-items-center">
            <a href="/"><img class="app-logo me-2" width="100" src="{{asset('images/'.$setting->logo)}}" alt=""></a>
        </div>
        <button @click="isCompact = !isCompact" class="compact-button btn border border-gray-600 d-none d-lg-flex align-items-center p-1 width_24">
            @include('components.icons.expand-arrows', ['class'=>'width_16'])
        </button>
        <button class="close-sidebar btn border border-gray-600 d-flex d-lg-none align-items-center p-1 width_24">
            @include('components.icons.expand-arrows', ['class'=>'width_16'])
        </button>
    </div>

    <!-- user -->
    <div class="scroll-nav" data-perfect-scrollbar data-suppress-scroll-x="true">
        <ul x-data="collapse('{{$parentPath}}')" class="list-group" id="menu">
            {{-- Dashboard --}}
            <li class="">
                <a href="{{ url('') }}" class="nav-item @if($path == 'dashboard/admin') active @endif">
                    @include('components.icons.dashboard2', ['class'=>'width_16'])
                    <span class="item-name">{{ __('translate.dashboard') }}</span>
                </a>
            </li>            

            {{-- User Management --}}
            @if (auth()->user()->can('user_view') || auth()->user()->can('group_permission'))
                <li>
                    <div 
                        @click="selectCollapse('user-management')"
                        :class="selected == 'user-management' ? 'collapse-active' : 'collapse-deactive'"
                        class="collapse-button"
                    >
                        @include('components.sidebar.collapse-navitem', [
                            'title'=>__('translate.UserManagement'), 
                            'icon'=>'components.icons.customers'
                        ])
                    </div>
                    <div
                        x-ref="user-management"
                        x-bind:style="activeCollapse($refs, 'user-management', selected)"
                        class="collapse-content"
                    >
                        <ul class="list-group">
                            @can('user_view')
                                <li class="">
                                    @include('components.sidebar.child-navitem', [
                                        'href'=> url('user-management/users'), 
                                        'title'=> __('translate.Users')
                                    ])
                                </li>
                            @endcan
                            @can('group_permission')
                                <li class="">
                                    @include('components.sidebar.child-navitem', [
                                        'href'=> url('user-management/permissions'), 
                                        'title'=> __('translate.Roles')
                                    ])
                                </li>
                            @endcan
                        </ul>
                    </div>
                </li>
            @endif

            {{-- Products --}}
            @if (auth()->user()->can('products_add') || auth()->user()->can('products_view')|| auth()->user()->can('category')
            || auth()->user()->can('brand') || auth()->user()->can('unit') || auth()->user()->can('warehouse') || auth()->user()->can('print_labels'))
                <li>
                    <div 
                        @click="selectCollapse('products')"
                        :class="selected == 'products' ? 'collapse-active' : 'collapse-deactive'"
                        class="collapse-button"
                    >
                        @include('components.sidebar.collapse-navitem', [
                            'title'=>__('translate.Products'), 
                            'icon'=>'components.icons.product2'
                        ])
                    </div>

                    <div
                        x-ref="products"
                        x-bind:style="activeCollapse($refs, 'products', selected)"
                        class="collapse-content"
                    >
                        <ul class="list-group">
                            @can('products_view')
                                <li>
                                    @include('components.sidebar.child-navitem', [
                                        'href'=> url('products/products'), 
                                        'title'=> __('translate.productsList')
                                    ])
                                </li>
                            @endcan
                            @can('products_add')
                                <li>
                                    @include('components.sidebar.child-navitem', [
                                        'href'=> url('products/products/create'), 
                                        'title'=> __('translate.AddProduct')
                                    ])
                                </li>
                            @endcan
                            {{--@can('print_labels')
                                <li>
                                    @include('components.sidebar.child-navitem', [
                                        'href'=> url('products/print_labels'), 
                                        'title'=> __('translate.Print_Labels')
                                    ])
                                </li>
                            @endcan--}}
                            @can('category')
                                <li>
                                    @include('components.sidebar.child-navitem', [
                                        'href'=> url('products/categories'), 
                                        'title'=> __('translate.Categories')
                                    ])
                                </li>
                            @endcan
                            @can('unit')
                                <li>
                                    @include('components.sidebar.child-navitem', [
                                        'href'=> url('products/units'), 
                                        'title'=> __('translate.Units')
                                    ])
                                </li>
                            @endcan
                            @can('brand')
                                <li>
                                    @include('components.sidebar.child-navitem', [
                                        'href'=> url('products/brands'), 
                                        'title'=> __('translate.Brand')
                                    ])
                                </li>
                            @endcan
                            @can('warehouse')
                                <li>
                                    @include('components.sidebar.child-navitem', [
                                        'href'=> url('products/warehouses'), 
                                        'title'=> __('translate.Warehouses')
                                    ])
                                </li>
                            @endcan
                            @if (auth()->user()->can('suppliers_view_all') || auth()->user()->can('suppliers_view_own'))
                                <li class="">
                                    @include('components.sidebar.child-navitem', [
                                        'href'=> url('products/suppliers'), 
                                        'title'=> __('translate.Suppliers')
                                    ])
                                </li>
                            @endif
                        </ul>
                    </div>
                </li>
            @endif

            {{-- Stock Adjustment --}}
            @if (auth()->user()->can('adjustment_view_all') || auth()->user()->can('adjustment_view_own') || auth()->user()->can('adjustment_add'))
                <li>
                    <div 
                        @click="selectCollapse('adjustment')"
                        :class="selected == 'adjustment' ? 'collapse-active' : 'collapse-deactive'"
                        class="collapse-button"
                    >
                        @include('components.sidebar.collapse-navitem', [
                            'title'=>__('translate.StockAdjustement'), 
                            'icon'=>'components.icons.eraser'
                        ])
                    </div>

                    <div
                        x-ref="adjustment"
                        x-bind:style="activeCollapse($refs, 'adjustment', selected)"
                        class="collapse-content"
                    >
                        <ul class="list-group">
                            @if (auth()->user()->can('adjustment_view_all') || auth()->user()->can('adjustment_view_own'))
                                <li>
                                    @include('components.sidebar.child-navitem', [
                                        'href'=> url('adjustment/adjustments'), 
                                        'title'=> __('translate.ListAdjustments')
                                    ])
                                </li>
                            @endif
                            @can('adjustment_add')
                                <li>
                                    @include('components.sidebar.child-navitem', [
                                        'href'=> url('adjustment/adjustments/create'), 
                                        'title'=> __('translate.CreateAdjustment')
                                    ])
                                </li>
                            @endcan
                        </ul>
                    </div>
                </li>  
            @endif

            {{-- Purchases --}}
            @if (auth()->user()->can('purchases_view_all') || auth()->user()->can('purchases_view_own') || auth()->user()->can('purchases_add'))
                <li>
                    <div 
                        @click="selectCollapse('purchase')"
                        :class="selected == 'purchase' ? 'collapse-active' : 'collapse-deactive'"
                        class="collapse-button"
                    >
                        @include('components.sidebar.collapse-navitem', [
                            'title'=>__('translate.Purchases'), 
                            'icon'=>'components.icons.cart'
                        ])
                    </div>
                    <div
                        x-ref="purchase"
                        x-bind:style="activeCollapse($refs, 'purchase', selected)"
                        class="collapse-content"
                    >
                        <ul class="list-group">
                            @if (auth()->user()->can('purchases_view_all') || auth()->user()->can('purchases_view_own'))
                                <li>
                                    @include('components.sidebar.child-navitem', [
                                        'href'=> url('purchase/purchases'), 
                                        'title'=> __('translate.ListPurchases')
                                    ])
                                </li>
                            @endif
                            @can('purchases_add')
                                <li>
                                    @include('components.sidebar.child-navitem', [
                                        'href'=> url('purchase/purchases/create'), 
                                        'title'=> __('translate.AddPurchase')
                                    ])
                                </li>
                            @endcan

                            {{-- Purchase Return --}}
                            {{--@if (auth()->user()->can('purchase_returns_view_all') || auth()->user()->can('purchase_returns_view_own'))

                                <li class="">
                                    @include('components.sidebar.child-navitem', [
                                        'href'=> url('purchase-return/returns_purchase'), 
                                        'title'=> __('translate.PurchasesReturn')
                                    ])
                                </li>  
                            @endif--}}
                        </ul>
                    </div>
                </li> 
            @endif

            {{-- Sales --}}
            @if (auth()->user()->can('sales_view_all') || auth()->user()->can('sales_view_own') || auth()->user()->can('sales_add'))
                <li>
                    <div 
                        @click="selectCollapse('sale')"
                        :class="selected == 'sale' ? 'collapse-active' : 'collapse-deactive'"
                        class="collapse-button"
                    >
                        @include('components.sidebar.collapse-navitem', [
                            'title'=>__('translate.Sales'), 
                            'icon'=>'components.icons.add-to-cart'
                        ])
                    </div>
                    <div
                        x-ref="sale"
                        x-bind:style="activeCollapse($refs, 'sale', selected)"
                        class="collapse-content"
                    >
                        <ul class="list-group">
                            @if (auth()->user()->can('sales_view_all') || auth()->user()->can('sales_view_own'))
                                <li>
                                    @include('components.sidebar.child-navitem', [
                                        'href'=> url('sale/sales'), 
                                        'title'=> __('translate.ListSales')
                                    ])
                                </li>
                            @endif
                            {{--@can('sales_add')
                                <li>
                                    @include('components.sidebar.child-navitem', [
                                        'href'=> url('sale/sales/create'), 
                                        'title'=> __('translate.AddSale')
                                    ])
                                </li>
                            @endcan--}}

                            {{-- Sales Return --}}
                            {{--@if (auth()->user()->can('sale_returns_view_all') || auth()->user()->can('sale_returns_view_own'))
                                <li class="">
                                    @include('components.sidebar.child-navitem', [
                                        'href'=> url('sales-return/returns_sale'), 
                                        'title'=> __('translate.SalesReturn')
                                    ])
                                </li>  
                            @endif--}}
                        </ul>
                    </div>
                </li> 
            @endif

            
            {{-- Reports --}}
            @if (auth()->user()->can('report_products') || 
            auth()->user()->can('report_inventaire') || auth()->user()->can('report_clients') || 
            auth()->user()->can('report_fournisseurs') || auth()->user()->can('reports_alert_qty') || 
            auth()->user()->can('report_profit') || auth()->user()->can('sale_reports') || 
            auth()->user()->can('purchase_reports') || auth()->user()->can('payment_sale_reports') || 
            auth()->user()->can('payment_purchase_reports') || auth()->user()->can('payment_return_purchase_reports') || 
            auth()->user()->can('payment_return_sale_reports') )
                <li>
                    <div 
                        @click="selectCollapse('reports')"
                        :class="selected == 'reports' ? 'collapse-active' : 'collapse-deactive'"
                        class="collapse-button"
                    >
                        @include('components.sidebar.collapse-navitem', [
                            'title'=>__('translate.Reports'), 
                            'icon'=>'components.icons.reports'
                        ])
                    </div>
                    <div
                        x-ref="reports"
                        x-bind:style="activeCollapse($refs, 'reports', selected)"
                        class="collapse-content"
                    >
                        <ul class="list-group">

                           {{--@can('report_profit')
                                <li>
                                    @include('components.sidebar.child-navitem', [
                                        'href'=> url('reports/report_profit'), 
                                        'title'=> __('translate.ProfitandLoss')
                                    ])
                                </li>
                            @endcan

                            @can('sale_reports')
                                <li>
                                    @include('components.sidebar.child-navitem', [
                                        'href'=> url('reports/sale_report'), 
                                        'title'=> __('translate.SalesReport')
                                    ])
                                </li>
                              
                            @endcan
                            @can('purchase_reports')
                                <li>
                                    @include('components.sidebar.child-navitem', [
                                        'href'=> url('reports/purchase_report'), 
                                        'title'=> __('translate.PurchasesReport')
                                    ])
                                </li>
                               
                            @endcan

                            @can('report_inventaire')
                                <li>
                                    @include('components.sidebar.child-navitem', [
                                        'href'=> url('reports/report_stock'), 
                                        'title'=> __('translate.Inventory_report')
                                    ])
                                </li>
                            @endcan
                            @can('report_products')
                                <li>
                                    @include('components.sidebar.child-navitem', [
                                        'href'=> url('reports/report_product'), 
                                        'title'=> __('translate.product_report')
                                    ])
                                </li>
                            @endcan
                           
                            @can('report_fournisseurs')
                                <li>
                                    @include('components.sidebar.child-navitem', [
                                        'href'=> url('reports/report_suppliers'), 
                                        'title'=> __('translate.SuppliersReport')
                                    ])
                                </li>
                            @endcan
                            
                            @can('payment_sale_reports')
                                <li>
                                    @include('components.sidebar.child-navitem', [
                                        'href'=> url('reports/payment_sale'), 
                                        'title'=> __('translate.payment_sale')
                                    ])
                                </li>
                            @endcan
                            @can('payment_purchase_reports')
                                <li>
                                    @include('components.sidebar.child-navitem', [
                                        'href'=> url('reports/payment_purchase'), 
                                        'title'=> __('translate.payment_purchase')
                                    ])
                                </li>
                            @endcan
                            @can('payment_return_sale_reports')
                                <li>
                                    @include('components.sidebar.child-navitem', [
                                        'href'=> url('reports/payment_sale_return'), 
                                        'title'=> __('translate.payment_sale_return')
                                    ])
                                </li>
                            @endcan
                            @can('payment_return_purchase_reports')
                                <li>
                                    @include('components.sidebar.child-navitem', [
                                        'href'=> url('reports/payment_purchase_return'), 
                                        'title'=> __('translate.payment_purchase_return')
                                    ])
                                </li>
                            @endcan
                            @can('reports_alert_qty')
                                <li>
                                    @include('components.sidebar.child-navitem', [
                                        'href'=> url('reports/reports_quantity_alerts'), 
                                        'title'=> __('translate.ProductQuantityAlerts')
                                    ])
                                </li>
                            @endcan--}}
                        </ul>
                    </div>
                </li>
            @endif
            
       
            {{-- Settings --}}
            @if (auth()->user()->can('settings') || auth()->user()->can('backup') || auth()->user()->can('currency')
             || auth()->user()->can('notification_template'))
                <li>
                    <div 
                        @click="selectCollapse('settings')"
                        :class="selected == 'settings' ? 'collapse-active' : 'collapse-deactive'"
                        class="collapse-button"
                    >
                        @include('components.sidebar.collapse-navitem', [
                            'title'=>__('translate.Settings'), 
                            'icon'=>'components.icons.settings'
                        ])
                    </div>
                    <div
                        x-ref="settings"
                        x-bind:style="activeCollapse($refs, 'settings', selected)"
                        class="collapse-content"
                    >
                        <ul class="list-group">
                            @can('settings')
                                <li>
                                    @include('components.sidebar.child-navitem', [
                                        'href'=> url('settings/system_settings'), 
                                        'title'=> __('translate.System_Settings')
                                    ])
                                </li>
                            @endcan

                            {{--@can('notification_template')
                                <li>
                                    @include('components.sidebar.child-navitem', [
                                        'href'=> url('settings/emails_template'), 
                                        'title'=> __('translate.emails_template')
                                    ])
                                </li>
                            @endcan--}}
                            @can('currency')
                                <li>
                                    @include('components.sidebar.child-navitem', [
                                        'href'=> url('settings/currency'), 
                                        'title'=> __('translate.Currency')
                                    ])
                                </li>
                            @endcan
                            @can('backup')
                                <li>
                                    @include('components.sidebar.child-navitem', [
                                        'href'=> url('settings/backup'), 
                                        'title'=> __('translate.Backup')
                                    ])
                                </li>
                            @endcan
                        </ul>
                    </div>
                </li>
            @endif
        </ul>
    </div>
</div>