<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model
{
    use SoftDeletes;
    protected $fillable = ['title', 'body', 'cover_image', 'pinned'];

    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function boot()
    {
        parent::boot();

        static::addGlobalScope('pinned', function ($query) {
            $query->orderBy('pinned', 'desc');
        });
    }

    public static function validationRules()
    {
        return [
            'title' => 'required|max:255',
            'body' => 'required|string',
            'cover_image' => 'sometimes|required|image',
            'pinned' => 'required|boolean',
        ];
    }
}