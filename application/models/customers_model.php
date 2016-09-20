<?php
class Customers_model extends CI_Model{
      public function Inventory()
      {
          $to_insert = array(
                        'unpaidTime' => sanitize_input($_POST['enterhours']),
                        'tutor_ID'     => sanitize_input($_POST['tutor_id']),
                        'MakeUpTime'        => '',
                        'Customer_ID' => sanitize_input($_POST['customer_id'])
                    );
            $where = 'Customer_ID ="'.sanitize_input($_POST['customer_id']).'" AND tutor_ID ="'.sanitize_input($_POST['tutor_id']).'"';
            $row = $this->My_model->select_where('customersInventory',  $where);
            if ($row)
            {  
                 //$to_insert['OrigTime'] = (int)$to_insert['OrigTime'] + (int)$row->OrigTime;
                 //$to_insert['FlexTime'] = (int)$to_insert['FlexTime'] + (int)$row->FlexTime;
                 $this->My_model->update_where('customersInventory', $to_insert, $where); 
                 return $row->Inventory_ID; 
            }
            else
            {
                $to_insert['OrigTime'] = $to_insert['FlexTime'] = 0;
                $this->My_model->inserting('customersInventory', $to_insert);
                return $this->db->insert_id();
            }
      }


// corky 8/22/16 creation
      public function getItemsForSale( )
      {
          $queryStr = "SELECT * FROM itemsforsale ORDER BY item_for_sale_ID LIMIT 100";

          //$queryResult = $this->db->query($queryStr); 
          if($this->db->query($queryStr)->num_rows() > 0)
          //if($queryResult->num_rows() > 0)
          {
             foreach($this->db->query($queryStr)->result() as $row) { $data[] = $row; } 
             //foreach($queryResult as $row) { $data[] = $row; }
          }
          else
          {
              $data = $queryResult;
          }
          return (isset($data)) ? $data : false;          
      }

// corky 8/22/16 creation

      public function getItemForSale($_searchFor=null )
      {
          $criteria = "";
          if (is_int ($_searchFor)) $criteria = "WHERE item_id = $_searchFor ";
          if (is_string ($_searchFor)) $criteria = 'WHERE name = "'.$_searchFor.'" ';  
          $queryStr = "SELECT * FROM itemsforsale ".$criteria."ORDER BY item_for_sale_ID LIMIT 100";



          $queryResult = $this->db->query($queryStr)->result();
          return $queryResult[0];

          if($this->db->query($queryStr)->num_rows() > 0)
          {
             foreach($this->db->query($queryStr)->result() as $row) { $data[] = $row; } 
          }
          else
          {   // this is the expected normal case
              $data = $queryResult;
          }
          return (isset($data)) ? $data : false;          
      }


