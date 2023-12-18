<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Laravel\Scout\Searchable;

class Category extends Model
{
    use HasFactory, Searchable;

    protected $table = 'categories';

    protected $fillable = [
        'id',
        'name',
        'slug',
        'icon'
    ];

    public function articles(): HasMany
    {
        return $this->hasMany(Article::class);
    }
}
