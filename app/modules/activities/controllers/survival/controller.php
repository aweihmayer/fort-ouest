<?php
use helper\loader\ImageLoader;
use helper\ProductList;
use helper\loader\ContactLoader;

class survivalController {
	public function indexAction(){
        $this->view->images = ImageLoader::load(CONTROLLER_PATH . 'data/images.xml', $this->request->locale)->banners;
    }
	
	public function packagesAction(){
        $this->view->images = ImageLoader::load(CONTROLLER_PATH . 'data/images.xml', $this->request->locale)->banners;
        $this->view->products = new ProductList(CONTROLLER_PATH . 'data/products.json');
    }
		
	public function photosAction(){
        $this->view->images = ImageLoader::load(CONTROLLER_PATH . 'data/images.xml', $this->request->locale)->gallery;
    }
	
	public function bookingAction(){
        $this->view->contacts = ContactLoader::load(APP_PATH . 'data/contacts.xml', $this->request->locale);
    }
}