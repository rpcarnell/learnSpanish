<div class="spanish">
    <div class="spanish-content profile">
        <?php echo $breadcrumbs;   ?><br /><br />
        <div class="content-page-view fl" style="width: 950px;margin: auto;"><br />
            <br /><a href="<?php echo base_url();?>tutors/createInvoice" style="background: #fff; border: 1px solid #333; padding: 5px; text-decoration: none;">Create an Invoice</a><br /><br />
           
                <br />
         <h3>Billing beginning <?php echo date('M d, Y', $firstDate);?>, ending <?php echo date('M d, Y', $lastDate);?>, for All Students</h3>
        
        <p><?php echo  $countStudents; ?> Students, $<?php echo $sumInvoices;?> Invoiced, $<?php echo $sumPaid;?> Paid, <?php echo $countRecur;?> Recurring, <?php echo $countFlex;?> Flex Time, <?php echo $quantUnpaid;?> Unpaid</p>
        <br />
            
            <h1>Show Existing Invoices</h1>
             <div style='width: 90%;'>
            <form id="myForm">
                <div class="warning"></div>
                <b>Date Range</b>
                <p><input type='radio' checked='checked' name='invoicefrom' value='0' />All</p>
                <p><input type='radio' name='invoicefrom' value='1' />Last Month</p>
                <p><input type='radio' name='invoicefrom' value='2' />This Month</p>
                <div><div style='margin-left: 0px; float: left;'><input type='radio' name='invoicefrom' value='3' /><span>Custom Dates</span>&nbsp;</div>
                <div style='margin-left: 15px; float: left;'>Start: <input id="invoicestart" type="text" name="invoicestart" value=""  /></div>
                <div style='margin-left: 15px; float: left;'>End: <input  id="invoiceend" type="text" name="invoiceend" value=""  /></div>
                </div>
            </form>
             </div>
            <div style='clear: both;'></div>
            <div style='width: 90%; margin-top: 50px;'>
              <!--  <div style='width: 40%; height: 110px; float: left; margin-left: 0px;'><b>For Tutors:</b>
                    <div style='border: 1px solid #aaa; padding: 5px; max-height: 100px; overflow-y: auto;'>
                        <?php
                        foreach ($tutors as $t)
                        {
                            echo "<p><a style='text-decoration: none;' href='javascript:void(0)' onClick='invoiceTutor(".$t->tutor_ID.")'>".$t->name."</a></p>";
                        }
                        ?>
                        
                    </div>
                </div>-->
                <div style='height: 110px; float: left; margin-left: 20px;'><b>For  Students:</b>
                    <div  style='width: 100%; border: 1px solid #aaa; padding: 5px; max-height: 100px; overflow-y: auto;'>
                        <?php
                        if (isset($customers) && is_array($customers)) {
                        foreach ($customers as $t)
                        {
                            echo "<p><a style='text-decoration: none;' href='javascript:void(0)' onClick='invoiceCust(".$t->Customer_ID.")'>".$t->name."</a></p>";
                        }} else echo "There are no students available at the moment.";
                        ?>
                    </div>
                </div>
            </div>
            <div style='clear: both;'></div>
            <div id='adminSpecs' style='width: 700px; margin-top: 50px;padding: 5px;'></div>
        <div id='teacherstudents' style='width: 700px; margin-top: 20px; border: 1px solid #aaa; padding: 5px; max-height: 200px; overflow-y: auto;'></div>
        <br />
        </div>
        
    </div>

