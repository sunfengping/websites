<?php
/**
 * $Id: zip.class.php 33 2004-06-13 12:48:19Z eofredj $
[HEADER]
 * Originally based on "Creating ZIP Files Dynamically" By John Coggeshall
 * http://www.zend.com/zend/spotlight/creating-zip-files1.php
 */
class zipfile {
	var $datasec = array();
	var $ctrl_dir = array();
	var $eof_ctrl_dir = "\x50\x4b\x05\x06\x00\x00\x00\x00";
	var $old_offset = 0;

	function unix2DosTime($unixtime = 0) {
		$timearray = ($unixtime == 0) ? getdate() : getdate($unixtime);

		if ($timearray['year'] < 1980) {
			$timearray['year'] = 1980;
			$timearray['mon'] = 1;
			$timearray['mday'] = 1;
			$timearray['hours'] = 0;
			$timearray['minutes'] = 0;
			$timearray['seconds'] = 0;
		} // end if
		return (($timearray['year'] - 1980) << 25) | ($timearray['mon'] << 21) | ($timearray['mday'] << 16) |
		($timearray['hours'] << 11) | ($timearray['minutes'] << 5) | ($timearray['seconds'] >> 1);
	} // end of the 'unix2DosTime()' method

	function add_dir($name) {
		$name = str_replace("\\", "/", $name);

		$fr = "\x50\x4b\x03\x04";
		$fr .= "\x0a\x00";
		$fr .= "\x00\x00";
		$fr .= "\x00\x00";
		$fr .= "\x00\x00\x00\x00";

		$fr .= pack("V", 0);
		$fr .= pack("V", 0);
		$fr .= pack("V", 0);
		$fr .= pack("v", strlen($name));
		$fr .= pack("v", 0);
		$fr .= $name;
		$fr .= pack("V", 0);
		$fr .= pack("V", 0);
		$fr .= pack("V", 0);

		$this->datasec[] = $fr;
		$new_offset = strlen(implode("", $this->datasec));

		$cdrec = "\x50\x4b\x01\x02";
		$cdrec .= "\x00\x00";
		$cdrec .= "\x0a\x00";
		$cdrec .= "\x00\x00";
		$cdrec .= "\x00\x00";
		$cdrec .= "\x00\x00\x00\x00";
		$cdrec .= pack("V", 0);
		$cdrec .= pack("V", 0);
		$cdrec .= pack("V", 0);
		$cdrec .= pack("v", strlen($name));
		$cdrec .= pack("v", 0);
		$cdrec .= pack("v", 0);
		$cdrec .= pack("v", 0);
		$cdrec .= pack("v", 0);
		$ext = "\x00\x00\x10\x00";
		$ext = "\xff\xff\xff\xff";
		$cdrec .= pack("V", 16);
		$cdrec .= pack("V", $this->old_offset);
		$cdrec .= $name;

		$this->ctrl_dir[] = $cdrec;
		$this->old_offset = $new_offset;
		return;
	}

	function add_file($data, $name, $time = 0) {
		$name = str_replace('\\', '/', $name);

		$dtime = dechex($this->unix2DosTime($time));
		$hexdtime = '\x' . $dtime[6] . $dtime[7] . '\x' . $dtime[4] . $dtime[5] . '\x' . $dtime[2] . $dtime[3] . '\x' . $dtime[0] . $dtime[1];
		eval('$hexdtime = "' . $hexdtime . '";');
		// "local file header" segment
		$unc_len = strlen($data);
		$crc = crc32($data);
		$zdata = gzcompress($data, 9);
		$zdata = substr(substr($zdata, 0, strlen($zdata) - 4), 2); // fix crc bug
		$c_len = strlen($zdata);

		$fr = "\x50\x4b\x03\x04";
		$fr .= "\x14\x00"; // ver needed to extract
		$fr .= "\x00\x00"; // gen purpose bit flag
		$fr .= "\x08\x00"; // compression method
		$fr .= $hexdtime; // last mod time and date
		$fr .= pack('V', $crc); // crc32
		$fr .= pack('V', $c_len); // compressed filesize
		$fr .= pack('V', $unc_len); // uncompressed filesize
		$fr .= pack('v', strlen($name)); // length of filename
		$fr .= pack('v', 0); // extra field length
		$fr .= $name;
		$fr .= $zdata; // "file data" segment

		// "data descriptor" segment (optional but necessary if archive is not
		// served as file)
		$fr .= pack('V', $crc); // crc32
		$fr .= pack('V', $c_len); // compressed filesize
		$fr .= pack('V', $unc_len); // uncompressed filesize

		// add this entry to array
		$this->datasec[] = $fr;
		$new_offset = strlen(implode('', $this->datasec));
		// now add to central directory record
		$cdrec = "\x50\x4b\x01\x02";
		$cdrec .= "\x00\x00"; // version made by
		$cdrec .= "\x14\x00"; // version needed to extract
		$cdrec .= "\x00\x00"; // gen purpose bit flag
		$cdrec .= "\x08\x00"; // compression method
		$cdrec .= $hexdtime; // last mod time & date
		$cdrec .= pack('V', $crc); // crc32
		$cdrec .= pack('V', $c_len); // compressed filesize
		$cdrec .= pack('V', $unc_len); // uncompressed filesize
		$cdrec .= pack('v', strlen($name)); // length of filename
		$cdrec .= pack('v', 0); // extra field length
		$cdrec .= pack('v', 0); // file comment length
		$cdrec .= pack('v', 0); // disk number start
		$cdrec .= pack('v', 0); // internal file attributes
		$cdrec .= pack('V', 32); // external file attributes - 'archive' bit set

		$cdrec .= pack('V', $this->old_offset); // relative offset of local header
		$this->old_offset += strlen($fr);

		$cdrec .= $name;
		// optional extra field, file comment goes here
		// save to central directory
		$this->ctrl_dir[] = $cdrec;
	} // end of the 'addFile()' method

	function file() {
		$data = implode('', $this->datasec);
		$ctrldir = implode('', $this->ctrl_dir);

		return $data . $ctrldir . $this->eof_ctrl_dir .
			pack('v', sizeof($this->ctrl_dir)) . // total # of entries "on this disk"
			pack('v', sizeof($this->ctrl_dir)) . // total # of entries overall
			pack('V', strlen($ctrldir)) . // size of central dir
			pack('V', strlen($data)) . // offset to start of central dir
			"\x00\x00"; // .zip file comment length
	}
}

?>