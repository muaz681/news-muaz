<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Category;
use App\Models\Seo;
use Illuminate\Database\Eloquent\Scope;
use Monolog\Handler\WhatFailureGroupHandler;

class Category extends Model
{
    use HasFactory , SoftDeletes;
    protected $fillable = ['title' , 'slug' , 'status', 'isMenu', 'parent_id'];

    public function seo(){
        return $this->morphOne(Seo::class, 'seoable');
    }

    public function scopeSearch($query, $catId)
    {
        return $query->findOrFail($catId);
    }

    public function scopeFilterByStatus($query)
    {
        return $query->where('status', 1);
    }

    public function posts()
    {
        return $this->belongsToMany(Post::class,'post_categories','category_id','post_id');
    }

    public function children(){
        return $this->hasMany(self::class, 'parent_id','id');
    }
    
    public function parent(){
        return $this->belongsTo(self::class, 'parent_id');
    }



     
}
