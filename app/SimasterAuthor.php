<?php

namespace App;

use Jenssegers\Mongodb\Eloquent\Model;

class SimasterAuthor extends Model
{
    protected $collection = 'simaster_authors';
    protected $connection = 'mongodb';
    protected $guarded = ['id'];
}