      public function getPurchases($id)
      {
          if (!is_numeric($id)) return;
           
          $theOrder = (isset($_GET['order']) && is_numeric($_GET['order']) && $_GET['order'] < 2) ? $_GET['order'] : 0; 
          $DESCASC = ($theOrder == 0) ? 'DESC' : 'ASC';
          $queryStr = "SELECT * FROM purchases WHERE customer_id = $id ORDER BY purchase_id $DESCASC LIMIT 100";
          if($this->db->query($queryStr)->num_rows() > 0)
          {
             foreach($this->db->query($queryStr)->result() as $row) { $data[] = $row; } 
          }
          return (isset($data)) ? $data : false;
      }
      public function getUnPaid($id)//same as above, but for unpaid
      {
          if (!is_numeric($id)) { return; }
          $DESCASC = 'DESC';
          $queryStr = "SELECT * FROM purchases WHERE customer_id = $id AND (date_paid = 0 OR date_paid IS NULL) ORDER BY purchase_id $DESCASC LIMIT 100";
          if($this->db->query($queryStr)->num_rows() > 0)
          { foreach($this->db->query($queryStr)->result() as $row) { $data[] = $row; } }
          return (isset($data)) ? $data : false;
      }
      public function payUnPaid($id, $result)
      {
          if (!is_numeric($id)) return;
          $DESCASC = 'DESC';
          $queryStr = "SELECT * FROM purchases WHERE customer_id = $id AND (date_paid = 0 OR date_paid IS NULL) ORDER BY purchase_id $DESCASC LIMIT 100";
          if($this->db->query($queryStr)->num_rows() > 0)
          {  foreach($this->db->query($queryStr)->result() as $row) 
             { 
                  $query = "UPDATE purchases SET transactionID = '".$result->transactionID."', merchantID = '".$result->merchantID."', Braintree_CustomerID = '".$result->customerID."', date_paid='".time()."' WHERE purchase_id=".$row->purchase_id." LIMIT 1";
                  $this->db->query($query); 
             } 
          }
      }
      public function getRecurring($item_id)
      {
          if (!is_numeric($item_id)) return;
          $where = "recurring-Profile-ID = $item_id LIMIT 1";
           
          $row = $this->My_model->select_where('Customers-RecurringProfiles',  $where);
          return ($row) ? $row : false;
      }
      public function getRecurringUsed($inven_id, $apoinChange = '', $cal_id = '')
      {
          if (!is_numeric($inven_id)) return;
          if ($apoinChange > 0 && (is_numeric($cal_id) && $cal_id > 0))
          { $extra = " AND calendar_ID != $cal_id"; } else $extra = '';
          $where = "WHERE type=2 AND inven_id = $inven_id $extra";//notice the type=2. Remember, inven_id can be the same for Flex times
          $queryStr = 'SELECT * FROM `calendarEntries` '.$where;
          $data = 0;
          if($this->db->query($queryStr)->num_rows() > 0)
          { foreach($this->db->query($queryStr)->result() as $row) { if (is_numeric($row->duration)) { $data += $row->duration; } } }
          return $data;
      }
      public function getUserRecurrings($user_id)
      {
          if (!is_numeric($user_id)) return;
          $where = "WHERE `customer-ID` = $user_id ORDER BY `recurring-Profile-ID` DESC";
          $queryStr = 'SELECT * FROM `Customers-RecurringProfiles` '.$where;
          if($this->db->query($queryStr)->num_rows() > 0)
          {
             foreach($this->db->query($queryStr)->result() as $row) { $data[] = $row; } 
          }
         
          return (isset($data)) ? $data : false;
      }
      public function RecurringProfiles()//it does not get profiles. It updates or inserts them
      {
          $theDate = isset($_POST['date']) ? $_POST['date'] : '';
          if ($theDate != '')
          {
              $date = explode('/',$_POST['date']);
              $date = $date[2]."-".$date[0]."-".$date[1];
              $date = $date ." 00:00:00";
          }
          else $date = '';
          $numberMonths = (0 == $_POST['numberofmonths']) ? 7777 : $_POST['numberofmonths']; 
          $to_insert = array(
              'minutes-per-week' => sanitize_input($_POST['minutesperweek']),
              'tutor-ID'     => sanitize_input($_POST['tutor_id']),
               'customer-ID' => sanitize_input($_POST['customer_id']),
               'lenghts-months' => sanitize_input($numberMonths),
               'StartDate'        => sanitize_input($date),
           );
           
           $where = 'paid_for = 0 AND `customer-ID` ="'.sanitize_input($_POST['customer_id']).'" AND `tutor-ID` ="'.sanitize_input($_POST['tutor_id']).'"';
           $row = $this->My_model->select_where('Customers-RecurringProfiles',  $where);
            if ($row)
            {  
                $this->My_model->update_where('Customers-RecurringProfiles', $to_insert, $where); 
		return $row->{'recurring-Profile-ID'}; 
	    }
            else
            {
		$this->My_model->inserting('Customers-RecurringProfiles', $to_insert);
		return $this->db->insert_id();
	    }
      }
      public function getCustomer($customer_id)
      {
         if (!is_numeric($customer_id)) return;
         $query = "SELECT * FROM customers WHERE Customer_ID = $customer_id LIMIT 1";
         $row = $this->db->query($query)->result();
         return ($row) ? $row[0] : false;
      }
      public function getRecurringProfile($id)
      {
	   if (!is_numeric($id)) return;
	   $where = '`recurring-Profile-ID` ="'.sanitize_input($id).'"';
           $row = $this->My_model->select_where('Customers-RecurringProfiles',  $where);
           if ($row) { return $row; } 
           else return false;
      }
      public function getCustInventory($id)
      {
           if (!is_numeric($id)) return;
           $where = '`Inventory_ID` = '.$id;
           $row = $this->My_model->select_where('customersInventory',  $where);
           if ($row) { return $row; } 
           else return false;
      }
      public function recurRemain($inven_id, $customer_id)
      {   
          $query = "SELECT * FROM `Customers-RecurringProfiles` WHERE `customer-ID` = $customer_id AND `recurring-Profile-ID` = $inven_id LIMIT 1";
          $result = $this->db->query($query)->result();
          if (!$result) return false;
         
          $minutesPerWeek = (int)$result[0]->{'minutes-per-week'}; 
          $where = "WHERE type = 2 AND customer_ID = $customer_id";
          $queryStr = "SELECT * FROM `calendarEntries` $where LIMIT 50";
          
          $minutesUsed = $this->getRecurringUsed($inven_id);
          //if($this->db->query($queryStr)->num_rows() > 0) { foreach($this->db->query($queryStr)->result() as $row) {  $minutesUsed += $row->duration; } }
          $remaining = $minutesPerWeek - (int)$minutesUsed;
          return ($remaining < 0) ? 0 : $remaining;
      }
      public function getFlexData($inven_id, $customer_id = false)
      {
          if (!is_numeric($inven_id)) return false;
          $cust = ($customer_id && is_numeric($customer_id)) ? "customer_ID = $customer_id AND ": ""; 
          $query = "SELECT * FROM customersInventory WHERE $cust Inventory_ID = $inven_id LIMIT 1";
          $result = $this->db->query($query)->result();
          return ($result) ? $result : false;
      }
      public function flexRemain($inven_id, $customer_id)
      { 
          $result = $this->getFlexData($inven_id, $customer_id);
          if (!$result) return false;
          $origTime = (int)$result[0]->OrigTime;
          $where = "WHERE type = 1 AND customer_ID = $customer_id";
          $queryStr = "SELECT * FROM `calendarEntries`  $where LIMIT 50";
          $minutesUsed = 0;
          if($this->db->query($queryStr)->num_rows() > 0) { foreach($this->db->query($queryStr)->result() as $row) {  $minutesUsed += $row->duration; } }
          $remaining = $origTime - $minutesUsed;
          return ($remaining < 0) ? 0 : $remaining;
      }    
      public function emailEditError($email, $customer_id)
      {
         if (!is_numeric($customer_id)) return;
         $query = "SELECT * FROM customers WHERE email_list = '$email' AND Customer_ID != $customer_id LIMIT 1";
        // echo $query;
         $row = $this->db->query($query)->result();
         
         return ($row) ? $row[0] : false;
      }
      public function getCalendarEntry($customer_id, $tutor_id, $from_recurring = 1)
     {
         if (!is_numeric($customer_id) || !is_numeric($tutor_id)) return false;
         $query = "SELECT * FROM calendarEntries WHERE tutor_id = $tutor_id AND customer_id = $customer_id AND from_recurring = $from_recurring LIMIT 1";
         
         $row = $this->db->query($query)->result();
         return ($row) ? $row[0] : false;
     }
     
