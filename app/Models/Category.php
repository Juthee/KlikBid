<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'parent_id',
        'level',
        'icon',
        'description',
        'is_active',
        'sort_order'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'level' => 'integer',
        'sort_order' => 'integer'
    ];

    /**
     * Get the parent category
     */
    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    /**
     * Get the child categories (subcategories)
     */
    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id')->orderBy('sort_order');
    }

    /**
     * Get all subcategories recursively
     */
    public function subcategories()
    {
        return $this->children()->with('subcategories');
    }

    /**
     * Check if this category has subcategories
     */
    public function hasChildren()
    {
        return $this->children()->count() > 0;
    }

    /**
     * Get only main categories (level 0)
     */
    public static function mainCategories()
    {
        return static::where('level', 0)
                    ->where('is_active', true)
                    ->with('children')
                    ->orderBy('sort_order')
                    ->get();
    }

    /**
     * Get only subcategories (level 1)
     */
    public static function subcategoriesOnly()
    {
        return static::where('level', 1)
                    ->where('is_active', true)
                    ->with('parent')
                    ->orderBy('sort_order')
                    ->get();
    }

    /**
     * Get auctions in this category
     */
    public function auctions()
    {
        return $this->hasMany(Auction::class);
    }

    /**
     * Get full category path (Parent > Child)
     */
    public function getFullNameAttribute()
    {
        if ($this->parent) {
            return $this->parent->name . ' > ' . $this->name;
        }
        return $this->name;
    }

    /**
     * Scope for active categories
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
