<?php
// Version 0.80 development //

/**
 * Copyright (C) 2004 Jonatan Heyman
 *
 * This file is part of the PHP application MMS Decoder.
 *
 * MMS Decoder is free software; you can redistribute it and/or
 * modify it under the terms of the Affero General Public License as
 * published by Affero, Inc.; either version 1 of the License, or
 * (at your option) any later version.
 *
 * MMS Decoder is distributed in the hope that it will be
 * useful, but WITHOUT ANY WARRANTY; without even the implied warranty
 * of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * Affero General Public License for more details.
 *
 * You should have received a copy of the Affero General Public
 * License in the COPYING file that comes with The Affero Project; if
 * not, write to Affero, Inc., 510 Third Street, Suite 225, San
 * Francisco, CA 94107 USA. 
 */

// turn on debugging
define("DEBUG", 1);

// load mms decoder class
require_once("../mmsdecoder.php");

?>

<html>
<head>
	<title>MMS Decoder - Debug tool, file upload and save</title>
</head>
<body>

This is a tool created to help in development of MMS decoding. Just upload a file
containing the raw MMS data and the parts of MMS will be saved separately.

<br><br>

<form action="mmssave.php" method="POST" enctype="multipart/form-data">
	File <input type='file' name='mmsfile'>
	<input type='submit' value='Upload and decode'>
</form>
<br><br>


<?php

if (isset($_FILES["mmsfile"])) {
	$input = $_FILES["mmsfile"]["tmp_name"];
	$mms = new MMSDecoder(file_get_contents($input));
	$mms->parse();
	
	// loop thru parts and save images as files on the server
	foreach ($mms->PARTS as $index => $part) {
		switch ($part->CONTENTTYPE) {
			case "image/jpeg":
				$fileext = ".jpg";
				break;
			case "image/png":
				$fileext = ".png";
				break;
			case "image/gif":
				$fileext = ".gif";
				break;
			case "application/smil":
				$fileext = ".smil";
				break;
			case "video/mp4":
				$fileext = ".mp4";
				break;
			case "text/plain":
				$fileext = ".txt";
				break;
			case "audio/amr":
				$fileext = ".amr";
				break;
			case "audio/midi":
				$fileext = ".mid";
				break;
			default:
				$fileext = ".unknown";
		}
		
		// save data to file with the date and md5 hash of the data as filename
		$filename = "parts" . DIRECTORY_SEPARATOR . basename($input) . "_part" . $index . $fileext;
		file_put_contents($filename, $part->DATA);
		echo "<p>File saved to: " . $filename . "</p>";
	}
}

?>

</body>
</html>
	