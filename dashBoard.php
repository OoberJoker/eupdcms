<?php
//composer require andreskrey/readability.php
//use andreskrey\Readability\Readability;

namespace andreskrey\Readability\dashBoard;
//require("/opt/lampp/htdocs/Readability.php");

//require("/opt/lampp/htdocs/Configuration.php");

//require("/opt/lampp/htdocs/ParseError.php");
include 'vendor/autoload.php';
use andreskrey\Readability\Readability;
use andreskrey\Readability\Configuration;
use andreskrey\Readability\ParseException;
 
$readability = new Readability(new Configuration());

session_start();
// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}
//'Mozilla/5.0 (Windows NT 6.3; Win64; x64; rv:10.0) Gecko/20100101 Firefox/10.0'
$context = stream_context_create(
    array(
	    "http" => array(
	     "method" =>"GET",    
            "header" => "User-Agent: Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/50.0.2661.102 Safari/537.36"
        )
    )
);

$newsData="";
$country="";
if(isset($_GET['country'])){
	$country= $_GET['country'];
	$contents = file_get_contents("https://www.pv-magazine.com/?s=".$country, false, $context);
	

	try {
    	$readability->parse($contents);
    	$newsData= $readability->getContent();
	} catch (ParseException $e) {
    		echo sprintf('Error processing text: %s', $e->getMessage());
	}

}
else{

	$country="";	
}

//$rawData = strip_tags($contents,"<h2><div>");
//echo $rawData;
//$filteredData = substr($rawData, strpos($rawData, "Search results for") + 1);    
//var_dump($filteredData);


?>

<?php echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>";?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<style>
span.a {
  display: inline; /* the default for span */
  width: 100px;
  height: 100px;
  padding: 5px;
  border: 1px solid blue;
  background-color: yellow;
}

span.b {
  display: inline-block;
  width: 40%;
  height: 50px;
  padding: 5px;
}

span.map {
  display: block;
  width: 40%;
  height: 50px;
  padding: 5px;
}

.collapsible {
  background-color: rgb(55, 115, 170);
  color: white;
  cursor: pointer;
  padding: 18px;
  width: 100%;
  border: none;
  text-align: left;
  outline: none;
  
/*  margin-left: 40%;*/
  font-size: 15px;
/*	font-weight: bold;*/
    height: 45px;
    padding-top: 10px;
}

.active, .collapsible:hover {
  background-color: rgb(0,75,160);
}

.collapsible:after {
  content: '\002B';
  color: rgb(255, 255, 255);
  /*font-weight: bold;*/
  float: right;
  margin-left: 5px;
  font-size: 20px;
}

.active:after {
  content: '\2212';
}

.content {
  padding: 0 18px;
  max-height: 0;
  overflow: hidden;
  transition: max-height 0.2s ease-out;
  background-color: #f1f1f1;
  width: 133%;
  /*margin-left: 40%;*/
}

#main {
    font-family: arial;
}
.btn {
    display: inline-block;
    padding: 6px 12px;
    margin-bottom: 0;
    font-size: 14px;
    font-weight: normal;
    line-height: 1.42857143;
    text-align: center;
    white-space: nowrap;
    vertical-align: middle;
    -ms-touch-action: manipulation;
    touch-action: manipulation;
    cursor: pointer;
    -webkit-user-select: none;
    -moz-user-select: none;
    -ms-user-select: none;
    user-select: none;
    background-image: none;
    border: 1px solid transparent;
    border-radius: 4px;
}

.btn-primary {
    color: #fff;
    background-color: rgb(55,115,170);
    border-color: #2e6da4;
}
</style>
<script src="https://code.jquery.com/jquery-1.12.4.min.js"></script>
<script src="https://requirejs.org/docs/release/2.3.5/minified/require.js"></script>
<link rel="stylesheet" type="text/css" href="cssmap-continents/cssmap-continents/cssmap-continents.css" media="screen" />

<script>


