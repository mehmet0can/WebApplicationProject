<!DOCTYPE html>
<html lang="tr">
<head>
  <meta charset="UTF-8">
  <title>Giriş yap</title>
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
      <h2 class="display-4 pt-3">Servis</h2>
          <p class="text-center">Giriş yapmak için formları doldurunuz.</p> <br><br>
          <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST">
            <div class="form-group <?php (!empty($username_err))?'has_error':'';?>">
              <input placeholder="kullanıcı adını giriniz" type="text" name="username" id="username" class="form-control" value="<?php echo $username ?>">
            </div>

            <div class="form-group <?php (!empty($password_err))?'has_error':'';?>">
              <input placeholder="şifrenizi giriniz" type="password" name="password" id="password" class="form-control" value="<?php echo $password ?>">
            </div>

            <div class="form-group">
              <input type="submit" class="btn btn-block btn-outline-primary" value="Giriş yap">
            </div>
            <p>Hesabın yok mu? <a href="register.php">Kayıt ol</a>.</p>
          </form>
    </section>
  </main>
</body>
</html><?php
  // Oturumu yükle
  session_start();

  // Kullanıcının zaten oturum açmış olup olmadığını kontrol edin, evet ise onu karşılama sayfasına yönlendirin
  if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
    header("location: welcome.php");
    exit;
  }

  // konfigürasyon dosyası
  require_once "config/config.php";

  // Değişkenleri tanımlayın ve boş değerlerle başlatın
  // $username = $password = '';
  // $username_err = $password_err = '';

  // Gönderilen form verilerini işle
  if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Kullanıcı adının boş olup olmadığını kontrol edin
    if(empty(trim($_POST['username']))){
      $username_err = 'Please enter username.';
    } else{
      $username = trim($_POST['username']);
    }

    // şifre boş ise
    if(empty(trim($_POST['password']))){
      $password_err = 'Please enter your password.';
    } else{
      $password = trim($_POST['password']);
    }

    // geçerli durumlar
    if (empty($username_err) && empty($password_err)) {
      $sql = 'SELECT id, username, password FROM users WHERE username = ?';

      if ($stmt = $mysql_db->prepare($sql)) {

        $param_username = $username;

        $stmt->bind_param('s', $param_username);

        if ($stmt->execute()) {

          $stmt->store_result();

          if ($stmt->num_rows == 1) {

            $stmt->bind_result($id, $username, $hashed_password);

            if ($stmt->fetch()) {
              if (password_verify($password, $hashed_password)) {

                session_start();

                $_SESSION['loggedin'] = true;
                $_SESSION['id'] = $id;
                $_SESSION['username'] = $username;

		if ($username === 'Admin' || $username === 'admin') {
        		header('Location: admin.php');
    		}
		else if($username === 'Manager' || $username === 'manager'){
			header('Location: manager.php');
		}
		else {
        		header('Location: welcome.php');
    		}                

              } else {

                $password_err = 'Şifre geçersiz';
              }
            }
          } else {
            $username_err = "Kullanıcı adı yok";
          }
        } else {
          echo "Oops! Birşeyler ters gitti, tekrar deneyiniz.";
        }
        $stmt->close();
      }

      $mysql_db->close();
    }
  }
?>


