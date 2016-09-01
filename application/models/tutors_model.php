<?php
class Tutors_model  extends CI_Model{
     public function __construct()
     {
		parent::__construct();
		 
     }
      public function getAdminPurchases($id)//this is for tutors. Different than the one in customers_model.php
      {
          if (!is_numeric($id)) return;
          $dateCreated = '';
          $theTime = (isset($_POST['theTime'])) ? $_POST['theTime'] : '';
          if ($theTime == 3)
          {
              $DatesJSON = (isset($_POST['DatesJSON'])) ? $_POST['DatesJSON'] : '';
              $DatesJSON = json_decode($DatesJSON);
              $dateCreated = " AND date_created BETWEEN ".$DatesJSON[0]." AND ".$DatesJSON[1];
          }
          elseif ($theTime == 2)
          {  
              $month = 3600 * 24 * 30;
              $month = time() - ($month);
              $dateCreated = " AND (date_created BETWEEN ".$month." AND ".time().")";
          }
          elseif ($theTime == 1)
          {
              $month = 3600 * 24 * 30;
              $month = time() - $month;
              $month2 = time() - ($month * 2);
              $dateCreated = " AND (date_created BETWEEN $month2 AND ".$month.")";
          }
          $theOrder = (isset($_GET['order']) && is_numeric($_GET['order']) && $_GET['order'] < 2) ? $_GET['order'] : 0; 
          $DESCASC = ($theOrder == 0) ? 'DESC' : 'ASC';
          $queryStr = "SELECT * FROM purchases WHERE tutor_id = $id $dateCreated ORDER BY purchase_id $DESCASC LIMIT 100";
          
          if($this->db->query($queryStr)->num_rows() > 0)
          {
             foreach($this->db->query($queryStr)->result() as $row) { $data[] = $row; } 
          }
          return (isset($data)) ? $data : false;
      }
      public function getPurchases($id)//this is for tutors. Different than the one in customers_model.php
      {
          if (!is_numeric($id)) return;
           
          $theOrder = (isset($_GET['order']) && is_numeric($_GET['order']) && $_GET['order'] < 2) ? $_GET['order'] : 0; 
          $DESCASC = ($theOrder == 0) ? 'DESC' : 'ASC';
          $queryStr = "SELECT * FROM purchases WHERE tutor_id = $id ORDER BY purchase_id $DESCASC LIMIT 100";
          if($this->db->query($queryStr)->num_rows() > 0)
          {
             foreach($this->db->query($queryStr)->result() as $row) { $data[] = $row; } 
          }
          return (isset($data)) ? $data : false;
      }
     public function countStudents($id)//this is for tutors. Different than the one in customers_model.php
     {
           $queryStr = "SELECT count(*) as stud FROM purchases WHERE tutor_id = $id GROUP BY customer_id";
          $data = $this->db->query($queryStr)->result();
          return count($data);
     }
     public function sumInvoices($id)//this is for tutors. Different than the one in customers_model.php
     {
          $queryStr = "SELECT * FROM purchases WHERE tutor_id = $id AND date_paid = 0";
          $data = 0;
          if($this->db->query($queryStr)->num_rows() > 0)
          {
             foreach($this->db->query($queryStr)->result() as $row) { $data += (float)$row->price_paid; } 
          }
         return $data;
     }
     public function sumPaid($id)//this is for tutors. Different than the one in customers_model.php
     {
          $queryStr = "SELECT * FROM purchases WHERE tutor_id = $id AND date_paid != 0";
          $data = 0;
          if($this->db->query($queryStr)->num_rows() > 0)
          {
             foreach($this->db->query($queryStr)->result() as $row) { $data += (float)$row->price_paid; } 
          }
         return $data;
     }
     public function countUnpaid($id)//this is for tutors. Different than the one in customers_model.php
     {
          $queryStr = "SELECT count(*) as quant FROM purchases WHERE tutor_id = $id AND date_paid = 0";
          $data = $this->db->query($queryStr)->result();
          return $data[0]->quant;
     }
     public function countRecur($id)
     { return $this->countItem($id, 2); }
     public function countFlex($id)
     { return $this->countItem($id, 1); }
     private function countItem($id, $item_id)//this is for tutors. Different than the one in customers_model.php
     {
          if (!is_numeric($id)) return;
          $queryStr = "SELECT count(*) as quant FROM purchases WHERE item_id = $item_id AND tutor_id = $id";
          $data = $this->db->query($queryStr)->result();
          return $data[0]->quant;
     }
     public function getTutors()
     {
          if (!isset($_GET['start']) || !is_numeric($_GET['start'])) $_GET['start'] = 0;
         $where = '';
          $start              = $_GET['start'];//$this->uri->segment(3) ? $this->uri->segment(3) : 0;
         //echo "start is $start";
          $config['per_page'] = 5;
           $config['total_rows'] = $this->db->query("SELECT * FROM tutors")->num_rows();
        //$queryStr .= " LIMIT {$start}, {$config['per_page']}";
        $config['base_url'] = base_url()."tutors?";
        $config['page_query_string'] = TRUE;
        //$config['reuse_query_string'] = TRUE;
        $redirect                    = isset($_SERVER['REDIRECT_QUERY_STRING']) ? $_SERVER['REDIRECT_QUERY_STRING'] : '';
        
        $redirect                    = explode('&', urldecode($redirect));
        $newRequest                  = array();
        if(is_array($redirect) && count($redirect) > 1){
            foreach($redirect as $value){
                if($value){
                    $value_2 = explode('=', $value);
                    if(!is_array($value_2)) continue;
                    if($value_2[1] == '' || $value_2[0] == '') continue;
                    if(strtolower($value_2[0]) == 'start') continue;
                    if(strtolower($value_2[0]) == 'purchase') continue;
                    $newRequest[] = $value_2[0] . "=" . $value_2[1];
                }
            }
        }
        $newRequest                     = implode('&', $newRequest);
         
        $config['query_string_segment'] =   "start". $newRequest;
        //echo "---> ".$config['query_string_segment']."<---- ";
        $get                            = $_GET;
        if(isset($get) && isset($get['start'])){
            unset($get['start']);
        }
        //echo '&' . http_build_query($get);
       /* if(count($get) > 0 || $_GET['start'] == '')*/{ $config['suffix'] = '&' . http_build_query($get);}//this is for the queries already in the URL
       $config['first_url'] = $config['base_url'] . $config['suffix']; 
       $this->pagination->initialize($config);
         
        
          $pages = $this->pagination->create_links();
         
          
         
         
         
         $queryStr      = "SELECT * FROM tutors " . $where . " ORDER BY name ASC LIMIT {$start}, {$config['per_page']}";
         if($this->db->query($queryStr)->num_rows() > 0){
            foreach($this->db->query($queryStr)->result() as $row){
                $data[] = $row;
            }
            return  array($data, $pages);
        }
        return array(false, false);
     }
     public function getTutor($id)
     {
         if (!is_numeric($id)) return;
	 $queryStr  = "SELECT * FROM tutors WHERE tutor_ID = $id LIMIT 1";
         if($this->db->query($queryStr)->num_rows() > 0)
         {
            $data = $this->db->query($queryStr)->result();
            return $data[0];
         }
         return false;
     }
     public function getTutorPhoto($id)
     {
         if (!is_numeric($id)) return;
	 $queryStr      = "SELECT photo FROM tutors WHERE tutor_ID = $id LIMIT 1";
         if($this->db->query($queryStr)->num_rows() > 0)
         {
            $data = $this->db->query($queryStr)->result();
            return $data[0]->photo;
         }
         return false;
     }
     public function adminTutors($search = '')
     {
         $AND = '';
         if ($search != '')
         {
            $AND .= " AND (a.name LIKE '%".sanitize_input($search)."%' OR a.email_list LIKE '%".sanitize_input($search)."%')";
         } 
         $DESCASC = 'ASC';//($theOrder == 0) ? 'DESC' : 'ASC';
         $queryStr      = "SELECT a.* FROM tutors a WHERE a.tutor_ID > 0 $AND GROUP BY a.tutor_ID ORDER BY a.name $DESCASC LIMIT 100";
         if($this->db->query($queryStr)->num_rows() > 0)
         {
            foreach($this->db->query($queryStr)->result() as $row){ $data[$row->tutor_ID] = $row; } return $data; 
         }
         return false;
     }
     public function adminStudents($search = '')
     {
        $recently = isset($_GET['rect']) ? $_GET['rect'] : 3;
        $order = isset($_GET['order']) ? $_GET['order'] : 0;
        $AND = '';
        if ($order == 1) 
        {
            $start = time() - ($recently * (30 * 24 * 3600));
            $start = date("Y-m-d", $start);
            $end = date("Y-m-d", time());
            $AND = 'AND (STR_TO_DATE(`date_reg`,"%Y-%m-%d") BETWEEN "'.$start.'" AND "'.$end.'")';
        }
        elseif ($order == 2)
        {
            $AND = " AND c.customer_id IS NULL";
        }
        if ($search != '')
        {
            $AND .= " AND (a.name LIKE '%".sanitize_input($search)."%' OR a.email_list LIKE '%".sanitize_input($search)."%')";
        } 
         //$theOrder = (isset($_GET['order']) && is_numeric($_GET['order']) && $_GET['order'] < 2) ? $_GET['order'] : 0; 
        $DESCASC = 'DESC';//($theOrder == 0) ? 'DESC' : 'ASC';
        //$queryStr      = "SELECT a.* FROM customers a LEFT JOIN purchases c ON c.customer_id = a.Customer_ID INNER JOIN calendarEntries b ON b.customer_ID = a.Customer_ID WHERE b.tutor_ID > 0 $AND GROUP BY a.Customer_ID ORDER BY a.name $DESCASC LIMIT 100";
        
        if ($order == 2)
            $queryStr      = "SELECT a.* FROM customers a LEFT JOIN purchases c ON c.customer_id = a.Customer_ID INNER JOIN calendarEntries b ON b.customer_ID = a.Customer_ID WHERE b.tutor_ID > 0 $AND GROUP BY a.Customer_ID ORDER BY a.name $DESCASC LIMIT 100";
        
        else { $queryStr      = "SELECT a.* FROM customers a WHERE a.Customer_ID > 0 $AND GROUP BY a.Customer_ID ORDER BY a.name $DESCASC LIMIT 100";
        }
        //echo $queryStr;
         if($this->db->query($queryStr)->num_rows() > 0){
            foreach($this->db->query($queryStr)->result() as $row){
                $data[$row->Customer_ID] = $row;
            }
            return $data;
         }
         else return false;
     }
     public function tutorStudents($id, $search = '')
     {
        if (!is_numeric($id)) return;
        $recently = isset($_GET['rect']) ? $_GET['rect'] : 3;
        $order = isset($_GET['order']) ? $_GET['order'] : 0;
        $AND = '';
        if ($order == 1) 
        {
            $start = time() - ($recently * (30 * 24 * 3600));
            $start = date("Y-m-d", $start);
            $end = date("Y-m-d", time());
            $AND = 'AND (STR_TO_DATE(`date_reg`,"%Y-%m-%d") BETWEEN "'.$start.'" AND "'.$end.'")';
        }
        elseif ($order == 2)
        {
            $AND = " AND c.customer_id IS NULL";
        }
        if ($search != '')
        {
            $AND .= " AND (a.name LIKE '%".sanitize_input($search)."%' OR a.email_list LIKE '%".sanitize_input($search)."%')";
        } 
         //$theOrder = (isset($_GET['order']) && is_numeric($_GET['order']) && $_GET['order'] < 2) ? $_GET['order'] : 0; 
        $DESCASC = 'DESC';//($theOrder == 0) ? 'DESC' : 'ASC';
        $queryStr      = "SELECT a.* FROM customers a LEFT JOIN purchases c ON c.customer_id = a.Customer_ID INNER JOIN calendarEntries b ON b.customer_ID = a.Customer_ID WHERE b.tutor_ID = $id $AND GROUP BY a.Customer_ID ORDER BY a.name $DESCASC LIMIT 100";
        //echo $queryStr;
        if($this->db->query($queryStr)->num_rows() > 0){
            foreach($this->db->query($queryStr)->result() as $row){
                $data[$row->Customer_ID] = $row;
            }
            return $data;
         }
         return false;
     }
     public function editTutorData()
     {
         $this->form_validation->set_error_delimiters('<p class="fl" style="color:red; padding-top:5px; padding-left:5px;">', '</p>');
         $this->form_validation->set_rules('name', 'Name', 'required|is_unique[admins');
         $this->form_validation->set_rules('phone', 'Phone', 'required');
         $this->form_validation->set_rules('password', 'Password', 'matches[cpassword]');
         $this->form_validation->set_rules('emails', 'Email', 'required|valid_email|'); 
         $names = $this->input->post('name');
         $pword = $this->input->post('password');
         $cpword = $this->input->post('cpassword');
         $email = $this->input->post('emails');
         $user_type = $this->input->post('user_type');
         $notes_tutor_viewable = $this->input->post('notes_tutor_viewable');
         $notes_hidden_from_tutor = $this->input->post('notes_hidden_from_tutor');
         $phone = $this->input->post('phone');
         $description = $this->input->post('description');
         $skype_name = $this->input->post('skype_name');
         $deleted = (isset($_POST['deleted']) && $_POST['deleted'] == 'on') ? 1 : 0;
         $inactive = (isset($_POST['inactive']) && $_POST['inactive'] == 'on') ? 1 : 0;
	 $to_insert = array(
                'name'=>$names,
                'email_list'=>$email,
              'bio' => $description,
              'phone' => $phone,
              'skype_name' =>  $skype_name,
              'time_zone' => $this->input->post('time_zone'),
              'inactive' => $inactive,
             'deleted' => $deleted,
             'notes_tutor_viewable' => $notes_tutor_viewable,
             'notes_hidden_from_tutor' => $notes_hidden_from_tutor
        );
          
          if (trim($pword) != '')
          {
             $to_insert['password'] = md5($pword);
          }
          if ($this->form_validation->run() == FALSE){
              $data['success'] = "<p style='color: #a00;'>ERROR &nbsp;</p>";
          }
         else{
               $thenewid = $_POST['tutor_ID'];
                $where = array("tutor_ID" => $thenewid);

                $this->My_model->update_where('tutors',$to_insert, $where);
               /// $thenewid = $this->db->insert_id();
                $config['upload_path'] = './uploads/tutors';
                $config['allowed_types'] = 'gif|jpg|jpeg|jpe|png|pdf';
                $config['max_size']	= '1000';
                $this->upload->initialize($config); 
                 
                if($this->upload->do_upload('rolephoto'))//yes, someone chose to upload a file
                { 
                   $uploaded = $this->upload->data('rolephoto');
                   $subject = $uploaded['file_type'];
                               $width = $uploaded['image_width'];
                                            $height = $uploaded['image_height'];
                    //get rid of the old image:
                    $qry = "select * from tutors WHERE tutor_ID = $thenewid";
                    $tutor = $this->db->query($qry)->row();
                    if ($tutor->photo) {
                        $prior = get_file_info('./uploads/tutors/' . $tutor->photo); 
                        unlink($prior['server_path']); }

                    if ($width > 400)
                    {
                            $height = $height * (400 / $width);
                            $width = 400;
                    }
                    elseif ($height > 400)
                    {
                            $width = $width * (400 / $height);
                            $height = 400;
                    }
                    $this->load->library('image_moo');
                    $imgfile      = $uploaded['file_name'];
                    $this->image_moo
                    ->load('./uploads/tutors/' . $imgfile)
                    ->resize_crop($width, $height)
                    ->save_pa($prepend = "", $append = "", $overwrite = true);
                    //$thenewid;
                    $where = array("tutor_ID" => $thenewid);
                    $to_update = array('photo' => $imgfile);
                    $this->My_model->update_where('tutors', $to_update, $where);
                    
                }
                $data['success'] = "<p style='color:green !important;'>Successfully Edited User</p>";
           }
           return $data;
     }
     public function firstDate()
     {
          $query = "SELECT * FROM purchases ORDER BY date_created ASC LIMIT 1";
          $data = $this->db->query($query)->result();
          return $data[0]->date_created;
     }
     public function lateDate()
     {
          $query = "SELECT * FROM purchases ORDER BY date_created DESC LIMIT 1";
          $data = $this->db->query($query)->result();
          return $data[0]->date_created;
     }
 }
