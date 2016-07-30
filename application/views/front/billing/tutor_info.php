<div class="spanish">
   <br />
         <div class="home-main-content"><?php echo $breadcrumbs; ?></div> 
        <br />
<div class="spanish-content profile">
       <div style="width: 25%; float: right"> 
        <a href="javascript:void(0)" oncliCk="sessionMin(2, 40)" class="tutorinfo">2 Sessions 40 min each per week</a>
        <a href="javascript:void(0)" oncliCk="sessionMin(3, 40)" class="tutorinfo">3 Sessions 40 min each per week</a>
        <a href="javascript:void(0)" oncliCk="sessionMin(4, 40)" class="tutorinfo">4 Sessions 40 min each per week</a>
     <a href="javascript:void(0)" oncliCk="sessionMin(5, 40)" class="tutorinfo">5 Sessions 40 min each per week</a>
     <a href="javascript:void(0)" oncliCk="sessionMin(2, 55)" class="tutorinfo">2 Sessions 55 min each per week</a>
     <a href="javascript:void(0)" oncliCk="sessionMin(3, 55)" class="tutorinfo">3 Sessions 55 min each per week</a>
     <a href="javascript:void(0)" oncliCk="sessionMin(4, 55)" class="tutorinfo">4 Sessions 55 min each per week</a>
     <a href="javascript:void(0)" oncliCk="sessionMin(5, 55)" class="tutorinfo">5 Sessions 55 min each per week</a>
    
    </div>
        <div class="profile-info-top fr">
           
<?php
if (isset($tutor))
{ 
		echo "<div class='teacher-box' style='width: 100%; height: 200px;'>";
                echo "<img class='tutorpic' src='".base_url()."uploads/tutors/".$tutor->photo."' style='float: left; margin: 5px; height: 150px; max-width: 150px; ' />";
		//echo "<div style='float: right; margin: 30px 0x;'><a href='".base_url()."tutors/apply/".$tutor->tutor_ID."'>Apply</a><br /><a href='javascript:void(0)' onClick='showOpenings()'>View Openings</a></div>";
		echo "<div><p>Teacher Name: <b>".ucwords($tutor->name)."</b></p></div>";
		
		echo "<div><p>".nl2br(substr($tutor->bio, 0,300))."...</p></div>";
                echo "<div style='clear: both;'></div>";
		echo "</div>";
	 echo "<div style='clear: left; margin-bottom: 0px;'></div>";
}
if ($error) echo "<br /><div style='color: #a00;'>$error</div>";
?>
    
    <h2>Buy Recurring Minutes per Week</h2>
    <div><form method='post'>
           <!-- <input type="hidden" value="1" name="minutesperweek" />-->
            <?php  if (isset($tutor)) { ?><input type="hidden" name="tutor_id" value="<?php echo $tutor->tutor_ID;?>" /><?php } ?>
            <input type="hidden" name="customer_id" value="<?php echo $customer_ID;?>" />
            <?php if (isset($tutor)) {  ?>
        <h3>How many Minutes per Week with <?php echo ucwords($tutor->name);?>?</h3>
        <?php
             } else { ?>
        <h3>Buy Time</h3>
             <?php } ?>
        <input type="text" style="width: 200px;" name='minutesperweek' id="selperhour" />
            <!--<option></option>
            
            <option value="80">80 min</option>
            <option value="110">110 min</option>
            <option value="120">120 min</option>
            <option value="220">220 min</option>
            <option value="255">255 min</option>
            <option value="340">340 min</option>
             <option value="355">355 min</option>
            <option value="440">440 min</option>
            <option value="455">455 min</option>
            <option value="540">540 min</option>
            <option value="555">555 min</option>            
           
        </select>-->
        <p>Cost per month for your selection: <span id="costpermonth">N/A</span></p>
        <p>For an <b>average</b> month, the hourly cost for this plan is: <span id="costperhour">N/A</span></p>
        <p>(Months vary in length, but an average month has 4.35 weeks)</p>
       <!-- <p><b>Start Date</b><br />
            <input type="text" name="date" id="datepicker" value="<?php echo $this->input->post('date') ?>" style="width: 115px;" id="dt6"/></p>-->
        <p><b>Number of Months to Repeat (blank = infinite)</b>
        <br /><input type='text' name='numberofmonths' value="<?php echo $this->input->post('numberofmonths') ?>" style="width: 115px;" />
        <br />You can always change this, or any other aspect of your subscription later
        </p>
        <input type='submit' value='Submit' />
        <input type="hidden" id="costpermonth_val" value='' />
        </form>
    </div>
        </div></div></div>
<?php 
$CI = &get_instance();
$CI->config->load('braintree', TRUE);
$costPerHour = $CI->config->item('costPerHour');
?>
<script type="text/javascript" src="<?php echo base_url()?>js/jquery-ui-1.8.23.custom.min.js"></script>


<script language="javascript">
$(function() {
    console.log( "ready!" );
    jQuery('#selperhour').on('keyup', function() { costPerMin( jQuery('#selperhour').val() ); } );
    jQuery( "#datepicker" ).datepicker();
});
function costPerMin(minutesperWeek)
{
    //alert(minutes);
   // var minutesValue = <?php echo $costPerHour; ?>;
   /* alert(minutesValue);
    var TableCostPerMinPerWeek  = minutesperMonth / 4.35;
    var costPerMin = parseFloat(minutesValue / 60);
    alert(costPerMin);*/
    
     jQuery.post('<?php echo base_url(); ?>billing/getRecurringPrice', {quant: minutesperWeek}, 
     function(data) { if (! isNaN(data)) {
         var cosPerHour = (60 * data) / 4.34;
         jQuery('#costperhour').html("$" +cosPerHour.toFixed(2)+ "");
         var cosPerMonth = data *  minutesperWeek;//minutesperWeek * (data) * 4.35;
         jQuery('#costpermonth').html("$" + cosPerMonth.toFixed(2)+ "");
         jQuery('#costpermonth_val').val( cosPerMonth.toFixed(2) ); 
     }
    } );
         
   
    
    
}
function sessionMin(session, min)
{
    min = session * min;
    jQuery('#selperhour').val(min);
    costPerMin(min);
}
</script>
