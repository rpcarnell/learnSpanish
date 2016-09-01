<?php
$recently = isset($_GET['rect']) ? $_GET['rect'] : 3;

?>
<div class="spanish">
    <div class="spanish-content profile">
       
        <?php echo $breadcrumbs; ?><br /><br />
       
        <div style="clear: both;"></div>
        
        
        <div style="float: right;">
            <h3>Edit Student Form</h3>
         <form action="<?php echo base_url() ?>tutors/editStudents" method="post">
                        <ul class="signup-table">
                            <li>* Name:<br /> <input type="text" id='studname' name="name" value="<?php echo $this->input->post('name'); ?>" class="clearField"/>
                               
                            </li>
                           
                            <li>* E-Mail:<br /> 
                               <input type="text" name="email" id='studemail' value="<?php echo $this->input->post('email'); ?>" class="clearField"/>
                           
                            </li>
                            
                            <li>Phone Number:<br />  
                               <input type="text" name="phone" id='studphone' value="<?php echo $this->input->post('phone'); ?>" class="clearField"/>
                            
                            </li>
                            
                             <li>
                                Skype:<br /> 
                                <input type="name" name="skype" id='studskype' value="<?php echo $this->input->post('skype'); ?>" autocomplete="off"/>
                            </li>
                             <li>* Where in the world are you?<br />(help us ensure your timezone is correct):<br /> 
                              <select id="studtimezone" name="time_zone">
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
                                
                            </li>
                           <li><div style="padding: 5px; font-size: 11px;">Leave these fields empty if you don't want to change your password</div>
                                Password:<br /> 
                                <input id="password-password1" type="password" name="pwd" value="" autocomplete="off"/>
                             
                            </li>
                            <li>
                                Confirm Password:<br /> 
                                <input id="password-password2" type="password" name="conf_pwd" value="" autocomplete="off"/>
                            </li>
                          <li><?php
                          if ($result == 0)
                          {
                              echo "<div style='color: #a00;'>There has been an error - some fields may be incomplete</div>";
                          }
                          elseif ($result == 2)
                          {
                              echo "<div style='color: #0a0;'>You just added a user, but the user is not linked to you yet</div>";
                          }
                          ?><br /><input type="submit" name="reg" value="Edit Probile"/></li>
                        </ul>
                        <input type='hidden' name='customer_id' id="studtomer_id" value='<?php echo $this->input->post('customer_id'); ?>' />
                        <input type='hidden' name='profile_edit' value='1' />
                    </form>
    </div>
        
        
        
        <div style="float: left; width: 50%"><p>
        <select id='orderbills' onChange='orderBills()'>
            <option value="0" <?php if ($order == 0) echo "selected"; ?>>Have ever Purchased from you</option>
            <option value="1" <?php if ($order == 1) echo "selected"; ?>>Have Purchased Recently</option>
            <option value="2" <?php if ($order == 2) echo "selected"; ?>>Signed Up But Never Purchased</option>
            
        </select>
            </p>
      
        <?php
        if ($order == 1) $visible = 'visible';
        else $visible = 'hidden';
        ?>
        <div style="float: right; visibility: <?php echo $visible;?>; margin-top: 0px;" id="recentlyDiv"><p>Define <b>recently</b> as:</p><p>In the last <select id='recently' onChange='orderBills_2()'>
            <option value="3" <?php if ($recently == 3) echo "selected"; ?>>3</option>
            <option value="2" <?php if ($recently == 2) echo "selected"; ?>>2</option>
            <option value="1" <?php if ($recently == 1) echo "selected"; ?>>1</option>
        </select> months</p></div>
        <br />
        <div>Student Name: <input type="text" onKeyUp="studentSearch(false)" id="studentSearch" /></div>
        <div style="margin: 10px;" id="teacherstudents">
  <?php
  if (isset($students) && is_array($students)) {  
  if (is_array($students))
  { echo "<table border='0' cellpadding='5' cellspacing='5'><tr><th>Student</th></tr>";//<th>Phone</th></tr>";
  foreach ($students as $student)
  {
       
      
       echo "<tr><td><a href='javascript:void(0)' onClick='studentSelected(".$student->Customer_ID.", ".$tutor_id.")'>".$student->name."</a></td></tr>";
       //<td>$student->email_list</a></td></tr>";//<td>".$student->phone."</td></tr>";
  }
  echo "</table>";
  }
  ?>
        </div><div id='refreshDiv'><a href='javascript:void(0)' onClick='refresh()'>Refresh</a></div>
        <br /><br />
        <?php
        } else echo "<p><b>There are no students</b></p>";
        ?>
          </div>
        
        
        
    </div></div>

<div style="clear: both;"></div>
<script>
<?php if ($this->input->post('time_zone')) { ?>
    $(document).ready(function() { jQuery('#studtimezone').val('<?php echo $this->input->post('time_zone'); ?>'); });
<?php } ?>
function refresh()
{
    jQuery('#refreshDiv').html('<img style="height: 60px;" src=\'<?php echo base_url()."images/circle-loading-gif.gif";?>\' alt="loading" />').css({'color' : '#a00'});
    setTimeout(function() {
        studentSearch(true);
        jQuery('#refreshDiv').css({'color' : '#000'}).html("<a href='javascript:void(0)' onClick='refresh()'>Refresh</a>");  
    }, 3000);
}
function orderBills()
{
     var order = jQuery('#orderbills').val();
    if (parseInt(order) == 1) { jQuery('#recentlyDiv').css({ 'visibility' : 'visible'}); }
    else { jQuery('#recentlyDiv').css({ 'visibility' : 'hidden'}); }
    studentSearch(true);
}
function orderBills_2()
{
     studentSearch(true);
}
function studentSearch(noword)
{
    var student = jQuery('#studentSearch').val();
    var order = jQuery('#orderbills').val();
    var recently = jQuery('#recently').val();
    
    if (noword || student.length > 2)
    {
    
       var request = $.ajax({
                             url    : "<?php echo base_url('tutors/ajaxstuedints') ?>",
                             type   : "GET",
                             data   : { stustring: student, order: order, rect: recently, tutorid: <?php echo $this->session->userdata('tutorid');?> },
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
function studentSelected(student_id, tutor_id)
{
      var request = $.ajax({
                             url    : "<?php echo base_url('tutors/AjaxstudEdit') ?>",
                             type   : "POST",
                             data   : { student_id: student_id, tutor_id: tutor_id },
                             success: function(msg){ 
                                 var data = JSON.parse(msg);
                                 if (msg != 0) {
                                 
                                 jQuery('#studname').val(data.name);
                                 jQuery('#studemail').val(data.email_list);
                                 jQuery('#studphone').val(data.phone);
                                 jQuery('#studskype').val(data.skype);
                                  jQuery('#studtimezone').val(data.time_zone);
                                  jQuery('#studtomer_id').val(student_id);
                              }
                              else
                              {   
                                   jQuery('#studname').val('');
                                 jQuery('#studemail').val('');
                                 jQuery('#studphone').val('');
                                 jQuery('#studskype').val('');
                              }
                          }
                         });
                        request.fail(function(jqXHR, textStatus){
                            apprise('Something went wrong. Please try again later.', {'animate': true});
          });
}
</script>