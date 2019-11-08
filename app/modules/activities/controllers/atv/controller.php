<?php
use helper\loader\ImageLoader;
use helper\ProductList;
use helper\loader\ContactLoader;

class atvController {
	public function indexAction() {
        $this->view->images = ImageLoader::load(CONTROLLER_PATH . 'data/images.xml', $this->request->locale)->banners;
    }
			
	public function photosAction() {
        $this->view->images = ImageLoader::load(CONTROLLER_PATH . 'data/images.xml', $this->request->locale)->gallery;
    }
	
	public function bookingAction() {
        $this->view->contact = ContactLoader::load(APP_PATH . 'data/contacts.xml', $this->request->locale)->fortOuest;
    }
}