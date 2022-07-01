<?php

namespace App;

use Jenssegers\Mongodb\Eloquent\Model;

class SimasterAuthor extends Model
{
    protected $collection = 'simaster_authors';
    protected $connection = 'mongodb';
    protected $guarded = ['id'];

    public static function searchText($value)
    {
        $query = self::whereRaw([
            '$text' => [
                '$search' => $value
            ]
        ]);
        $query->getQuery()->projections = ['score' => ['$meta' => 'textScore']];
        $search = $query->orderBy('score', ['$meta' => 'textScore'])->get();
        if ($search->count()) {
            $search = $search->first();
            if ($search->score >= 1) {
               return $search;
            }
        }

        return null;
    }
}
