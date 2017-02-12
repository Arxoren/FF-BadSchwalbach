<?php
class model_charts extends CI_Model {

	
	public function get_chartdata($id) {

		$id = explode(";", $id);
		$i=0;

		foreach($id as $chartid) {
			$query = $this->db->query('SELECT * FROM ffwbs_charts WHERE chartID="'.$chartid.'"');
			$querryvar = $query->row_array();

			$chartarray[$i] = $querryvar;

			$chartarray[$i]['chart_labels']=explode(";", $querryvar['chart_labels']);
			$chartarray[$i]['chart_data']=explode(";", $querryvar['chart_data']);
			$chartarray[$i]['chart_datacolor']=explode(";", $querryvar['chart_datacolor']);
			$i++;
		}

		return $chartarray;
	}


}