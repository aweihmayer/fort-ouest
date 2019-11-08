<?php
namespace PeazyPhp;

use PeazyPhp\exceptions\RouteNotFoundException;

use DOMDocument;
use DOMXpath;
use DOMElement;


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

	public function findRoute(string $path): array {
		$values = [];

        $path = strtok($path,'?');
        $nodes = $this->xpath->query("//path[text()='" . $path . "']");

        if($nodes->length) {
            $n = $nodes[0];
            $values = $this->attributesToArray($n);

            while($n->parentNode) {
                $values = array_merge($values, $this->attributesToArray($n));
                $n = $n->parentNode;
            }
        } else {
            throw new RouteNotFoundException($path);
        }

		return $values;
	}

    public function findPath(string $id, string $locale = null, array $params = []): string {
	    $path = '';
	    $query = "//route[@id='" . $id . "']/path";
	    if(isset($locale)) {
	        $query .= "[@locale='" . $locale . "']";
        }

        $nodes = $this->xpath->query($query);

	    if($nodes->length) {
            $path = $nodes[0]->nodeValue;

            if(!empty($params)) {
                $path .= '?' . http_build_query($params);
            }
        } else {
            throw new RouteNotFoundException($id);
        }

        return $path;
    }

	private function attributesToArray(DOMElement $node): array {
        $values = [];
        foreach($node->attributes as $a) {
            $values[$a->nodeName] = $a->nodeValue;
        }

        return $values;
    }
}