      public function futureApp($customer_ID, $hiderecur)
      {
          if ($hiderecur == 1) $data = false; 
          else {
              $where = "WHERE `customer-ID`=$customer_ID ORDER BY `recurring-Profile-ID` DESC LIMIT 100";
              $queryStr = "SELECT * FROM `Customers-RecurringProfiles` $where";
              if($this->db->query($queryStr)->num_rows() > 0)
              { 
                  foreach($this->db->query($queryStr)->result() as $row) { $data[] = $row; } 
              }
              $data = (isset($data)) ? $data : false;
          }
          $where = "WHERE `customer_ID`=$customer_ID ORDER BY `Inventory_ID` DESC LIMIT 100";
          $queryStr = "SELECT * FROM `customersInventory` $where";
          if($this->db->query($queryStr)->num_rows() > 0)
          { foreach($this->db->query($queryStr)->result() as $row2) { $data_2[] = $row2; } }
          $data_2 = (isset($data_2)) ? $data_2 : false;
          return array($data, $data_2);
      }
      public function getUser($id)
      {
          if (!is_numeric($id)) return;
          $query = "SELECT * FROM customers WHERE Customer_ID = $id LIMIT 1";
           
          if($this->db->query($query)->num_rows() > 0)
          { $row = $this->db->query($query)->result(); }
          return (isset($row) && isset($row[0])) ? $row[0] : false;
      }
      public function cronJobs()
      {
          $queryStr = "SELECT * FROM `crontasks`";
          if($this->db->query($queryStr)->num_rows() > 0) { foreach($this->db->query($queryStr)->result() as $row) {  $data[] = $row; } }
          if (!isset($data) || !is_array($data)) return;
          foreach ($data as $dat)
          {
               if (strtolower($dat->mhdmd) == 'month') { $time = 3600 * 24 * 30; }
               elseif (strtolower($dat->mhdmd) == 'week') { $time = 3600 * 24 * 7; }
               $unixTime = strtotime($dat->ran_at); 
               if ((time() - $unixTime) >= $time)
               {
                   if (method_exists('Customers_model', $dat->function)) { $this->{$dat->function}(); }
                   $date = date('Y-m-d H:i:s', time());
                   $queryStr = "UPDATE `crontasks` SET ran_at = '".$date."' WHERE id = $dat->id LIMIT 1";
                   $this->db->query($queryStr); 
               }
          }
      }
      public function getUnPurchases()
      {
           $week = 3600 * 24 ;
           $rightnow = time();
           $where = "WHERE (($rightnow - a.date_created) > $week) AND date_paid = 0";
           $queryStr = "SELECT * FROM `purchases` as a $where LIMIT 1000";
           if($this->db->query($queryStr)->num_rows() > 0) { foreach($this->db->query($queryStr)->result() as $row) {  $data[] = $row; } }
           $data = (isset($data)) ? $data : false;
           if (is_array($data)) {
           foreach ($data as $dat)
           {  
                $this->load->model('Tutors_model');
                $tutor = $this->Tutors_model->getTutor($dat->{'tutor_id'});
                $price = $dat->price_paid;
                $customer = $this->getUser($dat->customer_id);
                $minutesperweek = $this->getRecurringProfile($dat->profile_id);
                if (!is_object($minutesperweek)) continue;
                $minutesperweek = $minutesperweek->{'minutes-per-week'};
                $message = "<p>Hello ".ucwords($customer->name).",</p><p></p><p>Thank you for choosing us. We appreciate your business. We would like to notify you that a new invoice has been created for services.<p>";
                $message .= "<p>You have an oustanding balance of $".$price." for ".$minutesperweek." per week of recurring time with Tutor ".$tutor->name.".</p><p>Thanks for doing business with us. For more information visit your profile.</p>";
                $storeMail = 'corky@oklearnspanish.com';
                 
                if ($storeMail == '') echo "<div style='color: #a00'>Warning: store Email unavailable<div>";
                else {
                  $config['mailtype'] = 'html';
                  $this->email->initialize($config);
                  $this->email->from($storeMail);
                  $this->email->to($customer->email_list);  
                  $this->email->subject('Outstanding Balance');
                  $this->email->message($message);	
                  $this->email->send(); }
           } }
      }
      public function getRecurrentPayments()
      {
           $month = 30 * 24 * 3600;
           $rightnow = time();
           $where = "WHERE ($rightnow - a.last_payment) > $month";
           $queryStr = "SELECT * FROM `Customers-RecurringProfiles` as a $where LIMIT 1000";
           if($this->db->query($queryStr)->num_rows() > 0) { foreach($this->db->query($queryStr)->result() as $row) {  $data[] = $row; } }
           $data = (isset($data)) ? $data : false;
           if (is_array($data)) {
           foreach ($data as $dat)
           {  
               $queryStr = "UPDATE `Customers-RecurringProfiles` as a SET a.last_payment=".time()." WHERE `recurring-Profile-ID` = ".$dat->{'recurring-Profile-ID'}." LIMIT 1000";
               $this->db->query($queryStr); 
               $post['tutor_id'] = $dat->{'tutor-ID'};
               $post['item_id'] = 2;
               $post['appo'] = $dat->{'recurring-Profile-ID'};
               $post['amount'] = 0;
               $price = $this->calculate_price_per_min($dat->{'minutes-per-week'});
               $price = $dat->{'minutes-per-week'} * $price;
               $price = ceil($price * 4.35);
               $post['amount'] = $price;
               $this->insertPurchase($dat->{'customer-ID'}, $post, false);
               $this->mailRecCust($dat->{'customer-ID'}, $dat, $price);//mail customer
           } }
      }
      public function paidFor($customer_ID, $post)
      {
          $type = $post['item_id'];
          $profile_id = $post['appo'];
          if (!is_numeric($type) || !is_numeric($profile_id) || !is_numeric($customer_ID)) { return false; }
          $to_insert = array('payment_date' => time(), 'paid_for' => 1);
          if ($type == 2)
          {
              /*"`tutor-ID` = ".$tutor_id." AND */ 
              $where = "`recurring-Profile-ID` = ".$profile_id." AND `customer-ID` =".$customer_ID;
              $this->My_model->update_where('Customers-RecurringProfiles',  $to_insert, $where);
          }
          elseif ($type == 1)
          {
              /* `tutor_ID` = ".$tutor_id." AND  */
              $where = "`inventory_ID` = ".$profile_id." AND `customer_ID` =".$customer_ID;
              $this->My_model->update_where('customersInventory',  $to_insert, $where);
          }
      }
      public function updateTimePurchased($customer_ID, $post)
      {
          $type = $post['item_id'];
          $profile_id = $post['appo'];
          if (!is_numeric($type) || !is_numeric($profile_id) || !is_numeric($customer_ID)) { return false; }
          if ($type == 2)
          {
              /*$where = "`recurring-Profile-ID` = ".$profile_id." AND `customer-ID` =".$customer_ID;
              $this->My_model->update_where('Customers-RecurringProfiles',  $to_insert, $where);*/
          }
          elseif ($type == 1)
          {
              $where = "`inventory_ID` = ".$profile_id." AND `customer_ID` =".$customer_ID;
              $row = $this->My_model->select_where('customersInventory',  $where);
              if (!$row) { echo "<div style='color: #a00; font-weight: bold;'>ERROR - Unable to update customersInventory. Contact administrator immediately</div>"; return; } 
              $to_insert = array('unpaidTime' => 0, 'FlexTime' => ($row->unpaidTime + $row->FlexTime), 'OrigTime' => ($row->unpaidTime + $row->OrigTime) ) ;
              $this->My_model->update_where('customersInventory',  $to_insert, $where);
          }
      }
      public function insertPurchase($customer_ID, $post, $paid = true, $brainresult = '')
      {
          $to_insert = array();
          $to_insert['item_id'] = $post['item_id'];
          $to_insert['profile_id'] = $post['appo'];
          $to_insert['customer_id'] = $customer_ID;
          $this->db->where($to_insert);
          $this->db->from('purchases');
	  $result = $this->db->get();
          if ($result->num_rows() < 1) {    
             $to_insert['price_paid'] = $post['amount'];
             $to_insert['date_created'] = time();
             $to_insert['tutor_id'] = isset($post['tutor_id']) ? $post['tutor_id'] : 0;
             $to_insert['date_paid'] = ($paid) ? time() : 0;
             $to_insert['notes'] = '';
             $to_insert['viewed'] = 0;
             $to_insert['type'] = $post['item_id'];
             $to_insert['Braintree_customerID'] = ($paid && $brainresult != '' && isset($brainresult->customerID)) ? $brainresult->customerID : '';
             $to_insert['merchantID'] = ($paid && $brainresult != '' && isset($brainresult->merchantID)) ? $brainresult->merchantID : '';
             $to_insert['transactionID'] = ($paid && $brainresult != '' && isset($brainresult->transactionID)) ? $brainresult->transactionID : '';
             $this->My_model->inserting('purchases', $to_insert); }
      }
      private function mailRecCust($customer_ID, $data, $price)
      {
          $this->load->model('Tutors_model');
          $tutor = $this->Tutors_model->getTutor($data->{'tutor-ID'});
         
          $customer = $this->getUser($customer_ID);
          $message = "<p>Hello ".ucwords($customer->name).",</p><p></p><p>Thank you for choosing us. We appreciate your business. We would like to notify you that a new invoice has been created for services.<p>";
          $message .= "<p>You have an oustanding balance of $".$price." for ".$data->{'minutes-per-week'}." per week of recurring time with Tutor ".$tutor->name.".</p><p>Thanks for doing business with us. For more information visit your profile.</p>";
          $storeMail = 'corky@oklearnspanish.com';
          if ($storeMail == '') echo "<div style='color: #a00'>Warning: store Email unavailable<div>";
          else {
              $config['mailtype'] = 'html';
              $this->email->initialize($config);
	      $this->email->from($storeMail);
              $this->email->to($customer->email_list);  
              $this->email->subject('Outstanding Balance');
              $this->email->message($message);	
              $this->email->send(); }
      }
      public function calculate_price_per_min($permin)
      {  
          $query   = "SELECT quantity, price FROM prices WHERE type = 'recurring' ORDER BY quantity ASC";
          if($this->db->query($query)->num_rows() > 0)
          {
              $y = array();
              $x = array();
              $a = 0; $b = 0;
              foreach($this->db->query($query)->result() as $row)
              {
                 $y[$a] = $row->price; $x[$a] = $row->quantity;
                 $a++;
              }
              $maxPrice = $y[0];
              foreach ($y as $yone)
              {
                  if ($x[$b] <= $permin) $maxPrice = $yone;
                  $b++;
              }
             return $maxPrice; 
            /*  
              
              $m = $this->linear_regression($x, $y);
              $res = $permin * ($m['m']);
              $res = $m['b'] + $res;
             
              return $res;*/
         } else return false;
      }
      public function getUserTutors($customer_id, $nameOrder = false)
      {
          $extraWhere = '';
          //The time part of the query is based on this:
          //SELECT * FROM  `calendarEntries` WHERE UNIX_TIMESTAMP( start_time ) BETWEEN ( UNIX_TIMESTAMP() - (24 * 3600 * 44) ) AND UNIX_TIMESTAMP() 
          if (isset($_POST['bookedSince']) && $_POST['bookedSince'] > 0)
          {
              if ($_POST['bookedSince'] == 1) $days = 365;
              else $days = 90;
              $extraWhere = "AND ( UNIX_TIMESTAMP( a.start_time ) BETWEEN ( UNIX_TIMESTAMP() - (24 * 3600 * $days) ) AND UNIX_TIMESTAMP() )";
          }
          $orderName = ($nameOrder === false) ? "GROUP BY a.tutor_id" : "GROUP BY b.name";
          
          $query = "SELECT a.calendar_ID, a.customer_ID, a.from_recurring, a.tutor_id, b.skype_name, b.email_list, b.name FROM calendarEntries as a INNER JOIN tutors as b ON b.tutor_ID = a.tutor_id WHERE a.customer_id = $customer_id $extraWhere $orderName";
          $y = array();
           
          if ($this->db->query($query)->num_rows() > 0) {
            foreach ($this->db->query($query)->result() as $row) { $y[]= $row; }
          } else $y = false;
          return $y;
      }
      public function calculate_price_per_Flex($permin)
      {  
          //$permin = 10;
          $query   = "SELECT quantity, price FROM prices WHERE type = 'anytime' ORDER BY quantity ASC";
          $num_rows = $this->db->query($query)->num_rows();
          if($num_rows > 0)
          {
              $y = array(); $x = array();
              $a = 0; $b = 0;
              foreach($this->db->query($query)->result() as $row)
              {
                  $y[$a] = $row->price; $x[$a] = $row->quantity;
                  $a++; 
              }
              $maxPrice = $y[0];
              foreach ($y as $yone)
              {
                  if ($x[$b] <= $permin) $maxPrice = $yone;
                  $b++;
              }
              if (!is_numeric($maxPrice) || $maxPrice == 0) { echo "ERROR - unable to get proper price"; } 
             return $maxPrice;   
          } else return false;
      }
      /*private function linear_regression($x, $y) //DEPRECATED!!!!!!!!!!!!!!!!!!!
      {
              // calculate number points
              $n = count($x);

              // ensure both arrays of points are the same size
              if ($n != count($y)) {

                trigger_error("linear_regression(): Number of elements in coordinate arrays do not match.", E_USER_ERROR);

              }

              // calculate sums
              $x_sum = array_sum($x);
              $y_sum = array_sum($y);

              $xx_sum = 0;
              $xy_sum = 0;

              for($i = 0; $i < $n; $i++) {

                $xy_sum+=($x[$i]*$y[$i]);
                $xx_sum+=($x[$i]*$x[$i]);

              }

              // calculate slope
              $m = (($n * $xy_sum) - ($x_sum * $y_sum)) / (($n * $xx_sum) - ($x_sum * $x_sum));

              // calculate intercept
              $b = ($y_sum - ($m * $x_sum)) / $n;

              // return result
              return array("m"=>$m, "b"=>$b);
      }*/
      public function firstDate($id, $tutor_id)
     {
          if (is_numeric($tutor_id)) $tutor_id = "tutor_id = $tutor_id AND ";
          $query = "SELECT * FROM purchases WHERE $tutor_id customer_id = $id ORDER BY date_created ASC LIMIT 1";
          $data = $this->db->query($query)->result();
          return isset($data[0]->date_created) ? $data[0]->date_created : false;
     }
     public function lateDate($id, $tutor_id)
     {
         if (is_numeric($tutor_id)) $tutor_id = "tutor_id = $tutor_id AND ";
          $query = "SELECT * FROM purchases WHERE $tutor_id customer_id = $id ORDER BY date_created DESC LIMIT 1";
          $data = $this->db->query($query)->result();
           return isset($data[0]->date_created) ? $data[0]->date_created : false;
     }
     public function sumInvoices($id, $tutor_id, $DatesJSON)//this is for tutors. Different than the one in customers_model.php
     {
         if (is_numeric($tutor_id)) { $tutor_id = "tutor_id = $tutor_id AND "; }
         
         if ($DatesJSON != '')
         {
            $DatesJSON = json_decode($DatesJSON);        
            $dateDat['DatesJSON_1'] =  $DatesJSON[0];
            $dateDat['DatesJSON_2'] =  $DatesJSON[1];
            if (!isset($dateDat['DatesJSON_1']) || $dateDat['DatesJSON_1'] == '' || $dateDat['DatesJSON_1'] == 0) { $dateDat['DatesJSON_1'] = 0; }
         if (!isset($dateDat['DatesJSON_2']) || $dateDat['DatesJSON_2'] == '' || $dateDat['DatesJSON_2'] == 0) { $dateDat['DatesJSON_2'] = time(); }
            $date_created = "AND date_created > ".$dateDat['DatesJSON_1']." AND date_created < ".$dateDat['DatesJSON_2'];
         } else { $date_created = ''; }
         $theTime = (isset($_POST['theTime'])) ? $_POST['theTime'] : '';
         if ($theTime == 2)
         {  
              $month = 3600 * 24 * 30;
              $month = time() - ($month);
              $date_created = " AND (date_created BETWEEN ".$month." AND ".time().")";
         }
         elseif ($theTime == 1)
         {
              $month = 3600 * 24 * 30;
              $month = time() - $month;
              $month2 = time() - ($month * 2);
              $date_created = " AND (date_created BETWEEN $month2 AND ".$month.")";
         }
         if (is_numeric($id)) { $customer_id = "customer_id = $id AND "; }
         else { $customer_id = ''; }
         $queryStr = "SELECT * FROM purchases WHERE purchase_id > 0 AND $customer_id $tutor_id date_paid = 0 $date_created";
        
         // echo $queryStr;
         $data = 0;
         if($this->db->query($queryStr)->num_rows() > 0)
         { foreach($this->db->query($queryStr)->result() as $row) { $data += (float)$row->price_paid; } }
         return $data;
     }
     public function sumPaid($id, $tutor_id, $DatesJSON)//this is for tutors. Different than the one in customers_model.php
     {
         if (is_numeric($tutor_id)) { $tutor_id = "tutor_id = $tutor_id AND "; }
         if ($DatesJSON != '')
         {
            $DatesJSON = json_decode($DatesJSON);        
            $dateDat['DatesJSON_1'] =  $DatesJSON[0];
            $dateDat['DatesJSON_2'] =  $DatesJSON[1];
            $date_created = "AND date_created > ".$dateDat['DatesJSON_1']." AND date_created < ".$dateDat['DatesJSON_2'];
         } else { $date_created = ''; }
         $theTime = (isset($_POST['theTime'])) ? $_POST['theTime'] : '';
         if ($theTime == 2)
         {  
              $month = 3600 * 24 * 30;
              $month = time() - ($month);
              $date_created = " AND (date_created BETWEEN ".$month." AND ".time().")";
         }
         elseif ($theTime == 1)
         {
              $month = 3600 * 24 * 30;
              $month = time() - $month;
              $month2 = time() - ($month * 2);
              $date_created = " AND (date_created BETWEEN $month2 AND ".$month.")";
         }
         if (is_numeric($id)) { $customer_id = "customer_id = $id AND "; }
         else { $customer_id = ''; }
         $queryStr = "SELECT * FROM purchases WHERE purchase_id > 0 AND $tutor_id $customer_id date_paid != 0 $date_created";
          
         $data = 0;
         if($this->db->query($queryStr)->num_rows() > 0)
         { foreach($this->db->query($queryStr)->result() as $row) { $data += (float)$row->price_paid; } }
         return $data;
     }
     public function countUnpaid($id, $tutor_id, $DatesJSON)//this is for tutors. Different than the one in customers_model.php
     {
         
         if ($DatesJSON != '')
         {
            $DatesJSON = json_decode($DatesJSON);        
            $dateDat['DatesJSON_1'] =  $DatesJSON[0];
            $dateDat['DatesJSON_2'] =  $DatesJSON[1];
            $date_created = "AND date_created > ".$dateDat['DatesJSON_1']." AND date_created < ".$dateDat['DatesJSON_2'];
         } else { $date_created = ''; }
         $theTime = (isset($_POST['theTime'])) ? $_POST['theTime'] : '';
         if ($theTime == 2)
         {  
              $month = 3600 * 24 * 30;
              $month = time() - ($month);
              $date_created = " AND (date_created BETWEEN ".$month." AND ".time().")";
         }
         elseif ($theTime == 1)
         {
              $month = 3600 * 24 * 30;
              $month = time() - $month;
              $month2 = time() - ($month * 2);
              $date_created = " AND (date_created BETWEEN $month2 AND ".$month.")";
         }
         if (is_numeric($id)) { $customer_id = "customer_id = $id AND "; }
         else { $customer_id = ''; }
         if (is_numeric($tutor_id)) $tutor_id = "tutor_id = $tutor_id AND ";
         $queryStr = "SELECT count(*) as quant FROM purchases WHERE purchase_id > 0 AND $tutor_id $customer_id date_paid = 0 $date_created";
         //echo $queryStr;
         $data = $this->db->query($queryStr)->result();
         return $data[0]->quant;
     }
     public function countRecur($id, $tutor_id, $DatesJSON) { return $this->_countItem($id, 2, $tutor_id, $DatesJSON); }
     public function countFlex($id, $tutor_id, $DatesJSON) { return $this->_countItem($id, 1, $tutor_id, $DatesJSON); }
     private function _countItem($id, $item_id, $tutor_id, $DatesJSON)//this is for customers. Different than the one in tutors_model.php
     {
          list($customer_id, $tutor, $date_created) = $this->__formItemQuery($DatesJSON, $id, $tutor_id, $_POST);
          $queryStr = "SELECT count(*) as quant FROM purchases WHERE purchase_id > 0 AND $customer_id $tutor item_id = $item_id $date_created";
         // echo $queryStr;
          $data = $this->db->query($queryStr)->result();
          return $data[0]->quant;
     }
     private function __formItemQuery($DatesJSON, $id, $tutor_id, $post)
     {
          $month = time() - (3600 * 24 * 30);
          if ($DatesJSON != '')
          {
             $DatesJSON = json_decode($DatesJSON);        
             $dateDat['DatesJSON_1'] =  $DatesJSON[0];
             $dateDat['DatesJSON_2'] =  $DatesJSON[1];
             $date_created = "AND date_created > ".$dateDat['DatesJSON_1']." AND date_created < ".$dateDat['DatesJSON_2'];
          } else { $date_created = ''; }
          $theTime = (isset($post['theTime'])) ? $post['theTime'] : '';
          if ($theTime == 2) { $date_created = " AND (date_created BETWEEN ".$month." AND ".time().")"; }
          elseif ($theTime == 1)
          {
              $month2 = time() - ($month * 2);
              $date_created = " AND (date_created BETWEEN $month2 AND ".$month.")";
          }
          if (is_numeric($id)) { $customer_id = "customer_id = $id AND "; }
          else { $customer_id = ''; }
          if (is_numeric($tutor_id)) { $tutor_id = "tutor_id = $tutor_id AND "; }
          return array($customer_id, $tutor_id, $date_created);
     }
    
}
?>
