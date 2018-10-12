<?php

namespace App\Apis;

/**
 * Scopus Author Retrieval API
 */
class ScopusAuthorRetrieval extends BaseApi
{
	public $base_uri = 'https://api.elsevier.com/content/author/author_id/';
	public $keys = [
		'data' => 'author-retrieval-response',
		'meta' => 'meta',
		'links' => 'links'
	];
	public $query = [
		'apiKey' => 'ee76c131d9bf443e5afffa9be30dd723',
		'view' => 'ENHANCED',
		'field' => 'h-index,surname,given-name,affiliation-name,coredata,document-count,citations-count,cited-by-count',
	];

	public function getAuthor($authorId)
	{
		$response = $this->getResponse('GET', $this->base_uri.$authorId);

		return $response;
	}
}