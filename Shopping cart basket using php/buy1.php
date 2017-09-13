<html>
<head><title>Buy Products</title></head>
<body>
<?php
error_reporting(E_ALL);
ini_set('display_errors','On');
$xmlstr = file_get_contents('http://sandbox.api.ebaycommercenetwork.com/publisher/3.0/rest/GeneralSearch?apiKey=78b0db8a-0ee1-4939-a2f9-d3cd95ec0fcc&trackingId=7000610&keyword=samsung+i7&category=72');
$xml = new SimpleXMLElement($xmlstr);
header('Content-Type: text/xml');
print $xmlstr;
?>
</body>
</html>
