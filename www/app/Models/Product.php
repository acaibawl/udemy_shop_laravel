<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

/**
 * @property int $id
 * @property string $name
 * @property string $information
 * @property int $price
 * @property int $is_selling
 * @property int|null $sort_order
 * @property int $shop_id
 * @property int $secondary_category_id
 * @property int|null $image1
 * @property int|null $image2
 * @property int|null $image3
 * @property int|null $image4
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\SecondaryCategory $category
 * @property-read \App\Models\Image|null $imageFirst
 * @property-read \App\Models\Image|null $imageFourth
 * @property-read \App\Models\Image|null $imageSecond
 * @property-read \App\Models\Image|null $imageThird
 * @property-read \App\Models\Shop $shop
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Stock> $stocks
 * @property-read int|null $stocks_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\User> $users
 * @property-read int|null $users_count
 * @method static Builder|Product availableItems()
 * @method static \Database\Factories\ProductFactory factory($count = null, $state = [])
 * @method static Builder|Product newModelQuery()
 * @method static Builder|Product newQuery()
 * @method static Builder|Product query()
 * @method static Builder|Product searchKeyword(?string $keyword)
 * @method static Builder|Product selectCategory($categoryId)
 * @method static Builder|Product sortOrder(?string $sortOrder)
 * @method static Builder|Product whereCreatedAt($value)
 * @method static Builder|Product whereId($value)
 * @method static Builder|Product whereImage1($value)
 * @method static Builder|Product whereImage2($value)
 * @method static Builder|Product whereImage3($value)
 * @method static Builder|Product whereImage4($value)
 * @method static Builder|Product whereInformation($value)
 * @method static Builder|Product whereIsSelling($value)
 * @method static Builder|Product whereName($value)
 * @method static Builder|Product wherePrice($value)
 * @method static Builder|Product whereSecondaryCategoryId($value)
 * @method static Builder|Product whereShopId($value)
 * @method static Builder|Product whereSortOrder($value)
 * @method static Builder|Product whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'shop_id',
        'secondary_category_id',
        'sort_order',
        'name',
        'information',
        'price',
        'is_selling',
        'image1',
        'image2',
        'image3',
        'image4',
    ];

    public function shop(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Shop::class);
    }

    public function category(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(SecondaryCategory::class, 'secondary_category_id');
    }

    public function imageFirst(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Image::class, 'image1', 'id');
    }

    public function imageSecond(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Image::class, 'image2', 'id');
    }

    public function imageThird(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Image::class, 'image3', 'id');
    }

    public function imageFourth(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Image::class, 'image4', 'id');
    }

    public function stocks(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Stock::class);
    }

    public function users(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(User::class, 'carts')
            ->withPivot(['id', 'quantity']);
    }

    public function scopeAvailableItems(Builder $query): Builder
    {
        $stock = DB::table('t_stocks')
            ->select('product_id', DB::raw('sum(quantity) as quantity'))
            ->groupBy('product_id')
            ->having('quantity', '>=', 1);

        return $query->joinSub($stock, 'stock', function ($join) {
            $join->on('products.id', '=', 'stock.product_id');
        })
            ->join('shops', 'products.shop_id', '=', 'shops.id')
            ->join('secondary_categories', 'products.secondary_category_id', '=', 'secondary_categories.id')
            ->join('images as image1', 'products.image1', '=', 'image1.id')
            ->where('shops.is_selling', true)
            ->where('products.is_selling', true)
            ->select(
                'products.id as id',
                'products.name as name',
                'products.information',
                'products.price',
                'products.sort_order as sort_order',
                'products.shop_id',
                'products.secondary_category_id',
                'image1.filename as filename',
                'secondary_categories.name as category',
                'shops.name as shop_name',
            );
    }

    public function scopeSortOrder(Builder $query, ?string $sortOrder)
    {
        if ($sortOrder === null || $sortOrder === \Constant::SORT_ORDER['recommend']) {
            return $query->orderBy('sort_order', 'asc');
        }
        if ($sortOrder === \Constant::SORT_ORDER['higherPrice']) {
            return $query->orderBy('price', 'desc');
        }
        if ($sortOrder === \Constant::SORT_ORDER['lowerPrice']) {
            return $query->orderBy('price', 'asc');
        }
        if ($sortOrder === \Constant::SORT_ORDER['later']) {
            return $query->orderBy('products.created_at', 'desc');
        }
        if ($sortOrder === \Constant::SORT_ORDER['older']) {
            return $query->orderBy('products.created_at', 'asc');
        }
        return $query;
    }

    public function scopeSelectCategory(Builder $query, $categoryId): Builder
    {
        if ($categoryId !== '0') {
            return $query->where('secondary_category_id', $categoryId);
        } else {
            return $query;
        }
    }

    public function scopeSearchKeyword(Builder $query, ?string $keyword): Builder
    {
        if (is_null($keyword) || $keyword === '') {
            return $query;
        }

        // 全角スペースを半角に
        $spaceConvert = mb_convert_kana($keyword, 's');

        // 半角スペースで区切る
        $keywords = preg_split('/[\s]+/', $spaceConvert, -1, PREG_SPLIT_NO_EMPTY);

        // 単語をループで回す
        foreach ($keywords as $word) {
            $query->where('products.name', 'like', '%' . $word . '%');
        }
        return $query;
    }
}
