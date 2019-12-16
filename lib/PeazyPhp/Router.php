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
        $path = strtok($path,'?');
	    $routes = [];

        // Search for exact matching path without path parameters
        $nodes = $this->xpath->query("//path[text()='" . $path . "']");
        if($nodes->length) {
            $routes[] = $this->extractRouteParams($nodes[0]);
        } else {
            $nodes = $this->xpath->query("//path");

            foreach($nodes as $nd) {
                // Check paths that have a parameter (name prefixed with :)
                if(strpos($nd->nodeValue, ':') !== false) {
                    $path = explode('/', $path);
                    $path2 = explode('/', $nd->nodeValue);

                    // Number of path elements must be the same
                    if(count($path) == count($path2)) {
                        $params = [];

                        foreach($path as $i => $v) {
                            $v2 = $path2[$i];

                            // If this path element is a parameter, store it. Else if the path elements are not the same, skip this path
                            if(strpos($v2, ':') !== false) {
                                $name = ltrim($v2, ':');
                                $params[$name] = $v;
                            } else {
                                break 2;
                            }
                        }

                        $routes[] = array_merge(
                            $params,
                            $this->extractRouteParams($nd));
                    }
                }
            }
        }

        if(empty($routes)) {
            throw new RouteNotFoundException($path);
        }

		return $routes;
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

    private function extractRouteParams(DOMElement $node): array {
        $params = $this->attributesToArray($node);

        while($node->parentNode) {
            $params = array_merge($params, $this->attributesToArray($node));
            $node = $node->parentNode;
        }

        return $params;
    }
}