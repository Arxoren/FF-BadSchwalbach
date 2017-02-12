<?php
class model_adminuser extends CI_Model {

	public function adminuser_showlist() {
		
		$query_adminuser = $this->db->query('SELECT * FROM ffwbs_admin_user ORDER BY vorname, nachname ASC');
		$var['adminuser'] = $query_adminuser->result_array();

		$var['page_headline'] = "Adminuser Verwaltung";
		$var['page_btn_addnew'] = "Einen neuen Admin-Nutzer anlegen";
		return $var;
		
	}

	public function editor() {
		
		if(isset($_GET["adminuserID"]) && $_GET["adminuserID"]!="") {
			$query_adminuser = $this->db->query('SELECT * FROM ffwbs_admin_user WHERE userID="'.$_GET["adminuserID"].'"');
			$var['adminuser'] = $query_adminuser->row_array();

			$var['page_headline'] = "Adminuser bearbeiten";
		} else {
			$var['adminuser']  = array(
			   'userID' => '',
			   'wehren' => ''
			);
			$var['page_headline'] = "Adminuser anlegen";
		}
		return $var;
		
	}


	public function save() {

		if($_POST["editID"]=="") {

			$query_adminuser = $this->db->query('SELECT * FROM ffwbs_admin_user WHERE email="'.$_POST["email"].'"');
			if ($query_adminuser->num_rows() == 0) {
				$pw = $this->get_password();
				$token = $this->get_token();

		    	$data_adminuser = array(
				   'vorname' => ''.$_POST["vorname"].'' ,
				   'nachname' => ''.$_POST["nachname"].'' ,
				   'email' => ''.$_POST["email"].'' ,
				   'password' => ''.$pw["password_en"].'' ,
				   'logincount' => '0' ,
				   'locked' => '0' ,
				   'verifyed' => '0',
				   'token' => ''.$token.'' 
				);

				$this->db->insert('admin_user', $data_adminuser); 
				$msg = "success:Der Adminnutzer wurde angelegt.";
		
				// Load E-Mail Text
				$adminuser["vorname"] = $_POST["vorname"];
				$adminuser["nachname"] = $_POST["nachname"];
				$adminuser["token"] = $token;
				$nachricht=$this->get_text_emailconfirmation($adminuser);

				$header = 'From: '.$GLOBALS["project_domain"].'';
				mail($_POST["email"], $GLOBALS["project_domain"], $nachricht, $header);

				$log_action = 'hat einen neuen Adminuser "'.$_POST["vorname"].' '.$_POST["nachname"].'" angelegt.';
				basic_writelog($log_action,'adminuser - save', 2);
			} else {
				$msg = "error:Diese E-Mail Adresse ist bereits belegt.";
				$GLOBALS['globalmessage'] = $msg;
				$var = $this->adminuser_showlist();
				return $var;
			}		
		} else {
			$data_adminuser = array(
			   'vorname' => ''.$_POST["vorname"].'' ,
			   'nachname' => ''.$_POST["nachname"].'' ,
			   'email' => ''.$_POST["email"].'' ,
			);

			$this->db->where('userID', $_POST["editID"]);
			$this->db->update('admin_user', $data_adminuser);
			$msg = "success:Der Adminnutzer wurde bearbeitet.";
		
			$log_action = 'hat den Adminuser "#'.$_POST["editID"].' / '.$_POST["vorname"].' '.$_POST["nachname"].'" bearbeitet.';
			basic_writelog($log_action,'adminuser - save', 2);
		}

		$GLOBALS['globalmessage'] = $msg;
		$var = $this->adminuser_showlist();
		return $var;
	}

	function sendmail() {
		$query_adminuser = $this->db->query('SELECT * FROM ffwbs_admin_user WHERE userID="'.$_GET["id"].'"');
		$adminuser = $query_adminuser->row_array();
		
		// Generate password and update
		$pw = $this->get_password();
		$this->update_pw($pw);
		$msg = "success:Das Passwort wurde zurückgesetzt und per Mail verschickt.";

		// Load E-Mail Text
		$adminuser["password"] = $pw['password'];
		$nachricht=$this->get_text_newpassword($adminuser);

		$header = 'From: '.$GLOBALS["project_domain"].'';
		mail($adminuser["email"], $GLOBALS["project_domain"], $nachricht, $header);

		$log_action = 'hat die Zugangsdaten des Adminusers "#'.$adminuser["userID"].' / '.$adminuser["vorname"].' '.$adminuser["nachname"].'" erneut verschickt.';
		basic_writelog($log_action,'adminuser - sendmail', 2);

		$GLOBALS['globalmessage'] = $msg;
		$var = $this->adminuser_showlist();
		return $var;
	}

