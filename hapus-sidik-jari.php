<html>
	<head>
		<title>Contoh Koneksi Mesin Absensi Mengunakan SOAP Web Service</title>
	</head>
	<body bgcolor="#caffcb">
		<h3>Hapus Sidik Jari</h3>
		<?php
			$IP = $_GET["ip"];
			$Key = $_GET["key"];
			$id = $_GET["id"];
			$fn = $_GET["fn"];
			$temp = $_GET["temp"];
		?>
		<form action="hapus-sidik-jari.php">
			IP Address: <input type="Text" name="ip" size="15" value="<?php echo $IP; ?>"><br>
			Comm Key: <input type="Text" name="key" size="5" value="<?php echo $Key; ?>"><br><br>
			UserID: <input type="Text" name="id" size="5" value="<?php echo $id; ?>"><br><br>
			<input type="Submit" value="Hapus">
		</form>
		<br>
		<?php
			if($_GET["ip"] != "") {
				$connect = fsockopen($IP, "80", $errno, $errstr, 1);
				if($connect) {
					// <DeleteTemplate>
					// 	<ArgComKey xsi:type=\"xsd:integer\">".$Key."</ArgComKey>
					// 	<Arg>
					// 		<PIN xsi:type=\"xsd:integer\">".$id."</PIN>
					// 	</Arg>
					// </DeleteTemplate>
					$soap_request = "<DeleteTemplate><ArgComKey xsi:type=\"xsd:integer\">" . $Key . "</ArgComKey><Arg><PIN xsi:type=\"xsd:integer\">" . $id . "</PIN></Arg></DeleteTemplate>";
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
				// echo $buffer;
				$buffer = Parse_Data($buffer, "<DeleteTemplateResponse>", "</DeleteTemplateResponse>");
				$buffer = Parse_Data($buffer, "<Information>", "</Information>");
				echo "<b>Result:</b><br>" . $buffer;
			}
		?>
	</body>
</html>
