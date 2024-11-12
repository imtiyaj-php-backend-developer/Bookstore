<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bookstore extends Model
{
    protected $fillable = [
        'title',
        'author',
        'published_date',
        'ISBN',
        'Pages',
        'Language',
        'publisher',
    ];
}
