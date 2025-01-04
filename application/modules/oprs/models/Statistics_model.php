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



	public function __construct() {
		parent::__construct();
		$this->load->database(ENVIRONMENT);
	}

    public function get_submission_summary($from = null, $to = null){
		$oprs = $this->load->database('dboprs', TRUE);
		$oprs->select('p.id as pub_id, publication_desc, IFNULL(count(man_type), 0) as subm_count');
		$oprs->from($this->publication . ' p');

        if($from > 0 && $to > 0){
		    $oprs->join($this->manus . ' m', 'man_type = p.id AND DATE(m.date_created) >= \'' . $from . '\' AND DATE(m.date_created) <= \'' . $to . '\'', 'left');
        }else{
            $oprs->join($this->manus . ' m', 'man_type = p.id', 'left');
        }

        $oprs->group_by('p.id');
		$query = $oprs->get();
		return $query->result();
    }
    
    public function get_submission_stats($from = null, $to = null){
		$oprs = $this->load->database('dboprs', TRUE);
		$oprs->select('p.id as pub_id, publication_desc, IFNULL(count(man_type), 0) as subm_count');
		$oprs->from($this->publication . ' p');
        
        if($from > 0 && $to > 0){
		    $oprs->join($this->manus . ' m', 'man_type = p.id AND DATE(m.date_created) >= \'' . $from . '\' AND DATE(m.date_created) <= \'' . $to . '\'', 'left');
        }else{
            $oprs->join($this->manus . ' m', 'man_type = p.id', 'left');
        }

        $oprs->group_by('p.id');
		$query = $oprs->get();
		return $query->result();
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