<?php
namespace PeazyPhp;

class Content{
    private static $basePath = '';
	protected $file;

	public function __construct(string $f = null, array $params = []) {
	    if (isset($f)) {
            $this->setFile($f);
        }

        $this->addProperties($params);
	}

	public function addProperties(array $params): void {
        foreach ($params as $k => $v) {
            $this->$k = $v;
        }
    }

	public function applyModel($obj): void {
	    $this->addProperties(get_object_vars($obj));
    }

// File

    public static function setBasePath(string $path): void {
        self::$basePath = $path;
    }

    public static function getBasePath(): string {
	    return self::$basePath;
    }

    public function setFile(string $f): void {
	    $this->file = self::getBasePath() . $f;
    }

	public function getFile(): string {
		return $this->file;
	}

// Render

    public function render(): string {
        $f = $this->getFile();
        ob_start();
        include $f;
        return ob_get_clean();
    }

    public function includeFile(string $f): string {
        ob_start();
        include self::getBasePath() . $f;
        return ob_get_clean();
    }

    public function includeContent(string $f, array $params = []): string {
	    $component = new Content($f, $params);
	    return $component->render();
    }
}