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
    /*
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
    */
    // now create the table to put the data in with headers
    echo '
                <style>
                table, th, td {    border: 1px solid black;}
                </style>  
                <table rules="cols" frame="lhs" cellpadding="5" class="sortable">
                <thead>
                <tr>
                    <th>Invoice</th>
                    <th>Date</th>
                    <th>Date Paid</th>
                    <th>Price</th>
                    <th>Time</th>
                    <th>Type</th>
                    <th>Tutor</th>
                </tr>
                </thead>
                <tbody>
                ';    

    
    foreach ($bills as $bill)
    {
        $minutes = $Customers_model->getRecurring($bill->item_id);
        $minutes = ($minutes) ? $minutes->{'minutes-per-week'} : " NOT AVAILABLE";
        
        // I don't understand yet why Roddy had this if block..., or for that matter where it might have been set prior to coming here.
        //      if ($bill->tutor_id == 0)
        //      {
        // !!!!!!!!!  these values should be pulled from the "items for sale" table
        
        if ($bill->item_id == 2) // recurring case
        {
            $recurring = $Customers_model->getRecurring($bill->profile_id);
            if ($recurring)
            {
                $bill->tutor_id = $recurring->{'tutor-ID'};
                //$bill->tutor_id = $recurring->tutor-ID;
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
        

      
        $tutor = $tutor_model->getTutor($bill->tutor_id);
        $tutor = ($tutor) ? $tutor->name : '?';
           
        echo "<tr>";
        echo "<td>{$bill->purchase_id}</tc>";
        echo "<td>"  . date('M-d-Y', $bill->date_created) . "</font></td>";
        if ($bill->date_paid > 0)
        {
            echo "<td>" . date('M-d-Y', $bill->date_paid) . "</td>";
        }
        else
        {
            echo '<td><b> <font color=red> Unpaid </b></td>';
        }

        echo '<td> $' . "{$bill->price_paid}</td>";        
        // create a string to describe "time", possibly minutes, or hours, or null if there is no quantity
        $time       = $minutes . " min. ";
        if ($minutes >= 60)
        {
            $digits = 0;
            $fract = 0;
            $fract = (floatval($minutes/60.0) - intval($minutes/60.0));
            if ($fract) 
                $digits = 2;  
            $time = $time .'(' . number_format(($minutes/60.0),$digits) . ' hrs.)';
        }
        if ($bill->item_id == 3) $time = "";
        echo "<td>$time</td>";         // for purchases that have an amount of time show that

        // get descriptions for each type from the itemsForSale table, i.e. 1=flex, 2=recurring etc.
        $itemsForSale = $this->Customers_model->getItemsForSale();
        foreach ($itemsForSale as $item)
        {
            if ($item->item_ID == $bill->item_id)
                $TypeOfTime = $item->name;
        }
        echo "<td>$TypeOfTime</td>";
        echo "<td>$tutor</td>";
        echo "</tr>";
        
        echo "</li>";
    }
    echo " ";    
    echo '
    </tbody>
    </table>';
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