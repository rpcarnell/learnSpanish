<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/************
Title: Product Helper For Mindblow Cms 
Author: Kenneth Mwervin Enriquez

***************/

if(!function_exists('product_on_category')){
	function product_on_category($pcat_id){
		$CI = get_instance();
		$where = array('prod_cat_id'=>$pcat_id);
		$products = $CI->My_model->select_where_c('assign_product_category',$where);
		
		foreach ($products as $product):
			echo "<li><a href='".base_url('product/product_details')."/".$product->prod_id."'>".product_name($product->prod_id)."</a></li>";
		endforeach;
		
		return ;
  }
}





/****get category name of products by inserting it to the loop of product tinformation*****/
if(!function_exists('get_category_name')){
	function get_category_name($p_id){
		$CI = get_instance();
		$where = array('prod_id'=>$p_id);
		$prod_category = $CI->My_model->select_where_c('assign_product_category',$where);
		foreach($prod_category as $prod_categories):
			$prod_categori = $CI->My_model->select_where('product_category',array('prod_cat_id'=>$prod_categories->prod_cat_id));
			echo "<h4>$prod_categori->prod_cat_name</h4>";
		endforeach;
		return  ;
  }
}


