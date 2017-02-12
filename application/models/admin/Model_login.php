<?php
class model_login extends CI_Model {


	/*
	|--------------------------------------------------------------------------
	| einloggen = Verifiziert den User und loggt ihn ein
	|--------------------------------------------------------------------------
	*/
	public function login() {

		if(isset($_POST["benutzername"]) && isset($_POST["password"])) {
			$options = ['cost' => 12];	
			$query = $this->db->query('SELECT * FROM ffwbs_admin_user WHERE email="'.$_POST["benutzername"].'"');
			
			if ($this->db->affected_rows()==1) {
				$userdata = $query->row_array();

				if(password_verify($_POST["password"], $userdata['password'])) {
					if($userdata['verifyed']==1 && $userdata['locked']==0) {
						$_SESSION["userID"] = $userdata['userID'];	
						$_SESSION["username"] = $userdata['vorname'];
						$_SESSION["logincount"] = $userdata["logincount"];
						$_SESSION["secondnavilist"] = "";

						$log_action = 'hat sich eingeloggt.';
						basic_writelog($log_action,'admin - login', 1);

						$_GET["op"]='dashboard';
						$msg = 'Hallo '.$userdata['vorname'].'!';

						if($userdata["logincount"]>0) {
							$data_adminuser = array(
							   'logincount' => ''.($userdata["logincount"]+1).'' ,
							   'lastlogin' => ''.basic_get_date().' '.basic_get_time().'',
							);

							$this->db->where('userID', $_SESSION["userID"]);
							$this->db->update('admin_user', $data_adminuser);
							$_SESSION["logincount"] = $userdata["logincount"]+1;
						}

					} else {
						if($userdata['locked']==1) {
							$msg = 'error:Dein Account wurde gesperrt.<br>Bitte wende dich an den Site-Administrator.';
						} else {
							$msg = 'error:Du hast deine E-Mail Adresse noch nicht bestätigt<br/>Bitte drücke den Link in deiner Bestätigungsmail.';	
						}
					}
				} else {
					$msg ='error:Deine Login-Daten sind leider falsch. Probier es noch einmal.';
				}
			} else {
				$msg ='error:Deine Login-Daten sind leider falsch. Probier es noch einmal.';
			}
		} else {
			$msg ='error:Sie müssen einen Benutzername oder Passwort eingeben.';
		}

		return $msg;

	}


	/*
	|--------------------------------------------------------------------------
	| ausloggen = Die Session wird gelöscht
	|--------------------------------------------------------------------------
	*/
	public function logout() {

		$log_action = 'hat sich ausgeloggt.';
		basic_writelog($log_action,'admin - logout', 1);

		$_SESSION["username"] = $_SESSION["userID"] = '';
		session_destroy();

	}

}

?>