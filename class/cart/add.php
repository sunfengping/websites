<?
error_reporting(0);

require("merchantfront.php");

session_name("mysession");
session_start();
if (!session_is_registered("basket")) {
	$basket=new Basket;
	session_register("basket");
}


$myid="";
$myquant="";
$zpos="";

  if ($basket->Get_Basket_Count()>0) {  # are there items in the basket
	$pos = $basket->Enum_Items(true);
	while ($pos>=0) {



           if ($basket->Get_Item_ID($pos)==$itemid)
              {
              $basket->Set_Item_Quantity($pos,($basket->Get_Item_Quantity($pos)+1));
              $addprod="no";
              }


           $myid=$basket->Get_Item_ID($pos);
           $myquant=$basket->Get_Item_Quantity($pos);
           $zpos=$pos;

           $pos = $basket->Enum_Items();
	              }
}

if ($addprod!="no")
              {
              $basket->Add_Item($itemid,"George Foreman Grill",1,19.95,"YadaYada",".0675","black","XXL","1");
              }

header("Location:shop.php");
?>