<?php
namespace PeazyPhp;

class Request {
	public function __construct(array $params) {
        foreach ($params as $k => $v) {
            $this->$k = $v;
        }
    }

	public function getQuery() {
	    return $_GET;
    }

	public function getMethod() {
	    return $_SERVER['REQUEST_METHOD'];
    }

	public function getUri() {
	    return $_SERVER['REQUEST_URI'];
    }
}