<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>簡易民泊システム</title>

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css"
        integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"
        integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.6/umd/popper.min.js"
        integrity="sha384-wHAiFfRlMFy6i5SRaxvfOCifBUQy1xHdJ/yoi7FRNXMRBu5WHdZYu1hA6ZOblgut"
        crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/js/bootstrap.min.js"
        integrity="sha384-B0UglyR+jN6CkvvICOB2joaf5I4l3gm9GU6Hc1og6Ls7i6U/mkkaduKaBhlAXv9k"
        crossorigin="anonymous"></script>

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <script type="text/javascript">
        $(function(){

            // --------------------------------------------------
            // カレンダー処理
            // --------------------------------------------------
            function setDateText() {
                var price = $('#price').val();
                var date_count = 1;

                var start_date = '';
                var end_date = '';

                var start_date_str = $('#stay_start_date').val();
                var end_date_str = $('#stay_end_date').val();

                if (start_date_str !== "") {
                    start_date = new Date(start_date_str + ' 00:00:00');
                }
                if (end_date_str !== "") {
                    end_date = new Date(end_date_str + ' 00:00:00');
                }

                if (start_date !== '' && end_date !== '') {
                    date_count = (end_date.getTime() - start_date.getTime()) / 60 / 60 / 24 / 1000;
                }

                new_price = price * date_count;

                $('#date_count').text(date_count);
                $('#price_detail').text(new_price.toLocaleString());
                $('#price_sum').text(new_price.toLocaleString());
                $('#price_sum_hidden').val(new_price);
            }

            // 前月カレンダークック
            function click_back_calender() {
                var target_month = $('#back_month_hidden').val();
                var json_request_data = {
                    "target_month": target_month,
                };
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: '/changecal/',
                    type: 'POST',
                    contentType: "application/json",
                    data: JSON.stringify(json_request_data),
                    dataType: "json",
                }).done(function (data) {

                    var result_target_month = data.target_month.match(/(\d{4})-(\d{2})-(\d{2})/);
                    var result_target_month_hidden = data.target_month.match(/(\d{4}-\d{2}-\d{2})/)[1] + " 00:00:00";
                    var result_back_month_hidden = data.back_month.match(/(\d{4}-\d{2}-\d{2})/)[1] + " 00:00:00";
                    var result_next_month_hidden = data.next_month.match(/(\d{4}-\d{2}-\d{2})/)[1] + " 00:00:00";
                    
                    var calender_html = '' 
                                + '<div id="stay_date" class="collapse show">'
                                + '    <div class="row">'
                                + '        <div class="col-4 text-center">'
                                + '            <button id="back_month" type="button" class="btn btn-primary">'
                                + '                前月'
                                + '            </button>'
                                + '            <input type="hidden" id="back_month_hidden" name="back_month_hidden" value="' + result_back_month_hidden +'">'
                                + '        </div>'
                                + '        <div class="col-4 text-center">'
                                + '            <h3>' +  result_target_month[1]  + '年' + result_target_month[2] + '月'  + '</h3>'
                                + '            <input type="hidden" id="target_month" name="target_month" value="' + result_target_month_hidden + '">'
                                + '        </div>'
                                + '        <div class="col-4 text-center">'
                                + '            <button id="next_month" type="button" class="btn btn-primary">'
                                + '                翌月'
                                + '            </button>'
                                + '            <input type="hidden" id="next_month_hidden" name="next_month_hidden" value="' + result_next_month_hidden+ '">'
                                + '        </div>'
                                + '    </div><br>'
                                + '    <table class="table table-bordered" id="reserv_data_table">'
                                + '        <thead>'
                                + '        <tr>'
                                + '            <th>日</th>'
                                + '            <th>月</th>'
                                + '            <th>火</th>'
                                + '            <th>水</th>'
                                + '            <th>木</th>'
                                + '            <th>金</th>'
                                + '            <th>土</th>'
                                + '        </tr>'
                                + '        </thead>'
                                + '        <tbody>';
                                var week_count = 0;
                                var current_date = '';
                                var current_date_format = '';
                                for (count = 0; count < data.dates.length; count ++) {
                                    if (week_count === 0) {
                                        calender_html += '<tr>'; 
                                    }
                                    current_date = data.dates[count].match(/(\d{4})-(\d{2})-(\d{2})/);
                                    current_date_format = current_date[0] + ' 00:00';
                                    calender_html += ''
                                                + '<td data-reserv_data="'+ current_date_format + '">'
                                                + data.dates[count].match(/\d{4}-\d{2}-(\d{2})/)[1]
                                                + '</td">';
                                    week_count ++;
                                    if (week_count === 7) {
                                        calender_html += '<tr>';
                                        week_count = 0;
                                    }
                                }
                        calender_html +=''
                                + '        </tbody>'
                                + '    </table>'
                                + '</div>';

                    $("#stay_calender").html(calender_html);

                    // 前月クリック
                     $('#back_month')[0].addEventListener('click', click_back_calender);

                    // 翌月クリック
                     $('#next_month')[0].addEventListener('click', click_next_calender);


                     // カレンダーのテーブルのtdクラス数処理を繰り返す。
                     $('#reserv_data_table td').each(function (index, value) {
                         // 日付クリック(クリックされた日付の箇所で実行される)
                         $('#reserv_data_table td')[index].addEventListener('click', cal_click_function);
                     });
                });
            }

            // 翌月カレンダークック
            function click_next_calender() {
                var target_month = $('#next_month_hidden').val();
                var json_request_data = {
                    "target_month": target_month,
                };
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: '/changecal/',
                    type: 'POST',
                    contentType: "application/json",
                    data: JSON.stringify(json_request_data),
                    dataType: "json",
                }).done(function (data) {

                    var result_target_month = data.target_month.match(/(\d{4})-(\d{2})-(\d{2})/);
                    var result_target_month_hidden = data.target_month.match(/(\d{4}-\d{2}-\d{2})/)[1] + " 00:00:00";
                    var result_back_month_hidden = data.back_month.match(/(\d{4}-\d{2}-\d{2})/)[1] + " 00:00:00";
                    var result_next_month_hidden = data.next_month.match(/(\d{4}-\d{2}-\d{2})/)[1] + " 00:00:00";
                    
                    var calender_html = '' 
                                + '<div id="stay_date" class="collapse show">'
                                + '    <div class="row">'
                                + '        <div class="col-4 text-center">'
                                + '            <button id="back_month" type="button" class="btn btn-primary">'
                                + '                前月'
                                + '            </button>'
                                + '            <input type="hidden" id="back_month_hidden" name="back_month_hidden" value="' + result_back_month_hidden +'">'
                                + '        </div>'
                                + '        <div class="col-4 text-center">'
                                + '            <h3>' +  result_target_month[1]  + '年' + result_target_month[2] + '月'  + '</h3>'
                                + '            <input type="hidden" id="target_month" name="target_month" value="' + result_target_month_hidden + '">'
                                + '        </div>'
                                + '        <div class="col-4 text-center">'
                                + '            <button id="next_month" type="button" class="btn btn-primary">'
                                + '                翌月'
                                + '            </button>'
                                + '            <input type="hidden" id="next_month_hidden" name="next_month_hidden" value="' + result_next_month_hidden+ '">'
                                + '        </div>'
                                + '    </div><br>'
                                + '    <table class="table table-bordered" id="reserv_data_table">'
                                + '        <thead>'
                                + '        <tr>'
                                + '            <th>日</th>'
                                + '            <th>月</th>'
                                + '            <th>火</th>'
                                + '            <th>水</th>'
                                + '            <th>木</th>'
                                + '            <th>金</th>'
                                + '            <th>土</th>'
                                + '        </tr>'
                                + '        </thead>'
                                + '        <tbody>';
                                var week_count = 0;
                                var current_date = '';
                                var current_date_format = '';
                                for (count = 0; count < data.dates.length; count ++) {
                                    if (week_count === 0) {
                                        calender_html += '<tr>'; 
                                    }
                                    current_date = data.dates[count].match(/(\d{4})-(\d{2})-(\d{2})/);
                                    current_date_format = current_date[0] + ' 00:00';
                                    calender_html += ''
                                                + '<td data-reserv_data="'+ current_date_format + '">'
                                                + data.dates[count].match(/\d{4}-\d{2}-(\d{2})/)[1]
                                                + '</td">';
                                    week_count ++;
                                    if (week_count === 7) {
                                        calender_html += '<tr>';
                                        week_count = 0;
                                    }
                                }
                        calender_html +=''
                                + '        </tbody>'
                                + '    </table>'
                                + '</div>';

                    $("#stay_calender").html(calender_html);

                    // 前月クリック
                     $('#back_month')[0].addEventListener('click', click_back_calender);

                    // 翌月クリック
                     $('#next_month')[0].addEventListener('click', click_next_calender);
            
                     // カレンダーのテーブルのtdクラス数処理を繰り返す。
                     $('#reserv_data_table td').each(function (index, value) {
                         // 日付クリック(クリックされた日付の箇所で実行される)
                         $('#reserv_data_table td')[index].addEventListener('click', cal_click_function);
                     });

                });
            }

            // 前月クリック
            $('#stay_date').on('click', '#back_month', click_back_calender);

            // 翌月クリック
            $('#stay_date').on('click', '#next_month', click_next_calender);

            // チェックイン テキストクリック
            $('#stay_start_date').on('click', function () {
                // チェックイン
                $("#select_date").val(1);
            });
            // チェックアウト テキストクリック
            $('#stay_end_date').on('click', function () {
                // チェックアウト
                $("#select_date").val(2);
            });

            // カレンダークリック
            function cal_click_function() {
                // チェックイン 初期化
                var start_date = '';
                // チェックアウト 初期化
                var end_date = '';
                // チェックインのテキストボックスの値取得
                var start_date_textbox = $('#stay_start_date').val();
                // チェックアウトのテキストボックスの値取得
                var end_date_textbox = $('#stay_end_date').val();

                // チェックインのテキストボックスに値がセットされている場合
                if (start_date_textbox !== '') {
                    // 日付型に変換
                    start_date = new Date(start_date_textbox + ' 00:00:00');
                }
                // チェックアウトのテキストボックスに値がセットされている場合
                if (end_date_textbox !== '') {
                    // 日付型に変換
                    end_date = new Date(end_date_textbox + ' 00:00:00');
                }

                // 選択された日付
                var reserv_date_trtag = $(this).data('reserv_data');
                // 日付型に変換
                var set_date = new Date(reserv_date_trtag);
                // 年月日を分解して取得
                var year = set_date.getFullYear();
                var month = set_date.getMonth() + 1;
                var day = set_date.getDate();

                // カレンダーのテーブルのtdクラス数処理を繰り返す。
                var reserv_date_tmp = '';
                // カレンダーの日付を初期化
                var date_chek_tmp = '';
                // チェックインのカレンダーが選択された場合
                if ($("#select_date").val() === '1') {
                    // 選択された日付をテキストボックスにセット(YYYY/mm/dd形式)
                    $('#stay_start_date').val(year + '/' + month + '/' + day);
                    // 選択された日付を検索用の隠し項目にセット(YYYY-mm-dd形式)
                    $('#start_date').val(year + '-' + month + '-' + day);
                    // カレンダーの数だけ繰り返す
                    $('#reserv_data_table td').each(function () {
                        // カレンダーの日付を格納
                        reserv_date_tmp = $(this).data('reserv_data');
                        // 日付型に変換
                        date_chek_tmp = new Date(reserv_date_tmp);
                        // チェックアウトが設定かつカレンダーのtrタグの日付と終了日が異なる
                        if (end_date !== '' && date_chek_tmp.getTime() !== end_date.getTime()) {
                            // チェックインからチェックアウトの期間のタグの色をつける
                            if (set_date.getTime() <= date_chek_tmp.getTime() && end_date.getTime() >= date_chek_tmp.getTime()) {
                                $(this).addClass("bg-primary");
                            // タグの色を消す
                            } else {
                                $(this).removeClass("bg-primary");
                            }
                        }
                    });
                // チェックアウトのカレンダーが選択された場合
                } else {
                    // 選択された日付をテキストボックスにセット(YYYY/mm/dd形式)
                    $('#stay_end_date').val(year + '/' + month + '/' + day);
                    // 選択された日付を検索用の隠し項目にセット(YYYY-mm-dd形式)
                    $('#end_date').val(year + '-' + month + '-' + day);
                    // カレンダーの数だけ繰り返す
                    $('#reserv_data_table td').each(function () {
                        // カレンダーの日付を格納
                        reserv_date_tmp = $(this).data('reserv_data');
                        // カレンダーの数だけ繰り返す
                        date_chek_tmp = new Date(reserv_date_tmp);
                        // チェックインが設定かつカレンダーのtrタグの日付と開始日が異なる
                        if (start_date !== "" && date_chek_tmp.getTime() !== start_date.getTime()) {
                            // チェックインからチェックアウトの期間のタグの色をつける
                            if (start_date.getTime() <= date_chek_tmp.getTime() && set_date.getTime() >= date_chek_tmp.getTime()) {
                                $(this).addClass("bg-primary");
                            // タグの色を消す
                            } else {
                                $(this).removeClass("bg-primary");
                            }
                        }
                    });
                }
                // 選択タグの色をつける
                $(this).addClass("bg-primary");
                // カレンダーを消す
                $('#stay_date').collapse('hide');
            }
            
            // カレンダーのテーブルのtdクラス数処理を繰り返す。
            $('#reserv_data_table td').each(function (index, value) {
                // 日付クリック(クリックされた日付の箇所で実行される)
                $('#reserv_data_table td')[index].addEventListener('click', cal_click_function);
            });
            
            // --------------------------------------------------
            // 人数処理
            // --------------------------------------------------
            // 人数制御関数実行
            function setNumberText() {
                // 大人と子供の人数を加算します
                var adults = Number($('#adult_number').text()) + Number($('#children_number').text());
                // 幼児の人数を取得します
                var children = Number($('#toddler_number').text());
                // 大人の人数を隠し項目にセットします
                $('#adults').val(adults);
                // 幼児の人数を隠し項目にセットします
                $('#children').val(children);
                // テキストにセットする値を加工します。
                var stay_number_text = 'ゲスト' + adults + '人';
                // 幼児がひとり以上の場合
                if (Number($('#toddler_number').text()) > 0) {
                    // 幼児の文言を追加する
                    stay_number_text = stay_number_text + '，' + '乳幼児' + children + '名';
                }
                // 人数をセットする
                $('#stay_number_text').val(stay_number_text);
            }

            // 大人 マイナス 人数クリック
            $('#adult_minus').on('click', function () {
                // 大人人数取得
                var count = Number($('#adult_number').text());
                // 2人以上の場合
                if (count > 1) {
                    // 人数を減らしてテキスト更新
                    $('#adult_number').text(count - 1);
                    //人数制御関数実行
                    setNumberText();
                }
            });

            // 大人 プラス 人数クリック
            $('#adult_plus').on('click', function () {
                // 大人人数取得
                var count = Number($('#adult_number').text());
                // 8人以下の場合
                if (count < 9) {
                    // 人数を増やしてテキスト更新
                    $('#adult_number').text(count + 1);
                    //人数制御関数実行
                    setNumberText();
                }
            });

            // 子供 マイナス 人数クリック
            $('#children_minus').on('click', function () {
                // 子供人数取得
                var count = Number($('#children_number').text());
                // 1人以上の場合
                if (count > 0) {
                    // 人数を減らしてテキスト更新
                    $('#children_number').text(count - 1);
                    //人数制御関数実行
                    setNumberText();
                }
            });

            // 子供 プラス 人数クリック
            $('#children_plus').on('click', function () {
                // 子供人数取得
                var count = Number($('#children_number').text());
                // 8人以下の場合
                if (count < 9) {
                    // 人数を増やしてテキスト更新
                    $('#children_number').text(count + 1);
                    //人数制御関数実行
                    setNumberText();
                }
            });

            // 幼児 マイナス 人数クリック
            $('#toddler_minus').on('click', function () {
                // 幼児人数取得
                var count = Number($('#toddler_number').text());
                // 1人以上の場合
                if (count > 0) {
                    // 人数を減らしてテキスト更新
                    $('#toddler_number').text(count - 1);
                    //人数制御関数実行
                    setNumberText();
                }
            });

            // 幼児 プラス 人数クリック
            $('#toddler_plus').on('click', function () {
                // 幼児人数取得
                var count = Number($('#toddler_number').text());
                if (count < 9) {
                    // 人数を増やしてテキスト更新
                    $('#toddler_number').text(count + 1);
                    //人数制御関数実行
                    setNumberText();
                }
            });

            // 閉じる ボタン クリック
            $('#close_stay_number').on('click', function () {
                $('#stay_number_convolution').collapse('hide');
            });
        });
    </script>

