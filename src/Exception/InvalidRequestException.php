<?php declare(strict_types = 1);

namespace Api\Core\Exception;

use Api\Core\ErrorMapping;
use Exception;
use Symfony\Contracts\HttpClient\ResponseInterface;

final class InvalidRequestException extends Exception
{

	public function __construct(
		public readonly ResponseInterface $response
	)
	{
		parent::__construct(sprintf('Invalid request, http code: %s', $this->response->getStatusCode()));
	}

	/**
	 * @return mixed[]
	 */
	public function toPayload(ErrorMapping $mapping): array
	{
		return $mapping->render($this->response->toArray());
	}

}
