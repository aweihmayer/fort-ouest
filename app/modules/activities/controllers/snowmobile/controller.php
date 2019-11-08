<?php
use helper\loader\ImageLoader;
use helper\ProductList;
use helper\loader\ContactLoader;

class snowmobileController {
	public function indexAction(){
        $this->view->images = ImageLoader::load(CONTROLLER_PATH . 'data/images.xml', $this->request->locale)->banners;
    }
		
	public function pricesAction(){
        $this->view->products = new ProductList(CONTROLLER_PATH . 'data/products.json');
    }
	
	public function photosAction(){
        $this->view->images = ImageLoader::load(CONTROLLER_PATH . 'data/images.xml', $this->request->locale)->gallery;
    }
	
	public function bookingAction(){
        $this->view->contact = ContactLoader::load(APP_PATH . 'data/contacts.xml', $this->request->locale)->fortOuest;
    }
}
?>