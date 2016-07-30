<?php
if(!defined('BASEPATH')) exit('No direct script access allowed');
include_once('main_controller.php');
class Calendar extends SP_Controller
{
    var $unixRepair = 0;//(24 * 3600);
    var $tutor_id = 0;
    public function __construct()
    {
        parent::__construct();
        $customer_ID = $this->session->userdata('customerid');
        if (is_numeric($customer_ID) && $customer_ID > 0)
        { 
            $this->load->model('Timezones_model');
            $time_zone = $this->Timezones_model->getUserZone($customer_ID);
            date_default_timezone_set($time_zone);//***** we set the timezone first
        }
    }
    public function pauseLimit()
    {
         $pauseTime = $_POST['pauseTime'];
         $globals = $this->Fn_model->getGlobals();
         if ($pauseTime == 'Indefinite') { echo "Fine"; exit; }
         if (!is_numeric($pauseTime)) { echo "Value is not a number"; }
         elseif ($globals->{'max-pause-days'} < $pauseTime) { echo "Your pause cannot last longer than ".$globals->{'max-pause-days'}." days."; }
         else { echo 'Fine'; }
         exit;
    }
    public function tutorTime()
    {
	$data['main_content'] = 'front/profile_future';
        $this->load->view('front/includes/main_view', $data);
    }
    public function index()
    {
        $this->load->library('breadcrumbs');
        $this->breadcrumbs->push('Teacher Schedules', '/home/appointments');
        $this->breadcrumbs->unshift('Home', base_url());
        $breadcrumbs = $this->breadcrumbs->show();
        $data['breadcrumbs'] = $breadcrumbs;
	$error = '';
        $data['error'] = $error;
        $globals = $this->Fn_model->getGlobals();
        $data['page_title'] = $globals->{'store-name'}.' | Terms &amp; Conditions';
        $data['main_content'] = 'front/calendar';
        $this->load->view('front/includes/main_view',$data);
     }
     public function calData()
     {
	 $customer_ID = $this->session->userdata('customerid');
         $this->load->model('Calendar_model');
         $this->load->model('Tutors_model');
         if (!is_numeric($customer_ID)) return;
         $data_3 = array();
         
         if (isset($_GET['start']) && isset($_GET['end'])) 
          { 
              
              $tutor_id = (isset($_GET['tutor_id']) && is_numeric($_GET['tutor_id'])) ? $_GET['tutor_id'] : '';
              $data_1 = $this->Calendar_model->nonRecurringDays($_GET['start'], $_GET['end'], $customer_ID, $tutor_id);
              if (is_array($data_1))
              {
                  $i = 0;
                  foreach ($data_1 as $da1)
                  {
                       $photo = $this->Tutors_model->getTutorPhoto($da1->tutor_ID);
                       $data_1[$i]->tutorPhoto = $photo;
                       $i++;
                  }
              }
              $data_2 = $this->Calendar_model->recurringDays($_GET['start'], $customer_ID, $tutor_id);
              if (is_array($data_1))
              {
                  $i = 0;
                  foreach ($data_2 as $da2)
                  {
                       $photo = $this->Tutors_model->getTutorPhoto($da2->tutor_ID);
                       $data_2[$i]->tutorPhoto = $photo;
                       $i++;
                  }
              }
              $data_3 = array_merge($data_1, $data_2);
          }
              echo json_encode($data_3); 
              return;
    }
    public function tutorCal()
     { 
	 $customer_ID = $this->session->userdata('customerid');
         $tutor_id = (isset($_GET['tutor_id']) && is_numeric($_GET['tutor_id'])) ? $_GET['tutor_id'] : '';
         $this->load->model('Teachercal_model');
         if (!is_numeric($customer_ID) || !is_numeric($tutor_id)) return;
         $data_3 = array();
         
         if (isset($_GET['start']) && isset($_GET['end'])) 
          { 
              $data_1 = $this->Teachercal_model->nonRecurringDa_1($_GET['start'], $_GET['end'], $customer_ID, $tutor_id);
              $data_2 = $this->Teachercal_model->recurringDa_1($_GET['start'],  $customer_ID, $tutor_id);
              $data_3 = array_merge($data_1, $data_2);
          }
              echo json_encode($data_3); 
              return;
    }
    public function deleteadmin()
    {
        $calendar_id = $_POST['calendar_id'];
        $msg = array();
        $adminCredentials = $this->session->userdata('adminCredentials');
        if(!$adminCredentials) { exit; }
        if (!is_numeric($calendar_id)) 
        {
            $msg[0] = 0;
            $msg[1] = "Invalid IDs";
            
        }
        $this->load->model('Calendar_model');
        $this->Calendar_model->delCalRow($calendar_id);
        $msg[0] = 1;
        $msg[1] = "Appointment has been deleted";
        echo json_encode($msg);
    }
    public function tutorDelete()
    {
        $tutor_id = $_POST['tutor_id'];
        $calendar_id = $_POST['calendar_id'];
        $my_tutor_id = $this->session->userdata('tutorid');
        $msg = array();
        if (!is_numeric($tutor_id) || !is_numeric($calendar_id) || $tutor_id !=  $my_tutor_id) 
        {
            $msg[0] = 0;
            $msg[1] = "Invalid IDs";
            
        }
        $this->load->model('Calendar_model');
        $this->Calendar_model->delCalRow($calendar_id);
        $msg[0] = 1;
        $msg[1] = "Appointment has been deleted";
        echo json_encode($msg);
        
   }
    public function tutorSchedule()
    {
        $this->Fn_model->ch_login();
        $this->load->library('breadcrumbs');
        $customer_ID = $this->session->userdata('customerid');
        $this->load->model('Customers_model');
        $tutor_id = $this->uri->segment(3);
        if (!is_numeric($tutor_id)) { echo "ERROR - there is no tutor ID. Exiting"; exit; }
        $type = $this->session->userdata('purchase');
         
       /* $id = isset($_GET['id']) ? $_GET['id']  : '';
        if (!is_numeric($type)) { echo "Unavailable type"; return; } 
        if (!is_numeric($id)) { echo "Unavailable ID"; return; } 
        if ($type == 2) { $row = $this->Customers_model->getRecurring($id); }
        elseif ($type == 1) { $row = $this->Customers_model->getCustInventory($id); }
        else return;*/
        $row = '';
        $data['row'] = $row;
        
        $data['type'] = $type;
        $this->breadcrumbs->push('Profile', '/profile');
        $this->breadcrumbs->push('Make Purchase', '/billing/purchase');
        $this->breadcrumbs->push('Purchase Tutor Time', '/tutors?purchase='.$type);
        $this->breadcrumbs->push('Tutor Calendar', '/calendar/tutorSchedule/');
        $this->breadcrumbs->unshift('Home', base_url());
        $breadcrumbs = $this->breadcrumbs->show();
        $data['breadcrumbs'] = $breadcrumbs;
	$error = '';
        $data['error'] = $error;
        $this->load->model('Tutors_model');
        $data['customer_ID'] = $customer_ID;
        $data['tutor_id'] = $tutor_id;
        $data['tutor'] = $this->Tutors_model;
        $globals = $this->Fn_model->getGlobals();
        $data['page_title'] = $globals->{'store-name'}.' | Terms &amp; Conditions';
        $data['main_content'] = 'front/calendar/cal_tutor';
        $this->load->view('front/includes/main_view',$data);
    }
    public function deleteappoint()
    {
        $cal_id = $_POST['calendar_id'];
        $msg = array();
        $error = false;
        if (!is_numeric($cal_id)) { $msg[0]; $msg[1] = "Calendar ID is not available"; exit; } 
        $this->load->model('Calendar_model');
        $cal = $this->Calendar_model->_getCalRow($cal_id);
        if ($cal->customer_ID != $_POST['customer_id']) { $msg[0]; $msg[1] = "User ID is not valid"; $error = true; } 
        if ($cal->type == 1 && !is_numeric($cal->inven_id))  { $msg[0]; $msg[1] = "Inven ID is not valid"; $error = true;  } 
        elseif ($cal->type == 1)
        {
             $row = $this->Calendar_model->getFlexData($cal->customer_ID, $cal->inven_id);
             if (!$row) { $msg[0]; $msg[1] = "User ID is not valid"; $error = true;  }
             else
             {
                 $new_duration = $row->FlexTime + $cal->duration;
                 $res = $this->Calendar_model->updateFlexTime($cal->inven_id, $cal->customer_ID, $new_duration);
                 if (!$res) { $msg[0]; $msg[1] = "Error - Unable to change Flex Time duration"; $error = true; }
             }
        }
        $this->Calendar_model->delCalRow($cal_id);
        if ($error === false) 
        { 
           $msg[0] = 1;
           $msg[1] = "Appointment has been deleted";
        }
        echo json_encode($msg);
        exit;
    }
    public function appointments()
    {
        $this->Fn_model->ch_login();
        $userMessage = $this->showMessage();
        $this->load->library('breadcrumbs');
        $customer_ID = $this->session->userdata('customerid');
        if (!is_numeric($customer_ID) || $customer_ID == 0) { redirect(base_url('signup_login')); }
        $this->load->model('Customers_model');
        list($rows, $rows_2)  = $this->Customers_model->futureApp($customer_ID, 0);
        $this->load->model('Customers_model');
        $this->load->model('Tutors_model');
        $this->load->model('Timezones_model');
        $time_zone = $this->Timezones_model->getUserZone($customer_ID);
        date_default_timezone_set($time_zone);
        $type = isset($_GET['type']) ? $_GET['type']  : '';
        $id = isset($_GET['id']) ? $_GET['id']  : '';
        $msg = '';
        if (!is_numeric($type)) { echo "Unavailable Appointment type"; return; } 
        if (!is_numeric($id)) { echo "Unavailable Apointment ID"; return; } 
        if ($type == 2) { $row = $this->Customers_model->getRecurring($id); }
        elseif ($type == 1) { $row = $this->Customers_model->getCustInventory($id); }
        else return;
         
        $data['row'] = $row;
        if ($type == 2) 
        {
            $recurring = $this->Customers_model->getRecurringProfile($id);
        if (! $recurring) { echo "ERROR - recurring profile is invalid. Exiting"; exit; }
            $entry = $this->Customers_model->getCalendarEntry($customer_ID, $recurring->{'tutor-ID'}, 1);
            if ($entry)
            { 
                $recurntry = $this->Customers_model->getRecurring($entry->inven_id);
                $onlyhave = (int)$recurntry->{'minutes-per-week'} - $this->Customers_model->getRecurringUsed($entry->inven_id);
                $tutor = $this->Tutors_model->getTutor($entry->tutor_ID);
                $msg = '';
                $msg .= "<img src='".base_url()."uploads/tutors/".$tutor->photo."' style='float: left; margin: 5px; height: 85px; max-width: 150px; border-radius: 10px;' />";
		
                $msg .= "<p><b>Minutes per week:</b> ".$recurring->{'minutes-per-week'}."</p>";
                $msg .= "<p><b>Type:</b> Recurring Appointment</p>";
                $msg .= "<p><b>Tutor:</b> ".$tutor->name."</p>";
                $msg .= "<p>You have ".$onlyhave." minutes available</p>";
            }
        }
        elseif ($type == 1)
        {
            
            $entry = $this->Customers_model->getCustInventory($id);
             if ($entry)
            {
                $tutor = $this->Tutors_model->getTutor($entry->tutor_ID);
                $msg = '';
                $msg .= "<img src='".base_url()."uploads/tutors/".$tutor->photo."' style='float: left; margin: 5px; height: 85px; max-width: 150px;' />";
		$msg .= "<p><b>Type:</b> Flex Time Appointment</p>";
                $msg .= "<p><b>Tutor:</b> ".$tutor->name."</p>";
                $msg .="<p>".$entry->FlexTime." minutes of Flex Time appointment available.</p>";
            }
         }
        $data['type'] = $type;
         $this->breadcrumbs->push('Profile', '/profile');
        $this->breadcrumbs->push('Bookings', '/calendar/appointcalen');
        $this->breadcrumbs->push('Booking Calendar', '/calendar/appointments');
        $this->breadcrumbs->unshift('Home', base_url());
        $breadcrumbs = $this->breadcrumbs->show();
        $data['breadcrumbs'] = $breadcrumbs;
	$error = '';
        $data['msg'] = $msg;
        $data['recurring'] = $rows;
        $data['inventory'] = $rows_2;
        $data['error'] = $error;
        $data['tutor_model'] = $this->Tutors_model;
         $data['Customers_model'] = $this->Customers_model;
        $this->load->model('Tutors_model');
        $data['customer_ID'] = $customer_ID;
        $data['userMessage'] = $userMessage;
        //$data['tutor'] = $this->Tutors_model;
        $data['teacher'] = (isset($tutor) && isset($tutor->name)) ? $tutor->name : "your chosen tutor";
        $data['per_week'] = (isset($recurring) && isset($recurring->{'minutes-per-week'})) ? $recurring->{'minutes-per-week'} : "certain amount of minutes";
        $globals = $this->Fn_model->getGlobals();
        $data['page_title'] = $globals->{'store-name'}.' | Terms &amp; Conditions';
        $data['main_content'] = 'front/calendar/calendar';
        $this->load->view('front/includes/main_view',$data);
    }
    public function FlexRemain()
    {
        $this->_getRemaining(1);
    }
    public function RecurRemain()
    {
        $this->_getRemaining(2);
    }
    private function _getRemaining($type)
    {
        $result = array();
        $inven_id = $_POST['inven_id'];
        $customer_id = $_POST['customer_id'];
        if (!is_numeric($inven_id) || !is_numeric($customer_id))
        {
            $result[0] = 0;
            $result[1] = false;
        }
        $this->load->model('Customers_model');
        if ($type == 2) $rem = $this->Customers_model->recurRemain($inven_id, $customer_id);
        else $rem = $this->Customers_model->flexRemain($inven_id, $customer_id);
        if ($rem === false) 
        {
            $result[0] = 0;
            $result[1] = false;
        }
        else
        {
            $result[0] = 1;
            $result[1] = $rem;
        }
        echo json_encode($result);
        exit;
    }
    public function appointCalen()
    {
        $this->Fn_model->ch_login();
        $this->load->library('breadcrumbs');
        $customer_ID = $this->session->userdata('customerid');
        $this->load->model('Customers_model');
        list($rows, $rows_2)  = $this->Customers_model->futureApp($customer_ID, 0);
        $this->breadcrumbs->push('Profile', '/profile');
        $this->breadcrumbs->push('Bookings', '/home/appointments');
        $this->breadcrumbs->unshift('Home', base_url());
        $breadcrumbs = $this->breadcrumbs->show();
        $data['breadcrumbs'] = $breadcrumbs;
	$error = '';
         $data['recurring'] = $rows;
        $data['inventory'] = $rows_2;
        $data['error'] = $error;
         $globals = $this->Fn_model->getGlobals();
        $data['page_title'] = $globals->{'store-name'}.'| Terms &amp; Conditions';
        $data['main_content'] = 'front/pointcalendar';
        $this->load->model('Tutors_model');
       $data['Customers_model'] = $this->Customers_model;
        $data['tutor_model'] = $this->Tutors_model;
        $this->load->view('front/includes/main_view',$data);
    }
    public function futureapp()
    {
        $this->Fn_model->ch_login();
        $customer_ID = $this->session->userdata('customerid');
        $this->load->library('breadcrumbs');
        $this->breadcrumbs->push('Profile', '/profile');
        $this->breadcrumbs->push('Upcoming Appointments', '/calendar/futureapp');
        $this->breadcrumbs->unshift('Home', base_url());
        $breadcrumbs = $this->breadcrumbs->show();
        $this->load->model('Customers_model');
        $data['breadcrumbs'] = $breadcrumbs;
        $hiderecur = (isset($_GET['hiderecur']) && is_numeric($_GET['hiderecur']) ) ? $_GET['hiderecur'] : 0;
        list($rows, $rows_2) = $this->Customers_model->futureApp($customer_ID, $hiderecur);
        $error = '';
        $data['error'] = $error;
        $data['recurring'] = $rows;
        $data['inventory'] = $rows_2;
        $data['hiderecur'] = $hiderecur;
        
        $data['Customers_model'] = $this->Customers_model;
	$globals = $this->Fn_model->getGlobals();
        $data['page_title'] = $globals->{'store-name'}.' | Upcoming Appointments';
        $data['main_content'] = 'front/futureapp';
        $this->load->model('Tutors_model');
        $data['tutor_model'] = $this->Tutors_model;
        $this->load->view('front/includes/main_view',$data);
    }
    function teachercalendar()//same as calData, but for teacher data 
    {
         $tutor_id = $this->session->userdata('tutorid');
         if (!is_numeric($tutor_id)) return;
          $this->load->model('Calendar_model');
           if (is_numeric($tutor_id) && $tutor_id > 0)//let's get the timezone for the teacher
           {
            $this->load->model('Timezones_model');
            $time_zone = $this->Timezones_model->getUserZone($tutor_id, true);
            date_default_timezone_set($time_zone);//***** we set the timezone first
          }
         $data_3 = array();
         $data_2 = array();
         if (isset($_GET['start']) && isset($_GET['end'])) 
          { 
               //date_default_timezone_set('America/Los_Angeles');
              $tutor_id = (isset($_GET['tutor_id']) && is_numeric($_GET['tutor_id'])) ? $_GET['tutor_id'] : '';
              $data_1 = $this->Calendar_model->nonRecurringDays($_GET['start'], $_GET['end'], '', $tutor_id);
              $data_2 = $this->Calendar_model->recurringDays($_GET['start'], '', $tutor_id);
              $data_3 = array_merge($data_1, $data_2);
          }
              echo json_encode($data_3); 
              return;
    }
     function admincalendar()//same as teachers, but for administrator
    {
         
        $adminCredentials = $this->session->userdata('adminCredentials');
          $id = $adminCredentials['admin_id'];
        if (is_numeric($id) && $id > 0)
        {
             $this->load->model('Timezones_model');
            $time_zone = $this->Timezones_model->getUserZone($id, false, true);
            date_default_timezone_set($time_zone);
        }
        if(!$adminCredentials) exit;
          $this->load->model('Calendar_model');
          
        
         $data_3 = array();
         $data_2 = array();
         if (isset($_GET['start']) && isset($_GET['end'])) 
          { 
               //date_default_timezone_set('America/Los_Angeles');
             // $tutor_id = (isset($_GET['tutor_id']) && is_numeric($_GET['tutor_id'])) ? $_GET['tutor_id'] : '';
              $data_1 = $this->Calendar_model->nonRecurringDays($_GET['start'], $_GET['end']);
             $data_2 = $this->Calendar_model->recurringDays($_GET['start']);
              $data_3 = array_merge($data_1, $data_2);
          }
              echo json_encode($data_3); 
              return;
    }
    function dragTut()
    {
        $this->Fn_model->ch_tutorlogin();
        $tutor_id = $this->session->userdata('tutorid');
        if (!is_numeric($tutor_id) || $tutor_id == 0) exit;
        else $this->_dragAppointTut($tutor_id); 
    }
    private function _dragAppointTut($tutor_id = false)
    { 
        $apointChange = $_POST['appointChange'];
        $this->tutor_id = $tutor_id;
         
        $inven_id = $_POST['inven_id'];
        $cal_id = $_POST['cal_id'];
        $this->load->model('Customers_model');
        $row = $this->Customers_model->getRecurring($inven_id); 
        $customer_id = $row->{'customer-ID'};
        if (is_numeric($tutor_id))//we are dealing with a tutor
        {
            $this->load->model('Timezones_model');
            $time_zone = $this->Timezones_model->getUserZone($tutor_id, true);
            date_default_timezone_set($time_zone);//***** we set the timezone first
            $auth = 2;
        } else $auth = 0;
        
        $chosenDat = $_POST['chosenDat'];
        $chosenDat = explode('T', $chosenDat);
        $dateStart = $_POST['dateStart'];//start of the week in the visible part of the calendar
        $dateStart = explode('T', $dateStart);
        $dateStart[1] = "00:00:00";
        $dateStart = $dateStart[0]." ".$dateStart[1];
        
        $unixStart = strtotime($dateStart);
       // $pureStart = date('Y-m-d', $unixStart);
        $dateEnd = $_POST['dateEnd'];
        $dateEnd = explode('T', $dateEnd);//end of the week in the visible part of the calendar
        $dateEnd[1] = "00:00:00";
        $dateEnd = $dateEnd[0]." ".$dateEnd[1];
        
        $unixEnd = strtotime($dateEnd);
        //$pureEnd= date('Y-m-d', $unixEnd);
        $this->_in_up_Appoin($apointChange, $_POST, $inven_id, $cal_id, $chosenDat, $unixStart, $unixEnd, $customer_id, $auth);
    }
    function dragAppoint()
    { 
        $msg = array();
        $apointChange = $_POST['appointChange'];
        $inven_id = $_POST['inven_id'];
        $cal_id = $_POST['cal_id'];
        $customer_id = $_POST['customer_ID'];
        $adminCredentials = $this->session->userdata('adminCredentials');
        if(!$adminCredentials)
        {
            $login_id = $this->session->userdata('customerid');
            if (!is_numeric($login_id) || $login_id == 0) exit;
            if ((int)$customer_id != (int)$login_id) exit;
            $auth = 1;
        }
        
        else //the administrator's timezone may be different
        {
            $admin_id = $adminCredentials['admin_id'];
            if (is_numeric($admin_id) && $admin_id > 0)
            {
                $this->load->model('Timezones_model');
                $time_zone = $this->Timezones_model->getUserZone($admin_id, false, true);
                date_default_timezone_set($time_zone);
            }
            $auth = 3;
        }
        $chosenDat = $_POST['chosenDat'];
        $chosenDat = explode('T', $chosenDat);
        $dateStart = $_POST['dateStart'];//start of the week in the visible part of the calendar
        $dateStart = explode('T', $dateStart);
        $dateStart[1] = "00:00:00";
        $dateStart = $dateStart[0]." ".$dateStart[1];
        
        $unixStart = strtotime($dateStart);
        $pureStart = date('Y-m-d', $unixStart);
        $dateEnd = $_POST['dateEnd'];
        $dateEnd = explode('T', $dateEnd);//end of the week in the visible part of the calendar
        $dateEnd[1] = "00:00:00";
        $dateEnd = $dateEnd[0]." ".$dateEnd[1];
        
        $unixEnd = strtotime($dateEnd);
        $pureEnd= date('Y-m-d', $unixEnd);
        $this->_in_up_Appoin($apointChange, $_POST, $inven_id, $cal_id, $chosenDat, $unixStart, $unixEnd, $customer_id, $auth);
    }
    function createappoint()
    { 
        $msg = array();
        $apointChange = $_POST['appointChange'];
        $inven_id = $_POST['inven_id'];
        $cal_id = ($apointChange) ? $_POST['cal_id'] : '';
        $customer_id = $_POST['customer_ID'];
       
        $chosenDat = $_POST['chosenDat'];
        $chosenDat = explode('T', $chosenDat);
        $dateStart = $_POST['dateStart'];
        $dateStart = explode('T', $dateStart);
        $dateStart[1] = "00:00:00";
        $dateStart = $dateStart[0]." ".$dateStart[1];
        
        $unixStart = strtotime($dateStart);
        $pureStart = date('Y-m-d', $unixStart);
        $dateEnd = $_POST['dateEnd'];
        $dateEnd = explode('T', $dateEnd);
        $dateEnd[1] = "00:00:00";
        $dateEnd = $dateEnd[0]." ".$dateEnd[1];
        
        $unixEnd = strtotime($dateEnd);
        $pureEnd= date('Y-m-d', $unixEnd);
        $auth = 1;
        $this->_in_up_Appoin($apointChange, $_POST, $inven_id, $cal_id, $chosenDat, $unixStart, $unixEnd, $customer_id, $auth);
    }
    private function _in_up_Appoin($apoinChange, $post, $inven_id, $cal_id, $chosenDat, $unixStart, $unixEnd, $customer_id, $auth = 0)
    {
        //cal_id will not exist if apoinchange is 0, but inven_id will have a value anyhow
         $old_chosen = $chosenDat[0]." ".$chosenDat[1];//this is a date with time, not 00:00:00, like the others
        $unixTime = strtotime($old_chosen);
        //echo " *** unixtime is $unixTime with $old_chosen *** ";
        $day = date('D', $unixTime);
        //echo "day is $day and ".date('Y-m-d', $unixTime);
        $duration = $post['duration'];
        $type = $post['type'];
        if (!is_numeric($inven_id) || $inven_id == 0) { echo "inventory id $inven_id is not available"; return;}
        if ($apoinChange && (!is_numeric($cal_id) || $cal_id == 0))  {echo "calendar entry id is not available"; return;}
        $this->load->model('Customers_model');
        $this->load->model('Calendar_model');
        
        if ($type == 2) { //first we find out if the user has minutes left (for recurring)
            $row = $this->Customers_model->getRecurring($inven_id); 
            
            $timeAlrUsed = $duration + $this->Customers_model->getRecurringUsed($inven_id, $apoinChange, $cal_id);  
           // echo " so the time used is ". $timeAlrUsed;
            if ((int)$row->{'minutes-per-week'} < $timeAlrUsed)
            {
                $msg[0] = 0;
                $onlyhave = (int)$row->{'minutes-per-week'} - $this->Customers_model->getRecurringUsed($inven_id);
                if ($onlyhave > 0) $only = " only "; else $only = ' ';
                $msg[1] = "Unable to make change. You".$only."have ".$onlyhave." minutes available";
                echo json_encode($msg);
                return;
            }
        }
        elseif ($type == 1)  //first we find out if the user has minutes left (for Flex)
        { 
            $row = $this->Customers_model->getCustInventory($inven_id); 
            if (! $apoinChange && (int)$row->{'FlexTime'} < $duration)
            {
                if ((int)$row->{'MakeUpTime'} < $duration)
                {
                    $msg[0] = 0;
                    $msg[1] = "You don't have enough minutes";
                    echo json_encode($msg);
                    return;
                }
            }
        }
        $this->load->model('Tutors_model');
        //lety's find out whether the appointments are intercepting or not:
        if ($post['type'] == 1)
        {
            $customer_id_2 = $row->{'customer_ID'};
            $tutor_id = $row->{'tutor_ID'};
             
            $inter = $this->Calendar_model->findIntercepts($inven_id, $cal_id, $unixTime, $duration, ($unixStart + $this->unixRepair),  $unixEnd, $customer_id, $tutor_id, $post['type']);
            
            if ($inter[0] == 0)
            {  
                $msg = $inter;
                 echo json_encode($msg);
                return;
            }
            $flexTime = $row->FlexTime;
            $tutor = $this->Tutors_model->getTutor($tutor_id);
            $title = "Flextime Appointment";
            $details = "Flex Time: ".ucwords($tutor->name);
            $fromRecurring = 0;
        }
        elseif ($post['type'] == 2) 
        {
            $customer_id_2 = $row->{'customer-ID'};
            $tutor_id = $row->{'tutor-ID'};
             
            $inter = $this->Calendar_model->findIntercepts($inven_id, $cal_id, $unixTime, $duration, ($unixStart + $this->unixRepair),  $unixEnd, $customer_id, $tutor_id, $post['type']);
            
            if ($inter[0] == 0)
            {  
                $msg = $inter;
                 echo json_encode($msg);
                return;
            }
            $tutor = $this->Tutors_model->getTutor($tutor_id);
            $details = $row->StartDate.": Day of Week: $day. Recurring Time: ".ucwords($tutor->name);
            $title = "Recurring Appointment";
            $fromRecurring = 1;
        }
        //but can the user make changes? Maybe he/she is not authorized
        if ($customer_id_2 != $customer_id) 
        {
            if ($auth == 2 &&  $tutor_id == $this->tutor_id) { }
            else 
            { 
                $msg[0] = 0;
                $msg[1] = "You don't have the authorization to make changes";
                echo json_encode($msg);
                return;
            }
        }
        $title = sanitize_input($title);
        $details = sanitize_input($details);
       
        if ($fromRecurring == 1) //let's deal with recurrent appointment
        { $this->Calendar_model->recurCalChange($auth, $apoinChange, $tutor_id, $customer_id, $fromRecurring, $type, $inven_id, $cal_id, $title, $details, $chosenDat, $unixTime, $day, $duration); }
        else //let's deal with a Flextime appointment 
        {  $this->Calendar_model->flexCalChange($auth, $apoinChange, $row, $tutor_id, $customer_id, $inven_id, $cal_id, $duration, $type, $title,  $details, $chosenDat, $unixTime, $day, $fromRecurring); }
        $msg[0] = 1;
                    $msg[1] = "Your new appointment has been successfully created";
                    echo json_encode($msg);
                    return;
     }
     
}
?>
