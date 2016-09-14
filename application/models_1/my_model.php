<?php
class My_model extends CI_Model {

    /*************** Select data ****************************/
    function select_where($table, $where) // select wher return  2 dimensional array
    {
        return $this->db
            ->where($where)
            ->get($table)
            ->row();
    }

    function get_result($num, $offset) //for pagination purposes
    {
        $query = $this->db->get('event', $num, $offset)->result();
        return $query;
    }

    function select_where_res($table, $where) //Select all with where condition return array
    {
        return $this->db
            ->where($where)
            ->get($table)
            ->result();
    }

    function select_where_order($table, $where, $order, $type) {
        return $this->db
            ->order_by($order, $type)
            ->where($where)
            ->get($table)
            ->result();
    }

    function select_order_limit($table, $order, $limit, $type) //Select all recent added content with limit
    {
        return $this->db
            ->order_by($order, $type)
            ->get($table, $limit)

            ->result();
    }

    function select_where_order_limit_a($table, $where, $column, $limit) //Select all recent added content with limit 5
    {
        return $this->db
            ->order_by($column)
            ->where($where)
            ->get($table, $limit)
            ->result();
    }

    function select_where_order_limit_d($table, $where, $column, $limit) //Select all recent added content with limit 5
    {
        return $this->db
            ->order_by($column, "desc")
            ->where($where)
            ->get($table, $limit)
            ->result();
    }

    function select_table($table) //Select table
    {
        return $this->db
            ->get($table)
            ->result();
    }

    function select_table_limit($table, $limit) //Select table
    {
        return $this->db
            ->get($table, $limit)
            ->result();
    }

    function select_table_order($table, $order) //Select table
    {
        return $this->db
            ->order_by($order)
            ->get($table)
            ->result();
    }

    function select_table_order_type($table, $order, $type) //Select table
    {
        return $this->db
            ->order_by($order, $type)
            ->get($table)
            ->result();
    }

    function select_table_order_limit($table, $order, $limit) //Select table
    {
        return $this->db
            ->order_by($order)
            ->get($table, $limit)
            ->result();
    }

    /**************Count**********************/
    function count_where($table, $where) {
        return $this->db
            ->where($where)
            ->get($table)
            ->num_rows();
    }

    function count_where_order($table, $where, $order, $type) {
        return $this->db
            ->where($where)
            ->get($table)
            ->num_rows()
            ->order_by($order, $type);
    }

    function count_table($table) {
        return $this->db
            ->get($table)
            ->num_rows();
    }

    /************Insert**********************/
    function inserting($table, $to_insert) {
        return $this->db
            ->insert($table, $to_insert);
    }


    /*************Delete*********************/
    function delete_where($table, $where) {
        return $this->db
            ->where($where)
            ->delete($table);
    }

    /********************Update****************************/

    function update($table, $to_update) // update single

    {
        return $this->db
            ->update($table, $to_update);

    }

    function update_where($table, $to_update, $where) // update where
    {
        return $this->db
            ->where($where)
            ->update($table, $to_update);
    }

    function count_alls($table) {
        return $this->db
            ->get($table)
            ->num_rows();
    }

    function fetch_postd($limit, $start, $table, $column) {
        $this->db->limit($limit, $start);
        $query = $this->db->order_by($column, "desc")->get($table);

        if($query->num_rows() > 0){
            foreach($query->result() as $row){
                $data[] = $row;
            }
            return $data;
        }
        return false;
    }

    function fetch_postd_where($limit, $start, $table, $column, $where) {
        $this->db->limit($limit, $start);
        $query = $this->db
            ->order_by($column, "desc")
            ->where($where)
            ->get($table);

        if($query->num_rows() > 0){
            foreach($query->result() as $row){
                $data[] = $row;
            }
            return $data;
        }
        return false;
    }

    public function select_only($table) {
        return $this->db
            ->get($table)
            ->result();
    }


    /*******lyra*********/
    // function select_only($table)
    // {
    // return $this->db->query("SELECT * FROM
    // }


}
