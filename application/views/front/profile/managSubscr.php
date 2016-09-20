<?php

?><div class="spanish">
    <div class="spanish-content profile">
        
<?php         if (is_array($recurring)  ) { ?>
        
<?php } ?>
<div style="margin-top: 40px;">
    <?php  
    if (is_array($recurring)  ) {
    foreach ($recurring  as $recu)
    {  
        $recu->StartDate = explode(' ', $recu->StartDate);
        $recu->StartDate = explode('-', $recu->StartDate[0]);
        $recu->StartDate = $recu->StartDate[1]."-".$recu->StartDate[2]."-".$recu->StartDate[0];
        $tutor = $tutor_model->getTutor($recu->{'tutor-ID'});

         $recurring = $Customers_model->getRecurringProfile($recu->{'recurring-Profile-ID'});
         if (! $recurring) { echo "ERROR - recurring profile is invalid. Exiting"; exit; }
        /// $entry = $this->Customers_model->getCalendarEntry($customer_ID, $recurring->{'tutor-ID'}, 1);
 
        // echo "<br />--->".$entry->inven_id;
        
         $recurntry = $Customers_model->getRecurring($recu->{'recurring-Profile-ID'});
         //print_r($recurntry);
         $used = $Customers_model->getRecurringUsed($recu->{'recurring-Profile-ID'});
        // echo "uased is $used";
         $notUsed= (int)$recurntry->{'minutes-per-week'} - (int)$used;
         if ($notUsed == 0) { $avilcol = '#a00';}
         else { $avilcol = '#000';}
        // echo "alreadyUsed is $alreadyUsed";
        // $Customers_model->getRecurringUsed($recu->{'recurring-Profile-ID'});
       /// $onlyhave = (int)$recurntry->{'minutes-per-week'} - $Customers_model->getRecurringUsed($recu->{'recurring-Profile-ID'});
       //**** $gce = $Customers_model->getCalendarEntry($recu->{'customer-ID'}, $recu->{'tutor-ID'});
       //*** if ($gce && isset($gce->day)) $day = $gce->day;
        //****else $day = '<span style="color: #a00;">NOT GIVEN</span>';
       
       //****** $start_date = (isset($gce->unix_time) && is_numeric($gce->unix_time)) ? date('M-d-Y H:i:s', $gce->unix_time) : '<span style="color: #a00;">NOT GIVEN</span>';
        ///echo "<div class='billhistr'>Start Date: <a href='".base_url()."profile/managSub/".$recu->{'recurring-Profile-ID'}."'>".$start_date."</a>. Day of Week: $day. Recurring Time with ".ucwords($tutor->name)."</div>";
        echo "<div class='billhistr'><a href='".base_url()."profile/managSub/".$recu->{'recurring-Profile-ID'}."'>Recurring Time with ".ucwords($tutor->name)."</a> Minutes Per Week: ". (int)$recurntry->{'minutes-per-week'}." | <span style='color: {$avilcol};'>Minutes Still Available: ".$notUsed."</span></div>";
    }} else echo "<p>Sorry - there are no recurring appointments at the moment</p>";
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
