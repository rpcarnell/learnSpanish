<?php
 
if (!isset($recu->{'recurring-Profile-ID'}) || !is_numeric($recu->{'recurring-Profile-ID'} ) ) { echo "Data is not available"; exit; }
?><div class="spanish">
    <div class="spanish-content profile">
        
        <?php
        if ($msg != '')
        {
            echo "<span style='color: #0a0;'>$msg</span>";
        }
        ?>
        <br />
        <form method="POST">
<div style="margin-top: 10px;">
    <p>Minutes per Week  <input type="text" name='minutes-per-week' style='width: 70px;' value='<?php echo $recu->{'minutes-per-week'};?>' />  -  Monthly Cost is: $###</p>
    <p>Length in Months  <input type="text" name='lenghts-months' style='width: 70px;' value='<?php echo ( $recu->{'lenghts-months'} == 7777 ) ? 0 : $recu->{'lenghts-months'};?>' />  -  Number of months, including this month, to continue billing. 0 = infinity.</p>
    <br />
    <?php
    if ($recu->{'pause-start-date'} == '0000-00-00 00:00:00') { $recu->{'pause-start-date'} = ''; }
    
    if ($recu->{'pause-start-date'} != '')
             {
                 $date_sd = explode(' ',$recu->{'pause-start-date'});
                 $date_sd = explode('-',$date_sd[0]);
                 $date_sd = $date_sd[2]."/".$date_sd[1]."/".$date_sd[0];
             }
             else $date_sd = '';
    
    ?>
    <p>Date in future to Begin a Pause <input type="text" id="datepicker" name='pause-start-date' value='<?php echo $date_sd; ?>' /></p>
    <p>Length of Future Pause (days) <input type="text"  id='pause-length-days' name='pause-length-days' onChange='pauseNum()' value='<?php echo ($recu->{'pause-length-days'}) ?  ( (7777 == $recu->{'pause-length-days'}) ? 'Indefinite' : $recu->{'pause-length-days'}) : '';?>' />
    <span id='pauseWarn' style='font-size: 11px; color: #a00;'></span>
    </p>
   <?php if ( $recu->{'pause-length-days'} != 7777) { ?><br />
    <div style='padding-left: 0px; float: left; '><input type='button' id='pausInd' onClick='pauseIndef(this)' value='Pause Now Indefinitely' style='background: #aaa; color: #fff; padding: 10px; font-size: 12px;' /></div>
    <div style='padding-left: 5px; float: left; '>Note: After <?php echo $pauseDays;?> days, your reservation time will be released for other students to use.</div>
     <div style='clear: both;'></div>
    <br />
    <?php } 
     else { ?>
    <div style='padding-left: 0px; float: left; '><input type='button' id='pausInd' onClick='pauseIndef(this)' value='Unpause Subscription' style='background: #aaaa; color: #fff; padding: 10px; font-size: 12px;' /></div>
    <div style='clear: both;'></div>
    <br />
    <?php } 
    ?>
                 
     <input type='submit' value='Save Changes' style='background: #285c98; color: #fff; padding: 10px 15px; font-size: 12px;' />
  
     <br /><hr /> 
    <a href="<?php echo base_url();?>calendar/appointments?type=2&id=<?php echo $recu->{'recurring-Profile-ID'}; ?>" style='width: 180px; text-align: center; font-size: 14px;display: block; text-decoration: none; margin: 10px; margin-left: 0px; background: #aaa; color: #fff; padding: 10px; '>Edit Times on Calendar</a>
    <div style="clear: both;"></div>
     
    <input type='button' onClick='deletsubs()' value='Delete Subscription' style='width: 200px; background: #aaa; color: #fff; padding: 10px; font-size: 12px;' />
    <div id='deletsubs' style='display: none;'><p>Are you sure? This is not reversible.<br /><a href='javascript:void(0)' onClick='deletsbYES(<?php echo $recu->{'recurring-Profile-ID'}; ?>, <?php echo $customer_ID; ?>, 1)'>Yes</a> | <a href='javascript:void(0)'  onClick='deletsbNO()'>No</a></p></div>
   <input type='hidden' value='1' name='managesubscr' />
   <input type='hidden' value='<?php echo $customer_ID;?>' name='customer_ID' />
   <input type='hidden' value='<?php echo $item_ID;?>' name='item_ID' />
   
   
</div>
        </form>
    </div></div>

<script>
    $(function() {
    jQuery( "#datepicker" ).datepicker();
});
function pauseNum()
{
    var pauseTime = jQuery('#pause-length-days').val();
    if (isNaN(pauseTime) && pauseTime != 'Indefinite') 
    {
        jQuery('#pause-length-days').val(''); 
        jQuery('#pauseWarn').html('Value is not a number'); 
    }
    var request = $.ajax({url: "<?php echo base_url('calendar/pauseLimit') ?>",type: "POST", data: {pauseTime: pauseTime}, 
       success: function(msg) { 
           if (msg == 'Fine') {
               jQuery('#pauseWarn').html('');
               jQuery('#pausInd').val('Unpause Subscription');
           }  
           else jQuery('#pauseWarn').html(msg); } });
    request.fail(function(jqXHR, textStatus){ });
}
function pauseIndef($this)
{
    var nowis = jQuery($this).val();
    if (nowis == 'Pause Now Indefinitely')
    { 
        jQuery($this).val('Unpause Subscription');
        jQuery('#pause-length-days').val('Indefinite');
    } 
    else { 
        jQuery($this).val('Pause Now Indefinitely');
        jQuery('#pause-length-days').val('');
    }
}
function deletsubs() { jQuery('#deletsubs').slideDown(); }
function deletsbNO() { jQuery('#deletsubs').slideUp(); }
function deletsbYES(recurrentID, customer_ID, type)
{
     if (isNaN(customer_ID) || isNaN(customer_ID)) return;
     var request = $.ajax({url: "<?php echo base_url('profile/delSubs') ?>",type: "POST", data: {type: type, recurrentID: recurrentID, customer_ID: customer_ID}, success: function(msg){ 
            if (msg.trim() == '') { window.location.href='<?php echo base_url();?>profile/managSubscr'; }
            else alert(msg); }  });
                        request.fail(function(jqXHR, textStatus){
                            apprise('Something went wrong. Please try again later.', {'animate': true});
                        });
}
</script>
