<?php
include_once('calendar_model.php');
class Teachercal_model extends Calendar_model  
{
     function __construct()
     {}
     public function nonRecurringDa_1($start, $end, $customer_ID, $tutor_id)
     {
         $where = 'WHERE tutor_ID = '.$tutor_id.' AND from_recurring = 0 AND (STR_TO_DATE(`start_time`,"%Y-%m-%d") BETWEEN "'.$start.'" AND "'.$end.'")'; 
         $queryStr      = "SELECT a.tutor_ID, a.calendar_ID, a.inven_id, a.type, a.details,a.customer_ID, a.title, a.start_time as start, a.unix_time, a.duration FROM calendarEntries as a " . $where . "";//ORDER BY id DESC LIMIT {$start}, {$config['per_page']}";
         $data = array();
         if($this->db->query($queryStr)->num_rows() > 0)
         {   
            foreach($this->db->query($queryStr)->result() as $row)
            {
                $row = $this->_formatNonRecurr_1($tutor_id, $customer_ID, $row);
                $data[] = $row;
             }
          }
         return $data;
     }
     private function _formatNonRecurr_1( $tutor_id, $customer_ID, $row)//this is for customers viewing tutor openings
     {
           $this->load->model('Tutors_model');
                $row->start = date('Y-m-d H:i:s', $row->unix_time);
                $row->end = $row->unix_time + (60 * $row->duration);
                $row->end = date('Y-m-d H:i:s', $row->end);
                
                
                    $this->load->model('Customers_model');
                    $customer = $this->Customers_model->getCustomer($row->customer_ID);
                    if ($row->customer_ID == $customer_ID)
                    {
                        $row->color = "#879581";
                        $row->durationEditable = false; 
                        $row->startEditable = false;
                        $row->title = $row->title.": ".ucwords($customer->name);
                    }
                    else
                    {
                        $row->color = "#cccccc";
                        $tutor = $this->Tutors_model->getTutor($row->tutor_ID);
                         $row->textColor ="#000000";
                        $row->durationEditable = false; 
                        $row->startEditable = false;
                       // $row->title = $row->title;//" with ".ucwords($customer->name)." - Tutor: ".$tutor->name;
                    }
           return $row;
     }
     public function recurringDa_1($start, $customer_ID, $tutor_id)
     {  
          $sun = strtotime($start." 00:00:00")  + (3600 * 24 * 7);//let's say two days before the given time
           $where = 'WHERE unix_time < '.$sun.' AND tutor_ID = '.$tutor_id.' AND from_recurring = 1';
           $data = array();  
           $queryStr      = "SELECT a.calendar_ID, a.inven_id, a.type, a.calendar_ID, a.customer_ID, a.tutor_ID, a.title, a.unix_time, a.start_time as start, day, time, duration FROM calendarEntries as a " . $where . "";//ORDER BY id DESC LIMIT {$start}, {$config['per_page']}";
           if($this->db->query($queryStr)->num_rows() > 0)
           {
                 $students_array = $this->_getTutorStuds($tutor_id);
                 foreach($this->db->query($queryStr)->result() as $row)
                 {
                       if ($students_array && ! in_array($row->customer_ID, $students_array )) continue;
                       $cont = $this->formatTutorDat_1($row, $customer_ID, $tutor_id, $start); 
                       if ($cont === false) continue;
                       $data[] = $row;
                 }
           }
          return $data;    
     }
      private function formatTutorDat_1($row, $customer_ID, $tutor_ID, $start)//this is for students viewing a teacher appointment
     {  
            $sun = strtotime($start." 00:00:00");
            if ( $this->_filterbyDuration($row, $sun) == false) { return false; }
            $day = 3600 * 24;
            $this->load->model('Customers_model');
            if ($row->customer_ID == $customer_ID)
            {
                $customer = $this->Customers_model->getCustomer($row->customer_ID);
                $row->color="#285698"; 
                $row->textColor ="#ffffff"; 
                $row->title = "Recurring\n\nStudent: ".ucwords($customer->name);
            }
            else
            {
                $row->color="#cccccc"; 
                $row->textColor ="#000000"; 
            }
            $row->durationEditable = false; 
            $row->startEditable = false; 
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
}