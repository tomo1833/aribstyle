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
    textarea:focus, input:focus, input[type]:focus, .uneditable-input:focus {
      outline: 0;
      box-shadow: none;
      border: 2px solid #FF9800;
      background-color: #ffffff;
    }
    </style>

    <script>
        $(function () {
            function setNumberText() {
                $adults = Number($('#adult_number').text()) + Number($('#children_number').text());
                $children = Number($('#toddler_number').text());
                $('#adults').val($adults);
                $('#children').val($children);
                $stay_number = 'ゲスト' + $adults + '人';
                if (Number($('#toddler_number').text()) > 0) {
                    $stay_number = $stay_number + '，' + '乳幼児' + $children + '名';
                }
                $('#stay_number_text').val($stay_number);
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
                        $('#start_date').val($year + '-' + $month + '-' + $day);

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
                        $('#end_date').val($year + '-' + $month + '-' + $day);

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
        </header>
        <asign>
            <div class="row">
                <div class="col-12 ml-4">
                    <fieldset class="form-group">
                        <legend>宿泊先</legend>
                        <form method="get" action="s/" class="form-inline">
                            <div class="form-group col-3 border border-1">
                                <input type="text" id="location" name="location" class="form-control-lg border border-0"
                                    placeholder="ロケーション" style="width:100%;">
                            </div>
                            <div class="form-group col-4 border border-1">
                                <div class="form-control-lg ">
                                    <div class="row justify-content-between">
                                        <div class="col-5">
                                            <div></div>
                                            <input type="input" class="border border-0 " id="stay_start_date"
                                                data-toggle="collapse" data-target="#stay_date" placeholder="チェックイン"
                                                style="width:100%;" name="start_date" autocomplete="off" readonly>
                                        </div>
                                        <input type="hidden" id="start_date" name="start_date" value="">
                                        <div class="col-2 px-5">→</div>
                                        <div class="col-5">
                                            <input type="input" class="border border-0 " id="stay_end_date"
                                                data-toggle="collapse" data-target="#stay_date" placeholder="チェックアウト"
                                                style="width:100%;" name="end_date" autocomplete="off" readonly>
                                        </div>
                                        <input type="hidden" id="end_date" name="end_date" value="">
                                        <input type="hidden" id="select_date" name="select_date" value="">
                                    </div>
                                </div>

                            </div>
                            <div class="form-group col-3 border border-1">
                                <input type="text" class="form-control-lg border border-0"
                                    placeholder="人数" data-toggle="collapse" data-target="#stay_number_convolution"
                                    id="stay_number_text" style="width:100%;" autocomplete="off" readonly>
                                <input type="hidden" name="adults" id="adults" value="0">
                                <input type="hidden" name="children" id="children" value="0">
                            </div>
                            <div class="form-group col-2"><button type="submit" class="btn-lg btn-danger">&nbsp;&nbsp;探す&nbsp;&nbsp;</button>
                            </div>
                        </form>
                    </fieldset>
                </div>
            </div>
            <div class="row">
                <div class="col-3"></div>
                <div class="col-4">

                    <div class="form-group">

                        <div id="stay_date" class="collapse">
                            <table class="table table-bordered" id="reserv_data_table">
                                <thead>
                                    <tr>
                                        @foreach (['日', '月', '火', '水', '木', '金', '土'] as $dayOfWeek)
                                        <th>{{ $dayOfWeek }}</th>
                                        @endforeach
                                    </tr>
                                </thead>
                                <tbody>
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
                                </tbody>
                            </table>
                        </div>
                    </div>


                </div>
                <div class="col-3">
                    <div id="stay_number_convolution" class="collapse border p-2">
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
                <div class="col-2"></div>
            </div>
            <h2 class="mx-5">
                <span class="text-danger">laravelで作成した簡易民泊システムです。</span>
            </h2>
        </asign>

        <main class="border-bottom mb-5 pb-5">
            <div class="bg-dark pb-5">
                <section class="text-white py-4 mx-5">
                    <h3>
                        <p>ご紹介</p>
                    </h3>
                    <div>
                        <p>簡易民泊システムです。</p>
                    </div>
                </section>
                <section>
                    <div class="row center-block mx-1">
                        <div class="col-12">
                            <img class="bg-primary" alt="" src="{{ asset('/images/stay_001.jpg')}}" style="width:100%;">
                        </div>
                    </div>
                </section>
            </div>
            <div>
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
            </div>
        </main>

        <footer>
            <div class="row text-center mb-3">
                <div class="col "><strong> © 2020 簡易民泊システム, Inc. All rights reserved</strong></div>
            </div>
        </footer>
    </div>
</body>

</html>