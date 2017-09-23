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
		<form action="syn-time.php">
			IP Address: <input type="Text" name="ip" value="<?php echo $IP ?>" size=15>
			<br>
			Comm Key: <input type="Text" name="key" size="5" value="<?php echo $Key ?>">
			<br><br>
			<input type="Submit" value="Syn Time">
		</form>
		<br>

<?php
if($_GET["ip"] != ""){
	$Connect = fsockopen($IP, "80", $errno, $errstr, 1);
	if($Connect){
		$soap_request="<SetDate><ArgComKey xsi:type=\"xsd:integer\">".$Key."</ArgComKey><Arg><Date xsi:type=\"xsd:string\">".date("Y-m-d")."</Date><Time xsi:type=\"xsd:string\">".date("H:i:s")."</Time></Arg></SetDate>";
		$newLine="\r\n";
		fputs($Connect, "POST /iWsService HTTP/1.0".$newLine);
	    fputs($Connect, "Content-Type: text/xml".$newLine);
	    fputs($Connect, "Content-Length: ".strlen($soap_request).$newLine.$newLine);
	    fputs($Connect, $soap_request.$newLine);
		$buffer="";
		while($Response=fgets($Connect, 1024)){
			$buffer=$buffer.$Response;
		}
	} else echo "Koneksi Gagal";
	include("parse.php");
	$buffer = Parse_Data($buffer,"<Information>","</Information>");
	echo "<b>Result: </b><br>";
	echo $buffer;
}
?>
</body>
</html>
