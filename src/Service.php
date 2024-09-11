<?php declare(strict_types = 1);

namespace Api\Core;

use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\HttpClientInterface;

abstract class Service
{

	protected readonly HttpClientInterface $httpClient;

	public function __construct(
		private string $baseUrl,
		?HttpClientInterface $httpClient,
	)
	{
		$this->httpClient = $httpClient ?? HttpClient::create();
	}

	/**
	 * @param array<string, scalar|null> $params
	 * @param array<string, string> $headers
	 */
	protected function requestJson(RequestType $method, mixed $data, string $path, array $params = [], array $headers = []): ServiceRequest
	{
		return new ServiceRequest($this->httpClient, $method, $this->buildUrl($path, $params), [
			'json' => $data,
		], $headers);
	}

	/**
	 * @param array<string, scalar|null> $params
	 * @param array<string, string> $headers
	 */
	protected function requestGet(string $path, array $params = [], array $headers = []): ServiceRequest
	{
		return new ServiceRequest($this->httpClient, RequestType::Get, $this->buildUrl($path, $params), [], $headers);
	}

	/**
	 * @param array<string, scalar|null> $params
	 */
	protected function buildUrl(string $path, array $params = []): string
	{
		$url = $this->baseUrl . $path;

		if (count($params) > 0) {
			$url .= '?' . http_build_query($params);
		}

		return $url;
	}

}
