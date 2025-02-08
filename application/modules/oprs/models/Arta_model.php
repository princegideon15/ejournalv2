<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Arta_model extends CI_Model {
	// oprs
	private $criterias = 'tblcriterias';
	private $scores = 'tblscores';
	private $manus = 'tblmanuscripts';
	private $user = 'tblusers';
    private $publication = 'tblpublication_types';
	private $coauthors = 'tblcoauthors';
	private $sex = 'tblsex';

    // ej
	private $arta = 'tblcsf_arta';
	private $cc_type = 'tblcsf_client_type';
	private $ej_client = 'tbluser_profiles';
	private $ej_sex = 'tblsex';
	private $age_group = 'tblage_group';
	private $cc1 = 'tblcsf_cc1';
	private $cc2 = 'tblcsf_cc2';
	private $cc3 = 'tblcsf_cc3';
	private $sqd = 'tblcsf_sqd';

    //skms
    private $regions = 'tblregions';



	public function __construct() {
		parent::__construct();
		$this->load->database(ENVIRONMENT);
	}

    public function get_arta($from = null, $to = null, $region = null, $ctype = null, $sex = null){
        $this->db->select('a.*, CONCAT(first_name," ",last_name) as name, sex_name, ctype_desc, region_name');
		$this->db->from($this->arta . ' a');
		$this->db->join($this->ej_client . ' e', 'a.arta_user_id = e.user_id');
		$this->db->join($this->ej_sex . ' s', 'a.arta_sex = s.sex_id');
		$this->db->join($this->cc_type . ' c', 'a.arta_ctype = c.id');
		$this->db->join('new_dbskms.tblregions as r', 'a.arta_region = r.region_id');
		
		if($from > 0 && $to > 0){
		    $this->db->where('DATE(m.date_created) >=',$from);
			$this->db->where('DATE(m.date_created) <=',$to);
        }

		if($region){
			$this->db->where('arta_region',$region);
		}

		if($ctype){
			$this->db->where('arta_ctype',$ctype);
		}

		if($sex){
			$this->db->where('arta_sex',$sex);
		}

		$query = $this->db->get();
		return $query->result();
    }

	public function get_arta_resp_age($from = null, $to = null){

		$where = '';
		
		if($from > 0 && $to > 0){
		    $where = ' AND DATE(csf.arta_created_at) >= ' . $from . ' AND DATE(csf.arta_created_at) <= ' . $to;
        }

		$this->db->select('CONCAT(min_age,"-",max_age) AS age_range, 
		(SELECT COUNT(*) FROM dbej.tblcsf_arta AS csf WHERE csf.arta_sex = 1 AND csf.arta_age >= min_age AND csf.arta_age <= max_age' . $where . ') AS male, 
		(SELECT COUNT(*) FROM dbej.tblcsf_arta AS csf WHERE csf.arta_sex = 2 AND csf.arta_age >= min_age AND csf.arta_age <= max_age' . $where . ') AS female');


		$this->db->from($this->age_group);
		$query = $this->db->get();
		return $query->result();
	}

	public function get_arta_region($from = null, $to = null){

		$where = '';
		
		if($from > 0 && $to > 0){
		    $where = ' AND DATE(csf.arta_created_at) >= ' . $from . ' AND DATE(csf.arta_created_at) <= ' . $to;
        }

		$this->db->select('region_name, 
		(SELECT COUNT(*) FROM dbej.tblcsf_arta AS csf WHERE csf.arta_sex = 1 AND arta_region LIKE region_id' . $where . ') AS male, 
		(SELECT COUNT(*) FROM dbej.tblcsf_arta AS csf WHERE csf.arta_sex = 2 AND arta_region LIKE region_id' . $where . ') AS female');
		$this->db->from('new_dbskms.tblregions');

	
		$this->db->group_by('region_id');
		$query = $this->db->get();
		return $query->result();
	}

	public function get_arta_cc($from = null, $to = null){

		$where = '';
		
		if($from > 0 && $to > 0){
		    $where = ' WHERE DATE(arta_created_at) >= ' . $from . ' AND DATE(arta_created_at) <= ' . $to . ' ';
        }

		
		$query = "SELECT 
						COALESCE(ref.Citizen_Charter,'Total') as cc,
						COALESCE(SUM(CASE WHEN val = 1 THEN 1 ELSE 0 END),0) AS 'c1',
						COALESCE(SUM(CASE WHEN val = 2 THEN 1 ELSE 0 END),0) AS 'c2',
						COALESCE(SUM(CASE WHEN val = 3 THEN 1 ELSE 0 END),0) AS 'c3',
						COALESCE(SUM(CASE WHEN val = 4 THEN 1 ELSE 0 END),0) AS 'c4',
						COALESCE(SUM(CASE WHEN val = 5 THEN 1 ELSE 0 END),0) AS 'c5'
					FROM 
						(SELECT 'CC1' AS Citizen_Charter
						UNION ALL
						SELECT 'CC2'
						UNION ALL
						SELECT 'CC3') AS ref
					LEFT JOIN (
						SELECT 'CC1' AS Citizen_Charter, arta_cc1 AS val FROM tblcsf_cc1 
						LEFT JOIN tblcsf_arta ON c1_value = arta_cc1 " . $where . " 
						UNION ALL
						SELECT 'CC2' AS Citizen_Charter, arta_cc2 AS val FROM tblcsf_cc2 
						LEFT JOIN tblcsf_arta ON c2_value = arta_cc2 " . $where . " 
						UNION ALL
						SELECT 'CC3' AS Citizen_Charter, arta_cc3 AS val FROM tblcsf_cc3 
						LEFT JOIN tblcsf_arta ON c3_value = arta_cc3 " . $where . "
					) AS combined_data 
					ON ref.Citizen_Charter = combined_data.Citizen_Charter
					GROUP BY ref.Citizen_Charter
					WITH ROLLUP";
		// Execute the query
		$query = $this->db->query($query);

		// Fetch the result
		return $query->result();

	}

	public function get_arta_sqd($from = null, $to = null){

		$where = '';
		
		if($from > 0 && $to > 0){
		    $where = ' AND DATE(arta_created_at) >= ' . $from . ' AND DATE(arta_created_at) <= ' . $to . ' ';
        }

		$this->db->select("sqd_value as sqd, 
		(select count(*) from tblcsf_arta where arta_sqd1 = sqd_value " . $where . " ) as 'sqd1', 
		(select count(*) from tblcsf_arta where arta_sqd2 = sqd_value " . $where . " ) as 'sqd2', 
		(select count(*) from tblcsf_arta where arta_sqd3 = sqd_value " . $where . " ) as 'sqd3',
		(select count(*) from tblcsf_arta where arta_sqd4 = sqd_value " . $where . " ) as 'sqd4', 
		(select count(*) from tblcsf_arta where arta_sqd5 = sqd_value " . $where . " ) as 'sqd5', 
		(select count(*) from tblcsf_arta where arta_sqd5 != sqd_value " . $where . " ) as 'sqdna'");
		
		$this->db->from($this->sqd);
		$query = $this->db->get();
		return $query->result();
	}

}

/* End of file Arta_model.php */