<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Http\Requests\UploadImageRequest;
use App\Models\Shop;
use App\Services\ImageService;
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

    public function update(UploadImageRequest $request, int $id, ImageService $imageService)
    {
        $imageFile = $request->file('image');
        if (!is_null($imageFile) && $imageFile->isValid()) {
            $fileNameToStore = $imageService->upload($imageFile, 'shops');
        }

        return redirect()->route('owner.shops.index');
    }
}
