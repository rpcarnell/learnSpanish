<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/************************************
Title: General Helper for Mindblow Creatives.
Author: Kenneth Mervin Enriquez.
****************************/




/************Site Title***************/

if(!function_exists('site_title')){
 	
	function site_title(){
		$CI = get_instance();
		$title_site = $CI->My_model->select_where('general_setting',array('id'=>1));
		$site_title = $title_site->site_title;
		return $site_title;
  }
}


if(!function_exists('user_name')){
 	
	function user_name(){
		$CI = get_instance();
		echo $CI->session->userdata('username');
		return;
  }
}


if(!function_exists('rand_string')){


 function rand_string( $length ) {
		$chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";	

		$size = strlen( $chars );
		for( $i = 0; $i < $length; $i++ ) {
			@$str .= $chars[ rand( 0, $size - 1 ) ];
		}

		return @$str;
	}
}	


/****************Restrict User type dont show menu********************************/

if(!function_exists('user_restrict_manager')){
 	
	function user_restrict_manager(){
		$CI = get_instance();		
		$usertype =  $CI->session->userdata('user_type');
		if ($usertype=="Manager"){
		 echo "style='display:none;'";
		}
		return;
  }
}



if(!function_exists('user_restrict_author')){
 	
	function user_restrict_author(){
		$CI = get_instance();		
		$usertype =  $CI->session->userdata('user_type');
		if ($usertype=="Author"){
		 echo "style='display:none;'";
		}
		return;
  }
}
/*********************************/
/**************Restrict User cant access page(url)*********************/
if(!function_exists('page_restrict_author')){
 	
	function page_restrict_author(){
		$CI = get_instance();		
		$usertype =  $CI->session->userdata('user_type');
		if ($usertype=="Author"){
		 echo '<script>
		 alert("You Cant Access this Page.. You will be redirected to dashboard");
		 window.location.href = "'.base_url().'admin/dashboard/";
		 </script>';
		
		}
		return;
  }
}

if(!function_exists('page_restrict_manager')){
 	
	function page_restrict_manager(){
		$CI = get_instance();		
		$usertype =  $CI->session->userdata('user_type');
		if ($usertype=="Manager"){
		 echo '<script>
		 alert("You Cant Access this Page.. You will be redirected to dashboard");
		 window.location.href = "'.base_url().'admin/dashboard/";
		 </script>';
		
		}
		return;
  }
}







/**************************************************/







/**********SOcial***********/
if(!function_exists('facebook_share')){
 	
	function facebook_share($url,$title){
	$fbcode ='<a href="http://www.facebook.com/share.php?u='.$url.'&title='.$title.'">Facebook</a>';
  	return $fbcode;	
  }
 }
 
if(!function_exists('twitter_share')){
	function twitter_share($url,$title){
	$fbcode ='<a href="http://twitter.com/home?status='.$title.'+'.$url.'">Twitter</a>';
  	return $fbcode;	
  }
 } 

/*************get Image products***************/
if(!function_exists('image_prod')){
 	
	function image_prod($id){
		$CI = get_instance();
		$img_count = $CI->My_model->count_where('product_images',array(
		'status'=>1,
		'prod_id'=>$id));
		$img_prod = $CI->My_model->select_where('product_images',array(
		'status'=>1,
		'prod_id'=>$id));
		if ($img_count==0){
			$img = "no_image.jpg";
		}else{
		$img = $img_prod->prod_image ;
		}
		return $img;
  }
}

if(!function_exists('cat_prod')){
 	
	function cat_prod($id){
		$CI = get_instance();
		$cat_prod = $CI->My_model->select_where_c('assign_product_category',array('prod_id'=>$id));
		$cat_count = $CI->My_model->count_where('assign_product_category',array('prod_id'=>$id));
		
		
		if ($cat_count < 1){
			echo "Uncategorized";
		
		}
		else{
		foreach( $cat_prod as $cat_prods):
			$prod_cat_name = $CI->My_model->select_where('product_category',array('prod_cat_id'=>$cat_prods->prod_cat_id));
			$cats_count = $CI->My_model->count_where('product_category',array('prod_cat_id'=>$cat_prods->prod_cat_id));
			if($cats_count < 1){
				echo '';
			}else{
			
			echo $prod_cat_name->prod_cat_name.", ";
			}
		endforeach;
		}
		return;
		 
  }
}

if(!function_exists('get_cat_name')){
 	
	function get_cat_name($id){
		$CI = get_instance();
		$cat_query = $CI->My_model->select_where('post',array('post_id'=>$id));
		$cat_id = $cat_query->cat_id; 
		$cat_name = $CI->My_model->select_where('category',array('cat_id'=>$cat_id));
		$cat_count = $CI->My_model->count_where('category',array('cat_id'=>$cat_id));
		if  ($cat_count != 1 ){
			echo "Uncategorized";
		} else{
		
		echo $cat_name->cat_title;
		}
		return;
		 
  }
}

