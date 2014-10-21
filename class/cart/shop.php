<?

error_reporting(0);

require("merchantfront.php");

session_name("mysession");
session_start();
if (!session_is_registered("basket")) {
	$basket=new Basket;
	session_register("basket");
}

// Perform Cart Actions
if (isset($A))
{

if ($A=="Remove")
   {
   $basket->Del_Item($P);
   }
}


// End Cart Actions

if ($A=="Add") {
$basket->Add_Item("FORE2","George Foreman Grill",1,19.95,"YadaYada",".0675","black","XXL");
               }
// $basket->Set_Item_Quantity(0,2);
?>

<html>

<head>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
<meta name="GENERATOR" content="Microsoft FrontPage 4.0">
<meta name="ProgId" content="FrontPage.Editor.Document">
<title>New Page 1</title>
<style>
<!--
.netscape6problem		{ font-family: Verdana,Arial,Helvetica; font-size: 10px; }
.smallnormal			{ font-family: Verdana,Arial,Helvetica; font-size: 10px; }
.smallnormaltable		{ font-family: Verdana,Arial,Helvetica; font-size: 10px; color: black; }
.smallnormaltablealt		{ font-family: Verdana,Arial,Helvetica; font-size: 10px; color: red; }
.mednormal				{ font-family: Verdana,Arial,Helvetica; font-size: 12px }
.mednormaltable		{ font-family: Verdana,Arial,Helvetica; font-size: 12px; color: black; }
.mednormalhighlight		{ font-family: Verdana,Arial,Helvetica; font-size: 12px; color: black; background: yellow; }
.medbold				{ font-family: Verdana,Arial,Helvetica; font-size: 12px; font-weight: bold }
.medboldalt				{ font-family: Verdana,Arial,Helvetica; font-size: 12px; font-weight: bold; color: red; }
.medboldtable			{ font-family: Verdana,Arial,Helvetica; font-size: 12px; font-weight: bold; color: black; }
.largebold				{ font-family: Verdana,Arial,Helvetica; font-size: 18px; font-weight: bold }
.smallcompact			{ font-family: Verdana,Arial,Helvetica; font-size: 10px }
.smallbold				{ font-family: Verdana,Arial,Helvetica; font-size: 10px; font-weight: bold }
.smallboldtable			{ font-family: Verdana,Arial,Helvetica; font-size: 10px; font-weight: bold; color: black; }
.smallboldtablealt		{ font-family: Verdana,Arial,Helvetica; font-size: 10px; font-weight: bold; color: #99CCFF; }
.smallboldtabletop		{ font-family: Verdana,Arial,Helvetica; font-size: 10px; font-weight: bold; color: black; }
.smallboldtemplate		{ font-family: Verdana,Arial,Helvetica; font-size: 10px; font-weight: bold; color: black;}
.smallboldfooter			{ font-family: Verdana,Arial,Helvetica; font-size: 10px; color: #999999;}
.locationbar				{ font-family: Verdana,Arial,Helvetica; font-size: 10px; color: black; }

A:link					{text-decoration: underline; color: black; }
A:visited				{text-decoration: underline; color: black; }
A:hover					{text-decoration: underline; color: red; }

A.minibasket:link		{text-decoration: underline; color: blue; }
A.minibasket:visited		{text-decoration: underline; color: blue; }
A.minibasket:hover		{text-decoration: underline; color: red; }

A.table:link				{text-decoration: underline; color: blue; }
A.table:visited			{text-decoration: underline; color: blue; }
A.table:hover			{text-decoration: underline; color: red; }

A.templatemenu:link			{text-decoration: none; color: white; }
A.templatemenu:visited		{text-decoration: none; color: white; }
A.templatemenu:hover		{text-decoration: underline; color: red; background: #FFDD00; }


A.locationlink:link		{text-decoration: underline; color: black; }
A.locationlink:visited		{text-decoration: underline; color: black; }
A.locationlink:hover		{text-decoration: underline; color: red; }

table.minibasket			{background-color: white; }
tr.minibasketline 		{background-color: #EEEEEE; }

hr		{ height: 1; color: #DDDDDD }
pre		{ font-family: Courier; font-size: 10px; }
select	{ font-family: Verdana; font-size: 10px; color: black; background-color: white; border-color: #DDDDDD; }

table.mainpage				{background-color: white; }
tr.mainpagetableline 			{background-color: #99CCFF; }
tr.mainpagetableline2 		{background-color: #99CCFF; }
tr.mainpagetablespecial		{background-color: #DDDDDD; }
tr.mainpageversionhighlight	{background-color: #FFFF00; }

.buttonstyle	{font-family: Verdana; font-size: 10px; font-weight: bold; color: #666666; background-color: #DDDDDD; border-style: outset; border-color: #999999; border-width: 1px }
-->
</style>
</head>
<body>


<table class="mainpage" cellSpacing="0" cellPadding="5" width="100%" border="0">
  <tbody>
    <tr>
      <td>
        <div align="center">
          <center>
        <table cellSpacing="0" cellPadding="1" width="75%" border="0">
          <tbody>
            <tr>
              <td width="95"></td>
              <td width="252"></td>
              <td class="smallboldtabletop" align="right" width="66">price ($)</td>
              <td class="smallboldtabletop" align="right" width="59">
                <p align="center">&nbsp;&nbsp;&nbsp; qty</p>
              </td>
              <td width="57"></td>
            </tr>
<form method="post" action="shop.php?A=Update">
            <?
            $count=0;
            $myid="";
            $myquantity="";
            if ($basket->Get_Basket_Count()>0) {  # are there items in the basket
	$pos = $basket->Enum_Items(true);
	while ($pos>=0) {
	    // Update Cart Quantities
         if (isset($A)) {
          if ($A=="Update")
		     {
		     $myvalue=$howmany[$pos];
		     $basket->Set_Item_Quantity($pos,$howmany[$pos]);
		     }
                     }

           $mytext="<tr class='mainpagetableline2'>";
	       $mytext.="<td class='medboldtable' colSpan='6' width='614'> ".$basket->Get_Item_Name($pos)." - ".$basket->Get_Item_Color($pos)." - ".$basket->Get_Item_Size($pos)."</td>";
	        $mytext.="</tr><tr>";
	        $mytext.="<td class='smallnormaltable' vAlign='center' width='95'><a href='product.php?product=WHATEVER&V=27&ph=basket'>See Item</a></td>";
            $mytext.="<td class='smallnormaltable' vAlign='center' align='left' width='252'>".$basket->Get_Item_ID($pos)."</td>";
           $mytext.="<td class='smallboldtable' vAlign='center' align='right' width='66'>".$basket->Get_Item_Price($pos)."</td>";
           $mytext.="<td vAlign='center' align='right' width='59'><input size='3' value='".$basket->Get_Item_Quantity($pos)."' name='howmany[$pos]'></td>";
           $mytext.="<td class='smallnormaltable' vAlign='center' align='right' width='57'><a href='shop.php?A=Remove&P=".$pos."'>Remove</a></td>";
           $price=$basket->Get_Item_Price($pos);
           $quantity=$basket->Get_Item_Quantity($pos);
           $total=number_format(($total + ($price*$quantity)), 2, '.', '');
          print $mytext;
		$pos = $basket->Enum_Items();
	}
}

           if (!isset($pos)) { ?>
           <align="center">No Items in Cart</align>
           <? }

?>


          </tbody>
        </table><BR>
              <table width="75%">
              <TR>
              <td class="smallboldtabletop" align="right" width="100%">Total($): $<? print $total; ?></td>
            </tr>
            </table><BR>
        <input type="submit" name="submit" value="Update Quantities">
<form><BR><BR>
<a href="add.php?A=Add&itemid=FORE2">Add Product - FORE 2</a>
<BR><BR>
<a href="add.php?A=Add&itemid=FORE1">Add Product - FORE 1</a>
<BR><BR>
<a href="add.php?A=Add&itemid=FORE3">Add Product - FORE 3</a>
          </center>
        </div>
      </td>
    </tr>
    <a href="Copy%20of%20shop.php">
      </tbody>
      click here</a>
  
</table>
<p align="center">&nbsp;</p>
</body>

</html>