</div>
<script>
      $("#invoicestart").datepicker({dateFormat: "mm/dd/yy"});
    $("#invoiceend").datepicker({dateFormat: "mm/dd/yy"});
    function invoiceTutor(id)
    { getInvoices(id, 2); }
    function invoiceCust(id)
    { getInvoices(id, 1); }
    function getInvoices(id, type)
    {
        jQuery('.warning').html('');
        var theTime = $('input[name="invoicefrom"]:checked', '#myForm').val();
        var DatesJSON = ''; 
        if (typeof(theTime) == 'undefined') theTime = 0;
        if (theTime  == 0)
        {
            jQuery('#adminSpecs').html('');
        }
        else if (theTime  == 1)
        {
            jQuery('#adminSpecs').html('<p>Time: Last Month</p>');
        }
        else if (theTime  == 2)
        {
            jQuery('#adminSpecs').html('<p>Time: This Month</p>');
        }
        
            
        if (theTime == 3)
        {
            var invoiceStart = jQuery('#invoicestart').val();
            var invoiceEnd = jQuery('#invoiceend').val();
            jQuery('#adminSpecs').html('<p>Time: between '+ invoiceStart + " and "+ invoiceEnd + "</p>");
            
            
            invoiceStart = invoiceStart.split('/');
            
            invoiceStart = invoiceStart[2] + "-" + invoiceStart[0] + "-" + invoiceStart[1];
            
             
            if (invoiceStart == '' || invoiceEnd == '')
            {
                jQuery('.warning').html('One of the date fields is empty');
                return;
            }
            
            invoiceEnd = invoiceEnd.split('/');
            
            invoiceEnd = invoiceEnd[2] + "-" + invoiceEnd[0] + "-" + invoiceEnd[1];
            invoiceStart = new Date( invoiceStart ).getTime() / 1000;
            invoiceEnd = new Date( invoiceEnd ).getTime() / 1000;
            if (isNaN(invoiceEnd) || isNaN(invoiceStart))
            {
                jQuery('.warning').html('One of the dates has incorrect data');
                return;
            }
             if (invoiceStart > invoiceEnd)
            {
                jQuery('.warning').html('The End Date must be greater than the Start Date');
                
                return;
            }
            var invoiceDates = [];
            invoiceDates[0] = invoiceStart;
            invoiceDates[1] = invoiceEnd;
            DatesJSON = JSON.stringify(invoiceDates);
        }
        var url, dat = {};
        if (type == 1) 
        { 
            url = "<?php echo base_url('admin/studentInvoices') ?>";
            dat = { tutorID: <?php echo $tutors[0]->tutor_ID; ?>, tutorUse: 1, theTime: theTime, student_id: id, DatesJSON: DatesJSON };
        }
        else if (type == 2)
        {
           url  = "<?php echo base_url('admin/tutorinvoices') ?>";
           dat  = { theTime: theTime, tutor_id: id, DatesJSON: DatesJSON  };
        }
        
        var request = $.ajax({
                             url    : url,
                             type   : "POST",
                             data   : dat,
                             success: function(msg){  
                                 if (msg != 0) jQuery('#teacherstudents').html(msg);
                                 else jQuery('#teacherstudents').html("No data to show");
                             }
                         });
                        request.fail(function(jqXHR, textStatus){
                            apprise('Something went wrong. Please try again later.', {'animate': true});
                        });
        
        var url_2  = "<?php echo base_url('tutors/studentDates') ?>";
            var request = $.ajax({
                             url    : url_2,
                             type   : "POST",
                             data   : dat,
                             success: function(msg){   
                              //   jQuery('#adminSpecs').html( JSON.stringify(msg) ); return;
                                 msg = JSON.parse(msg);
                               var totalMSG = "<p>Billing beginning "+ msg.DatesJSON_1+", ending "+ msg.DatesJSON_2 + "</p>";
                               totalMSG += "<p>1 Student, $"+ msg.sumInvoices+" Invoiced, $"+ msg.sumPaid +" Paid, "+ msg.countRecur +" Recurring, "+ msg.countFlex +" Flex Time, "+msg.quantUnpaid+" Unpaid</p>";
                               jQuery('#adminSpecs').html( totalMSG );
                                // var msg_2 = JSON.parse(msg.DatesJSON);
                                  
                               //  jQuery('#adminSpecs').html('<p>Time: between '+ invoiceStart + " and "+ invoiceEnd + "</p>");
                                  // jQuery('#adminSpecs').append('<br />'+msg.countUnpaid);
                             }
                         });
                        request.fail(function(jqXHR, textStatus){
                            apprise('Something went wrong. Please try again later.', {'animate': true});
                        });
    }
    </script>