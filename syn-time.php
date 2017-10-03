<html>
	<head>
		<title>Contoh Koneksi Mesin Absensi Mengunakan SOAP Web Service</title>
	</head>
	<body bgcolor="#caffcb">
		<h3>Syncronize Time</h3>
		<h3><?php echo date('Y-m-d H:i:s') ?></h3>
		<?php
			$IP = $_GET["ip"];
			$Key = $_GET["key"];
		?>
		<form action="syn-time.php">
			IP Address: <input type="Text" name="ip" size="15" value="<?php echo $IP; ?>"><br>
			Comm Key: <input type="Text" name="key" size="5" value="<?php echo $Key; ?>"><br><br>
			<input type="Submit" value="Syn Time">
		</form>
		<br>
		<?php
			if($_GET["ip"] != "") {
				$connect = fsockopen($IP, "80", $errno, $errstr, 1);
				if($connect) {
					// <SetDate>
					// 	<ArgComKey xsi:type=\"xsd:integer\">".$Key."</ArgComKey>
					// 	<Arg>
					// 		<Date xsi:type=\"xsd:string\">".date("Y-m-d")."</Date>
					// 		<Time xsi:type=\"xsd:string\">".date("H:i:s")."</Time>
					// 	</Arg>
					// </SetDate>
					$soap_request = "<SetDate><ArgComKey xsi:type=\"xsd:integer\">" . $Key . "</ArgComKey><Arg><Date xsi:type=\"xsd:string\">" . date("Y-m-d") . "</Date><Time xsi:type=\"xsd:string\">" . date("H:i:s") . "</Time></Arg></SetDate>";
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
				echo "<b>Result: </b><br>";
				echo $buffer;
			}
		?>
	</body>
</html>
