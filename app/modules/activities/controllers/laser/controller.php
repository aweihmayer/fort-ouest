<?php
use helper\loader\ImageLoader;

class laserController {
	public function indexAction() {
        $this->view->images = ImageLoader::load(CONTROLLER_PATH . 'data/images.xml', $this->request->locale)->banners;
    }

	public function photosAction() {
        $this->view->images = ImageLoader::load(CONTROLLER_PATH . 'data/images.xml', $this->request->locale)->gallery;
    }
	
	public function bookingAction() {

	}
}