<?php

namespace App;

use Jenssegers\Mongodb\Eloquent\Model;

class AuthorDocument extends Model
{
    protected $collection = 'author_document';
    protected $connection = 'mongodb';
    protected $guarded = ['id'];
}
