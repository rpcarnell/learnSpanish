<?php
$recently = isset($_GET['rect']) ? $_GET['rect'] : 3;

?>
<div class="spanish">
    <div class="spanish-content profile">
       
        <?php echo $breadcrumbs; ?><br /><br />
       
        <div style="clear: both;"></div>
        <div style="float: left;"><p>
        <select id='orderbills' onChange='orderBills()'>
            <option value="0" <?php if ($order == 0) echo "selected"; ?>>Have ever Purchased from you</option>
            <option value="1" <?php if ($order == 1) echo "selected"; ?>>Have Purchased Recently</option>
            <option value="2" <?php if ($order == 2) echo "selected"; ?>>Signed Up But Never Purchased</option>
            
        </select>
            </p>
        </div>
        <?php
        if ($order == 1) $visible = 'visible';
        else $visible = 'hidden';
        ?>
        <div style="float: right; visibility: <?php echo $visible;?>; margin-top: 0px;" id="recentlyDiv"><p>Define <b>recently</b> as:</p><p>In the last <select id='recently' onChange='orderBills_2()'>
            <option value="3" <?php if ($recently == 3) echo "selected"; ?>>3</option>
            <option value="2" <?php if ($recently == 2) echo "selected"; ?>>2</option>
            <option value="1" <?php if ($recently == 1) echo "selected"; ?>>1</option>
        </select> months</p></div>
        <div style="clear: both;"></div>
        <div>Student Name: <input type="text" onKeyUp="studentSearch(false)" id="studentSearch" /></div>
        <div style="margin: 10px;" id="teacherstudents"><br />
  <?php
 if (isset($students) && is_array($students)) {  
  if (is_array($students))
  { //echo "<table border='0' cellpadding='5' cellspacing='5'><tr><th>Student</th><th>E-mail</th></tr>";//<th>Phone</th></tr>";
      $emailsList = array();
  foreach ($students as $student)
  {
       
     
       $emailsList[] = $student->name." &lt".$student->email_list."&gt";//</a></td></tr>";//<td>".$student->phone."</td></tr>";
       //echo "<tr><td>".$student->name."</td><td>$student->email_list</a></td></tr>";//<td>".$student->phone."</td></tr>";
  }
      echo implode(', ', $emailsList);
//  echo "</table>";
  }
  ?>
       <br /><br /> </div><div id='refreshDiv'><!--<a href='javascript:void(0)' onClick='refresh()'>Refresh</a>--></div>
        <?php
        } else echo "<p><b>There are no bills</b></p>";
        ?>
    </div></div>
<script>
var charused = 0;
function refresh()
{
    console.log('refreshing');
    jQuery('#refreshDiv').html('<img style="height: 60px;" src=\'<?php echo base_url()."images/circle-loading-gif.gif";?>\' alt="loading" />').css({'color' : '#a00'});
    setTimeout(function() {
        studentSearch(true);
        //jQuery('#refreshDiv').css({'color' : '#000'}).html("<a href='javascript:void(0)' onClick='refresh()'>Refresh</a>");  
        jQuery('#refreshDiv').css({'color' : '#000'}).html("");  
    }, 3000);
   // studentSearch(true);
}
function orderBills()
{
     var order = jQuery('#orderbills').val();
    if (parseInt(order) == 1) { jQuery('#recentlyDiv').css({ 'visibility' : 'visible'}); }
    else { jQuery('#recentlyDiv').css({ 'visibility' : 'hidden'}); }
    studentSearch(true);
    
   /* var order = jQuery('#orderbills').val();
    var recently = jQuery('#recently').val();
    window.location.href = "<?php echo base_url();?>tutors/studentemails?rect="+recently+"&order=" + order;*/
}
function orderBills_2()
{
     studentSearch(true);
    /*var order = 1;
    var recently = jQuery('#recently').val();
    window.location.href = "<?php echo base_url();?>tutors/studentemails?rect="+recently+"&order=" + order;*/
}
function studentSearch(noword)
{
    var student = jQuery('#studentSearch').val();
    var order = jQuery('#orderbills').val();
    var recently = jQuery('#recently').val();
    console.log(" btt: " + student.length + " noword is " + charused);
    if (student.length == 0 && charused > 2)
    {
        refresh(); 
        charused = 0;
    }
    if (noword || student.length > 2)
    {   
        charused = student.length;
        console.log('it gets here now');
       var request = $.ajax({
                             url    : "<?php echo base_url('tutors/ajaxstudents') ?>",
                             type   : "GET",
                             data   : { stustring: student, order: order, rect: recently, tutorid: <?php echo $this->session->userdata('tutorid');?> },
                             success: function(msg){
                                 if (msg != 0) jQuery('#teacherstudents').html(msg);
                                 else jQuery('#teacherstudents').html("No students to show");
                             }
                         });
                        request.fail(function(jqXHR, textStatus){
                            apprise('Something went wrong. Please try again later.', {'animate': true});
          });
    }    
}
</script>