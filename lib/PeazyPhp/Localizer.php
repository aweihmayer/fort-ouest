<?php
namespace PeazyPhp;

class Localizer{
	protected static $basePath = '';
	
	protected $locale;
	protected $values = [];
	
	protected static $unsafeHtmlChars;
	protected static $safeHtmlChars;
	
	public function __construct(string $locale) {
		$this->setLocale($locale);

        if(!isset(self::$safeHtmlChars)) {
            self::initHtmlSafeCharacters();
        }
	}
	
// Html safe
	
	private static function initHtmlSafeCharacters(): void {
        $htmlChars = array_diff_key(
            get_html_translation_table(HTML_ENTITIES),
            array_flip(['>', '<', '"']));

        self::$unsafeHtmlChars = array_keys($htmlChars);
        self::$safeHtmlChars = array_values($htmlChars);
	}
	
	protected static function toHtmlSafe($value): string {
		if(is_array($value)) {
			foreach($value as $i => $v) {
				$value[$i] = self::toHtmlSafe($v);
			}
		} else {
			$value = str_replace(self::$unsafeHtmlChars, self::$safeHtmlChars, $value);
		}
		
		return $value;
	}

// Load values

	public function load($file): void {
		if(is_array($file)) {
			foreach($file as $f) {
				$this->load($f);
			}
		} else {
            $locale = $this->getLocale();
			$file = self::getBasePath() . $file;

			if(file_exists($file)) {
				$this->values[$locale] = array_merge(
					$this->values[$locale],
					json_decode(file_get_contents($file), true)
				);
			}
		}
	}
	
// Values
	
	public function get(string $key, array $replace = []) {
		if(isset($this->values[$this->getLocale()][$key])) {
			$value = $this->values[$this->getLocale()][$key];

            if(!empty($replace)){
                $value = str_replace(
                    array_keys($replace),
                    array_values($replace),
                    $value);
            }
		} else {
			$value = $key;
		}
		
		return self::toHtmlSafe($value);
	}
	
	public function getLocaleValues(): array {
		return $this->values[$this->getLocale()];
	}
	
	public function getAllValues(): array {
		return $this->values;
	}
	
// Locale
	
	public function setLocale(string $locale): void { 
		$this->locale = $locale;
		
		if(!isset($this->values[$locale])) {
			$this->values[$locale] = [];
		}
	}
	
	public function getLocale(): string {
		return $this->locale;
	}
	
// Base path
	
	public static function setBasePath(string $basePath): void {
		self::$basePath = $basePath;
	}
	
	public static function getBasePath(): string {
		return self::$basePath;
	}
}