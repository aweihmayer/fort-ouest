<?php
use helper\loader\ImageLoader;

class receptionsController {
	public function indexAction(){
        $this->view->images = ImageLoader::load(CONTROLLER_PATH . 'data/images.xml', $this->request->locale)->receptions;
    }
}