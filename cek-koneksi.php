<html>
	<head>
		<title>Contoh Koneksi Mesin Absensi Mengunakan SOAP Web Service</title>
	</head>
	<body bgcolor="#caffcb">
		<h3>Syncronize Time</h3>
		<?php
			$IP = $_GET["ip"];
			$Key = $_GET["key"];
			if($IP == "") $IP = "192.168.1.201";
			if($Key == "") $Key = "0";
		?>
		<form action="cek-koneksi.php">
			IP Address: <input type="Text" name="ip" value="<?php echo $IP ?>" size=15>
			<br>
			Comm Key: <input type="Text" name="key" size="5" value="<?php echo $Key ?>">
			<br><br>
			<input type="Submit" value="Cek Koneksi">
		</form>
		<br>

    <?php
      if($_GET["ip"] != ""){
      	$Connect = fsockopen($IP, "80", $errno, $errstr, 1);
      	if($Connect){
      		echo "Koneksi Berhasil";
      	} else {
          echo "Koneksi Gagal";
        }
      }
    ?>
</body>
</html>
