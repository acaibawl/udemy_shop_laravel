<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Shop;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;

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
        $shop = Shop::findOrFail($id);
        return view('owner.shops.edit', compact('shop'));
    }

    public function update(Request $request, int $id, ImageManager $imageManager)
    {
        $imageFile = $request->file('image');
        if (!is_null($imageFile) && $imageFile->isValid()) {
            // Storage::putFile('public/shops', $imageFile); // リサイズなし
            $fileName = uniqid(rand() . '_');
            $extension = $imageFile->extension();
            $fileNameToStore = $fileName . '.' . $extension;
            $resizedImage = $imageManager->read($imageFile->getRealPath())->resize(1920, 1080)->encode();

            Storage::put('public/shops/' . $fileNameToStore, $resizedImage);
        }

        return redirect()->route('owner.shops.index');
    }
}
