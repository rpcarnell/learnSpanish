<div id='firstheader'>
   
    <img src="<?php echo base_url() ?>images/spanishforgood.png" alt="" title="" style="width: 50%; float: left;" />
     <ul >
        
       <li><a href="<?php echo base_url() ?>contact_us">Contact us</a></li>
	<!--<li><a href="<?php echo base_url() ?>privacy_policy">Privacy Policy</a></li>   removed by corky-->  
	<!--<li><a href="<?php echo base_url('terms_and_condition') ?>">Terms and conditions</a></li>-->
	<li ><a href="">Home</a></li>
    </ul>
    
  
    <div style='clear: both;'></div>
</div>

<div class="fr" style='background: #fff; height: 40px;'>
    <?php if (isset($breadcrumbs) && $breadcrumbs != '') { ?>
 <div class="home-main-content_2" style='float: left; margin: 0px; padding: 0px;'>
     <?php echo $breadcrumbs; ?></div>
    <?php } ?>
  <div style="float: right; padding: 0px; margin: 0px; width: 150px;">
		<?php
		if(! $this->session->userdata('login_customer'))
		{   
                    $tutor_id_logged = $this->session->userdata('tutorid');
                    if (is_numeric($tutor_id_logged) && $tutor_id_logged > 0)
                    {
                        ?>
		<p class="fr"><a href="<?php echo base_url('tutors/logout') ?>">Logout</a></p>
		<?php
                    } else { 
		?>
		<p class="fr"><a href="<?php echo base_url('signup_login') ?>">Sign Up or Log In</a></p>
		<?php
                    }}
		 else {
                  
        $customer_id = $this->session->userdata('customerid');
        $tutor_id_logged = $this->session->userdata('tutorid');
        if (is_numeric($customer_id)) {
		?>
		<p class="fr"><a href="<?php echo base_url() ?>profile">My Profile</a> | <a href="<?php echo base_url('signup_login/logout') ?>">Logout</a></p>
		<?php
                     }
                     elseif (is_numeric($tutor_id_logged) && $tutor_id_logged > 0)
                    {
                        ?>
		<p class="fr"><a href="<?php echo base_url('tutors/logout') ?>">Logout</a></p>
		<?php
                    }
                     else { 
		?>
		<p class="fr"><a href="<?php echo base_url('signup_login') ?>">Sign Up or Log In</a></p>
		<?php
                    }
        }
		?>

		
		<!--<div class="search fr">
			<form action="<?php echo base_url()?>spanish" method="post">
				<div><input type="text" name="search_keyword" value="" placeholder="Search Dancer"/></div>
				<div><input type="submit" name="search_dancer" value="" /></div>
			</form>
		</div>
		<div class="clr"></div>-->
	</div>	
	 
	 
	<div class="clr"></div>
</div>
<div class="clr"></div>
