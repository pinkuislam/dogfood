<div class="compact-layout">
    <div 
        data-compact-width="100" 
        class="layout-sidebar"
    >
        @include('layouts.sidebar.sidebar')
    </div>

    <div class="layout-content"> 
        @include('layouts.sidebar.header')

        <div class="content-section">
            @yield('main-content')
        </div>

        <div class="flex-grow-1"></div>
        @include('layouts.sidebar.footer')
        
    </div>
</div>
