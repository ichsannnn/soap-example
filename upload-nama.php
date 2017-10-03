<html>
	<head>
		<title>Contoh Koneksi Mesin Absensi Mengunakan SOAP Web Service</title>
	</head>
	<body bgcolor="#caffcb">
		<h3>Upload Nama</h3>
		<?php
			$IP = $_GET["ip"];
			$Key = $_GET["key"];
		?>
		<form action="upload-nama.php">
			IP Address: <input type="Text" name="ip" size="15" value="<?php echo $IP; ?>"><br>
			Comm Key: <input type="Text" name="key" size="5" value="<?php echo $Key; ?>"><br><br>
			UserID: <input type="Text" name="id" size="5" value="<?php echo $id; ?>"><br>
			Nama: <input type="Text" name="nama" size="15" value="<?php echo $nama; ?>"><br><br>
			<input type="Submit" value="Upload Nama">
		</form>
		<br>
		<?php
			if($_GET["ip"] != "") {
				$connect = fsockopen($IP, "80", $errno, $errstr, 1);
				if($connect) {
					$id = $_GET["id"];
					$nama = $_GET["nama"];
					// <SetUserInfo>
					// 	<ArgComKey Xsi:type=\"xsd:integer\">".$Key."</ArgComKey>
					// 	<Arg>
					// 		<PIN>".$id."</PIN>
					// 		<Name>".$nama."</Name>
					// 	</Arg>
					// </SetUserInfo>
					$soap_request = "<SetUserInfo><ArgComKey Xsi:type=\"xsd:integer\">" . $Key . "</ArgComKey><Arg><PIN>" . $id . "</PIN><Name>" . $nama . "</Name></Arg></SetUserInfo>";
					$newLine = "\r\n";
					fputs($connect, "POST /iWsService HTTP/1.0" . $newLine);
					fputs($connect, "Content-Type: text/xml" . $newLine);
					fputs($connect, "Content-Length: " . strlen($soap_request) . $newLine . $newLine);
					fputs($connect, $soap_request . $newLine);
					$buffer = "";
					while($Response = fgets($connect, 1024)) {
						$buffer = $buffer . $Response;
					}
				} else {
					echo "Koneksi Gagal";
				}
				include("parse.php");
				$buffer = Parse_Data($buffer, "<Information>", "</Information>");
				echo "<b>Result:</b><br>";
				echo $buffer;
			}
		?>
	</body>
</html>