	function resetpassword() {
		if(isset($_POST["email"])) {
			$query_adminuser = $this->db->query('SELECT * FROM ffwbs_admin_user WHERE email="'.$_POST["email"].'"');
			if($query_adminuser->num_rows()==1) {	
				$adminuser = $query_adminuser->row_array();
				
				// Generate password and update
				$_GET["id"] = $adminuser["userID"];
				$pw = $this->get_password();
				$this->update_pw($pw);
				$msg = "success:Das Passwort wurde zurückgesetzt und per Mail verschickt.";

				// Load E-Mail Text
				$adminuser["password"] = $pw['password'];
				$nachricht=$this->get_text_newpassword($adminuser);

				$header = 'From: '.$GLOBALS["project_domain"].'';
				mail($adminuser["email"], $GLOBALS["project_domain"], $nachricht, $header);

			} else {
				$msg = 'error:Dieser Nutzer existiert nicht.';
			}
		} else {
			$msg = 'error:Bitte geben Sie eine E-Mail Adresse ein.';
		}
		return $msg;
	}
	
	/*
	|--------------------------------------------------------------------------
	| E-Mail Confirmation
	|--------------------------------------------------------------------------
	*/
	function email_confirmation() {

		$query_adminuser = $this->db->query('SELECT * FROM ffwbs_admin_user WHERE token="'.$_GET["t"].'"');
		if($query_adminuser->num_rows()==1) {
			$adminuser = $query_adminuser->row_array();
			
			$data_adminuser = array(
			   'token' => '' ,
			   'verifyed' => '1'
			);

			$this->db->where('userID', $adminuser["userID"]);
			$this->db->update('admin_user', $data_adminuser);

			// Generate password and update
			$_GET["id"] = $adminuser["userID"];
			$pw = $this->get_password();
			$this->update_pw($pw);
			
			$adminuser["password"] = $pw['password'];
			$nachricht=$this->get_text_newpassword($adminuser);

			$header = 'From: '.$GLOBALS["project_domain"].'';
			mail($adminuser["email"], $GLOBALS["project_domain"], $nachricht, $header);

			$msg = "success:Dein Account wurde aktiviert. Du bekommst in den nächsten Minuten eine E-Mail mit deinen Zugangsdaten.";			
		} else {
			$msg = "error:Entschuldigung, Dein Token ist abgelaufen. Bitte wende dich an den Seitenadministrator.";			
		}

		return $msg;
	}


	/*
	|--------------------------------------------------------------------------
	| Password Helper
	|--------------------------------------------------------------------------
	*/
	function get_token() {
		$this->load->helper('string');
		$token = random_string('alnum', 22);
		return $token;
	}

	function get_password() {
		$length = 12;
		$options = ['cost' => 12];	
				
		$chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()_-=+;:,.?";
		$password = substr( str_shuffle( $chars ), 0, $length);
		$password_en = password_hash($password, PASSWORD_BCRYPT, $options);

		$pw_array = array(
			'password' => ''.$password.'' ,
			'password_en' => ''.$password_en.'' ,
 		);		
		return $pw_array;
	}

	function update_pw($pw) {
		$data_adminuser = array(
			'password' => ''.$pw["password_en"].''
		);

		$this->db->where('userID', $_GET["id"]);
		$this->db->update('admin_user', $data_adminuser);
	}

