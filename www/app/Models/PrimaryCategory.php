<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PrimaryCategory extends Model
{
    use HasFactory;

    public function secondary(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(SecondaryCategory::class);
    }
}
