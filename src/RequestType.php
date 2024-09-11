<?php declare(strict_types = 1);

namespace Api\Core;

enum RequestType: string
{

	case Post = 'POST';
	case Get = 'GET';
	case Put = 'PUT';
	case Delete = 'DELETE';
	case Patch = 'PATCH';

}
