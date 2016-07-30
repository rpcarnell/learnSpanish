<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/************************************
Title: General Helper for Mindblow Creatives.
Author: Kenneth Mervin Enriquez.
****************************/




/************dont enter if login***************/

if(!function_exists('dont_enter_if_login')){
 	
	function dont_enter_if_login(){
		$CI = get_instance();
		$client_true = $CI->session->userdata('client_login');
		if($client_true == 1 || $client_true == 2){
			redirect(base_url());				
		}
		return;
  }
}





