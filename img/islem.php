<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST' && empty($_POST) && empty($_FILES) && $_SERVER['CONTENT_LENGTH'] > 0) {
	header("Location:../menu.php?resim=cogbuyug");
}
/**
 * @TODO 09/07/2020
 * @author mso
 * 
 * TRY CATCH VE ERROR HANDLING
 */
require_once '../../inc/baglan.php';
require_once 'fonksiyon.php';

if (isset($_SESSION['firma_id'])) {
	$firma_id = $_SESSION['firma_id'];
}

if (isset($_POST['kategoriekle'])) {

	$kategori_ad = htmlspecialchars($_POST['kategori_ad']);
	$kategori_sira = htmlspecialchars($_POST['kategori_sira']);

	if (!preg_match("/^[0-9]+$/", $_POST['ana_kategori']))
		$_POST['ana_kategori'] = null;

	$kaydet = $db->prepare("INSERT INTO kategori SET
		firma_id=:firma_id,
		kategori_ad=:kategori_ad,
		kategori_sira=:kategori_sira,
		ana_kategori_id=:ana_kategori_id
		");
	$insert = $kaydet->execute(array(
		'firma_id' => $firma_id,
		'kategori_ad' => $kategori_ad,
		'kategori_sira' => $kategori_sira,
		'ana_kategori_id' => $_POST['ana_kategori']
	));

	if ($insert) {
		Header("Location:../kategori.php?ekle=ok");
	} else {
		Header("Location:../kategori.php?ekle=no");
	}
}

if (isset($_POST['anakategoriekle'])) {
	$ana_kategori_ad = htmlspecialchars($_POST['ana_kategori_ad']);
	$ana_kategori_sira = htmlspecialchars($_POST['ana_kategori_sira']);

	$kaydet = $db->prepare("INSERT INTO ana_kategori SET
		firma_id=:firma_id,
		ad=:ad,
		sira=:sira
		");
	$insert = $kaydet->execute(array(
		'firma_id' => $firma_id,
		'ad' => $ana_kategori_ad,
		'sira' => $ana_kategori_sira
	));

	if ($insert) {
		Header("Location:../ana-kategori.php?ekle=ok");
	} else {
		Header("Location:../ana-kategori.php?ekle=no");
	}
}

if (isset($_GET['anakategoridurum'])) {

	$id = $_GET['id'];
	if ($_GET['anakategoridurum'] == 0) {
		try {

			$durumguncelle = $db->prepare(
				"UPDATE ana_kategori SET
				durum=:durum
				WHERE firma_id=:firma_id and id=:id
				"
			);

			$oldu = $durumguncelle->execute(array(
				'durum' => 1,
				'firma_id' => $firma_id,
				'id' => $id
			));

			header("Location:../ana-kategori.php?durum=ok");
		} catch (Exception $e) {
			header("Location:../ana-kategori.php?durum=no");
		}
	} else {

		try {
			$durumguncelle = $db->prepare(
				"UPDATE ana_kategori SET
				durum=:durum
				WHERE firma_id=:firma_id and id=:id
				"
			);

			$oldu = $durumguncelle->execute(array(
				'durum' => 0,
				'firma_id' => $firma_id,
				'id' => $id
			));
			header("Location:../ana-kategori.php?durum=ok");
		} catch (Exception $e) {
			header("Location:../ana-kategori.php?durum=no");
		}
	}
}

if (isset($_GET['anakategorisil']) == "ok") {
	try {
		$id = $_GET['id'];
		$altKategoriSor = $db->prepare("SELECT * FROM kategori WHERE ana_kategori_id=:ana_kategori_id");
		$altKategoriSor->execute(array(
			'ana_kategori_id' => $id
		));

		$altKategoriCek = $altKategoriSor->fetchAll(PDO::FETCH_ASSOC);
		$say = count($altKategoriCek);
	} catch (Exception $e) {
		header("location:../ana-kategori-duzenle.php?id=$id&error=hata");
	}

	if (!$say == 0)
		header("location:../ana-kategori-duzenle.php?id=$id&error=dolu");
	else {
		try {
			$sil = $db->prepare("DELETE from ana_kategori where id=:id and firma_id=:firma_id");
			$kontrol = $sil->execute(array(
				'id' => $id,
				'firma_id' => $firma_id
			));
			header("location:../ana-kategori.php?sil=ok");
		} catch (Exception $e) {
			header("location:../ana-kategori.php?sil=no");
		}
	}
}

if (isset($_GET['kategorisil']) == "ok") {

	$id = $_GET['id'];
	$urunsor = $db->prepare("SELECT * FROM urun WHERE  kategori_id=:kategori_id");
	$urunsor->execute(array(
		'kategori_id' => $id
	));
	while ($uruncek = $urunsor->fetch(PDO::FETCH_ASSOC)) {
		$say++;
	}

	if (!$say == 0) {
		header("location:../kategori-duzenle.php?id=$id&error=dolu");
	} else {

		$sil = $db->prepare("DELETE from kategori where kategori_id=:id and firma_id=:firma_id");
		$kontrol = $sil->execute(array(
			'id' => $id,
			'firma_id' => $firma_id
		));

		if ($kontrol) {

			header("location:../kategori.php?sil=ok");
		} else {

			header("location:../kategori.php?sil=no");
		}
	}
}

if (isset($_GET['urunsil']) == "ok") {

	$id = $_GET['id'];
	$urunsor = $db->prepare("SELECT * FROM urun WHERE urun_id=:urun_id");
	$urunsor->execute(array(
		'urun_id' => $id
	));
	$uruncek = $urunsor->fetch(PDO::FETCH_ASSOC);

	$opsiyonsor = $db->prepare("SELECT * FROM opsiyon WHERE urun_id = :urun_id");
	$opsiyonsor->execute(array(
		"urun_id" => $id
	));
	$opsiyoncek = $opsiyonsor->fetchAll(PDO::FETCH_ASSOC);

	if (count($opsiyoncek) > 0) {
		foreach ($opsiyoncek as $key => $value) {
			$opsiyonsil = $db->prepare("DELETE from opsiyon where id=:id");
			$opsiyonkontrol = $opsiyonsil->execute(array(
				'id' => $value["id"]
			));
		}
	}

	$sil = $db->prepare("DELETE from urun where urun_id=:id and firma_id=:firma_id");
	$kontrol = $sil->execute(array(
		'id' => $id,
		'firma_id' => $firma_id
	));

	if ($kontrol) {
		$resimsilunlink = $uruncek['urun_resimyol'];
		if ($resimsilunlink != "img/urun/defaultfood.jpg") {
			unlink("../../$resimsilunlink");
		}
		header("location:../menu.php?sil=ok");
	} else {

		header("location:../menu.php?sil=no");
	}
}


if (isset($_POST['kategoriduzenle'])) {
	if (@$_POST['kategori_durum'] == 1) {
		$kategori_durum = 1;
	} else {
		$kategori_durum = 0;
	}

	if (!preg_match("/^[0-9]+$/", $_POST['ana_kategori']))
		$_POST['ana_kategori'] = null;

	$kategorikaydet = $db->prepare("UPDATE kategori SET
		kategori_ad=:kategori_ad,
		kategori_sira=:kategori_sira,
		kategori_durum=:kategori_durum,
		ana_kategori_id=:ana_kategori_id
		WHERE kategori_id={$_POST['kategori_id']}");

	$update = $kategorikaydet->execute(array(
		'kategori_ad' => $_POST['kategori_ad'],
		'kategori_sira' => $_POST['kategori_sira'],
		'kategori_durum' => $kategori_durum,
		'ana_kategori_id' => $_POST['ana_kategori']
	));

	if ($update) {
		header("Location:../kategori.php?guncelle=ok");
	} else {
		header("Location:../kategori.php?guncelle=no");
	}
}

if (isset($_POST['anakategoriduzenle'])) {
	if (@$_POST['durum'] == 1) {
		$kategori_durum = 1;
	} else {
		$kategori_durum = 0;
	}

	$kategorikaydet = $db->prepare("UPDATE ana_kategori SET
		ad=:kategori_ad,
		sira=:kategori_sira,
		durum=:kategori_durum
		WHERE id={$_POST['kategori_id']}");

	$update = $kategorikaydet->execute(array(
		'kategori_ad' => $_POST['kategori_ad'],
		'kategori_sira' => $_POST['kategori_sira'],
		'kategori_durum' => $kategori_durum
	));
	if ($update) {
		header("Location:../ana-kategori.php?guncelle=ok");
	} else {
		header("Location:../ana-kategori.php?guncelle=no");
	}
}

if (isset($_POST['urunkaydet'])) {

	if (@$_FILES['urun_resimyol']["size"] < 1) {

		$refimgyol = "img/urun/defaultfood.jpg";
		$kaydet = $db->prepare("INSERT INTO urun SET
				firma_id=:firma_id,
				kategori_id=:kategori_id,
				urun_resimyol=:urun_resimyol,
				urun_ad=:urun_ad,
				urun_detay=:urun_detay,
				urun_fiyat=:urun_fiyat,
				urun_sira=:urun_sira


				");
		$insert = $kaydet->execute(array(
			'firma_id' => $firma_id,
			'kategori_id' => $_POST['kategori_id'],
			'urun_resimyol' => $refimgyol,
			'urun_ad' => $_POST['urun_ad'],
			'urun_detay' => $_POST['urun_detay'],
			'urun_fiyat' => $_POST['urun_fiyat'],
			'urun_sira' => $_POST['urun_sira']
		));

		if ($insert) {
			Header("Location:../urun-ekle.php?ekle=ok");
		} else {
			Header("Location:../urun-ekle.php?ekle=ok");
		}
	} else if (@$_FILES['urun_resimyol']["size"] > 1) {

		$size = $_FILES['urun_resimyol']["size"];
		$type = $_FILES['urun_resimyol']["type"];

		if ($size < 1048576) {
			if ($type == 'image/jpeg' || $type == 'image/jpg' || $type == 'image/png' || $type == 'image/gif') {


				$uploads_dir = '../../img/urun';
				@$tmp_name = $_FILES['urun_resimyol']["tmp_name"];
				@$name = $_FILES['urun_resimyol']["name"];
				$benzersizsayi1 = rand(20000, 32000);
				$benzersizsayi2 = rand(20000, 32000);
				$benzersizsayi3 = rand(20000, 32000);
				$benzersizsayi4 = rand(20000, 32000);
				$benzersizad = $benzersizsayi1 . $benzersizsayi2 . $benzersizsayi3 . $benzersizsayi4;
				$refimgyol = substr($uploads_dir, 6) . "/" . $benzersizad . $name;
				@move_uploaded_file($tmp_name, "$uploads_dir/$benzersizad$name");

				$kaydet = $db->prepare("INSERT INTO urun SET
				firma_id=:firma_id,
				kategori_id=:kategori_id,
				urun_resimyol=:urun_resimyol,
				urun_ad=:urun_ad,
				urun_detay=:urun_detay,
				urun_fiyat=:urun_fiyat,
				urun_sira=:urun_sira


				");
				$insert = $kaydet->execute(array(
					'firma_id' => $firma_id,
					'kategori_id' => $_POST['kategori_id'],
					'urun_resimyol' => $refimgyol,
					'urun_ad' => $_POST['urun_ad'],
					'urun_detay' => $_POST['urun_detay'],
					'urun_fiyat' => $_POST['urun_fiyat'],
					'urun_sira' => $_POST['urun_sira']
				));

				if ($insert) {
					Header("Location:../urun-ekle.php?ekle=ok");
				} else {
					Header("Location:../urun-ekle.php?ekle=ok");
				}
			} else {
				Header("Location:../urun-ekle.php?tur=uyumsuz");
			}
		} else {
			Header("Location:../urun-ekle.php?boyut=buyuk");
		}
	}
}

if (isset($_POST['urunguncelle'])) {


	$kategorikaydet = $db->prepare("UPDATE urun SET
		kategori_id=:kategori_id,
		urun_ad=:urun_ad,
		urun_detay=:urun_detay,
		urun_fiyat=:urun_fiyat,
		urun_sira=:urun_sira
		WHERE urun_id={$_POST['urun_id']}");

	$update = $kategorikaydet->execute(array(
		'kategori_id' => $_POST['kategori_id'],
		'urun_ad' => $_POST['urun_ad'],
		'urun_detay' => $_POST['urun_detay'],
		'urun_fiyat' => $_POST['urun_fiyat'],
		'urun_sira' => $_POST['urun_sira']
	));


	if ($update) {
		$postData = $_POST;

		unset($postData["kategori_id"]);
		unset($postData["urun_ad"]);
		unset($postData["urun_detay"]);
		unset($postData["urun_fiyat"]);
		unset($postData["urun_sira"]);

		$opsiyonlar = array();
		foreach ($postData as $key1 => $value1) {
			if (preg_match("/^(opsiyon-fiyat-[0-9]+)$/", $key1)) {
				$expId = explode("-", $key1);
				$id = $expId[2];
				$opsiyonFiyat = $value1;
				foreach ($postData as $key2 => $value2) {
					if (preg_match("/^(opsiyon-$id+)$/", $key2)) {
						$opsiyon = $value2;
					}
				}

				$opsyion = array(
					"id" => $id,
					"fiyat" => $opsiyonFiyat,
					"opsiyon" => $opsiyon
				);

				array_push($opsiyonlar, $opsyion);
			}
		}

		$opsiyonKaydetErrorCount = 0;

		foreach ($opsiyonlar as $key => $value) {
			try {
				$opsiyonkaydet = $db->prepare(
					"UPDATE opsiyon SET opsiyon = :opsiyon, fiyat = :fiyat WHERE id = :id"
				);

				$update = $opsiyonkaydet->execute(array(
					"opsiyon" => $value["opsiyon"],
					"fiyat" => $value["fiyat"],
					"id" => $value["id"]
				));
			} catch (Exception $e) {
				$opsiyonKaydetErrorCount++;
			}
		}

		if ($opsiyonKaydetErrorCount == count($opsiyonlar))
			header("Location:../menu.php?opsyion-guncelle=no");

		header("location:../urun-duzenle.php?id=" . $_POST['urun_id'] . "?durum=ok");
	} else {
		header("location:../urun-duzenle.php?id=" . $_POST['urun_id'] . "?durum=no");
	}
}

if (isset($_POST['fotoguncelle'])) {
	if (!isset($_FILES)) header("Location:../menu.php?resim=no");

	try {
		$size = $_FILES['urun_resimyol']["size"];
		$type = $_FILES['urun_resimyol']["type"];

		if ($size > 7999999) {
			header("Location:../menu.php?tur=uyumsuz");
		}
	} catch (Exception $e) {
		header("Location:../menu.php?resim=no");
	}

	if ($type == 'image/jpeg' || $type == 'image/jpg' || $type == 'image/png' || $type == 'image/gif') {
		$uploads_dir = '../../img/urun';
		@$tmp_name = $_FILES['urun_resimyol']["tmp_name"];
		@$name = $_FILES['urun_resimyol']["name"];
		$fileName = pathinfo($name, PATHINFO_FILENAME);

		$benzersizsayi1 = rand(20000, 32000);
		$benzersizsayi2 = rand(20000, 32000);
		$benzersizsayi3 = rand(20000, 32000);
		$benzersizsayi4 = rand(20000, 32000);
		$benzersizad = $benzersizsayi1 . $benzersizsayi2 . $benzersizsayi3 . $benzersizsayi4;

		$refimgyol = substr($uploads_dir, 6) . "/" . $benzersizad . $name;
		$refuploadsdir = substr($uploads_dir, 6);
		$realPath = $uploads_dir . "/" . $benzersizad . $name;

		if ($size <= 2000000) {
			@move_uploaded_file($tmp_name, "$uploads_dir/$benzersizad$name");
			@move_uploaded_file($tmp_name, "$uploads_dir/$benzersizad$name");

			$duzenle = $db->prepare("UPDATE urun SET
			urun_resimyol=:urun_resimyol
			WHERE urun_id={$_POST['urun_id']}
			");

			$update = $duzenle->execute(array(
				'urun_resimyol' => $uploads_dir . "/" . $benzersizad . $name
			));

			try {
				$resimsilunlink = $_POST['eski_resim'];
				if ($resimsilunlink != "img/urun/defaultfood.jpg") {
					unlink("../../$resimsilunlink");
				}
			} catch (Exception $e) {
				header("Location:../menu.php?resim=no");
			}
		} elseif ($size > 2000000 && $size <= 8000000) {
			$i = 0;
			try {
				move_uploaded_file($tmp_name, "$uploads_dir/$benzersizad$name");
				move_uploaded_file($tmp_name, "$uploads_dir/$benzersizad$name");
			} catch (Exception $e) {
				header("Location:../menu.php?resim=no");
			}
			if ($type != 'image/gif') {
				$compressionQuality = 50;
				try {
					$imagick = new \Imagick($realPath);
					$imagick->setImageFormat("jpeg");
					$imagick->setImageCompressionQuality($compressionQuality);
					$imagick->writeImage($uploads_dir . "/" . $benzersizad . $fileName . ".jpeg");

					$image = new Imagick($uploads_dir . "/" . $benzersizad . $fileName . ".jpeg");
					while ($image->getImageLength() > 1500000) {
						try {
							$compressionQuality -= 20;
							$image->setImageCompression(Imagick::COMPRESSION_JPEG);
							$image->setImageCompressionQuality($compressionQuality);
							$image->writeImage($uploads_dir . "/" . $benzersizad . $fileName . ".jpeg");
							$image = new Imagick($uploads_dir . "/" . $benzersizad . $fileName . ".jpeg");
						} catch (Exception $e) {
							header("Location:../menu.php?resim=no");
						}
					}
				} catch (Exception $e) {
					header("Location:../menu.php?resim=no");
				}
			} else {
				$compressionQuality = 100;
				try {
					$imagick = new \Imagick($realPath);
					$format = $imagick->getImageFormat();
					if ($format == 'GIF') {
						$imagick = $imagick->coalesceImages();
						do {
							$imagick->setImageCompressionQuality($compressionQuality);
						} while ($imagick->nextImage());
						$imagick = $imagick->deconstructImages();
						$imagick->writeImages($uploads_dir . "/" . $benzersizad . $fileName . ".gif", true);
					}
				} catch (Exception $e) {
					header("Location:../menu.php?resim=no");
				}
				$image = new Imagick($uploads_dir . "/" . $benzersizad . $fileName . ".gif");
				while ($image->getImageLength() > 1500000) {
					$compressionQuality -= 8;
					try {
						$image = new Imagick($uploads_dir . "/" . $benzersizad . $fileName . ".gif");
						$format = $image->getImageFormat();
						if ($format == 'GIF') {
							$image = $image->coalesceImages();
							do {
								$image->setImageCompressionQuality($compressionQuality);
							} while ($image->nextImage());
							$image = $image->deconstructImages();
							$image->writeImages($uploads_dir . "/" . $benzersizad . $fileName . ".gif", true);
						}
					} catch (Exception $e) {
						header("Location:../menu.php?resim=no");
					}
				}
			}

			$duzenle = $db->prepare("UPDATE urun SET
			urun_resimyol=:urun_resimyol
			WHERE urun_id={$_POST['urun_id']}
			");

			$update = $duzenle->execute(array(
				'urun_resimyol' => $refuploadsdir . "/" . $benzersizad . $fileName . ".jpeg"
			));

			try {
				unlink($uploads_dir . "/" . $benzersizad . $name);
			} catch (Exception $e) {
				header("Location:../menu.php?resim=no");
			}
		} else {
			header("Location:../menu.php?tur=uyumsuz");
		}

		if ($update) {
			$resimsilunlink = $_POST['eski_resim'];
			if ($resimsilunlink != "img/urun/defaultfood.jpg") {
				unlink("../../$resimsilunlink");
			}
			header("Location:../menu.php?resim=ok");
		} else {
			header("Location:../menu.php?resim=no");
		}
	}
}

if (isset($_POST['firmaduzenle'])) {

	$firma_adres = htmlspecialchars($_POST['firma_adres']);
	$firma_ilce = htmlspecialchars($_POST['firma_ilce']);
	$firma_il = htmlspecialchars($_POST['firma_il']);
	$firma_ulke = htmlspecialchars($_POST['firma_ulke']);
	$firma_dil = htmlspecialchars($_POST['firma_dil']);

	if ($_FILES['firma_logo']["size"] == 0) {
		$bilgiekle = $db->prepare("UPDATE firma SET

		firma_adres=:firma_adres,
		firma_ilce=:firma_ilce,
		firma_il=:firma_il,
		firma_ulke=:firma_ulke,
		firma_dil=:firma_dil
		WHERE firma_id=:firma_id
		");

		$oldu = $bilgiekle->execute(array(
			'firma_id' => $firma_id,
			'firma_adres' => $firma_adres,
			'firma_ilce' => $firma_ilce,
			'firma_il' => $firma_il,
			'firma_ulke' => $firma_ulke,
			'firma_dil' => $firma_dil
		));

		if ($oldu) {
			header("Location:../profile.php?guncelle=ok");
		} else {
			header("Location:../profile.php?guncelle=none");
		}
	} else {

		$size = $_FILES['firma_logo']["size"];
		$type = $_FILES['firma_logo']["type"];

		if ($size < 1048576) {
			if ($type == 'image/jpeg' || $type == 'image/jpg' || $type == 'image/png') {

				$resimsilunlink = $_POST['eski_resim'];

				$uploads_dir = '../../img/firma';
				@$tmp_name = $_FILES['firma_logo']["tmp_name"];
				@$name = $_FILES['firma_logo']["name"];
				$benzersizsayi1 = rand(20000, 32000);
				$benzersizsayi2 = rand(20000, 32000);
				$benzersizsayi3 = rand(20000, 32000);
				$benzersizsayi4 = rand(20000, 32000);
				$benzersizad = $benzersizsayi1 . $benzersizsayi2 . $benzersizsayi3 . $benzersizsayi4;
				$refimgyol = substr($uploads_dir, 6) . "/" . $benzersizad . $name;
				@move_uploaded_file($tmp_name, "$uploads_dir/$benzersizad$name");
				@move_uploaded_file($tmp_name, "$uploads_dir/$benzersizad$name");


				$bilgiekle = $db->prepare("UPDATE firma SET
					firma_logo=:firma_logo,
					firma_adres=:firma_adres,
					firma_ilce=:firma_ilce,
					firma_il=:firma_il,
					firma_ulke=:firma_ulke,
					firma_dil=:firma_dil
					WHERE firma_id=:firma_id
				");

				$oldu = $bilgiekle->execute(array(
					'firma_logo' => $refimgyol,
					'firma_id' => $firma_id,
					'firma_adres' => $firma_adres,
					'firma_ilce' => $firma_ilce,
					'firma_il' => $firma_il,
					'firma_ulke' => $firma_ulke,
					'firma_dil' => $firma_dil

				));

				if ($oldu) {
					unlink("../../$resimsilunlink");
					header("Location:../profile-setting.php?guncelle=ok");
				} else {
					header("Location:../profile-setting.php?resim=no");
				}
			} else {
				Header("Location:../profile-setting.php?tur=uyumsuz");
			}
		} else {
			Header("Location:../profile-setting.php?boyut=buyuk");
		}
	}
}



if (isset($_POST['bilgiekle'])) {

	$firma_ad = htmlspecialchars($_POST['firma_ad']);
	$firma_yetkili_ad = htmlspecialchars($_POST['firma_yetkili_ad']);
	$firma_yetkili_soyad = htmlspecialchars($_POST['firma_yetkili_soyad']);
	$firma_yetkili_kkno = htmlspecialchars($_POST['firma_yetkili_kkno']);
	$firma_yetkili_kkay = htmlspecialchars($_POST['firma_yetkili_kkay']);
	$firma_yetkili_kkyil = htmlspecialchars($_POST['firma_yetkili_kkyil']);
	$firma_yetkili_kkcvv = htmlspecialchars($_POST['firma_yetkili_kkcvv']);
	$firma_adres = htmlspecialchars($_POST['firma_adres']);
	$firma_ilce = htmlspecialchars($_POST['firma_ilce']);
	$firma_il = htmlspecialchars($_POST['firma_il']);
	$firma_ulke = htmlspecialchars($_POST['firma_ulke']);
	$firma_dil = htmlspecialchars($_POST['firma_dil']);

	if ($_FILES['firma_logo']["size"] == 0) {

		$bilgiekle = $db->prepare("UPDATE firma SET
			firma_ad=:firma_ad,
			firma_yetkili_ad=:firma_yetkili_ad,
			firma_yetkili_soyad=:firma_yetkili_soyad,
			firma_yetkili_kkno=:firma_yetkili_kkno,
			firma_yetkili_kkay=:firma_yetkili_kkay,
			firma_yetkili_kkyil=:firma_yetkili_kkyil,
			firma_yetkili_kkcvv=:firma_yetkili_kkcvv,
			firma_adres=:firma_adres,
			firma_ilce=:firma_ilce,
			firma_il=:firma_il,
			firma_ulke=:firma_ulke,
			firma_dil=:firma_dil
			WHERE firma_id=:firma_id
			");

		$oldu = $bilgiekle->execute(array(
			'firma_id' => $firma_id,
			'firma_ad' => $firma_ad,
			'firma_yetkili_ad' => $firma_yetkili_ad,
			'firma_yetkili_soyad' => $firma_yetkili_soyad,
			'firma_yetkili_kkno' => $firma_yetkili_kkno,
			'firma_yetkili_kkay' => $firma_yetkili_kkay,
			'firma_yetkili_kkyil' => $firma_yetkili_kkyil,
			'firma_yetkili_kkcvv' => $firma_yetkili_kkcvv,
			'firma_adres' => $firma_adres,
			'firma_ilce' => $firma_ilce,
			'firma_il' => $firma_il,
			'firma_ulke' => $firma_ulke,
			'firma_dil' => $firma_dil
		));

		if ($oldu) {
			header("Location:../index.php?guncelle=ok");
		} else {
			header("Location:../bilgiekle.php?guncelle=no");
		}
	} else {

		$size = $_FILES['firma_logo']["size"];
		$type = $_FILES['firma_logo']["type"];

		if ($size < 1048576) {
			if ($type == 'image/jpeg' || $type == 'image/jpg' || $type == 'image/png') {


				$uploads_dir = '../../img/firma';
				@$tmp_name = $_FILES['firma_logo']["tmp_name"];
				@$name = $_FILES['firma_logo']["name"];
				$benzersizsayi1 = rand(20000, 32000);
				$benzersizsayi2 = rand(20000, 32000);
				$benzersizsayi3 = rand(20000, 32000);
				$benzersizsayi4 = rand(20000, 32000);
				$benzersizad = $benzersizsayi1 . $benzersizsayi2 . $benzersizsayi3 . $benzersizsayi4;
				$refimgyol = substr($uploads_dir, 6) . "/" . $benzersizad . $name;
				@move_uploaded_file($tmp_name, "$uploads_dir/$benzersizad$name");
				@move_uploaded_file($tmp_name, "$uploads_dir/$benzersizad$name");

				$bilgiekle = $db->prepare("UPDATE firma SET
					firma_ad=:firma_ad,
					firma_logo=:firma_logo,
					firma_yetkili_ad=:firma_yetkili_ad,
					firma_yetkili_soyad=:firma_yetkili_soyad,
					firma_yetkili_kkno=:firma_yetkili_kkno,
					firma_yetkili_kkay=:firma_yetkili_kkay,
					firma_yetkili_kkyil=:firma_yetkili_kkyil,
					firma_yetkili_kkcvv=:firma_yetkili_kkcvv,
					firma_adres=:firma_adres,
					firma_ilce=:firma_ilce,
					firma_il=:firma_il,
					firma_ulke=:firma_ulke,
					firma_dil=:firma_dil
					WHERE firma_id=:firma_id
				");

				$update = $bilgiekle->execute(array(
					'firma_id' => $firma_id,
					'firma_ad' => $firma_ad,
					'firma_logo' => $refimgyol,
					'firma_yetkili_ad' => $firma_yetkili_ad,
					'firma_yetkili_soyad' => $firma_yetkili_soyad,
					'firma_yetkili_kkno' => $firma_yetkili_kkno,
					'firma_yetkili_kkay' => $firma_yetkili_kkay,
					'firma_yetkili_kkyil' => $firma_yetkili_kkyil,
					'firma_yetkili_kkcvv' => $firma_yetkili_kkcvv,
					'firma_adres' => $firma_adres,
					'firma_ilce' => $firma_ilce,
					'firma_il' => $firma_il,
					'firma_ulke' => $firma_ulke,
					'firma_dil' => $firma_dil
				));

				if ($update) {
					header("Location:../bilgiekle.php?guncelle=ok");
				} else {
					header("Location:../bilgiekle.php?resim=no");
				}
			} else {
				Header("Location:../bilgiekle.php?tur=uyumsuz");
			}
		} else {
			Header("Location:../bilgiekle.php?boyut=buyuk");
		}
	}
}
if (isset($_POST["destekmesaj"])) {
	$recaptche_kodu = $_POST['recaptche_kodu'];
	define("SECRETKEY", "6Ldz66YZAAAAALiACE8r4RSlPlKT6GNHuy4SW-aa");
	$kontrol = botKontrol($recaptche_kodu);
	if ($kontrol->success == true && $kontrol->score > 0.4) {

		$mesaj = $_POST["destek_mesaj"];
		$konu = $_POST["destek_konu"];

		$konu = trim($konu);
		$konu = htmlspecialchars($konu);
		$konu = stripslashes($konu);
		$konu = filter_var($konu, FILTER_SANITIZE_SPECIAL_CHARS);

		$mesaj = trim($mesaj);
		$mesaj = htmlspecialchars($mesaj);
		$mesaj = stripslashes($mesaj);
		$mesaj = filter_var($mesaj, FILTER_SANITIZE_SPECIAL_CHARS);

		try {
			$mailSor = $db->prepare("SELECT * FROM firma WHERE firma_id = :id");
			$mailSor->execute(array(
				"id" => $firma_id
			));
			$mailCek = $mailSor->fetch(PDO::FETCH_ASSOC);
		} catch (\Throwable $th) {
			//throw $th;
		}

		require_once "../../inc/mailer.php";
		$mailer = new Mailer();
		$destekDurum = $mailer->destekMailiGonder("resta run", "destek@resta.run", $konu, $mesaj);
		$iletildiDurum = $mailer->iletildiMailiGonder($mailCek["firma_yetkili_ad"] . " " . $mailCek["firma_yetkili_soyad"], $mailCek["firma_mail"], $konu);
		if ($destekDurum && $iletildiDurum) {
			header("Location:../destek.php?durum=ok");
		} else {
			header("Location:../destek.php?durum=hata");
		}
	} else {
		session_destroy();
		header("Location:../../index?bot=uzakdur");
	}
}

if (isset($_POST['kartbilgi'])) {

	if ($_POST['firma_yetkili_kkay'] == "Seçiniz...") {
		header("Location:../cart-setting.php?ay=bos");
	} else {
		if ($_POST['firma_yetkili_kkyil'] == "Seçiniz...") {
			header("Location:../cart-setting.php?yil=bos");
		} else {


			$firma_yetkili_ad = htmlspecialchars($_POST['firma_yetkili_ad']);
			$firma_yetkili_soyad = htmlspecialchars($_POST['firma_yetkili_soyad']);
			$firma_yetkili_kkno = htmlspecialchars($_POST['firma_yetkili_kkno']);
			$firma_yetkili_kkay = htmlspecialchars($_POST['firma_yetkili_kkay']);
			$firma_yetkili_kkyil = htmlspecialchars($_POST['firma_yetkili_kkyil']);
			$firma_yetkili_kkcvv = htmlspecialchars($_POST['firma_yetkili_kkcvv']);


			$bilgiekle = $db->prepare("UPDATE firma SET
			firma_yetkili_ad=:firma_yetkili_ad,
			firma_yetkili_soyad=:firma_yetkili_soyad,
			firma_yetkili_kkno=:firma_yetkili_kkno,
			firma_yetkili_kkay=:firma_yetkili_kkay,
			firma_yetkili_kkyil=:firma_yetkili_kkyil,
			firma_yetkili_kkcvv=:firma_yetkili_kkcvv
			WHERE firma_id=:firma_id
			");

			$oldu = $bilgiekle->execute(array(
				'firma_id' => $firma_id,
				'firma_yetkili_ad' => $firma_yetkili_ad,
				'firma_yetkili_soyad' => $firma_yetkili_soyad,
				'firma_yetkili_kkno' => $firma_yetkili_kkno,
				'firma_yetkili_kkay' => $firma_yetkili_kkay,
				'firma_yetkili_kkyil' => $firma_yetkili_kkyil,
				'firma_yetkili_kkcvv' => $firma_yetkili_kkcvv,
			));

			if ($oldu) {
				header("Location:../cart-setting.php?guncelle=ok");
			} else {
				header("Location:../cart-setting.php?guncelle=no");
			}
		}
	}
}

if (isset($_POST['sifreduzenle'])) {
	if (empty($_POST['firma_pass']) or empty($_POST['pass1'] or empty($_POST['pass2']))) {
		$data['status'] = "error";
		$data['message'] = "Tüm alanları doldurduğunuzdan emin olun";
		echo json_encode($data);
		exit;
	}
}

if (isset($_GET['udurum'])) {

	$urun_id = $_GET['id'];
	if ($_GET['udurum'] == 0) {
		$durumguncelle = $db->prepare("UPDATE urun SET
			urun_durum=:urun_durum
			WHERE firma_id=:firma_id and urun_id=:urun_id
			");

		$oldu = $durumguncelle->execute(array(

			'urun_durum' => 1,
			'firma_id' => $firma_id,
			'urun_id' => $urun_id
		));

		if ($oldu) {
			header("Location:../menu.php?durum=ok");
		} else {
			header("Location:../menu.php?durum=no");
		}
	} else {
		$durumguncelle = $db->prepare("UPDATE urun SET
			urun_durum=:urun_durum
			WHERE firma_id=:firma_id and urun_id=:urun_id
			");

		$oldu = $durumguncelle->execute(array(

			'urun_durum' => 0,
			'firma_id' => $firma_id,
			'urun_id' => $urun_id
		));

		if ($oldu) {
			header("Location:../menu.php?durum=ok#$urun_id");
		} else {
			header("Location:../menu.php?durum=no");
		}
	}
}

if (isset($_GET['kdurum'])) {

	$kategori_id = $_GET['id'];

	$retVal = ($_GET["kdurum"]) ? 0 : 1; // durum 1 ise 0 döndür, 0 ise 1 döndür
	// işlem yapılan kategorilere bağlı ürünleri pasif/aktif yapar
	try {
		$urunDurumGuncelle = $db->prepare("UPDATE urun SET
		urun_durum=:urun_durum
		WHERE firma_id=:firma_id AND kategori_id=:kategori_id
		");

		$olduUrun = $urunDurumGuncelle->execute(array(
			'urun_durum' => $retVal,
			'firma_id' => $firma_id,
			'kategori_id' => $kategori_id
		));
		if (!$olduUrun) header("Location:../kategori.php?durum=no"); // fallback
	} catch (Exception $e) {
		header("Location:../kategori.php?durum=no"); // fallback
	}

	// işlem yapılan kategoriyi pasif/aktif yapar
	try {
		$durumguncelle = $db->prepare("UPDATE kategori SET
				kategori_durum=:kategori_durum
				WHERE firma_id=:firma_id and kategori_id=:kategori_id
				");

		$oldu = $durumguncelle->execute(array(

			'kategori_durum' => $retVal,
			'firma_id' => $firma_id,
			'kategori_id' => $kategori_id
		));
		if ($oldu) {
			header("Location:../kategori.php?durum=ok");
		} else {
			header("Location:../kategori.php?durum=no");
		}
	} catch (Exception $e) {
		header("Location:../kategori.php?durum=no"); // fallback
	}

	/*
	if ($_GET['kdurum'] == 0) {
		try {
			$durumguncelle = $db->prepare("UPDATE kategori SET
				kategori_durum=:kategori_durum
				WHERE firma_id=:firma_id and kategori_id=:kategori_id
				");

			$oldu = $durumguncelle->execute(array(

				'kategori_durum' => 1,
				'firma_id' => $firma_id,
				'kategori_id' => $kategori_id
			));
			if ($oldu) {
				header("Location:../kategori.php?durum=ok");
			} else {
				header("Location:../kategori.php?durum=no");
			}
		} catch (\Throwable $th) {
			header("Location:../kategori.php?durum=no");
		}
	} else {
		try {
			$durumguncelle = $db->prepare("UPDATE kategori SET
				kategori_durum=:kategori_durum
				WHERE firma_id=:firma_id and kategori_id=:kategori_id
				");

			$oldu = $durumguncelle->execute(array(

				'kategori_durum' => 0,
				'firma_id' => $firma_id,
				'kategori_id' => $kategori_id
			));

			if ($oldu) {
				header("Location:../kategori.php?durum=ok");
			} else {
				header("Location:../kategori.php?durum=no");
			}
		} catch (\Throwable $th) {
			header("Location:../kategori.php?durum=no");
		}
	}
	*/
}


if (isset($_POST['sifreduzenle'])) {

	$firma_pass = strip_tags($_POST['firma_pass']);
	$pass1 = strip_tags($_POST['pass1']);
	$pass2 = strip_tags($_POST['pass2']);

	$firma_pass = md5($firma_pass);

	// match username with the username in the database
	$firmasor = $db->prepare("SELECT firma_pass FROM firma WHERE firma_id=:firma_id");
	$firmasor->execute(array(
		'firma_id' => $firma_id
	));
	$firmacek = $firmasor->fetch(PDO::FETCH_ASSOC);

	$mevcut = $firmacek['firma_pass'];

	if ($firma_pass == $mevcut) {

		if ($pass1 == $pass2) {

			$pass1 = md5($pass2);

			$durumguncelle = $db->prepare("UPDATE firma SET
			firma_pass=:firma_pass
			WHERE firma_id=:firma_id 
			");

			$oldu = $durumguncelle->execute(array(

				'firma_pass' => $pass1,
				'firma_id' => $firma_id

			));

			if ($oldu) {
				header("Location:../profile.php?durum=ok");
			} else {
				header("Location:../password.php?durum=no");
			}
		} else echo "Şifreniz birbiri ile eşleşmiyor!";
	} else echo "Mevcut Şifreniz Hatalı!";
}

if (isset($_POST["menuduzenle-tekrenk"])) {

	$renk1 = $_POST["renk1"];

	$renk1_rgb = substr($renk1, 4);
	$renk1_rgb = substr($renk1_rgb, 0, -1);
	$renk1_rgb = str_replace(' ', '', $renk1_rgb);

	$renk1_hex = rgb2hex2rgb($renk1_rgb);
	$renk1_hex = substr($renk1_hex, 1);

	$id = $_POST["menu_id"];
	$tarih = date('Y-m-d H:i:s');

	if (isset($_POST["menu_durum"])) {
		if ($_POST["menu_durum"] == "1") {
			try {
				$menuGuncelle = $db->prepare(
					"UPDATE menu 
					SET guncellenme_tarihi=:tarih, durum=:durum WHERE firma_id=:id"
				);

				$oldu = $menuGuncelle->execute(array(
					"id" => $firma_id,
					"durum" => "0",
					"tarih" => $tarih,
				));

				if (!$oldu) {
					header("Location:../tek-renk-menu-duzenle.php?durum=no");
				}
			} catch (Exception $e) {
				header("Location:../tek-renk-menu-duzenle.php?durum=no");
			}
			$durum = "1";
		} else {
			$durum = "0";
		}
	} else {
		$durum = "0";
	}

	try {
		$menuGuncelle = $db->prepare(
			"UPDATE menu 
			SET renk1=:renk1, guncellenme_tarihi=:tarih, durum=:durum WHERE id=:id"
		);

		$oldu = $menuGuncelle->execute(array(
			"id" => $id,
			"renk1" => $renk1_hex,
			"tarih" => $tarih,
			"durum" => $durum
		));

		if ($oldu) {
			header("Location:../menu-ayarlar.php?durum=ok");
		} else {
			header("Location:../tek-renk-menu-duzenle.php?durum=no");
		}
	} catch (Exception $e) {
		die($e);
	}
}
if (isset($_POST["menuduzenle-ciftrenk"])) {
	//echo "<pre>";var_dump($_POST);die;
	$id = $_POST["menu_id"];
	$tarih = date('Y-m-d H:i:s');
	if (isset($_POST["menu_durum"])) {
		if ($_POST["menu_durum"] == "1") {
			try {
				$menuGuncelle = $db->prepare(
					"UPDATE menu 
				SET guncellenme_tarihi=:tarih, durum=:durum WHERE firma_id=:id"
				);

				$oldu = $menuGuncelle->execute(array(
					"id" => $firma_id,
					"durum" => "0",
					"tarih" => $tarih,
				));

				if (!$oldu) {
					header("Location:../tek-renk-menu-duzenle.php?durum=no");
				}
			} catch (Exception $e) {
				header("Location:../tek-renk-menu-duzenle.php?durum=no");
			}
			$durum = "1";
		} else
			$durum = "0";
	} else
		$durum = "0";

	$renk1 = $_POST["renk1"];

	$renk1_rgb = substr($renk1, 4);
	$renk1_rgb = substr($renk1_rgb, 0, -1);
	$renk1_rgb = str_replace(' ', '', $renk1_rgb);

	$renk1_hex = rgb2hex2rgb($renk1_rgb);
	$renk1_hex = substr($renk1_hex, 1);

	$renk2 = $_POST["renk2"];

	$renk2_rgb = substr($renk2, 4);
	$renk2_rgb = substr($renk2_rgb, 0, -1);
	$renk2_rgb = str_replace(' ', '', $renk2_rgb);

	$renk2_hex = rgb2hex2rgb($renk2_rgb);
	$renk2_hex = substr($renk2_hex, 1);


	try {
		$menuGuncelle = $db->prepare(
			"UPDATE menu 
			SET renk1=:renk1, renk2=:renk2, guncellenme_tarihi=:tarih, durum=:durum WHERE id=:id"
		);

		$oldu = $menuGuncelle->execute(array(
			"id" => $id,
			"renk1" => $renk1_hex,
			"renk2" => $renk2_hex,
			"tarih" => $tarih,
			"durum" => $durum
		));

		if ($oldu) {
			header("Location:../menu-ayarlar.php?durum=ok");
		} else {
			header("Location:../cift-renk-menu-duzenle.php?durum=no");
		}
	} catch (Exception $e) {
		die($e);
	}
}

if (isset($_POST["menuduzenle-katalog"])) {
	$id = $_POST["menu_id"];
	$tarih = date('Y-m-d H:i:s');
	if (isset($_POST["menu_durum"])) {
		if ($_POST["menu_durum"] == "1") {
			try {
				$menuGuncelle = $db->prepare(
					"UPDATE menu 
					SET guncellenme_tarihi=:tarih, durum=:durum WHERE firma_id=:id"
				);

				$oldu = $menuGuncelle->execute(array(
					"id" => $firma_id,
					"durum" => "0",
					"tarih" => $tarih,
				));

				if (!$oldu) {
					header("Location:../menu-ayarlar.php?durum=no");
				}
			} catch (Exception $e) {
				header("Location:../katalog-menu-duzenle.php?durum=no");
			}
			$durum = "1";
		} else
			$durum = "0";
	} else
		$durum = "0";


	try {
		$menuGuncelle = $db->prepare(
			"UPDATE menu 
			SET guncellenme_tarihi=:tarih, durum=:durum WHERE id=:id"
		);

		$oldu = $menuGuncelle->execute(array(
			"id" => $id,
			"tarih" => $tarih,
			"durum" => $durum
		));

		if ($oldu) {
			header("Location:../menu-ayarlar.php?durum=ok");
		} else {
			header("Location:../katalog-menu-duzenle.php?durum=no");
		}
	} catch (Exception $e) {
		die($e);
	}
}

if (isset($_POST["menuduzenle-katalog-arkaplan-duzenle"])) {
	$size = $_FILES['urun_resimyol']["size"];
	$type = $_FILES['urun_resimyol']["type"];

	if ($size <= 2000000) {
		if ($type == 'image/jpeg' || $type == 'image/jpg' || $type == 'image/png' || $type == 'image/gif') {

			$uploads_dir = '../../img/katalog-bg';
			@$tmp_name = $_FILES['urun_resimyol']["tmp_name"];
			@$name = $_FILES['urun_resimyol']["name"];
			$benzersizsayi1 = rand(20000, 32000);
			$benzersizsayi2 = rand(20000, 32000);
			$benzersizsayi3 = rand(20000, 32000);
			$benzersizsayi4 = rand(20000, 32000);
			$benzersizad = $benzersizsayi1 . $benzersizsayi2 . $benzersizsayi3 . $benzersizsayi4;
			$refimgyol = substr($uploads_dir, 6) . "/" . $benzersizad . $name;
			@move_uploaded_file($tmp_name, "$uploads_dir/$benzersizad$name");
			@move_uploaded_file($tmp_name, "$uploads_dir/$benzersizad$name");

			$duzenle = $db->prepare("UPDATE kategori SET
				kategori_gorsel=:urun_resimyol
				WHERE kategori_id={$_POST['urun_id']}
				");

			$update = $duzenle->execute(array(
				'urun_resimyol' => $refimgyol
			));

			if ($update) {
				$resimsilunlink = $_POST['eski_resim'];
				if ($resimsilunlink != "bg.png") {
					unlink("../../img/katalog-bg/$resimsilunlink");
				}
				header("Location:../katalog-menu-duzenle.php?resim=ok");
			} else {
				header("Location:../katalog-menu-duzenle.php?resim=no");
			}
		} else {
			Header("Location:../katalog-menu-duzenle.php?tur=uyumsuz");
		}
	} else {
		header("Location:../katalog-menu-duzenle.php?boyut=buyuk");
	}
}

if (isset($_POST["opsiyon-ekle"])) {
	if (!preg_match("/^[0-9]+$/", $_POST["urun_id"]))
		header("Location:menu.php");
	else
		$urun_id = $_POST["urun_id"];

	try {
		$kaydet = $db->prepare(
			"INSERT INTO opsiyon SET urun_id = :urun_id, opsiyon = :opsiyon, fiyat = :fiyat"
		);

		$insert = $kaydet->execute(array(
			"urun_id" => $urun_id,
			"opsiyon" => $_POST["opsiyon"],
			"fiyat" => $_POST["opsiyon-fiyat"]
		));

		header("Location:../urun-duzenle.php?id=" . $urun_id);
	} catch (Exception $e) {
		die($e);
		header("Location:../urun-duzenle.php?id=" . $urun_id . "&durum=no");
	}
}

if (isset($_GET['opsiyon-sil']) == "ok") {
	if (!preg_match("/^[0-9]+$/", $_GET["urun-id"]))
		header("Location:menu.php");
	else
		$urun_id = $_GET["urun-id"];

	if (!preg_match("/^[0-9]+$/", $_GET["opsiyon-id"]))
		header("Location:menu.php");
	else
		$opsiyon_id = $_GET["opsiyon-id"];

	$opsiyonsor = $db->prepare("SELECT * FROM opsiyon WHERE id=:id");
	$opsiyonsor->execute(array(
		'id' => $opsiyon_id
	));
	$opsiyoncek = $opsiyonsor->fetch(PDO::FETCH_ASSOC);

	$sil = $db->prepare("DELETE from opsiyon where id=:id");
	$kontrol = $sil->execute(array(
		'id' => $opsiyon_id
	));

	if ($kontrol) {
		header("location:../urun-duzenle.php?id=" . $urun_id);
	} else {
		header("location:../urun-duzenle.php?id=" . $urun_id);
	}
}
