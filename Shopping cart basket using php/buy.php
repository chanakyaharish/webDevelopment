<html>
<head><title>Buy Products</title></head>
<body>
<?php





session_start();

$price = 0;

if(isset($_REQUEST['buy'])){
$pid=$_REQUEST['buy'];
$_REQUEST['buy'] = null;
if(empty($_SESSION['pid1'])){
$_SESSION['pid1'][]=$pid;
        foreach($_SESSION['cart'] as $key=>$list){
        if($list['pid']==$pid){
			
	?>
	
	
	<table border=1>
	
	
	<tr>
	
	
	<td><?=$list['name']?></td>
     <td><?=$list['Min Price']?></td>
	 <?php
	 
		$price1=abs($list['Min Price']);
		
		?>
	<td><a href="buy.php?delete=<?=$pid?>">Delete items</a>
        echo "</tr>";
        echo "</table>";
        }
		$price = $price1;
}        
}else{
if(!in_array($pid,$_SESSION['pid1'])){
if(!in_array($pid, $_SESSION['delpid'])){$_SESSION['pid1'][]=$pid;
		 echo "<table border=1>";
        foreach($_SESSION['pid1'] as $idd){
        foreach($_SESSION['cart'] as $key=>$list1){
        if($list1['pid']== $idd){
        echo "<tr>";
        echo "<td><a href=".$list1['OfferURL']."><img src=".$list1['Img_URL']."></a></td>"; 
        echo "<td>".$list1['name']."</td>";
        echo "<td>".$list1['Min Price']."</td>";
		$price1=abs($list1['Min Price']);
		echo "<td><a href='buy.php?delete=".$idd."'>Delete</a>";
        echo "</tr>";
		 $price+= $price1;
       } 
	  
}
}
echo "</table>";}
else{
		echo "<table border=1>";
        foreach($_SESSION['pid1'] as $idd){
        foreach($_SESSION['cart'] as $key=>$list1){
        if($list1['pid']== $idd){
        echo "<tr>";
        
        echo "<td>".$list1['name']."</td>";
        echo "<td>".$list1['Min Price']."</td>";
		$price1=abs($list1['Min Price']);
		echo "<td><a href='buy.php?delete=".$idd."'>Delete</a>";
        echo "</tr>";
		$price+=$price1;
       } 
	   
}
}
}
}else{
echo "<table border=1>";
	foreach($_SESSION['pid1'] as $idd){
        foreach($_SESSION['cart'] as $key=>$list){
        if($list['pid']==$idd){
        
        echo "<tr>";
        echo "<td><a href=".$list['OfferURL']."><img src=".$list['Img_URL']."></a></td>"; 
        echo "<td>".$list['name']."</td>";
        echo "<td>".$list['Min Price']."</td>";
		$price1=abs($list['Min Price']);
		echo "<td><a href='buy.php?delete=".$pid."'>Delete</a>";
        echo "</tr>";
       $price+=$price1;
       } 
	   
}
}
 echo "</table>";
}
}
}
elseif(isset($_GET['clear'])){
unset($_SESSION['pid1']);
unset($_SESSION['cart']);
unset($_SESSION['delpid']);
$price = 0;
}
elseif(isset($_SESSION['pid1'])){
	echo "<table border=1>";
	foreach($_SESSION['pid1'] as $idd){
        foreach($_SESSION['cart'] as $key=>$list){
        if($list['pid']==$idd){
        echo "<tr>";
         
        echo "<td>".$list['name']."</td>";
        echo "<td>".$list['Min Price']."</td>";
		$price1=abs($list['Min Price']);
		echo "<td><a href='buy.php?delete=".$idd."'>Delete</a>";
        echo "</tr>";
        $price+=$price1;
       } 	   
}
}
echo "</table>";
}
echo "Total Price:".$price."$";
echo"<form action='buy.php' method='get'>
<input type='hidden' name='clear' value='1'></input>
<input type='submit' value='Empty Basket'></input>
</form>";




$xmlstr = file_get_contents('http://sandbox.api.ebaycommercenetwork.com/publisher/3.0/rest/CategoryTree?apiKey=78b0db8a-0ee1-4939-a2f9-d3cd95ec0fcc&visitorUserAgent&visitorIPAddress&trackingId=7000610&categoryId=72&showAllDescendants=true');
$xml = new SimpleXMLElement($xmlstr);
?>
<form action='buy.php' method='get'>
<fieldset>
<legend>Find Products</legend>
<label>Category <select name='catalog'>
<option ><?=$xml->category->name?></option>

<?php
foreach($xml->category->categories->category as $catalog)
{
	
$subcatalog=$catalog->categories->category;
	
	?>
<optgroup label=<?=$catalog->name?>>
<option value=<?=$catalog->attributes()->id?>><?=$catalog->name?></option>
	<?php
	foreach($subcatalog as $subcatalog){
		?>
		
	<option value='<?=$subcatalog->attributes()->id?>'><?=$subcatalog->name?></option>

<?php		}
         ?>
</optgroup>
<?php
}

?>

</select>
</label>
<label>Search <input type='text' name='search'>
<input type='submit' value='search'>
</label>
</fieldset>
</form>
<?php

if(isset($_GET['catalog'])){
if($_GET['catalog']=='NULL'){
        echo "";
}
}


	$items=file_get_contents('http://sandbox.api.ebaycommercenetwork.com/publisher/3.0/rest/GeneralSearch?apiKey=78b0db8a-0ee1-4939-a2f9-d3cd95ec0fcc&trackingId=7000610&categoryId='.$_GET['catalog'].'&keyword='.$_GET['search'].'&numItems=20');
$items=new SimpleXMLElement($items);
?>
<table border='2'>
<?php
foreach($items->categories->category->items->product as $item){
        $name=$item->name;
        $product_id=$item->attributes()->id;
        $price=$item->price;
        $offerURLS=$item->productOffersURL;
        $image=$item->images->image->sourceURL;
		if (isset($_SESSION['cart']))
		{	if(!in_array($product_id,$_SESSION['bpid'])){
			
				array_push($_SESSION['bpid'], $product_id);
					$_SESSION['cart'][]=array(
					'name'=>$name,
					'pid'=>$product_id,
					'Min Price'=>$price,
					
					'image'=>$image
					);
				}
		}else{
			
$_SESSION['cart'][]=array(                            
'name'=>$name,
'pid'=>$product_id,
'Min Price'=>$price,
			
'image'=>$image
			);
			
$_SESSION['bpid']=array($product_id);
		}
		
		
		?>
		
	<tr>	
	<td><a href="buy.php?buy=<?=$item->attributes()->id?>"><img src="<?=$item->images->image->sourceURL?>"</a></td>
		<td><?=$item->name?></td>
       <td><?=$item->price?></td>
       <td><?=$item->fullDescription?></td>
	   </tr>

	   
<?php

}

?>
</table>



</body>
</html>