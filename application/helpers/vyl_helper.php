<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/************************************
Title: General Helper for Mindblow Creatives.
Author: Orvyl "Pogi" Tumaneng
****************************/

	/*****SANITIZE********/
	if(!function_exists('sanitize_input'))
	{
	 	
		function sanitize_input($txt)
		{
			return mysql_real_escape_string(strip_tags($txt));
	  	}
	}


	/**********RANDOM CHAR****************/
	if(!function_exists('random_char'))
	{
		function random_char($num_char)
		{
			$length = $num_char;
			$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
			$code = '';
			for ($i = 0; $i < $length; $i++)
			{
				$code .= $characters[rand(0, strlen($characters) - 1)];
			}

			return $code;
		}
	}

	/********Get Page content(for text CMS)*********/
	if(!function_exists('get_content'))
	{
	 	
		function get_content($id)
		{
			$CI = get_instance();
			$n =  $CI->My_model->select_where('pages',array('page_id'=>$id));
			return $n->page_content;
		}
	}

	 /***********Require Login***********/
	if(!function_exists('req_login'))
	{
		function req_login()
		{
			$CI = get_instance();
			if(!$CI->session->userdata('logged'))
				redirect(base_url());
		}
	}

