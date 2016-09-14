<link href='<?php echo base_url(); ?>calendarfiles/jQuery-structure.css' rel='stylesheet' />
<link href='<?php echo base_url(); ?>calendarfiles/jquery-ui-theme.css' rel='stylesheet' />
<link href='<?php echo base_url(); ?>calendarfiles/fullcalendar.css' rel='stylesheet' />
<link href='<?php echo base_url(); ?>calendarfiles/fullcalendar.print.css' rel='stylesheet' media='print' />
<script src='<?php echo base_url(); ?>calendarfiles/lib/moment.min.js'></script>
<script src='<?php echo base_url(); ?>calendarfiles/lib/jquery.min.js'></script>
<script src='<?php echo base_url(); ?>calendarfiles/fullcalendar.min.js'></script>

<script><!--
     function getRecurRemain()
     {
         var customer_id = global_c_ID; 
         var inven_id = jQuery('#inven_id').html();
          var request = $.ajax({
                             url    : "<?php echo base_url('calendar/recurremain') ?>",
                             type   : "POST",
                             data   : { customer_id: customer_id, inven_id: inven_id },
                             success: function(msg){  
                              msg = JSON.parse(msg);
                              if (msg[0] == 1) { jQuery('#availableTime').html("<b>Available Time:</b> " + msg[1] + " minutes"); } 
                         }
                         });
                        request.fail(function(jqXHR, textStatus){
                            apprise('Something went wrong. Please try again later.', {'animate': true});
          });
     }
     function getFlexRemain()
     {
         var customer_id = global_c_ID; 
         var inven_id = jQuery('#inven_id').html();
          var request = $.ajax({
                             url    : "<?php echo base_url('calendar/flexremain') ?>",
                             type   : "POST",
                             data   : { customer_id: customer_id, inven_id: inven_id },
                             success: function(msg){  msg = JSON.parse(msg);
                              if (msg[0] == 1) { jQuery('#availableTime').html("<b>Available Time:</b> " + msg[1] + " minutes"); }  }
                         });
                        request.fail(function(jqXHR, textStatus){
                            apprise('Something went wrong. Please try again later.', {'animate': true});
          });
     }
    <?php
    echo "var globalTime=".time().";";
    
    ?>
    jQuery(function(){ 
       if (!isNaN(jQuery('#inven_id').html()))
       {
           //('<?php echo $type;?>-' + jQuery('#inven_id').html();
           jQuery('#<?php echo $type;?>-' + jQuery('#inven_id').html()).attr('selected', 'selected');
       }
      // bind change event to select
      jQuery('#dynamic_select').on('change', function () {
          var url = $(this).val(); // get selected value
          if (url) { // require a URL
              window.location = url; // redirect
          }
          return false;
      });
    });
    var fullCalendar;
    var global_c_ID = <?php echo $customer_ID;?>;
    function removeEventMsg(customer_id, calendar_id)
    {
        
         var request = $.ajax({
                             url    : "<?php echo base_url('calendar/deleteappoint') ?>",
                             type   : "POST",
                             data   : { customer_id: customer_id, calendar_id: calendar_id },
                             success: function(msg){    
                                 fullCalendar.fullCalendar( 'refetchEvents' );//put it here, so that a JSON error won't affect this line
                                  msg = JSON.parse(msg);
                                  var $css;
                                  if (msg[0] == 0)
                                  { $css = '#a00';  }  
                                  else if (msg[0] == 1)
                                  {  $css = '#0a0'; } 
                                  jQuery('#popup_bloc_2').fadeOut();
                                 // jQuery('#removeEventMsg').css({'color' : $css}).html(msg[1]);
                            }
                         });
                        request.fail(function(jqXHR, textStatus){
                            apprise('Something went wrong. Please try again later.', {'animate': true});
          });
    }
    function submitDuration()
    {
        var duration = jQuery('#duration').val();
        var dateStart = jQuery('#dateStart').val();
        var dateEnd = jQuery('#dateEnd').val(); 
        if (isNaN(duration) || duration == '' || duration == 0)
        {
            jQuery('#popwarn').html('Duration is not a number. Please try again');
            return;
        } 
        else if ( duration > 55)
        {
            jQuery('#popwarn').html('Duration cannot be more than 55 minutes');
            return;
        } 
       var request = $.ajax({
                             url    : "<?php echo base_url('calendar/createappoint') ?>",
                             type   : "POST",
                             data   : { appointChange: 0, dateEnd: dateEnd, dateStart: dateStart, inven_id: jQuery('#inven_id').html(), type: <?php echo $type;?>, customer_ID: <?php echo $customer_ID;?>, duration: duration, chosenDat: jQuery('#dateHidden').html() },
                             success: function(msg){ console.log(msg);  
                                 fullCalendar.fullCalendar( 'refetchEvents' );//put it here, so that a JSON error won't affect this line
                                  msg = JSON.parse(msg);
                                  
                                  if (msg[0] == 0)
                                  {  
                                      jQuery('#popwarn').html(msg[1]);
                                  } else { jQuery('#popwarn').html('');  }
                                  if (msg[0] == 1)
                                  {  
                                      jQuery('#successtell').html(msg[1]);
                                  } else { jQuery('#successtell').html(''); }
                            }
                         });
                        request.fail(function(jqXHR, textStatus){
                            apprise('Something went wrong. Please try again later.', {'animate': true});
          });
    }
    function submitAppoinChange(event, view, jsEvent, duration, url, type)
    {
         if (event.type == 0) event.type = <?php echo $type;?>;
        var request = $.ajax({
                             url    : url,
                             type   : "POST",
                             data   : { cal_id: event.calendar_ID, appointChange: 1, chosenDat: event.start.format(), dateStart: view.start.format(), dateEnd: view.end.format(), inven_id: event.inven_id, type: event.type, customer_ID: event.customer_ID, duration: duration},
                             success: function(msg){  console.log(msg);
                                  msg = JSON.parse(msg);
                                     fullCalendar.fullCalendar( 'refetchEvents' );
                                  jQuery('#popup_bloc_2').fadeIn().css({'top' : (jsEvent.pageY - 100) + 'px', 'left' : jsEvent.pageX + 'px'});
                                  jQuery('#closeZZPop_2').on('click', function() { jQuery('#popup_bloc_2').fadeOut(); jQuery('#fade99').remove(); } ); 
                                  if (msg[0] == 0)
                                  {   
                                      jQuery('#popwar_2').html(msg[1]).css({'color' : '#a00'});
                                      fullCalendar.fullCalendar( 'refetchEvents' );
                                  } else { jQuery('#popwar_2').html(msg[1]).css({'color' : '#0a0'});  }
                             }
                         });
                        request.fail(function(jqXHR, textStatus){ alert('ERROR');
                           // apprise('Something went wrong. Please try again later.', {'animate': true});
          });
    }
 
