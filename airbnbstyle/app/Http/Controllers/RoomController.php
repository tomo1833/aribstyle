<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

/**
 * ルーム部屋画面のコントローラ.
 */
class RoomController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index($room_id)
    {
        $now = Carbon::now();
        $dates =  $this->getCalendarDates($now->year, $now->month);

    	// ショップテーブルから店舗を取得する
        $room= DB::table('rooms')->find($room_id);

    	// 予約テーブルから予約情報を取得する
        $reservs = DB::table('reservs')->where('room_id', $room_id)->whereMonth('start_date', $now->month)->get();

    	// レビューテーブルからレビュー情報を取得する
        $reviews = DB::table('reviews')->join('users', 'user_id', '=', 'users.id')->select('reviews.*', 'users.name')->where('room_id', $room_id)->get();

        // 取得した値をビュー「guide/index」に渡す
        return view('room/index', ['room' => $room, 'dates' => $dates, 'target_month' => $now, 'reviews' => $reviews, 'reservs' => $reservs]);
    }
    
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function reservConfirm(Request $request)
    {
        // 認証チェック
        if (Auth::check()) {
            $user_id = Auth::id();
        } else {
            return redirect('/login/');
        }
        
        $room_id = $request->input('room_id');
        $stay_number = $request->input('stay_number');
        $start_date = $request->input('start_date');
        $end_date = $request->input('end_date');
        $price = $request->input('price');


        DB::table('reservs')->insert(['room_id' => $room_id, 'user_id' => $user_id, 'adults' => $stay_number, 'children' => 0, 'start_date' => $start_date, 'end_date' => $end_date, 'price' => $price]);

        $respons_json = response()->json([
            'start_date' => $start_date,
            'end_date' => $end_date,            
            'result' => 'sucess',
         ]);
         
        return $respons_json;

    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function reviewPost(Request $request)
    {
        // 認証チェック
        if (Auth::check()) {
            $user_id = Auth::id();
            $user_info = Auth::user();
        } else {
            return redirect('/login/');
        }
        $room_id = $request->input('room_id');
        $comment = $request->input('comment');

        DB::table('reviews')->insert(['room_id' => $room_id, 'user_id' => $user_id, 'comment' => $comment]);
   
        $respons_json = response()->json([
            'result' => 'sucess',
            'name' => $user_info['name'],
            'comment' => $comment,
         ]);

        return $respons_json;
    }


    /**
     * カレンダーメソッドです.
     * 
     */
    private function getCalendarDates($year, $month)
    {
        $dateStr = sprintf('%04d-%02d-01', $year, $month);
        $date = new Carbon($dateStr);
        // カレンダーを四角形にするため、前月となる左上の隙間用のデータを入れるためずらす
        $date->subDay($date->dayOfWeek);
        // 同上。右下の隙間のための計算。
        $count = 31 + $date->dayOfWeek;
        $count = ceil($count / 7) * 7;
        $dates = [];

        for ($i = 0; $i < $count; $i++, $date->addDay()) {
            // copyしないと全部同じオブジェクトを入れてしまうことになる
            $dates[] = $date->copy();
        }
        return $dates;
    }
}
