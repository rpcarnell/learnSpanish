<?php
$type = 1;

?>
<link href='<?php echo base_url(); ?>calendarfiles/jQuery-structure.css' rel='stylesheet' />
<link href='<?php echo base_url(); ?>calendarfiles/jquery-ui-theme.css' rel='stylesheet' />
<link href='<?php echo base_url(); ?>calendarfiles/fullcalendar.css' rel='stylesheet' />
<link href='<?php echo base_url(); ?>calendarfiles/fullcalendar.print.css' rel='stylesheet' media='print' />
<script src='<?php echo base_url(); ?>calendarfiles/lib/moment.min.js'></script>
<script src='<?php echo base_url(); ?>calendarfiles/lib/jquery.min.js'></script>
<script src='<?php echo base_url(); ?>calendarfiles/fullcalendar.min.js'></script>

<script>
    var fullCalendar; var fullCalendar;
    var global_c_ID = <?php echo $tutor_id;?>;
    function submitAppoinChange(event, view, jsEvent, duration, url, type)
    {
        
        if (event.type == 0) event.type = <?php echo $type;?>;
        var request = $.ajax({
                             url    : url,
                             type   : "POST",
                             data   : { cal_id: event.calendar_ID, appointChange: 1, chosenDat: event.start.format(), dateStart: view.start.format(), dateEnd: view.end.format(), inven_id: event.inven_id, type: event.type, customer_ID: event.customer_ID, duration: duration},
                             success: function(msg){  console.log(msg);
                                  msg = JSON.parse(msg);
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
    function submitDuration()
    {
        var duration = jQuery('#duration').val();
         
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
                             url    : "<?php echo base_url('calendar/teachercalendar') ?>",
                             type   : "POST",
                             data   : { inven_id: jQuery('#inven_id').html(), type: <?php echo $type;?>, tutor_id: <?php echo $tutor_id;?>, duration: duration, chosenDat: jQuery('#dateHidden').html() },
                             success: function(msg){
                                 alert("try: " + msg);
                                 //fullCalendar = $('#calendar').fullCalendar({ events: { url: '<?php echo base_url();?>calendar/caldata', error: function() { } }  });
                                 fullCalendar.fullCalendar( 'refetchEvents' );
                             }
                         });
                        request.fail(function(jqXHR, textStatus){
                            apprise('Something went wrong. Please try again later.', {'animate': true});
          });
    }

	$(document).ready(function() {
	        function removeEventMsg(customer_id, calendar_id)
    {
        
         $.ajax({
                             url    : "<?php echo base_url('calendar/tutordelete') ?>",
                             type   : "POST",
                             data   : { tutor_id: <?php echo $tutor_id; ?>, calendar_id: calendar_id },
                             success: function(msg){ alert(msg);  
                                 fullCalendar.fullCalendar( 'refetchEvents' );//put it here, so that a JSON error won't affect this line
                                  msg = JSON.parse(msg);
                                  var $css;
                                  if (msg[0] == 0)
                                  { $css = '#a00';  }  
                                  else if (msg[0] == 1)
                                  {  $css = '#0a0' } 
                                  jQuery('#removeEventMsg').css({'color' : $css}).html(msg[1]);
                            }
                         });
                        request.fail(function(jqXHR, textStatus){
                            apprise('Something went wrong. Please try again later.', {'animate': true});
          });
    }   
		 fullCalendar = $('#calendar').fullCalendar({
           
     eventDrop: function( event, delta, revertFunc, jsEvent, ui, view) 
     { 
         var duration = event.duration;
         var url = "<?php echo base_url('calendar/dragTut') ?>";
         var type = <?php echo $type;?>;
         submitAppoinChange(event, view, jsEvent, duration, url, type);
     },  
     eventResize: function( event, delta, revertFunc, jsEvent, ui, view ) 
     { 
         var unixStart = Math.floor(Date.parse( event.start.format() ) / 1000 );
         var unixEnd = Math.floor(Date.parse( event.end.format() ) / 1000 );
         var duration = Math.floor(( unixEnd - unixStart) / 60 );
         var type = <?php echo $type;?>;
         var url = "<?php echo base_url('calendar/dragTut') ?>";
         submitAppoinChange(event, view, jsEvent, duration, url, type);
     },  
     dayClick: function(date, jsEvent, view) 
     {  },
              eventClick: function(calEvent, jsEvent, view) {  
                  var extra = '';
                
            if (calEvent.tutor_ID == global_c_ID)
           { 
               extra = "<div style='margin: auto; margin-top: 10px;' id='removeEventMsg'>Remove this appointment?<div><span id='removdatyes'>Yes</span> | <span id='removeno'>No</span></div></div>";
            }
           jQuery('#popwar_2').html('Appointment:<br />' + calEvent.title + extra).css({'color' : '#000'});
           //jQuery('.fc-event').each(function()  {  alert(  parseInt(jQuery(this).data('fcSeg').event.tutor_ID)  );  } );
           jQuery('#popup_bloc_2').fadeIn().css({'top' : (jsEvent.pageY -100) + 'px', 'left' : jsEvent.pageX + 'px'});
           jQuery('#closeZZPop_2').on('click', function() { jQuery('#popup_bloc_2').fadeOut();});
           jQuery('#removeno').on('click', function() { jQuery('#closeZZPop_2').trigger('click'); } );
           jQuery('#removdatyes').on('click', function() { removeEventMsg(calEvent.customer_ID, calEvent.calendar_ID);  } );
           /* jQuery('#popup_bloc_2').fadeIn().css({'top' : (jsEvent.pageY - 100) + 'px', 'left' : jsEvent.pageX + 'px'});
                                  jQuery('#closeZZPop_2').on('click', function() { jQuery('#popup_bloc_2').fadeOut();  } ); 
                                  jQuery('#popwar_2').html('Appointment:<br />' + calEvent.title).css({'color' : '#000'});
           */                          
           
    },
    
			dayRender: function (date, cell)
			{
				//jQuery('#calendar').fullCalendar('refetchEvents');
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
				url: '<?php echo base_url();?>calendar/teachercalendar?tutor_id=<?php echo $tutor_id; ?>',
				error: function() {
					//$('#script-warning').show();
				}
			},
			loading: function(bool) {
				$('#loading').toggle(bool);
			}
		});
		
	});

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
		margin: 40px auto;
		padding: 0 10px;
                margin-top: 100px;
	}

</style>
<div id='script-warning'>
		<code>calendar coltroller not sending the right data. It </code> must be running.
	</div>

	<div id='loading'>loading...</div>
  <br />
        <div class="clr"></div>
<?php
    if ($error) { echo "<br /><br /><div style='color: #a00; margin-top: 5px;' class='spanish-form'>".$error."</div>"; }
    ?>
     
	<div id='calendar'></div>
        
        <div id="popup_bloc_2" style="height: auto;">
             <div id='closeZZPop_2' style="height: 30px; margin-left:300px;">
                <div style="float: right; background: #a00; color: #fff; border: 1px solid #aaa; border-radius: 50%; padding: 5px;" >X</div></div>
            <div id="popwar_2" style="color: #a00;"></div>   
        </div>
        <div id="popup_block" style="height: auto;"><div id='closeZZPopup' style="height: 30px; margin-left:300px;"><div style="float: right; background: #a00; color: #fff; border: 1px solid #aaa; border-radius: 50%; padding: 5px;" >X</div></div>
    <div class="event-edit-inputs">
            <div class="float-container" style="position: relative;">
                 <div class="form-group">
                    <div>
                     
                    </div>
                    <div id='dateclicked'></div>
                    <div id="dateHidden" style="display: none;"></div>
                    <div style="margin-top: 5px;">Enter Duration (minutes): <input type="text" id="duration" name="duration" /></div>
                    <br /><input type="submit" value="Submit" onClick="submitDuration()" />
                    <div id="popwarn" style="color: #a00;"></div>
                    <br />
                </div>
               
               
           

        </div>
</div></div>
