<?php 
require_once 'dbconfig.php';

class USER
{
	private $conn;
	
	public function __construct()
	{
		$database = new Database();
		$db = $database->dbConnection();
		$this->conn = $db;
	}

	public function runQuery($sql)
	{
		$stmt = $this->conn->prepare($sql);
		return $stmt;
	}

	public function lastID()
	{
		$stmt = $this->conn->lastInsertId();
		return $stmt;
	}

	public function register($uname, $email, $upass, $code)
	{
		try
		{
			$password = md5($upass);
			$stmt = $this->conn->prepare("INSERT INTO tbl_users(userName,userEmail,userPass,tokenCode) VALUES(:user_name, :user_mail, :user_pass, :active_code)");
			$stmt->bindparam(":user_name", $uname);
			$stmt->bindparam(":user_mail", $email);
			$stmt->bindParam(":user_pass", $password);
			$stmt->bindparam(":active_code", $code);
			$stmt->execute();
			return $stmt;
		}
		catch(PDOException $ex)
		{
			echo $ex->getMessage();
		}
	} // register


	public function login($email,$upass)
	{
		try
		{
			$stmt = $this->conn->prepare("SELECT * FROM tbl_users WHERE userEmail=:email_id");
			$stmt->execute(array(":email_id"=>$email));
			$userRow=$stmt->fetch(PDO::FETCH_ASSOC);

			if($stmt->rowCount() == 1)
			{
				if($userRow['userStatus']=="Y")
				{
					if($userRow['userPass']==md5($pass))
					{
						$_SESSION['userSession'] = $userRow['userID'];
						return true;
					} // userPass
					else
					{
						header("Location: index.php?error");
						exit;
					}
				} // userStatus
				else {
						header("Location: index.php?inactive");
						exit;
					}
			} // rowCount
			else 
			{
				header("Location: index.php?error");
				exit;
			}
			
		}	
		catch(PDOException $ex)
		{
			echo $ex->getMessage();
		}
	} // login


	public function is_logged_in()
	{
		if(isset($_SESSION['userSession']))
		{
			return true;
		}
	} // is_logged_in

	public function redirect($url)
	{
		header("Location: $url");
	} // redirect

	public function logout()
	{
		session_destroy();
		$_SESSION['userSession'] = false;
	} // logout

	function send_mail($email, $message, $subject)
	{
		// require_once('mailer/class.phpmailer.php');
		require_once('mailer/PHPMailerAutoload.php');
		$mail = new PHPMailer();
		$mail->IsSMTP();
		$mail->SMTPDebug 	= 0;
		$mail->SMTPAuth 	= true;
		$mail->SMTPSecure 	= "ssl";
		// $mail->Host			= "smtp.gmail.com";
		$mail->Host			= "myhost";
		$mail->Port			= 465;
		$mail->AddAddress($email);
		// $mail->Username="";
		$mail->Username="admin@mbsitapp.co.uk";
		// $mail->Password="gmailpassword";
		$mail->Password="mypassword";
		$mail->SetFrom('contact@imonweb.co.uk', 'ImonWeb');
		$mail->AddReplyTo('contact@imonweb.co.uk', 'ImonWeb');
		$mail->Subject 		= $subject;
		$mail->MsgHTML($message);
		$mail->Send();
	} // send_mail



} // user

 ?>