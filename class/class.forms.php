<?php
/***************************************************************************
 *                          forms.class
 *                            -------------------
 *   begin                : Saturday,10/07/03
 *   copyright            : (C) 2003  Peak Software
 *   email                : chris@peaksoftware.com.au

 ***************************************************************************/


class forms {

	//stores validation javascript
	var $validation = "";
	//stores form to valide
	var $form_name = "";

	function forms() {
	 $validation = "";
	}
//Value, display text,table,Select name,seletced item,selectitem=
	function query_select($value,$text,$table,$name,$selected=0,$var=0,$custom="") {
	
		$mysql_select = new mysql();
	$sql="SELECT $value,$text FROM $table order by $text";
		$sheader="<select id='$name' name='$name' $custom >\n";
		$sfooter="</select>\n";
	
	  $mysql_select->query($sql);  
		  if ($mysql_select->num_rows() > 0) {
				while ($mysql_select->movenext()) { 
				$svalue= $mysql_select->getfield(0);
				$stext= $mysql_select->getfield(1);
					if($svalue==$selected){
							$sbody=$sbody."<option value='$svalue' selected>$stext</option>\n";
						}
					else
						{
							$sbody=$sbody."<option value='$svalue'>$stext</option>\n";
						}
 				
				}
		}	
		return $sheader.$sbody.$sfooter;
	}
	
	
	
	
	
	//Value, display text,table,Select name,seletced item,selectitem=
	function query_sql_select($name,$sql,$selected=0,$class="",$Options="") {
	
		$mysql_select = new mysql();
			$sheader="<select id='$name' name='$name' class='$class' $Options>\n";
		$sfooter="</select>\n";
		$sbody="<option value='' >Please Select</option>\n";
	  $mysql_select->query($sql);  
		  if ($mysql_select->num_rows() > 0) {
				while ($mysql_select->movenext()) { 
				$svalue= $mysql_select->getfield(0);
				$stext= $mysql_select->getfield(1);
					if($svalue==$selected){
							$sbody=$sbody."<option value='$svalue' selected>$stext</option>\n";
						}
					else
						{
							$sbody=$sbody."<option value='$svalue'>$stext</option>\n";
						}
 				
				}
		}	
		return $sheader.$sbody.$sfooter;
	}
	
	
	
	
	
	function query_select_leadingspace($value,$text,$table,$name) {
	
		$mysql_select = New mysql();
		$sql="SELECT $value,$text FROM $table order by $text";
		$sheader="<select id='$name' name='$name' $custom >\n";
		$sfooter="</select>\n";
		$sbody="<option value='' ></option>\n";
	
	  $mysql_select->query($sql);  
		  if ($mysql_select->num_rows() > 0) {
				while ($mysql_select->movenext()) { 
				$svalue= $mysql_select->getfield(0);
				$stext= $mysql_select->getfield(1);
					if($svalue==$selected){
							$sbody=$sbody."<option value='$svalue' selected>$stext</option>\n";
						}
					else
						{
							$sbody=$sbody."<option value='$svalue'>$stext</option>\n";
						}
 				
				}
		}	
		return $sheader.$sbody.$sfooter;
	}	
	
		function checkbox_($name,$value,$checked = false) {
		if($checked == $value) {
			$checked = "checked=\"checked\"";
		} else {
			$checked = "";
		}
		return "<input id=\"$name\" name=\"$name\" type=\"checkbox\" value=\"$value\" $checked />";
	}	
	
	
	
	//Value, display text,table,Select name,seletced item,selectitem=
	function query_select_progress($value,$text,$table,$name,$selected="",$var=0,$custom="") {
		$mysql_select = New mysql();
		$sql="SELECT id,name,aorder FROM $table order by aorder";
		$sheader="<select id='$name' name='$name' $custom >\n";
		$sfooter="</select>\n";
	
	  $mysql_select->query($sql);  
		  if ($mysql_select->num_rows() > 0) {
				while ($mysql_select->movenext()) { 
				$svalue= $mysql_select->getfield('aorder');
				$stext= $mysql_select->getfield('name');
				$sorder= $mysql_select->getfield('aorder');
					if($mysql_select->getfield('aorder')==$selected){
							$sbody=$sbody."<option value='$svalue' selected>$sorder-$stext</option>\n";
						}
					else
						{
							$sbody=$sbody."<option value='$svalue'>$sorder-$stext</option>\n";
						}
 				
				}
		}	
		return $sheader.$sbody.$sfooter;
	}
	
	
	
