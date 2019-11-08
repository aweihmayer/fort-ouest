<?php
namespace PeazyPhp;

class Request {
	public function __construct(array $params = []) {
        foreach ($params as $k => $v) {
            $this->$k = $v;
        }
    }

	public function getQuery(): array {
	    return $_GET;
    }

	public function getMethod(): string {
	    return $_SERVER['REQUEST_METHOD'];
    }

	public function getUri(): string {
	    return $_SERVER['REQUEST_URI'];
    }
}