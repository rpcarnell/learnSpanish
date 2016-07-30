

<div id="main-wrapper">
    <div class="clr"></div>
    <div class="content-wrapper">
        <div class="admin-sidebar fl">
        
        </div>
        <div class="content-page-view fl" style="width: 625px !important;">
            
  <div id="header-wrapper">
		<div class="top-bg">
			<div class="mafia-top">
				<div class="logo fl">
                                                                                                                  
      <!--<img src="<?php echo base_url();?>images_front/page_template/logo.png" width="151" height="146" alt="" title="" /><!--<a href="<?php echo base_url();?>"></a>-->
                                </div>
				<div class="mafia-menu-inner fr">
					
				</div>
				<div class="clr"></div>
			</div>
		</div>
        
        <div class="mid-header">
        	<div class="main-menu">

            	<ul class="main-menu-list">
                <?php
					$class = $this->router->fetch_class();
					// $method = $this->router->fetch_method(); 
					$method = $this->uri->segment(2);
				?>
                    <li class="pages <?php if ($method == 'manage_tutors' || $method == 'approve_spanish' || $method == 'recommend_spanish' || $method == 'view_spanish' || $method == 'review_spanish'){ echo ' active';}?>"><a href="<?php echo base_url('admin/manage_tutors') ?>">Edit / Create Tutors</a></li>
                   
                                        
					
                    <li class="pages <?php if ($method == 'pages'){ echo ' active';}?>"><a href="<?php echo base_url('admin/gettutmails');?>">Get Tutors Email Addresses</a></li>
                    
                   <li class="pages"><a href="<?php echo base_url('admin/editstudents');?>">Get / Create Student Accounts</a></li>
                    
                     <li class="pages"><a href="<?php echo base_url('admin/getstudmails');?>">Get Students Email Addresses</a></li>
                    
                    
                    
                    
                    <li class="pages <?php if ($method == 'admin_setting'){ echo ' active';}?>"><a href="<?php echo base_url('admin/admin_setting');?>">Edit / Create an Admin Account</a></li>
                   
                     <li class="pages"><a href="<?php echo base_url('admin/invoices');?>">View Invoices and Payments</a></li>
                     
                     <li class="pages"><a href="<?php echo base_url('admin/editValues');?>">Edit Global Store Values</a></li>
                     
                     <li class="pages"><a href="<?php echo base_url('admin/editprices');?>">Edit Prices and Offerings</a></li>
                     
                      <li class="pages"><a href="<?php echo base_url('admin/edityouraccount');?>">Edit Your Account Info</a></li>
                      
                      <li class="pages"><a href="<?php echo base_url('admin/editcalendar');?>">Edit calendar</a></li>
                     
					
                                       <!-- <li class="pages <?php if ($method == 'gen'){ echo ' active';}?>"><a href="<?php echo base_url('admin/general_setting')?>">General Setting</a></li>				 -->
                </ul>
             </div>
			 
			<style type="text/css">
				.lolale{ font-family: "tahoma"; font-size: 12px;}
			</style>
             <div class="sub-menu">   
			 
				
				<ul class="sub-menu-list lolale" style="<?php if ($method == 'manage_tutors' || $method == 'review_spanish' || $method == 'approve_spanish' || $method == 'recommend_spanish' || $method == 'recommended_dancer_details'|| $method == 'view_spanish'){ echo 'display:block;';}else{echo 'display:none;';}?>">
                	<li><a href="<?php echo base_url('admin/manage_tutors');?>" style="<?php if($method == 'manage_tutors') echo 'text-decoration:underline;'?>">Manage spanish</a></li>
					<li><a href="<?php echo base_url('admin/approve_spanish ');?>" style="<?php if($method == 'approve_spanish') echo 'text-decoration:underline;'?>">Approve spanish</a></li>
					<li><a href="<?php echo base_url('admin/recommend_spanish');?>" style="<?php if($method == 'recommend_spanish') echo 'text-decoration:underline;'?>">Recommended spanish</a></li>	
                </ul>
				
             
                
                
            </div>
        </div>
    </div>
        </div>
        <div class="clr"></div>


    </div>

</div>