	function manual_select($name,$value,$selected="",$class="",$Options="") {
	
	$sheader="<select name='$name' id='$name' class='$class' $Options>\n";
	$sfooter="</select>\n";
	$temp_text = explode(",",$value);	
	$arrayLength = count($temp_text);

		for ($i = 0; $i < $arrayLength; $i++){
		if($temp_text[$i]==$selected){
				$sbody=$sbody."<option value='$temp_text[$i]' selected>$temp_text[$i]</option>\n";
			}
		else
			{
				$sbody=$sbody."<option value='$temp_text[$i]'>$temp_text[$i]</option>\n";
			}
		} 				
			
		return $sheader.$sbody.$sfooter;
	}	
	
	
	
///Value, display text,table,Select name,seletced item   
	function query_radio($name,$on,$off,$label1,$label2,$var,$value,$width=150) {
	   if ($var==$value){
		   $checked="checked" ; 
		   $unchecked="";
	   }else{
		   $checked="" ; 
		   $unchecked="checked";
	   }	   
			$body.="<table width='$width'>\n<tr>";
			$body.="<td><label><input type='radio' name='$name' id='$name' value='$on' $checked>$label1</label></font></td>\n";
			$body.="<td><label><input name='$name' id='$name' type='radio' value='$off' $unchecked>$label2</label></font></td>\n";
			$body.="</tr>\n";
			$body.="</table>\n";

	return $body;

	}	

	function radio($name,$value,$custom="",$class="") {
		if($class != "") {
			$class = "class=\"$class\"";
		} 
		$body.="<input type='radio' name='$name' id='$name' value='".$value."' ".$custom." $class />\n";

		return $body;

	}	
	
