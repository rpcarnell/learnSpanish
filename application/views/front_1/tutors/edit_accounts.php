<div class="spanish">
   <br />
        <div class="home-main-content"><?php echo $breadcrumbs; ?></div>
    <div class="spanish-content contact-us-content">
       
       
        
            <div>
                <div >
                    <h4 class="orange">Spanish for Good: Tutor Acccount</h4>
                      
                    <div style="height: 200px; width: 50%; border: 1px solid #888; overflow-y: auto">
                        	
The site is jonpine.com

This is in a really old version of CI. Back when it had scaffolding with auto generated database front end. Accessing it is at https://secure.absolutenet.com/jonpine.com/admin... However, it's in a protected directory so you won't be able to get to it on the live server.


You can download the code at:
User: ftp-jonpine.com
Pass: pinedoespix*!
Dir: httpdocs

All the code is there but the admin and ordering stuff has redirects to force it to be loaded through the https url.

Download it and get it running locally without the redirects. I'll add them back in once its running on the new server after you are done.

He currently has ses.html for accepting orders from one school. The url I sent you, http://www.jonpine.com/irchs.html is for a new school. This is almost an exact clone. The new page points to the same order2 directory. It needs to go to an order3 directory that has a clone with minor modifications. It needs to have it's own irchs database or if you use the same DB (preferred), a different table. Payments will go to the same merchant account as the other school.

We can have two different admin areas. One for each school.

I've attached the database.

I'll set up the new hosting account on the new server later in the week.

Enjoy!
Tim :)
jonpineshop.sql.gz
                    </div>
                    
                    <br /><br />
                            <div>
             
            <div>
                <div>
                   

                    <div style="display: <?php echo($err == 0 ? 'none' : 'block') ?>">
                        <span class="warning">There are errors</span>
                    </div>
                        <?php
                        
                       
                        
                        
                    ?>
                    <form action="<?php echo base_url('signup_login') ?>/signup" method="post">
                        <ul class="signup-table">
                            <li>* First Name:<br /> <input type="text" name="fname" value="<?php echo($this->input->post('fname') != '' ? $this->input->post('fname') : 'First Name') ?>" class="clearField"/>
                                <div class="warning"><?php echo form_error('fname'); ?></div>
                            </li>
                            <li>* Last Name:<br /> <input type="text" name="lname" value="<?php echo($this->input->post('fname') != '' ? $this->input->post('lname') : 'Last Name') ?>" class="clearField" />
                               <div class="warning"><?php  echo form_error('lname'); ?> </div>
                            </li>
                            <li>* E-Mail:<br /> 
                               <input type="text" name="email2" value="<?php echo($this->input->post('email2') != '' ? $this->input->post('email2') : 'E-mail Address') ?>" class="clearField"/>
                             <div class="warning"><?php echo form_error('email2'); ?> </div>
                            </li>
                            
                            <li>Phone Number:<br />  
                               <input type="text" name="phone" value="<?php echo($this->input->post('phone') != '' ? $this->input->post('phone') : '') ?>" class="clearField"/>
                            </li>
                            <li>
                                Password:<br /> 
                                <input id="password-password1" type="password" name="pwd" value="" autocomplete="off"/>
                                <div class="warning"><?php  echo form_error('pwd');?> </div>
                            </li>
                            <li>
                                Confirm Password:<br /> 
                                <input id="password-password2" type="password" name="conf_pwd" value="" autocomplete="off"/>
                            </li>
                             <li>
                                Skype:<br /> 
                                <input id="skype" type="name" name="skype" value="<?php echo($this->input->post('skype') != '' ? $this->input->post('skype') : '') ?>" autocomplete="off"/>
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
                            <li>
                                How did you find us?<br />
                                <input type="text" value="<?php echo($this->input->post('how_did_you_find_us') != '' ? $this->input->post('how_did_you_find_us') : '') ?>" autocomplete="off" name="how_did_you_find_us" />
                            </li>
                            <li>
                                Notes<br />
                                <textarea name="notes_cust_viewable" style="width: 500px; height: 200px;"><?php echo($this->input->post('notes_cust_viewable') != '' ? $this->input->post('notes_cust_viewable') : '') ?></textarea>
                                <div class="warning"><?php  echo form_error('notes_cust_viewable');?> </div>
                                
                            </li>
                            <li>
                                Notes: Only Staff can see<br />
                                <textarea name="notes_hidden_from_cust" style="width: 500px; height: 200px;"><?php echo($this->input->post('notes_hidden_from_cust') != '' ? $this->input->post('notes_hidden_from_cust') : '') ?></textarea>
                                <div class="warning"><?php  echo form_error('notes_hidden_from_cust');?> </div>
                                
                            </li>
                            
                            <li>
                                <p>By clicking Sign Up, I have agreed to your <a class="termsAndCond" href="#termsAndCond">Terms and Conditions</a></p>
                            </li>
                            <li><input type="submit" name="reg" value="Sign up"/></li>
                        </ul>
                    </form>
                </div>
            </div>
        </div>
                    
                    
                    
                </div>
            </div>
             
        <div class="clr"></div>
    </div>
</div>