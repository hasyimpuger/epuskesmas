<?php
class Pengadaanbarang_model extends CI_Model {

    var $tabel    = 'inv_pengadaan';
	var $lang	  = '';

    function __construct() {
        parent::__construct();
		$this->lang	  = $this->config->item('language');
    }
    
    function get_data_status()
    {	
    	$this->db->where("mst_inv_pilihan.tipe",'status_inventaris');
 		$this->db->select('mst_inv_pilihan.*');		
 		$this->db->order_by('mst_inv_pilihan.code','asc');
		$query = $this->db->get('mst_inv_pilihan');	
		return $query->result();	
    }
    function get_data($start=0,$limit=999999,$options=array())
    {
        $this->db->select("$this->tabel.*,mst_inv_pilihan.value");
        $this->db->join('mst_inv_pilihan', "inv_pengadaan.pilihan_status_pengadaan = mst_inv_pilihan.code AND mst_inv_pilihan.tipe='status_pengadaan'",'left');
        $this->db->order_by('tgl_pengadaan','asc');
        $query = $this->db->get($this->tabel,$limit,$start);
        return $query->result();
    }
    public function getItem($table,$data)
    {   
        $this->db->where('mst_inv_pilihan.tipe','status_inventaris');
        $this->db->select("inv_inventaris_barang.id_mst_inv_barang,inv_inventaris_barang.nama_barang,inv_inventaris_barang.harga,
                        COUNT(inv_inventaris_barang.id_mst_inv_barang) AS jumlah,
                        COUNT(inv_inventaris_barang.id_mst_inv_barang)*inv_inventaris_barang.harga AS totalharga,
                        inv_inventaris_barang.keterangan_pengadaan,mst_inv_pilihan.value,inv_inventaris_barang.tanggal_diterima,
                        inv_inventaris_barang.waktu_dibuat,inv_inventaris_barang.terakhir_diubah,inv_inventaris_barang.pilihan_status_invetaris");
        $this->db->join('mst_inv_pilihan', "inv_inventaris_barang.pilihan_status_invetaris=mst_inv_pilihan.code");
        $this->db->group_by("inv_inventaris_barang.id_mst_inv_barang");
        return $this->db->get_where($table, $data);
    }

 	function get_data_row($kode){
		$data = array();
		$this->db->where("inv_pengadaan.id_pengadaan",$kode);
		$this->db->select("$this->tabel.*,mst_inv_pilihan.value");
        $this->db->join('mst_inv_pilihan', "inv_pengadaan.pilihan_status_pengadaan = mst_inv_pilihan.code AND mst_inv_pilihan.tipe='status_pengadaan'",'left');
		$query = $this->db->get($this->tabel);
		if ($query->num_rows() > 0){
			$data = $query->row_array();
		}

		$query->free_result();    
		return $data;
	}
	function get_data_barang_edit($kode, $id_barang){
		$data = array();
		
		$this->db->select("inv_inventaris_barang.id_mst_inv_barang,inv_inventaris_barang.nama_barang,inv_inventaris_barang.harga,
                        COUNT(inv_inventaris_barang.id_mst_inv_barang) AS jumlah,
                        COUNT(inv_inventaris_barang.id_mst_inv_barang)*inv_inventaris_barang.harga AS totalharga,
                        inv_inventaris_barang.keterangan_pengadaan,inv_inventaris_barang.tanggal_diterima,
                        inv_inventaris_barang.waktu_dibuat,inv_inventaris_barang.terakhir_diubah,inv_inventaris_barang.pilihan_status_invetaris");
		$this->db->where("id_pengadaan",$kode);
		$this->db->where("id_mst_inv_barang",$id_barang);
		$query = $this->db->get("inv_inventaris_barang");
		if ($query->num_rows() > 0){
			$data = $query->row_array();
		}

		$query->free_result();    
		return $data;
	}
	public function getSelectedData($table,$data)
    {
        return $this->db->get_where($table, $data);
    }

    function get_permohonan_id($puskesmas="")
    {
    	$this->db->select('MAX(id_inv_permohonan_barang)+1 as id');
    	$this->db->where('code_cl_phc',$puskesmas);
    	$permohonan = $this->db->get('inv_permohonan_barang')->row();
    	if (empty($permohonan->id)) {
    		return 1;
    	}else {
    		return $permohonan->id;
    	}
	}
	function get_inventarisbarang_id($id,$barang)
    {
    	$query  = $this->db->query("SELECT max(id_inventaris_barang) as id from inv_inventaris_barang WHERE id_pengadaan=$id AND id_mst_inv_barang=$barang");
    	if (empty($query->result()))
    	{
    		return 1;
    	}else {
    		foreach ($query->result() as $jum ) {
    			return $jum->id+1;
    		}
    	}

	}
   function insert_entry()
    {
    	$data['tgl_pengadaan']	            = date("Y-m-d",strtotime($this->input->post('tgl')));
		$data['pilihan_status_pengadaan']	= $this->input->post('status');
		$data['keterangan']		            = $this->input->post('keterangan');
        $data['nomor_kontrak']              = $this->input->post('nomor_kontrak');
		$data['waktu_dibuat']		        = date('Y-m-d');
        $data['terakhir_diubah']            = "0000-00-00";
		$data['jumlah_unit']      	        = 0;
        $data['nilai_pengadaan']            = 0;
		if($this->db->insert($this->tabel, $data)){
			return $this->db->insert_id();
		}else{
			return mysql_error();
		}
    }

    function update_entry($kode)
    {
    	$data['tgl_pengadaan']             = date("Y-m-d",strtotime($this->input->post('tgl')));
        $data['pilihan_status_pengadaan']   = $this->input->post('status');
        $data['keterangan']                 = $this->input->post('keterangan');
        $data['nomor_kontrak']              = $this->input->post('nomor_kontrak');
        $data['terakhir_diubah']            = date('Y-m-d');
		$this->db->where('id_pengadaan',$kode);

		if($this->db->update($this->tabel, $data)){
			return true;
		}else{
			return mysql_error();
		}
    }
    function tampil_id($status){
    	$this->db->select('code');
    	$this->db->where('value',$status);
		$this->db->where('tipe','status_pengadaan');
		$query=$this->db->get('mst_inv_pilihan');
		if($query->num_rows()>0)
        {
            foreach($query->result() as $k)
            {
                $id = $k->code;
            }
        }
        else
        {
            $id = 1;
        }
        	return  $id;
    }
    function update_status()
    {	
    	$status= $this->input->post('pilihan_status_pengadaan');
    	$data['pilihan_status_pengadaan']	= $this->tampil_id($status);
    	$id = $this->input->post('inv_permohonan_barang');
		if($this->db->update($this->tabel, $data,array('id_inv_permohonan_barang'=> $id))){
			return true;
		}else{
			return mysql_error();
		}
    }
    function sum_jumlah_item($kode,$tipe){
    	$this->db->select_sum($tipe);
    	$this->db->where('id_pengadaan',$kode);
		$query=$this->db->get('inv_inventaris_barang');
		if($query->num_rows()>0)
        {
            foreach($query->result() as $k)
            {
                $jumlah = $k->harga;
            }
        }
        else
        {
            $jumlah = 0;
        }
        return  $jumlah;
    }
    function barang_kembar_proc($kode){
        $q = $this->db->query("SELECT barang_kembar_proc FROM inv_inventaris_barang WHERE id_mst_inv_barang=$kode ORDER BY barang_kembar_proc DESC");
        $kd = "";
        if($q->num_rows()>0)
        {
            $kd = $q->id_mst_inv_barang;
        }
        else
        {
            $qq = $this->db->query("SELECT max(id_mst_inv_barang) as kd_max FROM inv_inventaris_barang where  id_inventaris_barang=$kode");
            foreach($qq->result() as $k)
            {
                $tmp = ((int)$k->kd_max)+1;
                $kd = sprintf("%03s", $tmp);
            }
        }

        $today = date("ym"); 
        return "KBNS-$today".$kd;
    }
    function sum_unit($kode)
    {
        $this->db->select("*");
        $this->db->where('id_pengadaan',$kode);  
        return $query = $this->db->get("inv_inventaris_barang"); 
    }
	function delete_entry($kode)
	{
		$this->db->where('id_pengadaan',$kode);

		return $this->db->delete($this->tabel);
	}
	function delete_entryitem($kode,$id_barang)
	{
		$this->db->where('id_pengadaan',$kode);
		$this->db->where('id_mst_inv_barang',$id_barang);
		return $this->db->delete('inv_inventaris_barang');
	}
	function get_databarang($start=0,$limit=999999)
    {
		$this->db->order_by('uraian','asc');
        $query = $this->db->get('mst_inv_barang',$limit,$start);
        return $query->result();
    }
}