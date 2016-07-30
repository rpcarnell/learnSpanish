<div id="main-wrapper">
    <div class="clr"></div>
    <div class="content-wrapper">
        <?php echo $breadcrumbs; ?><br /><br />
        <div class="content-wrapper">
        	<div class="content-header title-bars">
       			<h2>Admin Setting</h2>
                <div class="clr"></div>                
            </div>
           
			
      
                       
           
           
            <div class="content-body">
            	
             <div class="form-conatiner-2 fl">
             <?php echo form_open(); ?>
             <p style="color: #0a0;"><?php  echo $success; ?> </p>
             <div class="clr"></div>
            	<input type="hidden" name='date_today' value="<?php  echo $today = date("Y-m-d H:m:s");?>">
         		 <div class="clr"></div>
                <div class="fl label">USERNAME</div>                
                <div class="fl"><input type="text"  name="uname" style=" height:20px; width:300px;" value="<?php echo $this->input->post('uname') ? $this->input->post('uname') : (isset($spanish->username) ? $spanish->username : ''); ?>">
                    <div style="color: #a00"><?php echo form_error('uname'); ?></div><div class="clr"></div></div>
                <br /><div class="clr"></div>
                
                <div class="fl label">PASSWORD</div>
                <div><input type="password" name="pwd" style=" height:20px; width:300px;" value=""> <?php echo form_error('password'); ?>
                    <div style="color: #a00"><?php  if (true === $add_admin) { echo form_error('pwd'); } else { echo $password_error; } ?></div>
                    <div class="clr"></div></div>
                 <br />
				<div class="clr"></div>                
                
                <div class="fl label">CONFIRM PASSWORD</div>
                <div class="fl"><input type="password" name="conf_pwd" style=" height:20px; width:300px;" value=""> <?php echo form_error('cpassword'); ?> <div class="clr"></div></div>
                 <br />
                <div class="clr"></div>
               	
                <div class="fl label">EMAIL</div>
                <div class="fl"><input type="text" name="email" style=" height:20px; width:300px;" value="<?php echo  $this->input->post('email') ? $this->input->post('email') : (isset($spanish->email) ? $spanish->email : ''); ?>">  
                    <div style="color: #a00"><?php echo form_error('email'); ?></div>
                    <div class="clr"></div></div>
                 <br />
                <div class="clr"></div>
                
                
                 <div class="fl label">PHONE</div>
                 <div class="fl"><input type="text" name="phone" style=" height:20px; width:300px; " value='<?php echo $this->input->post('phone') ? $this->input->post('phone') : (isset($spanish->phone) ? $spanish->phone : '');?>'><divc style="color: #a00"><?php echo form_error('phone'); ?></div><div class="clr"></div></div>
                              
               
                
                
                  <div class="fl label">SKYPE</div>
                <div class="fl"><input type="text" name="skype" value='<?php echo  $this->input->post('skype') ? $this->input->post('skype') : (isset($spanish->skype) ? $spanish->skype: '');?>' style=" height:20px; width:300px;"><div class="clr"></div></div>
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
                 <div style="color: #a00"><?php echo form_error('time_zone');  ?></div>
                <div class="clr"><br /></div> 
                 <?php
                 $admin_type = isset($spanish->admin_type) ? $spanish->admin_type : 1;
                 $segment_2 = strtolower(trim($this->uri->segment(2)));
                
                 
                 if ($segment_2 != 'edityouraccount' && (!isset($spanish) || !isset($spanish->username) || $spanish->username != 'admin') && $adminCredentials['admin_type'] > 1) {
                   
                      if ($adminCredentials['username'] == 'admin' || ($adminCredentials['username'] != 'admin' && $spanish->admin_type < 2)) {
                 ?>
                <br />
                  <div class="fl label">ADMINISTRATOR TYPE</div>
                 <div class="fl">
                     <select name="admin_type">
                         <option value="1" <?php echo ($admin_type == 1) ? "selected='selected'": ""; ?>>Administrator</option>
                         <option value="2" <?php echo ($admin_type == 2) ? "selected='selected'": ''; ?>>Super-Administrator</option>
                     </select>
                     </div>
                 <?php }} 
                 else { echo '<input type="hidden" name="admin_type" value="'.$admin_type.'" />'; }
                 ?>           
                <div class="clr"><br /><br /></div> 
                <?php if (isset($spanish->admin_id) && is_numeric($spanish->admin_id)) { ?>
                <input type="hidden" name="admin_id" value="<?php echo $spanish->admin_id;?>" />
                <input type="hidden" name="admin_edit" value="1" />
                <?php } else { ?>
                <input type="hidden" name="admin_add" value="1" />
                <?php } ?>
                <input type="submit" name="update" value="Update"  style="width:100px; padding:5px; margin-bottom:10px;" >
            <?php echo form_close(); ?> 
            
                
            
            <div class="clr"></div>
        
        </div>
    
    </div>
     <div class="clr"></div>
     </div>
         </div>
<?php 
if (isset($spanish->time_zone) || $this->input->post('time_zone')) {
    $time_zone = $this->input->post('time_zone') ? $this->input->post('time_zone') : $spanish->time_zone;
?>
<script language="javascript">
$(function() {
            $('#time_zone').val('<?php echo $time_zone; ?>');
});
</script>
<?php } ?>