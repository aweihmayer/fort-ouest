<?php
namespace PeazyPhp\exceptions;

class RouteNotFoundException extends NotFoundException {
	public function __construct(string $p) {
        parent::__construct('Router did not find resource for "' . $p . '"');
    }
}