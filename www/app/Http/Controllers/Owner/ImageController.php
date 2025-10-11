<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Http\Requests\UploadImageRequest;
use App\Models\Image;
use App\Models\Owner;
use App\Models\Product;
use App\Models\Shop;
use App\Services\ImageService;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ImageController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:owners');
        $this->middleware(function (Request $request, $next) {
            $imageId = $request->route()->parameter('image');
            if (!is_null($imageId)) {
                $image = Image::findOrFail($imageId);
                if ($image->owner_id !== Auth::id()) {
                    abort(404);
                }
            }
            return $next($request);
        });
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $images = Image::where('owner_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('owner.images.index', compact('images'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('owner.images.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UploadImageRequest $request, ImageService $imageService)
    {
        $imageFiles = $request->file('files');
        if (!is_null($imageFiles)) {
            foreach ($imageFiles as $imageFile) {
                $fileNameToStore = $imageService->upload($imageFile['image'], 'products');
                Image::create([
                    'owner_id' => Auth::id(),
                    'filename' => $fileNameToStore,
                ]);
            }
        }

        return redirect()->route('owner.images.index')->with([
            'message' => '画像登録を実施しました。',
            'status' => 'info',
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $image = Image::findOrFail($id);
        return view('owner.images.edit', compact('image'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'title' => ['string', 'max:50'],
        ]);
        $image = Image::findOrFail($id);
        $image->update(['title' => $request->input('title')]);

        return redirect()->route('owner.images.index')->with([
            'message' => '画像情報を更新しました。',
            'status' => 'info',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            DB::transaction(function () use ($id) {
                $image = Image::findOrFail($id);

                $products = Product::where('image1', $image->id)
                    ->orWhere('image2', $image->id)
                    ->orWhere('image3', $image->id)
                    ->orWhere('image4', $image->id)
                    ->get();

                $products->each(function (Product $product) use ($image) {
                    if ($product->image1 === $image->id) {
                        $product->image1 = null;
                    }
                    if ($product->image2 === $image->id) {
                        $product->image2 = null;
                    }
                    if ($product->image3 === $image->id) {
                        $product->image3 = null;
                    }
                    if ($product->image4 === $image->id) {
                        $product->image4 = null;
                    }
                    $product->save();
                });

                $filePath = 'public/products/' . $image->filename;
                if (Storage::exists($filePath)) {
                    Storage::delete($filePath);
                }

                $image->delete();
            });
        } catch (\Throwable $e) {
            \Log::error($e);
            throw $e;
        }

        return redirect()
            ->route('owner.images.index')
            ->with([
                'message' => '画像を削除しました。',
                'status' => 'alert',
            ]);
    }
}
