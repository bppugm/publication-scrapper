<?php

namespace App;

use Jenssegers\Mongodb\Eloquent\Model;

class Scimago extends Model
{
    protected $guarded = ['id'];
    protected $collection = 'ranks';
    protected $connection = 'mongodb';
}
