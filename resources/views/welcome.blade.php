<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="{{ mix('css/app.css') }}">
        <title>LaraStart</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">

        <!-- Styles -->
        <style>
            /* width */
            ::-webkit-scrollbar {
              width: 10px;
              height: 100%;
            }

            /* Track */
            ::-webkit-scrollbar-track {
              background: #f1f1f1;
            }

            /* Handle */
            ::-webkit-scrollbar-thumb {
              background: rgba(68, 63, 181, 0.9);
            }

            /* Handle on hover */
            ::-webkit-scrollbar-thumb:hover {
              background: #443fb5;
            }

            html, body {
                background-color: #737373;
                color: #fff;
                font-family: 'Nunito', sans-serif;
                font-weight: 200;
                /* height: 100vh; */
                margin: 0;
            }

            .full-height {
                /* height: 80vh; */
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
                font-size: 84px;
            }

            .links > a {
                color: #fff;
                width: 230px;
                font-size: 15px;
                border-width: 3px !important;
                letter-spacing: .2rem;
                text-decoration: none;
                text-transform: uppercase;
            }

            @media only screen and (max-width: 502px) {
              html, body {
                min-width: 490px;
                overflow: hidden;
              }
              .full-height {
                margin-top: 20vh;
              }
            }

            @media only screen and (min-width: 502px) {
              html, body {
                height: 100vh;
              }
              .full-height {
                height: 80vh;
              }
            }

        </style>
    </head>
    <body>
        <div class="joja">
            <div class="flex-center position-ref full-height">
                <div class="content">

                  <img src="{{ asset('img/logo.png') }}" alt="Avatar" style="width:300px">
                    <div class="title">
                        Welcome to LaraStart!
                    </div>

                    @if (Route::has('login'))
                        <div class="links">
                            @auth
                                {{-- <a class="btn btn-lg btn-outline-primary" href="{{ url('/home') }}">Home</a> --}}
                                @if(Auth::user()->role_id == 1)
                                  {{ 'Page' }}
                                @else
                                  <script>
                                    window.location = "/home";
                                  </script>
                                @endif
                            @else
                                <a class="btn btn-lg btn-outline-primary" href="{{ route('login') }}">Login</a>

                                @if (Route::has('register'))
                                    <a class="btn btn-lg btn-outline-primary" href="{{ route('register') }}">Register</a>
                                @endif
                            @endauth
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </body>
</html>