/**************Service Available**********************/
if(!function_exists('service_available')){
 	
	function service_available($service_id){
		$CI = get_instance();
		$services = $CI->My_model->select_where('service_manager',array('service_id'=>$service_id));
		$service_stat = $services->service_status;
		if  ($service_stat != 1 ){				
			echo 'style ="display:none;"';	
				}
		return;
		 
  }
}

if(!function_exists('service_blockpage')){
 	
	function service_blockpage($service_id){
		$CI = get_instance();
		$services = $CI->My_model->select_where('service_manager',array('service_id'=>$service_id));
		$service_stat = $services->service_status;
		if  ($service_stat != 1 ){				
			echo '<script>alert("You Disable this Service");  window.location.href = "'.base_url().'admin/dashboard/";</script>';	
			
				}
		return;
		 
  }
}


/*******************Recent Post*************************************/
if(!function_exists('recent_post')){
 	
	function recent_post(){
		$CI = get_instance();
		$recent_post = $CI->My_model->selectall_recent_post('post');
		
		echo '<div class="news-feed"><div><img src="'.base_url().'images_front/home/news_feed.png" width="170" height="38" alt="" title="" /></div>';
		echo '<ul>';
		foreach ($recent_post as $recent_posts):
		$countchar = strlen($recent_posts->post_title);
		$mydate = $recent_posts->post_date;
		$wy = date("M d, Y ",strtotime($mydate));
		if ($countchar > 27){
			echo '<li><a href="#"><h4>'.substr($recent_posts->post_title,0,25).'...</h4><h5>'.$wy.'</h5><div class="clr"></div>';
			echo'<p>'.strip_tags(substr($recent_posts->post_content,0,100)).'</p></a></li>';
		}else{
			echo '<li><a href="#"><h4>'.$recent_posts->post_title.'</h4><h5>'.$wy.'</h5><div class="clr"></div>';
			echo'<p>'.strip_tags(substr($recent_posts->post_content,0,100)).'</p></a></li>';
		}
			
		endforeach;							
		echo '</ul><p class="fr"><a href="#">read more >> </a></p><div class="clr"></div></div>';	
		return;		 
  }
}

if(!function_exists('recent_posts'))
{
	function recent_posts()
	{
		$CI = get_instance();
		$recent_posts = $CI->My_model->selectall_recent('post');
		foreach($recent_posts as $recent_posts_details)
		{
			$post_title = $recent_posts_details->post_title;
			$post_title = str_replace(' ','-',$post_title);
			echo "<li><a href='".base_url('blog/blog_details')."/".$recent_posts_details->post_id."/".category1($recent_posts_details->cat_id)."/".$post_title."'>".$recent_posts_details->post_title."</a></li>";
		
		}
	return;
	}
}


/************************Social*****************************/

if(!function_exists('social_add')){
 	
	function social_add(){
		$CI = get_instance();	
		
		echo '<div class="home-link">
					<div class="h-l-margin"><img  class="newsletter-click" src="'.base_url().'images_front/home/news_feed.jpg" width="270" height="76" alt="" title="Subscribe to our NewsLetter" /></div>
					<div class="h-l-margin"><a href="http://twitter.com/#!/hellomindblow" target="_blank"><img src="'.base_url().'images_front/home/twitter.jpg" width="270" height="74" alt="" title="Follow us on Twitter" /></a></div>
					<div><a href="#"><img src="'.base_url().'images_front/home/facebook.jpg" width="270" height="76" alt="" title="Like us on Facebook" /></a></div>
				</div>';
		return;		 
  }
}

/****************FEatured Products*****************/
if(!function_exists('featured_addon')){
 	
	function featured_addon(){
		$CI = get_instance();	
		
		echo "<div class='latest-delights'>
					<div><img src='".base_url()."images_front/home/latest_delights.png' width='168' height='76' /></div>
					<div class='cupcake-latest'><img src='".base_url()."images_front/home/cupcake.png' width='194' height='229' alt='' title='' /></div>
					<div class='sale'><img src='".base_url()."images_front/home/sale.png' width='101' height='94' alt='' title='' /></div>
					<p>on Mindblow"."'s"."latest </p>
					<p>candied concoction: </p>
					<h2>The Fireproof Cupcake!</h2>
				</div>";
		return;		 
  }
}


/****************sidebar container*****************/
if(!function_exists('sidebar_one')){
	function sidebar_one($page_id){
		$CI = get_instance();
		$count_adon = $CI->My_model->select_where_c_order('assign_addon',array('page_id' => $page_id),'add_id');
			
		foreach ($count_adon as $count_adons ):
		 $addons_id = $count_adons->add_id;	
		
			if($addons_id == 1){
				$bob = $CI->My_model->select_where('add-on',array('add_id' => $addons_id));
				if($bob->add_on_status == "1"){
					echo '<div class="home-entry home-margin fl">';
					echo recent_post();
					echo '</div>';
				}				
			}
			if($addons_id == 2){
				$bob = $CI->My_model->select_where('add-on',array('add_id' => $addons_id));
				if($bob->add_on_status == "1"){
				echo '<div class="home-entry home-margin fl">';
				echo featured_addon();
				echo '</div>';
				}
			}
			if($addons_id == 3){
				$bob = $CI->My_model->select_where('add-on',array('add_id' => $addons_id));
				if($bob->add_on_status == "1"){
				echo '<div class="home-entry home-margin fl">';
				echo social_add();
				echo '</div>';
				}
			}
		
		
		endforeach;
			 
  }
}


