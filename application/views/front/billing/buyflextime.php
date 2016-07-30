<div class="spanish">
     <div class="home-main-content"><?php echo $breadcrumbs; ?></div> 
<div class="spanish-content profile">
   
    <?php
    if ($error)  {   echo "<div style='color: #a00;'>$error</div>"; }
    ?>
       <br /><form method="post" onSubmit='return checkMinQuant()'><?php
if (isset($tutor))
{ 
		echo "<div class='teacher-box'>";
                 echo "<img class='tutorpic' src='".base_url()."uploads/tutors/".$tutor->photo."' style='float: left; margin: 5px; height: 150px;' />";
		//echo "<div style='float: right; margin: 30px 0x;'><a href='".base_url()."tutors/apply/".$tutor->tutor_ID."'>Apply</a><br /><a href='javascript:void(0)' onClick='showOpenings()'>View Openings</a></div>";
		echo "<div><p>Teacher Name: <b>".ucwords($tutor->name)."</b></p></div>";
		
		echo "<div><p>".nl2br(substr($tutor->bio, 0,300))."...</p></div>";
                         ?>
           <a target='_blank' href='<?php echo base_url()."calendar/tutorSchedule/".$tutor->tutor_ID;?>' style="text-decoration: none;">View Openings</a>
           <?php
                        
                        echo "<div style='clear: both;'></div>";
               
		echo "</div><div style='clear: both; margin-bottom: 0px;'></div>";
	
}
?>
     <br />
        <div>
            <h2>Enter Flex <span class='timeType'>Hours</span> to Purchase</h2>  
            <!--<label>Enter <span class='timeType'>Hours</span> to Purchase</label><br />--><input type='text' style='width: 300px;' onKeyup='getNewHours()' id='userenterhours' />
            <select onChange='changeHours()' id='getNewSel'><option value='1'>Hours</option><option value='0'>Minutes</option></select> 
            <br /><div style="color: #a00;" id="quantwarn"></div>
            <p>Your Total is: $<span id="flexPriceRes">0.00</span></p>
            <table><tr>
                    <!--<td><input type='button' value='Calcula Cost' onClick='enterFlexHours()' style='border: 1px solid #000;' /></td>--><td>
                 <input type='hidden' style='width: 300px;' name='enterhours' id='enterhours' />
                        <input type='submit' /></td>
                </tr></table>
            <!--<p>Pricing</p>
            <ul><li>Less than 5 Hours: 00.00 / hr</li></ul>-->
        </div>
    <?php  if (isset($tutor)) { ?><input type="hidden" name="tutor_id" value="<?php echo $tutor->tutor_ID;?>" /><?php } ?>
            <input type="hidden" name="customer_id" value="<?php echo $customer_ID;?>" />
            <input type="hidden" name="buyingflex" value="1" />
            <input type='hidden'  name='flexcost' id='flexcost' value='0' />
            <div id='quantwarn' style='color: #a00;'></div>
       </form>
        </div>
    </div>

<script>
    function checkMinQuant()
    {
        var flex = jQuery('#flexcost').val();
        if (isNaN(flex) || flex == 0)
        { jQuery('#quantwarn').html('Insufficient Data'); return false; }
        else return true;
    }
        function getNewHours()
    {
        var userHours = jQuery('#userenterhours').val();
        var getSel = jQuery('#getNewSel').val();
        if (getSel > 0) { getSel = 60; }
        else {  getSel = 1; }
        if (isNaN(userHours)) { jQuery('#enterhours').val(''); jQuery('#flexcost').val('0'); return; }
        jQuery('#enterhours').val(getSel * userHours);
        enterFlexHours();
    }
      function changeHours()
    {
        jQuery('#enterhours').val('');
        jQuery('#userenterhours').val('');
        var getSel = jQuery('#getNewSel').val();
        if (getSel > 0) { jQuery('.timeType').html('Hours'); }
        else { jQuery('.timeType').html('Minutes'); }
        jQuery('#flexPriceRes').html('0.00');
        jQuery('#flexcost').val('0');
    }   
    function enterFlexHours()
    {
        var quant = jQuery('#enterhours').val();
        var quantSel = jQuery('#getNewSel').val();
        if (isNaN(quant)){ jQuery('#quantwarn').html('non-numeric data'); return; }
        else jQuery('#quantwarn').html('');
        jQuery.post('<?php echo base_url(); ?>billing/getFlexPrice', {quantSel: quantSel, quant: quant}, function(data) { data = JSON.parse(data); 
            jQuery('#flexPriceRes').html(data[0]); jQuery('#flexcost').val(data[0]);  } );
         
    }
</script>