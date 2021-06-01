<?php
require_once 'import/connect.php';

if (isset($_SESSION['user_mail'])) {

    $mail = $_SESSION['user_mail'];
    $kullanicisor = $db->prepare("SELECT * FROM user WHERE user_mail=:user_mail");
    $kullanicisor->execute(array(
        'user_mail' => $mail
    ));
    $kullanicicek = $kullanicisor->fetch(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="tr">

  <head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="TemplateMo">
    <link href="https://fonts.googleapis.com/css?family=Roboto:100,300,400,500,700" rel="stylesheet">

    <title>Davetiye Cebinde | Online davetiye platformu.</title>

    <!-- Bootstrap core CSS -->
    <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <!-- Additional CSS Files -->
    <link rel="stylesheet" href="assets/css/fontawesome.css">
    <link rel="stylesheet" href="assets/css/templatemo-host-cloud.css">
    <link rel="stylesheet" href="assets/css/owl.css">

  </head>

  <body>

    <!-- ***** Preloader Start ***** -->
    <div id="preloader">
        <div class="jumper">
            <div></div>
            <div></div>
            <div></div>
        </div>
    </div>  
    <!-- ***** Preloader End ***** -->

    <!-- Header -->
    <header class="">
      <nav class="navbar navbar-expand-lg">
        <div class="container">
          <a class="navbar-brand" href="index.php"><h2>DAVETİYE <em>CEBİNDE</em></h2></a>
          <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
          </button>
          <div class="collapse navbar-collapse" id="navbarResponsive">
          </div>
          <div class="functional-buttons">
            <ul>
                <?php
                if (isset($_SESSION['user_mail'])) {
                ?>
                    <li><a href=""><?php echo $kullanicicek['user_surname']?> <?php echo $kullanicicek['user_lastname']; ?></a></li>
                    <li><a href="import/logout.php">ÇIKIŞ</a></li>
                <?php
                }else {
                ?>
                    <li><a href="login/login.php">GİRİŞ YAP</a></li>
                    <li><a href="login/index.php">KAYIT OL</a></li>
              <?php } ?>
            </ul>
          </div>
        </div>
      </nav>
    </header>
