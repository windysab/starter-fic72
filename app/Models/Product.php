<?php

namespace App\Models;

use App\Models\User;
use App\Models\Category;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'price',
        'image_url',
        'category_id',
        'user_id',

    ];


    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeCategoryId(Builder $query, string $categoryId): Builder
    {
        return $query->where('category_id', 'LIKE', '%' . $categoryId . '%');
    }
}
