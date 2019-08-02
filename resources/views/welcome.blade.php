<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Welcome to Garments Trading</title>


        <link rel = "icon" href ="/svg/logo.png" type = "image/x-icon">
        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">

        <!-- Styles -->
        <style>
            html, body {
                background-color: #fff;
                color: white;
                font-family: 'Nunito', sans-serif;
                font-weight: 200;
                height: 100vh;
                margin: 0;
                background-size:     cover;                      /* <------ */
                background-repeat:   no-repeat;
                background-position: center center;
                background-image: url("http://invoguesocial.com/agrisoft/user/uploads/2017/05/product-11.png");
            }

            .full-height {
                height: 100vh;
            }

            .flex-center {
                align-items: center;
                display: flex;
                justify-content: center;
            }

            .position-ref {
                position: relative;
            }

            .top-right {
                position: absolute;
                right: 10px;
                top: 18px;
            }

            .content {
                text-align: center;
            }

            .title {
                font-size: 100px;
            }

            .links > a {
                color: wheat;
                padding: 0 25px;
                font-size: 15px;
                font-weight: 600;
                letter-spacing: .1rem;
                text-decoration: none;
                text-transform: uppercase;
            }

            .m-b-md {
                margin-bottom: 50px;
            }

            .logo {
                position: absolute;
                top: 30%;
                left: 50%;
                transform: translate(-50%, -50%);
                text-align: center;

            }

            .logo img {
                width: 40%;
                height: 40%;
            }


            .ex:hover, .ex:active {
                font-size: 200%;
            }

            .img:hover, .img:active {
                transform: scale(1.5);
                transition: transform .2s;
            }
        </style>
    </head>
    <body>
        <div class="flex-center position-ref full-height ex">
            @if (Route::has('login'))
                <div class="top-right links">
                    @auth
                        <a href="{{ url('/home') }}">Home</a>
                    @else
                        <a href="{{ route('login') }}">Login</a>

                        @if (Route::has('register'))
                            <a href="{{ route('register') }}">Register</a>
                        @endif
                    @endauth
                </div>
            @endif
                <div class="logo">
                    <img src="/svg/logo2.svg" alt="">
                </div>
                <br>
            <div class="content">
                <div class="title m-b-md">
                    <br><b>Becho</b>
                </div>

                <div class="links">

                    <a href="/home/sajid/Downloads/startbootstrap-business-casual-gh-pages/index.html">About</a>
                    <a href="{{ url('/home') }}">Profile</a>
                    <a href="{{ url('/theme/myHome') }}">News</a>
                    <a href="https://blog.laravel.com">Search</a>
                    <a href="{{ url('/index') }}">Index</a>
                    <a href="https://forge.laravel.com">about</a>
                    <a href="https://github.com/laravel/laravel">GitHub</a>
                </div>
            </div>

        </div>
    </body>
</html>
