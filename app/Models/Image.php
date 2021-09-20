<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'path', 'user_id', 'category_id', 'visibility'
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }
}
