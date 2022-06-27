<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Category;
use App\Models\Tag;
use App\Models\Picture;
use App\Models\Seo;
use App\Models\Social;
use App\Models\PostMeta;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Scope;

class Post extends Model
{
    use HasFactory , SoftDeletes;
    protected $fillable = ['title' , 'slug' ,'short_description', 'description' ,'video_link','status'];


    public function categories()
    {
        return $this->belongsToMany(Category::class,'post_categories','post_id','category_id');
    }

    public function pictures(){
        return $this->morphMany(Picture::class, 'imageable');
    }

    public function seo(){
        return $this->morphOne(Seo::class, 'seoable');
    }

    public function tags(){
        return $this->belongsToMany(Tag::class, 'post_tags' , 'post_id' , 'tag_id');
    }

    public function postMetas(){
        return $this->hasMany(PostMeta::class,'post_id','id');
    }

    public function districts()
    {
        return $this->belongsToMany(District::class,'post_districts','post_id','district_id');
    }

    public function socials()
    {
        return $this->belongsToMany(Social::class,'post_socials','post_id','social_id');
    }

    /**
     * @param Builder $query
     * @return Builder
     */
    public function scopePublished($query)
    {
        return $query->where(function ($query) {
            $query->where('status', 1);
            $query->orWhere(function ($query) {
                $query->where('status', 2);
                $query->where('created_at', '<=', Carbon::now()->format('Y-m-d H:i:s'));
            });
        });
    }

    /**
     * @param Builder $query
     * @return Builder
     */
    public function scopeNewest(Builder $query)
    {
        return $query->orderBy('posts.created_at', 'desc');
    }

    /**
     * @param Builder $query
     * @return Builder
     */
    public function scopeOldest(Builder $query)
    {
        return $query->orderBy('posts.created_at', 'asc');
    }

    /**
     * @param Builder $query
     * @param string|array $meta
     * @param mixed $value
     * @param string $operator
     * @return Builder
     */
    public function scopeHasMeta(Builder $query, $meta, $value = null, string $operator = '=')
    {
        if (!is_array($meta)) {
            $meta = [$meta => $value];
        }

        foreach ($meta as $key => $value) {
            $query->whereHas(PostMeta::class, function (Builder $query) use ($key, $value, $operator) {
                if (!is_string($key)) {
                    return $query->where('meta_key', $operator, $value);
                }
                $query->where('meta_key', $operator, $key);

                return is_null($value) ? $query :
                    $query->where('meta_value', $operator, $value);
            });
        }

        return $query;
    }

    public function scopeCategories(Builder $query, $categories)
    {
        return $query->whereHas('categories', function (Builder $query) use ($categories) {
            $query->whereIn('categories.id', is_array($categories) ? $categories : [$categories]);
        });
    }

    public function scopeTags(Builder $query, $tags)
    {
        return $query->whereHas('tags', function (Builder $query) use ($tags) {
            $query->whereIn('tags.id', is_array($tags) ? $tags : [$tags]);
        });
    }

    /**
     * @param Builder $query
     * @param string $taxonomy
     * @param string|array $values
     * $taxonomy values can be either one of 'categories', 'districts' or 'tags'
     * @return Builder
     */
    public function scopeTaxonomies(Builder $query, $taxonomy, $values)
    {
        return $query->whereHas($taxonomy, function (Builder $query) use ($values) {
            $query->whereIn('slug', is_array($values) ? $values : [$values]);
        });
    }

}
