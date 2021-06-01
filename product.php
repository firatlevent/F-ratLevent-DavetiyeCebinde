<?php
include "import/header.php";
$urunsor = $db->prepare("SELECT * FROM product WHERE pr_id=:pr_id AND pr_status=:pr_status");
$urunsor->execute(array(
    'pr_id' => $_GET['id'],
    'pr_status' => 1
));

$uruncek = $urunsor->fetch(PDO::FETCH_ASSOC);

?>


<!-- Page Content -->
<!-- Heading Starts Here -->
<div class="page-heading header-text">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h1>Sadece birkaç adımda davetiyeni herkese ulaştır.</h1>
            </div>
        </div>
    </div>
</div>
<!-- Heading Ends Here -->

<!-- Contact Us Starts Here -->
<div class="contact-us">
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <img src="<?php echo $uruncek['pr_img']; ?>" alt="">
            </div>
            <div class="col-md-6">
                <div class="contact-form">
                    <form id="contact" action="import/islem.php" method="POST" enctype="multipart/form-data">
                        <div class="row">
                            <div class="col-md-12 col-sm-12">
                                <fieldset>
                                    <p>Davetiye Türü</p>
                                    <select class="form-control" name="ord_type" id="exampleFormControlSelect1">
                                        <option>Seçiniz</option>
                                        <option value="0">Dijital Davetiye</option>
                                        <option value="1">Baskı Davetiye</option>
                                    </select>
                                    <br>
                                </fieldset>
                            </div>

                            <div class="col-md-12 col-sm-12">
                                <fieldset>
                                    <p>Damat</p>
                                    <input name="ord_damat" type="text" id="ord_damat" placeholder="Damat İsim" required="">
                                </fieldset>
                            </div>
                            <div class="col-md-12 col-sm-12">
                                <fieldset>
                                    <p>Gelin</p>
                                    <input name="ord_gelin" type="text" id="ord_gelin" placeholder="Gelin İsim" required="">
                                </fieldset>
                            </div>
                            <div class="col-md-12 col-sm-12">
                                <fieldset>
                                    <p>Aile</p>
                                    <input name="ord_aile" type="text" id="ord_aile" placeholder="Örn: Gökçe ve Alkan ailesi" required="">
                                </fieldset>
                            </div>
                            <div class="col-lg-12">
                                <fieldset>
                                    <p>Davet Metni</p>
                                    <textarea name="ord_mani" rows="6" id="ord_mani" placeholder="Örn: İki cihan mutluluğu duası ile birlikteliğimize ilk adımı atarken, sizleri de aramızda görmekten onur duyarız.." required=""></textarea>
                                </fieldset>
                            </div>

                            <div class="col-md-6 col-sm-6">
                                <fieldset>
                                    <p>Organizasyon Tarihi</p>
                                    <input name="ord_tarih" type="date" id="ord_tarih" required="">
                                </fieldset>
                            </div>
                            <div class="col-md-6 col-sm-6">
                                <fieldset>
                                    <p>Organizasyon Saati</p>
                                    <input name="ord_saat" type="time" id="ord_saat" required="">
                                </fieldset>
                            </div>

                            <div class="col-lg-12">
                                <fieldset>
                                    <p>Organizasyon Adresi</p>
                                    <textarea name="ord_adres" rows="2" id="ord_adres" placeholder="" required=""></textarea>
                                </fieldset>
                            </div>



                            <div class="col-md-12 col-sm-12">
                                <fieldset>
                                    <p>Davetli Listesi</p>
                                    <small>Maximum 10mb xls ve xlsx dosyalarını yükleyiniz.</br>Excel dosyanızı ilk sütunda isim ikinci sütunda mail olacak şekilde biçimlendirin.</small>
                                    <input name="ord_file" type="file" class="form-control-file" required="">
                                </fieldset>
                            </div>

                            <input type="hidden" name="pr_id" value="<?php echo $_GET["id"] ?>">

                            <br>
                            <div class="col-lg-12">
                                <fieldset>
                                    <center>
                                        <?php
                                        if (isset($_SESSION['user_mail'])) {
                                        ?>
                                            <button type="submit" name="siparis" id="form-submit" class="main-button ">Siparişi Oluştur</button>
                                        <?php } else { ?>
                                            <p class="text-danger">*Sipariş oluşturmak için <a href="login/login.php">Giriş Yap</a>'malısınız.</p></br>
                                            <button type="submit" disabled name="siparis" id="form-submit" class="main-button btn btn-secondary">Siparişi Oluştur</button>

                                        <?php } ?>
                                    </center>
                                </fieldset>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Contact Us Ends Here -->


<!-- Testimonials Starts Here -->
<div class="testimonials-section">
</div>
<!-- Testimonials Ends Here -->


<?php
include "import/footer.php"
?>