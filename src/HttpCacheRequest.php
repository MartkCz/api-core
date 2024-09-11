<?php declare(strict_types = 1);

namespace Api\Core;

use DateTimeImmutable;
use DateTimeInterface;
use DateTimeZone;

/**
 * Dates are in UTC
 */
class HttpCacheRequest
{

	public function __construct(
		public readonly ?string $ifNoneMatch,
		public readonly ?DateTimeInterface $ifModifiedSince,
		public readonly ?DateTimeInterface $sourceLastUpdate,
	)
	{
	}

	/**
	 * @param array<string, string> $headers
	 * @return array<string, string>
	 */
	public function createHttpHeaders(array $headers = []): array
	{
		if ($this->sourceLastUpdate) {
			$headers['X-Last-Update'] = $this->toUtc($this->sourceLastUpdate)->format('D, d M Y H:i:s \G\M\T');
		}

		if ($this->ifModifiedSince) {
			$headers['If-Modified-Since'] = $this->toUtc($this->ifModifiedSince)->format('D, d M Y H:i:s \G\M\T');
		}

		if ($this->ifNoneMatch) {
			$headers['If-None-Match'] = $this->ifNoneMatch;
		}

		return $headers;
	}

	private function toUtc(DateTimeInterface $dateTime): DateTimeInterface
	{
		$timeZone = $dateTime->getTimezone();

		if ($timeZone->getName() === 'UTC') {
			return $dateTime;
		}

		return DateTimeImmutable::createFromInterface($dateTime)
			->setTimezone(new DateTimeZone('UTC'));
	}

}
