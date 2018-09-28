<?php

namespace App\Apis;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Psr7\Request;

/**
* A class to simplfy Guzzle HTTP request
*/
abstract class BaseApi
{
	/**
	 * base url for guzzle client request
	 * @var string
	 */	
	public $base_uri;

	/**
	 * request body
	 * @var array
	 */
	protected $body;

	/**
	 * Guzzle http client
	 * @var GuzzleHttp\Client
	 */
	protected $client;

	/**
	 * request form parameters that will be
	 * attached to body
	 * @var array
	 */
	public $form_params = [];

	/**
	 * Request headers
	 * @var array
	 */
	public $headers = [];

	/**
	 * json request that will be attach to $body
	 * @var array
	 */
	public $json = [];

	/**
	 * multipart form data request that
	 * will be attached to body
	 * @var array
	 */
	public $multipart = [];

	/**
	 * request url query
	 * @var array
	 */
	public $query = [];

	/**
	 * request response
	 * @var array
	 */
	public $response;

	/**
	 * Response keys
	 *
	 * @var Array
	 **/
	public $keys = [
		'data' => 'data',
		'meta' => 'meta',
		'links' => 'links'
	];

	/**
	 * response data
	 * @var collection
	 */
	public $data;

	/**
	 * response metadata
	 * @var collection
	 */
	public $meta;

	/**
	 * Pagination response links
	 * @var [type]
	 */
	public $links;
	
	/**
	 * set base url and inisiate Guzzle client
	 */
	function __construct()
	{
		$this->client = new Client([
			'base_uri' => $this->base_uri
		]);
	}

	/**
	 * set body property request, get response based on method, uri parameter
	 * and body
	 * @param  string $method GET|POST|PUT|DELETE
	 * @param  string $uri
	 * @return array
	 */
	public function getResponse($method, $uri)
	{
		$this->setBody();

		// Make a request. Throw the RequestFailed exception if failed
		$request = $this->client->request($method, $uri, $this->body);

		// Get response body if success
		$body = $request->getBody();
		$data = collect(json_decode($body, true));

		// Set data, meta, and links property
		$response = [
			'data' => $this->setResponseData($data),
			'meta' => $this->setResponseMeta($data),
			'links' => $this->setResponselinks($data),
		];

		return $response;
	}

	/**
	 * set body property based on query, form_params,
	 * json, multipart, and headers property
	 */
	public function setBody()
	{
		$body = [];

		if (!empty($this->query)) {
			$body['query'] = $this->query;
		}

		if (!empty($this->form_params)) {
			$body['form_params'] = $this->form_params;
		}

		if (!empty($this->json)) {
			$body['json'] = $this->json;
		}

		if (!empty($this->multipart)) {
			$body['multipart'] = $this->multipart;
		}

		$body['headers'] = $this->setHeaders();

		$this->body = $body;
		return $this;
	}

	/**
	 * Set request header. Add api token if 
	 * user is autenthicated
	 */
	public function setHeaders()
	{
		return [
			'Accept' => 'application/json',
		];
	}

	/**
	 * set response data property
	 * if API body doesn't have data, set response as data instead
	 */
	protected function setResponseData($response)
	{
		$data = $response[$this->keys['data']];

		return $data;
	}

	/**
	 * set response metadata
	 */
	protected function setResponseMeta($response)
	{
		$meta = optional($response)[$this->keys['meta']];

		if (!empty($meta)) {
			return $meta;
		} else {
			return null;
		}
	}

	/**
	 * set response links
	 */
	protected function setResponselinks($response)
	{
		$links = optional($response)[$this->keys['links']];

		if (!empty($links)) {
			return $links;
		} else {
			return null;
		}
	}

	public function page($page)
	{
		return url()->current().'?'.http_build_query(request()->merge(['page' => $page])->all());
	}

	public function nextPage()
	{
		return $this->page($this->meta['current_page']+1);
	}

	public function previousPage()
	{
		return $this->page($this->meta['current_page']-1);
	}

	/**
	 * Dynamically access the data's attributes.
	 *
	 * @param  string  $key
	 * @return mixed
	 */
	// public function __get($key)
	// {
	// 	if (is_array($this->data[$key])) {
	// 		return collect($this->data[$key]);
	// 	}

	//     return $this->data[$key];
	// }
}
