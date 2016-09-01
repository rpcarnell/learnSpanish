<?php
class Cronparser_model extends CI_Model {
 	
 	var $bits = Array(); //exploded String like 0 1 * * *
 	var $now= Array();	//Array of cron-style entries for time()
 	var $lastRan; 		//Timestamp of last ran time.
 	var $taken;
 	var $debug;
	
 	 
 	function CronParser($string){
 		/*$tstart = microtime();
 		$this->debug("<b>Working on cron schedule: $string</b>");
                 
 		$this->bits = @explode(" ", $string);
                
 		$this->getNow();  	
 		$this->calcLastRan(); //exit;	
 		$tend = microtime();
                
 		$this->taken = $tend-$tstart;
                
 		$this->debug("Parsing $string taken ".$this->taken." seconds");*/
 		
 	}
        function getGlobals() 
        { 
            $globals = $this->Fn_model->getGlobals();
            
            $time = time();
            $last = time() - $globals->{'cron-last'};
            $last_msg = $time - $globals->{'last-msgs'};
            if ($last > $globals->{'chron-job-seconds'})
            {
                
                $query = "UPDATE GlobalValues SET `cron-last`=".$time."";
                $this->db->query($query);
                if ((60 * $globals->{'delay-before-change-notice'}) < ($time - $globals->{'last-msgs'})) 
                {
                    $this->_msgUsers(); 
                    $this->examinePauses($globals);
                }
            }
        } 
        private function examinePauses($globals)//examines pauses, and deletes calendarEntries above the limit
        {
            $day =   3600 * 24;
            $limit = $globals->{'max-pause-days'} * $day;
            $query = "SELECT * FROM `Customers-RecurringProfiles` WHERE `pause-length-days` > 0";
             if($this->db->query($query)->num_rows() > 0)
            {   
                foreach($this->db->query($query)->result() as $row)
                {
                    $days = $row->{'pause-length-days'} * $day;
                    if ($limit < $days)//infinity would go way above the limit
                    {
                        $pauseDate = strtotime($row->{'pause-start-date'});
                        $time_passed = time() - $pauseDate; 
                        $time_passed = ceil($time_passed / $day);
                        if ($time_passed > 0)//if the time is in the future, time_passed will be 0 or less
                        {
                            if ((int)$time_passed > (int)$globals->{'max-pause-days'})//it has been paused for too long, so let's get rid of it
                            {
                                $query = "DELETE FROM calendarEntries WHERE type= 2 AND  inven_id= '".$row->{'recurring-Profile-ID'}."'";
                                $this->db->query($query);
                            }
                        }
                    }
                }
            }
        }
        private function _msgUsers()
        {
            $query = "UPDATE GlobalValues SET `last-msgs`=".time()."";
            $this->db->query($query);
            $query = "SELECT * FROM calendarEntries WHERE start_time != old_time OR duration != old_duration";
            if($this->db->query($query)->num_rows() > 0)
            {   
                foreach($this->db->query($query)->result() as $row)
                {
                    $this->_warnUserChange($row);
                    $query2 = "UPDATE calendarEntries SET old_time = start_time, old_duration = duration WHERE calendar_ID = $row->calendar_ID LIMIT 1";
                    $this->db->query($query2);
                } 
            }
        }
        private function _warnUserChange($row)
        {
            if ($row->updater == 2) { $this->_calWarnStud($row->customer_ID, $row->tutor_ID,  $row);  }
            elseif ($row->updater == 3)//Okay, who made the update? User, administrator, tutor?
            {
                $this->_calWarnTutor($row->tutor_ID, $row->customer_ID, $row, true);
                $this->_calWarnStud($row->customer_ID, $row->tutor_ID, $row, true);
            }
            else { $this->_calWarnTutor($row->tutor_ID, $row->customer_ID, $row); }
        }
        private function _calWarnStud($customer_ID, $tutor_ID, $row, $admin = false)
        {
            if (!is_numeric($customer_ID) || !is_numeric($tutor_ID)) return;
            $this->load->model('Customers_model');
            $customer = $this->Customers_model->getUser($customer_ID);
            $this->load->model('Tutors_model');
            $tutor = $this->Tutors_model->getTutor($tutor_ID);
            $title = $row->details." has been changed";
            $by = ($admin === true) ? " an administrator" : "your tutor ".$tutor->name;
            $content = "<p>Hello, ".$customer->name."</p><p>We want to inform you that a ".$row->title." has been changed by ".$by.".</p>";
             if ($row->duration != $row->old_duration)
            { $content .= "<p>The duration used to be $row->old_duration minutes, but it is now ".$row->duration." minutes.</p>"; }
            if ($row->start_time != $row->old_time)
            {
                $content .= "<p>The time and date used to be ".date('H:i:s m/d/Y', strtotime($row->old_time)).". The new date and time are: ".date('H:i:s m/d/Y', strtotime($row->start_time)).".</p>";
            }
            $content .= "<p>If you think there is an error, feel free to contact us.</p>";
            $this->_calMailWarn($content, $title, $customer->email_list);
        }
        private function _calWarnTutor($tutor_ID, $customer_ID, $row, $admin = false)
        {
            if (!is_numeric($customer_ID) || !is_numeric($tutor_ID)) return;
            $this->load->model('Customers_model');
            $customer = $this->Customers_model->getUser($customer_ID);
            $this->load->model('Tutors_model');
            $tutor = $this->Tutors_model->getTutor($tutor_ID);
            $title = $row->details." has been changed";
            $by = ($admin === true) ? " an administrator" : "your student ".$customer->name;
            $content = "<p>Hello, ".$tutor->name."</p><p>We want to inform you that a ".$row->title." has been changed by ".$by.".</p>";
            if ($row->duration != $row->old_duration)
            {  $content .= "<p>The duration used to be $row->old_duration minutes, but it is now ".$row->duration." minutes.</p>";  }
            if ($row->start_time != $row->old_time)
            {
                $content .= "<p>The time and date used to be ".date('H:i:s m/d/Y', strtotime($row->old_time)).". The new date and time are: ".date('H:i:s m/d/Y', strtotime($row->start_time)).".</p>";
            }
            $content .= "<p>If you think there is an error, feel free to contact us.</p>";
            $this->_calMailWarn($content, $title, $tutor->email_list);
        }
        private function _calMailWarn($content, $subject, $email)
        {
              $config = $this->config->item('smtpsettings');
              $this->email->initialize($config);
              $emailTo = $this->My_model->select_where('admins', array( 'username' => 'admin' ));
              $this->email->from($emailTo->email);
              $this->email->to($email);
              $this->email->subject('Professional 101 spanish | '.$subject);
              $this->email->message($content);
              $this->email->send();
        }
         
 }
?>
