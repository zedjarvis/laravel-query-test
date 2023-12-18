<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Laravel\Scout\Searchable;


class Article extends Model
{
    use Searchable, HasFactory;

    protected $fillable = [
        'id',
        'category_id',
        'author',
        'title',
        'description',
        'image_url',
        'article_url',
        'content',
        'published',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class);
    }

    public function toSearchableArray(): array
    {
        return [
            'id' => (int) $this->id,
            'title' => $this->title,
            'content' => $this->content,
        ];
    }

    protected function makeAllSearchableUsing(Builder $query)
    {
        return $query->with(['category']);
    }
}
