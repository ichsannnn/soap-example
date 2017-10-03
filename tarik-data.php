<html>
	<head>
		<title>Contoh Koneksi Mesin Absensi Mengunakan SOAP Web Service</title>
	</head>
	<body bgcolor="#caffcb">
		<h3>Download Log Data</h3>
		<?php
			$IP = $_GET["ip"];
			$Key = $_GET["key"];
		?>
		<form action="tarik-data.php">
			IP Address: <input type="Text" name="ip" size="15" value="<?php echo $IP; ?>"><br>
			Comm Key: <input type="Text" name="key" size="5" value="<?php echo $Key; ?>"><br><br>
			<input type="Submit" value="Download">
		</form>
		<br>
		<?php if($_GET["ip"] != "") : ?>
			<table cellspacing="2" cellpadding="2" border="1">
				<tr align="center">
					<td><b>UserID</b></td>
					<td width="200"><b>Tanggal & Jam</b></td>
					<td><b>Verifikasi</b></td>
					<td><b>Status</b></td>
				</tr>
				<?php
					$connect = fsockopen($IP, "80", $errno, $errstr, 1);
					if($connect) {
						// <GetAttLog>
						// 	<ArgComKey xsi:type=\"xsd:integer\">".$Key."</ArgComKey>
						// 	<Arg>
						// 		<PIN xsi:type=\"xsd:integer\">All</PIN>
						// 		<Date xsi:type=\"xsd:string\">".date("Y-m-d")."</Date>
						// 	</Arg>
						// </GetAttLog>
						$soap_request = "<GetAttLog><ArgComKey xsi:type=\"xsd:integer\">" . $Key . "</ArgComKey><Arg><PIN xsi:type=\"xsd:integer\">All</PIN></Arg></GetAttLog>";
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
					$buffer = Parse_Data($buffer, "<GetAttLogResponse>", "</GetAttLogResponse>");
					$buffer = explode("\r\n", $buffer);
					for($a = 0; $a < count($buffer); $a++):
						$data = Parse_Data($buffer[$a], "<Row>", "</Row>");
						$PIN = Parse_Data($data, "<PIN>", "</PIN>");
						$DateTime = Parse_Data($data, "<DateTime>", "</DateTime>");
						$Verified = Parse_Data($data, "<Verified>", "</Verified>");
						$Status = Parse_Data($data, "<Status>", "</Status>");
				?>
				<tr align="center">
					<td><?php echo $PIN; ?></td>
					<td><?php echo $DateTime; ?></td>
					<td><?php echo $Verified; ?></td>
					<td><?php echo $Status; ?></td>
				</tr>
			<?php endfor; ?>
			</table>
		<?php endif; ?>
	</body>
</html>