function filter(selectedValue,comingFrom,nonReload){
	var op="";
	if(comingFrom=='dropdown'){
         	op = selectedValue.options[selectedValue.selectedIndex];
		op = op.text;
		if(op=="North America"){
                        op="North-America";
                }
                else if(op=="South America"){
                        op="South-America";
                }
	}
	else if(comingFrom=='map'){
		op= $(selectedValue).text();
		if(op=="North America"){
			op="North-America";
		}
		else if(op=="South America"){
			op="South-America";
		}
		//$("continentsDropdown")[0].selectedIndex=0;
		//
		var hash={'Africa':0,'Asia':1,'Australia':2,'Europe':3,'North-America':4,'South-America':5};
		console.log("selected->"+hash[op]);
		$("#continentsDropdown")[0].selectedIndex=hash[op];
		    
		
		console.log("this->"+hash["Asia"]);
		console.log("check->"+$("continentsDropdown")[0]);

	}
	console.log("->"+op);
	var continents=["Africa","Asia","Australia","Europe","North-America","South-America"];
	var i;
	for(i=0;i<continents.length;i++){
		if(op != continents[i]){
		  $("#country-optgroup-"+continents[i]).hide();	
		}
		else{
	  	  $("#country-optgroup-"+op).show();
		  // $("#country-optgroup-"+op)[0].selectedIndex=0

		}
	}
	if(nonReload==1){
	
		  $("#country")[0].selectedIndex=0
	}
	//$("#country-optgroup-"+op.text).show();	
	    
}
function submitToPage(selectBox) {
	var op = selectBox.options[selectBox.selectedIndex];
	var optgroup = op.parentNode;
	window.location.href = "http://3.15.176.57/dashBoard.php?country="+op.text+"&continent="+optgroup.label;
}


function swapDivs(showMap){
        document.getElementsByTagName('body')[0].onload = filter(document.getElementById("continents"));
        var url = new URL(window.location.href);
	var country = url.searchParams.get("country");
	console.log(showMap);
		if(showMap){
		 	$("#expandingDiv").hide();
			$("#map-continents").show();
			$("#showMapButton").hide();
		}
		else{
                	if(!country){
                        	country=null;
                	}
             		if(country == null){
                        	$("#expandingDiv").hide();
				$("#map-continents").show();
//				$("#showMapButton").show();
			}
                	else{
                        	$("#expandingDiv").show();
				$("#map-continents").hide();

				$("#showMapButton").show();
			}
		}
}

</script>
<meta name="google" content="notranslate">
<meta content="text/html; charset=utf-8" http-equiv="Content-Type">
</head>
<body id="mybody">
<!-- jQuery -->
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>

<!-- CSSMap SCRIPT -->
<script type="text/javascript" src="https://cssmapsplugin.com/5/jquery.cssmap.min.js"></script>

<script type="text/javascript">
$(document).ready(function(){

// CSSMap;
$("#map-continents").CSSMap({
"size": 960,
"mapStyle": "blue"
	// OTHER OPTIONS;
});
// END OF THE CSSMap;

});
function waitForAddedNode(params) {
    new MutationObserver(function(mutations) {
        var el = document.getElementById(params.id);
        if (el) {
           // this.disconnect();
            params.done(el);
        }
    }).observe(params.parent || document, {
        subtree: !!params.recursive,
        childList: true,
    });
	
}

waitForAddedNode({
    id: 'cssmap-tooltip',
    parent: document.querySelector('#mybody'),
    recursive: false,
    done: function(el) {
        //console.log(el);
		
		var html = $(el).filter('#cssmap-tooltip').html();
		console.log(html);
    }
});

</script>

<div id="main"  style=""  >

<!--<div id="logoutDiv" style="float: right;"><b><a href="logout.php" style="color: black;font-size: 20px;">Logout</a></b>-->
<input type="submit" class="btn btn-primary" value="Logout" style="float: right; margin-right: 5px;" onclick="window.location.href = 'logout.php';">

</div>
<div id="logo"> 
<a href="http://3.15.176.57/dashBoard.php"><img src="https://www.eupd-research.com/fileadmin/system/system-dateien/EuPD_Research-Logo.jpg" style="width: 180px;height: auto; margin-top: 7px;" title="EuPD Research" alt="EuPD Research">
</a>
</div>
 
