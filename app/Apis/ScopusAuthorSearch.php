<?php

namespace App\Apis;

/**
 * Scopus Author Search
 */
class ScopusAuthorSearch extends BaseApi
{
	public $base_uri = 'https://api.elsevier.com/content/search/author';
	public $keys = [
		'data' => 'search-results',
		'meta' => 'meta',
		'links' => 'links'
	];
	public $query = [
		'apiKey' => 'ee76c131d9bf443e5afffa9be30dd723',
		'query' => 'AF-ID(60069380)',
	];

	public function findAuthors($query)
	{
		$scopusQuery = $this->setScopusQuery($query);
		$this->addQuery($scopusQuery);

		$response = $this->getResponse('GET', $this->base_uri);
		return $response;
	}

	public function setScopusQuery($query)
	{
		$scopusQuery = "AND AUTHLASTNAME($query[last_name])";

		if (!empty($query['first_name'])) {
			$scopusQuery .= " AND AUTHFIRST($query[first_name])";
		}

		return $scopusQuery;
	}

	public function addQuery($value='')
	{
		$this->query['query'] .= $value;
	}
}