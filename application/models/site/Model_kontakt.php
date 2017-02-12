<?php
class model_kontakt extends CI_Model {


		public function get_kontakt_adresse() {

			$query = $this->db->query('SELECT * FROM ffwbs_kontakt_adresse WHERE wehrID="'.$GLOBALS['akt_wehr_details']['wehrID'].'"');
			 
			if($query->num_rows() == 0) {
				if($GLOBALS['akt_wehr_details']['wehrID'] == 0) {
					// Basis Kontakt erstellen wenn keine Wehr eingestellt und nichts in der DB zu finden ist
					$adresse[0]['vorname'] = $GLOBALS['basic_contact_name'];
					$adresse[0]['nachname'] = "";
					$adresse[0]['aufgabe'] = "";
					$adresse[0]['str'] = $GLOBALS['basic_contact_str'];
					$adresse[0]['ort'] = $GLOBALS['basic_contact_ort'];
					$adresse[0]['plz'] = "";
					$adresse[0]['email'] = $GLOBALS['basic_contact_email'];
					$adresse[0]['telefon'] = $GLOBALS['basic_contact_tel'];
				} else {
					// Adresse der jeweiligen eingestellten Wehr benutzen (Wehr_details)
					$adresse[0]['vorname'] = $GLOBALS['akt_wehr_details']['wehr_name'];
					$adresse[0]['nachname'] = "";
					$adresse[0]['aufgabe'] = "";
					$adresse[0]['str'] = $GLOBALS['akt_wehr_details']['str']." ".$GLOBALS['akt_wehr_details']['hausnr'];
					$adresse[0]['ort'] = $GLOBALS['akt_wehr_details']['ort'];
					$adresse[0]['plz'] = $GLOBALS['akt_wehr_details']['plz'];
					$adresse[0]['email'] = $GLOBALS['akt_wehr_details']['email'];
					$adresse[0]['telefon'] = $GLOBALS['akt_wehr_details']['tel'];
				}
			} else {
				$adresse = $query->result_array();
			}

			return $adresse;

		}


}