<?php
namespace PeazyPhp\exceptions;

class FileNotFoundException extends NotFoundException {
	public function __construct($f) {
        parent::__construct('File not found: ' . $f);
    }
}