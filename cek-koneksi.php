<html>
	<head>
		<title>Contoh Koneksi Mesin Absensi Mengunakan SOAP Web Service</title>
	</head>
	<body bgcolor="#caffcb">
		<h3>Syncronize Time</h3>
		<?php
			$IP = $_GET["ip"];
			$Key = $_GET["key"];
		?>
		<form action="cek-koneksi.php">
			IP Address: <input type="Text" name="ip" size="15" value="<?php echo $IP ?>"><br>
			Comm Key: <input type="Text" name="key" size="5" value="<?php echo $Key ?>"><br><br>
			<input type="Submit" value="Cek Koneksi">
		</form>
		<br>
    <?php
      if($_GET["ip"] != "") {
      	$connect = fsockopen($IP, "80", $errno, $errstr, 1);
      	if($connect) {
      		echo "Koneksi Berhasil";
      	} else {
          echo "Koneksi Gagal";
        }
      }
    ?>
</body>
</html>
