<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/************
Title: Blog Helper For Mindblow Cms 
Author: Kenneth Mwervin Enriquez

***************/

if(!function_exists('blog_categories')){
	function blog_categories(){
		$CI = get_instance();
		$blog_categories = $CI->My_model->select_only('category');
		
		echo '<ul>';
		foreach ($blog_categories as $blog_category):
			echo "<li><a href='".base_url('blog/blog_category')."/$blog_category->cat_id'>$blog_category->cat_title</a></li>";
		endforeach;
		
		echo '</ul>';
		
		return ;
  }
}

if(!function_exists('blog_archives')){
	function blog_archives(){
		$CI = get_instance();
		$blog_archives = $CI->My_model->blog_archive();
		echo "<ul>";
						
		foreach ($blog_archives as $blog_archive):
		echo  '<li><a href="'.base_url('blog/blog_archives').'/'.$blog_archive->months.'/'.$blog_archive->yeahboy.'">'.date("F", mktime(0, 0, 0, $blog_archive->months)). ' ' . $blog_archive->yeahboy.'</a></li>' ;
		endforeach;
		
		echo "</ul>";
		
		return ;
  } 
}

if(!function_exists('category')){
	function category($cat_id){
		$CI = get_instance();
		$category = $CI->My_model->select_where('category', array('cat_id'=>$cat_id));
		$category = $category->cat_title;
		$category = strtoupper($category);
		echo $category;
		return ;
  }  
}

if(!function_exists('categorys')){
	function categorys($cat_id){
		$CI = get_instance();
		$category = $CI->My_model->select_where('category', array('cat_id'=>$cat_id));
		
		
		echo $category->cat_title;
		return ;
  }  
}

if(!function_exists('category1')){
	function category1($cat_id){
		$CI = get_instance();
		$category = $CI->My_model->select_where('category', array('cat_id'=>$cat_id));
		
		
		return  $category->cat_title;
		
  }  
}



if(!function_exists('post_content')){
	function post_content(){
		$CI = get_instance();
		$post_content = $CI->My_model->select_where('post', array('post_id'=> $CI->uri->segment(3)));
		echo $post_content->post_content;
		return ;
  }
 }
  
if(!function_exists('post_title')){
	function post_title(){
		$CI = get_instance();
		$post_content = $CI->My_model->select_where('post', array('post_id'=>$CI->uri->segment(3)));
		echo $post_content->post_title;
		return ;
  }  
}  

if(!function_exists('post_image')){
	function post_image(){
		$CI = get_instance();
		$post_content = $CI->My_model->select_where('post', array('post_id'=>$CI->uri->segment(3)));
		echo "<img src='".base_url('blog')."/".$post_content->post_image."' width='300px' height='270px'>";
		return ;
  }  
}