/*******************Assign ad on********admin***************/

if(!function_exists('recent_blog_active')){
 	
	function recent_blog_active($page_id){
		$CI = get_instance();
		$rba = $CI->My_model->count_where('assign_addon',array('page_id'=>$page_id,'add_id'=>1));
		if ($rba != 1){
				echo '<a href="'.base_url().'admin/activate_addon/'.$page_id.'/1">Activate</a>';
				
		}else{
			echo '<a href="'.base_url().'admin/deactivate_addon/'.$page_id.'/1">De-activate</a>';
		}
		
		return;
		 
  }
}

if(!function_exists('featured_active')){
 	
	function featured_active($page_id){
		$CI = get_instance();
		$rba = $CI->My_model->count_where('assign_addon',array('page_id'=>$page_id,'add_id'=>2));
		if ($rba != 1){
				echo '<a href="'.base_url().'admin/activate_addon/'.$page_id.'/2">Activate</a>';
				
		}else{
			echo '<a href="'.base_url().'admin/deactivate_addon/'.$page_id.'/2">De-activate</a>';
		}
		
		return;
		 
  }
}

if(!function_exists('social_active')){
 	
	function social_active($page_id){
		$CI = get_instance();
		$rba = $CI->My_model->count_where('assign_addon',array('page_id'=>$page_id,'add_id'=>3));
		if ($rba != 1){
			echo '<a href="'.base_url().'admin/activate_addon/'.$page_id.'/3">Activate</a>';
				
		}else{
			echo '<a href="'.base_url().'admin/deactivate_addon/'.$page_id.'/3">De-activate</a>';
		}
		
		return;
		 
  }
}


if(!function_exists('page_status')){
 	
	function page_status($page_id){
		$CI = get_instance();
		$page = $CI->My_model->select_where('pages',array('page_id'=>$page_id));
		$page_stat = $page->status;
		if  ($page_stat != '1' ){				
			echo 'Unpublished';	
			}else{ echo 'Published';}
			
			
		return;
		 
  }
}
/***************Page***********************/

if(!function_exists('the_content')){
 	
	function the_content($page_id){
		$CI = get_instance();
		$content = $CI->My_model->select_where('pages',array('page_id'=>$page_id));
		echo $content->page_content;
		return;
		 
  }
}

/***************************helper deactivate adonslist***********/

if(!function_exists('adonactive')){
 	
	function adonactive($adid){
		$CI = get_instance();		
		$yeahboy = $CI->My_model->select_where('add-on',array('add_id'=>$adid));
		if ($yeahboy->add_on_status=='1'){
			echo "<a href='".base_url()."admin/deactivate_manager/".$adid."'/>Deactivate</a>";
		}else{
			echo "<a href='".base_url()."admin/activate_manager/".$adid."'/>Activate</a>";
		}
		
		return;
  }
}
if(!function_exists('prod_name')){
	function prod_name($prod_id)
	{
		$CI= get_instance();
		$prodDesc = $CI->My_model->select_where('products',array('prod_id'=>$prod_id));
		if(!$prodDesc)
		{
			echo "<br/>";
		}
		else
		{
			echo $prodDesc->prod_name;
		}
	}
}	

if(!function_exists('prod_desc'))
	{
		function prod_desc($prod_id)
		{
			$CI= get_instance();
			$prodDesc = $CI->My_model->select_where('products',array('prod_id'=>$prod_id));
			if(!$prodDesc)
			{
				echo "<br/>";
			}
			else
			{
				echo $prodDesc->prod_description;
			}
		}	
		
	}
if(!function_exists('fac_image'))
{
	function fac_image($table,$image_id,$where_column)
	{
		$CI = get_instance();
		$where = array($where_column=>$image_id);
		$image = $CI->My_model->select_where($table,$where);
		return $image->fac_img;

	}
}
if(!function_exists('home_image'))
{
	function home_image($image_id)
	{
		$CI = get_instance();
		$where = array('home_image_id'=>$image_id);
		$image = $CI->My_model->select_where('home_image',$where);
		return $image->home_image_name;

	}
}

if(!function_exists('features_image'))
{
	function features_image($image_id)
	{
		$CI = get_instance();
		$where = array('pricing_img_id'=>$image_id);
		$image = $CI->My_model->select_where('pricing_images',$where);
		return $image->pricing_img_name;

	}
}

if(!function_exists('other_features'))
{
	function other_features($image_id)
	{
		$CI = get_instance();
		$where = array('pricing_other'=>$image_id);
		$image = $CI->My_model->select_where('pricing_other',$where);
		return $image->pricing_other_name;

	}
}


