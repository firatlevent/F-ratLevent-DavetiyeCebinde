<?php
require_once "import/header.php";

$urunsor = $db->prepare("SELECT * FROM product WHERE pr_status=:pr_status");
$urunsor->execute(array(
  'pr_status' => 1
));


?>


<!-- Page Content -->
<!-- Heading Starts Here -->
<div class="page-heading header-text">
  <div class="container">
    <div class="row">
      <div class="col-md-12">
        <h1>En popüler online davetiyelerimiz</h1>
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
          <h2>İster mail, ister posta</h2>
          <p>Hedefimiz sizi davetiye dağıtma yükünden kurtarmak. Dilediğiniz davetiyeyi seçin ve düzenleyin. Davetlinizi ister mail olarak isterseniz adresine posta yolu ile ulaşarak biz çağıralım.</p>
        </div>
      </div>

      <?php



      while ($uruncek = $urunsor->fetch(PDO::FETCH_ASSOC)) {
      ?>


        <div class="col-md-4 col-sm-6 col-xs-12">
          <div class="team-item">
            <a href="product.php?id=<?php echo $uruncek["pr_id"] ?>">
              <img src="<?php echo $uruncek["pr_img"] ?>" alt="Sipariş Ver" title="Sipariş Ver">
            </a>
            <div class="down-content">
              <h4><?php echo $uruncek["pr_name"] ?></h4>
              <br />
              <a href="product.php?id=<?php echo $uruncek["pr_id"] ?>" class="main-button">Sipariş ver</a>
            </div>
          </div>
        </div>
      <?php } ?>

      
    </div>
  </div>
</div>
<!-- Pr Ends Here -->


<!-- Testimonials Starts Here -->
<<div class="testimonials-section">
  <div class="container">
    <div class="row">
      <div class="col-md-12">
        <div class="section-heading">
          <span>Müşteri Yorumları</span>
          <h2>İki haftalık davetiye dağıtım serüveninden kurtulduk.</h2>
        </div>
      </div>
      <div class="col-md-10 offset-md-1">
        <div class="owl-testimonials owl-carousel">
          <div class="testimonial-item">
            <div class="icon">
              <i class="fa fa-quote-right"></i>
            </div>
            <p>"Abim evlendiğinde davetlilere ulaşmam ve onları davet etmem tam olarak iki hafta zamanımı aldı. Bu durum evliliği gözümde daha fazla büyütüyordu ta ki Davetiye Cebinde ile tanışana kadar."</p>
            <h4>Ahmet Mithat</h4>
            <span>Yeni Evli</span>
          </div>

        </div>
      </div>
    </div>
  </div>
  </div>
  <!-- Testimonials Ends Here -->


  <?php
  require_once "import/footer.php"
  ?>