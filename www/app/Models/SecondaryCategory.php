<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $primary_category_id
 * @property string $name
 * @property int $sort_order
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\PrimaryCategory|null $primary
 * @method static \Illuminate\Database\Eloquent\Builder|SecondaryCategory newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SecondaryCategory newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SecondaryCategory query()
 * @method static \Illuminate\Database\Eloquent\Builder|SecondaryCategory whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SecondaryCategory whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SecondaryCategory whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SecondaryCategory wherePrimaryCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SecondaryCategory whereSortOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SecondaryCategory whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class SecondaryCategory extends Model
{
    use HasFactory;

    public function primary(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(PrimaryCategory::class);
    }
}
