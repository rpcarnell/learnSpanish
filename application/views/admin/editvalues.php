

  
       <div id="main-wrapper">
    <div class="clr"></div>
    <div class="content-wrapper">
            <?php echo $breadcrumbs; ?><br /><br />
        <div style="clear: both;"></div>
        <div style="float: left;">
        </div>
       
        
        <div style="clear: both;"></div>
        
        <div style="margin: 20px;">
            <form method="post">
            <div style='width: 90%;'><span style='float: left;'>Closest Scheduling Allowed</span>
                <span style='float: right;'><input type='text' name='nearest-hours-to-change' value="<?php echo ($this->input->post('nearest-hours-to-change')) ? $this->input->post('nearest-hours-to-change') : $globals->{'nearest-hours-to-change'}; ?>" style='width: 80px' /> Hours from now</span>
            </div>
            <div style='clear: both;'></div>
             <div style='width: 90%; margin-top: 20px;'><span style='float: left;'>Farthest in the Future Allowed</span>
                <span style='float: right;'><input type='text' name='future-booking-limit' value="<?php echo ($this->input->post('future-booking-limit')) ? $this->input->post('future-booking-limit') : $globals->{'future-booking-limit'}; ?>" style='width: 80px' /> Months from now</span>
            </div>
            
             <div style='clear: both;'></div>
             <div style='width: 90%; margin-top: 20px;'><span style='float: left;'>Store Name</span>
                <span style='float: right;'><input type='text' name='store-name' value="<?php echo ($this->input->post('store-name')) ? $this->input->post('store-name') : $globals->{'store-name'}; ?>" placeholder='Spanish for good' style='width: 200px;' /></span>
            </div>
             
               <div style='clear: both;'></div>
             <div style='width: 90%; margin-top: 20px;'><span style='float: left;'>Pictures and Video Location</span>
                <span style='float: right;'><input type='text' name='pictures-location-path' value="<?php echo ($this->input->post('pictures-location-path')) ? $this->input->post('pictures-location-path') : $globals->{'pictures-location-path'}; ?>" placeholder='Uploads' style='width: 200px;' /></span>
            </div>
               
                <div style='clear: both;'></div>
             <div style='width: 90%; margin-top: 20px;'><span style='float: left;'>Max Days can Pause Subscription</span>
                
                 <span style='float: right;'><input type='text' value="<?php echo ($this->input->post('max-pause-days')) ? $this->input->post('max-pause-days') : $globals->{'max-pause-days'}; ?>" style='width: 80px' /></span>
                  <div style='clear: both;'></div>
                 <span style='font-size: 11px;'></span>
            </div>
                
                <div style='clear: both;'></div>
                
                
             <div style='width: 90%; margin-top: 20px;'><span style='float: left;'>Delay Before Change Notice</span>
                 <span style='float: right;'><input type='text' name='delay-before-change-notice' value="<?php echo ($this->input->post('delay-before-change-notice')) ? $this->input->post('delay-before-change-notice') : ($globals->{'delay-before-change-notice'}); ?>" style='width: 80px' /> </span>
                        <div style='clear: both;'></div>
                 <span style='font-size: 11px;'>
                    Number of Minutes after calendar changes to wait before sending e-mail. Reduces Excessive Email</span>
            </div>
                
                 <div style='clear: both;'></div>
             <div style='width: 90%; margin-top: 20px;'><span style='float: left;'>Makeup Allowance</span>
                  
                 <span style='float: right;'><input type='text' name='makeup-allowance' value="<?php echo ($this->input->post('makeup-allowance')) ? $this->input->post('makeup-allowance') : ($globals->{'makeup-allowance'}); ?>" style='width: 80px' /> </span>
                        <div style='clear: both;'></div>
                 <span style='font-size: 11px;'>
                   % of weekly recurring to give as possible makeup time usable over the month then - balance does not build month after month</span>
            </div>
                 
                 <div style='clear: both;'></div>
             <div style='width: 90%; margin-top: 20px;'><span style='float: left;'>Time-Zone</span>
                  
                 <span style='float: right;'>
                     <select id="time-zone" name="time-zone">
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
                 </span>
                     <div style='clear: both;'></div>
                 <span style='font-size: 11px;'></span>
            </div>
                 
                 
                 <div style='width: 90%; margin-top: 20px;'><span style='float: left;'>Run "Cron every"</span>
                      
                 <span style='float: right;'><input type='text' style='width: 80px' name='chron-job-seconds' value="<?php echo ($this->input->post('chron-job-seconds')) ? $this->input->post('chron-job-seconds') : ($globals->{'chron-job-seconds'}/60); ?>" /> minutes</span>
                        <div style='clear: both;'></div>
                  
            </div>
                 <br /> <br />
                 <?php
                 if ($error)
                 {
                     echo "<div style='margin: 5px; color: #a00;'>ERROR - $error</div>";
                 }
                 ?>
            <input type="submit" value="Submit" />
        </form>
        </div> 
        
    </div></div>
<script language="javascript">
jQuery(function() {  
            jQuery('#time-zone').val('<?php echo $globals->{'time-zone'}; ?>');
});

function refresh()
{
    jQuery('#refreshDiv').html('<img style="height: 60px;" src=\'<?php echo base_url()."images/circle-loading-gif.gif";?>\' alt="loading" />').css({'color' : '#a00'});
    setTimeout(function() {
        studentSearch(true);
        jQuery('#refreshDiv').css({'color' : '#000'}).html("<a href='javascript:void(0)' onClick='refresh()'>Refresh</a>");  
    }, 3000);
   // studentSearch(true);
}
function orderBills()
{
     var order = jQuery('#orderbills').val();
    if (parseInt(order) == 1) { jQuery('#recentlyDiv').css({ 'visibility' : 'visible'}); }
    else { jQuery('#recentlyDiv').css({ 'visibility' : 'hidden'}); }
    studentSearch(true);
    
   /* var order = jQuery('#orderbills').val();
    var recently = jQuery('#recently').val();
    window.location.href = "<?php echo base_url();?>tutors/studentemails?rect="+recently+"&order=" + order;*/
}
function orderBills_2()
{
     studentSearch(true);
    /*var order = 1;
    var recently = jQuery('#recently').val();
    window.location.href = "<?php echo base_url();?>tutors/studentemails?rect="+recently+"&order=" + order;*/
}
function studentSearch(noword)
{
    var student = jQuery('#studentSearch').val();
    var order = jQuery('#orderbills').val();
    var recently = jQuery('#recently').val();
    if (noword || student.length > 2)
    {
       var request = $.ajax({
                             url    : "<?php echo base_url('admin_base/ajaxstudents') ?>",
                             type   : "GET",
                             data   : { stustring: student, order: order, rect: recently },
                             success: function(msg){  
                                 if (msg != 0) jQuery('#teacherstudents').html(msg);
                                 else jQuery('#teacherstudents').html("No students to show");
                             }
                         });
                        request.fail(function(jqXHR, textStatus){
                            apprise('Something went wrong. Please try again later.', {'animate': true});
          });
    }    
}
</script>