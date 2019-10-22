<?php
namespace PeazyPhp\exceptions;

class RouteNotFoundException extends \Exception {
	public function __construct($uri, \Exception $previous = null) {
        parent::__construct('The router was not able to find the request for: "' . $uri . '"', 404, $previous);
    }
}