</head>

<body>
    <div class="container-fluid">
        <header>
            <div class="row">
                <div class="col-auto text-left py-3 pl-5">
                    <a href="/">
                        <img class="" alt="" src="{{ asset('/images/LaravelLogo.png')}}" style="width:60px;">
                    </a>
                </div>
                <div class="col"></div>
                <div class="col-auto text-right py-3 pr-5">
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
                <div class="col px-4">
                    <fieldset class="form-group">
                        <legend>宿泊先</legend>
                        <form method="get" action="s/" class="form-inline">
                            <div class="form-group col-lg-3 border border-1">
                                <input type="text" id="location" name="location" class="form-control-lg border border-0"
                                    placeholder="ロケーション" style="width:100%;">
                            </div>
                            <div class="form-group col-lg-4 border border-1">
                                <div style="width:100%;">
                                    <div class="row">
                                        <div class="col-lg-5">
                                            <input type="input" id="stay_start_date" name="start_date" class="form-control-lg border border-0"
                                                placeholder="チェックイン" style="width:100%;"
                                                data-toggle="collapse" data-target="#stay_date" autocomplete="off" readonly>
                                            <input type="hidden" id="start_date" name="start_date" value="">        
                                        </div>
                                        <div class="col-lg-2 text-center form-control-lg">
                                            →
                                        </div>
                                        <div class="col-lg-5">
                                            <input type="input" id="stay_end_date" name="end_date" class=" form-control-lg border border-0 " 
                                                placeholder="チェックアウト" style="width:100%;" 
                                                data-toggle="collapse" data-target="#stay_date" autocomplete="off" readonly>
                                            <input type="hidden" id="end_date" name="end_date" value="">
                                        </div>
                                        <input type="hidden" id="select_date" name="select_date" value="">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group col-lg-3 border border-1">
                                <input type="text" id="stay_number_text" class="form-control-lg border border-0"
                                    placeholder="人数"style="width:100%;" 
                                    data-toggle="collapse" data-target="#stay_number_convolution" autocomplete="off" readonly>
                                <input type="hidden" id="adults" name="adults" value="0">
                                <input type="hidden" id="children" name="children" value="0">
                            </div>
                            <div class="form-group col-lg-2">
                                <button type="submit" class="btn-lg btn-danger">&nbsp;&nbsp;探す&nbsp;&nbsp;</button>
                            </div>
                        </form>
                    </fieldset>
                </div>
            </div>
            
            <div class="row">
                <div class="col-lg-3"></div>
                <div class="col-lg-4">
                    <div id="stay_calender" class="form-group">
                        <div id="stay_date" class="collapse">
                            <div class="row">
                                <div class="col-4 text-center">
                                    <button id="back_month" type="button" class="btn btn-primary">
                                        前月
                                    </button>
                                    <input type="hidden" id="back_month_hidden" name="back_month_hidden" value="{{$back_month}}">
                                </div>
                                <div class="col-4 text-center">
                                    <h3>{{ $target_month->format('Y年m月')}}</h3>
                                    <input type="hidden" id="target_month" name="target_month" value="{{$target_month}}">
                                </div>
                                <div class="col-4 text-center">
                                    <button id="next_month" type="button" class="btn btn-primary">
                                        翌月
                                    </button>
                                    <input type="hidden" id="next_month_hidden" name="next_month_hidden" value="{{$next_month}}">
                                </div>
                            </div>
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
                
                <div class="col-lg-3">
                    <div id="stay_number_convolution" class="collapse border p-2">
                        <div class="row mt-3">
                            <div class="col">
                                <strong>大人</strong>
                            </div>
                            <div class="col text-right">
                                <button type="button" id="adult_minus" class="btn btn-primary rounded-circle p-0"
                                    style="width:2rem;height:2rem;">ー</button>
                                <span id="adult_number" class="col-md-4">1</span>
                                <button type="button" id="adult_plus" class="btn btn-primary rounded-circle p-0"
                                    style="width:2rem;height:2rem;">＋</button>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col">
                                <strong>小児</strong>
                                <div>年齢2 - 12</div>
                            </div>
                            <div class="col text-right">
                                <button type="button" id="children_minus" class="btn btn-primary rounded-circle p-0"
                                    style="width:2rem;height:2rem;">ー</button>
                                <span id="children_number" class="col-md-4">0</span>
                                <button type="button" id="children_plus" class="btn btn-primary rounded-circle p-0"
                                    style="width:2rem;height:2rem;">＋</button>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col">
                                <strong>幼児</strong>
                                <div>2歳未満</div>
                            </div>
                            <div class="col text-right">
                                <button type="button" id="toddler_minus" class="btn btn-primary rounded-circle p-0"
                                    style="width:2rem;height:2rem;">ー</button>
                                <span id="toddler_number" class="col-md-4">0</span>
                                <button id="toddler_plus" type="button" class="btn btn-primary rounded-circle p-0"
                                    style="width:2rem;height:2rem;">＋</button>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col">
                                <div>乳幼児は人数にカウントされません。</div>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col text-right">
                                <button type="button" id="close_stay_number" class="btn btn-primary">
                                    閉じる
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-2"></div>
            </div>
        </asign>
        <h2 class="px-5">
            <span class="text-danger">laravelで作成した簡易民泊システムです。</span>
        </h2>
        <main class="border-bottom pb-5 px-5">
            <section class=" bg-dark text-white px-lg-5 py-lg-5">
                <h3>
                    <p>ご紹介</p>
                </h3>
                <div>
                    <p>簡易民泊システムです。</p>
                </div>
                <div class="row center-block mx-1">
                    <div class="col">
                        <img class="bg-primary img-fluid" alt="" src="{{ asset('/images/stay_001.jpg')}}">
                    </div>
                </div>
            </section>
            <section>
                <div class="row px-5 pt-5 pb-2">
                    <div class="col-lg-4">
                        <div class="row">
                            <h3><span>企業情報</span></h3>
                        </div>
                        <div class="row">
                            <span>お部屋を掲載</span>
                        </div>
                        <div class="row">
                            <span>オリンピック</span>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="row">
                            <h3><span>ゲスト向け</span></h3>
                        </div>
                        <div class="row">
                            <span>お友達を招待</span>
                        </div>
                        <div class="row">
                            <span>オリンピック</span>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="row">
                            <h3><span>サポート</span></h3>
                        </div>
                        <div class="row">
                            <span>ヘルプセンター</span>
                        </div>
                        <div class="row">
                            <span>オリンピック</span>
                        </div>
                    </div>
                </div>
            </section>
        </main>
        <footer>
            <div class="row text-center py-3">
                <div class="col"><strong> © 2020 簡易民泊システム, Inc. All rights reserved</strong></div>
            </div>
        </footer>
    </div>
</body>

</html>