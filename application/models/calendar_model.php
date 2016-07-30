<?php
class Calendar_model extends CI_Model
{
     public function findIntercepts($inven_id, $cal_id, $unixTime, $duration, $unixStart,  $unixEnd, $customer_id, $tutor_id, $type)
     {
         $pureStart = date('Y-m-d', $unixStart);
         $this->load->model('Customers_model');
         $this->load->library('intercepts', array('calendar' => $this, 'customers' => $this->Customers_model));
         $msg = $this->intercepts->getIntercepts($inven_id, $cal_id, $pureStart, $customer_id, $tutor_id, $duration, $unixTime, $unixStart,  $unixEnd, $type);
         return $msg;
     }
     public function UserNonRecurring($start, $end, $customer_ID)
     {
         $where = 'WHERE from_recurring = 0 AND customer_ID = "'.$customer_ID.'" AND (STR_TO_DATE(`start_time`,"%Y-%m-%d") BETWEEN "'.$start.'" AND "'.$end.'")'; 
         $queryStr      = "SELECT a.calendar_ID, a.inven_id, a.details,a.customer_ID, a.title, a.start_time as start, a.unix_time, a.duration FROM calendarEntries as a " . $where . "";//ORDER BY id DESC LIMIT {$start}, {$config['per_page']}";
         $data = array();
         if($this->db->query($queryStr)->num_rows() > 0)
         {
            foreach($this->db->query($queryStr)->result() as $row){
                $row->start = date('Y-m-d H:i:s', $row->unix_time);
                $row->end = $row->unix_time + (60 * $row->duration);
                $row->end = date('Y-m-d H:i:s', $row->end);
                    $row->color = "#879581";
                    $row->editable ="true";
                    $this->load->model('Tutors_model');
                    if (isset($row->tutor_ID)) $tutor = $this->Tutors_model->getTutor($row->tutor_ID);
                    else $tutor = false;
                    if ($tutor) $row->title = $row->details.": ".$tutor->name;
                    else $row->title = $row->details;
                $data[] = $row;
             }
          }
          return $data;
     }
     public function nonRecurringDays($start, $end, $customer_ID = '', $tutor_id = '')
     {
         $where = 'WHERE from_recurring = 0 AND (STR_TO_DATE(`start_time`,"%Y-%m-%d") BETWEEN "'.$start.'" AND "'.$end.'")'; 
         //else $where = '';
        // if (isset($tutor_id) && is_numeric($tutor_id)) { $where .= ' AND tutor_ID='.$tutor_id; }
         $queryStr      = "SELECT a.tutor_ID, a.calendar_ID, a.inven_id, a.type, a.details,a.customer_ID, a.title, a.start_time as start, a.unix_time, a.duration FROM calendarEntries as a " . $where . "";//ORDER BY id DESC LIMIT {$start}, {$config['per_page']}";
         $data = array();
         //echo $queryStr; exit;
         $adminCredentials = $this->session->userdata('adminCredentials');
         $admin_id = $adminCredentials['admin_id'];
         $tutor_array = '';
         $students_array = '';
         //the filter below is for students:
         if (is_numeric($customer_ID) && ! is_numeric($tutor_id)){ $tutor_array = $this->_getCustomerTutors($customer_ID); /*$this->_getFlexTutors($customer_ID);*/ }
         elseif (!is_numeric($customer_ID) && is_numeric($tutor_id)) {  $students_array = $this->_getTutorStuds($tutor_id);   }
         if($this->db->query($queryStr)->num_rows() > 0)
         {
            foreach($this->db->query($queryStr)->result() as $row)
            {
                $row = $this->_formatNonRecurring($customer_ID, $tutor_id, $row, $students_array, $tutor_array, $admin_id);
                if ($row === false) continue;
                $data[] = $row;
             }
          }
         return $data;
     }
     public function userRecurringDays($start, $customer_id)
     {
           $where = 'WHERE from_recurring = 1 AND customer_ID = '.$customer_id;
           $data = array();
           $sun = strtotime($start." 00:00:00");
           $day = 3600 * 24;
           $queryStr      = "SELECT a.calendar_ID, a.inven_id, a.customer_ID, a.tutor_ID, a.title, a.unix_time, a.start_time as start, day, time, duration FROM calendarEntries as a " . $where . "";//ORDER BY id DESC LIMIT {$start}, {$config['per_page']}";
 
           if($this->db->query($queryStr)->num_rows() > 0)
             {
                
                foreach($this->db->query($queryStr)->result() as $row){
                   
                          $row = $this->formatRecurringData($row, $customer_id, $start);
                      if ($row) $data[] = $row;
                 }
          }
          return $data;   
     }
    
