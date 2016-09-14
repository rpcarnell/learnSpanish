 
<div class="spanish">
    <div class="spanish-content profile">
        <?php echo $breadcrumbs; 
        if (isset($recurring)) {
         $theDate = isset($recurring->StartDate) ? $recurring->StartDate : '';
            if ($theDate != '')
            {
                $date = explode(' ',$theDate);
                $date = explode('-',$date[0]);
                $theDate = $date[1]."/".$date[2]."/".$date[0];
            }
        }
        if (isset($flexData))
        {
            
        }
        /* if ($data->item_id == 2) $ofTime = " Recurring Time";
                      elseif ($data->item_id == 1) $ofTime = "Flex Time";
                      else $ofTime = 'Other'; */
        ?> 
        <div class="content-page-view fl" style="width: 950px;margin: auto;"><br />
            <script>$(document).ready(function(){
	
	$('ul.tabs li').click(function(){
		var tab_id = $(this).attr('data-tab');

		$('ul.tabs li').removeClass('current');
		$('.tab-content').removeClass('current');

		$(this).addClass('current');
		$("#"+tab_id).addClass('current');
	})

})</script>
            <div>
 
      <br />
<?php if ($data->item_id == 2) { ?>
        <h2>Recurring Time</h2>
	<div id="tab-1" class="tab-content current">
            <div id='tab_1_warn' class='warning'></div>
            <form method='POST' onSubmit='return recurringSubmit()'>
            <div class="fl label">Description:</div><textarea name='notes' style="width: 400px; height: 100px;"><?php echo nl2br($data->notes);?></textarea>
              <div class="clr"><br /></div> 
            <div class="fl label">Hours per Week:</div><input type="text" name="hoursperweek" value='<?php echo ceil($recurring->{'minutes-per-week'}/60);?>' required />
            <div class="clr"><br /></div> 
            <div class="fl label">Price per month:</div><input type="text" name="price" value="<?php echo $data->price_paid;?>"  required />
              <div class="clr"><br /></div> 
               <div class="fl label">Start Date:</div><input id='startdate' type="text" name="startdate" value='<?php echo $theDate;?>' required />
              <div class="clr"><br /></div> 
              
               <div class="fl label">Number of months (blank = infinite):</div><input type="text" value='<?php echo $recurring->{'lenghts-months'};?>'name="months" required />
              <div class="clr"><br /></div> 
           
              <input type='hidden' value='1' name='e_ecurringform' />
              <input type='hidden' name='tutor_id' class='form_tutor_id' id='form_tutor_i_1' value='<?php echo $data->tutor_id;?>' />
               <input type='hidden' name='customer_id' class='form_customer_id' id='form_cust_i_1' value='<?php echo $data->customer_id;?>' />
               <input type='hidden' name='purchase_id' class='form_customer_id' id='form_cust_i_1' value='<?php echo $data->purchase_id;?>' />
               <input type='hidden' name='profile_id' class='form_customer_id' id='form_cust_i_1' value='<?php echo $recurring->{'recurring-Profile-ID'};?>' />
               
              <input type='submit' value='Edit Invoice' />
            </form>  
        </div>
<?php } elseif ($data->item_id == 1) {   ?> 
        <h2>Flex Time</h2>
	<div id="tab-2" class="tab-content">
             <div id='tab_2_warn' class='warning'></div>
            <form  method='POST' onSubmit='return recurringSubm_2()'> 
	  <div class="fl label">Description:</div><textarea name='notes' style="width: 400px; height: 100px;" required><?php echo nl2br($data->notes);?></textarea>
            <div class="clr"><br /></div> 
            <?php
            $flexDataFlexTime = isset($flexData->FlexTime) ? $flexData->FlexTime : 0;
            ?>
             <div class="fl label">Hours:</div><input value='<?php echo ceil($flexDataFlexTime /60); ?>' type="text" name="hours" required />
              <div class="clr"><br /></div> 
              <div class="fl label">Price:<br /><span style="font-size: 11px;">If 0 time is available immediately</span></div>
              <input type="text" name="price" value="<?php echo $data->price_paid;?>" required />
              <div class="clr"><br /></div> 
              <?php
              $flexDataInventory_ID = isset($flexData->Inventory_ID) ? $flexData->Inventory_ID : 0;
              ?>
               <input type="hidden" name="inventory_id" value="<?php echo $flexDataInventory_ID;?>" />
               <input type='hidden' value='1' name='e_flexform' />
               <?php
             $datatutor_id = isset($data->tutor_id) ? $data->tutor_id : 0;
              $datacustomer_id = isset($data->customer_id) ? $data->customer_id : 0;
              ?>
               <input type='hidden' name='tutor_id' class='form_tutor_id' id='form_tutor_i_2' value='<?php echo $datatutor_id;?>' />
               <input type='hidden' name='customer_id' class='form_customer_id' id='form_cust_i_2' value='<?php echo $datacustomer_id;?>' />
                   <input type='hidden' name='purchase_id' class='form_customer_id' id='form_cust_i_1' value='<?php echo $data->purchase_id;?>' />
              <input type='submit' value='Edit Invoice' />
              </form>
        </div>
<?php } else { ?>   
        <h2>Other</h2>
	<div id="tab-3" class="tab-content">
            <div id='tab_3_warn' class='warning'></div>
            <form  method='POST' onSubmit='return recurringSubm_3()'>
	  <div class="fl label">Description:</div><textarea name='notes' style="width: 400px; height: 100px;" required><?php echo nl2br($data->notes);?></textarea>
            <div class="clr"><br /></div> 
            <div class="fl label">Price:</div><input type="text" name="price" value="<?php echo $data->price_paid;?>"  required />
              <div class="clr"><br /></div> 
             
                <div class="fl label"><input type="checkbox" name="recurring" />Recurring Monthly</div>
              <div class="clr"><br /></div> 
               <input type='hidden' value='1' name='e_otherform' />
               <input type='hidden' name='tutor_id' class='form_tutor_id' id='form_tutor_i_3' value='<?php echo $data->tutor_id;?>' />
               <input type='hidden' name='customer_id' class='form_customer_id' id='form_cust_i_3'  value='<?php echo $data->customer_id;?>' />
                   <input type='hidden' name='purchase_id' class='form_customer_id' id='form_cust_i_1' value='<?php echo $data->purchase_id;?>' />
              <input type='submit' value='Edit Invoice' />
              </form>
        </div>
<?php } 
if ($msg) echo "<div style='margin-top: 4px; color: #0a0;'>$msg</div>";
?>
     
<br />
</div><!-- container -->
            
            
          
         
   
        </div>
        
    </div>

</div>
<script>
    $("#startdate").datepicker({dateFormat: "mm/dd/yy"});
    $("#invoicestart").datepicker({dateFormat: "mm/dd/yy"});
    $("#invoiceend").datepicker({dateFormat: "mm/dd/yy"});
   
     
    </script>