<?php
	// konfigrasyon dosyası
	require_once 'config/config.php';


	// Değişkenleri tanımlayın ve boş değerlerle başlatın
	$username = $password = $confirm_password = "";

	$username_err = $password_err = $confirm_password_err = "";

	// Gönderilen form verilerini işle (get ile)
	// post ile
	if ($_SERVER['REQUEST_METHOD'] === 'POST') {

		// kullanıcı adının boş olup olmadığını doğrula
		if (empty(trim($_POST['username']))) {
			$username_err = "Lütfen kullanıcı adınızı giriniz.";

			// Kullanıcı adının zaten mevcut olup olmadığını kontrol edin
		} else {

			// Bir seçim ifadesi hazırlayın
			$sql = 'SELECT id FROM users WHERE username = ?';

			if ($stmt = $mysql_db->prepare($sql)) {
				// parametreyi ayarla
				$param_username = trim($_POST['username']);

				// İfadeyi hazırlamak için param değişkenini bağlayın
				$stmt->bind_param('s', $param_username);

				// İfadeyi yürütme girişimi
				if ($stmt->execute()) {
					
					// Yürütülen sonucu sakla
					$stmt->store_result();

					if ($stmt->num_rows == 1) {
						$username_err = 'Bu kullanıcı adı zaten alındı';
					} else {
						$username = trim($_POST['username']);
					}
				} else {
					echo "Oops! ${$username}, something went wrong. Please try again later.";
				}

				// durumu kapat
				$stmt->close();
			} else {

				// bağlantıyı kapa
				$mysql_db->close();
			}
		}

		// şifreyi doğrula
	    if(empty(trim($_POST["password"]))){
	        $password_err = "Lütfen bir şifre giriniz.";     
	    } elseif(strlen(trim($_POST["password"])) < 6){
	        $password_err = "Şifreniz en az 6 karakter olmalı";
	    } else{
	        $password = trim($_POST["password"]);
	    }
    
	    // şifreyi onayla
	    if(empty(trim($_POST["confirm_password"]))){
	        $confirm_password_err = "Lütfen şifrenizi onaylayın";     
	    } else{
	        $confirm_password = trim($_POST["confirm_password"]);
	        if(empty($password_err) && ($password != $confirm_password)){
	            $confirm_password_err = "Şifre eşleşmedi";
	        }
	    }

	    // Veritabanına eklemeden önce giriş hatasını kontrol edin

	    if (empty($username_err) && empty($password_err) && empty($confirm_err)) {

	    	// Ek açıklamayı hazırlayın
			$sql = 'INSERT INTO users (username, password) VALUES (?,?)';

			if ($stmt = $mysql_db->prepare($sql)) {

				// parametreleri ayarla
				$param_username = $username;
				$param_password = password_hash($password, PASSWORD_DEFAULT); // Created a password

				// İfadeyi hazırlamak için param değişkenini bağlayın
				$stmt->bind_param('ss', $param_username, $param_password);

				// yürütme girişimi
				if ($stmt->execute()) {
					header('location: ./login.php');
				} else {
					echo "Something went wrong. Try signing in again.";
				}
				$stmt->close();	
			}
			$mysql_db->close();
	    }
	}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
	<meta charset="UTF-8">
	<title>Kayıt ol</title>
	<link href="https://stackpath.bootstrapcdn.com/bootswatch/4.4.1/cosmo/bootstrap.min.css" rel="stylesheet" integrity="sha384-qdQEsAI45WFCO5QwXBelBe1rR9Nwiss4rGEqiszC+9olH1ScrLrMQr1KmDR964uZ" crossorigin="anonymous">
	<style>
        .wrapper{ 
        	width: 500px; 
        	padding: 20px; 
        }
        .wrapper h2 {text-align: center}
        .wrapper form .form-group span {color: red;}
	</style>
</head>
<body>
	<main>
		<section class="container wrapper">
			<h2 class="display-4 pt-3">Servise üye ol</h2>
        	<p class="text-center">Lütfen bilgilerinizi doldurunuz.</p>
        	<form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST">
        		<div class="form-group <?php (!empty($username_err))?'has_error':'';?>">
        			<input placeholder="kullanıcı adınızı giriniz" type="text" name="username" id="username" class="form-control" value="<?php echo $username ?>">
        		</div>

        		<div class="form-group <?php (!empty($password_err))?'has_error':'';?>">
        			<input placeholder="şifrenizi giriniz" type="password" name="password" id="password" class="form-control" value="<?php echo $password ?>">
        		</div>

        		<div class="form-group <?php (!empty($confirm_password_err))?'has_error':'';?>">
        			<input placeholder="şifrenizi doğrulayınız" type="password" name="confirm_password" id="confirm_password" class="form-control" value="<?php echo $confirm_password; ?>">
        		</div>

        		<div class="form-group">
        			<input type="submit" class="btn btn-block btn-outline-success" value="Onayla">
        			<input type="reset" class="btn btn-block btn-outline-primary" value="Sıfırla">
        		</div>
        		<p>Zaten hesabınız var mı? <a href="login.php">Giriş yapın</a>.</p>
        	</form>
		</section>
	</main>
</body>
</html>