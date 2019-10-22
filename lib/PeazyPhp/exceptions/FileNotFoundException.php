<?php
namespace Peazy\exceptions;

class FileNotFoundException extends \Exception {
	public function __construct($resourceName, \Exception $previous = null) {
        parent::__construct('File not found: ' . $resourceName, 404, $previous);
    }
}