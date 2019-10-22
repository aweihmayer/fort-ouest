<?php
namespace helper;

use helper\StringHelper;

class ProductList {
	protected $items = [];

	public function __construct($source = null) {
	    if(is_string($source)) {
            $this->items = json_decode(file_get_contents($source), true);
        } elseif(is_array($source)) {
            $this->items = $source;
        }
	}

	public function addItem(string $name, array $item) {
	    $this->items[$name] = $item;
    }

	public function getItems() {
	    return $this->items;
	}
	
	public function getItem(string $name) {
		return $this->items[$name];
	}

	public function getItemPrice(string $name, bool $noDecimals = false) {
	    return StringHelper::toCurrency($this->getItem($name)['price'], $noDecimals);
    }
	
	public function getItemsByType(string $type) {
		$itemsFound = new ProductList();

		foreach($this->getItems() as $n => $item) {
			if($item['type'] == $type) {
				$itemsFound->addItem($n, $item);
			}
		}

		return $itemsFound;
	}

	public function toTable($noDecimals = false) {
		$table = [];

		foreach($this->getItems() as $n => $item) {
			$table[] = [
				t($n),
                StringHelper::toCurrency($item['price'], $noDecimals)
			];
		}
		
		return $table;
	}
}