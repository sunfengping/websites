<?php
/***************************************************************************
 *                          class.mysql.php
 *                            -------------------
 *   begin                : Saturday,10/07/03
 *   copyright            : (C) 2003  Peak Software
 *   email                : chris@peaksoftware.com.au

 ***************************************************************************/
// include("../admin/config.php");
class page_link{
    var $page;//当前页码；
    var $firstcount; //（数据库）查询的起始项；
    var $total; //数据总数
    var $displaypg;//每页显示数据条数
    var $get_txt; //页码标示字段
    var $url;//页面地址
    var $lastpg;////最后页，也是总页数
    
    function page_link($total,$displaypg=30,$get_txt='page',$url=''){
		
        $lastpg=ceil($total/$displaypg);
        $this->lastpg=$lastpg;
		if(isset($_GET[$get_txt]))
		{
		  $this->page=($_GET[$get_txt]=='')?1:min($_GET[$get_txt],$lastpg);
		}
		else
		{
		  $this->page=1;
		}
        $this->total=$total;
        $this->displaypg=$displaypg;
        $this->get_txt=$get_txt;
        $this->url=($url=='')?$_SERVER["REQUEST_URI"]:$url;
        $this->firstcount=($this->page-1)*$displaypg;
     }
    
    //url网址处理函数:URL后加页码查询信息待赋值
    function get_new_url($urls){
	  // $url=$this->url;
	   $url=$urls;
	   
        $parse_url=parse_url($url);
		
		$url_query="";
		if(isset($parse_url["query"]))
		{
		  $url_query=$parse_url["query"]; //单独取出URL的查询字串
		  
		  if($url_query){
			  
			//因为URL中可能包含了页码信息，我们要把它去掉，以便加入新的页码信息。
		    $url_query=preg_replace("/(^|&)page=[0-9]$/","",$url_query);
           
		    //将处理后的URL的查询字串替换原来的URL的查询字串：
            $new_url=str_replace($parse_url["query"],$url_query,$url);
			
			

           
			//在URL后加page查询信息，但待赋值
			 if($url_query)
			 {
			   
			   $new_url.="&".$this->get_txt;
			 } 
			 else
			 {
			  $new_url.=$this->get_txt; 
			 } 
         }else {
            $new_url=$url.$this->get_txt;
         }
		}
		else
		{
		 
		  $new_url=$url.$this->get_txt;
		}
		$new_url.="=";
        return  $new_url;
     }
    
   /*
    ////输出分页导航条代码：
    function show_link(){
       $pagenav="显示第 <B>".($this->total?($this->firstcount+1):0)."</B>-<B>".min($this->firstcount+$this->displaypg,$this->total)."</B> 条记录，共 $this->total 条记录&nbsp;&nbsp;&nbsp;";

        //如果多于一页：
        if($this->lastpg >1){
            $prepg=$this->page-1; //上一页
            $nextpg=($this->page==$this->lastpg ? 0 : $this->page+1); //下一页
            $url=$this->get_new_url();
           // $pagenav.="<a href='$url=1'>第一页</a> ";
           if($prepg) $pagenav.=" <a href='$url=$prepg'>上一页</a> "; else $pagenav.=" 上一页 ";
            if($nextpg) $pagenav.=" <a href='$url=$nextpg'>下一页</a> "; else $pagenav.=" 下一页 ";
            $pagenav.=" <a href='$url=$this->lastpg'>末页</a> ";
            //下拉跳转列表，循环列出所有页码：
            $pagenav.="　到第 <select name='topage' size='1' onchange='javascript:window.location=\"$url=\"+this.value'>\n";
			
            for($i=1;$i<=$this->lastpg;$i++){
                if($i==$this->page) $pagenav.="<option value='$i' selected>$i</option>\n";
                else $pagenav.="<option value='$i'>$i</option>\n";
             }
            $pagenav.="</select> 页，共 $this->lastpg 页";
         }
        return $pagenav;
     }
	 */
	 
	 ////输出分页导航条代码：
    function show_link($urls,$currentPage){
		$this->page=$currentPage;
		$pagenav="";
      // $pagenav='<li class=""><a href="$url=1">first</a></li>';

        //如果多于一页：
        if($this->lastpg >1){
            $prepg=$this->page-1; //上一页
            $nextpg=($this->page==$this->lastpg ? 0 : $this->page+1); //下一页
            $url=$this->get_new_url($urls);
           if($prepg) $pagenav.='<li class=""><a href="'.$url.$prepg.'"><i class="fa fa-angle-left"></i></a></li> ';
		    for($i=1;$i<=$this->lastpg;$i++){
                if($i==$this->page) $pagenav.='<li class="active"><a href="'.$url.$i.'">'.$i.'</a></li>';
                else $pagenav.='<li><a href="'.$url.$i.'">'.$i.'</a></li>';
             }
            if($nextpg) $pagenav.='<li class=""><a href="'.$url.$nextpg.'"><i class="fa fa-angle-right"></i></a></li>';
           // $pagenav.='<li class=""><a href="$url=$this->lastpg">last</a></li>';
            
			
           
           // $pagenav.=",total $this->lastpg items";
         }
        return $pagenav;
     }
	 
	 
}
?>
