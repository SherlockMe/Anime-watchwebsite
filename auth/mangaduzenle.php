<?php
include("../baglanti.php");
header('X-XSS-Protection: 0');

ob_start();
session_start();

$kulnick=$_SESSION["user"];
$rut = mysqli_query($con,"SELECT * FROM users where kullaniciadi='$kulnick'");
$rutrue=mysqli_fetch_array($rut);
$rutbe=$rutrue["rutbe"];

if($rutbe!="1" && $rutbe!="3")
{
	header("Location:/auth/index.html");
}

if(!isset($_SESSION["login"])){
    header("Location:/auth/index.html");
}
else
{

?>
<!DOCTYPE html>
<html lang="en">

<head>

    <!-- Fontfaces CSS-->
    <link href="/admin/css/font-face.css" rel="stylesheet" media="all">
    <link href="/admin/vendor/font-awesome-4.7/css/font-awesome.min.css" rel="stylesheet" media="all">
    <link href="/admin/vendor/font-awesome-5/css/fontawesome-all.min.css" rel="stylesheet" media="all">
    <link href="/admin/vendor/mdi-font/css/material-design-iconic-font.min.css" rel="stylesheet" media="all">

    <!-- Bootstrap CSS-->
    <link href="/admin/vendor/bootstrap-4.1/bootstrap.min.css" rel="stylesheet" media="all">

    <!-- Vendor CSS-->
    <link href="/admin/vendor/animsition/animsition.min.css" rel="stylesheet" media="all">
    <link href="/admin/vendor/bootstrap-progressbar/bootstrap-progressbar-3.3.4.min.css" rel="stylesheet" media="all">
    <link href="/admin/vendor/wow/animate.css" rel="stylesheet" media="all">
    <link href="/admin/vendor/css-hamburgers/hamburgers.min.css" rel="stylesheet" media="all">
    <link href="/admin/vendor/slick/slick.css" rel="stylesheet" media="all">
    <link href="/admin/vendor/select2/select2.min.css" rel="stylesheet" media="all">
    <link href="/admin/vendor/perfect-scrollbar/perfect-scrollbar.css" rel="stylesheet" media="all">
	<link rel="stylesheet" href="/admin/css/dropzone.css">

    <!-- Main CSS-->
    <link href="/admin/css/theme.css" rel="stylesheet" media="all">

</head>

<body>

            <!-- MAIN CONTENT-->
            <div class="main-content">
                <div class="section__content section__content--p30">
                    <div class="container-fluid">
   <form  name="formara" method="post" action="/auth/mangaduzenle.php?adim=ara">
  <input class="form-control" type="text" name="aranan" Placeholder="İçerik Ara" />
  <input class="form-control" type="submit" value="Ara" width="45" height="35"/>
</form>
<br>       
                        <div class="row">
                            <div class="col-lg-12">
 <?php
$adim = $_GET["adim"];
switch($adim){
case "": 
?>							
						 <table class="table">
  <thead>
    <tr>
      <th scope="col">#id</th>
	  <th scope="col">Resim</th>
      <th scope="col">Adı</th>
      <th scope="col">Yazar</th>
	  <th scope="col">Tür</th>
	  <th scope="col">hit</th>
      <th scope="col">Son Güncelleme</th>
	  <th scope="col">İşlem</th>
    </tr>
  </thead>
  <tbody>  
						   <?php 
				
## Sayfa Degiskeninin alalim.
	$sayfa		=	@$_GET["s"];
	## Sayfa Boşsa yada sayı değilse,
	if(empty($sayfa) || !is_numeric($sayfa)){
		$sayfa	=	1;
	}

	## Kaçar Tane Gözükecek
	$kacar		=	10;
	## Kayıt Sayısı
	$ksayisi	=	mysqli_num_rows(mysqli_query($con,"select id from manga"));
	## Sayfa Sayısı
	$ssayisi	=	ceil($ksayisi/$kacar);
	## Nereden Başlayacak
	$nereden	=	($sayfa*$kacar)-$kacar;
	
	## Verileri Çekelim
	$bul		=	mysqli_query($con,"select * from manga order by id DESC limit $nereden,$kacar");
	$a=0;
		while($mangalist=mysqli_fetch_array($bul)){
					extract($mangalist);
					
					
					$tural=$mangalist["tur"];
					$tursnc=mysqli_query($con,"select * from turler where id=".$tural."");
					$tursncc=mysqli_fetch_array($tursnc);
					
					
					echo '		  
		<tr>
      <th scope="row">'.$a++.'</th>
      <td><img src="../uploads/'.$mangalist["kapak_resim"].'" style="width:25px;"></td>
      <td>'.$mangalist["ad"].'</td>
      <td>'.$mangalist["yazar"].'</td>
	  <td>'.$tursncc["adi"].'</td>
	  <td>'.$mangalist["hit"].'</td>
	  <td>'.$mangalist["guncellemetarihi"].'</td>
	  <td><a href="mangaduzenledv.php?id='.$mangalist["essizid"].'"><font color="blue">DÜZENLE</font></a> - 
	  <a href="mangasil.php?id='.$mangalist["essizid"].'" onclick="cik()"><font color="red">SİL</font></a>

	  </td>
    </tr>
							  '; 

		}
		echo '</tbody>
  </table>';
	## Sayfaları Yazdıralım
	for ($i=1; $i<=$ssayisi; $i++){
		echo "<span style='text-align:center;margin:5px; border:1px dotted orange'><a href='mangaduzenle.php?s={$i}'";
		if($i == $sayfa){
			echo "class='aktif'";
		}
		echo ">{$i}</a></span>";
	}		   
						   
						   ?>
	                  
<?php
break;
case "ara":

$ara = $_POST["aranan"];

$sqlara = "SELECT * FROM manga WHERE ad LIKE '%$ara%' LIMIT 20";

$deger=mysqli_query($con,$sqlara);

    $bul=mysqli_num_rows($deger);

  if($bul > 0) {
    while($yaz=mysqli_fetch_array($deger)) {
		if($yaz["durum"]=="1")
		{
			$drmsnc='Devam Ediyor';
		}
			else{
				$drmsnc='Bitti';
			}
	  echo  "<span><a href='/auth/mangaduzenledv.php?id=".$yaz["essizid"]."'>Düzenle -> ".$yaz["ad"]." | Tarih: ".$yaz["guncellemetarihi"]." | Durum: ".$drmsnc." </a><hr></span>";
	  
    }
  } else {
    echo "<br>";
    echo "<font color='gray' size='4'>'".$ara."' aradığınız kelime ile ilgili bir  sonuç bulunamadı.</font>";
  }


break;

}
?>								   
                                
                            </div> 
                        </div>

                    </div>
                </div>
            </div>
	

    <!-- Jquery JS-->
    <script src="/admin/vendor/jquery-3.2.1.min.js"></script>
    <!-- Bootstrap JS-->
    <script src="/admin/vendor/bootstrap-4.1/popper.min.js"></script>
    <script src="/admin/vendor/bootstrap-4.1/bootstrap.min.js"></script>
    <!-- Vendor JS       -->
    <script src="/admin/vendor/slick/slick.min.js">
    </script>
    <script src="/admin/vendor/wow/wow.min.js"></script>
    <script src="/admin/vendor/animsition/animsition.min.js"></script>
    <script src="/admin/vendor/bootstrap-progressbar/bootstrap-progressbar.min.js">
    </script>
    <script src="/admin/vendor/counter-up/jquery.waypoints.min.js"></script>
    <script src="/admin/vendor/counter-up/jquery.counterup.min.js">
    </script>
    <script src="/admin/vendor/circle-progress/circle-progress.min.js"></script>
    <script src="/admin/vendor/perfect-scrollbar/perfect-scrollbar.js"></script>
    <script src="/admin/vendor/chartjs/Chart.bundle.min.js"></script>
    <script src="/admin/vendor/select2/select2.min.js">
    </script>

    <!-- Main JS-->
    <script src="/admin/js/main.js"></script>
	
	<script type="text/javascript">
function cik (){
    id = confirm('Silmek için emin misin? Bu İşlem Geri Alınamaz !');
    if(id){

    }else{

	 document.location = 'hesabim.php#animeedit';
    }
}
</script>
	

</body>

</html>
<!-- end document-->
<?php }?>
