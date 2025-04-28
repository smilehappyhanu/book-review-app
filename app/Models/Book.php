<?php

namespace App\Models;

use App\Models\Review;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Book extends Model
{
    use HasFactory;
    protected $table = 'books';
    protected $guarded = [];
    use SoftDeletes;

    public function reviews() {
        return $this->hasMany(Review::class);
    }
}
