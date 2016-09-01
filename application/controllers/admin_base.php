<?php
// copied bdi
if(!defined('BASEPATH')) exit('No direct script access allowed');

class Admin_base extends CI_Controller {
      protected function _addNewAdmin($adminCredentials, $to_update, $second_error)
    {
         $success = false;
         
         $this->load->model('admin_model');
         $editor = $this->admin_model->getAdminById($adminCredentials['admin_id']);//admin doing the editing
         if ($to_update['admin_type'] > 1 && $editor->admin_type < 2)
         { 
                $success = "<span style='color: #a00;'>ERROR - you cannot create a super-administrator. </span><br />";
                $to_update['admin_type'] = 1;
         } 
         
         
         $to_update['username'] = strtolower($to_update['username']);
         if ($second_error === false) { $this->My_model->inserting('admins', $to_update); 
         $success = "Administrator Added  Successfully"; }
         if ($success) 
         {
              echo "<script>window.location.href='" .base_url()."admin/admin_setting'</script>";
         }
         return $success;
    }
    protected function _editExistingAdmin($adminCredentials, $to_update, $where, $second_error) //$_POST['email'], $_POST['customer_id']
    { 
         $success = false;
         $this->load->model('admin_model');
         $editor = $this->admin_model->getAdminById($adminCredentials['admin_id']);//admin doing the editing
         $oldUserData = $this->admin_model->getAdminById($where['admin_id']);
         $to_update['username'] = strtolower($to_update['username']);
         if ($oldUserData->email != $to_update['email'])
         {
             $queryStr = "SELECT * FROM admins WHERE email = '".sanitize_input($to_update['email'])."' AND admin_id !=".$oldUserData->admin_id." LIMIT 1";
             
             if($this->db->query($queryStr)->num_rows() > 0)
              {
                 $second_error = true;  $success = "<span style='color: #a00;'>ERROR - E-mail is being used by another administrator. </span><br />";
                 return array($second_error, $success);
              }
         }
         list($to_update, $success) = $this->_editExistingAdmi_2($oldUserData, $to_update, $editor, $success);
         $data = array(
                    'thread_id' => $this->uri->segment(4),
                    'sender_id' =>  $this->session->userdata('id'),
                    'name_surname' => $this->session->userdata('name_surname'),
                    'date' => NOW(),
                    'message' => $this->input->post('message'),
                    'readed' => '0');
         if ($second_error === false) { $this->My_model->update_where('admins', $to_update, $where); 
         $success = ($success) ? $success." Profile Successfully Edited" : "Profile Successfully Edited"; }
         return array($second_error, $success);
    }
    private function _editExistingAdmi_2($oldUserData, $to_update, $editor, $success)
    {
         if (($oldUserData->username != 'admin' && $to_update['username'] == 'admin') || ($oldUserData->username == 'admin' && $to_update['username'] != 'admin'))
         {
             $success = "<span style='color: #a00;'>ERROR - you cannot change the admin username. </span><br />";
             $to_update['username'] = $oldUserData->username;
         }
         if ($to_update['admin_type'] > 1 && $editor->admin_type < 2)
         { 
                $success = "<span style='color: #a00;'>ERROR - you cannot create a super-administrator. </span><br />";
                $to_update['admin_type'] = 1;
         }
         if ($oldUserData->username == 'admin')
         {
            if ($to_update['admin_type'] < 2)
            { 
                $success = "<span style='color: #a00;'>ERROR - you cannot downgrade the main administrator account. </span><br />";
                $to_update['admin_type'] = 2;
            }
         }
         return array($to_update, $success);
    }
    protected function _getStudentPurc($id, $tutor_id = '')
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
          if (is_numeric($tutor_id)) $tutor_id = "tutor_id = $tutor_id AND ";
          $queryStr = "SELECT * FROM purchases WHERE $tutor_id customer_id = $id $dateCreated ORDER BY purchase_id $DESCASC LIMIT 100";
          //echo $queryStr;
          if($this->db->query($queryStr)->num_rows() > 0)
          {
             foreach($this->db->query($queryStr)->result() as $row) { $data[] = $row; } 
          }
          return (isset($data)) ? $data : false;
      }
      protected function _editExistingCustomer($Customers_model, $email, $customer_id, $to_update, $where, $second_error) //$_POST['email'], $_POST['customer_id']
    { 
         $success = false;
         $emailerror = false;
         if ( $Customers_model->emailEditError($email, $customer_id) )
         {
           $second_error = true;
           $emailerror = "E-mail is already registered";
         }    
         if ($second_error === false) { $this->My_model->update_where('customers', $to_update, $where); 
         $success = "Profile Successfully Edited"; }
         return array($second_error, $emailerror, $success);
    }
    protected function _addNewCustomer($to_update, $second_error )
    {
         $success = false;
         if ($second_error === false) { $this->My_model->inserting('customers', $to_update); 
         $success = "Profile Added  Successfully"; }
         if ($success) 
         {
              echo "<script>window.location.href='" .base_url()."admin/editstudents'</script>";
         }
         return $success;
    }
    protected function getCustomerInfo($customer_id)
    {
        
         if (!is_numeric($customer_id)) echo "ERROR - customer_id is not numeric";
         $this->load->model('Customers_model');
         $row = $this->Customers_model->getUser($customer_id);
         return $row;
    }
    protected function processCustomerData($add_student, $post)
    {
         if (true !== $add_student) $where = array("Customer_ID" => $post['customer_id']);
         $second_error = false;
         $notes_hidden_from_cust = sanitize_input($post['notes_hidden_from_cust']);
         $notes_cust_viewable = sanitize_input($post['notes_cust_viewable']);
         $deleted = (isset($post['deleted']) && $post['deleted'] == 'on') ? 1 : 0;
         $inactive = (isset($post['inactive']) && $post['inactive'] == 'on') ? 1 : 0;
         $email_confirmed = (isset($post['email_confirmed']) && $post['email_confirmed'] == 'on') ? 1 : 0;
         $to_update = array('notes_cust_viewable' => $notes_cust_viewable, 'notes_hidden_from_cust' => $notes_hidden_from_cust, 'email_confirmed' => $email_confirmed, 'inactive' => $inactive, 'deleted' => $deleted, 'deleted' => $deleted, 'skype' => $post['skype'],'time_zone' => $post['time_zone'], 'name' => $post['name'], 'email_list' => $post['email'], 'phone' => $post['phone']);
         if ($post['pwd'] && trim($post['pwd']) != '')
         {
              if ($post['pwd'] != $post['conf_pwd'])
              {
                  $second_error = true;
                  $password_error = "Passwords are not the same";
              }
              else
              {
                  $reg = date('Y-m-d h:i:s');
                  $to_update['date_reg']  = $reg;
                  $to_update['password'] = md5($post['pwd'] . $reg);
              }
         }
         if (true === $add_student) { $success = $this->_addNewCustomer( $to_update, $second_error); }
         else { list($second_error, $emailerror, $success) = $this->_editExistingCustomer($this->Customers_model, $post['email'], $post['customer_id'], $to_update, $where, $second_error); }//, 
         $data = array();
         $data['emailerror'] = (isset($emailerror)) ? $emailerror : '';
         $data['success'] = (isset($success)) ? $success : '';
         $data['password_error'] = isset($password_error) ? $password_error : '';
         $data['err'] = false;
         $row = $this->getCustomerInfo($post['customer_id']);
         return array($data, $row);
    }
    protected function DelAdminMsg($errorMsg)
    { 
        echo "<script>alert('$errorMsg');window.location.href='" . base_url() . 'admin/admin_setting' . "'</script>";
        exit;
    }
    public function studentInvoices()
    {
        $id = $_POST['student_id'];
        $tutor_id = (isset($_POST['tutorID']) && is_numeric($_POST['tutorID'])) ? $_POST['tutorID'] : '';
        $tutorUse = (isset($_POST['tutorUse']) && is_numeric($_POST['tutorUse'])) ? $_POST['tutorUse'] : ''; 
        $bills = $this->_getStudentPurc($id, $tutor_id, $tutorUse);
        if (is_array($bills))
        {
             echo "<table cellpadding='10' cellspacing='5'>";
             echo "\n<tr><th>Tutor</th><th>Date</th><th>Recurring / Flex Time</th><th>Amount</th><th>Status</th><th></th></tr>";
             foreach ($bills as $bill) { $this->_studentInvoiceFormat($bill, $tutorUse); }
             echo "\n</table>";
        } else echo "No bills to show";
    }
    private function _studentInvoiceFormat($bill, $tutorUse)
    {
        $this->load->model('Tutors_model');
        $this->load->model('Customers_model');
        $tutor_model = $this->Tutors_model;
        $Customers_model = $this->Customers_model;
        $r_p = $Customers_model->getRecurring($bill->profile_id);
        $r_p = ($r_p) ? $r_p->{'minutes-per-week'} : " NOT AVAILABLE";
        if ($bill->item_id == 2) $ofTime = " Recurring Time";
        elseif ($bill->item_id == 1) $ofTime = "Flex Time";
        else $ofTime = 'Other'; 
        $tutor = $tutor_model->getTutor($bill->tutor_id);
        $tutor = ($tutor) ? $tutor->name : 'Unknown Tutor';
        if ((int)$bill->date_paid < 1) { $unpaid = "\n<span style='color: #a00;'>(unpaid)</span>"; }
        else $unpaid = '';
        $billdate_paid = ($bill->date_paid < 1) ? $bill->date_created : $bill->date_paid;
        echo "\n<tr><td>$tutor</td>";
        echo "<td>".date('M-d-Y', $billdate_paid)."</td>";
        echo "<td>".$ofTime."</td>";
        echo "<td>$".$bill->price_paid."</td>";
        echo "<td>".$unpaid."</td>";
        $admin =  ($tutorUse == 1) ? "tutors" : "admin"; 
        echo "<td><a href='".base_url().$admin."/editInvoice/".$bill->purchase_id."'>Edit</a></td></tr>\n";
    }
    public function tutorInvoices()
    {
        $adminCredentials = $this->session->userdata('adminCredentials');
        if(!$adminCredentials) redirect(base_url() . 'admin');
        $tutor_id = $_POST['tutor_id'];
        if (!is_numeric($tutor_id)) return;
         $this->load->model('Tutors_model');
        $bills = $this->Tutors_model->getAdminPurchases($tutor_id);
        
        if (is_array($bills))
        {
            echo "<table cellpadding='10' cellspacing='5'>";
            echo "<tr><th>Student</th><th>Date</th><th>Recurring / Flex Time</th><th>Amount</th><th>Status</th><th></th></tr>";
            foreach ($bills as $bill) { $this->_tutorInvoicesFormat($bill); }
            echo "</table>";
        } else echo "No bills to show";
     }
     private function _tutorInvoicesFormat($bill)
     {
          $this->load->model('Customers_model');
          $this->load->model('Tutors_model');
          $tutor_model = $this->Tutors_model;
          $Customers_model = $this->Customers_model;  
          $r_p =  $Customers_model->getRecurring($bill->profile_id);
          $r_p = ($r_p) ? $r_p->{'minutes-per-week'} : " NOT AVAILABLE";
          if ($bill->item_id == 2) $ofTime = " Recurring Time";
          elseif ($bill->item_id == 1) $ofTime = "Flex Time";
          else $ofTime = 'Other'; 
          $tutor = $tutor_model->getTutor($bill->tutor_id);
          $tutor = ($tutor) ? $tutor->name : 'Unknown Tutor';
          echo "<tr>";
          if ((int)$bill->date_paid < 1) { $unpaid = " <span style='color: #a00;'>(unpaid)</span>"; }
          else $unpaid = '';
          if (is_numeric($bill->customer_id))
          {
               $thisName = $Customers_model->getCustomer($bill->customer_id);
               if ($thisName) $thisName = $thisName->name;
               else $thisName = '';
          } else $thisName = '';
          echo "<td>".$thisName;
          $billdate_paid = ($bill->date_paid < 1) ? $bill->date_created : $bill->date_paid;
          if ($bill->date_paid) $showDate = date('M-d-Y', $billdate_paid);
          else $showDate = "No date to show";
          echo "</td><td>$showDate</td>";
          echo "<td>".$ofTime."</td>";
          echo "<td>$".$bill->price_paid."</td>";
          echo "<td>".$unpaid."</td>";
           echo "<td><a href='".base_url()."admin/editInvoice/".$bill->purchase_id."'>Edit</a></td>";
          echo "</tr>";
     }
     public function AjaxStudents()
    {
        $searchVar = true;
        if (!isset($_GET)) $searchVar = false;
        if (!isset($_GET['stustring']) || $_GET['stustring'] == '')  $_GET['stustring'] = '';
        if ( ( !isset($_GET['order']) || !is_numeric($_GET['order']) ) || ( !isset($_GET['rect']) || !is_numeric($_GET['rect']) ) )  $searchVar = false;
        if ($searchVar)
        {
            $this->load->model('Tutors_model');
            $students = $this->Tutors_model->adminStudents($_GET['stustring']);
            if ($students && is_array($students))
            {
                  $emailsList = array();
                  foreach ($students as $student) { $emailsList[] = $student->name." &lt".$student->email_list."&gt"; }
                  echo implode(', ', $emailsList);
            } else echo 0;
        } else echo 0;
        exit;
    }
}