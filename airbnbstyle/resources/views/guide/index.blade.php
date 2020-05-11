<html lang="ja">

<head>
    <title>簡易民泊システム</title>
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css"
        integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <script <script src="https://code.jquery.com/jquery-3.4.1.min.js"
        integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.6/umd/popper.min.js"
        integrity="sha384-wHAiFfRlMFy6i5SRaxvfOCifBUQy1xHdJ/yoi7FRNXMRBu5WHdZYu1hA6ZOblgut"
        crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/js/bootstrap.min.js"
        integrity="sha384-B0UglyR+jN6CkvvICOB2joaf5I4l3gm9GU6Hc1og6Ls7i6U/mkkaduKaBhlAXv9k"
        crossorigin="anonymous"></script>

    <style>
        #map {
            height: 100%;
        }
    </style>
</head>

<body>
    <div>
        <header>
            <div class="row">
                <div class="col my-3 mx-5 ">
                    <a href="/">
                        <img class="" alt="" src="{{ asset('/images/LaravelLogo.png')}}" style="width:60px;">
                    </a>
                </div>
                <div class="col text-right my-3 mx-5">
                    @if (Route::has('login'))
                    <div class="top-right links">
                        @auth
                        <div class="btn btn-white rounded-pill shadow"><a class="text-dark" href="{{ route('logout') }}" style="text-decoration: none;"
                                onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">&nbsp;&nbsp;ログアウト&nbsp;&nbsp; </a>
                        </div>
                        <div class="btn btn-white rounded-pill shadow">
                            <a href="{{ route('register') }}" style="text-decoration: none;" class="text-dark">
                                <img src="{{ asset('/images/user_pic.png')}}" class="rounded-circle"
                                    style="width:20px;">&nbsp;&nbsp;{{ Auth::user()->name }}
                            </a>
                        </div>
                        @else
                        <div class="btn btn-white rounded-pill shadow">
                            <a href="{{ route('login') }}" class="text-dark"  style="text-decoration: none;">
                                &nbsp;&nbsp;ログイン&nbsp;&nbsp;
                            </a>
                        </div>
                        @if (Route::has('register'))
                        <div class="btn btn-white rounded-pill shadow">
                            <a href="{{ route('register') }}" class="text-dark"  style="text-decoration: none;">
                                &nbsp;&nbsp;会員登録&nbsp;&nbsp;
                            </a>
                        </div>
                        @endif
                        @endauth
                    </div>
                    @endif
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                </div>
            </div>
        </header>

        <main class="border-bottom mb-5 pb-5 px-5">
            <div class="row">
                <div class="col-6">
                    @foreach ($rooms as $room)
                    <div class="row">
                        <a href="/room/{{$room->id}}/" class="text-dark" style="text-decoration: none;">
                            <div class="card mb-3">
                                <div class="row no-gutters">
                                    <div class="col-md-4">
                                        <img src="{{asset('/images/' . $room->image_url)}}"
                                            class="card-img img-thumbnail" alt="..."
                                            style="　width: 250px;  height: 250px;object-fit: cover;">
                                    </div>
                                    <div class="col-md-8">
                                        <div class="card-body">
                                            <h3 class="card-title">{{ $room->name }}</h3>
                                            <p class="card-text"
                                                style="max-height:120px; overflow: hidden; text-overflow: ellipsis;">
                                                {{ $room->description }}</p>
                                            <p class="card-text"><small class="text-muted"></small></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                    @endforeach
                </div>
                <div class="col-6">
                    <div id="map" style="height: 100vh
                    ; width: 100%; auto 0;"></div>

                    <!-- jqueryの読み込む -->
                    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
                    <!-- google map api -->
                    <script
                        src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCsegc1QRdhIo3FTbQVvbaRgSku_tCHnts"></script>
                    <!-- js -->
                    <script type="text/javascript">
                        var map = new google.maps.Map(document.getElementById('map'), {
                            center: {
                                lat: 35.658584, //緯度を設定
                                lng: 139.7454316  //経度を設定
                            },
                            zoom: 12 //地図のズームを設定
                        });
                    </script>
                </div>
            </div>

            <section>
                <div class="row mx-5 mt-5 mb-2">
                    <div class="col-4">
                        <h3><span>企業情報</span></h3>
                    </div>
                    <div class="col-4">
                        <h3><span>ゲスト向け</span></h3>
                    </div>
                    <div class="col-4">
                        <h3><span>サポート</span></h3>
                    </div>
                </div>
                <div class="row  mx-5  mb-1">
                    <div class="col-4">
                        <span>お部屋を掲載</span>
                    </div>
                    <div class="col-4">
                        <span>お友達を招待</span>
                    </div>
                    <div class="col-4">
                        <span>ヘルプセンター</span>
                    </div>
                </div>
                <div class="row  mx-5  mb-1">
                    <div class="col-4">
                        <span>オリンピック</span>
                    </div>
                    <div class="col-4">
                        <span>オリンピック</span>
                    </div>
                    <div class="col-4">
                        <span>オリンピック</span>
                    </div>
                </div>
            </section>
        </main>
        <footer>
            <div class="row text-center mb-3">
                <div class="col "><strong> © 2020 簡易民泊システム, Inc. All rights reserved</strong></div>
            </div>
        </footer>
    </div>
</body>