$(document).ready(function() {
	 
     fullCalendar = $('#calendar').fullCalendar({
     eventDrop: function( event, delta, revertFunc, jsEvent, ui, view) 
     { 
         var duration = event.duration;
         var url = "<?php echo base_url('calendar/dragAppoint') ?>";
         var type = <?php echo $type;?>;
         submitAppoinChange(event, view, jsEvent, duration, url, type);
     },  
     eventResize: function( event, delta, revertFunc, jsEvent, ui, view ) 
     { 
         var unixStart = Math.floor(Date.parse( event.start.format() ) / 1000 );
         var unixEnd = Math.floor(Date.parse( event.end.format() ) / 1000 );
         var duration = Math.floor(( unixEnd - unixStart) / 60 );
         if (duration > 55) duration = 55;
         var type = <?php echo $type;?>;
         var url = "<?php echo base_url('calendar/dragAppoint') ?>";
         submitAppoinChange(event, view, jsEvent, duration, url, type);
     },
     dayClick: function(date, jsEvent, view) 
     {  
           var type = <?php echo $type;?>;
           if (type == 2) getRecurRemain(); 
           else if (type == 1) getFlexRemain(); 
           var dateFormat = date.format();
           var start = view.start.format();
           var end = view.end.format();
           jQuery('#dateStart').val(start);
           jQuery('#dateEnd').val(end);
           var chosenDat = jQuery('#dateHidden').html( dateFormat );
           var unixPast = Date.parse(dateFormat ) / 1000;
          // alert('esca');
           var datFot = dateFormat.split('T');
           jQuery('#dateclicked').html("<b>Time:</b> " +  datFot[1]);
           jQuery('body').append('<div id=\'fade99\'></div>');
           jQuery('#popwarn').html(''); jQuery('#successtell').html('');
           if (globalTime > unixPast)
           {
                jQuery('#popwarn').html("You appointment is in the past.");
               jQuery('#appointSubmit').fadeOut();
           } else {
               jQuery('#appointSubmit').fadeIn();
               jQuery('#popwarn').html("");
           }
           jQuery('#fade99').fadeIn();
           jQuery('#popup_block').fadeIn().css({'top' : (jsEvent.pageY - 100) + 'px', 'left' : jsEvent.pageX + 'px'});
          // $(this).css('background-color', 'blue');
           jQuery('#closeZZPopup').on('click', function() { jQuery('#popup_block').fadeOut(); jQuery('#fade99').remove(); } ); 
     },
       eventClick: function(calEvent, jsEvent, view) {  
           var extra = '';
            if (calEvent.customer_ID == global_c_ID)
           {
               extra = "<div style='margin: auto; margin-top: 10px;' id='removeEventMsg'><b>Remove this appointment?</b><div style='width: 100%; text-align: center;'><span id='removdatyes'>Yes</span> | <span id='removeno'>No</span></div></div>";
           }
           jQuery('#popwar_2').html('<img style="max-height: 80px; max-width: 100px; float: left; margin: 0px 5px;" src=\'<?php echo base_url();?>/uploads/tutors/'+calEvent.tutorPhoto+'\' />Appointment:<br />' + calEvent.title + extra).css({'color' : '#000'});
           //jQuery('.fc-event').each(function()  {  alert(  parseInt(jQuery(this).data('fcSeg').event.tutor_ID)  );  } );
           jQuery('#popup_bloc_2').fadeIn().css({'top' : (jsEvent.pageY -100) + 'px', 'left' : jsEvent.pageX + 'px'});
           jQuery('#closeZZPop_2').on('click', function() { jQuery('#popup_bloc_2').fadeOut();});
           jQuery('#removeno').on('click', function() { jQuery('#closeZZPop_2').trigger('click'); } );
           jQuery('#removdatyes').on('click', function() { removeEventMsg(calEvent.customer_ID, calEvent.calendar_ID);  } );
           
    },       
   /*firstDay :moment().weekday(),*/
    viewRender: function(currentView){
        var minDate = moment();
        // Past
       /* if (minDate >= currentView.start && minDate <= currentView.end) {
            $(".fc-prev-button").prop('disabled', true); 
            $(".fc-prev-button").addClass('fc-state-disabled'); 
        }
        else {
            $(".fc-prev-button").removeClass('fc-state-disabled'); 
            $(".fc-prev-button").prop('disabled', false); 
        }*/},
			dayRender: function (date, cell)
			{
				//jQuery('#calendar').fullCalendar('refetchEvents');
                                //cell.css("background-color", "red");
                               
			},
    theme: true,
			header: {
				left: 'prev,next,today',
				center: 'title',
				right: 'agendaWeek,agendaDay',
				prev: 'left-single-arrow',
    next: 'right-single-arrow',
    prevYear: 'left-double-arrow',
    nextYear: 'right-double-arrow'
			},
			defaultDate: '<?php echo date('Y-m-d', time()); ?>',
			editable: true,
                        defaultView: 'agendaWeek',
			eventLimit: true, // allow "more" link when too many events
			events: {
				url: '<?php echo base_url();?>calendar/caldata',
                                id: 1,
				error: function() {
					//$('#script-warning').show();
				}
			},
			loading: function(bool) {
				$('#loading').toggle(bool);
			}
		});
});
-->
</script>
<style>

	body {
		margin: 0;
		padding: 0;
		font-family: "Lucida Grande",Helvetica,Arial,Verdana,sans-serif;
		font-size: 14px;
	}

	#script-warning {
		display: none;
		background: #eee;
		border-bottom: 1px solid #ddd;
		padding: 0 10px;
		line-height: 40px;
		text-align: center;
		font-weight: bold;
		font-size: 12px;
		color: red;
	}

	#loading {
		display: none;
		position: absolute;
		top: 10px;
		right: 10px;
	}

	#calendar {
		max-width: 900px;
                 height: 1000px;
		margin: 40px auto;
		padding: 0 10px;
                margin-top: 70px;
	}

