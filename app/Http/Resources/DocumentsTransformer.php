<?php

namespace App\Http\Resources;

use App\Http\Resources\AuthorsTransformer;
use App\Scimago;
use Illuminate\Http\Resources\Json\JsonResource;

class DocumentsTransformer extends JsonResource
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
            'title' => $this['dc:title'],
            'published_at' => $this['prism:coverDate'],
            'doi' => optional($this)['prism:doi'],
            'identifier' => optional($this)['dc:identifier'],
            'publication_name' => $this['prism:publicationName'],
            'citation_count' => $this['citedby-count'],
            'subtype' => $this['subtype'],
            'subtype_description' => $this['subtypeDescription'],
            'source_id' => optional($this)['source-id'],
            'authors' => AuthorsTransformer::collection(collect($this['author']))->jsonSerialize(),
            'keywords' => optional($this)['authkeywords'],
            'scimago' => optional(Scimago::where('source_id', (int)(optional($this)['source-id']))->first())->toArray(),
        ];
    }
}
