<?php
namespace PeazyPhp;

class Response {
    private $code = 200;
    private $response = '';
    private $redirection = '';

    public function setCode(int $code): void {
        $this->code = $code;
    }

    public function getCode(): int {
        return $this->code;
    }

    public function setRedirection(string $redirection): void {
        $this->redirection = $redirection;
    }

    public function getRedirection(): string {
        return $this->redirection;
    }

    public function setResponse(string $response): void {
        $this->response = $response;
    }

    public function getResponse(): string {
        return $this->response;
    }

    public function send() {
        $code = $this->getCode();

        http_response_code($this->getCode());

        if($code == 301) {
            header('Location: ' . $this->getRedirection());
            exit;
        }

        echo $this->getResponse();

        exit;
    }
}