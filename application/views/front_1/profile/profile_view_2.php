<?php
?>
<div class="spanish">
    <div class="spanish-content profile">
       
        <div class="profile-info-top fr">
              <?php echo $breadcrumbs; ?><br /><br /> 
            <div>
<div style='padding: 0px 5px; background: #fff; border-radius: 5px; border: 1px solid #ddd;margin: 5px 0px;'>
                <p>Name: <?php echo $customer->name;   ?></p>
                <p>E-Mail: <?php echo $customer->email_list;   ?></p>
             <p>skype: <?php echo $customer->skype;?></p>
             <p>Phone: <?php echo $customer->phone ?></p>
            
</div>
                
       <div style='padding: 0px 5px; background: #fff; border-radius: 5px; border: 1px solid #ddd;margin: 5px 0px;'>     
           <h3>User Location</h3>
             <p style='font-size: 11px;'>Address: <?php echo $customer->address;?></p>
             <p><?php echo $customer->city;?>, <?php echo $customer->state;?>, <?php echo $customer->country;?></p>
             <p style='font-size: 11px;'>Zip: <?php echo $customer->zip;?></p>
              <p style='font-size: 11px;'>Time Zone: <?php echo $customer->time_zone;?></p>
       </div>
   <div style='padding: 0px 5px; background: #fff; border-radius: 5px; border: 1px solid #ddd;margin: 5px 0px;'>           
              <h3>User Status</h3>
              <p style='font-size: 11px;'>E-mail Confirmed: <?php echo ($customer->email_confirmed) ? 'Yes' : 'No'?> </p>
              <p style='font-size: 11px;'>Customer Deleted: <?php echo ($customer->deleted) ? 'Yes' : 'No'?></p>
               <p style='font-size: 11px;'>Customer Inactive: <?php echo ($customer->inactive) ? 'Yes' : 'No';?></p>
              
               </div> 
                 <div style='padding: 0px 5px; background: #fff; border-radius: 5px; border: 1px solid #ddd;margin: 5px 0px;'> 
                     <h3>User Notes</h3>
              <p style='font-size: 11px;'>Notes Customer Viewable: <?php echo ($customer->notes_cust_viewable) ? $customer->notes_cust_viewable : '';?></p>
              <p style='font-size: 11px;'>Notes Hidden from Customer: <?php echo ($customer->notes_hidden_from_cust) ? $customer->notes_hidden_from_cust : '';?></p>
              
              <p style='font-size: 11px;'>How Did You Find Us: <?php echo ($customer->how_did_you_find_us) ? $customer->how_did_you_find_us : 'Not available';?></p>
              <p style='font-size: 11px;'>How Did You Find Other: <?php echo ($customer->how_did_you_find_other) ? $customer->how_did_you_find_other : 'Not available';?></p>
              
   </div>    
              
              
                <p class="fl"><a href="<?php echo base_url('admin/edit_student/'.$customer->Customer_ID) ?>" style="text-decoration: none;"><input type="submit" value="Edit Profile"></a></p>

            </div>
   
			   
            <div class="clr"></div>
        </div>
      
      
      
     
     
        <div class="clr"></div>
        
        


    </div>
</div>




