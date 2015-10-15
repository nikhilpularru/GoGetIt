<!-- 
    Author: Nikhil Pularru
    B. Tech. (Computer Science)
    3rd Year Student At Shiv Nadar University
-->
<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" type="text/css" href="bootstrap/css/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="bootstrap/css/index.css">
</head>
<body>
<div class="container font-decoration">

<?php    
function scrape_between($data, $start, $end){
        $data = stristr($data, $start); // Stripping all data from before $start
        $data = substr($data, strlen($start));  // Stripping $start
        $stop = stripos($data, $end);   // Getting the position of the $end of the data to scrape
        $data = substr($data, 0, $stop);    // Stripping all data from after and including the $end of the data to scrape
        return $data;   // Returning the scraped data from the function
    }

include('simple_html_dom.php');
$srch = $_REQUEST["srch"];
$region=$_REQUEST["region"];
$near=$_REQUEST["near"];
$page=$_REQUEST["page"];

$html = new simple_html_dom();
$count=0;
if($page==1)
	$html->load_file("http://www.justdial.com/".$region."/".$srch."-%3Cnear%3E-".$near);
else $html->load_file("http://www.justdial.com/".$region."/".$srch."-%3Cnear%3E-".$near."/page-".$page);
if(!empty($html)){
	$element=$html->find(".jgbg");
	if(empty($element))
		$element=$html->find(".jbbg");

	if(empty($element)){
		if($page==1){
			echo "<b>No Information Available.</b>";
		}
		else {echo "<b>No More Information Available.</b>";}
	$count=1;
	}
}
		
?>
<table class="table table-striped table-bordered table-hover table-condensed" >
<tr>
<th>NAME</th>
<th>ADDRESS</th>
<th>PHONE NO.</th>
<th>MAP</th>
</tr>
<?php
if($count==0){
foreach($element as $data){
	$name = $data->find(".jcnwrp");
	$add=$data->find(".mrehover");
	$phn=$data->find(".jrcw");
	$cood=$data->find(".rsmap");
	?>
	<tr>
	<td>
	<?php
	if(!empty($name))
		echo strip_tags($name[0])."<br>";
	else echo "NA";
	?>
	</td>
	<td>
	<?php
	if(!empty($add))
		echo $add[0]."<br>";
	else echo "NA";
	?>
	</td>
	<td>
	<?php
	if(!empty($phn))
		echo strip_tags($phn[0])."<br>";
	else echo "NA";
	?>
	</td>
	<td>
	<?php
	if(!empty($cood)){
		$scraped_data = scrape_between($cood[0], "Delhi", ")");
		$temp1=substr($scraped_data,4,15);
		$temp2=substr($scraped_data,23,15);
		$float1=floatval($temp1);
		$float2=floatval($temp2);
		$dN=intval($float1);
		$mN=intval(($float1-$dN)*60);
		$sN=floatval(($float1-$dN-($mN/60))*3600);
		$dE=intval($float2);
		$mE=intval(($float2-$dE)*60);
		$sE=floatval(($float2-$dE-($mE/60))*3600);
		?>
		<a href=<?php echo "https://www.google.co.in/maps/place/".$dN."&#176;".$mN."'".round($sN,2)."\"N+".$dE."&#176;".$mE."'".round($sE,2)."\"E"; ?> target="_blank"> Show on Map</a>
	<?php
		}
	else echo "NA";
	?>
	</td>
	<?php
	}
}
?>
</tr>
</table>
</div>
</body>
</html>