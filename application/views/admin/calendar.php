<link href='<?php echo base_url(); ?>calendarfiles/jQuery-structure.css' rel='stylesheet' />
<link href='<?php echo base_url(); ?>calendarfiles/jquery-ui-theme.css' rel='stylesheet' />
<link href='<?php echo base_url(); ?>calendarfiles/fullcalendar.css' rel='stylesheet' />
<link href='<?php echo base_url(); ?>calendarfiles/fullcalendar.print.css' rel='stylesheet' media='print' />
<script src='<?php echo base_url(); ?>calendarfiles/lib/moment.min.js'></script>

<script src='<?php echo base_url(); ?>calendarfiles/fullcalendar.min.js'></script>
<script>
    var fullCalendar;
      function removeEventMsg(customer_id, calendar_id)
    {
        
         $.ajax({
                             url    : "<?php echo base_url('calendar/deleteadmin') ?>",
                             type   : "POST",
                             data   : { calendar_id: calendar_id },
                             success: function(msg){   
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
                                  //alert(msg[1]);
                                 //fullCalendar = $('#calendar').fullCalendar({ events: { url: '<?php echo base_url();?>calendar/caldata', error: function() { } }  });
                                 
                             }
                         });
                        request.fail(function(jqXHR, textStatus){
                            apprise('Something went wrong. Please try again later.', {'animate': true});
          });
    }
    function submitAppoinChange(event, view, jsEvent, duration, url, type)
    {
       // alert(event.customer_ID + " <--customer ID");
        if (event.type == 0) event.type = <?php echo $type;?>;
        var request = $.ajax({
                             url    : url,
                             type   : "POST",
                             data   : { cal_id: event.calendar_ID, appointChange: 1, chosenDat: event.start.format(), dateStart: view.start.format(), dateEnd: view.end.format(), inven_id: event.inven_id, type: event.type, customer_ID: event.customer_ID, duration: duration},
                             success: function(msg){  console.log(msg);
                                  msg = JSON.parse(msg);
                                  jQuery('#popup_bloc_2').fadeIn().css({'top' : (jsEvent.pageY - 100) + 'px', 'left' : (jsEvent.pageX -400) + 'px'});
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
//jQuery('#closePopup').on('click', function() { console.log('pasar nada'); alert('here'); } );
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
         var type = <?php echo $type;?>;
         var url = "<?php echo base_url('calendar/dragAppoint') ?>";
         submitAppoinChange(event, view, jsEvent, duration, url, type);
     },
     eventClick: function(calEvent, jsEvent, view) {  //alert('event or om ' + jsEvent.pageY);
 
 
                  var extra = '';
                
          // if (calEvent.tutor_ID == global_c_ID)
           { 
               extra = "<div style='margin: auto; margin-top: 10px;' id='removeEventMsg'>Remove this appointment?<div><span id='removdatyes'>Yes</span> | <span id='removeno'>No</span></div></div>";
            }
           jQuery('#popwar_2').html('Appointment:<br />' + calEvent.title + extra).css({'color' : '#000'});
           //jQuery('.fc-event').each(function()  {  alert(  parseInt(jQuery(this).data('fcSeg').event.tutor_ID)  );  } );
           jQuery('#popup_bloc_2').fadeIn().css({'top' : (jsEvent.pageY -100) + 'px', 'left' : (jsEvent.pageX - 400) + 'px'});
           jQuery('#closeZZPop_2').on('click', function() { jQuery('#popup_bloc_2').fadeOut();});
           jQuery('#removeno').on('click', function() { jQuery('#closeZZPop_2').trigger('click'); } );
           jQuery('#removdatyes').on('click', function() { removeEventMsg(calEvent.customer_ID, calEvent.calendar_ID);  } );
           /* jQuery('#popup_bloc_2').fadeIn().css({'top' : (jsEvent.pageY - 100) + 'px', 'left' : jsEvent.pageX + 'px'});
                                  jQuery('#closeZZPop_2').on('click', function() { jQuery('#popup_bloc_2').fadeOut();  } ); 
                                  jQuery('#popwar_2').html('Appointment:<br />' + calEvent.title).css({'color' : '#000'});
           */ 
    },
    /* dayClick: function(date, jsEvent, view) 
     {
           var dateFormat = date.format();
           var start = view.start.format();
           var end = view.end.format();
          // alert(start); alert(end);
           jQuery('#dateStart').val(start);
           jQuery('#dateEnd').val(end);
           var chosenDat = jQuery('#dateHidden').html( dateFormat );
           var datFot = dateFormat.split('T');
           jQuery('#dateclicked').html("<b>Time:</b> " +  datFot[1]);
           jQuery('body').append('<div id=\'fade99\'></div>');
           jQuery('#popwarn').html(''); jQuery('#successtell').html('') 
           jQuery('#fade99').fadeIn();
           jQuery('#popup_block').fadeIn().css({'top' : (jsEvent.pageY - 100) + 'px', 'left' : jsEvent.pageX + 'px'});
          // $(this).css('background-color', 'blue');
           jQuery('#closeZZPopup').on('click', function() { jQuery('#popup_block').fadeOut(); jQuery('#fade99').remove(); } ); 
     },*/
              
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
				url: '<?php echo base_url();?>calendar/admincalendar',
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
                margin-top: 70px;
	}

</style>
<div id="main-wrapper">
    <div class="clr"></div>
    <div class="content-wrapper">
        <?php echo $breadcrumbs; ?><br /><br />
        <div class="content-page-view fl" style="width: 950px;margin: auto;">
            <div id='calendar'></div>
            
        </div>
         <div id="popup_bloc_2" style="height: auto;">
             <div id='closeZZPop_2' style="height: 30px; margin-left:300px;">
                <div style="float: right; background: #a00; color: #fff; border: 1px solid #aaa; border-radius: 50%; padding: 5px;" >X</div></div>
            <div id="popwar_2" style="color: #a00;"></div>   
        </div>
         <div id="popup_block" style="height: auto; width: 350px; position: fixed;">
             <div id="popwarn1"></div>
             <div id='closeZZPopup' style="height: 30px; margin-left: 325px;">X</div></div>
        
    </div></div>
