<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

/**
 * ガイド画面のコントローラ.
 */
class GuideController extends Controller
{
    /**
     * 表示メソッド.
     */
    public function index(Request $request)
    {
        $location = $request->input('location');
        $start_date = $request->input('start_date');
        $end_date = $request->input('end_date');
        $adults = $request->input('adults');
        $children = $request->input('children');

        // ショップテーブルから店舗を取得する
        $rooms = DB::table('rooms')
                ->when($location, function ($query) use ($location) {
                    return $query->where('address', 'LIKE', '%'. $location .'%');
                })
                ->when($adults, function ($query) use ($adults) {
                    return $query->where('adults', '>=', $adults);
                })
                ->when($children, function ($query) use ($children) {
                    return $query->where('children', '>=', $children);
                })
                ->when($start_date or $end_date, function ($query) use ($start_date, $end_date) {
                    return $query->whereNotExists(function ($query) use ($start_date, $end_date) {
                        return $query->select('room_id')
                            ->from('reservs')
                                ->when($start_date, function ($query) use ($start_date) {
                                    return $query->where(function ($query) use ($start_date) {
                                        return $query->where('start_date', '<=', $start_date)
                                            ->where('end_date', '>=', $start_date);
                                    });
                                })
                                ->when($end_date, function ($query) use ($end_date) {
                                    return $query->orWhere(function ($query) use ($end_date) {
                                        return $query->where('start_date', '<=', $end_date)
                                            ->where('end_date', '>=', $end_date);
                                    });
                                })
                            ->groupBy('room_id')
                            ->havingRaw('room_id = rooms.id');
                    });
                })
                ->get();

        // 取得した値をビュー「guide/index」に渡す
        return view('guide/index', ['rooms' => $rooms]);
    }
}
