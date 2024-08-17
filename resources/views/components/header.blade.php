<div class="navigation">
    <img style="width: 100px;margin-top: 10px; margin-left: 30px;" src="{{asset('storage/'.setting('logo'))}}"
         alt="{{setting('title')}}">

    <span class="navigation-close">&times;</span>

    <nav class="toggle-nav">
        <ul>
            <li><a href="{{localized_route('home')}}">{{__('website.home')}}</a></li>
            <li>
                <a href="#" class="dropdown-toggle">{{__('website.catalog')}}</a>
                <ul class="dropdown-container">
                    @foreach($categories as $category)
                        <li>
                            <a href="{{localized_route('home.products',['slug' => $category->slug])}}">{{$category->name}}</a>
                        </li>
                    @endforeach
                </ul>
            </li>
            <!--<li><a href="{{route(app()->getLocale().'.about')}}">{{__('website.about')}}</a></li>-->
            <li><a href="{{route(app()->getLocale().'.partners')}}">{{__('website.partners')}}</a></li>
            <li><a href="{{route(app()->getLocale().'.general_questions')}}">{{__('website.general_questions')}}</a></li>
            <li><a href="{{route(app()->getLocale().'.news')}}">{{__('website.news')}}</a></li>
            <li><a href="{{route(app()->getLocale().'.contact')}}">{{__('website.contact')}}</a></li>ss
        </ul>
    </nav>
</div>

<header class="sticky-bar">
    <div class="container-fluid">
        <div class="row align-items-center">
            <div class="col-xl-2 col-lg-2 col-md-2 col-sm-2 col-2">
                <a class="logo-header" href="{{localized_route('home')}}"><img src="{{asset('storage/'.setting('logo'))}}"
                                                                     alt="{{setting('title')}}"></a>
            </div>
            <div class="col-xl-8 col-lg-7 col-md-6 col-sm-4 col-2">
                <nav class="navbar">
                    <ul class="navbar-list">
                        <li><a href="{{localized_route('home')}}">{{__('website.home')}}</a></li>
                        <ul class="nav-dropdown">
                            <button class="nav-dropbtn">{{__('website.catalog')}}
                                <i class="fa fa-caret-down"></i>
                            </button>
                            <div class="nav-dropdown-content">
                                @foreach($categories as $category)
                                    <li>
                                        <a href="{{localized_route('home.products',['slug' => $category->slug])}}">{{$category->name}}</a>
                                    </li>
                                @endforeach
                            </div>
                        </ul>
                        <!--<li><a href="{{route(app()->getLocale().'.about')}}">{{__('website.about')}}</a></li>-->
                        <li><a href="{{route(app()->getLocale().'.partners')}}">{{__('website.partners')}}</a></li>
                        <li><a href="{{route(app()->getLocale().'.general_questions')}}">{{__('website.general_questions')}}</a></li>
                        <li><a href="{{route(app()->getLocale().'.news')}}">{{__('website.news')}}</a></li>
                        <li><a href="{{route(app()->getLocale().'.contact')}}">{{__('website.contact')}}</a></li>


                    </ul>
                </nav>
            </div>
            <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6 col-8 d-flex justify-content-end">
                <div class="header-right d-flex">
                    <div class="header-search">
                        <div class="dropdown-search">
                            <button onclick="toggleDropdown()" class="dropbtn-search">
                                <i class="fas fa-search fa-lg"></i>
                            </button>
                            <div id="myDropdown" class="dropdown-search-content">
                                @livewire('search')
                            </div>
                        </div>
                    </div>

                    @livewire('cart-count')

                    <div class="dropdown-language">
                        <button>{{app()->getLocale()}}<span class="language-arrow">&#x25BE</span></button>

                        <div class="dropdown-language__content">
                            @foreach($available_locales as $locale_name => $available_locale)
                            @php($route=implode(".", array_slice(explode(".", \Request::route()->getName()), 1)))


                                @if($slug1)
                                 @php($slug1=$slug1->getTranslation('slug', $available_locale))
                                    <a rel="alternate" hreflang="{{ $available_locale }}"
                                     href="{{route(app()->getLocale().'.'.$route)}}">
                                        {{ $available_locale }}
                                    </a>

                                    <a rel="alternate" hreflang="{{ $available_locale }}"
                                       href="/language/{{ $available_locale }}/{{$route}}/{{$slug1}}">
                                        {{ $available_locale }}
                                    </a>
                                @endif



                                    <a rel="alternate" hreflang="{{ $available_locale }}"
                                       href="/language/{{ $available_locale }}/{{$route}}">
                                        {{ $available_locale }}
                                    </a>
                            @endforeach
                        </div>
                        <!-- <div class="dropdown-language__content">
                            @foreach($available_locales as $locale_name => $available_locale)
                                    <a rel="alternate" hreflang="{{ $available_locale }}"
                                       href="/language/{{explode('.',\Request::route()->getName())[1]}}/{{ $available_locale }}">
                                        {{ $available_locale }}
                                    </a>
                            @endforeach
                        </div> -->

                    </div>

                    <button class="hamburger">
                        <div id="bar1" class="bar"></div>
                        <div id="bar2" class="bar"></div>
                        <div id="bar3" class="bar"></div>
                    </button>
                </div>


            </div>

        </div>
    </div>
</header>
@push('scripts')
    <script>
        function toggleDropdown() {
            var dropdown = document.getElementById("myDropdown");
            dropdown.style.display = (dropdown.style.display === "block") ? "none" : "block";
        }
    </script>
@endpush
