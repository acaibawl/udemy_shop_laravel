<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\Product;
use Illuminate\Support\Collection;

class CartService
{
    /**
     * @param Collection<Cart> $items
     * @return array
     */
    public function getItemsInCart(Collection $items)
    {
        $products = [];

        $items->each(function (Cart $item) use (&$products) {
            $owner = $item->product->shop->owner->only(['name', 'email']);
            $products[] = [
                ...$item->product->only(['id', 'name', 'price']),
                'ownerName' => $owner['name'],
                'email' => $owner['email'],
                'quantity' => $item->quantity
            ];
        });

        return $products;
    }
}
