<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
include_once('main_controller.php');
class Home extends SP_Controller
{
    public function __construct()
    {
        parent::__construct();
         
    }

    public function index()
    {
        $globals = $this->Fn_model->getGlobals();
        $data['page_title'] = $globals->{'store-name'}.' | Home';
        $data['main_content'] = 'front/home_view';
        $this->load->model('Customers_model');
        $this->Customers_model->cronJobs();
        $this->load->view('front/includes/main_view',$data);
    }
     public function terms()
    {
        $globals = $this->Fn_model->getGlobals();
        $data['page_title'] = $globals->{'store-name'}.' | Terms &amp; Conditions';
        $data['main_content'] = 'front/terms_view';
        $this->load->view('front/includes/main_view',$data);
    }


    public function privacy_policy(){
        $globals = $this->Fn_model->getGlobals();
        $data['page_title'] = $globals->{'store-name'}.' | Terms &amp; Conditions';
        $data['main_content'] = 'front/privacy_view';
        $this->load->view('front/includes/main_view',$data);
    }
   
}









