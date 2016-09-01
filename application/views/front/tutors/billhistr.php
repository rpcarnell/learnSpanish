<?php

?>
<div class="spanish">
    <div class="spanish-content profile">
       
        <?php echo $breadcrumbs; ?><br />
        <br /><p><a href="<?php echo base_url();?>tutors/createInvoice" style="background: #fff; border: 1px solid #333; padding: 5px; text-decoration: none;">Create an Invoice</a></p>
       <br />
         <h3>Billing beginning <?php echo date('M d, Y', $firstDate);?>, ending <?php echo date('M d, Y', $lastDate);?>, for All Students</h3>
        
        <p><?php echo  $countStudents; ?> Students, $<?php echo $sumInvoices;?> Invoiced, $<?php echo $sumPaid;?> Paid, <?php echo $countRecur;?> Recurring, <?php echo $countFlex;?> Flex Time, <?php echo $quantUnpaid;?> Unpaid</p>
        <br />
        <?php   if (isset($bills) && is_array($bills)) { ?>
        <select id='orderbills' onChange='orderBills()'>
            <option value="0" <?php if ($order == 0) echo "selected"; ?>>Most Recent First</option>
            <option value="1" <?php if ($order == 1) echo "selected"; ?>>Oldest First</option>
        </select>
        <br />
        
     <br />
        
        <ul>
  <?php
if (is_array($bills))
{
    
echo "<table cellpadding='10' cellspacing='5'>";
echo "<tr><th>Student</th><th>Date</th><th>Recurring / Flex Time</th><th>Invoice / Payment</th><th>Amount</th><th>Status</th>"
. "<th>Edit</th></tr>";
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
                // $bill->tutor_id = $recurring->{'tutor-ID'};
                // $r_p = $recurring->{'minutes-per-week'};
             }
          }
          elseif ($bill->item_id == 1)
          {
              /*$flex = $Customers_model->getCustInventory($bill->profile_id);
              if ($flex) {
                  $bill->tutor_id = $flex->tutor_ID;
                  $r_p = $flex->FlexTime;*/
             // }
          }
      }
      if ($bill->item_id == 2) $ofTime = " Recurring Time";
      elseif ($bill->item_id == 1) $ofTime = "Flex Time";
      else $ofTime = ''; 
      $tutor = $tutor_model->getTutor($bill->tutor_id);
       
      $tutor = ($tutor) ? $tutor->name : 'Unknown Tutor';
      echo "<tr>";
     
      if ((int)$bill->date_paid < 1) { $unpaid = " <span style='color: #a00;'>(unpaid)</span>"; }
      else $unpaid = '';
       
      //Customers_model->getCustomer($bill->customer_id);
      echo "<td>".$Customers_model->getCustomer($bill->customer_id)->name;
      echo "</td><td>".date('M-d-Y', $bill->date_paid)."</td>";
      echo "<td>".$ofTime."</td>";
      $invoice = ($bill->date_paid == 0) ? "Invoice" : "Payment";
       echo "<td>".$invoice ."</td>";
      echo "<td>$".$bill->price_paid."</td>";
      echo "<td>".$unpaid."</td>";
      echo "<td><a href='".base_url()."tutors/editInvoice/".$bill->purchase_id."'>Edit</a></td>";
      echo "</tr>";
  }
  echo "</table>";
}
  ?>
         
        <?php
        } else echo "<p><b>There are no bills</b></p>";
        ?>
    </div></div>
<script>
function orderBills()
{
    var order = jQuery('#orderbills').val();
    window.location.href = "<?php echo base_url();?>tutors/invoices?order=" + order;
}
</script>