<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Page extends Model
{
    protected $fillable = ['parent_id', 'slug', 'title', 'content','is_published'];

    public function parent(): BelongsTo {
        return $this->belongsTo(Page::class, 'parent_id');
    }

    public function children(): HasMany {
        return $this->hasMany(Page::class, 'parent_id')->with('children');
    }
}
