<?php
use helper\ProductList;

class restaurantController {
	public function indexAction(){
        $this->view->products = new ProductList(CONTROLLER_PATH . 'data/products.json');
    }
}