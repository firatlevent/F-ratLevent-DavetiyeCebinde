<?php
require_once "import/header.php";

$siparissor = $db->prepare("SELECT * FROM ord WHERE ord_id=:ord_id");
$siparissor->execute(array(
  'ord_id' => $_GET["id"]
));
$sipariscek = $siparissor->fetch(PDO::FETCH_ASSOC);

?>


<!-- Page Content -->
<!-- Heading Starts Here -->
<div class="page-heading header-text">
  <div class="container">
    <div class="row">
      <div class="col-md-12">
        <h1>Tebrikler <?php echo $sipariscek['ord_id']; ?> nolu siparişiniz tarafımıza ulaştı.</h1>
      </div>
    </div>
  </div>
</div>
<!-- Heading Ends Here -->


<!-- Team Starts Here -->
<div class="team-section">
  <div class="container">
    <div class="row">
      <div class="col-md-8 offset-md-2">
        <div class="section-heading">
          <h2>Sipariş Ayrıntılarınız</h2>
          <div>
            <h3><?php echo $sipariscek['ord_damat']; ?> & <?php echo $sipariscek['ord_gelin']; ?></h3>
          </div>
          <div>
            <h4><?php echo $sipariscek['ord_aile']; ?></h4>
          </div>
          <div>
            <p>
              <?php echo $sipariscek['ord_mani']; ?>
            </p>
          </div>
          <br>
          <div class="row">
            <div class="row col-md-4">
              <div class="col-md-12">
                <?php echo $sipariscek['ord_tarih']; ?>
              </div>
              <div class="col-md-12">
                <?php echo $sipariscek['ord_saat']; ?>
              </div>
            </div>
            <div class="row col-md-8">
              <?php echo $sipariscek['ord_adres']; ?>
            </div>
          </div>
          <hr>
          <div>
            <p>Müşteri temsilcimiz sizinle en kısa zamanda iletişime geçecektir.</p><br>
            <p>İyi günler dileriz</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- Pr Ends Here -->

<?php
require_once "import/footer.php"
?>