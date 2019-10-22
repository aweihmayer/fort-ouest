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
    }
		
	public function photosAction(){
        $this->view->images = ImageLoader::load(CONTROLLER_PATH . 'data/images.xml', $this->request->locale)->gallery;
    }
	
	public function bookingAction(){
        $this->view->contacts = ImageLoader::load(CONTROLLER_PATH . 'data/images.xml', $this->request->locale);
    }
}
?>