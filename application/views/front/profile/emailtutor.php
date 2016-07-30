<?php

?><div class="spanish">
    <div class="spanish-content profile">
        <?php echo $breadcrumbs; ?><br /><br /><br />

<div style="margin-top: 40px;">
    <?php 
    if (is_array($tutors)) {
        echo "<h2>Your Tutors:</h2>";
    foreach ($tutors  as $recu)
    {
      
        $calendar = $this->Customers_model->getCalendarEntry($recu->customer_ID, $recu->tutor_id, $recu->from_recurring);
        $skype = ($recu->skype_name) ? " | <b>Skype:</b> $recu->skype_name" : '' ;
        echo "<p><div style='padding: 2px; background: #f0f0f0; margin: 0px; margin-bottom: 5px;'><a href='".base_url()."calendar/tutorSchedule/".$recu->tutor_id."'>".$recu->name."</a> | <b>E-Mail:</b> ".$recu->email_list." ".$skype."</div>";
        if ($calendar->from_recurring == 0)
        {
            echo "You have a Flex Time appointment with this tutor at ".date('M-d-Y G:i:s', $calendar->unix_time);
        }
        elseif ($calendar->from_recurring == 1)
        {
            echo "You have a Recurring Appointment with this tutor";
        }
        echo "</p>";
    }} else echo "You have no calendar entries yet";
    ?>
    <?php 
   /* if (is_array($inventory)) {
    foreach ($inventory  as $inven)
    {
        $inven->StartDate = '';
        $tutor = $tutor_model->getTutor($inven->{'tutor_ID'});
        echo $inven->StartDate."Flex Time with ".ucwords($tutor->name);
    }}*/
    ?>
</div>
    </div></div>
