<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Exportcodes extends CI_Model 
{
    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
    }

	function search_description($desc)
	{
		$this->db->select('code,descrip_1,descrip_2')->from('export_codes')->like('descrip_1', $desc); 
		$query = $this->db->get();
	    return $query->result();
	}

	
	/*
	* pass in an array of search terms 
	* to search for description fields in export database
	*/
	function search($terms){
	
		$search_clauses = $this->search_clauses_for_description_search($terms);

		$sql_begin = "select code, descrip_1, descrip_2 from export_codes_text_search ".
			"where MATCH(descrip_1, descrip_2) ".
			"AGAINST ('";
			
		$sql_end = "' IN BOOLEAN MODE)";
		
		$sql_final = "";
		
		for($clause=0; $clause < count($search_clauses); $clause++){
			$sql_final .= $sql_begin.$search_clauses[$clause].$sql_end;
			if($clause < count($search_clauses)-1)
				$sql_final .= " UNION ";
		}
		
		$query = $this->db->query($sql_final);
		$result = NULL;
		if($query->num_rows() > 0)
			$result = $query->result();
		return $result;
	}
	
	
	
	/*
	* given an array of search terms
	* generates array of search clauses
	* e.g.: [0] => hello [1] => sucka [2] => mc
	* first permuation: +hello +sucka +mc
	* second: +hello +sucka mc
	* third: +hello sucka mc
	* @param: array of search terms
	*/
	function search_clauses_for_description_search($terms){
		/*
		* generate all permuatations of where clause
		* for mysql query
		*/
		$search_permutations = array();
		
		/*
		* loop backwards for each term in the array
		* we want to generate all permutations needed for search
		* e.g.: [0] => hello [1] => sucka [2] => mc
		* first permuation: +hello +sucka +mc
		* second: +hello +sucka mc
		* third: +hello sucka mc
		*/
		for($num_terms=count($terms)-1; $num_terms >= 0; $num_terms--){
			//echo "Num Terms ".$num_terms;
			$permutation = "";
			/*
			* add a "+" symbol to each term in the array
			* reduce the number of terms "+" symbol is added to by
			* the number of times we have looped through terms
			*/
			for($term=0; $term <= count($terms)-1; $term++){
				if($term <= $num_terms)
					$permutation .= "+".$terms[$term]." ";
				else
					$permutation .= $terms[$term]." ";
			}
			
			array_push($search_permutations, rtrim($permutation));
		
		}
		
		return $search_permutations;
	}
	
	
	/**
	select * from export_codes_text_search 
	where 
		MATCH(descrip_1, descrip_2)
		AGAINST("+chicken +paste" IN BOOLEAN MODE)
	UNION
	select * from export_codes_text_search 
	where 
		MATCH(descrip_1, descrip_2)
		AGAINST("+chicken paste" IN BOOLEAN MODE);
	**/

}
/** end model **/