	/*
	|--------------------------------------------------------------------------
	| Einen Einsatz löschen
	|--------------------------------------------------------------------------
	*/
	public function delete() {

		$query_adminuser = $this->db->query('SELECT * FROM ffwbs_admin_user WHERE userID="'.$_GET["id"].'"');
		$adminuser = $query_adminuser->row_array();
		
		$query = $this->db->query('DELETE FROM ffwbs_admin_user WHERE userID="'.$_GET["id"].'"');

		$log_action = 'hat den Adminuser "#'.$adminuser["userID"].' | '.$adminuser["vorname"].' '.$adminuser["nachname"].'" gelöscht.';
		basic_writelog($log_action,'adminuser - sendmail', 2);

		$GLOBALS['globalmessage'] = "success:Der Adminnutzer wurde gelöscht";

	}



	/*
	|--------------------------------------------------------------------------
	| ADMIN USER SETTINGS
	|--------------------------------------------------------------------------
	*/
	public function usersettings() {
		
		$query_adminuser = $this->db->query('SELECT * FROM ffwbs_admin_user WHERE userID="'.$_SESSION["userID"].'"');
		$var['adminuser'] = $query_adminuser->row_array();

		$var['page_headline'] = "Einstellungen bearbeiten";

		return $var;
		
	}

	public function usersettings_save() {
		$sql = 'SELECT * FROM ffwbs_admin_user WHERE userID="'.$_SESSION["userID"].'"';
		$query = $this->db->query($sql);
		$userdata = $query->row_array();

		$data_adminuser = array(
		   'vorname' => ''.$_POST["vorname"].'' ,
		   'nachname' => ''.$_POST["nachname"].'' ,
		   'email' => ''.$_POST["email"].'' ,
		);

		$this->db->where('userID', $_SESSION["userID"]);
		$this->db->update('admin_user', $data_adminuser);
		$msg = "success:Deine Daten wurden aktualisiert.";

		if($_POST["password_old"]!="" && $_POST["password_new"]!="") {
			if(password_verify($_POST["password_old"], $userdata['password'])) {
				
				$options = ['cost' => 12];	
				$pw = password_hash($_POST["password_new"], PASSWORD_BCRYPT, $options);

				$data_adminuser = array(
				   'password' => ''.$pw.'' ,
				);
				$this->db->where('userID', $_SESSION["userID"]);
				$this->db->update('admin_user', $data_adminuser);

				if($_SESSION["logincount"]==0) {
					$_SESSION["logincount"] = 1;
					$data_adminuser = array(
					   'logincount' => ''.($_SESSION["logincount"]+1).'' ,
					   'lastlogin' => ''.basic_get_date().' '.basic_get_time().'',
					);
					
					$this->db->where('userID', $_SESSION["userID"]);
					$this->db->update('admin_user', $data_adminuser);
				}

			} else {
				$msg = "error:Dein altes Passwort stimmt nicht.";
			}
		}

		$GLOBALS['globalmessage'] = $msg;
	}

	/*
	|--------------------------------------------------------------------------
	| Mail-Texte
	|--------------------------------------------------------------------------
	*/
	private function get_text_emailconfirmation($content) {

	$text= "
Hallo ".$content["vorname"].",

Es ist ein Admin-Account auf ".$GLOBALS["project_domain"]." für dich angelegt worden. 

Bevor Du dich einloggen kannst, musst Du noch deine E-Mail-Adresse bestätigen. Dazu klicke diesen Link:
www.".$GLOBALS["project_domain"]."/admin/?op=confirmation&t=".$content['token']."
		
Viel Spaß.
		";

		return $text;
	}
	private function get_text_newpassword($content) {

	$text= "
Hallo ".$content["vorname"].",

Dein Account wurde erfolgreich freigeschaltet. Deine Zugangsdaten lauten: 

Benutzername: ".$content["email"]."
Passowrd: ".$content["password"]."
			
Zu seinem Adminbereich gelangst Du hier:
www.".$GLOBALS["project_domain"]."/admin
		
Viel Spaß.
		";

		return $text;
	}
	private function get_text_resetpassword($content) {

	$text= "
Hallo ".$content["vorname"].",

Dein Passwort wurde zurückgesetzt. Mit deinem temporären Passwort solltest Du dich wieder einloggen können. 

Passowrd: ".$content["password"]."
			
Zu seinem Adminbereich gelangst Du hier:
www.".$GLOBALS["project_domain"]."/admin
		
Viel Spaß.
		";

		return $text;
	}

}
?>