<br></br>
<div id="welcomeNote" style="font-size: 15px; font-family: arial; color: rgb(55,115,170); height: 40px; background: #337ab7;color: white;"><span class="b" style="">Welcome Parag</span> <span class="b" style="width: 50%; margin-top: 5px;">Company Name: APSUN Power | Logged in: 9th Sept 2019 14:00 CET</span></div>

<div id="continetDropDown" style="margin-top: 10px; font-size: 18px; font-family: arial;" >

<span class="b" style="font-size: 15px;">Select Continent

	<select style="margin-left: 10px; height: 22px; font-family: arial; width: 200px;" id="continentsDropdown" onChange="filter(this,'dropdown',1)">
		  <option value="Africa" <?php if(isset($_GET['continent']) && $_GET['continent']=="Africa"){echo "selected='selected'";}  ?>>Africa</option>
		  <option value="Asia" <?php if(isset($_GET['continent']) && $_GET['continent']=="Asia"){echo "selected='selected'";}  ?> >Asia</option>
 		 <option value="Australia" <?php if(isset($_GET['continent']) && $_GET['continent']=="Australia"){echo "selected='selected'";}  ?> >Australia</option>
 		 <option value="Europe"  <?php if(isset($_GET['continent']) && $_GET['continent']=="Europe"){echo "selected='selected'";}  ?>>Europe</option>
 		 <option value="North-America" <?php if(isset($_GET['continent']) && $_GET['continent']=="North America"){echo "selected='selected'";}  ?> >North America</option>
 		 <option value="South-America" <?php if(isset($_GET['continent']) && $_GET['continent']=="South America"){echo "selected='selected'";}  ?> >South America</option>
	</select>
</span>

<span class="b" style="font-size: 15px;">
Select Country
<select name="country" id="country" style="margin-left: 10px; height: 22px; font-family: arial; width: 200px;" onChange="submitToPage(this);">
<option value="0" label="Select a country … " selected="selected">Select a country …       </option>
<optgroup id="country-optgroup-Africa" label="Africa">
<option value="DZ" label="Algeria"<?php if($country=="Algeria"){echo "selected='selected'";}  ?>>Algeria</option>
<option value="AO" label="Angola" <?php if($country=="Angola"){echo "selected='selected'";}  ?>>Angola</option>
<option value="BJ" label="Benin"  <?php if($country=="Benin"){echo "selected='selected'";}  ?>>Benin</option>
<option value="BW" label="Botswana"  <?php if($country=="Botswana"){echo "selected='selected'";}  ?>>Botswana</option>
</optgroup>
<optgroup id="country-optgroup-North-America" label="North America" style="display: none;">
<option value="CA" label="Canada"  <?php if($country=="Canada"){echo "selected='selected'";}  ?>>Canada</option>
</optgroup>

<optgroup id="country-optgroup-South-America" label="South America" style="display: none;">
<option value="BR" label="Brazil"  <?php if($country=="Brazil"){echo "selected='selected'";}  ?>>Brazil</option>
</optgroup>