</style>
<div id='script-warning'>
		<code>calendar controller not sending the right data. It </code> must be running.
	</div>

	<div id='loading'>loading...</div>
  <br />
        
        <?php if ($userMessage) { ?><div id="userMessage"><?php echo $userMessage; ?></div><?php } ?>
       <div class="clr"></div>

       <div class="home-main-content" style="margin-top: 30px;">
           <p><b>Instructions:</b> if you click anywhere on the calendar, you will create a new appointment with <?php echo $teacher;?>. Remember, you have
           <?php echo $per_week;?> minutes per week. You can make multiple appointments, but you cannot exceed this time.</p>
           <p>You can change <i>any</i> appointment date and time by simply dragging the appointment accross the calendar. You can change the amount of appointment time
           by increasing the size of the box. However you can only create a new appointment to the purchase you selected.</p>
           <p><b>Caution:</b> It is worth mentioning that gray areas are appointments that do not belong to you. They belong to tutors you have chosen. If this is the case,
           you'll receive a warning if there is a conflict.</p>
       </div>
       <?php
        if ($msg) {
    ?>
       <div class="home-main-content" style="margin-top: 10px;  color: #fff; border: 1px solid #66562f; background: #b1a78e; padding: 5px; border-radius: 5px;">
           <?php echo $msg;?>
       </div>
    <?php } ?>
       
       <?php
    if ($error) { echo "<br /><br /><div style='color: #a00; margin-top: 5px;' class='spanish-form'>".$error."</div>"; }
  if ( ($msg) && ( isset($recurring) || is_array($inventory) )) {
    ?>
       
       <div class="home-main-content">
           <p>Select another purchase if you want to create a new appointment:</p>
            
           <select id="dynamic_select">
    <option value="" selected></option>
   
       <?php
 
   if (isset($recurring) && is_array($recurring)) {
    foreach ($recurring  as $recu)
    {   $urlte = base_url()."calendar/appointments?type=2&id=".$recu->{'recurring-Profile-ID'};
         $gce = $Customers_model->getCalendarEntry($recu->{'customer-ID'}, $recu->{'tutor-ID'});
         if ($gce && isset($gce->day)) $day = $gce->day;
        else $day = '<span style="color: #a00;">NOT GIVEN</span>';
         $tutor = $tutor_model->getTutor($recu->{'tutor-ID'});
         $choice = "Day of Week: $day. Recurring Time with ".ucwords($tutor->name);
         echo "<option value=\"$urlte\" id='2-".$recu->{'recurring-Profile-ID'}."'>$choice</option>";
       }} 
    ?>
    <?php 
      
    if (is_array($inventory)) {
    foreach ($inventory  as $inven)
    {  
        $inven->StartDate = '';
        $tutor = $tutor_model->getTutor($inven->{'tutor_ID'});
        echo "<option id='1-".$inven->Inventory_ID."' value=\"".base_url()."calendar/appointments?type=1&id=".$inven->{'Inventory_ID'}."\">Flex Time with ".ucwords($tutor->name)." ($inven->FlexTime minutes available)</option>";
        
    }}
    
   ?></select>
     </div> <?php } ?>
	<div id='calendar'></div>
        
        <div id="popup_bloc_2" style="height: auto;">
             <div id='closeZZPop_2' style="height: 30px; margin-left:300px;">
                <div style="float: right; background: #a00; color: #fff; border: 1px solid #aaa; border-radius: 50%; padding: 5px;" >X</div></div>
            <div id="popwar_2" style="color: #a00;"></div>   
        </div>
        <div id="popup_block" style="height: auto;">
            <div id='closeZZPopup' style="height: 30px; margin-left:300px;">
                <div style="float: right; background: #a00; color: #fff; border: 1px solid #aaa; border-radius: 50%; padding: 5px;" >X</div></div>
    <div class="event-edit-inputs">
            <div class="float-container" style="position: relative;">
                 <div class="form-group">
                    <div> 
                        <?php 
                         
                        if (isset($row)) {
                        if ($type == 1)
                        {
                            echo "<h2>Flex Time Appointment</h2>";
                            if (!is_numeric($row->tutor_ID)) {
                            $tutor = $tutor->getTutor($row->tutor_ID);
                            if (! $tutor) { $tutor = new stdClass(); $tutor->name = "Unable to get tutor's name"; }
                            echo "<p><b>Teacher:</b> ".$tutor->name."</p>";}
                            echo "<div id='flexremain'></div>";
                            echo "<div style='display: none;' id='inven_id'>".$row->Inventory_ID."</div>";
                        }
                        elseif ($type == 2)
                        {
                            echo "<h2>Recurring Time Appointment</h2>";
                             if (!is_numeric($row->{'tutor-ID'})) {
                            $tutor = $tutor->getTutor($row->{'tutor-ID'});
                            if (! $tutor) { $tutor = new stdClass(); $tutor->name = "Unable to get tutor's name"; }
                             }
                            echo "<div id='recurremain'></div>"; 
                            echo "<div style='display: none;' id='inven_id'>".$row->{'recurring-Profile-ID'}."</div>";  
                            if (!is_numeric($row->{'tutor-ID'})) echo "<p><b>Teacher:</b> ".$tutor->name."</p>";
                        }}
                       ?>
                    </div>
                    <div id='dateclicked'></div>
                    <div id="dateHidden" style="display: none;"></div>
                    <div id="availableTime"></div>
                    <div style="margin-top: 5px;">Enter Duration (minutes): <input type="text" id="duration" name="duration" /></div>
                    <br /><input type="submit" value="Submit" id='appointSubmit' onClick="submitDuration()" />
                    <input type="hidden" id="dateStart" value="" />
                    <input type="hidden" id="dateEnd" value="" />
                    <div id="popwarn" style="color: #a00;"></div>
                    <div id="successtell" style="color: #0a0;"></div>
                    <br />
                </div>
               
               
           

        </div>
</div></div>
