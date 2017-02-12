?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 


	class basic {

		function basic_get_date() {
			$heute = date("Y")."-".date("m")."-".date("d");
			return("$heute");
		}
	
		function basic_get_time()	{
			$zeit = date("G:i:s");
			return("$zeit");
		}

		function basic_get_ger_date($date) {
			$a = explode("-", $date);
			$new_date="".$a[2].".".$a[1].".".$a[0]."";
			return("$new_date");
		}

		function basic_get_ger_datetime($date, $format_time) {
			$a = explode(" ", $date);
			$date = get_ger_date($a[0]);
			$b = explode(":", $a[1]);
			$time = "";
			
			for($i=0; $i<$format_time; $i++) {
				if($time == "") {
					$time = "- ".$b[$i];
				} else {
					$time = $time.':'.$b[$i];
				}
			}

			$new_date= $date." ".$time;
			return("$new_date");
		}

		function basic_get_engl_date($date) {
			if(substr($date, 2, 1)==".") {	
				$var=explode(".", $date); 
				$date=$var[2]."-".$var[1]."-".$var[0];
			} else {
				$date="error";
			}
			return($date);
		}
		
		function basic_get_engl_datetime($date) {
			if(substr($date, 2, 1)==".") {	
				$var=explode(" ", $date); 
					$var2=explode(".", $var[0]); 
					$date=$var2[2]."-".$var2[1]."-".$var2[0]." ".$var[1];
			} else {
				$date="error";
			}
			return($date);
		}

	}

