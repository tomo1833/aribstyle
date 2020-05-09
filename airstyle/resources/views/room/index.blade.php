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

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <style>
        .fade-in {
            opacity: 0;
            animation-name: fadeIn;
            animation-duration: 5s;
            animation-iteration-count: 1;
            animation-fill-mode: forwards;
        }

        @keyframes fadeIn {
            0% {
                opacity: 0;
                transform: translateY(-500px);
            }

            50% {
                opacity: 1;
                transform: translateY(0);
            }

            100% {
                opacity: 0;
                transform: translateY(500px);
            }
        }
    </style>

    <script>

        $(function () {
            function setNumberText() {
                $stay_number_hidden = Number($('#adult_number').text()) + Number($('#children_number').text());
                $('#stay_number_hidden').val($stay_number_hidden);
                $stay_number = 'ゲスト' + (Number($('#adult_number').text()) + Number($('#children_number').text())) + '人';
                if (Number($('#toddler_number').text()) > 0) {
                    $stay_number = $stay_number + '，' + '乳幼児' + Number($('#toddler_number').text()) + '名';
                }
                $('#stay_number_text').text($stay_number)
            }

            function setDateText() {
                $price = $('#price').val();
                $date_count = 1;
                $start_date_str = $('#stay_start_date').val();
                $start_date = '';
                if ($start_date_str !== "") {
                    $start_date = new Date($start_date_str + ' 00:00:00');
                }
                $end_date_str = $('#stay_end_date').val();
                $end_date = '';
                if ($end_date_str !== "") {
                    $end_date = new Date($end_date_str + ' 00:00:00');
                }

                if ($start_date !== '' && $end_date !== '') {
                    $date_count = ($end_date.getTime() - $start_date.getTime()) / 60 / 60 / 24 / 1000;
                }

                $new_price = $price * $date_count;
                $('#date_count').text($date_count);
                $('#price_detail').text($new_price.toLocaleString());
                $('#price_sum').text($new_price.toLocaleString());
                $('#price_sum_hidden').val($new_price);
            }

            $('#reserv_data_table td').each(function () {
                $(this).on('click', function (index, element) {

                    $start_date_str = $('#stay_start_date').val();
                    $start_date = '';
                    if ($start_date_str !== "") {
                        $start_date = new Date($start_date_str + ' 00:00:00');
                    }

                    $end_date_str = $('#stay_end_date').val();
                    $end_date = '';
                    if ($end_date_str !== "") {
                        $end_date = new Date($end_date_str + ' 00:00:00');
                    }

                    $reserv_date = $(this).data('reserv_data');
                    $set_date = new Date($reserv_date);

                    $year = $set_date.getFullYear();
                    $month = $set_date.getMonth() + 1;
                    $day = $set_date.getDate();

                    if ($("#select_date").val() === '1') {

                        $('#stay_start_date').val($year + '/' + $month + '/' + $day);

                        $start_date_str = $('#stay_start_date').val();
                        $start_date = new Date($start_date_str + ' 00:00:00');

                        $('#reserv_data_table td').each(function () {
                            $reserv_date_tmp = $(this).data('reserv_data');
                            $date_chek_tmp = new Date($reserv_date_tmp);

                            if ($end_date !== "" && $date_chek_tmp.getTime() !== $end_date.getTime()) {
                                if ($start_date.getTime() <= $date_chek_tmp.getTime() && $end_date.getTime() >= $date_chek_tmp.getTime()) {
                                    $(this).addClass("bg-primary");
                                } else {
                                    $(this).removeClass("bg-primary");
                                }
                            }
                        });
                        $(this).addClass("bg-primary");
                    } else {

                        $('#stay_end_date').val($year + '/' + $month + '/' + $day);

                        $end_date_str = $('#stay_end_date').val();
                        $end_date = new Date($end_date_str + ' 00:00:00');

                        $('#reserv_data_table td').each(function () {

                            $reserv_date_tmp = $(this).data('reserv_data');
                            $date_chek_tmp = new Date($reserv_date_tmp);

                            if ($start_date !== "" && $date_chek_tmp.getTime() !== $start_date.getTime()) {
                                if ($start_date.getTime() <= $date_chek_tmp.getTime() && $end_date.getTime() >= $date_chek_tmp.getTime()) {
                                    $(this).addClass("bg-primary");
                                } else {
                                    $(this).removeClass("bg-primary");
                                }
                            }
                        });
                        $(this).addClass("bg-primary");
                    }
                    setDateText();
                    $('#stay_date').collapse('hide');
                });
            });

            $('#myModal').on('shown.bs.modal', function () {
            });

            $('#stay_start_date').on('click', function () {
                $("#select_date").val(1);
            });
            $('#stay_end_date').on('click', function () {
                $("#select_date").val(2);
            });

            $('#close_stay_number').on('click', function () {
                $('#stay_number_convolution').collapse('hide');
            });

            $('#adult_minus').on('click', function () {
                $count = Number($('#adult_number').text());
                if ($count > 1) {
                    $('#adult_number').text($count - 1);
                    setNumberText();
                }
            });

            $('#adult_plus').on('click', function () {
                $count = Number($('#adult_number').text());
                if ($count < 9) {
                    $('#adult_number').text($count + 1);
                    setNumberText();
                }
            });

            $('#children_minus').on('click', function () {
                $count = Number($('#children_number').text());
                if ($count > 0) {
                    $('#children_number').text($count - 1);
                    setNumberText();
                }
            });

            $('#children_plus').on('click', function () {
                $count = Number($('#children_number').text());
                if ($count < 9) {
                    $('#children_number').text($count + 1);
                    setNumberText();
                }
            });

            $('#toddler_minus').on('click', function () {
                $count = Number($('#toddler_number').text());
                if ($count > 0) {
                    $('#toddler_number').text($count - 1);
                    setNumberText();
                }
            });

            $('#toddler_plus').on('click', function () {
                $count = Number($('#toddler_number').text());
                if ($count < 9) {
                    $('#toddler_number').text($count + 1);
                    setNumberText();
                }
            });

            $('#review_post').on('click', function () {
                $room_id = $('#room_id').val();
                $comment = $('#comment').val();

                var json_request_data = {
                    "room_id": $room_id,
                    "comment": $comment
                };
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: '/room/reviewpost/',
                    type: 'POST',
                    contentType: "application/json",
                    data: JSON.stringify(json_request_data),
                    dataType: "json",
                }).done(function (result) {
                    $insert_html = ''
                        + '<div class="row">'
                        + '    <div class="card w-100">'
                        + '        <div class="card-body">'
                        + '           <h5 class="card-title">' + result.name + ' </h5>'
                        + '           <p class="card-text">' + result.comment + '</p>'
                        + '        </div>'
                        + '     </div>'
                        + '</div>'
                        + '';

                    $('#review_list').append($insert_html);
                    $('#comment').val('');
                    $('#fade_in').remove();
                    $result = '<div class="fixed-top fade-in" id="fade_in"><div class="row bg-success"  style="height: 100px"><div class="col d-flex align-items-center justify-content-center "> 更新しました。</div></div>';
                    $('footer').after($result);
                });
                $('#exampleModalCenterReview').modal('hide');
            });

            $('#reserv_confirm').on('click', function () {
                $room_id = $('#room_id').val();
                $start_date = $('#stay_start_date').val();
                $end_date = $('#stay_end_date').val();
                $stay_number = $('#stay_number_hidden').val();
                $price = $('#price_sum_hidden').val();

                var json_request_data = {
                    "room_id": $room_id,
                    "start_date": $start_date,
                    "end_date": $end_date,
                    "stay_number": $stay_number,
                    "price": $price,
                };
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: '/room/reservconfirm/',
                    type: 'POST',
                    contentType: "application/json",
                    data: JSON.stringify(json_request_data),
                    dataType: "json",
                }).done(function (data) {

                    $date_flg = false;
                    $('#reserv_data_main_table td').each(function () {

                        $start_date_str = $('#stay_start_date').val();
                        $start_date = '';
                        if ($start_date_str !== "") {
                            $start_date = new Date($start_date_str + ' 00:00:00');
                        }

                        $end_date_str = $('#stay_end_date').val();
                        $end_date = '';
                        if ($end_date_str !== "") {
                            $end_date = new Date($end_date_str + ' 00:00:00');
                        }

                        $reserv_date = $(this).data('reserv_data');
                        $set_date = new Date($reserv_date);
                    
                        if ($set_date.getTime() === $start_date.getTime()) {
                            $date_flg = true;
                        }
                    
                        if ($date_flg) {
                            $(this).addClass("bg-primary");
                        }
                        
                        if ($set_date.getTime() === $end_date.getTime()) {
                            $date_flg = false;
                        }
                    });

                    $('#fade_in').remove();
                    $result = '<div class="fixed-top fade-in" id="fade_in"><div class="row bg-success"  style="height: 100px"><div class="col d-flex align-items-center justify-content-center "> 更新しました。</div></div>';
                    $('footer').after($result);
                });;
                $('#exampleModalCenterReserv').modal('hide');
            });
        });

    </script>
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
                        <div class="btn btn-white rounded-pill shadow"><a class="text-dark" href="{{ route('logout') }}"
                                style="text-decoration: none;"
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
                            <a href="{{ route('login') }}" class="text-dark" style="text-decoration: none;">
                                &nbsp;&nbsp;ログイン&nbsp;&nbsp;
                            </a>
                        </div>
                        @if (Route::has('register'))
                        <div class="btn btn-white rounded-pill shadow">
                            <a href="{{ route('register') }}" class="text-dark" style="text-decoration: none;">
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

            <div class="row">
                <div class="col-6" style="height: 680px;">
                    <img src="{{ asset('/images/' . $room->image_url) }}">
                </div>
            </div>
        </header>

        <main class="border-bottom mb-5 pb-5">

            <div class="row mt-5 ">
                <div class="col-2">&nbsp;</div>
                <div class="col-4">
                    <h1>{{$room->name}}</h1>
                    <input type="hidden" name="room_id" id="room_id" value="{{$room->id}}">
                    <p>{{$room->description}}</p>

                    <h2>予約可能状況</h2>
                    <table class="table table-bordered" id="reserv_data_main_table">
                        <thead>
                            <tr>
                                @foreach (['日', '月', '火', '水', '木', '金', '土'] as $dayOfWeek)
                                <th>{{ $dayOfWeek }}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            <?php $date_flag = 'false'; ?>
                            @foreach ($dates as $date)
                            @if ($date->dayOfWeek == 0)
                            <tr>
                            @endif
                            
                            @foreach ($reservs as $reserv)
                                @if ($date->format('Y-m-d') == $reserv->start_date )
                                    <?php $date_flag = 'true'; ?>
                                @endif
                            @endforeach                               
                            @if ($date_flag == 'true')
                                <td class="bg-primary" data-reserv_data="{{ $date->format('Y/m/d 00:00')}}">
                            @else
                                <td data-reserv_data="{{ $date->format('Y/m/d 00:00')}}">
                            @endif
                                {{ $date->day }}
                            @foreach ($reservs as $reserv)
                                @if ($date->format('Y-m-d') == $reserv->end_date )
                                    <?php $date_flag = 'false'; ?>
                                @endif
                            @endforeach
                                </td>
                            @if ($date->dayOfWeek == 6)
                            </tr>
                            @endif
                            @endforeach
                        </tbody>
                    </table>
                    <h2>レビュー</h2>
                    @auth
                    <button type="button" class="btn btn-primary btn-block" data-toggle="modal"
                        data-target="#exampleModalCenterReview">
                        口コミを書く
                    </button>
                    @else
                    <button type="button" class="btn btn-block bg-secondary">
                        口コミを書く
                    </button>
                    @endauth
                    <div id="review_list">
                        @foreach ($reviews as $review)
                        <div class="row mt-3">
                            <div class="card w-100">
                                <div class="card-body">
                                    <h5 class="card-title">
                                        <img src="{{ asset('/images/user_pic.png')}}" class="rounded-circle">
                                        {{ $review->name }}
                                    </h5>
                                    <p class="card-text">{{ $review->comment }}</p>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                <div class="col-4">

                    <div class="card">
                        <div class="card-header">
                            <h3>{{ number_format($room->price) }} / 1泊</h3>
                            <input type="hidden" name="price" id="price" value="{{$room->price}}">
                        </div>
                        <div class="card-body">

                            <div class="form-group">

                                <label for="text1">日付</label>
                                <div class="form-control">
                                    <div class="row">
                                        <div class="col">
                                            <input type="input" class="border border-0" id="stay_start_date"
                                                data-toggle="collapse" data-target="#stay_date" placeholder="チェックイン">
                                        </div>
                                        <div class="col text-center">
                                            →
                                        </div>
                                        <input type="hidden" id="select_date" name="select_date" value="">
                                        <div class="col ">
                                            <input type="input" class="border border-0" id="stay_end_date"
                                                data-toggle="collapse" data-target="#stay_date" placeholder="チェックアウト">
                                        </div>
                                    </div>
                                </div>

                                <div id="stay_date" class="collapse">
                                    <table class="table table-bordered" id="reserv_data_table">
                                        <thead>
                                            <tr>
                                                @foreach (['日', '月', '火', '水', '木', '金', '土'] as $dayOfWeek)
                                                <th>{{ $dayOfWeek }}</th>
                                                @endforeach
                                            </tr>
                                        </thead>
                                            @foreach ($dates as $date)
                                            @if ($date->dayOfWeek == 0)
                                            <tr>
                                                @endif
                                                <td data-reserv_data="{{ $date->format('Y/m/d 00:00')}}">
                                                    {{ $date->day }}
                                                </td>
                                                @if ($date->dayOfWeek == 6)
                                            </tr>
                                            @endif
                                            @endforeach
                                    </table>
                                </div>

                            </div>

                            <div class="form-group">
                                <label for="text1">人数</label>
                                <div class="form-control" data-toggle="collapse" data-target="#stay_number_convolution"
                                    id="stay_number_text">ゲスト1人</div>
                                <input type="hidden" name="stay_number_hidden" id="stay_number_hidden" value="1">
                                <div id="stay_number_convolution" class="collapse">
                                    <div class="row mt-3">
                                        <div class="col">
                                            <strong>大人</strong>
                                        </div>
                                        <div class="col text-right">
                                            <button type="button" class="btn btn-primary rounded-circle p-0"
                                                style="width:2rem;height:2rem;" id="adult_minus">ー</button>
                                            <span class="col-md-4" id="adult_number">1</span>
                                            <button type="button" class="btn btn-primary rounded-circle p-0"
                                                style="width:2rem;height:2rem;" id="adult_plus">＋</button>
                                        </div>
                                    </div>
                                    <div class="row mt-3">
                                        <div class="col">
                                            <strong>小児</strong>
                                            <div>年齢2 - 12</div>
                                        </div>
                                        <div class="col text-right">
                                            <button type="button" class="btn btn-primary rounded-circle p-0"
                                                style="width:2rem;height:2rem;" id="children_minus">ー</button>
                                            <span class="col-md-4" id="children_number">0</span>
                                            <button type="button" class="btn btn-primary rounded-circle p-0"
                                                style="width:2rem;height:2rem;" id="children_plus">＋</button>
                                        </div>
                                    </div>
                                    <div class="row mt-3">
                                        <div class="col">
                                            <strong>幼児</strong>
                                            <div>2歳未満</div>
                                        </div>
                                        <div class="col text-right">
                                            <button type="button" class="btn btn-primary rounded-circle p-0"
                                                style="width:2rem;height:2rem;" id="toddler_minus">ー</button>
                                            <span class="col-md-4" id="toddler_number">0</span>
                                            <button type="button" class="btn btn-primary rounded-circle p-0"
                                                style="width:2rem;height:2rem;" id="toddler_plus">＋</button>
                                        </div>
                                    </div>
                                    <div class="row mt-3">
                                        <div class="col">
                                            <div>乳幼児は人数にカウントされません。</div>
                                        </div>
                                    </div>
                                    <div class="row mt-3">
                                        <div class="col text-right">
                                            <button type="button" class="btn btn-primary" id="close_stay_number">
                                                閉じる
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row mt-3">
                                    <div class="col">
                                        <span>{{ number_format($room->price) }} x <span id="date_count">1</span>泊</span>
                                    </div>
                                    <div class="col  text-right">
                                        <span id="price_detail">{{ number_format($room->price) }}</span>
                                    </div>
                                </div>
                                <div class="row mt-3">
                                    <div class="col">
                                        <span>合計額</span>
                                    </div>
                                    <div class="col  text-right">
                                        <span id="price_sum">{{ number_format($room->price) }}</span>
                                        <input type="hidden" name="price_sum_hidden" id="price_sum_hidden" value="">
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="card-footer">
                            <div class="form-group">
                                @auth
                                <button type="button" class="btn btn-primary btn-block" id="reserv_confirm">
                                    予約する
                                </button>
                                @else
                                <button type="button" class="btn btn-block bg-secondary">
                                    予約する
                                </button>
                                @endauth
                            </div>
                        </div>
                    </div>

                </div>
                <div class="col-2">&nbsp;</div>
            </div>
            <section>
                <div class="row  mx-5 mt-5 mb-2">
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

        <!-- Modal -->
        <div class="modal fade" id="exampleModalCenterReview" tabindex="-1" role="dialog"
            aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalCenterTitle">口コミを書く</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="comment">コメント</label>
                            <textarea class="form-control" rows=3 id="comment" name="comment" placeholder=""></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" id="review_post">口コミを書く</button>
                    </div>
                </div>
            </div>
        </div>

    </div>
</body>

</html>