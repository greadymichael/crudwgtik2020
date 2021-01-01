<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Siswa extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->userTbl = 'data_siswa';
    }

    public function getData($params = array())
    {
        $this->db->select('*');
        $this->db->from($this->userTbl);

        if (array_key_exists("conditions", $params)) {
            foreach ($params['conditions'] as $key => $value) {
                $this->db->where($key, $value);
            }
        }
        if (array_key_exists("uplink", $params)) {
            $this->db->where('uplink', $params['uplink']);
            $query = $this->db->get();
            $result = $query->row_array();
        } else if (array_key_exists("date", $params)) {
            $this->db->where('date', $params['date']);
            $query = $this->db->get();
            $result = $query->row_array();
        } else {
            if (array_key_exists("start", $params) && array_key_exists("limit", $params)) {
                $this->db->limit($params['limit'], $params['start']);
            } else if (!array_key_exists("start", $params) && array_key_exists("limit", $params)) {
                $this->db->limit($params['limit']);
            }
            if (array_key_exists("returnType", $params) && $params['returnType'] == 'count') {
                $result = $this->db->count_all_results();
            } else if (array_key_exists("returnType", $params) && $params['returnType'] == 'single') {
                $query = $this->db->get();
                $result = ($query->num_rows() > 0) ? $query->row_array() : false;
            } else {
                $query = $this->db->get();
                $result = ($query->num_rows() > 0) ? $query->result_array() : false;
            }
        }
        return $result;
    }

    public function insert($data)
    {
        $insert = $this->db->insert($this->userTbl, $data);
        return $insert;
    }

    public function lastorderya()
    {
        $this->db->select('*');
        $this->db->from("pesanan");
        $this->db->order_by('id', 'DESC');
        $this->db->limit(1);
        $query = $this->db->get();
        $result = $query->result_array();
        return $result[0];
    }

    public function update($data, $id)
    {
        $update = $this->db->update($this->userTbl, $data, array('uplink' => $id));
        return $update ? true : false;
    }

    public function uOrder($data, $id)
    {
        $update = $this->db->update($this->userTbl, $data, array('cid' => $id));
        return $update ? true : false;
    }

    public function delete($id)
    {
        $delete = $this->db->delete($this->userTbl, array('uplink' => $id));
        return $delete ? true : false;
    }
}