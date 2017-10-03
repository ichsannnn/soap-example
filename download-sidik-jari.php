<html>
	<head>
		<title>Contoh Koneksi Mesin Absensi Mengunakan SOAP Web Service</title>
	</head>
	<body bgcolor="#caffcb">
		<h3>Download Sidik Jari</h3>
		<?php
			$IP = $_GET["ip"];
			$Key = $_GET["key"];
			$id = $_GET["id"];
			$fn = $_GET["fn"];
		?>
		<form action="download-sidik-jari.php">
			IP Address: <input type="Text" name="ip" size="15" value="<?php echo $IP; ?>"><br>
			Comm Key: <input type="Text" name="key" size="5" value="<?php echo $Key; ?>"><br><br>
			UserID: <input type="Text" name="id" size="5" value="<?php echo $id; ?>"><br>
			Finger No: <input type="Text" name="fn" size="1" value="<?php echo $fn; ?>"><br><br>
			<input type="Submit" value="Download">
		</form>
		<br>
		<?php if($_GET["ip"] != "") : ?>
			<table cellspacing="2" cellpadding="2" border="1">
				<tr align="center">
					<th>UserId</th>
					<th width="200">FingerID</th>
					<th>Size</th>
					<th>Valid</th>
					<th align="left">Template</th>
				</tr>
				<?php
					$connect = fsockopen($IP, "80", $errno, $errstr, 1);
					if($connect) {
						// <GetUserTemplate>
						// 	<ArgComKey xsi:type=\"xsd:integer\">" . $Key . "</ArgComKey>
						// 	<Arg>
						// 		<PIN xsi:type=\"xsd:integer\">" . $id . "</PIN>
						// 		<FingerID xsi:type=\"xsd:integer\">" . $fn . "</FingerID>
						// 	</Arg>
						// </GetUserTemplate>
						$soap_request = "<GetUserTemplate><ArgComKey xsi:type=\"xsd:integer\">" . $Key . "</ArgComKey><Arg><PIN xsi:type=\"xsd:integer\">" . $id . "</PIN><FingerID xsi:type=\"xsd:integer\">" . $fn . "</FingerID></Arg></GetUserTemplate>";
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
					echo $buffer;
					include("parse.php");
					$buffer = Parse_Data($buffer, "<GetUserTemplateResponse>", "</GetUserTemplateResponse>");
					$buffer = explode("\r\n", $buffer);
					for($a = 0; $a < count($buffer); $a++) :
						$data = Parse_Data($buffer[$a], "<Row>", "</Row>");
						$PIN = Parse_Data($data, "<PIN>", "</PIN>");
						$FingerID = Parse_Data($data, "<FingerID>", "</FingerID>");
						$Size = Parse_Data($data, "<Size>", "</Size>");
						$Valid = Parse_Data($data, "<Valid>", "</Valid>");
						$Template = Parse_Data($data, "<Template>", "</Template>");
				?>
						<tr align="center">
							<td><?php echo $PIN; ?></td>
							<td><?php echo $FingerID; ?></td>
							<td><?php echo $Size; ?></td>
							<td><?php echo $Valid; ?></td>
							<td><?php echo $Template; ?></td>
						</tr>
				<?php endfor; ?>
			</table>
		<?php endif; ?>
	</body>
</html>
