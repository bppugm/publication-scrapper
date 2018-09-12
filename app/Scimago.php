<?php

namespace App;

use Jenssegers\Mongodb\Eloquent\Model;

class Scimago extends Model
{
    protected $guarded = ['id'];
    protected $collection = 'scimago';
    protected $connection = 'mongodb';
}
