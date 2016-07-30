<?php if(!defined('BASEPATH')) exit('No direct script access allowed');
include_once('main_controller.php');
class About_us extends SP_Controller {
    public function __construct() {
        parent::__construct();
    }

    public function index() {
        $globals = $this->Fn_model->getGlobals();
        $data['page_title'] = $globals->{'store-name'}.' | About Us';
        $data['main_content'] = 'front/about/about_view';
        $this->load->view('front/includes/main_view', $data);
    }
}
