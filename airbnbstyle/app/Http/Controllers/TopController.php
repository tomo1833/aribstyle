<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;

/**
 * トップ画面のコントローラ.
 */
class TopController extends Controller
{
    
    /**
     * 表示メソッド.
     */
    public function index()
    {
        $now = Carbon::now();
        $dates =  $this->getCalendarDates($now->year, $now->month);
        
        // 取得した値をビュー「top/index」に渡す
        return view('top/index', ['dates' => $dates]);
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
