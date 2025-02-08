<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Statistics_model extends CI_Model {
	// oprs
	private $criterias = 'tblcriterias';
	private $scores = 'tblscores';
	private $manus = 'tblmanuscripts';
	private $user = 'tblusers';
    private $publication = 'tblpublication_types';
	private $coauthors = 'tblcoauthors';
	private $sex = 'tblsex';
	private $editors = 'tbleditors_review';
	private $tech_rev_score = 'tbltech_rev_score';



	public function __construct() {
		parent::__construct();
		$this->load->database(ENVIRONMENT);
	}

    public function get_submission_summary($from = null, $to = null){

		$oprs = $this->load->database('dboprs', TRUE);
		$oprs->select('p.id as pub_id, publication_desc,   
		COALESCE(SUM(man_type), 0) as subm_count, 
		COALESCE(SUM(CASE WHEN man_status = 14 THEN 1 ELSE 0 END),0) as rej_count,
		COALESCE(SUM(CASE WHEN man_status = 12 THEN 1 ELSE 0 END),0) as pass_count,
		COALESCE(SUM(CASE WHEN man_status > 1 THEN 1 ELSE 0 END),0) as process_count,
		COALESCE(SUM(CASE WHEN man_status = 16 THEN 1 ELSE 0 END),0) as publ_count');
		$oprs->from($this->publication . ' p');

        if($from > 0 && $to > 0){
		    $oprs->join($this->manus . ' m', 'man_type = p.id AND DATE(m.date_created) >= \'' . $from . '\' AND DATE(m.date_created) <= \'' . $to . '\'', 'left');
        }else{
            $oprs->join($this->manus . ' m', 'man_type = p.id', 'left');
        }

        $oprs->group_by('p.id');
		$query = $oprs->get();

		$results = $query->result();

		// Initialize total counters
		$totals = [
			'pub_id' => count($results) + 2,
			'publication_desc' => 'Total',
			'subm_count' => 0,
			'rej_count' => 0,
			'pass_count' => 0,
			'process_count' => 0,
			'publ_count' => 0
		];

		// Process the results and accumulate the totals
		foreach ($results as $row) {
			$totals['subm_count'] += $row->subm_count;
			$totals['rej_count'] += $row->rej_count;
			$totals['pass_count'] += $row->pass_count;
			$totals['process_count'] += $row->process_count;
			$totals['publ_count'] += $row->publ_count;
		}

		// Append grand total row to the results array
		$results[] = (object) $totals; // Convert the array to an object for consistency

		return $results;
    }
    
    public function get_submission_stats($from = null, $to = null){
		$oprs = $this->load->database('dboprs', TRUE);
		$oprs->select('p.id as pub_id, publication_desc, COALESCE(SUM(man_type), 0) as subm_count,
		COALESCE(COUNT(DISTINCT CASE WHEN tr_final = 2 THEN t.tr_man_id END),0) as rej_teded_count,
		COALESCE(COUNT(DISTINCT CASE WHEN tr_final = 1 THEN t.tr_man_id END),0) as pass_teded_count,
		COALESCE(COUNT(DISTINCT CASE WHEN edit_status = 15 THEN e.edit_man_id END),0) as pass_assoced_count,
		COALESCE(COUNT(DISTINCT CASE WHEN edit_status = 14 THEN e.edit_man_id END),0) as rej_assoced_count,
		COALESCE(COUNT(DISTINCT CASE WHEN man_status > 1 THEN m.row_id END),0) as process_count,
		COALESCE(COUNT(DISTINCT CASE WHEN man_status = 16 THEN m.row_id END),0) as publ_count');
		$oprs->from($this->publication . ' p');
        
        if($from > 0 && $to > 0){
		    $oprs->join($this->manus . ' m', 'man_type = p.id AND DATE(m.date_created) >= \'' . $from . '\' AND DATE(m.date_created) <= \'' . $to . '\'', 'left');
        }else{
            $oprs->join($this->manus . ' m', 'man_type = p.id', 'left');
        }
	
		$oprs->join(
			'(' .
			'SELECT ed.* FROM ' . $this->editors . ' ed ' .
			'JOIN (SELECT edit_man_id, MAX(er.row_id) AS last_entry FROM ' . $this->editors . ' er JOIN tblusers on edit_usr_id = usr_id where usr_desc LIKE "%associate%" GROUP BY edit_man_id) latest ' .
			'ON ed.row_id = latest.last_entry ' .
			') e',
			'm.row_id = e.edit_man_id',
			'left'
		);

		$oprs->join($this->tech_rev_score . ' t', 'm.row_id = t.tr_man_id', 'left');

        $oprs->group_by('p.id');
		$query = $oprs->get();
		
		$results = $query->result();

		// Initialize total counters
		$totals = [
			'pub_id' => count($results) + 2,
			'publication_desc' => 'Total',
			'subm_count' => 0,
			'rej_teded_count' => 0,
			'pass_teded_count' => 0,
			'rej_assoced_count' => 0,
			'pass_assoced_count' => 0,
			'process_count' => 0,
			'publ_count' => 0
		];

		// Process the results and accumulate the totals
		foreach ($results as $row) {
			$totals['subm_count'] += $row->subm_count;
			$totals['rej_teded_count'] += $row->rej_teded_count;
			$totals['pass_teded_count'] += $row->pass_teded_count;
			$totals['rej_assoced_count'] += $row->rej_assoced_count;
			$totals['pass_assoced_count'] += $row->pass_assoced_count;
			$totals['process_count'] += $row->process_count;
			$totals['publ_count'] += $row->publ_count;
		}

		// Append grand total row to the results array
		$results[] = (object) $totals; // Convert the array to an object for consistency
		
		return $results;

    }
    public function get_author_by_sex_stats($from = null, $to = null){
		
		$oprs = $this->load->database('dboprs', TRUE);
		$oprs->select('sex, IFNULL(count(DISTINCT(man_author)),0) as total');
		$oprs->from($this->sex . ' s');

		if($from > 0 && $to > 0){
		    $oprs->join($this->manus . ' m', 's.id = m.man_author_sex AND man_author_sex > 0 AND DATE(m.date_created) >= \'' . $from . '\' AND DATE(m.date_created) <= \'' . $to . '\'', 'left');
        }else{
            $oprs->join($this->manus . ' m', 's.id = m.man_author_sex AND man_author_sex > 0', 'left');
        }


        $oprs->group_by('s.id');

		$query = $oprs->get();
		$author = $query->result();

		$oprs = $this->load->database('dboprs', TRUE);
		$oprs->select('sex, IFNULL(count(DISTINCT(coa_name)),0) as total');
		$oprs->from($this->sex . ' s');


		if($from > 0 && $to > 0){
		    $oprs->join($this->coauthors . ' c', 's.id = c.coa_sex AND coa_sex > 0 AND DATE(c.date_created) >= \'' . $from . '\' AND DATE(c.date_created) <= \'' . $to . '\'', 'left');
        }else{
            $oprs->join($this->coauthors . ' c', 's.id = c.coa_sex AND coa_sex > 0', 'left');
        }


        $oprs->group_by('s.id');
		$query = $oprs->get();
		$coauthor = $query->result();

        $combined_counts = [
            'authors' => $author,
            'coauthors' => $coauthor,
        ];

        return $combined_counts;

    }
}

/* End of file Review_model.php */