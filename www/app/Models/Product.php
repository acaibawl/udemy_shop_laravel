<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
 * @property-read \App\Models\Shop $shop
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Stock> $stocks
 * @property-read int|null $stocks_count
 * @method static \Illuminate\Database\Eloquent\Builder|Product newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Product newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Product query()
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereImage1($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereImage2($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereImage3($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereImage4($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereInformation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereIsSelling($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereSecondaryCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereShopId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereSortOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'shop_id',
        'secondary_category_id',
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

    public function stocks(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Stock::class);
    }
}
