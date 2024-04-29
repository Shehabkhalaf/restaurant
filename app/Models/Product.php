<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Product extends Model
{
    use HasFactory;
    protected $fillable = [
      'category_id',
      'title',
      'initial_price',
      'final_price',
      'description',
      'image'
    ];
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
}
