<?php

?>
<div class="spanish">
    <div class="spanish-content profile">
          <?php if ($userMessage) { ?><div id="userMessage"><?php echo $userMessage; ?></div><?php } ?>
        <?php   if (isset($bills) && is_array($bills)) { ?>
        <select id='orderbills' onChange='orderBills()'>
            <option value="0" <?php if ($order == 0) echo "selected"; ?>>Most Recent First</option>
            <option value="1" <?php if ($order == 1) echo "selected"; ?>>Oldest First</option>
        </select>
        <ul>
  <?php

  foreach ($bills as $bill)
  {
       
      
      $r_p = $Customers_model->getRecurring($bill->item_id);
     
      $r_p = ($r_p) ? $r_p->{'minutes-per-week'} : " NOT AVAILABLE";
       
      if ($bill->tutor_id == 0)
      {
          if ($bill->item_id == 2)
          {
             $recurring = $Customers_model->getRecurring($bill->profile_id);
             if ($recurring) 
             {
                 $bill->tutor_id = $recurring->{'tutor-ID'};
                 $r_p = $recurring->{'minutes-per-week'};
             }
          }
          elseif ($bill->item_id == 1)
          {
              $flex = $Customers_model->getCustInventory($bill->profile_id);
              if ($flex) {
                  $bill->tutor_id = $flex->tutor_ID;
                  $r_p = $flex->FlexTime;
              }
          }
      }
      if ($bill->item_id == 2) $ofTime = "$r_p minutes of Recurring Time";
      elseif ($bill->item_id == 1) $ofTime = "Flex Time";
      else $ofTime = ''; 
      $tutor = $tutor_model->getTutor($bill->tutor_id);
       
      $tutor = ($tutor) ? $tutor->name : 'Unknown Tutor';
      echo "<li class='billhistr' style='margin-bottom: 10px; padding: 5px;'>";
      
      if ((int)$bill->date_paid < 1) { $unpaid = " <span style='color: #a00;'>(unpaid)</span>"; }
      else $unpaid = '';
      echo "On ".date('M-d-Y', $bill->date_paid).", you were billed \${$bill->price_paid} for <i>$ofTime</i> with $tutor{$unpaid}.";
      echo "</li>";
  }
  
  ?>
        </ul>
        <?php
        } else echo "<p><b>You have no bills</b></p>";
        ?>
    </div></div>
<script>
function orderBills()
{
    var order = jQuery('#orderbills').val();
    window.location.href = "<?php echo base_url();?>billing/history?order=" + order;
}
</script>