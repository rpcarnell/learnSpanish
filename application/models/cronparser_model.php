<?php
/*
 * Mick Sear, eCreate, May 2005
 * http://www.ecreate.co.uk
 * License: GPL
 * Version: 1.1
 */

 
 /*$cron  = "15,33,48 1-10 * * *";
 $cp = new CronParser($cron);
 echo "Cron $cron last ran on:";
 
 echo nl2br($cp->getDebug());*/


 
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
                            if ((int)$time_passed > (int)$globals->{'max-pause-days'})
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
            elseif ($row->updater == 3)
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
 	function getNow(){
		$t = strftime("%M,%H,%d,%m,%w,%Y", time()); //Get the values for now in a format we can use
		$this->now = explode(",", $t); //Make this an array
	 }
 	
 	function getLastRan(){
 		return explode(",", strftime("%M,%H,%d,%m,%w,%Y", $this->lastRan)); //Get the values for now in a format we can use	
 	}
 	
 	function getDebug(){
 		return $this->debug;	
 	}
 	
	function debug($str){
		if (is_array($str)){
			$this->debug .= "\nArray: ";
			foreach($str as $k=>$v){
				$this->debug .= "$k=>$v, ";
			}
	
		} else {
			$this->debug .= "\n$str";
		}
		//echo nl2br($this->debug);
		
	} 	
 	

	function getExtremeMonth($extreme){

		if ($extreme == "END"){
			$year = $this->now[5] - 1;
		} else {
			$year = $this->now[5];
		}
		
		//Now determine start or end month in the last year
		if ($this->bits[3] == "*" && $extreme == "END"){//Check month format
			$month = 12;			
		} else if ($this->bits[3] == "*" && $extreme == "START"){
			$month = 1;
		} else {
			$months = $this->expand_ranges($this->bits[3]);
			if ($extreme == "END"){
				sort($months);
			} else {
				rsort($months);
			}
			$month = array_pop($months);
		}
		
		//Now determine the latest day in the specified month
		$day=$this->getExtremeOfMonth($month, $year, $extreme);
		$this->debug("Got day $day for $extreme of $month, $year");
		$hour = $this->getExtremeHour($extreme);
		$minute = $this->getExtremeMinute($extreme);
		
		return mktime((int)$hour, (int)$minute, 0, (int)$month, (int)$day, (int)$year);
	}
	
	/**
	 * Assumes that value is not *, and creates an array of valid numbers that 
	 * the string represents.  Returns an array.
	 */
	function expand_ranges($str){
		//$this->debug("Expanding $str");
		if (strstr($str,  ",")){
			$tmp1 = explode(",", $str);
			//$this->debug($tmp1);
			$count = count($tmp1);
			for ($i=0;$i<$count;$i++){//Loop through each comma-separated value
				if (strstr($tmp1[$i],  "-")){ //If there's a range in this place, expand that too
					$tmp2 = explode("-", $tmp1[$i]);
					//$this->debug("Range:");
					//$this->debug($tmp2);
					for ($j=$tmp2[0];$j<=$tmp2[1];$j++){
						$ret[] = $j;
					}
				} else {//Otherwise, just add the value
					$ret[] = $tmp1[$i];
				}
			} 
		} else if (strstr($str,  "-")){//There might only be a range, no comma sep values at all.  Just loop these
			$range = explode("-", $str);
			for ($i=$range[0];$i<=$range[1];$i++){
				$ret[] = $i;
			}
		} else {//Otherwise, it's a single value
			$ret[] = $str;
		}
		//$this->debug($ret);
		return $ret;
	}
	
	/**
	 * Given a string representation of a set of weekdays, returns an array of
	 * possible dates.
	 */
	function getWeekDays($str, $month, $year){
		$daysInMonth = $this->daysinmonth($month, $year);
		
		if (strstr($str,  ",")){
			$tmp1 = explode(",", $str);
			$count = count($tmp1);
			for ($i=0;$i<$count;$i++){//Loop through each comma-separated value
				if (strstr($tmp1[$i],  "-")){ //If there's a range in this place, expand that too
					$tmp2 = explode("-", $tmp1[$i]);
					
					for ($j=$start;$j<=$tmp2[1];$j++){
						for ($n=1;$n<=$daysInMonth;$n++){
			 				if ($j == jddayofweek(gregoriantojd ( $month, $n, $year),0)){
			 					$ret[] = $n;
			 				}			 				
			 			}
					}
				} else {//Otherwise, just add the value
					for ($n=1;$n<=$daysInMonth;$n++){
	 					if ($tmp1[$i] == jddayofweek(gregoriantojd ( $month, $n, $year),0)){
	 						$ret[] = $n;
	 					}			 				
	 				}
				}
			} 
		} else if (strstr($str,  "-")){//There might only be a range, no comma sep values at all.  Just loop these
			$range = explode("-", $str);
			for ($i=$start;$i<=$range[1];$i++){
				for ($n=1;$n<=$daysInMonth;$n++){
	 				if ($i == jddayofweek(gregoriantojd ( $month, $n, $year),0)){
	 					$ret[] = $n;
	 				}			 				
	 			}
			}
		} else {//Otherwise, it's a single value
			for ($n=1;$n<=$daysInMonth;$n++){				
				if ($str == jddayofweek(gregoriantojd ( $month, $n, $year),0)){
					$ret[] = $n;
				}			 				
			}
		}
		
		return $ret;		
	}
	
 	function daysinmonth($month, $year){
       if(checkdate($month, 31, $year)) return 31;
       if(checkdate($month, 30, $year)) return 30;
       if(checkdate($month, 29, $year)) return 29;
       if(checkdate($month, 28, $year)) return 28;
       return 0; // error
   }	
   
   /**
    * Get the timestamp of the last ran time.
    */
   function calcLastRan(){
		$now = time();

		if ($now < $this->getExtremeMonth("START")){
			//The cron isn't due to have run this year yet.  Getting latest last year
			$this->debug("Last ran last year");
			$tsLatestLastYear = $this->getExtremeMonth("END");	
			
			$this->debug("Timestamp of latest scheduled time last year is ".$tsLatestLastYear);
			$this->lastRan = $tsLatestLastYear;
			
			$year = date("Y", $this->lastRan);
			$month = date("m", $this->lastRan);
			$day = date("d", $this->lastRan);
			$hour = date("h", $this->lastRan);
			$minute = date("i", $this->lastRan);		
			
			
		} else { //Cron was due to run this year.  Determine when it was last due
			$this->debug("Cron was due to run earlier this year");
	   		$year = $this->now[5];	   		
	   		
   			$arMonths = $this->expand_ranges($this->bits[3]);
   			if (!in_array($this->now[3], $arMonths) && $this->bits[3] != "*"){//Not due to run this month.  Get latest of last month
   				$this->debug("Cron was not due to run this month at all. This month is ".$this->now[3]);
   				$this->debug("Months array: ");
   				$this->debug($arMonths);
   				sort($arMonths);
				do{
					$month = array_pop($arMonths);
				} while($month > $this->now[3]);
				$day = $this->getExtremeOfMonth($month, $this->now[5], "END");
	   			$hour = $this->getExtremeHour("END");
	   			$minute = $this->getExtremeMinute("END");	
   			} else if ($now < $this->getExtremeOfMonth($this->now[3], $this->now[5], "START")){ //It's due in this month, but not yet.
   				$this->debug("It's due in this month, but not yet.");
   				sort($arMonths);
				do{
					$month = array_pop($arMonths);
				} while($month > $this->now[3]);
				$day = $this->getExtremeOfMonth($month, $this->now[5], "END");
	   			$hour = $this->getExtremeHour("END");
	   			$minute = $this->getExtremeMinute("END");
   			} else {//It has been due this month already
   				$this->debug("Cron has already been due to run this month (".$month = $this->now[3].")");
	   			$month = $this->now[3];		
	   			$this->debug("Getting days array");
	   			$days = $this->getDaysArray($this->now[3]);
	   			
	   			if (!in_array($this->now[2], $days)){
	   				$this->debug("Today not in the schedule.  Getting latest last due day");
	   				//No - Get latest last scheduled day   				
	   				sort($days);
	   				do{
	   					$day = array_pop($days);
	   				} while($day > $this->now[2]);
	   				
	   				$hour = $this->getExtremeHour("END");
	   				$minute = $this->getExtremeMinute("END");
	   				
	   			} else if($this->now[1] < $this->getExtremeHour("START")){//Not due to run today yet
	   				$this->debug("Cron due today, but not yet.  Getting latest on last day");
	   				sort($days);
	   				do{
	   					$day = array_pop($days);
	   				} while($day >= $this->now[2]);
	   				
	   				$hour = $this->getExtremeHour("END");
	   				$minute = $this->getExtremeMinute("END");
	   			} else {
	   				$this->debug("Cron has already been due to run today");
	   				$day = $this->now[2];
	   				//Yes - Check if this hour is in the schedule?
	   				
	   				$arHours = $this->expand_ranges($this->bits[1]);
	   				
	   				if (!in_array($this->now[1], $arHours) && $this->bits[1] != "*"){
	   					$this->debug("Cron not due in this hour, getting latest in last scheduled hour");
	   					//No - Get latest last hour
	   					sort($arHours);
	   					do{
	   						$hour = array_pop($arHours);
	   						//$this->debug("hour is $hour, now is ".$this->now[1]);
	   					} while($hour > $this->now[1]);
	   					
	   					$minute = $this->getExtremeMinute("END");
	   					
	   				} else if ($now < $this->getExtremeMinute("START") && $this->bits[1] != "*"){ //Not due to run this hour yet
	   					sort($arHours);
	   					do{
	   						$hour = array_pop($arHours);
	   					} while($hour >= $this->now[1]);
	   					$minute = $this->getExtremeMinute("END");
	   				} else {
	   					//Yes, it is supposed to have run this hour already - Get last minute
	   					$hour = $this->now[1];
	   					if ($this->bits[0] != "*"){
		   					$arMinutes = $this->expand_ranges($this->bits[0]);
		   					$this->debug($arMinutes);
		   					do{
		   						$minute = array_pop($arMinutes);		   						
		   					} while($minute >= $this->now[0]);
		   					
		   					//If the first time in the hour that the cron is due to run is later than now, return latest last hour
		   					if($minute > $this->now[1] || $minute == ""){
		   						$this->debug("Valid minute not set");
		   						$minute = $this->getExtremeMinute("END"); //The minute will always be the last valid minute in an hour
		   						//Get the last hour.
		   						if ($this->bits[1] == "*"){
		   							$hour = $this->now[1] - 1;
		   						} else {
			   						$arHours = $this->expand_ranges($this->bits[1]);
			   						$this->debug("Array of hours:");
			   						$this->debug($arHours);
			   						sort($arHours);
		   							do{
		   								$hour = array_pop($arHours);
		   							} while($hour >= $this->now[1]);
		   						}
		   					}
		   					
	   					} else {
	   						$minute = $this->now[0] -1; 
	   					}
	   				}  				
	   				
	   			}
	   			
	   		} 
		}
   		$this->debug("LAST RAN: $hour:$minute on $day/$month/$year");
   		$this->lastRan = mktime((int)$hour, (int)$minute, 0, (int)$month, (int)$day, (int)$year);

   }
     
   function getExtremeOfMonth($month, $year, $extreme){
   		$daysInMonth = $this->daysinmonth($month, $year);
		if ($this->bits[2] == "*"){
			if ($this->bits[4] == "*"){//Is there a day range?
				//$this->debug("There were $daysInMonth days in $month, $year");
				if ($extreme == "END"){
					$day = $daysInMonth;
				} else {
					$day=1;
				}
			} else {//There's a day range.  Ignore the dateDay range and just get the list of possible weekday values.
				$days = $this->getWeekDays($this->bits[4],$month, $year);
				$this->debug($this->bits);
				$this->debug("Days array for ".$this->bits[4].", $month, $year:");
				$this->debug($days);
				if ($extreme == "END"){
					sort($days);
				} else {
					rsort($days);	
				}
				$day = array_pop($days);
			}
		} else {
			$days = $this->expand_ranges($this->bits[2]);
			if ($extreme == "END"){
				sort($days);
			} else {
				rsort($days);	
			}
			
			do {
				$day = array_pop($days);
			} while($day > $daysInMonth);
		}	
		//$this->debug("$extreme day is $day");
		return $day;
   }
     
   function getDaysArray($month){
   		$this->debug("Getting days for $month");
   		$days = array();
   		
   		if ($this->bits[4] != "*"){   			
   			$days = $this->getWeekDays($this->bits[4], $month, $this->now[5]);
   			$this->debug("Weekdays:");
   			$this->debug($days);
   		} 
		if ($this->bits[2] != "*" && $this->bits[4] == "*") {
			$days = $this->expand_ranges($this->bits[2]);
   		} 
   		if ($this->bits[2] == "*" && $this->bits[4] == "*"){
   			//Just return every day of the month
   			$daysinmonth = $this->daysinmonth($month, $this->now[5]);
   			$this->debug("Days in ".$month.", ".$this->now[5].": ".$daysinmonth);
   			for($i = 1;$i<=$daysinmonth;$i++){
   				$days[] = $i;
   			}
   		}
   		$this->debug($days);
   			
   		return $days;
   }
   
   function getExtremeHour($extreme){
   		if ($this->bits[1] == "*"){
			if ($extreme == "END"){
				$hour = 23;
			} else {
				$hour = 0;	
			}
		} else {
			$hours = $this->expand_ranges($this->bits[1]);
			if ($extreme == "END"){
				sort($hours);
			} else {
				rsort($hours);	
			}
			$hour = array_pop($hours);
		}
		//$this->debug("$extreme hour is $hour");
		return $hour;
   }
   
   function getExtremeMinute($extreme){
		if ($this->bits[0] == "*"){
			if ($extreme == "END"){
				$minute = 59;
			} else {
				$minute = 0;	
			}
		} else {
			$minutes = $this->expand_ranges($this->bits[0]);
			if ($extreme == "END"){
				sort($minutes);
			} else {
				rsort($minutes);	
			}
			$minute = array_pop($minutes);
		}
		//$this->debug("$extreme minute is $minute");
		return $minute;
   }

 }
?>