	function radioGroup($name="",$values=array(),$selected="",$class="",$custom="") {
		$body = "";
		foreach($values as $val) {
			$body .= "<div>";
			if($selected == $val[1]) {
				$body.="<input type='radio' checked name='$name' value='".$val[1]."' ".$custom." $class />";
			} else {
				$body.="<input type='radio' name='$name' value='".$val[1]."' ".$custom." $class />";
			}
			$body .= " " . $val[0]. "</div>";
		}
		return $body;
	}
	//name,  action, method
	function form_open($name="frm", $action="", $method="post" , $custom="") {
		$this->form_name = $name;
		return "<form name=\"$name\"  id=\"$name\" action=\"$action\" $custom method=\"$method\">\n\r";
	}
	function form_close() {
		return "</form>\n\n" . $this->validation();
	}
	//name,  action, method
	function open($name="frm", $action="", $method="post", $enctype="") {
		$this->form_name = $name;
		if($enctype != "") {
			$enctype = "enctype=\"$enctype\"";
		}
		return "<form name=\"$name\" action=\"$action\" method=\"$method\" $enctype>";
	}
	function close() {
		return "</form>" . $this->validation();
	}
	//name, value(multidimensional(text,value)), size class, custom, 
	function select($name="select",$value=array(),$selected="",$size=1,$class="",$custom="") {
		$list = "<select name=\"$name\" size=\"$size\" class=\"$class\" id=\"$name\" $custom>";
		foreach($value as $val) {
			if($selected == $val[1]) {
				$list .= "<option selected value=\"".$val[1]."\">".$val[0]."</option>";
			} else {
				$list .= "<option value=\"".$val[1]."\">".$val[0]."</option>";
			}
		}
		$list .= "</select>";
		return $list;
	}
	//name, class, custom textfield options
	function email($name="email",$value="",$custom="",$class="",$size="25",$maxlength="50",$validate=true) {
		if($validate==true) {
			$this->validation .= "frmvalidator.addValidation(\"$name\",\"maxlen=50\");\n\r
 						frmvalidator.addValidation(\"$name\",\"req\");\n\r
						frmvalidator.addValidation(\"$name\",\"email\");\n\r";
		}
		if($class != "") {
			$class = "class=\"$class\"";
		} 
		
		if($value != "") {
			$value = "value=\"$value\"";
		} 
		
		return "<input name=\"$name\" id=\"$name\" type=\"text\" size=\"$size\" maxlength=\"$maxlength\" $class $custom $value />";

	}
	
	//name, class, custom textfield options
	function password($name="pass", $class="", $custom="",$validate=true,$value = "") {
		if($validate==true) {
			$this->validation .= "frmvalidator.addValidation(\"$name\",\"maxlen=50\");\n\r
 						frmvalidator.addValidation(\"$name\",\"req\");\n\r";
		}
		if($class != "") {
			$class = "class=\"$class\"";
		} 
		
		return "<input name=\"$name\" type=\"password\" value=\"$value\" size=\"25\" maxlength=\"50\" $class $custom />\n\r";
	}
	
	//name, class, custom textfield options
	function button($name="submit",$value="Submit",$class="",$custom="") {
		if($class != "") {
			$class = "class=\"$class\"";
		} 
		return "<input name=\"$name\"  id=\"$name\" type=\"button\" value=\"$value\" $class $custom />\n\r";
	}
	
	//name, class, custom textfield options
	function submit_button($name="submit",$value="Submit",$class="",$custom="") {
		if($class != "") {
			$class = "class=\"$class\"";
		} 
		return "<input name=\"$name\" id=\"$name\"  type=\"submit\" value=\"$value\" $class $custom />\n\r";
	}
	
	//form validation script
	function validation() {
		if($this->validation != "") {
			$valid = "<script language=\"JavaScript\" type=\"text/javascript\">\n\r
			var frmvalidator = new Validator(\"".$this->form_name."\");\n\r";
			$valid .= $this->validation;
			$valid .=  "</script>";
			
			return $valid;
		}
	}
	
	//name value
	function hidden($name,$value) {
		return "<input name=\"$name\" id=\"$name\" type=\"hidden\" value=\"$value\" />\n\r";
	}
	
	//name, value, custom, class
	function text_field($name,$value,$custom="",$class="",$size="25",$maxlength="25",$validate=false) {
		if($validate==true) {
			$this->validation .= "frmvalidator.addValidation(\"$name\",\"maxlen=$maxlength\");\n\r
 						frmvalidator.addValidation(\"$name\",\"req\");\n\r";
		}
		if($class != "") {
			$class = "class=\"$class\"";
		} 
		return "<input name=\"$name\" id=\"$name\" type=\"text\" size=\"$size\" maxlength=\"$maxlength\" value=\"$value\" $custom $class />\n\r";
	}
	
	//name, value, custom, class
	function text_area($name,$value,$custom="",$class="",$maxlength=255,$cols=40,$rows=5,$validate=false) {
		if($validate==true) {
			$this->validation .= "frmvalidator.addValidation(\"$name\",\"maxlen=$maxlength\");\n\r
 						frmvalidator.addValidation(\"$name\",\"req\");\n\r";
		}
		if($class != "") {
			$class = "class=\"$class\"";
		} 
		return "<textarea name=\"$name\" id=\"$name\" cols=\"$cols\" rows=\"$rows\"  $custom $class>$value</textarea>\n\r";
	}
	
	//name, value, custom, class
	function post_code($name,$value,$custom="",$class="",$size=4,$minlength=4,$maxlength=4,$validate=true) {
		if($validate==true) {
			$this->validation .= "frmvalidator.addValidation(\"$name\",\"maxlen=$maxlength\");\n\r
 						frmvalidator.addValidation(\"$name\",\"req\");\n\r
						frmvalidator.addValidation(\"$name\",\"num\");\n\r
						frmvalidator.addValidation(\"$name\",\"minlen=$minlength\");\n\r";
		}
		if($class != "") {
			$class = "class=\"$class\"";
		} 
		return "<input name=\"$name\" id=\"$name\" type=\"text\" size=\"$size\" maxlength=\"$maxlength\" value=\"$value\" $custom $class>\n\r";
	}
	
	function checkbox($name,$value,$custom="",$class="",$checked = false) {
		if($checked == true) {
			$checked = "checked=\"checked\"";
		} else {
			$checked = "";
		}
		return "<input id=\"$name\" name=\"$name\" type=\"checkbox\" value=\"$value\" $checked $custom />";
	}
	
	function ffile($name,$custom="") {
		return "<input id=\"$name\" name=\"$name\" type=\"file\" $custom />";
	}

	//insert into database
	function DB_INSERT($table,$ignore_array=array('','ACTIONS','Submit','submit')) { 
		$array_size=count($_GET);
		$array_keys=array_keys($_GET);
		$myinsert = new mysql();	
		$fields = array();
		$values = array();
		
		for($x=0; $x< $array_size; $x++) {	
			$key=array_search($array_keys[$x], $ignore_array);
			if($key==NULL){
				array_push($fields, $array_keys[$x]);
				array_push($values, $_GET[$array_keys[$x]]);					
			}	
		}					
		
		$sql = "INSERT INTO ".$table."(".implode(",",$fields).") VALUES ( '".implode("','",$values)."')";
		$myinsert->query($sql);
		
		
		
		return "true";
	}
	
	//insert into database
	function DB_POST_INSERT($table,$ignore_array=array('','ACTIONS','Submit','submit','ADD','DEBUG','_TABLE','_TABLE_KEY','_TABLE_KEY_VALUE')) { 	
	
		$array_size=count($_POST);
		$array_keys=array_keys($_POST);
		$myinsert = new mysql();	
		$fields = array();
		$values = array();

	
		for($x=0; $x< $array_size; $x++) {	

			$key=array_search($array_keys[$x], $ignore_array);
			echo $key;
			if($key==NULL){
					if($array_keys[$x]!="ADD" && $array_keys[$x]!="ACTIONS"  && $array_keys[$x]!="Submit" && $array_keys[$x]!="submit" && $array_keys[$x]!="DEBUG" && $array_keys[$x]!="_TABLE" && $array_keys[$x]!="_TABLE" && $array_keys[$x]!="_TABLE_KEY" && $array_keys[$x]!="_TABLE_KEY_VALUE"){
				array_push($fields, $array_keys[$x]);
				array_push($values, $_POST[$array_keys[$x]]);
					}
			}	
		}					
		
		$sql = "INSERT INTO ".$table."(".implode(",",$fields).") VALUES ( '".implode("','",$values)."')";
		$myinsert->query($sql);
		
		return "true";
	}	
			
		
		
		

	function DB_UPDATE($TABLE,$TABLE_KEY,$TABLE_KEY_VALUE,$IGNORE_ARRAY=array('','ACTIONS','Submit','submit','ADD','DEBUG','_TABLE','_TABLE_KEY','_TABLE_KEY_VALUE')) {
		$array_size=count($_POST);
		$array_keys=array_keys($_POST);
		$myupdate = New mysql();
	
		
		if(isset($_POST['ACTIONS'])){
	
					for($x=0; $x< $array_size; $x++) {	
						$key=array_search($array_keys[$x], $IGNORE_ARRAY);
	
						if($key==NULL){
							if($array_keys[$x]!="ADD"){
								$sql_var.= $array_keys[$x]."= '".$_POST[$array_keys[$x]]."' ,";		
							}
							
						}	
					}
			$sql_var= rtrim($sql_var,",");
			$sql= "UPDATE ".$TABLE." SET ".$sql_var."  WHERE ".$TABLE_KEY."= '".$TABLE_KEY_VALUE."'  ";		
			$myupdate->query($sql);
			return "true";
		}
	
	}
	
	
	
		function DB_GET_UPDATE($TABLE,$TABLE_KEY,$TABLE_KEY_VALUE,$IGNORE_ARRAY=array('','ACTIONS','Submit','submit')) {
		$array_size=count($_GET);
		$array_keys=array_keys($_GET);
		$myupdate = New mysql();	
		
		if(isset($_GET['ACTIONS'])){
	
					for($x=0; $x< $array_size; $x++) {	
						$key=array_search($array_keys[$x], $IGNORE_ARRAY);
	
						if($key==NULL){
							$sql_var.= $array_keys[$x]."= '".$_GET[$array_keys[$x]]."' ,";		
						}	
					}
			$sql_var= rtrim($sql_var,",");
			$sql= "UPDATE ".$TABLE." SET ".$sql_var."  WHERE ".$TABLE_KEY."= '".$TABLE_KEY_VALUE."'  ";		
			$myupdate->query($sql);
			return "true";
		}
	}
	
	
	
	
	
	function DB_DELETE($TABLE,$TABLE_KEY,$TABLE_KEY_VALUE) {
			$mydelete = New mysql();	

			$sql="DELETE FROM ".$TABLE." WHERE ".$TABLE_KEY."= '".$TABLE_KEY_VALUE."'  ";		
			$mydelete->query($sql);
			return "true";
	}	

	

	function EMAIL_FROM($TO_EMAIL,$FROM_EMAIL,$SUBJECT,$IGNORE_ARRAY=array('','ACTIONS','Submit','submit')) {
		$array_size=count($_POST);
		$array_keys=array_keys($_POST);
		require("../../class/class.phpmailer.php");	

		
		if(isset($_POST['ACTIONS'])){
		
					for($x=0; $x< $array_size; $x++) {	
						$key=array_search($array_keys[$x], $IGNORE_ARRAY);
	
						if($key==NULL){
							if($array_keys[$x]=="HEADER"){
							$form_key.=$_POST[$array_keys[$x]]."<hr>";
							}
							else
							{
							
							$form_key.="<strong>".$array_keys[$x]."</strong> : ".$_POST[$array_keys[$x]]."<br>";
							}
						}	
					}					


		$mymail = new PHPMailer();
		$mymail->IsMail();
	
		
		$mymail->From     = $FROM_EMAIL;
		$mymail->FromName = $FROM_EMAIL;
		$mymail->AddAddress($TO_EMAIL);   
		//$mymail->AddReplyTo($my_site->get_site_contact_form_email());		
		$mymail->WordWrap = 50;		
		$mymail->IsHTML(true);           
		
		$mymail->Subject  = $SUBJECT;
		$mymail->Body     = $form_key;
		
		
		if(!$mymail->Send())
		{
		
		  $error.=EMAIL_ERROR;
		  $error.='Mailer Error: ' . $mymail->ErrorInfo;
		  return $error;
		}	
		else
		{
		  return $form_key;
		}
				
			}	
			
	}






}
?>