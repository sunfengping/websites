<?php
class ArrayToXML
{
	var $array = array();
	var $xml_output = "";
	var $xml_header = "<?xml version=\"1.0\" encoding=\"iso-8859-1\"?><root>";
	var $xml_footer = "</root>";

	public function __construct($array)
	{
		$this->array = $array;
		
		if(is_array($array) && count($array) > 0)
		{
			$this->structure_xml($array);
		}
		else
		{
			$this->xml_output .= "No data avaible";
		}
	}

	public function structure_xml($array)
	{
		foreach($array as $key => $value)
		{
			if(is_array($value))
			{
				$tag = preg_replace('/^[0-9]{1,}/i','data',$key);
				$tag2 = explode(" ", $tag);
				$this->xml_output .= "<$tag>";
				$this->structure_xml($value);
				$this->xml_output .= "</".$tag2[0].">";
			}
			else
			{
				$tag = preg_replace('/^[0-9]{1,}/i','data',$key);
				$tag2 = explode(" ", $tag);
				$this->xml_output .= "<$tag>$value</".$tag2[0].">";

			}
		}
	}
	
	public function to_xml()
	{
		
		$body = $this->xml_header;
		$body .= $this->xml_output;
		$body .= $this->xml_footer;
		return $body;
	}
}
?>