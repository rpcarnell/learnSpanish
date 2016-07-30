<?php
// copied bdi
if(!defined('BASEPATH')) exit('No direct script access allowed');
class Events extends CI_Controller {
    public function __construct() {
        parent::__construct();
    }
    public function musicians() {
        $qry = "SELECT *
				FROM (
					SELECT d.dancer_id,fname, lname,CONCAT(fname,' ',lname) as fullname, title, location, date, time1, time2,CONCAT(time1, '-',time2) as time, details,
																		dance_style, special_skills, exp, dance_ints_type, event_id
					FROM dancer d
						INNER JOIN event
							ON d.dancer_id = event.dancer_id
					WHERE act = '1' AND type='musician'
					) as tbl";
        if(isset($_POST['search'])){
            $fl = 0;
            if($_POST['name'] != ''){
                $st = $_POST['name'];
                $qry .= " where fname like '%$st%' or lname like '%$st%' or fullname like '%$st%' ";
                $fl = 1;
            }
            if($_POST['location'] != ''){
                if($fl == 0)
                    $qry .= " where ";
                else
                    $qry .= " or ";
                $st = $_POST['location'];
                $qry .= " location like '%$st%'";
                $fl = 1;
            }
            if($_POST['shimmy-1'] != ''){
                if($fl == 0)
                    $qry .= " where ";
                else
                    $qry .= " or ";
                $st = $_POST['shimmy-1'];
                $qry .= " dance_style like '%$st%'";
                $fl = 1;
            }
            if($_POST['shimmy-2'] != ''){
                if($fl == 0)
                    $qry .= " where ";
                else
                    $qry .= " or ";
                $st = $_POST['shimmy-2'];
                $qry .= " exp like '%$st%'";
                $fl = 1;
            }
            if($_POST['shimmy-3'] != ''){
                if($fl == 0)
                    $qry .= " where ";
                else
                    $qry .= " or ";
                $st = $_POST['shimmy-3'];
                $qry .= " special_skills like '%$st%'";
                $fl = 1;
            }
            if($_POST['shimmy-4'] != ''){
                if($fl == 0)
                    $qry .= " where ";
                else
                    $qry .= " or ";
                $st = $_POST['shimmy-4'];
                $qry .= " dance_ints_type like '%$st%'";
                $fl = 1;
            }
        }
        echo $qry;
        $data['query']        = $this->db->query($qry)->result();
        $data['page_title']   = 'Professional 101 spanish | Events';
        $data['main_content'] = 'front/events_view';
        $data['type'] = 'musician';
        $this->load->view('front/includes/main_view', $data);
    }
    public function index() {
        $qry = "SELECT *
				FROM (
					SELECT d.dancer_id,fname, lname,CONCAT(fname,' ',lname) as fullname, title, location, date, time1, time2,CONCAT(time1, '-',time2) as time, details,
																		dance_style, special_skills, exp, dance_ints_type, event_id
					FROM dancer d
						INNER JOIN event
							ON d.dancer_id = event.dancer_id
					WHERE act = '1' AND type='dancer'
					) as tbl";
        if(isset($_POST['search'])){
            $fl = 0;
            if($_POST['name'] != ''){
                $st = $_POST['name'];
                $qry .= " where fname like '%$st%' or lname like '%$st%' or fullname like '%$st%' ";
                $fl = 1;
            }
            if($_POST['location'] != ''){
                if($fl == 0)
                    $qry .= " where ";
                else
                    $qry .= " or ";
                $st = $_POST['location'];
                $qry .= " location like '%$st%'";
                $fl = 1;
            }
            if($_POST['shimmy-1'] != ''){
                if($fl == 0)
                    $qry .= " where ";
                else
                    $qry .= " or ";
                $st = $_POST['shimmy-1'];
                $qry .= " dance_style like '%$st%'";
                $fl = 1;
            }
            if($_POST['shimmy-2'] != ''){
                if($fl == 0)
                    $qry .= " where ";
                else
                    $qry .= " or ";
                $st = $_POST['shimmy-2'];
                $qry .= " exp like '%$st%'";
                $fl = 1;
            }
            if($_POST['shimmy-3'] != ''){
                if($fl == 0)
                    $qry .= " where ";
                else
                    $qry .= " or ";
                $st = $_POST['shimmy-3'];
                $qry .= " special_skills like '%$st%'";
                $fl = 1;
            }
            if($_POST['shimmy-4'] != ''){
                if($fl == 0)
                    $qry .= " where ";
                else
                    $qry .= " or ";
                $st = $_POST['shimmy-4'];
                $qry .= " dance_ints_type like '%$st%'";
                $fl = 1;
            }
        }
        $data['query']        = $this->db->query($qry)->result();
        $data['page_title']   = 'Professional 101 spanish | Events';
        $data['main_content'] = 'front/events_view';
        $data['type'] = 'dancer';
        ///print_r($data);
        $this->load->view('front/includes/main_view', $data);
    }

    public function unsubscribe($email) {

        $data['email'] = urldecode($email);
        $this->db->query("DELETE FROM subscribers WHERE email ='" . $data['email'] . "'");
        $data['page_title']   = 'Professional 101 spanish | Unsubscribe';
        $data['main_content'] = 'front/unsubscribe_view';
        $this->load->view('front/includes/main_view', $data);
    }
}
