<?php
class Timezones_model extends CI_Model
{
     public function __construct()
    {
        
    }
    public function adminTimeZone($id)
    {
        if (!is_numeric($id)) return false;
        $query = "SELECT time_zone FROM admins WHERE admin_id = $id LIMIT 1";
        $row = $this->db->query($query)->result();
        return is_numeric($row[0]->time_zone) ? $row[0]->time_zone : 0;
    }
    public function tutorTimeZone($id)
    {
        if (!is_numeric($id)) return false;
        $query = "SELECT time_zone FROM tutors WHERE tutor_ID = $id LIMIT 1";
        $row = $this->db->query($query)->result();
        return is_numeric($row[0]->time_zone) ? $row[0]->time_zone : 0;
    }
    public function userTimeZone($id)
    {
        if (!is_numeric($id)) return false;
        $query = "SELECT time_zone FROM customers WHERE Customer_ID = $id LIMIT 1";
        $row = $this->db->query($query)->result();
        return is_numeric($row[0]->time_zone) ? $row[0]->time_zone : 0;
    }
    public function getUserZone($id, $tutor = false, $admin = false)
    {
        if (!is_numeric($id)) return false;
        if ($tutor)
        { $query = "SELECT time_zone FROM tutors WHERE tutor_ID = $id LIMIT 1"; }  
        elseif ($admin)
        { $query = "SELECT time_zone FROM admins WHERE admin_id = $id LIMIT 1"; }  
        else { $query = "SELECT time_zone FROM customers WHERE Customer_ID = $id LIMIT 1"; }
        $row = $this->db->query($query)->result();
         
         $timezone_offsets = $this->_timezones_Main();
        // sort timezone by offset
         $useThis = 0;
        asort($timezone_offsets);
        if (strpos($row[0]->time_zone, '-0.0') > -1 )
        { $row[0]->time_zone = str_replace('-0.0', ' 0:00', $row[0]->time_zone); }
        elseif (strpos($row[0]->time_zone, '.5') > -1 )
        { $row[0]->time_zone = str_replace('.5', ':30', $row[0]->time_zone); }
        elseif (strpos($row[0]->time_zone, '.0') > -1 )
        { $row[0]->time_zone = str_replace('.0', ':00', $row[0]->time_zone); }
        if (strpos($row[0]->time_zone, ':') > -1 )
        {
             foreach( $timezone_offsets as $timezone => $offset )
             {
                  
                  $offset_prefix = $offset < 0 ? '-' : '+';
                  $searchfor = $offset_prefix.gmdate( 'g:i', abs($offset));
                  if ($searchfor == $row[0]->time_zone)
                  {
                      $useThis = $offset_formatted = gmdate( 'H:i', abs($offset) );
                      $useThis = $offset_prefix.$useThis;
                  }
              }
        }
        $offsets = $this->timezone_offsets();
         return (isset($offsets["UTC".$useThis])) ? $offsets["UTC".$useThis] : 'America/Rankin_Inlet';
    }
    public function timezone_offsets()
    {
        $timezone_offsets = $this->_timezones_Main();
        // sort timezone by offset
        asort($timezone_offsets);
        $timezone_list = array();
        foreach( $timezone_offsets as $timezone => $offset )
        {
            $offset_prefix = $offset < 0 ? '-' : '+';
            $offset_formatted = gmdate( 'H:i', abs($offset) );
            $pretty_offset = "UTC${offset_prefix}${offset_formatted}";
            $timezone_list["UTC${offset_prefix}${offset_formatted}"] = "$timezone";
        }
        return $timezone_list;
    }
    public function generate_timezone_list()
    {
        $timezone_offsets = $this->_timezones_Main();
        // sort timezone by offset
        asort($timezone_offsets);
        $timezone_list = array();
        foreach( $timezone_offsets as $timezone => $offset )
        {
            $offset_prefix = $offset < 0 ? '-' : '+';
            $offset_formatted = gmdate( 'H:i', abs($offset) );
            $pretty_offset = "UTC${offset_prefix}${offset_formatted}";
            $timezone_list[$timezone] = "(${pretty_offset}) $timezone";
        }
        return $timezone_list;
    }
    private function _timezones_Main()
    {
        static $regions = array(
            DateTimeZone::AFRICA,
            DateTimeZone::AMERICA,
            DateTimeZone::ANTARCTICA,
            DateTimeZone::ASIA,
            DateTimeZone::ATLANTIC,
            DateTimeZone::AUSTRALIA,
            DateTimeZone::EUROPE,
            DateTimeZone::INDIAN,
            DateTimeZone::PACIFIC,
        );
        $timezones = array();
        foreach( $regions as $region )
        { $timezones = array_merge( $timezones, DateTimeZone::listIdentifiers( $region ) ); }
        $timezone_offsets = array();
        foreach( $timezones as $timezone )
        {
            $tz = new DateTimeZone($timezone);
            $timezone_offsets[$timezone] = $tz->getOffset(new DateTime);
        }
        return $timezone_offsets;
    }
}