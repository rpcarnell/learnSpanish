<?php
$recently = isset($_GET['rect']) ? $_GET['rect'] : 3;

?>
<div class="spanish">
    <div class="spanish-content profile">
       
        <?php echo $breadcrumbs; ?><br /><br />
       
        <div style="clear: both;"></div>
        <div style="float: left;"><!--<p>
       <select id='orderbills' onChange='orderBills()'>
            <option value="0" <?php if ($order == 0) echo "selected"; ?>>All Students</option>
            <option value="1" <?php if ($order == 1) echo "selected"; ?>>Have Purchased Recently</option>
            <option value="2" <?php if ($order == 2) echo "selected"; ?>>Signed Up But Never Purchased</option>
            
        </select>
            </p>-->
        </div>
        <?php
        //if ($order == 1) $visible = 'visible';
        //else $visible = 'hidden';
        if (1 == 2) {
        ?>
        <div style="float: right; visibility: <?php echo $visible;?>; margin-top: 0px;" id="recentlyDiv"><p>Define <b>recently</b> as:</p><p>In the last <select id='recently' onChange='orderBills_2()'>
            <option value="3" <?php if ($recently == 3) echo "selected"; ?>>3</option>
            <option value="2" <?php if ($recently == 2) echo "selected"; ?>>2</option>
            <option value="1" <?php if ($recently == 1) echo "selected"; ?>>1</option>
        </select> months</p></div>
        <?php
        }
        ?>
        <div style="clear: both;"></div>
        <div>Tutor Name: <input type="text" onKeyUp="studentSearch(false)" id="studentSearch" /></div>
        <div style="margin: 10px;" id="teacherstudents"><br />
  <?php
  if (isset($tutors) && is_array($tutors)) {  
  if (is_array($tutors))
  {
 $emailsList = array();
  foreach ($tutors as $tutor)
  {
       
       
       $emailsList[] = $tutor->name." &lt".$tutor->email_list."&gt";//</a></td></tr>";//<td>".$student->phone."</td></tr>";
       //echo "<tr><td>".$student->name."</td><td>$student->email_list</a></td></tr>";//<td>".$student->phone."</td></tr>";
  }
      echo implode(', ', $emailsList);
  }
  ?>
            <br /><br /></div><div id='refreshDiv'><a href='javascript:void(0)' onClick='refresh()'>Refresh</a></div>
        <?php
        } else echo "<p><b>There are no bills</b></p>";
        ?>
    </div></div>
<script>
function refresh()
{
    jQuery('#refreshDiv').html('<img style="height: 60px;" src=\'<?php echo base_url()."images/circle-loading-gif.gif";?>\' alt="loading" />').css({'color' : '#a00'});
    setTimeout(function() {
        studentSearch(true);
        jQuery('#refreshDiv').css({'color' : '#000'}).html("<a href='javascript:void(0)' onClick='refresh()'>Refresh</a>");  
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
    if (noword || student.length > 2)
    {
       var request = $.ajax({
                             url    : "<?php echo base_url('admin/ajaxtutors') ?>",
                             type   : "GET",
                             data   : { stustring: student  },
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