     protected function _filterbyDuration($row, $start)
     {
         if (!is_numeric($row->inven_id)) { return false; }
         
         $queryStr  = "SELECT * FROM `Customers-RecurringProfiles` WHERE `recurring-Profile-ID` = ".$row->inven_id." LIMIT 1";
         $row_2 = $this->db->query($queryStr)->result();
         if (isset($row_2[0]))
         { 
             if ($row_2[0]->{'lenghts-months'} == 7777) $row_2[0]->{'lenghts-months'} = 100;//we don't need that many months
             $months = (float)$row_2[0]->{'lenghts-months'} * 30 * 24 * 3600;
             $end = $row->unix_time + $months;
             if (time() > ($end * 30 * 24 *3600 * 90)) echo "older than three months";
             if ($start > $end) {  return false; }
             else return true;
         }
         return false;
     }
     public function recurringDays($start, $customer_ID = '', $tutor_id = '')
     {  
           $sun = strtotime($start." 00:00:00")  + (3600 * 24 * 7);//let's say two days before the given time
           $where = 'WHERE unix_time < '.$sun.' AND from_recurring = 1';
           $data = array();  
           if (isset($tutor_id) && is_numeric($tutor_id)) { $where .= ' AND tutor_ID='.$tutor_id; }
           
           $queryStr      = "SELECT a.calendar_ID, a.inven_id, a.type, a.calendar_ID, a.customer_ID, a.tutor_ID, a.title, a.unix_time, a.start_time as start, day, time, duration FROM calendarEntries as a " . $where . "";//ORDER BY id DESC LIMIT {$start}, {$config['per_page']}";
 
           if($this->db->query($queryStr)->num_rows() > 0)
             {
                    if (is_numeric($customer_ID) && $customer_ID > 0)  
                    { $tutors_array = $this->_getCustomerTutors($customer_ID); }//$this->_getRecurTutors($customer_ID);
                    elseif($tutor_id) { $students_array = $this->_getTutorStuds($tutor_id); }
                    foreach($this->db->query($queryStr)->result() as $row){
                    
                    if (is_numeric($customer_ID) && $customer_ID > 0) 
                    {   //this is a format for students:
                        if ($tutors_array && ! in_array($row->tutor_ID, $tutors_array )) continue;
                        $row = $this->formatRecurringData($row, $customer_ID, $start, $tutors_array);
                        if ($row === false) { continue; }
                    }
                    elseif($tutor_id) { 
                        if ($students_array && ! in_array($row->customer_ID, $students_array )) continue;
                        $row = $this->formatTutorData($row, $tutor_id, $start); 
                        if ($row === false) { continue; }
                    }
                    else 
                    {    //this is for administrators:
                       $adminCredentials = $this->session->userdata('adminCredentials');
                       $id = $adminCredentials['admin_id'];
                       if (is_numeric($id) && $id > 0) { $this->formatTutorData($row, '', $start, $id); }
                    }
                    $data[] = $row;
                 }
          }
         
          return $data;    
     }
     private function formatTutorData($row, $tutor_ID, $start, $admin_id = '')//this is for students
     {  
            $sun = strtotime($start." 00:00:00");
            $day = 3600 * 24;
            if ( $this->_filterbyDuration($row, $sun) == false) { return false; }
                   $row->color="#285698"; 
                   $row->textColor ="#ffffff"; 
                   $row->durationEditable = true; 
                   $row->startEditable = true; 
                   $this->load->model('Customers_model');
                   $customer = $this->Customers_model->getCustomer($row->customer_ID);
                   if (!is_numeric($tutor_ID) && is_numeric($admin_id) && $admin_id > 0) 
                   { 
                       $this->load->model('Tutors_model');
                       $tutor_model = $this->Tutors_model;
                       $tutor = $tutor_model->getTutor($row->tutor_ID);
                       $tutorInfo = " - Teacher: ".$tutor->name;
                       $row->title = "Student: ".ucwords($customer->name).$tutorInfo;
                   }  
                   else $row->title = "Recurring:\n\nStudent: ".ucwords($customer->name);
                   
                    $row->day = strtolower( date('D',  $row->unix_time ) ); //strtolower($row->day);
                    $row->time = date('H:i:s',  ( $row->unix_time  ) );
                    if ($row->day == 'sun') { $row->start = date('Y-m-d', ($sun + (0 * $day) )); } 
                    elseif ($row->day == 'mon') { $row->start = date('Y-m-d', ($sun + (1 * $day) )); } 
                    elseif ($row->day == 'tue') { $row->start = date('Y-m-d', ($sun + (2 * $day) )); } 
                    elseif ($row->day == 'wed') { $row->start = date('Y-m-d', ($sun + (3 * $day) )); } 
                    elseif ($row->day == 'thu') { $row->start = date('Y-m-d', ($sun + (4 * $day) )); } 
                    elseif ($row->day == 'fri') { $row->start = date('Y-m-d', ($sun + (5 * $day) )); } 
                    elseif ($row->day == 'sat') { $row->start = date('Y-m-d', ($sun + (6 * $day) )); } 
                     
                    $row->start = $row->start." ".$row->time;
                    $row->end = strtotime($row->start) + (60 * $row->duration);
                    $row->end = date('Y-m-d H:i:s', $row->end);
                    return $row;
     }
     private function _formatNonRecurring($customer_ID, $tutor_id, $row, $students_array, $tutor_array, $admin_id)
     {
         
           
          
          if (is_numeric($customer_ID) && ! is_numeric($tutor_id)) //this filter is for students:
            {  if (! $tutor_array || ! in_array($row->tutor_ID, $tutor_array))  { return false; } }
            
            elseif (!is_numeric($customer_ID) && is_numeric($tutor_id)) //this filter is for tutors:
            { if (! $students_array && ! in_array($row->customer_ID, $students_array)) {  return false; } }
            
             $this->load->model('Tutors_model');
                $row->start = date('Y-m-d H:i:s', $row->unix_time);
                $row->end = $row->unix_time + (60 * $row->duration);
                $row->end = date('Y-m-d H:i:s', $row->end);
                if (is_numeric($admin_id) && 0 < $admin_id)
                {
                     $row->textColor ="#ffffff";
                    
                    $row->color = "#879581";
                    
                     $row->durationEditable = true; 
                    $row->startEditable = true; 
                    //$row->editable =  true;
                   
                    if (isset($row->tutor_ID)) $tutor = $this->Tutors_model->getTutor($row->tutor_ID);
                    else $tutor = false;
                    if ($tutor) $row->title = $row->details.": ".$tutor->name;
                    else $row->title = $row->details;
                }
                elseif ($customer_ID == $row->customer_ID) //used by students
                { 
                  
                    $row->color = "#879581";
                    $row->editable ="true";
                    if (isset($row->tutor_ID)) $tutor = $this->Tutors_model->getTutor($row->tutor_ID);
                    else $tutor = false;
                    if ($tutor) $row->title = $row->details;//" with ".$tutor->name;
                    else $row->title =   $row->details;
                   
                }
                elseif (!is_numeric($customer_ID) && is_numeric($tutor_id)) //used by teachers
                { 
                    $this->load->model('Customers_model');
                    $customer = $this->Customers_model->getCustomer($row->customer_ID);
                    if ($row->tutor_ID == $tutor_id)
                    {
                        $row->color = "#879581";
                        $row->durationEditable = true; 
                        $row->startEditable = true;
                        $row->title = $row->title.": ".ucwords($customer->name);
                    }
                    else
                    {
                        $row->color = "#cccccc";
                        $tutor = $this->Tutors_model->getTutor($row->tutor_ID);
                         $row->textColor ="#000000";
                        $row->durationEditable = false; 
                        $row->startEditable = false;
                        $row->title = $row->title.": ".ucwords($customer->name)." - Tutor: ".$tutor->name;
                    }
                }
                else 
                {
                    if (isset($row->tutor_ID)) $tutor = $this->Tutors_model->getTutor($row->tutor_ID);
                    else $tutor = false;
                    if ($tutor) $row->title = $row->details;//" with ".$tutor->name;
                    //else $row->title =   $row->details;
                    $row->color = "#cccccc";
                    $row->textColor ="#000000"; 
                    $row->durationEditable = false; 
                    $row->startEditable = false; 
                }
                return $row;
     }
     private function formatRecurringData($row, $customer_ID, $start, $tutorsArray = '')//this is for students
     {
         $sun = strtotime($start." 00:00:00");//start comes from the calendar's $_GET['start'] Could be any date
         $day = 3600 * 24;
         if ( $this->_filterbyDuration($row, $sun) == false) { return false; }
         $row->time = date('H:i:s', $row->unix_time);
         $this->load->model('Tutors_model');
         
            if ($customer_ID == $row->customer_ID) 
            {

                $tutor = $this->Tutors_model->getTutor($row->tutor_ID);
                $row->color="#285698";
                $row->editable ="true";
                $row->title = "Recurring: ".ucwords($tutor->name);

            }
            else { 
                $row->color="#cccccc"; 
                $row->textColor ="#000000"; 
                $row->durationEditable = false; 
                $row->startEditable = false;
                $tutor = $this->Tutors_model->getTutor($row->tutor_ID);
                $row->title = "Recurring: ".ucwords($tutor->name);
            }
            $row->day = strtolower($row->day);
                    
            if ($row->day == 'sun') { $row->start = date('Y-m-d', ($sun + (0 * $day) )); } 
            elseif ($row->day == 'mon') { $row->start = date('Y-m-d', ($sun + (1 * $day) )); } 
            elseif ($row->day == 'tue') { $row->start = date('Y-m-d', ($sun + (2 * $day) )); } 
            elseif ($row->day == 'wed') { $row->start = date('Y-m-d', ($sun + (3 * $day) )); } 
            elseif ($row->day == 'thu') { $row->start = date('Y-m-d', ($sun + (4 * $day) )); } 
            elseif ($row->day == 'fri') { $row->start = date('Y-m-d', ($sun + (5 * $day) )); } 
            elseif ($row->day == 'sat') { $row->start = date('Y-m-d', ($sun + (6 * $day) )); } 

            $row->start = $row->start." ".$row->time;
           
            $row->end = strtotime($row->start) + (60 * $row->duration);
            $row->end = date('Y-m-d H:i:s', $row->end);
            //
            //
            $this->load->model('Customers_model');
            $customer = $this->Customers_model->getRecurringProfile($row->inven_id);
             
            $startPause = strtotime($customer->{'pause-start-date'});
            $pauseLength = (int)$customer->{'pause-length-days'} * $day;
            $endPause = $startPause + $pauseLength;
            
            $rowend = strtotime($row->end);
            $rowstart = strtotime($row->start);
            if ($pauseLength > 0) {
                if (($rowend >= $endPause && $rowstart <= $endPause) || ($rowend >= $startPause && $rowstart <= $startPause))
                {  $row->color="#dddddd"; }
                elseif ($rowend <= $endPause && $rowstart >= $startPause)
                { $row->color="#dddddd"; }
            }
            return $row;
     }
     private function _getRecurTutors($customer_id)//this is kind of a filter
     {
         $query = "SELECT * FROM `Customers-RecurringProfiles` WHERE `customer-ID`='$customer_id'";
         $num_rows = $this->db->query($query)->num_rows();
           $tutors = array();
            if ($num_rows > 0)//recurring time is updated if it is already there:
            {
                foreach($this->db->query($query)->result() as $row){
                    if (! in_array($row->{'tutor-ID'}, $tutors ))
                    { array_push($tutors, $row->{'tutor-ID'}); }
                }
                return $tutors;
            } else return array();
     }
     private function _getFlexTutors($customer_id)//this is kind of a filter, but for flex time
     {
         $query = "SELECT * FROM `customersInventory` WHERE `customer_ID`='$customer_id'";
         $num_rows = $this->db->query($query)->num_rows();
            $tutors = array();
            if ($num_rows > 0) 
            {
                foreach($this->db->query($query)->result() as $row){
                    if (! in_array($row->tutor_ID, $tutors ))
                    { array_push($tutors, $row->tutor_ID); }
                }
                return $tutors;
            } else return array();
     }
     private function _getCustomerTutors($customer_id)
     {
         $tutors_1 = $this->_getFlexTutors($customer_id);
         $tutors_2 = $this->_getRecurTutors($customer_id);
         $tutors = array_unique(array_merge($tutors_1, $tutors_2));
          
         return (count($tutors) >= 1) ? $tutors : false;
     }
     private function _getRecurStuds($tutor_id)//this is kind of a filter
     {
         $query = "SELECT * FROM `Customers-RecurringProfiles` WHERE `tutor-ID`='$tutor_id'";
         $num_rows = $this->db->query($query)->num_rows();
           $studs = array();
            if ($num_rows > 0)//recurring time is updated if it is already there:
            {
                foreach($this->db->query($query)->result() as $row){
                    if (! in_array($row->{'customer-ID'}, $studs ))
                    { array_push($studs, $row->{'customer-ID'}); }
                }
                return $studs;
            } else return array();
     }
     private function _getFlexStuds($tutor_id)//this is kind of a filter, but for flex time
     {
         $query = "SELECT * FROM `customersInventory` WHERE `tutor_ID`='$tutor_id'";
         $num_rows = $this->db->query($query)->num_rows();
            $studs = array();
            if ($num_rows > 0) 
            {
                foreach($this->db->query($query)->result() as $row){
                    if (! in_array($row->customer_ID, $studs ))
                    { array_push($studs, $row->customer_ID); }
                }
                return $studs;
            } else return array();
     }
     protected function _getTutorStuds($tutor_id)
     {
         $stud_1 = $this->_getFlexStuds($tutor_id);
         $stud_2 = $this->_getRecurStuds($tutor_id);
         $studs = array_unique(array_merge($stud_1, $stud_2));
         return (count($studs) >= 1) ? $studs : false;
     }
     public function recurCalChange($auth, $apoinChange, $tutor_id, $customer_id, $fromRecurring, $type, $inven_id, $cal_id, $title, $details, $chosenDat, $unixTime, $day, $duration)//Recurrent Calendar Changes
     {
         if (!is_numeric($auth)) $auth = 1;
         $query = "SELECT * FROM `calendarEntries` WHERE tutor_ID='$tutor_id' AND customer_ID='$customer_id' AND from_recurring = '$fromRecurring' LIMIT 1";
            $num_rows = $this->db->query($query)->num_rows();
            if ($apoinChange && $num_rows > 0)//recurring time is updated if it is already there:
            {
                $recurringTabl = $this->db->query($query)->result();
                $recurringTabl = $recurringTabl[0];
                if (0 < $apoinChange  && (is_numeric($cal_id) && 0 < $cal_id))
                { $extra = "calendar_ID = $cal_id"; } 
                else 
                { 
                    $msg[0] = 0;
                    $msg[1] = "Calendar ID unavailable. Update is impossible";
                    echo json_encode($msg);
                    exit;
                }
                
                $query = "UPDATE `calendarEntries` SET last_update='".time()."', old_duration='".$recurringTabl->duration."', old_time ='".$recurringTabl->start_time."', updater=$auth, type='$type', inven_id = '$inven_id', title = '$title', details='$details', start_time='".$chosenDat[0]." ".$chosenDat[1]."', time='".$chosenDat[1]."', unix_time='$unixTime', day='$day', from_recurring='$fromRecurring', duration='$duration' WHERE $extra LIMIT 1";
                $this->db->query($query);
                
            }
            else
            {
                $query = "INSERT INTO  `calendarEntries` (`last_update`, `updater`, `customer_ID` ,`inven_id`,`type`, `tutor_ID` ,`title` ,`details` ,`start_time`, `old_time` ,`time`,`unix_time`, `day`, `from_recurring` ,`duration`, `old_duration`) VALUES ('".time()."','$auth', '$customer_id', '$inven_id', '$type',  '$tutor_id',  '$title',  '$details',  '".$chosenDat[0]." ".$chosenDat[1]."', '".$chosenDat[0]." ".$chosenDat[1]."', '".$chosenDat[1]."', '$unixTime', '$day', '$fromRecurring',  '$duration', '$duration');";
                $this->db->query($query);
            }
           // echo $query;
     }
     public function updateFlexTime($inven_id, $cust_id, $new_duration)
     {  
         if (!is_numeric($cust_id) || !is_numeric($inven_id) || !is_numeric($new_duration)) return false;
         $query = "UPDATE customersInventory SET FlexTime = $new_duration WHERE Inventory_ID = $inven_id AND customer_ID = $cust_id LIMIT 1";
         return $this->db->query($query);
     }
     public function flexCalChange($auth, $apoinChange, $row, $tutor_id, $customer_id, $inven_id, $cal_id, $duration, $type, $title,  $details, $chosenDat, $unixTime, $day, $fromRecurring)//Flex Calendar Changes
     {
         //$query = "UPDATE `calendarEntries` SET type='$type', inven_id = '$inven_id', title = '$title', details='$details', start_time='".$chosenDat[0]." ".$chosenDat[1]."', time='".$chosenDat[1]."', unix_time='$unixTime', day='$day', from_recurring='$fromRecurring', duration='$duration' WHERE tutor_ID='$tutor_id' AND customer_ID='$customer_id' AND from_recurring = '$fromRecurring' LIMIT 1";
            if ($apoinChange) {
                $flexData = $this->_getCalRow($cal_id); 
                $newDuration = $flexData->duration - $duration;
            }
            else
            {
                $flexData = false;
                $newDuration = "NOT NEEDED";
            }
            
            if ($row)//recurring time is updated if it is already there:
            {
               if (!$apoinChange && $row->FlexTime <= 0)
               {
                    $msg[0] = 0;
                    $msg[1] = "You don't have enough Flex Time";
                    echo json_encode($msg);
                    exit;
                    return;
               }
               elseif (!$apoinChange && $row->FlexTime < $duration)
               {
                    $msg[0] = 0;
                    $msg[1] = "Your available Flex Time is ".$row->FlexTime;
                    echo json_encode($msg);
                    exit;
                    return;
               }
               if ($apoinChange && $row->FlexTime <= 0 && ($duration > $flexData->duration))
               {
                    $msg[0] = 0;
                    $msg[1] = "Unable to change duration to $duration minutes. You don't have enough Flex Time";
                    echo json_encode($msg);
                    exit;
                    return;
               }
               elseif ($apoinChange && $row->FlexTime < $newDuration  && ($duration > $flexData->duration))
               {
                    $msg[0] = 0;
                    $msg[1] = "Unable to change duration to $duration minutes. Your available Flex Time is ".$row->FlexTime;
                    echo json_encode($msg);
                    exit;
                    return;
               }
               
               if (! $apoinChange) $query = "UPDATE `customersInventory` SET FlexTime = '".((int)$row->FlexTime - (int)$duration)."' WHERE tutor_ID='$tutor_id' AND customer_ID='$customer_id' AND Inventory_ID = '$inven_id' LIMIT 1";
               else
               {
                   $row_2 = $flexData;
                   $newDuration = $row_2->duration - $duration;
                   $Flextime = $row->FlexTime + ($newDuration);
                   if ($Flextime < 0)
                   {
                      $msg[0] = 0;
                      $msg[1] = "You do not have enough Flex time to make this change";
                      echo json_encode($msg);
                      exit; 
                   }
                   
                   $query = "UPDATE `customersInventory` SET FlexTime = $Flextime WHERE tutor_ID='$tutor_id' AND customer_ID='$customer_id' AND Inventory_ID = '$inven_id'  LIMIT 1"; 
               }
               $this->db->query($query);
               //echo $query; 
            } 
            if (! $apoinChange)
            {
                $query = "INSERT INTO  `calendarEntries` (`last_update`, `updater`, `customer_ID` ,`inven_id`,`type`, `tutor_ID` ,`title` ,`details` ,`start_time`, `old_time` ,`time`,`unix_time`, `day`, `from_recurring` ,`duration`, `old_duration`) VALUES ('".time()."', '".$auth."', '$customer_id', '$inven_id', '$type', '$tutor_id',  '$title',  '$details',  '".$chosenDat[0]." ".$chosenDat[1]."',  '".$chosenDat[0]." ".$chosenDat[1]."', '".$chosenDat[1]."', '$unixTime', '$day', '$fromRecurring',  '$duration',  '$duration');";
            }
            else { 
                
                $query = "UPDATE `calendarEntries` SET  last_update='".time()."', old_duration='". $flexData->duration."', old_time ='". $flexData->start_time."', updater=$auth,  type='$type', inven_id = '$inven_id', title = '$title', details='$details', start_time='".$chosenDat[0]." ".$chosenDat[1]."', time='".$chosenDat[1]."', unix_time='$unixTime', day='$day', from_recurring='$fromRecurring', duration='$duration' WHERE calendar_ID = $cal_id LIMIT 1";
                }
             //echo $query." with $apoinChange";
            $this->db->query($query);
     }
     public function _getCalRow($cal_id)
     {
         if (!is_numeric($cal_id)) {  $msg[0] = 0;
                    $msg[1] = "ERROR - Unavailable Calendar ID. How did we get this far?";
                    echo json_encode($msg);
                    exit; } 
     
         $query = "SELECT * FROM `calendarEntries` WHERE calendar_ID ='$cal_id' LIMIT 1";
            $num_rows = $this->db->query($query)->num_rows();
            if ($num_rows > 0)//recurring time is updated if it is already there:
            {
                $recurringTabl = $this->db->query($query)->result();
                $recurringTabl = $recurringTabl[0];
                return $recurringTabl;
            } else {
                 $msg[0] = 0;
                  $msg[1] = "ERROR - Unavailable Calendar ID Entry.";
                    echo json_encode($msg);
                    exit; 
            }
     }
     public function delCalRow($cal_id)
     {
         if (!is_numeric($cal_id)) { return false; }
     
         $query = "DELETE FROM `calendarEntries` WHERE calendar_ID ='$cal_id' LIMIT 1";
         $this->db->query($query);
         return true;
     }
     public function getFlexData($customer_id, $inven_id = false)
      {
          if (!is_numeric($customer_id)) return false;
          $inven_id = ($inven_id && is_numeric($inven_id)) ? "Inventory_ID = $inven_id AND ": ""; 
          $query = "SELECT * FROM customersInventory WHERE $inven_id  customer_ID = $customer_id LIMIT 1";
          $result = $this->db->query($query)->result();
          return ($result) ? $result[0] : false;
      }
     private function _getCalInvenID($inven_id)//get Calendar row by InvenID
     {
         echo "DEPRECATED. A flex time and a calendar_time can both use the same inven_id"; exit;
         if (!is_numeric($inven_id)) {  $msg[0] = 0;
                    $msg[1] = "ERROR - Unavailable inventory ID. How did we get this far?";
                    echo json_encode($msg);
                    exit; } 
     
         $query = "SELECT * FROM `calendarEntries` WHERE inven_id ='$inven_id'";
            $num_rows = $this->db->query($query)->num_rows();
            if ($num_rows > 0)//recurring time is updated if it is already there:
            {
                $recurringTabl = $this->db->query($query)->result();
                $recurringTabl = $recurringTabl[0];
                return $recurringTabl;
            } else {
            $msg[1] = "ERROR - Unavailable Calendar Entry.";
                    echo json_encode($msg);
                    exit; 
            }
     }
}
?>
