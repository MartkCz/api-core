<?php declare(strict_types = 1);

namespace Api\Core\Exception;

use LogicException;
use Symfony\Contracts\HttpClient\ResponseInterface;

final class UnrecoverableRequestException extends LogicException
{

	public function __construct(
		public readonly ResponseInterface $response,
	)
	{
		parent::__construct(sprintf('Invalid request, http code: %s', $this->response->getStatusCode()));
	}

}
