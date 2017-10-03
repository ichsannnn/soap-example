<html>
	<head>
		<title>Contoh Koneksi Mesin Absensi Mengunakan SOAP Web Service</title>
	</head>
	<body bgcolor="#caffcb">
		<h3>Clear Log Data</h3>
		<?php
			$IP = $_GET["ip"];
			$Key = $_GET["key"];
		?>
		<form action="clear-data.php">
			IP Address: <input type="Text" name="ip" size="15" value="<?php echo $IP; ?>"><br>
			Comm Key: <input type="Text" name="key" size="5" value="<?php echo $Key; ?>"><br><br>
			<input type="Submit" value="Clear Log">
		</form>
		<br>
		<?php
			if($_GET["ip"] != "") {
				$connect = fsockopen($IP, "80", $errno, $errstr, 1);
				if($connect) {
					// <ClearData>
					// 	<ArgComKey xsi:type=\"xsd:integer\">".$Key."</ArgComKey>
					// 	<Arg>
					// 		<Value xsi:type=\"xsd:integer\">3</Value>
					// 	</Arg>
					// </ClearData>
					$soap_request = "<ClearData><ArgComKey xsi:type=\"xsd:integer\">" . $Key . "</ArgComKey><Arg><Value xsi:type=\"xsd:integer\">3</Value></Arg></ClearData>";
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
