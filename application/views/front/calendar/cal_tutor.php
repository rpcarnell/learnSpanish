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
    var fullCalendar;
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
                             url    : "<?php echo base_url('calendar/createappoint') ?>",
                             type   : "POST",
                             data   : { inven_id: jQuery('#inven_id').html(), type: <?php echo $type;?>, customer_ID: <?php echo $customer_ID;?>, duration: duration, chosenDat: jQuery('#dateHidden').html() },
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
	 
		 fullCalendar = $('#calendar').fullCalendar({
                     
     dayClick: function(date, jsEvent, view) 
     {
         /*  var dateFormat = date.format();
           var chosenDat = jQuery('#dateHidden').html( dateFormat );
           var datFot = dateFormat.split('T');
           jQuery('#dateclicked').html("<b>Time:</b> " +  datFot[1]);
           jQuery('body').append('<div id=\'fade99\'></div>');
           jQuery('#fade99').fadeIn();
           jQuery('#popup_block').fadeIn().css({'top' : (jsEvent.pageY - 100) + 'px', 'left' : jsEvent.pageX + 'px'});
          // $(this).css('background-color', 'blue');
           jQuery('#closeZZPopup').on('click', function() { jQuery('#popup_block').fadeOut(); jQuery('#fade99').remove(); } ); 
           
           
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
				url: '<?php echo base_url();?>calendar/tutorCal?tutor_id=<?php echo $tutor_id; ?>',
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
		<code>calendar controller not sending the right data. It </code> must be running.
	</div>

	<div id='loading'>loading...</div>
  
       
       <div class="clr"></div>
<?php
    if ($error) { echo "<br /><br /><div style='color: #a00; margin-top: 5px;' class='spanish-form'>".$error."</div>"; }
    ?>
      <br /><br /><div class="home-main-content"><?php echo $breadcrumbs; ?></div>
      <br /><br /><br /><br />
      <?php
       $tutor = $tutor->getTutor($tutor_id);
     
       echo "<div class='tutorlayer' style='clear: both; float: none; margin: 20px auto; max-width: 900px;'>";
                echo "<img src='".base_url()."uploads/tutors/".$tutor->photo."' style='float: left; margin: 5px; height: 150px; max-width: 150px;' />";
		//echo "<div style='float: right; margin: 30px 0x;'><a href='".base_url().$tutor->tutor_ID."'>Apply</a><br /><a href='".base_url()."calendar/tutorSchedule/".$tutor->tutor_ID."'>View Openings</a></div>";
		echo "<div><p>Name: <b>".ucwords($tutor->name)."</b></p></div>";
		
		echo "<div style='margin: 10px; width: 90%;'><p>".ucwords($tutor->bio)."</p></div><div style='clear: both;'></div>";
		echo "</div>";
      ?>
	<div id='calendar'></div>
        
        
        <div id="popup_block" style="height: auto;"><div id='closeZZPopup' style="height: 30px; margin-left:300px;"><div style="float: right; background: #a00; color: #fff; border: 1px solid #aaa; border-radius: 50%; padding: 5px;" >X</div></div>
    <div class="event-edit-inputs">
            <div class="float-container" style="position: relative;">
                 <div class="form-group">
                    <div>
                        <?php if ($type == 1 && isset($row->Inventory_ID) && is_numeric($row->Inventory_ID))
                        {
                            echo "<h2>Flex Time Appointment</h2>";
                           
                           echo "<p><b>Teacher:</b> ".$tutor->name."</p>";
                            echo "<div style='display: none;' id='inven_id'>".$row->Inventory_ID."</div>";
                        }
                        elseif ($type == 2)
                        {
                            echo "<h2>Recurring Time Appointment</h2>";
                         
                              
                            echo "<div style='display: none;' id='inven_id'>".$row->{'recurring-Profile-ID'}."</div>";  
                            echo "<p><b>Teacher:</b> ".$tutor->name."</p>";
                        }
                         ?>
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
