<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
<head>
	<title>Indexhibit Sites Gallery - 50 Featured Participants</title>
	<link rel="alternate" 
	      type="application/rss+xml" 
	      title="Indexhbit - 50 latest featured participants" 
	      href="http://www.indexhibit.org/xml/thelist.xml.php" />
	<link rel="stylesheet" type="text/css" href="jquery-lightbox-0.5/css/jquery.lightbox-0.5.css" media="screen" />
	<style>
	img {
		width: 300px;
		height: 240px;
		border: none;
		cursor: pointer;
	}
	#contact,
	h1 {
		font-size: 2em;
		margin: 0 0 15px 15px;
		font-family: Times;
		color: #ccc;
		letter-spacing: -1px;
	}
	#contact {
		margin-top: 15px;
	}
	#gallery a {
		margin: 0 0 5px 5px;
	}
	#contact a,
	#contact a:link,
	#contact a:visited,
	h1 a,
	h1 a:link,
	h1 a:visited {
		color: #bbb;
	}
	#contact a:active,
	h1 a:active {
		color: #aaa;
	}
	</style>

	<script type="text/javascript" src="jquery-lightbox-0.5/js/jquery.js"></script>
	<script type="text/javascript" src="jquery-lightbox-0.5/js/jquery.lightbox-0.5.js"></script>
	<script type="text/javascript">
//	$(function() {
//		$('#gallery a').lightBox(); 
//	});
	</script>
</head>
<body>
<h1><a href="http://indexhibit.org" title="Indexhibit is a web application used to build and maintain an archetypal, invisible website format that combines text, image, movie and sound.">Indexhibit</a> Sites Gallery - 50 Featured Participants</h1>
<div id="gallery">
<?php
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "http://www.indexhibit.org/xml/thelist.xml.php");
curl_setopt($ch, CURLOPT_HEADER, 0);

ob_start();
$contents = curl_exec($ch);
$data = ob_get_contents();
curl_close($ch);
ob_end_clean();

$parser = xml_parser_create('UTF-8');
xml_parser_set_option($parser, XML_OPTION_SKIP_WHITE, 1); 
xml_parse_into_struct($parser, $data, $vals, $index); 
xml_parser_free($parser);

$count = 0;
$show = 0;
for ($i = 6; $i < (count($vals) - 1); $i++) {	
	if ($show < 1) {
		$count++;
		$name = '#' . $count . ' - ' . $vals[$i]["value"];
		echo '<a href="' . $vals[$i]["value"] . '" title="' . $name . '"><img src="thumbalizr?url=' . $vals[$i]["value"] . '" alt="' . $name . '"></a>';
	}
	$show++;	
	if ($show > 6) {
		$show = 0;
	}
}

?>
</div>
<div id="contact">Made by <a href="http://geekmap.co.uk/people/b052-daniel-morris" title="Dan is a web geek, based in London, UK">Daniel Morris</a></div>
</body>
</html>