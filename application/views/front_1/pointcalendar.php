<?php

?><div class="spanish">
    <div class="spanish-content profile">
        <?php //echo $breadcrumbs; ?>

<div style="margin-top: 40px;">
    <?php 
   if (isset($recurring) && is_array($recurring)) {
    foreach ($recurring  as $recu)
    {
        $recu->StartDate = explode(' ', $recu->StartDate);
        $recu->StartDate = explode('-', $recu->StartDate[0]);
        $recu->StartDate = $recu->StartDate[1]."-".$recu->StartDate[2]."-".$recu->StartDate[0];
        $tutor = $tutor_model->getTutor($recu->{'tutor-ID'});
  
           $gce = $Customers_model->getCalendarEntry($recu->{'customer-ID'}, $recu->{'tutor-ID'});
        if ($gce && isset($gce->day)) $day = $gce->day;
        else $day = '<span style="color: #a00;">NOT GIVEN</span>';
       
        $start_date = (isset($gce->unix_time) && is_numeric($gce->unix_time)) ? date('M-d-Y H:i:s', $gce->unix_time) : '<span style="color: #a00;">NOT GIVEN</span>';
        echo "<p>Start Date: <a href='".base_url()."profile/managSub/".$recu->{'recurring-Profile-ID'}."'>".$start_date."</a>. Day of Week: $day. Recurring Time with ".ucwords($tutor->name)."</p>";
     
        }} else echo "<p>There are no appointments to make yet.</p>";
    ?>
    <?php 
    if (is_array($inventory)) {
    foreach ($inventory  as $inven)
    {
        $inven->StartDate = '';
        $tutor = $tutor_model->getTutor($inven->{'tutor_ID'});
        
        echo "<p><a href='".base_url()."calendar/appointments?type=1&id=".$inven->{'Inventory_ID'}."'>Flex Time with ".ucwords($tutor->name)." ($inven->FlexTime minutes available)</a>";
    }}
    ?>
</div>
    </div></div>
