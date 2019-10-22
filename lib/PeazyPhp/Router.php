<?php
namespace PeazyPhp;

use PeazyPhp\exceptions\RouteNotFoundException;

use DOMDocument;
use DOMXpath;


class Router {
	private  $routes;
	private $xpath;
	
	public function __construct(string $f) {
        $this->loadRoutes($f);
	}
	
	private function loadRoutes(string $f): void {
		$this->routes = new DOMDocument();
		$this->routes->load($f);
		$this->xpath = new DOMXpath($this->routes);
	}

	public function findRoute(string $url): array {
		$values = [];

        $url = strtok($url,'?');
        $nodes = $this->xpath->query("//path[text()='" . $url . "']");

        if ($nodes->length) {
            $n = $nodes[0];
            $values = $this->attributesToArray($n);

            while($n->parentNode) {
                $values = array_merge($values, $this->attributesToArray($n));
                $n = $n->parentNode;
            }
        } else {
            throw new RouteNotFoundException($url);
        }

		return $values;
	}

	private function attributesToArray($node): array {
        $values = [];
        foreach ($node->attributes as $a) {
            $values[$a->nodeName] = $a->nodeValue;
        }

        return $values;
    }

    public function findPath(string $id, string $locale) {
        $nodes = $this->xpath->query("//route[@id='" . $id . "']/path[@locale='" . $locale . "']");
        return $nodes[0]->nodeValue;
    }

// URL

	public function uri(string $id, $locale = null): string {
        $route = $this->getRoute($id);

		$uris = $route->getUris();
        $uri = isset($locale) ? $uris[$locale] : $uris[0];

		if(!empty($params)) {
			$uri .= '?' . http_build_query($params);
		}
		
		return $uri;
	}
}