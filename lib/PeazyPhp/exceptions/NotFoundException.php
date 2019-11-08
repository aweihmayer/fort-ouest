<?php
namespace PeazyPhp\exceptions;

use Exception;

class NotFoundException extends Exception {
	public function __construct(string $msg) {
        parent::__construct($msg, 404, null);
    }
}