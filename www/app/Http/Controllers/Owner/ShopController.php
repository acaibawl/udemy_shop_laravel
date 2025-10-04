<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Shop;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ShopController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:owners');
        $this->middleware(function (Request $request, $next) {
//            dd($request->route()->parameter('shop')); //文字列
//            dd(Auth::id()); // 数字
            $shopId = $request->route()->parameter('shop');
            if (!is_null($shopId)) {
                $shop = Shop::findOrFail($shopId);
                if ($shop->owner_id !== Auth::id()) {
                    abort(404);
                }
            }
            return $next($request);
        });
    }

    public function index()
    {
        $shops = Shop::where('owner_id', Auth::id())->get();

        return view('owner.shops.index', compact('shops'));
    }

    public function edit(int $id)
    {
        dd(Shop::findOrFail($id));
    }

    public function update(Request $request, int $id)
    {

    }
}
