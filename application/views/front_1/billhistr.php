<?php
//$userMessage='test message'
/*
$itemsForSale = $this->Customers_model->getItemsForSale();
        foreach ($itemsForSale as $item)
        {
            if ($item) echo $item->item_ID.'  '.$item->name.'<p>';

        }


$single = $this->Customers_model->getItemForSale(1);
echo "Searching for 1 found: ".$single->item_ID."   ".$single->name."<br><br>";
$single = $this->Customers_model->getItemForSale("Recurring");
echo "Searching for recurring found: ".$single->item_ID."   ".$single->name;
*/
?>


<div class="spanish">
    <div class="spanish-content profile">

<?php
// if there is a message, show it at the top using userMessage formatting
if ($userMessage)
{
    echo '<div id="userMessage">';
    echo $userMessage;
    echo '</div>';
}


// if there are bills, make a button to sort them by date   
if (isset($bills) && is_array($bills))
{
    echo "<select id='orderbills' onChange='orderBills()'>";
    echo '<option value="0"';
    if ($order == 0)
        echo "selected";
    echo '>Most Recent First</option>';
    echo '<option value="1"';
    if ($order == 1)
        echo "selected";
    
    echo '>Oldest First</option>';
    echo '</select>';
    echo '<br><br>';
    
    // now create the table to put the data in with headers
    echo '
                <style>
                table, th, td {    border: 1px solid black;}
                </style>  
                <table rules="cols" frame="lhs" cellpadding="5" >
                <tr>
                    <th>Invoice</th>
                    <th>Date</th>
                    <th>Date Paid</th>
                    <th>Price</th>
                    <th>Time</th>
                    <th>Type</th>
                    <th>Tutor</th>
                </tr>
                ';    

    
    foreach ($bills as $bill)
    {
        $minutes = $Customers_model->getRecurring($bill->item_id);
        $minutes = ($minutes) ? $minutes->{'minutes-per-week'} : " NOT AVAILABLE";
        
        //      if ($bill->tutor_id == 0)
        //      {
        // !!!!!!!!!  these values should be pulled from the "items for sale" table
        if ($bill->item_id == 2) // recurring case
        {
            $recurring = $Customers_model->getRecurring($bill->profile_id);
            if ($recurring)
            {
                $bill->tutor_id = $recurring->{'tutor-ID'};
                $minutes        = $recurring->{'minutes-per-week'};
            }
        }
        elseif ($bill->item_id == 1) // flex time case
        {
            $flex = $Customers_model->getCustInventory($bill->profile_id);
            if ($flex)
            {
                $bill->tutor_id = $flex->tutor_ID;
                $minutes        = $flex->FlexTime;
            }
        }
        //      }    // if ($bill->tutor_id == 0)
        
        // create a string to describe "time", possibly minutes, or hours, or null if there is no quantity
        $time       = $minutes;
        $MinOrHours = "";
        if ($minutes >= 60)
        {
            $MinOrHours = "Hours";
            $time       = $time / 60;
        }

        if (($minutes < 60) & ($minutes > 0))
            $MinOrHours = "Minutes";

        // get descriptions for each type from the itemsForSale table, i.e. 1=flex, 2=recurring etc.
        $itemsForSale = $this->Customers_model->getItemsForSale();
        foreach ($itemsForSale as $item)
        {
            if ($item->item_ID == $bill->item_id)
                $TypeOfTime = $item->name;
        }
        
      
        $tutor = $tutor_model->getTutor($bill->tutor_id);
        $tutor = ($tutor) ? $tutor->name : '?';
        
        $color = '<font color="black">';
        
        echo "<tr>";
        echo "<td>{$bill->purchase_id}</tc>";
        echo "<td>" . $color . date('M-d-Y', $bill->date_created) . "</font></td>";
        if ($bill->date_paid > 0)
        {
            echo "<td>" . date('M-d-Y', $bill->date_paid) . "</td>";
        }
        else
        {
            echo '<td><b> <font color="#ff0000"> Unpaid </b></td>';
        }
        echo '<td>' . $color . '$';
        echo "{$bill->price_paid}</font></td>";
        $fractDigits = 0;   // if it's minutes, the fractional part is always .00, so suppress it, it's distracting
        if ($minutes >= 60)
            if ($time - intval($time) != 0)  // even if it's hours, don't show fractional part if it's .00
                $fractDigits = 2; // if it's hours, we need to see the fractional part
        $str_time = number_format(strval($time), $fractDigits);
        echo "<td>{$str_time} {$MinOrHours}</td>";
        echo "<td>$TypeOfTime</td>";
        echo "<td>$tutor{$unpaid}</td>";
        echo "</tr>";
        
        echo "</li>";
    }
    echo " ";
    echo '</table>';
}
else
    echo "<p><b>You have no bills</b></p>";
?>
  </div></div>




<script>
function orderBills()
{
    var order = jQuery('#orderbills').val();
    window.location.href = "<?php
echo base_url();
?>billing/history?order=" + order;
}
</script>