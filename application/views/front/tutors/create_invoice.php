<style>
    
		.container{
			width: 800px;
			margin: 0;
		}



		ul.tabs{
			margin: 0px;
			padding: 0px;
			list-style: none;
		}
		ul.tabs li{
			background: none;
			color: #222;
			display: inline-block;
			padding: 10px 15px;
			cursor: pointer;
		}

		ul.tabs li.current{
			background: #ededed;
			color: #222;
		}

		.tab-content{
			display: none;
			background: #ededed;
			padding: 15px;
		}

		.tab-content.current{
			display: inherit;
		}
</style>
<div class="spanish">
    <div class="spanish-content profile">
        <?php echo $breadcrumbs; ?><br /><br />
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
            <div class="container">
 
         
            <div style='clear: both;'></div>
                         <div style='width: 95%; margin-top: 10px;'>
             
                <div style='width: 30%; height: 110px; float: left; margin-left: 20px;'><b>For  Students:</b>
                    <div  style='border: 1px solid #aaa; padding: 5px; max-height: 100px; overflow-y: auto;'>
                        <?php
                        foreach ($customers as $t)
                        {
                            echo "<p><a style='text-decoration: none;' href='javascript:void(0)' onClick='studentTutors(".$t->Customer_ID.", \"".str_replace("'", '', $t->name)."\",  \"".ucwords($tutor->name)."\")'>".$t->name."</a></p>";
                        }
                        ?>
                    </div>
                </div>
                 <div style='width: 30%; height: 110px; float: left; margin-left: 20px;'><b>For Tutors:</b>
                    <div style='border: 1px solid #aaa; padding: 5px; max-height: 100px; overflow-y: auto;' id='studentsTutors'>
                        <p><?php echo ucwords($tutor->name);?></p>
                     </div>
                </div>
                             <!--
                              <div style='width: 30%; height: 110px; float: left; margin-left: 20px;'><br />
                                  <div class="fl label"><input type="radio" value='2' onclick="timeStudentT()" name="bookwithlast" class='bookwithlast' />In the last three months</div>
                    <div class="clr"><br /></div> 
                    <div class="fl label"><input type="radio" value='1' onclick="timeStudentT()" name="bookwithlast" class='bookwithlast' />In the last year</div>
                    <div class="clr"><br /></div> 
                      <div class="fl label"><input type="radio" value='0' onclick="timeStudentT()" name="bookwithlast" class='bookwithlast' checked />Have ever booked</div>
                </div>-->
                             
            </div>
            <div style='clear: both;'></div>  <br /><br />
            
            
          
                <div id='adminSpecs' style='width: 700px; margin-top: 50px;padding: 5px;'>
                  
            </div>
            
                <div style='width: 700px; margin-top: 20px; border: 1px solid #aaa; padding: 5px; max-height: 200px; overflow-y: auto;'>
                    <div id='teacherstudents'><p>No student chosen yet</p></div>
                    <div id='studentTutor'><p><?php echo "<b>Tutor:</b> ".ucwords($tutor->name);?></p></div>
            </div>
        <br />
                
                
	<ul class="tabs">
		<li class="tab-link current" data-tab="tab-1">Recurring Invoice</li>
		<li class="tab-link" data-tab="tab-2">Flex Time Invoice</li>
		<li class="tab-link" data-tab="tab-3">Other Invoice (no hours)</li>
		 
	</ul>

	<div id="tab-1" class="tab-content current">
            <div id='tab_1_warn' class='warning'></div>
            <form method='POST' onSubmit='return recurringSubmit()'>
            <div class="fl label">Description:</div><textarea name='notes' style="width: 400px; height: 100px;"></textarea>
              <div class="clr"><br /></div> 
            <div class="fl label">Hours per Week:</div><input type="text" name="hoursperweek" required />
            <div class="clr"><br /></div> 
            <div class="fl label">Price per month:</div><input type="text" name="price" required />
              <div class="clr"><br /></div> 
               <div class="fl label">Start Date:</div><input id='startdate' type="text" name="startdate" required />
              <div class="clr"><br /></div> 
              
               <div class="fl label">Number of months (blank = infinite):</div><input type="text" name="months" required />
              <div class="clr"><br /></div> 
              
              <div class="fl label"><input type="checkbox" name="emailinvoice" />E-mail Invoice</div>
              <div class="clr"><br /></div> 
              <input type='hidden' value='1' name='recurringform' />
              <input type='hidden' name='tutor_id' class='form_tutor_id' id='form_tutor_i_1' value='<?php print_r($tutor->tutor_ID);?>' />
               <input type='hidden' name='customer_id' class='form_customer_id' id='form_cust_i_1' value='' />
              <input type='submit' value='Create Invoice' />
            </form>  
        </div>
              
	<div id="tab-2" class="tab-content">
             <div id='tab_2_warn' class='warning'></div>
            <form  method='POST' onSubmit='return recurringSubm_2()'> 
	  <div class="fl label">Description:</div><textarea name='notes' style="width: 400px; height: 100px;" required></textarea>
            <div class="clr"><br /></div> 
             <div class="fl label">Hours:</div><input type="text" name="hours" required />
              <div class="clr"><br /></div> 
              <div class="fl label">Price:<br /><span style="font-size: 11px;">If 0 time is available immediately</span></div><input type="text" name="price" required />
              <div class="clr"><br /></div> 
              
               <div class="fl label"><input type="checkbox" name="emailinvoice"  />E-mail Invoice</div>
              <div class="clr"><br /></div> 
               <input type='hidden' value='1' name='flexform' />
               <input type='hidden' name='tutor_id' class='form_tutor_id' id='form_tutor_i_2' value='<?php print_r($tutor->tutor_ID);?>' />
               <input type='hidden' name='customer_id' class='form_customer_id' id='form_cust_i_2' value='' />
              <input type='submit' value='Create Invoice' />
              </form>
        </div>
                
	<div id="tab-3" class="tab-content">
            <div id='tab_3_warn' class='warning'></div>
            <form  method='POST' onSubmit='return recurringSubm_3()'>
	  <div class="fl label">Description:</div><textarea name='notes' style="width: 400px; height: 100px;" required></textarea>
            <div class="clr"><br /></div> 
            <div class="fl label">Price:</div><input type="text" name="price" required />
              <div class="clr"><br /></div> 
               <div class="fl label"><input type="checkbox" name="emailinvoice" />E-mail Invoice</div>
                <div class="fl label"><input type="checkbox" name="recurring" />Recurring Monthly</div>
              <div class="clr"><br /></div> 
               <input type='hidden' value='1' name='otherform' />
               <input type='hidden' name='tutor_id' class='form_tutor_id' id='form_tutor_i_3' value='<?php print_r($tutor->tutor_ID);?>' />
               <input type='hidden' name='customer_id' class='form_customer_id' id='form_cust_i_3'  value='' />
              <input type='submit' value='Create Invoice' />
              </form>
        </div>
	 
