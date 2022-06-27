<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;


class MenuItems extends Model
{
    use HasFactory;
    protected $fillable = [
        'menu_id', 'menuable_id' , 'menuable_type', 'title', 'parent', 'link', 'order', 'parameters',
        'remarks', 'is_active','created_by','modified_by'
    ];

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('order', function (Builder $builder) {
            $builder->orderBy('order', 'asc');
        });
    }

    public function menuable()
    {
        return $this->morphTo(__FUNCTION__, 'menuable_type', 'menuable_id');
    }

    public function children(){
        return $this->hasMany(self::class, 'parent','id');
    }

}
