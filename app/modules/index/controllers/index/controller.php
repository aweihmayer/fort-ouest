<?php
use helper\loader\ContactLoader;
use helper\loader\ImageLoader;

class indexController {
    public function init() {
        $this->view->contacts = ContactLoader::load(APP_PATH . 'data/contacts.xml', $this->request->locale);
        $this->view->images = ImageLoader::load(APP_PATH . 'data/images.xml', $this->request->locale);
    }

	public function indexAction(){
        $this->view->contact = $this->view->contacts->fortOuest;
    }

	public function partnersAction(){ }

	public function contactAction(){
        $this->view->contact = $this->view->contacts->fortOuest;
    }
}