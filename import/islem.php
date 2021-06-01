<?php
require_once 'connect.php';

if (isset($_POST['register'])) {

    $user_surname = htmlspecialchars($_POST['user_name']);
    $user_lastname = htmlspecialchars($_POST['user_lastname']);
    $user_mail = htmlspecialchars($_POST['user_mail']);
    $user_pass = htmlspecialchars($_POST['user_pass']);
    $user_re_pass = htmlspecialchars($_POST['user_re_pass']);

    if ($user_re_pass == $user_pass) {

        $usersor = $db->prepare("SELECT * FROM user where user_mail=:mail");

        $usersor->execute(array(
            'mail' => $user_mail
        ));
        $kayit = $usersor->rowCount();


        if ($kayit == 0) {

            $user_pass = md5($user_re_pass);


            $userkaydet = $db->prepare("INSERT INTO user SET
                user_surname=:surname,
                user_lastname=:lastname,
			    user_mail=:mail,
			    user_pass=:pass,
                user_status=:statu
			    ");

            $insert = $userkaydet->execute(array(
                'surname' => $user_surname,
                'lastname' => $user_lastname,
                'mail' => $user_mail,
                'pass' => $user_pass,
                'statu' => 1
            ));
            if ($insert) {

                $_SESSION['user_mail'] = $user_mail;
                Header("Location:../index.php");
            } else {
                Header("Location:../login/index.php?ekle=no");
            }
        } else {
            header("Location:../login/index.php?mail=kayitli");
        }
    } else {
        header("Location:../login/index.php?pass=eslesmiyor");
    }
}



if (isset($_POST['login'])) {


    $user_mail = $_POST['user_mail'];
    $user_pass = md5($_POST['user_pass']);

    echo $user_mail;
    echo $user_pass;
    $usersor = $db->prepare("SELECT * FROM user WHERE user_mail=:mail AND user_pass=:pass AND user_status=:statu");
    $usersor->execute(array(
        'mail' => $user_mail,
        'pass' => $user_pass,
        'statu' => 1
    ));


    $usercek = $usersor->fetch(PDO::FETCH_ASSOC);

    $say = $usersor->rowCount();

    if ($say == 1) {
        $_SESSION['user_mail'] = $user_mail;

        header("Location:../index.php");
        exit;
    } else {
        header("Location:../login/login.php?kayitsiz=no");
    }
}


if (isset($_POST['siparis'])) {

    $mail = $_SESSION['user_mail'];
    $kullanicisor = $db->prepare("SELECT * FROM user WHERE user_mail=:user_mail");
    $kullanicisor->execute(array(
        'user_mail' => $mail
    ));
    $kullanicicek = $kullanicisor->fetch(PDO::FETCH_ASSOC);

    $userid = $kullanicicek["user_id"];
    $pr_id = htmlspecialchars($_POST['pr_id']);
    $ord_type = htmlspecialchars($_POST['ord_type']);
    $ord_damat = htmlspecialchars($_POST['ord_damat']);
    $ord_gelin = htmlspecialchars($_POST['ord_gelin']);
    $ord_aile = htmlspecialchars($_POST['ord_aile']);
    $ord_mani = htmlspecialchars($_POST['ord_mani']);
    $ord_tarih = htmlspecialchars($_POST['ord_tarih']);
    $ord_saat = htmlspecialchars($_POST['ord_saat']) . ":00";
    $ord_adres = htmlspecialchars($_POST['ord_adres']);

    $file = $_FILES['ord_file'];
    $size = $file["size"];
    $type = $file["type"];
    var_dump($size);
    var_dump($type);

    if ($size < 1048576) {
        echo "Boyut İyi";
        if ($type == 'application/vnd.ms-excel' || $type == 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet') {

            // Döküman adı rastgeleliştirme ve kaydetme.
            $uploads_dir = '../doc';
            @$tmp_name = $file["tmp_name"];
            @$name = $file["name"];
            
            $benzersizsayi1 = rand(20000, 32000);
            $benzersizsayi2 = rand(20000, 32000);
            $benzersizsayi3 = rand(20000, 32000);
            $benzersizsayi4 = rand(20000, 32000);

            $benzersizad = $benzersizsayi1 . $benzersizsayi2 . $benzersizsayi3 . $benzersizsayi4;
            $refdocyol = substr($uploads_dir, 3) . "/" . $benzersizad . $name;
            @move_uploaded_file($tmp_name, "$uploads_dir/$benzersizad$name");
            @move_uploaded_file($tmp_name, "$uploads_dir/$benzersizad$name");
            // Döküman adı rastgeleliştirme ve kaydetme sonu.

            $userkaydet = $db->prepare("INSERT INTO ord SET
                userid=:userid,
                pr_id=:pr_id,
		        ord_type=:ord_type,
                ord_damat=:ord_damat,
                ord_gelin=:ord_gelin,
		        ord_aile=:ord_aile,
		        ord_mani=:ord_mani,
                ord_tarih=:ord_tarih,
                ord_saat=:ord_saat,
                ord_adres=:ord_adres,
                ord_file=:ord_file
		        ");

            $insert = $userkaydet->execute(array(
                'userid' => $userid,
                'pr_id' => $pr_id,
                'ord_type' => $ord_type,
                'ord_damat' => $ord_damat,
                'ord_gelin' => $ord_gelin,
                'ord_aile' => $ord_aile,
                'ord_mani' => $ord_mani,
                'ord_tarih' => $ord_tarih,
                'ord_saat' => $ord_saat,
                'ord_adres' => $ord_adres,
                'ord_file' => $refdocyol
            ));

            if ($insert) {
                $user_mail = $_SESSION["user_mail"];
                $ordsor = $db->prepare("SELECT * FROM ord WHERE userid=:userid ORDER BY ord_id DESC");
                $ordsor->execute(array(
                    'userid' => $userid
                ));

                $ordcek = $ordsor->fetch(PDO::FETCH_ASSOC);
                $ord_id = $ordcek['ord_id'];

                
                Header("Location:../order.php?id=$ord_id");


            } else {
                Header("Location:../product.php?id=$pr_id&error=true");
            }
        } else {
            Header("Location:../product.php?id=$pr_id&error=format");
        }
    } else {
        Header("Location:../product.php?id=$pr_id&error=size");
    }  
}
