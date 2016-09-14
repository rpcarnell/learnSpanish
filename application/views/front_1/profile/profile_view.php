<?php // $method = $this->router->fetch_method();
 
?>
<div class="spanish">
    <div class="spanish-content profile">
       
        <div class="profile-info-top fr">
            
            <div class="profileInfoLeft">

                <p>Name: <?php echo $customer->name;   ?></p>
                <p>E-Mail: <?php echo $customer->email_list;   ?></p>
            <!-- <p><span>Location:</span> <?php echo $customer->city.", ".$customer->state.", ".$customer->country ?></p>-->
             <p>Phone:<?php echo $customer->phone ?></p>
              <!--<p><span>Skype:</span> <?php echo $customer->skype ?></p>-->

                

             

                <p class="fl"><a href="<?php echo base_url('signup_login/editProfile/'.$customer->Customer_ID) ?>" style="text-decoration: none;"><input type="submit" value="Edit My Profile"></a></p>

            </div>
           <div class="profileInfoRight">
               <?php if ($teachersNum > 0) { } 
               else { ?><p><b><a href='<?php echo base_url();?>calendar/tutors'>Schedule Time with New Tutor</a></b></p><?php } ?>
               <!--<p><a href='<?php echo base_url();?>billing/balance'>Pay Outstanding Balance of $38.92</a></p>-->
               <?php echo $outs; ?>
               <p><a href='<?php echo base_url();?>billing/purchase'>Make Purchase</a></p>
               <p><a href='<?php echo base_url();?>billing/history'>View Billing History</a></p>
               <p><a href='<?php echo base_url();?>profile/managSubscr'>Manage View Your Subscription/s</a></p>
			    <p><a href='<?php echo base_url();?>calendar/futureapp'>Show List of Future Appointments</a></p>
                            <p><a href='<?php echo base_url();?>calendar/appointcalen'>Scheduling Calendar</a></p>
                           <!-- <p><a href='<?php echo base_url();?>tutors'>Schedule Time with New Tutor</a></p>-->
                            <p><a href='<?php echo base_url();?>profile/emailtutor'>Send an Email to your Tutor</a></p>
			   </div>
			   
            <div class="clr"></div>
        </div>
      
      
      
     
     
        <div class="clr"></div>
        
        


    </div>
</div>




