<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    {!! Meta::toHtml() !!}
    <link href="{{ asset('/frontend/bootstrap/bootstrap.min.css') }}" rel="stylesheet" >
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick-theme.min.css" integrity="sha512-17EgCFERpgZKcm0j0fEq1YCJuyAWdz9KUtv1EjVuaOz8pDnh/0nZxmU6BBXwaaxqoi9PQXnRWqlcDB027hgv9A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.css" integrity="sha512-yHknP1/AwR+yx26cB1y0cjvQUMvEa2PFzt1c9LlS4pRQ5NOTZFWbhBig+X9G9eYW/8m0/4OXNx8pxJ6z57x0dw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    
       <!--<link href="{{ asset('/frontend/bootstrap/swiper.min.css') }}"  rel="stylesheet">-->
       
       
       
    <link rel="stylesheet" href="{{asset('frontend/assest/css/style.css?v='.now())}}">
        <link rel="icon" type="image/x-icon" href="{{asset('frontend/assest/image/favicon/2lezzet-favicon.png')}}" />
    @yield('og')
    @livewireStyles
    <!-- Google Tag Manager -->
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','GTM-N7W4S4X');</script>
<!-- End Google Tag Manager -->

    <style>
        .noty_theme__mint.noty_bar .noty_body {
            font-size: 16px!important;
            padding: 20px!important;
            background-color:#DC3B3E !important;
        }
        #noty_layout__topRight {
            width: 380px!important;
        }
        @media (max-width: 600px) {
            .noty_theme__mint.noty_bar .noty_body {
                font-size: 14px!important;
                padding: 20px 10px!important;
            }
            #noty_layout__topRight {
                width: 340px!important;
            }
        }
        @media (max-width: 400px) {
            .noty_theme__mint.noty_bar .noty_body {
                font-size: 12px!important;
            }
        }
    </style>
</head>

<body>

<div id="toTop">
    <i class="fa-solid fa-arrow-up"></i>
</div>
<main>
