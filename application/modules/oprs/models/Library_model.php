<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Library_model extends CI_Model {

	// oprs
	private $titles = 'tbltitles';
	private $status = 'tblstatus_types';
	private $publication = 'tblpublication_types';
	private $tech_rev_crit = 'tbltech_rev_criterias';
	private $peer_rev_crit = 'tblpeer_rev_criterias';

	// ejournal
	private $client_type = 'tblcsf_client_type';

	// skms
	private $regions = 'tblregions';


	public function __construct() {
		parent::__construct();
		$this->load->database(ENVIRONMENT);
	}

	/**
	 * Retreive titles (ex. mr., ms., etc)
	 *
	 * @return void
	 */
	public function get_titles() {
		$oprs = $this->load->database('dboprs', TRUE);
		$oprs->select('*');
		$oprs->from($this->titles);
		$oprs->order_by('title_name', 'asc');
		$query = $oprs->get();
		return $query->result();
	}

	public function get_tables(){

		$oprs = $this->load->database('dboprs', TRUE);
        return $oprs->list_tables();
    }

	// status types functions

	public function get_status_types($id = null){
		$oprs = $this->load->database('dboprs', TRUE);
		$oprs->select('*');
		$oprs->from($this->status);

		if($id){
			$oprs->where('id', $id);
		}

		$query = $oprs->get();
		return $query->result();
	}
	
	public function check_unique_status($name, $id){
		$oprs = $this->load->database('dboprs', TRUE);
		$oprs->select('*');
		$oprs->from($this->status);
		$oprs->where('status_desc', $name);
        $oprs->where('id !=', $id); // Exclude the current record
        $query = $oprs->get();
		$rows = $query->num_rows();
		if ($rows > 0) {
			return 'false';
		} else {
			return 'true';
		}
	}

	public function update_status_type($post, $where){
		$oprs = $this->load->database('dboprs', TRUE);
		$oprs->update($this->status, $post, $where);
	}

	// publication types functions

	public function get_publication_types($id = null){
		$oprs = $this->load->database('dboprs', TRUE);
		$oprs->select('*');
		$oprs->from($this->publication);

		if($id){
			$oprs->where('id', $id);
		}

		$query = $oprs->get();
		return $query->result();
	}
	
	public function check_unique_publication_type($name, $id){
		$oprs = $this->load->database('dboprs', TRUE);
		$oprs->select('*');
		$oprs->from($this->publication);
		$oprs->where('publication_desc', $name);
        $oprs->where('id !=', $id); // Exclude the current record
        $query = $oprs->get();
		$rows = $query->num_rows();
		if ($rows > 0) {
			return 'false';
		} else {
			return 'true';
		}
	}

	public function update_publication_type($post, $where){
		$oprs = $this->load->database('dboprs', TRUE);
		$oprs->update($this->publication, $post, $where);
	}

	// review criterion

	public function get_criteria($id = null, $category = null){
		$oprs = $this->load->database('dboprs', TRUE);
		if($category){
			if($category == 1){
				$oprs->select('crt_id as id, crt_code as code, crt_desc as desc, created_at, last_updated');
				$oprs->from($this->tech_rev_crit);
				if($id){
					$oprs->where('crt_id', $id);
				}
			}else{
				$oprs->select('pcrt_id as id, pcrt_code as code, pcrt_desc as desc, pcrt_score as score, created_at, last_updated');
				$oprs->from($this->peer_rev_crit);
				if($id){
					$oprs->where('pcrt_id', $id);
				}
			}
		}

		$query = $oprs->get();
		return $query->result();
	}
	
	public function check_unique_criteria_code($name, $id, $criteria){
		$oprs = $this->load->database('dboprs', TRUE);
		$oprs->select('*');
		if($criteria == 1){
			$oprs->from($this->tech_rev_crit);
			$oprs->where('crt_code', $name);
			$oprs->where('crt_id !=', $id); // Exclude the current record
		}else{
			$oprs->from($this->peer_rev_crit);
			$oprs->where('pcrt_code', $name);
			$oprs->where('pcrt_id !=', $id); // Exclude the current record
		}
        $query = $oprs->get();
		$rows = $query->num_rows();
		if ($rows > 0) {
			return 'false';
		} else {
			return 'true';
		}
	}
	
	public function check_unique_criteria_desc($name, $id, $criteria){
		$oprs = $this->load->database('dboprs', TRUE);
		$oprs->select('*');
		if($criteria == 1){
			$oprs->from($this->tech_rev_crit);
			$oprs->where('crt_desc', $name);
			$oprs->where('crt_id !=', $id); // Exclude the current record
		}else{
			$oprs->from($this->peer_rev_crit);
			$oprs->where('pcrt_desc', $name);
			$oprs->where('pcrt_id !=', $id); // Exclude the current record
		}
        $query = $oprs->get();
		$rows = $query->num_rows();
		if ($rows > 0) {
			return 'false';
		} else {
			return 'true';
		}
	}

	public function update_critera($post, $where, $criteria){
		$oprs = $this->load->database('dboprs', TRUE);
		if($criteria == 1){
			$oprs->update($this->tech_rev_crit, $post, $where);
		}else{
			$oprs->update($this->peer_rev_crit, $post, $where);
		}
	}

	public function get_regions(){
		$regions = $this->load->database('members', TRUE);
		$regions->select('*');
		$regions->from($this->regions);
		$query = $regions->get();
		return $query->result();
	}

	public function get_client_type(){
		$this->db->select('*');
		$this->db->from($this->client_type);
		$query = $this->db->get();
		return $query->result();
	}

}

/* End of file Library_model.php */