<optgroup id="country-optgroup-Asia" label="Asia" style="display: none;">
<option value="KH" label="Cambodia"  <?php if($country=="Cambodia"){echo "selected='selected'";}  ?> >Cambodia</option>
<!--<option value="CN" label="China"  <?php //if($country=="China"){echo "selected='selected'";}  ?>>China</option>-->
<option value="IN" label="India"  <?php if($country=="India"){echo "selected='selected'";}  ?>>India</option>
<option value="ID" label="Indonesia"  <?php if($country=="Indonesia"){echo "selected='selected'";}  ?>>Indonesia</option>
<option value="IL" label="Israel"  <?php if($country=="Israel"){echo "selected='selected'";}  ?>>Israel</option>
<!--<option value="JP" label="Japan"  <?php //if($country=="Japan"){echo "selected='selected'";}  ?>>Japan</option>-->
</optgroup>
<optgroup id="country-optgroup-Europe" label="Europe" style="display: none;">
<option value="DK" label="Denmark"  <?php if($country=="Denmark"){echo "selected='selected'";}  ?>>Denmark</option>
<option value="FR" label="France"  <?php if($country=="France"){echo "selected='selected'";}  ?>>France</option>
<option value="DE" label="Germany"  <?php if($country=="Germany"){echo "selected='selected'";}  ?>>Germany</option>
<!--<option value="IT" label="Italy"  <?php //if($country=="Italy"){echo "selected='selected'";}  ?>>Italy</option>-->
<option value="LT" label="Lithuania"  <?php if($country=="Lithuania"){echo "selected='selected'";}  ?>>Lithuania</option>
<option value="NL" label="Netherlands"  <?php if($country=="Netherlands"){echo "selected='selected'";}  ?>>Netherlands</option>
<!--<option value="ES" label="Spain"  <?php //if($country=="Spain"){echo "selected='selected'";}  ?>>Spain</option>-->
</optgroup>
<optgroup id="country-optgroup-Australia" label="Australia" style="display: none;">
<option value="AU" label="Australia"  <?php if($country=="Australia"){echo "selected='selected'";}  ?>>Australia</option>
<!--<option value="GU" label="Guam"  <?php //if($country=="Guam"){echo "selected='selected'";}  ?>>Guam</option>-->
<option value="TO" label="Tonga"  <?php if($country=="Tonga"){echo "selected='selected'";}  ?>>Tonga</option>
</optgroup>
</select>

</span>
 <span id="showMapButton" style="display: none;">
<button  id="showMapButtonId" class="btn btn-primary" value="Show Map" style=" float: right; margin-right: 5px;" onclick="swapDivs(true); ">Show Map</button>
</span>
</div>


<div id="countryData">

<!--<center><img src="img/worldmap.png" style="width: 80%; height: 550px"></center>-->
<div id="map-continents">
 <ul class="continents">
  <li class="c1" onmousedown="filter(this,'map',1);"><a href="#africa">Africa</a></li>
  <li class="c2" onmousedown="filter(this,'map',1);"><a href="#asia">Asia</a></li>
  <li class="c3" onmousedown="filter(this,'map',1);"><a href="#australia">Australia</a></li>
  <li class="c4" onmousedown="filter(this,'map',1);"><a href="#europe">Europe</a></li>
  <li class="c5" onmousedown="filter(this,'map',1);"><a href="#north-america">North America</a></li>
  <li class="c6" onmousedown="filter(this,'map',1);"><a href="#south-america">South America</a></li>
 </ul>
</div>

<div id="expandingDiv">

<script>swapDivs(false);</script>
<span class="countryMap" id="countryImages">
<?php
if($country=="Algeria"){
 echo "<center><iframe src=\"jqvmap-master/examples/algeria.html\" width=\"650\" height=\"420\"></iframe></center>";
}
if($country=="Angola"){
 echo "<center><img src=\"https://d2gg9evh47fn9z.cloudfront.net/800px_COLOURBOX11394466.jpg\" style=\"height: 400px;\"></center>";
}
if($country=="Benin"){
 echo "<center><img src=\"https://www.iconspng.com/images/benin-flag-map/benin-flag-map.jpg\" style=\"height: 400px;\"></center>";
}
if($country=="Botswana"){
 echo "<center><img src=\"http://www.offthetrack.co.zw/wp-content/uploads/2016/04/Botswana-Map.png\" style=\"height: 400px;\"></center>";
}
if($country=="Cambodia"){
 echo "<center><img src=\"https://images-na.ssl-images-amazon.com/images/I/61tc1kRyrTL._SY500_.jpg\" style=\"height: 400px;\"></center>";
}
if($country=="India"){
 echo "<center><img src=\"https://www.kidzone.ws/geography/india/states/images/map.gif\" style=\"height: 400px;\"></center>";
}

if($country=="Israel"){
 echo "<center><img src=\"https://ibt.org.il/images/mapeng.png\" style=\"height: 400px;\"></center>";
}

