<?php

namespace App\Http\Controllers\Api;
use App\Models\Order;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Resources\OrderResource;
use App\Models\OrderDet;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
class OrderController extends Controller
{
    //
    public function index()
    {
        //get all Orders
        $orders = Order::where('flag_ord', '<', 4)
               ->orderBy('tgl_ord','desc')
               ->take(10)
               ->get();

        //return collection of posts as a resource
        return new OrderResource(true, 'List Data Orders', $orders);
    }

    public function order_by_store($store_id)
    {
        //get all Orders
        $orders = Order::select('id_ord','receiver_name','phone_number','tgl_ord','flag_ord','tgl_confirm','tgl_delivery','tgl_close','preorder_status')
               ->where('flag_ord', '<', 4)
               ->where('store_ord', $store_id)
               ->orderBy('tgl_ord','desc')
               ->take(10)
               ->get();

        //return collection of posts as a resource
        return new OrderResource(true, 'List Data Orders', $orders);
    }

    public function active_orders()
    {
        //get all Orders
        $orders = Order::select('id_ord','receiver_name','phone_number','tgl_ord','flag_ord','tgl_confirm','tgl_delivery','tgl_close','preorder_status')
               ->where('flag_ord', '<', 4)
               ->orderBy('tgl_ord','desc')
               ->get();

        //return collection of posts as a resource
        return new OrderResource(true, 'List Data Orders', $orders);
    }

    public function get_det_item_by_mk($mk)
    {
        //get all Orders
        $orders = OrderDet::select('no_urut','plu','nama_item','qty','harga','discount_group','no_juklak')
               ->where('id_ord', $mk)
               ->orderBy('no_urut','asc')
               ->get();

        //return collection of posts as a resource
        return new OrderResource(true, 'List Data Item', $orders);
    }

    public function notif_panel($store_id)
    {
        //get progress order
        $progress_order = Order::select(Order::raw('count(*) as PROGRESS_ORDER, store_ord'))
        ->where('store_ord', $store_id)
        ->whereBetween('flag_ord', [1, 3])
        ->whereNotNull('waktu_kirim')
        ->groupBy('store_ord');
        
        $new_order = Order::select(Order::raw('count(*) as NEW_ORDER, store_ord'))
        ->where('store_ord', $store_id)
        ->where('flag_ord', 0)
        ->whereNotNull('waktu_kirim')
        ->groupBy('store_ord');

        $defisit_order = Order::select(Order::raw('count(*) as DEFISIT_ORDER, store_ord'))
        ->where('store_ord', $store_id)
        ->where('flag_kurang','Y')
        ->whereBetween('flag_ord', [0,3])
        ->whereNotNull('waktu_kirim')
        ->groupBy('store_ord');

        $total_order = Order::select(Order::raw('count(*) as TOTAL_ORDER, store_ord'))
        ->where('store_ord', $store_id)
        ->whereBetween('flag_ord', [0,4])
        ->whereNotNull('waktu_kirim')
        ->groupBy('store_ord');

        $preorder = Order::select(Order::raw('count(*) as PREORDER, store_ord'))
        ->where('store_ord', $store_id)
        ->whereBetween('flag_ord', [0,3])
        ->whereOr('preorder_status', 1)
        ->whereOr('preorder_status', 2)
        ->whereNotNull('waktu_kirim')
        ->groupBy('store_ord');

        $join_all =Order::distinct()->select('kring_ord.store_ord','total_order','new_order','defisit_order','progress_order','preorder')
        ->where('kring_ord.store_ord', $store_id)
        ->leftJoinSub($total_order, 'total_order', function ($join) {
            $join->on('kring_ord.store_ord', '=', 'total_order.store_ord');
        })
        ->leftJoinSub($new_order, 'new_order', function ($join) {
            $join->on('kring_ord.store_ord', '=', 'new_order.store_ord');
        })
        ->leftJoinSub($progress_order, 'progress_order', function ($join) {
            $join->on('kring_ord.store_ord', '=', 'progress_order.store_ord');
        })
        ->leftJoinSub($defisit_order, 'defisit_order', function ($join) {
            $join->on('kring_ord.store_ord', '=', 'defisit_order.store_ord');
        })
        ->leftJoinSub($preorder, 'preorder', function ($join) {
            $join->on('kring_ord.store_ord', '=', 'preorder.store_ord');
        })
        ->get('store_ord');


        //return collection of posts as a resource
        return new OrderResource(true, 'Notif Dashboard', $join_all);
    }

    public function konfirmasi(Request $request, $id_order)
    {
        //define validation rules
        $validator = Validator::make($request->all(), [
            'flag_ord'     => 'required',
            'nik_konfirm'     => 'required',
        ]);

        //check if validation fails
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $order = Order::select('id_ord','flag_ord','tgl_confirm','u_store')->find($id_order);

        $order->update([
            'flag_ord'     => $request->flag_ord,
            'u_store'      => $request->nik_konfirm,
            'tgl_confirm'      => Carbon::now()->timezone('Asia/Jakarta')->toDateTimeString(),
        ]);
        
        //return response
        return new OrderResource(true, 'Order Midi Kring Berhasil dikonfirmasi!', $order);
    }
}

