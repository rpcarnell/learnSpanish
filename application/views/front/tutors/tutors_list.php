<div class="spanish">
     <div class="home-main-content"><?php echo $breadcrumbs; ?></div>
    <div class="spanish-content contact-us-content">
       
<?php
echo "<br />";
$purchase = $this->session->userdata('purchase');
if ($purchase == 3) { $link = "billing/custom/"; }
elseif ($purchase == 2) { $link = "billing/recurring/"; }
else { $link = "billing/buyFlexTime/"; }

if (is_array($tutors))
{
	foreach ($tutors as $tutor)
	{
		echo "<div class='tutorlayer'>";
                echo "<img class='tutorpic' src='".base_url()."uploads/tutors/".$tutor->photo."' style='float: left; margin: 5px; height: 150px; max-width: 150px;' />";
		echo "<div style='float: right; margin: 30px 0x;'><a href='".base_url()."$link".$tutor->tutor_ID."'>Select <img src='".base_url()."images/selecticon.png' style='height: 30px;' /></a></div>";
		echo "<div><p>Name: <b>".ucwords($tutor->name)."</b></p></div>";
		
		echo "<div style='margin: 10px; width: 90%;'><p>".ucwords($tutor->bio)."</p></div>";
                ?>
        <a href='<?php echo base_url()."calendar/tutorSchedule/".$tutor->tutor_ID;?>' style="text-decoration: none;">View Openings</a>
        <?php
		echo "<div style='clear: both;'></div></div><div style='clear: both; margin-bottom: 5px;'></div>";
	}
        echo "<div class='pages'>$pages</div>";
}

?>
    </div></div>
<script>
function showOpenings()
{
	alert('Openings are here'); 
}
</script>