if($country=="Australia"){
 echo "<center><img src=\"https://www.cairnsholidayspecialists.com.au/shared_resources/media/gos5c45m34vux8_520x417.gif\" style=\"height: 400px;\"></center>";
}

if($country=="Tonga"){
 echo "<center><img src=\"https://simplemaps.com//static/svg/to/to.svg\" style=\"height: 400px;\"></center>";
}

if($country=="Denmark"){
 echo "<center><img src=\"https://img.posterlounge.co.uk/img/products/670000/661391/661391_poster_l.jpg\" style=\"height: 400px;\"></center>";
}

if($country=="France"){
 echo "<center><img src=\"https://www.conceptdraw.com/How-To-Guide/picture/Geo-map-europe-france.png\" style=\"height: 400px;\"></center>";
}
if($country=="Germany"){
 echo "<center><img src=\"https://thumbs.dreamstime.com/b/germany-map-germany-flag-germany-map-germany-flag-use-background-drawing-illustration-germany-map-germany-flag-139947689.jpg\" style=\"height: 400px;\"></center>";
}

if($country=="Lithuania"){
 echo "<center><img src=\"https://png2.cleanpng.com/sh/624ac58064fd5e8f9aeb62317f65d989/L0KzQYm3V8I2N6Zui5H0aYP2gLBuTfxqfJl6edDyYT3vebb7lgZwe15wfd5yYXmwfbL3TgJwaZUyjORqboPzf8P7TgRwdJ0yitHqZD24coaBWcY2bmg5SaZtMz66QoG8VsA1OGI6S6QAM0K2QYq7V8U2NqFzf3==/kisspng-lithuania-lietuvos-keliai-map-road-transport-toll-road-5b58965f7414d3.7205604015325323194755.png\" style=\"height: 400px;\"></center>";
}
if($country=="Netherlands"){
 echo "<center><img src=\"https://cdn.pixabay.com/photo/2014/04/02/10/18/netherlands-303419_960_720.png\" style=\"height: 400px;\"></center>";
}

if($country=="Canada"){
 echo "<center><img src=\"https://cdn.shopify.com/s/files/1/0827/7859/products/canada-map-print-MapsAsArt-cc2-4_1800x1800.png?v=1552078560\" style=\"height: 400px;\"></center>";
}
if($country=="Brazil"){
 echo "<center><img src=\"https://cdn.shopify.com/s/files/1/0827/7859/products/brazil-map-print-MapsAsArtNEW-cc-9_1800x1800.png?v=1551991330\" style=\"height: 400px;\"></center>";
}
if($country=="Indonesia"){
 echo "<center><img src=\"https://us.123rf.com/450wm/cavestudios/cavestudios1710/cavestudios171000161/87966450-indonesia-map-detailed-visualization-for-country-place-travel-texture-and-background.jpg?ver=6\" style=\"height: 400px;\"></center>";
}
?>

<!--</span>-->

<!--<span class="b" id="expandingData">-->
<button class="collapsible">Economic Development Parameters</button>
<div class="content">
  <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>
</div>
<button class="collapsible">Electricity Parameters</button>
<div class="content">
  <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>
</div>
<button class="collapsible">Political Parameters</button>
<div class="content">
  <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>
</div>
<button class="collapsible">Solar PV Parameters</button>
<div class="content">
  <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>
</div>
<button class="collapsible">Miscellaneous</button>
<div class="content">
  <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>
</div>
<button class="collapsible">Top News</button>
<div class="content" style="font-style: arial;">
  <p><?php echo $newsData; ?></p>
</div>
<button class="collapsible">Tenders</button>
<div class="content">
  <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>
</div>

</div>
	
</span>

</div>
</div>
<script>

var coll = document.getElementsByClassName("collapsible");
var i;

for (i = 0; i < coll.length; i++) {
  coll[i].addEventListener("click", function() {
  this.classList.toggle("active");
    var content = this.nextElementSibling;
    if (content.style.maxHeight){
      content.style.maxHeight = null;
    } else {
      content.style.maxHeight = content.scrollHeight + "px";
    }
  });
}
</script>

</body>
</html>

