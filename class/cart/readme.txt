The Merchantfront class is a modified version of the basketlib class. 
This class was originally created by Edward Rudd (Kudos!).  Much of his
functionality is in the file merchantfront.php (the main class file).

Added Features:
* functionality to pass item color, weight, tax, and size.  (It is easy to add more as you need them.)
* ability to have a different tax on different items. (for foriegn users.)
* No seperate lines for the same item.
  - In basketlib each item was its own line.  So if your user clicked on the same product twice it would
    show up as two lines in the cart. Merchantfront fixes this and amends item quantities rather than 
    adding new lines. (This functionality is stored in add.php)

* Added a basic graphical format.
* Added ability to update quantities in cart with graphical format.

My Instructions:

*If you load shop.php (the actual basket) you will notice three "add product" links.
These links are just for testing purposes.  If you decide to use this class you
may create a catalog that will in effect have the same functionality. 

*ITEM ID's of each product MUST be unique.  If you add a product to the cart with
item ID "FORE1" and then you add another item with ID "FORE1", the program will
tell the cart that you have two of the same item, and will therefore update the 
quantity rather than add a new item.  

*Study the way the pages interact and you should get the hang of how things work.
Again, this is not very different from basketlib.php

Original Instructions: 

*************************************************************************************

To actually use the basket put ssomething like this in the beginning of your pages of the site or in a prepended included file. (auto_prepend)

session_name("mysession");
session_start();
if (! ssession_is_registered("basket") ) {
	$basket=new Basket;
	session_register("basket");
}

To add items to a basket use
$basket->Add_Item($ITEM_ID,$DISPLAY_NAME,$quantity,$price,$data,$tax,$color,$size,$weight);

the item id is the unique ID for the item added to the basket. (ie for lookup in a databasse)
name can be used for a description for the item. (possibly for pulling into a view basket)
quantity is self explanitory and defaults to one.
price is the price of the item. (this is for future functionality) defaults to 0.
data is for any extra data to be associated with the item (ie. for a card the name and message)
    just store an associated array ($data["firstname"]="Jon")

the Del_* Get_* Set_* items get and set the different fields and require a $pos identifier returned from Enum_Items (position in the basket)

when Enum_Items  is first called pass a true parameter to start the enumaration from the beginning of the basket.
ever call after that pass either nothing of false to get the next item.
It returns -1 when the end of the basket is reached.

( This procedure skips over deleted items.  so you can't just start as pos 0 and goto Get_Basket_Count)

here is sample code to enumerate the basket.

if ($basket->Get_Basket_Count()>0) {  # are there items in the basket
	$pos = $basket->Enum_Items(true);
	while ($pos>=0) {
		print $basket->Get_Item_Name($pos)."-".$basket->Get_Item_Quantity($pos)."<BR>";
		$pos = $basket->Enum_Items();
	}
}

Get_Basket_Count returns the number of undeleted items in the basket. 
(deleted items just get flagged so the data is still in the basket).

Well there it is.
If you have any comments please feel free to send them my way to eddie@omegaware.com
And if you use this on a site let me know.  I'd like to see it in action elsewere.

*************************************************************************************

If you have specific questions related to the merchantfront class please e-mail them
to joshua@net-avenue.com.  If you do get this working please let me know, I would love
to see what you have done.

