<?php //echo page_restrict_author(); ?>
<?php //echo page_restrict_manager(); 
 
?>
<div id="main-wrapper">
    <div class="clr"></div>
    <div class="content-wrapper">
            <?php echo $breadcrumbs; ?><br /><br />
            
        	<div class="content-header title-bars">
       			<h2>Edit User <?php echo (isset($spanish->name)) ? $spanish->name : ''; ?></h2>
                <div class="clr"></div>                
            </div>
           
			
            	
                   
           
           
            	
            
             <?php echo form_open_multipart(); if ($success) { ?>
             <p><?php  echo $success; ?> </p>
              
						<?php } if(!empty($spanish->photo) && trim($spanish->photo) != ''):?>	
							<img src = "<?php echo base_url('uploads/tutors/'.$spanish->photo)?>" style="max-height: 300px;" /><br />
						<?php else:
                                                      if (1 == 2) {?>
							<img src = "<?php echo base_url('images/spanishforgood.png')?>" width="182" height="232" /><br />
						<?php 
                                                             
                                                 }
                                                      endif?>
					 <br /> 
             <div class="clr"></div>
            	<input type="hidden" name='date_today' value="<?php  echo $today = date("Y-m-d H:m:s");?>">
         		<div class="fl label">TUTOR NAME</div>
                <div class="fl"><input type="text" name="name" value='<?php echo (isset($spanish->name)) ? $spanish->name : ''; ?>' style=" height:20px; width:300px; "><?php echo form_error('name'); ?><div class="clr"></div><div class="clr"></div></div>
                <div class="clr"><br /></div> 
                
                 <div class="fl label">Picture</div>
                <div class="fl"><div> <input type='file' name="rolephoto" /></div>
                    <div class="clr"></div></div>
               
                 <br /> 
                
                <div class="fl label">BIO</div>
                <div class="fl"><textarea name="description" style="height:250px; width: 70%;"><?php echo (isset($spanish->bio)) ? $spanish->bio : '';?></textarea><?php echo form_error('description'); ?><div class="clr"></div></div>
                <div class="clr"><br /></div> 
                
                <div class="fl label">PHONE</div>
                <div class="fl"><input type="text" name="phone" style=" height:20px; width:300px; " value='<?php echo (isset($spanish->phone)) ? $spanish->phone : '';?>'> <?php echo form_error('phone'); ?><div class="clr"></div></div>
                <div class="clr"><br /></div> 
                
                <div class="fl label">TIME ZONE</div>
                 <div class="fl"><select id="time_zone" name="time_zone">
                                                    <option value="">Select a timezone...</option>
                                                    <option value="-12.0">(GMT-12:00) International Date Line West</option>
                                                    <option value="-11.0">(GMT-11:00) Midway Island, Samoa</option>
                                                    <option value="-10.0">(GMT-10:00) Hawaii</option>
                                                    <option value="-9.0">(GMT-09:00) Alaska</option>
                                                    <option value="-8.0">(GMT-08:00) Pacific Time (US &amp; Canada), Tijuana</option>
                                                    <option value="-7.0">(GMT-07:00) Arizona</option>
                                                    <option value="-7.0">(GMT-07:00) Chihuahua, La Paz, Mazatlan</option>
                                                    <option value="-7.0">(GMT-07:00) Mountain Time (US &amp; Canada)</option>
                                                    <option value="-6.0">(GMT-06:00) Central America</option>
                                                    <option value="-6.0">(GMT-06:00) Central Time (US &amp; Canada)</option>
                                                    <option value="-6.0">(GMT-06:00) Guadalajara, Mexico City, Monterrey</option>
                                                    <option value="-6.0">(GMT-06:00) Saskatchewan</option>
                                                    <option value="-5.0">(GMT-05:00) Bogota, Lima, Quito</option>
                                                    <option value="-5.0">(GMT-05:00) Eastern Time (US &amp; Canada)</option>
                                                    <option value="-5.0">(GMT-05:00) Indiana (East)</option>
                                                    <option value="-4.0">(GMT-04:00) Atlantic Time (Canada)</option>
                                                    <option value="-4.0">(GMT-04:00) Caracas, La Paz</option>
                                                    <option value="-4.0">(GMT-04:00) Santiago</option>
                                                    <option value="-3.5">(GMT-03:30) Newfoundland</option>
                                                    <option value="-3.0">(GMT-03:00) Brasilia</option>
                                                    <option value="-3.0">(GMT-03:00) Buenos Aires, Georgetown</option>
                                                    <option value="-3.0">(GMT-03:00) Greenland</option>
                                                    <option value="-2.0">(GMT-02:00) Mid-Atlantic</option>
                                                    <option value="-1.0">(GMT-01:00) Azores</option>
                                                    <option value="-1.0">(GMT-01:00) Cape Verde Is.</option>
                                                    <option value="-0.0">(GMT 0:00) Casablanca, Monrovia</option>
                                                    <option value="-0.0">(GMT 0:00) London, Lisbon</option>
                                                    <option value="+1.0">(GMT+01:00) Amsterdam, Berlin, Bern, Rome, Stockholm, Vienna</option>
                                                    <option value="+1.0">(GMT+01:00) Belgrade, Bratislava, Budapest, Ljubljana, Prague</option>
                                                    <option value="+1.0">(GMT+01:00) Brussels, Copenhagen, Madrid, Paris</option>
                                                    <option value="+1.0">(GMT+01:00) Sarajevo, Skopje, Warsaw, Zagreb</option>
                                                    <option value="+1.0">(GMT+01:00) West Central Africa</option>
                                                    <option value="+1.0">(GMT+02:00) Athens, Beirut, Istanbul, Minsk</option>
                                                    <option value="+2.0">(GMT+02:00) Bucharest</option>
                                                    <option value="+2.0">(GMT+02:00) Cairo</option>
                                                    <option value="+2.0">(GMT+02:00) Harare, Pretoria</option>
                                                    <option value="+2.0">(GMT+02:00) Helsinki, Kyiv, Riga, Sofia, Tallinn, Vilnius</option>
                                                    <option value="+2.0">(GMT+02:00) Jerusalem</option>
                                                    <option value="+3.0">(GMT+03:00) Baghdad</option>
                                                    <option value="+3.0">(GMT+03:00) Kuwait, Riyadh</option>
                                                    <option value="+3.0">(GMT+03:00) Moscow, St. Petersburg, Volgograd</option>
                                                    <option value="+3.0">(GMT+03:00) Nairobi</option>
                                                    <option value="+3.0">(GMT+03:30) Tehran</option>
                                                    <option value="+4.0">(GMT+04:00) Abu Dhabi, Muscat</option>
                                                    <option value="+4.0">(GMT+04:00) Baku, Tbilisi, Yerevan</option>
                                                    <option value="+4.5">(GMT+04:30) Kabul</option>
                                                    <option value="+5.0">(GMT+05:00) Ekaterinburg</option>
                                                    <option value="+5.0">(GMT+05:00) Islamabad, Karachi, Tashkent</option>
                                                    <option value="+5.5">(GMT+05:30) Chennai, Kolkata, Mumbai, New Delhi</option>
                                                    <option value="+6.0">(GMT+06:00) Almaty, Novosibirsk</option>
                                                    <option value="+6.0">(GMT+06:00) Astana, Dhaka</option>
                                                    <option value="+6.0">(GMT+06:00) Sri Jayawardenepura</option>
                                                    <option value="+6.5">(GMT+06:30) Rangoon</option>
                                                    <option value="+7.0">(GMT+07:00) Bangkok, Hanoi, Jakarta</option>
                                                    <option value="+7.0">(GMT+07:00) Krasnoyarsk</option>
                                                    <option value="+8.0">(GMT+08:00) Beijing, Chongqing, Hong Kong, Urumqi</option>
                                                    <option value="+8.0">(GMT+08:00) Irkutsk, Ulaan Bataar</option>
                                                    <option value="+8.0">(GMT+08:00) Kuala Lumpur, Singapore</option>
                                                    <option value="+8.0">(GMT+08:00) Perth</option>
                                                    <option value="+8.0">(GMT+08:00) Taipei</option>
                                                    <option value="+9.0">(GMT+09:00) Osaka, Sapporo, Tokyo</option>
                                                    <option value="+9.0">(GMT+09:00) Seoul</option>
                                                    <option value="+9.0">(GMT+09:00) Vakutsk</option>
                                                    <option value="+9.5">(GMT+09:30) Adelaide</option>
                                                    <option value="+9.5">(GMT+09:30) Darwin</option>
                                                    <option value="+10.0">(GMT+10:00) Brisbane</option>
                                                    <option value="+10.0">(GMT+10:00) Canberra, Melbourne, Sydney</option>
                                                    <option value="+10.0">(GMT+10:00) Guam, Port Moresby</option>
                                                    <option value="+10.0">(GMT+10:00) Hobart</option>
                                                    <option value="+10.0">(GMT+10:00) Vladivostok</option>
                                                    <option value="+11.0">(GMT+11:00) Magadan, Solomon Is., New Caledonia</option>
                                                    <option value="+12.0">(GMT+12:00) Auckland, Wellington</option>
                                                    <option value="+12.0">(GMT+12:00) Fiji, Kamchatka, Marshall Is.</option>
                                                    <option value="+13.0">(GMT+13:00) Nuku'alofa </option>
        </select>
                
                 </div>
                <div class="clr"><br /></div> 
                
                
                <?php if (isset($spanish)) { ?> <div style='color: #a00;'><p><b>Leave the passwords blank unless you want to change the passwords as well</b></p></div><?php } ?>
                
                <div class="fl label">PASSWORD</div>
                <div class="fl"><input type="password" name="password" style=" height:20px; width:300px;"> <?php echo form_error('password'); ?><div class="clr"></div></div>
                <div class="clr"><br /></div>
                
                <div class="fl label">CONFIRM PASSWORD</div>
                <div class="fl"><input type="password" name="cpassword" style=" height:20px; width:300px;"><?php echo form_error('cpassword'); ?><div class="clr"></div></div>
                <div class="clr"><br /></div>
               	
                <div class="fl label">EMAIL</div>
                <div class="fl"><input type="text" name="emails" value='<?php echo (isset($spanish->email_list)) ? $spanish->email_list : '';?>' style=" height:20px; width:300px;"><?php echo form_error('emails'); ?><div class="clr"></div></div>
                <div class="clr"><br /></div>
                
                 <div class="fl label">SKYPE</div>
                <div class="fl"><input type="text" name="skype_name" value='<?php echo (isset($spanish->skype_name)) ? $spanish->skype_name: '';?>' style=" height:20px; width:300px;"><div class="clr"></div></div>
                <div class="clr"><br /></div>
                
              <div class="fl label"> Notes: Both Admin and Tutor can see:</div>
                  <div class="fl">                  
                      <textarea name="notes_tutor_viewable" style="width: 250px; height: 100px;"><?php echo isset($spanish->notes_tutor_viewable) ? $spanish->notes_tutor_viewable  : '';?></textarea>
                  </div>           
                <div class="fl label">Notes: Only Administrator can see:</div>
                  <div class="fl">
                                   
                                    <textarea name="notes_hidden_from_tutor" style="width: 250px; height: 100px;"><?php echo isset($spanish->notes_hidden_from_tutor) ? $spanish->notes_hidden_from_tutor  : '';?></textarea>
                </div> 
              <div class="clr"></div>
                <?php if (isset($spanish)) { ?>
              <br />
             <div class="fl label"><input name='deleted' type="checkbox" <?php echo (isset($spanish->deleted) && $spanish->deleted) ? 'checked' : '';?> /> Toggle Deleted<div>
                <br />
                
             <div class="fl label"><input name='inactive' type="checkbox" <?php echo (isset($spanish->inactive) && $spanish->inactive) ? 'checked' : '';?> />  Toggle Inactive<div>
                <br /> <br />
                <?php } ?>
                <input type='hidden' name='tutor_ID' value='<?php echo (isset($spanish->tutor_ID)) ? $spanish->tutor_ID : '';?>' />
                <?php if (isset($spanish)) { ?>
                <input type="submit" name="edittutor" value="Save Changes"  style="width:150px; padding:5px; margin-bottom:10px;" >
                <?php } else { ?>
                 <input type="submit" name="add" value="ADD TUTOR"  style="width:150px; padding:5px; margin-bottom:10px;" >
                <?php }
              echo form_close(); ?> 
            
                
            </div>
            <div class="clr"></div>
        
        </div>
    	</div> 
    </div>
     <div class="clr"></div>
     
                 
                </div>    </div>
<?php
if (isset($spanish->time_zone)) {
?>
<script language="javascript">
$(function() {
            $('#time_zone').val('<?php echo $spanish->time_zone; ?>');
});
</script>
<?php } ?>