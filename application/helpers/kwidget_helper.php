<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/************
Title: Widget Helper For Mindblow Cms 
Author: Kenneth Mwervin Enriquez

***************/

if(!function_exists('recent_order')){
	function recent_order(){
		$CI = get_instance();
		$recent_order = $CI->My_model->select_recent_limit('order','order_id','5');
		
		
		echo '<ul class="recent_order_table">';
			echo "<li style='width:80px; text-align:center;' class='fl'>Order id</li><li style='width:150px; text-align:center;' class='fl'>Product Name</li><li style='width:120px; text-align:center;' class='fl'>Email</li><div class='clr'></div>";
		
		foreach ($recent_order as $recent_orders):
			echo "<li style='width:80px; text-align:center;' class='fl'>".$recent_orders->order_id."</li><li style='width:150px; text-align:center;' class='fl'>".product_name($recent_orders->prod_id)."</li><li style='width:120px; text-align:center;' class='fl'>".$recent_orders->order_email."</li><div class='clr'></div>";
		
		endforeach;
		
		echo '</ul>';
		
		return ;
  }
}



