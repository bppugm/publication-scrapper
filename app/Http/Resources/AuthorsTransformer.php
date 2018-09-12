<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AuthorsTransformer extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'auth_id' => $this['authid'],
            "authname" => $this['authname'],
            "surname" => $this['surname'],
            "given-name" => $this['given-name'],
            "initials" => $this['initials'],
            'affiliation_id' => $this->getAffiliations(),
        ];
    }

    public function getAffiliations()
    {
        if (!empty($this['afid'])) {
            return collect($this['afid'])->pluck('$')->all();
        } else {
            return null;
        }
    }
}