<br />
</div><!-- container -->
            
            
          
         
   
        </div>
        
    </div>

</div>
<script>
    $("#startdate").datepicker({dateFormat: "mm/dd/yy"});
    $("#invoicestart").datepicker({dateFormat: "mm/dd/yy"});
    $("#invoiceend").datepicker({dateFormat: "mm/dd/yy"});
    /*function timeStudentT()
    {
        var studentN = jQuery('#teacherstudents').data('studentName');
        var studentID = jQuery('#teacherstudents').data('studentID');
        
        if (typeof(studentN) === 'undefined' || typeof(studentID) === 'undefined') return;
        else jQuery('#studentsTutors').html('<img src="<?php echo base_url()."images/circle-loading-gif.gif";?>" alt="loading" />');
        setTimeout(function() { studentTutors(studentID,  studentN, '<?php echo str_replace('\'', '\\\'', json_encode($tutor));?>'); }, 2000);
    }*/
    function recurringSubmit()
    {
        var warn = '';
        var cust_i_1 = jQuery('#form_cust_i_1').val();
        var tutor_i_1 = jQuery('#form_tutor_i_1').val();
        if (tutor_i_1.trim() == '' || isNaN(tutor_i_1))
        {
            warn += "<p>No tutor has been chosen</p>";
        }
        if (cust_i_1.trim() == '' || isNaN(cust_i_1))
        {
            warn += "<p>No customer has been chosen</p>";
        }
        if (warn == '') return true;
        jQuery('#tab_1_warn').html(warn);
        return false;
    }
    function recurringSubm_2()
    {
        var warn = '';
        var cust_i_2 = jQuery('#form_cust_i_2').val();
        var tutor_i_2 = jQuery('#form_tutor_i_2').val();
        if (tutor_i_2.trim() == '' || isNaN(tutor_i_2))
        {
            warn += "<p>No tutor has been chosen</p>";
        }
        if (cust_i_2.trim() == '' || isNaN(cust_i_2))
        {
            warn += "<p>No customer has been chosen</p>";
        }
        if (warn == '') return true;
        jQuery('#tab_2_warn').html(warn);
        return false;
    }
    function recurringSubm_3()
    {
        var warn = '';
        var cust_i_3 = jQuery('#form_cust_i_3').val();
        var tutor_i_3 = jQuery('#form_tutor_i_3').val();
        if (tutor_i_3.trim() == '' || isNaN(tutor_i_3))
        {
            warn += "<p>No tutor has been chosen</p>";
        }
        if (cust_i_3.trim() == '' || isNaN(cust_i_3))
        {
            warn += "<p>No customer has been chosen</p>";
        }
        if (warn == '') return true;
        jQuery('#tab_3_warn').html(warn);
        return false;
    }
    function tutorstudent(id, tutor)
    {
         
        jQuery('#studentTutor').html("<p><b>Tutor:</b> " + tutor + "</p>");
        jQuery('.form_tutor_id').val(id);
    }
    function studentTutors(id, student, tutor)
    {  
        var selected = 0;
        jQuery('.bookwithlast ').each(function() { if (jQuery(this).is(':checked')) selected = jQuery(this).val(); })
        jQuery('#teacherstudents').html("<p><b>Student:</b> " + student + "</p>");
        jQuery('#teacherstudents').data('studentName', student);
        jQuery('#teacherstudents').data('studentID', id);
        jQuery('.form_customer_id').val(id);
       //jQuery('#studentTutor').html("<p>No tutor has been chosen</p>");
        //var url  = "<?php echo base_url('admin/AjaxStudentTutors') ?>";
        jQuery('#studentsTutors').html(tutor);
        //var dat  = { student_id: id,  bookedSince: selected };
        //jQuery('#studentsTutors').html('<img src="<?php echo base_url()."images/circle-loading-gif.gif";?>" alt="loading" />');
        /*var request = $.ajax({
                             url    : url,
                             type   : "POST",
                             data   : dat,
                             success: function(msg){  jQuery('#studentsTutors').html(msg); }
                         });
                        request.fail(function(jqXHR, textStatus){
                            apprise('Something went wrong. Please try again later.', {'animate': true});
          });*/
    }
     
    </script>