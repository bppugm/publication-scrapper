<?php

namespace App;

use Jenssegers\Mongodb\Eloquent\Model;

class Author extends Model
{
    protected $collection = 'authors';
    protected $connection = 'mongodb';
    protected $guarded = ['id'];
}
