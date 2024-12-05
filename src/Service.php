<?php declare(strict_types = 1);

namespace Api\Core;

use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\HttpClientInterface;

abstract class Service
{

	private ?string $language = null;

	protected readonly HttpClientInterface $httpClient;

	public function __construct(
		private string $baseUrl,
		?HttpClientInterface $httpClient,
	)
	{
		$this->httpClient = $httpClient ?? HttpClient::create();
	}

	public function withLanguage(?string $language = null): static
	{
		$clone = clone $this;
		$clone->language = $language;

		return $clone;
	}

	/**
	 * @param array<string, scalar|null> $params
	 * @param array<string, string> $headers
	 */
	protected function requestJson(RequestType $method, mixed $data, string $path, array $params = [], array $headers = []): ServiceRequest
	{
		return $this->createRequest($method, $this->buildUrl($path, $params), [
			'json' => $data,
		], $headers);
	}

	/**
	 * @param array<string, scalar|null> $params
	 * @param array<string, string> $headers
	 */
	protected function requestBody(RequestType $method, string $body, string $path, array $params = [], array $headers = []): ServiceRequest
	{
		return $this->createRequest($method, $this->buildUrl($path, $params), [
			'body' => $body,
		], $headers);
	}

	/**
	 * @param array<string, scalar|null> $params
	 * @param array<string, string> $headers
	 */
	protected function requestGet(string $path, array $params = [], array $headers = []): ServiceRequest
	{
		return $this->createRequest(RequestType::Get, $this->buildUrl($path, $params), [], $headers);
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

	/**
	 * @param array<string, mixed> $options
	 * @param array<string, string> $headers
	 */
	private function createRequest(
		RequestType $method,
		string $url,
		array $options = [],
		array $headers = [],
	): ServiceRequest
	{
		if ($this->language) {
			$headers['x-language'] = $this->language;
		}

		return new ServiceRequest($this->httpClient, $method, $url, $options, $headers);
	}

}
