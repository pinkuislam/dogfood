<section class="layout-header card rounded-0">
    <div class="d-flex align-items-center">
        @include('layouts.sidebar.mobile-sidebar')
        <button class="toggle-button d-none d-lg-block btn btn-light p-2">
            @include('components.icons.toggle2', ['class'=>'width_20'])
        </button>

        <div class="dropdown layouts_add_new">
            <button class="btn btn-light d-none d-lg-flex align-items-center px-3 py-2 fw-semibold" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                @include('components.icons.plus', ['class'=>'me-2 width_14'])
                <span>{{ __('translate.Add_new') }}</span>
            </button>
                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                    @can('user_add')
                        <li><a class="dropdown-item" href="/user-management/users"> <i class="far fa-user text-20 me-2"></i> {{ __('translate.Add user') }}</a></li>
                    @endcan
                    @can('suppliers_add')
                        <li><a class="dropdown-item" href="/products/suppliers"> <i class="fas fa-user-tag text-20 me-2"></i> {{ __('translate.Add Supplier') }}</a></li>
                    @endcan
                    @can('products_add')
                        <li><a class="dropdown-item" href="/products/products/create"> <i class="fas fa-box text-20 me-2"></i> {{ __('translate.AddProduct') }}</a></li>
                    @endcan
                    @can('adjustment_add')
                        <li><a class="dropdown-item" href="/adjustment/adjustments/create"> <i class="fas fa-eraser text-20 me-2"></i> {{ __('translate.CreateAdjustment') }}</a></li>
                    @endcan
                  
                </ul>
        </div>
    </div>

    <div class="d-flex align-items-center button_pos">
        <button class="btn p-2 ms-4" data-fullscreen data-toggle="tooltip" data-placement="top" title="Full Screen">
            @include('components.icons.fullscreen', ['class'=>'width_30'])
        </button>
        
        <div class="dropdown button_settings">
            <img alt="" width="42" height="42" type="button" data-bs-toggle="dropdown" aria-expanded="false" class="rounded-circle dropdown-toggle" src="{{asset('images/avatar/'.Auth::user()->avatar)}}"/>
            <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="{{ url('profile') }}">{{ __('translate.profile') }}</a></li>
                @can('settings')
                    <li><a class="dropdown-item" href="{{ url('settings/system_settings') }}">{{ __('translate.Settings') }}</a></li>
                @endcan
                <li><a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">{{ __('translate.logout') }}</a>

                    <form id="logout-form" action="{{ route('logout') }}" method="POST">
                        @csrf
                    </form>
                </li>
            </ul>
        </div>
    </div>
</section>