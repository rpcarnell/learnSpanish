<?php
?>
 
        <div class="home-main-content"><?php echo $breadcrumbs; ?></div>
        <div style="clear: both;"></div>
<div class="spanish">
    

    <div class="spanish-form contact-us-content">
      
        <div>
             
            <div>
                <div>
                    <h4 class="orange">Edit Your Profile</h4>

                    <div style="display: <?php echo($err == 0 ? 'none' : 'block') ?>">
                        <span class="warning">There are errors</span>
                    </div>
                    <div style="color: #0a0;"><?php echo $success;?></div>
                        <?php
                        
                       
                        
                        
                    ?>
                    <form action="<?php echo base_url() ?>signup_login/editProfile/<?php echo $row->Customer_ID; ?>" method="post">
                        <ul class="signup-table">
                            <li>* Name:<br /> <input type="text" name="name" value="<?php echo($this->input->post('name') != '' ? $this->input->post('name') : $row->name) ?>" class="clearField"/>
                                <div class="warning"><?php echo form_error('name'); ?></div>
                            </li>
                           
                            <li>* E-Mail:<br /> 
                               <input type="text" name="email" value="<?php echo($this->input->post('email') != '' ? $this->input->post('email') : $row->email_list) ?>" class="clearField"/>
                             <div class="warning"><?php echo form_error('email'); echo " ".$emailerror; ?> </div>
                            </li>
                            
                            <li>Phone Number:<br />  
                               <input type="text" name="phone" value="<?php echo($this->input->post('phone') != '' ? $this->input->post('phone') : $row->phone) ?>" class="clearField"/>
                             <div class="warning"><?php echo form_error('phone'); ?></div>
                            </li>
                            
                             <li>
                                Skype:<br /> 
                                <input id="skype" type="name" name="skype" value="<?php echo($this->input->post('skype') != '' ? $this->input->post('skype') : $row->skype) ?>" autocomplete="off"/>
                            </li>
                             <li>* Where in the world are you?<br />(help us ensure your timezone is correct):<br /> 
                              <select id="time_zone" name="time_zone">
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
                                 <div class="warning"><?php  echo form_error('time_zone');?> </div>
                            </li>
                           <li><div style="padding: 5px; font-size: 11px;">Leave these fields empty if you don't want to change your password</div>
                                Password:<br /> 
                                <input id="password-password1" type="password" name="pwd" value="" autocomplete="off"/>
                                <div class="warning"><?php  echo $password_error;?> </div>
                            </li>
                            <li>
                                Confirm Password:<br /> 
                                <input id="password-password2" type="password" name="conf_pwd" value="" autocomplete="off"/>
                            </li>
                          <li><br /><input type="submit" name="reg" value="Edit Probile"/></li>
                        </ul>
                        <input type='hidden' name='customer_id' value='<?php echo $row->Customer_ID ;?>' />
                        <input type='hidden' name='profile_edit' value='1' />
                    </form>
                </div>
            </div>
        </div>
        <div class="clr"></div>
    </div>
</div>
<script language="javascript">
$(function() {
            $('#time_zone').val('<?php echo $row->time_zone; ?>');
});
</script>