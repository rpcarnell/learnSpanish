<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Intercepts 
{
    private $params = array();
    public function __construct($params)
    {
        $this->params = $params;  
    }
    public function getIntercepts($inven_id, $cal_id, $pureStart, $customer_id, $tutor_id, $duration, $unixTime, $unixStart,  $unixEnd, $type)
    {
        //there are two kinds: one where the appointment intercepts with another teacher appointment, and one where the user's appointment intercepts
        //with another one of his appointments
        //REMEMBER unixStart and unixEnd are the start and end for the calendar, and not the start or end time of the appointment
        
        $msg = $this->_teacherIntercepts($inven_id, $cal_id, $pureStart, $customer_id, $tutor_id, $duration, $unixTime, $unixStart, $unixEnd, $type);
        if ($msg[0] === 0) return $msg; 
        $msg = $this->_userIntercepts($inven_id, $cal_id, $pureStart, $customer_id, $duration, $unixTime, $unixEnd, $type);
        if ($msg[0] === 0) return $msg;
        else 
        {
            $msg = array();
            $msg[0] = 1;
            return $msg;
        }
    }
    private function _userIntercepts($inven_id, $cal_id, $pureStart, $customer_id, $duration, $unixTime, $unixEnd, $type)
    {   
        $pureEnd= date('Y-m-d', $unixEnd);
        $msg = $this->_userRecurring($inven_id, $cal_id, $pureStart, $customer_id, $duration, $unixTime, $type);
        if ($msg[0] === 0) return $msg;
        $msg = $this->_userFlexTime($inven_id, $cal_id, $pureStart, $pureEnd, $customer_id, $duration, $unixTime, $type);
        if ($msg[0] === 0) return $msg;
    }
    private function _teacherIntercepts($inven_id, $cal_id, $pureStart, $customer_id, $tutor_id, $duration, $unixTime, $unixStart, $unixEnd, $type)
    {  
        $msg = $this->_teacherRecurring($inven_id, $cal_id, $pureStart, $customer_id, $tutor_id, $duration, $unixTime, $type);
        if ($msg[0] === 0) return $msg;
        $msg = $this->_getFlexTime($inven_id, $cal_id, $unixStart, $unixEnd, $customer_id, $tutor_id, $unixTime, $duration, $type);
        if ($msg[0] === 0) return $msg;
    }
    private function _userFlexTime($inven_id, $cal_id, $pureStart, $pureEnd, $customer_id, $duration, $unixTime, $type)
    {
         if ($type == 2) 
         {
             $recurring = $this->params['customers']->getRecurring($inven_id);
             $day = 3600 * 24;
             if ($recurring->{'lenghts-months'} == 7777)  $recurring->{'lenghts-months'} = 100;
             $months = $day * 30 * $recurring->{'lenghts-months'};
             $pureEnd = date('Y-m-d', (strtotime($pureEnd) + $months));
         }  
         
        $flexTimes = $this->params['calendar']->UserNonRecurring($pureStart, $pureEnd, $customer_id);
         
        foreach ($flexTimes as $recur)
        {
             $startCompare = strtotime($recur->start);
             $endCompare = $startCompare + ($recur->duration * 60);
             $unixTime_2 = $unixTime  + ($duration * 60);
              if ($type == 2) //with recurring appointments, we use time and day and forget the month in order to avoid future 
              // flex times from causing a problem
             {
                 $startCompare = ( (10000 * date('N', $startCompare)) + date('Hi', $startCompare));
                 $endCompare = ( (10000 * date('N', $endCompare)) + date('Hi', $endCompare));
                 $unixTime = ( (10000 * date('N', $unixTime)) + date('Hi', $unixTime));
                 $unixTime_2 = ( (10000 * date('N', $unixTime_2)) + date('Hi', $unixTime_2));
             }
              if ($this->_findConflicts($startCompare, $endCompare, $unixTime, $unixTime_2))
             {
                 $msg[0] = 0;
                 if ($recur->calendar_ID == $cal_id) continue;
                 $msg[1] = "There is a conflict with one of your Flex Time appointments. Please choose another time.";
                 return $msg;
             }
        }
    }
    private function _getFlexTime($inven_id, $cal_id, $unixStart, $unixEnd, $customer_id, $tutor_id, $unixTime, $duration, $type)
    {
         $msg = array();
         $msg[0] = 1;
         if ($type == 2) 
         {
             $recurring = $this->params['customers']->getRecurring($inven_id);
             $day = 3600 * 24;
             if ($recurring->{'lenghts-months'} == 7777)  $recurring->{'lenghts-months'} = 100;
             $months = $day * 30 * $recurring->{'lenghts-months'};
         } else $months = 0;
         $pureEnd= date('Y-m-d', ($unixEnd + $months));
         $pureStart = date('Y-m-d', $unixStart);
         $flexTimes = $this->params['calendar']->nonRecurringDays($pureStart, $pureEnd, $customer_id, $tutor_id);
         foreach ($flexTimes as $recur)
         {
             $startCompare = strtotime($recur->start);
             $endCompare = $startCompare + ($recur->duration * 60);
             $unixTime_2 = $unixTime  + ($duration * 60);
             if ($type == 2) //with recurring appointments, we use time and day and forget the month in order to avoid future flex times 
             // from causing a problem
             {
                 $startCompare = ( (10000 * date('N', $startCompare)) + date('Hi', $startCompare));
                 $endCompare = ( (10000 * date('N', $endCompare)) + date('Hi', $endCompare));
                 $unixTime = ( (10000 * date('N', $unixTime)) + date('Hi', $unixTime));
                 $unixTime_2 = ( (10000 * date('N', $unixTime_2)) + date('Hi', $unixTime_2));
             }
              if ($this->_findConflicts($startCompare, $endCompare, $unixTime, $unixTime_2))
             {
                 $msg[0] = 0;
                 if ($recur->calendar_ID == $cal_id) continue;//your own appointment, so ignore this
                 if ($recur->customer_ID == $customer_id) $msg[1] = "There is a conflict with one of your appointments. Please choose another time.";
                 elseif ($recur->tutor_ID == $tutor_id)
                 {
                     $msg[1] = "There is a Flex Time conflict. Please choose another time.";
                 } else { $msg[0] = 1;  $msg[1] = ''; }
                 return $msg;
             }
         }
    }
    private function _userRecurring($inven_id, $cal_id, $pureStart, $customer_id, $duration, $unixTime, $type)
    {
         $msg = array();
         $msg[0] = 1;
         $recurrents = $this->params['calendar']->userRecurringDays($pureStart, $customer_id);
         foreach ($recurrents as $recur)
         {
             if ($recur->calendar_ID == $cal_id) continue;//avoid a conflict with the very class you are trying to change
             $startCompare = strtotime($recur->start);
             $endCompare = $startCompare + ($recur->duration * 60);
             $unixTime_2 = $unixTime  + ($duration * 60);
              if ($this->_findConflicts($startCompare, $endCompare, $unixTime, $unixTime_2))
             {
                 $msg[0] = 0;
                 $msg[1] = "There is a conflict with one of your recurring appointments. Please choose another time.";
                 return $msg;
             }
         }
    }  
    private function _teacherRecurring($inven_id, $cal_id, $pureStart, $customer_id, $tutor_id, $duration, $unixTime, $type)
    {
        $recurrents = $this->params['calendar']->recurringDays($pureStart, '', $tutor_id);
        $msg = array();
        $msg[0] = 1;
        foreach ($recurrents as $recur)
        {  
             $startCompare = strtotime($recur->start);
             $endCompare = $startCompare + ($recur->duration * 60);
             $unixTime_2 = $unixTime  + ($duration * 60);
             if ($this->_findConflicts($startCompare, $endCompare, $unixTime, $unixTime_2))
             {   
                 if ($recur->calendar_ID == $cal_id) continue;//avoid a conflict with the very class you are trying to change
                 $msg[0] = 0;
                 if ($recur->customer_ID == $customer_id) 
                 { $msg[1] = "There is a conflict with one of your classes. Please choose another time."; }
                 else $msg[1] = "There is a conflict with another student. Please choose another time.";
                 return $msg;
             }
        } 
    }
    private function _findConflicts($userTime, $userTime_2, $unixTime, $unixTime_2)
    {
        //we are using Unix time here
        if ($userTime == $unixTime && $userTime_2 == $unixTime_2) return true;
        elseif ($unixTime < $userTime_2 && $unixTime_2 > $userTime ) return true;
        else return false;
    }

}