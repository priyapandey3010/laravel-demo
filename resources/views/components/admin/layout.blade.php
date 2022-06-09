@include('components.admin.header')

    <div id="wrapper">

        @include('components.admin.sidebar')

        <div id="content-wrapper" class="d-flex flex-column">

            <div id="content">

                @include('components.admin.topbar')

                @yield('page-content')

            </div>

            @include('components.admin.footnote')

        </div>
    
    </div>

@include('components.admin.footer')

