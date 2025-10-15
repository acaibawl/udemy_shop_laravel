<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Stock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ItemController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:users');
        $this->middleware(function (Request $request, $next) {
            $itemId = $request->route()->parameter('item');
            if (!is_null($itemId)) {
                $isExists = Product::availableItems()->where('products.id', $itemId)->exists();
                if (!$isExists) {
                    abort(404);
                }
            }
            return $next($request);
        });
    }

    public function index(Request $request)
    {
        $products = Product::availableItems()
            ->sortOrder($request->sort)
            ->paginate($request->perPage ?? 20);

        return view('user.index', compact('products'));
    }

    public function show($id)
    {
        $product = Product::findOrFail($id);
        $quantity = Stock::where('product_id', $id)->sum('quantity');
        if ($quantity > 9) {
            $quantity = 9;
        }

        return view('user.show', compact('product', 'quantity'));
    }
}
