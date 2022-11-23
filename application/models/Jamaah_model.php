<?php
/* 
 * Generated by CRUDigniter v3.2 
 * www.crudigniter.com
 */

class Jamaah_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }


    private $_countryID;

    // set country id
    public function setCountryID($countryID)
    {
        return $this->_countryID = $countryID;
    }
    // set state id
    public function setStateID($stateID)
    {
        return $this->_stateID = $stateID;
    }

    public function getAllCountries()
    {
        $this->db->select(array('c.id_keberangkatan as id_keberangkatan', 'c.tanggal_keberangkatan', 'c.tanggal_keberangkatan'));
        $this->db->from('keberangkatan as c');
        $query = $this->db->get();
        return $query->result_array();
    }

    // get state method
    public function getStates()
    {
        $this->db->select(array('id_paket', 'fk_id_keberangkatan', 'nama_program'));
        $this->db->from('paket');
        $this->db->where('fk_id_keberangkatan', $this->_countryID);
        $query = $this->db->get();
        return $query->result_array();
    }

    function add_keberangkatan($params)
    {
        $this->db->set('id_jamaah', $params['id_jamaah']);
        $this->db->set('id_paket', $params['id_paket']);
        $this->db->insert('record_keberangkatan');
    }

    public function filter($search, $limit, $start, $order_field, $order_ascdesc)
    {
        $user_id = $this->session->userdata('user_id');
        $this->db->where('created_by', $user_id); // Untuk menambahkan query where OR LIKE
        $this->db->like('nik', $search); // Untuk menambahkan query where LIKE
        $this->db->or_like('nama_jamaah', $search); // Untuk menambahkan query where OR LIKE
        $this->db->or_like('nik', $search); // Untuk menambahkan query where OR LIKE
        $this->db->or_like('alamat', $search); // Untuk menambahkan query where OR LIKE
        $this->db->or_like('updated_at', $search); // Untuk menambahkan query where OR LIKE
        $this->db->or_like('qr_code_benar', $search); // Untuk menambahkan query where OR LIKE
        $this->db->order_by($order_field, $order_ascdesc); // Untuk menambahkan query ORDER BY
        $this->db->limit($limit, $start); // Untuk menambahkan query LIMIT

        return $this->db->get('jamaah')->result_array(); // Eksekusi query sql sesuai kondisi diatas
    }

    public function count_all()
    {
        return $this->db->count_all('jamaah'); // Untuk menghitung semua data jamaah
    }

    public function count_filter($search)
    {
        $user_id = $this->session->userdata('user_id');
        $this->db->where('created_by', $user_id); // Untuk menambahkan query where OR LIKE
        $this->db->like('nik', $search); // Untuk menambahkan query where LIKE
        $this->db->or_like('nama_jamaah', $search); // Untuk menambahkan query where OR LIKE
        $this->db->or_like('nik', $search); // Untuk menambahkan query where OR LIKE
        $this->db->or_like('alamat', $search); // Untuk menambahkan query where OR LIKE
        $this->db->or_like('updated_at', $search); // Untuk menambahkan query where OR LIKE
        $this->db->or_like('qr_code_benar', $search); // Untuk menambahkan query where OR LIKE

        return $this->db->get('jamaah')->num_rows(); // Untuk menghitung jumlah data sesuai dengan filter pada textbox pencarian
    }

    /*
     * Get luasan by id_luasan
     */
    function get_jamaah($id_jamaah)
    {
        return $this->db->get_where('jamaah', array('id_jamaah' => $id_jamaah))->row_array();
    }

    /*
     * Get all jamaah count
     */
    function get_all_jamaah_count()
    {
        $this->db->from('jamaah');
        return $this->db->count_all_results();
    }

    function get_jamaah_by_nik($nik)
    {
        return $this->db->get_where('jamaah', array('nik' => $nik))->row_array();
    }

    function get_jamaah_by_uuid($uuid)
    {
        return $this->db->get_where('jamaah', array('uuid' => $uuid))->row_array();
    }

    /*
     * Get all jamaah
     */
    function get_all_jamaah($params = array())
    {
        $this->db->order_by('jamaah.updated_at', 'desc');
        $this->db->join('tbl_users', 'tbl_users.user_id=jamaah.created_by', 'left');
        return $this->db->get('jamaah')->result_array();
    }

    function get_all_jamaah_with_record()
    {
        $this->db->order_by('jamaah.updated_at', 'desc');
        $this->db->join('tbl_users', 'tbl_users.user_id=jamaah.created_by', 'left');
        $this->db->join('record_keberangkatan', 'record_keberangkatan.id_jamaah=jamaah.id_jamaah', 'left');
        $this->db->join('paket', 'paket.id_paket=record_keberangkatan.id_paket', 'left');
        $this->db->join('keberangkatan', 'keberangkatan.id_keberangkatan=paket.fk_id_keberangkatan', 'left');
        return $this->db->get('jamaah')->result_array();
    }

    function get_all_jamaah_pure($params = array())
    {
        $this->db->order_by('jamaah.grup_keberangkatan', 'asc');
        return $this->db->get('jamaah')->result_array();
    }

    function get_all_jamaah_by_cabang($user_id)
    {
        $this->db->order_by('jamaah.id_jamaah', 'asc');
        return $this->db->get_where('jamaah', array('created_by' => $user_id))->result_array();
    }

    /*
     * function to add new jamaah
     */
    function add_jamaah($params, $gambar)
    {
        $this->db->set('nik', $params['nik']);
        $this->db->set('nama_jamaah', $params['nama_jamaah']);
        $this->db->set('nomor_telepon', $params['nomor_telepon']);
        $this->db->set('jenis_kelamin', $params['jenis_kelamin']);
        $this->db->set('alamat', $params['alamat']);
        $this->db->set('nomor_paspor', $params['nomor_paspor']);
        $this->db->set('jamaah_img', $gambar);
        $this->db->set('created_by', $params['created_by']);
        $this->db->set('uuid', 'UUID_SHORT()', FALSE);
        $this->db->insert('jamaah');
    }

    /*
     * function to update jamaah
     */
    function update_jamaah($id_jamaah, $params)
    {
        $this->db->where('id_jamaah', $id_jamaah);
        return $this->db->update('jamaah', $params);
    }
    function update_jamaah_img($params, $gambar)
    {
        var_dump($params, $gambar);
        exit();
        // $this->db->where('id_jamaah', $id_jamaah);
        // return $this->db->update('jamaah', $params);
    }

    function update_scan($result_code, $params)
    {
        $this->db->where('uuid', $result_code);
        return $this->db->update('jamaah', $params);
    }

    /*
     * function to delete jamaah
     */
    function delete_jamaah($id_jamaah)
    {
        return $this->db->delete('jamaah', array('id_jamaah' => $id_jamaah));
    }

    function get_record_keberangkatan($id_jamaah)
    {
        $this->db->where('id_jamaah', $id_jamaah);
        $this->db->order_by('record_keberangkatan.id_jamaah', 'asc');
        $this->db->join('paket', 'paket.id_paket=record_keberangkatan.id_paket', 'left');
        $this->db->join('keberangkatan', 'keberangkatan.id_keberangkatan=paket.fk_id_keberangkatan', 'left');
        // $this->db->join('kehadiran', 'record_keberangkatan.id_jamaah=kehadiran.fk_id_jamaah', 'left');
        return $this->db->get('record_keberangkatan')->result_array();
    }

    function get_hotel_from_record_keberangkatan($id_jamaah)
    {
        $this->db->where('id_jamaah', $id_jamaah);
        $this->db->join('paket', 'paket.id_paket=record_keberangkatan.id_paket', 'left');
        $this->db->join('keberangkatan', 'keberangkatan.id_keberangkatan=paket.fk_id_keberangkatan', 'left');
        $this->db->order_by('record_keberangkatan.created_at', 'desc');
        return $this->db->get('record_keberangkatan')->result_array();
    }
}
