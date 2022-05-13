<?
require_once('../config.php');


/* --- Вход --- */

if(isset($_POST['enter']))
{
	$login = dataprocessing($_POST['login']);
	$password = dataprocessing($_POST['password']);
	
	$seachuser = mysql_query("SELECT * FROM lk_user WHERE login='".$login."' LIMIT 1");
	if(mysql_num_rows($seachuser) == 0)
	{
		$_SESSION['notice'] = "Логин не зарегистрирован";
		header("location: /lk");
		exit;	
	}
	else
	{
		$seachuser_result = mysql_fetch_array($seachuser);
		$sault = $seachuser_result['sault'];
		$class = $seachuser_result['class'];
		$password = md5($sault.$password);

		if ($password <> $seachuser_result['password'])
		{
			$_SESSION['notice'] = "Неверный пароль";
			header("location: /lk");
			exit;
		}
		else
		{
			
			setcookie("lk_user", $seachuser_result['login']."|".$seachuser_result['password'] , time() + 1209600, "/", $_SERVER['SERVER_NAME'], "0");
			
			header("location: /lk");
			exit;			
		}
	}
}

/* --- // --- */


/* --- Выход --- */

if(isset($_POST['destroy']))
{
	if(isset($_COOKIE['lk_user'])) 
	{
		setcookie("lk_user", "", time() - 1209600, "/", $_SERVER['SERVER_NAME'], "0");
	}
	
	header("location: /lk");
	exit;
}

/* --- // --- */



/* --- Окно входа --- */

if (LK_USER != 'true') {
	echo $HEADER;
	echo "<div class='lk-wrap'>
		<div class='center'>
			<div class='lk-head lk-head-center'>Вход в личный кабинет</div>
			<div class='lk-auth'>
				<form action='' method='post'>
					<div class='formbox'>
						Логин<br />
						<input type='text' name='login' value='' class='input lk-input' required />
					</div>
					<div class='formbox'>
						Пароль<br />
						<input type='password' name='password' value='' class='input lk-input' autocomplete='new-password' required />
					</div>";
				
					if (isset($_SESSION['notice'])) {
						$notice = dataprocessing($_SESSION['notice']);
						echo "<div class='lk-notice'>".$notice."</div>";
						$_SESSION['notice'] = "";
					}

					echo "<div class='formbox_submit'>
						<input name='enter' type='submit' value='Войти' class='lk-btn button' />
					</div>
				</form>
			</div>
		</div>
	</div>";
	echo $FOOTER;
	exit;
}

/* --- // --- */


//7703590927

//echo $_SESSION['lk_table'];


//в Excel
if (!empty($_GET['export'])) {
	echo lk_excel();
}
else {			
	echo $HEADER;
	
	$company = LK_USER_COMPANY;
	if (empty($company)) $company = "Не заполнено";
	
	echo "<div class='lk-wrap'>
		<div class='center'>
		<form action='' method='POST'>
			<input type='submit' class='lk-destroy' value='Выйти' name='destroy'>
			<div class='lk-login'>ИНН: ".LK_USER_LOGIN."</div>
			<div class='clear'></div>
		</form>
		<div class='clear'></div>
		<div class='lk-head'>Учет прохождения предварительного/периодического медицинского осмотра</div>";
		
		echo "<div class='lk-comp'>".$company."</div>
		<div class='clear'></div>
		<div class='lk-back lk-back-dark'>
			<form class='lk-form' action='' method='GET'>";
				
				// echo lk_form();
				require_once('lk-form/form.php');
			
			echo "</form>
		</div>";

		echo "</div>
	</div>";
	
	echo $FOOTER;
}
	

?>