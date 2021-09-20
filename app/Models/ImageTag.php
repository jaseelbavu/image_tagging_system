<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ImageTag extends Model
{
    use HasFactory;

    protected $fillable = [
        'image_id', 'coords', 'label', 'description'
    ];

    public function image() {
        return $this->belongsTo(Image::class);
    }
}
