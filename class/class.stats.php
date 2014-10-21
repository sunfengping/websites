<?
/***************************************************************************
 *                          stats.class
 *                            -------------------
 *   begin                : Saturday,10/07/03
 *   copyright            : (C) 2003  Peak Software
 *   email                : chris@peaksoftware.com.au

 ***************************************************************************/

  //$mysql_stat = New mysql();
class stats {

	  var $ip;
	  var $page;
	  var $pdate;
	  var $ptime;
	  var $ref;
 	



        function stats() {
         
        }

        function add_stats($ip,$page,$adate,$atime,$ref) {
		   $mysql_stat = New mysql();
					
			$mysql_stat->query("INSERT INTO stats (ip,page,adate,atime,ref)  values  ('$ip','$page','$adate','$atime','$ref')");			
 
       	 return "updated";

    	}



						


}
?>