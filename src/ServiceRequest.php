<?php declare(strict_types = 1);

namespace Api\Core;

use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

final class ServiceRequest
{

	/**
	 * @param array<string, mixed> $options
	 * @param array<string, string> $headers
	 */
	public function __construct(
		private readonly HttpClientInterface $httpClient,
		private readonly RequestType $method,
		private readonly string $url,
		private readonly array $options,
		private array $headers,
	)
	{
	}

	public function setCache(HttpCacheRequest $cache): self
	{
		$this->headers = $cache->createHttpHeaders($this->headers);

		return $this;
	}

	public function request(): ResponseInterface
	{
		$options = $this->options;
		$options['headers'] = $this->headers;

		return $this->httpClient->request($this->method->value, $this->url, $options);
	}

}
