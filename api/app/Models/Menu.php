<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    use HasFactory;
    protected $fillable = [
        'name','type','remarks','created_by','modified_by'
    ];

    public function items(){
        return $this->hasMany(MenuItems::class, 'menu_id','id');
    }

}
