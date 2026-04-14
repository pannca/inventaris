<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    protected $fillable = [
        'category_id',
        'name',
        'total',
        'repair',
        'available',
        'lending_total',
    ];

    public function category() {
        return $this->belongsTo(Category::class);
    }

    public function lendings() {
        return $this->hasMany(Lending::class);
    }
}
