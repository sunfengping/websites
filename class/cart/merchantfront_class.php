<?
/***************************************************************************
 *                          merchantfront_class.php
 *                            -------------------
 *   begin                : Saturday,10/07/03
 *   copyright            : (C) 2003  Peak Software
 *   email                : chris@peaksoftware.com.au

 ***************************************************************************/
class Basket {
        var $basket_count;
        var $basket_item_id;
        var $basket_item_name;
        var $basket_item_quantity;
        var $basket_item_data;
        var $basket_item_price;
        var $basket_item_tax;
        var $basket_item_color;
        var $basket_item_size;

        function Basket() {
                $this->basket_count=0;
        }
        function Add_Item($ID,$name,$quantity=1,$price=0,$data='',$tax,$color,$size,$weight) {

                $this->basket_item_id[$this->basket_count]=$ID;
                $this->basket_item_name[$this->basket_count]=$name;
                $this->basket_item_quantity[$this->basket_count]=$quantity;
                $this->basket_item_data[$this->basket_count]=$data;
                $this->basket_item_price[$this->basket_count]=$price;
                $this->basket_item_tax[$this->basket_count]=$tax;
                $this->basket_item_color[$this->basket_count]=$color;
                $this->basket_item_size[$this->basket_count]=$size;
                $this->basket_item_weight[$this->basket_count]=$weight;

                $this->basket_count++;
                return ($this->basket_count-1);
        }
        function Del_Item($pos) {
                $this->basket_item_id[$pos]='';
        }
        function Get_Item_ID($pos) {
                return $this->basket_item_id[$pos];
        }
        function Get_Item_Name($pos) {
                return $this->basket_item_name[$pos];
        }
        function Get_Item_Price($pos) {
                return $this->basket_item_price[$pos];
        }
        function Get_Item_Quantity($pos) {
                return $this->basket_item_quantity[$pos];
        }
        function Get_Item_Data($pos) {
                return $this->basket_item_data[$pos];
        }
        function Get_Item_Tax($pos) {
		                return $this->basket_item_tax[$pos];
        }
        function Get_Item_Color($pos) {
		                return $this->basket_item_color[$pos];
        }
        function Get_Item_Size($pos) {
		                return $this->basket_item_size[$pos];
        }
        function Get_Item_Weight($pos) {
				                return $this->basket_item_weight[$pos];
        }
        function Set_Item_Quantity($pos,$quantity) {
                $this->basket_item_quantity[$pos]=$quantity;
        }
        function Set_Item_Data($pos,$data) {
                $this->basket_item_data[$pos]=$data;
        }
        function Enum_Items($start=false) {
                static $current;
                if ($current>=$this->basket_count) return -1;
                if (!$start) {
                        $current++;
                } else {
                        $current=0;
                }
                while (($this->basket_item_id[$current]=='') && ($current<$this->basket_count)) {
                        $current++;
                }
                return ($current<$this->basket_count) ? $current : -1;
        }
        function Empty_Basket() {
                $this->basket_count=0;
        }
        function Get_Basket_Count() {
            $num=0;
            for ($i=0;$i<$this->basket_count;$i++) {
                        if ($this->basket_item_id[$i]!='') $num++;
            }
            return $num;
        }
}
?>