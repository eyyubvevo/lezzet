    <footer style="background-color:rgb(239, 245, 245);">
        <div class="container">
            <div class="row">
                <div class="col-lg-5 col-md-12">
                    <img class="footer-logo" src="{{asset('storage/'.setting('logo'))}}" alt="logo">
                </div>
                <div class="col-lg-3 col-md-12" >
                    <div class="footer-nav" >
                        <h4>{{__('website.menu')}}</h4>
                        <ul>
                            <li><a href="{{route(app()->getLocale().'.home')}}">{{__('website.home')}}</a></li>
                            <li><a href="{{route(app()->getLocale().'.partners')}}">{{__('website.partners')}}</a></li>
                            <li><a href="{{route(app()->getLocale().'.general_questions')}}">{{__('website.general_questions')}}</a></li>
                            <li><a href="{{route(app()->getLocale().'.news')}}">{{__('website.news')}}</a></li>
                            <li><a href="{{route(app()->getLocale().'.contact')}}">{{__('website.contact')}}</a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-4 col-md-12" >
                    <div class="footer-contact"  >
                        <h4>{{__('website.contact_info')}}</h4>
                        <ul>
                            <li><i class="fas fa-phone-alt"></i> <span>{{setting('phone')}}</span></li>
                            <li><i class="fas fa-envelope"></i> <span>{{setting('email')}}</span></li>
                            <li><i class="fas fa-map-marker-alt"></i> <span>{{setting('address_'.app()->getLocale())}}</span></li>
                            <li><i class="fas fa-map-marker-alt"></i> <span>{{setting('address2_'.app()->getLocale())}}</span></li>
                        </ul>
                        <div class="footer-icons">
                            @if(setting('facebook') != null)
                             <a target="_blank" href="{{setting('facebook')}}"><i class="fa-brands fa-facebook fa-lg" style="color: blue;"></i></a>
                            @endif
                             @if(setting('whatsapp') != null)
                            <a target="_blank" href="{{setting('whatsapp')}}"><i class="fa-brands fa-whatsapp fa-lg" style="color: green;"></i></a>
                             @endif
                                @if(setting('youtube') != null)
                            <a target="_blank" href="{{setting('youtube')}}"><i class="fa-brands fa-youtube fa-lg" style="color: red;"></i></a>
                             @endif
                               @if(setting('tiktok') != null)
                            <a target="_blank" href="{{setting('tiktok')}}"><i class="fa-brands fa-tiktok fa-lg"></i></a>
                             @endif
                              @if(setting('instagram') != null)
                            <a target="_blank" href="{{setting('instagram')}}"><i class="fa-brands fa-instagram fa-lg"></i></a>
                            @endif
                               @if(setting('linkedin') != null)
                            <a target="_blank" href="{{setting('linkedin')}}"><i class="fa-brands fa-linkedin fa-lg" style="color: blue;"></i></a>
                               @endif
                        </div>
                    </div>
                </div> 
            </div>




        </div>
        <div class="copyright" style="background-color: inherit">
            <div class="markup-agency">
                <h5>&copy 2024 - Veb sayta texniki dəstək <a href="https://markup.az/" target="_blank" class="footer-markup">Markup.az</a>
                </h5>
            </div>
        </div>
    </footer>
