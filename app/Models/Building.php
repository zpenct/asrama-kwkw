<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\HtmlString;

class Building extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['name', 'description', 'type', 'image_url'];

    public function rooms()
    {
        return $this->hasMany(Room::class);
    }

    public function facilities()
    {
        return $this->belongsToMany(Facility::class);
    }

    public function floors()
    {
        return $this->hasMany(Floor::class);
    }

    public function getDescriptionHtml()
    {
        return new HtmlString($this->description);
    }
}
