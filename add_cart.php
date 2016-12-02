case "add":
	if(!empty($_POST["quantity"])) {
		$productByCode = $db_handle->runQuery("SELECT * FROM tblproduct WHERE sku='" . $_GET["sku"] . "'");
		$itemArray = array($productByCode[0]["sku"]=>array('name'=>$productByCode[0]["name"], 'sku'=>$productByCode[0]["sku"], 'quantity'=>$_POST["quantity"], 'price'=>$productByCode[0]["price"]));		
		if(!empty($_SESSION["cart_item"])) {
			if(in_array($productByCode[0]["sku"],$_SESSION["cart_item"])) {
				foreach($_SESSION["cart_item"] as $k => $v) {
					if($productByCode[0]["sku"] == $k)	$_SESSION["cart_item"][$k]["quantity"] = $_POST["quantity"];
				}
			} else $_SESSION["cart_item"] = array_merge($_SESSION["cart_item"],$itemArray);
		} else 	$_SESSION["cart_item"] = $itemArray;
	}
break;

case "remove":
	if(!empty($_SESSION["cart_item"])) {
		foreach($_SESSION["cart_item"] as $k => $v) {
			if($_GET["sku"] == $k)	unset($_SESSION["cart_item"][$k]);				
			if(empty($_SESSION["cart_item"])) unset($_SESSION["cart_item"]);
		}
	}
break;
case "empty":
	unset($_SESSION["cart_item"]);
break;



<?php
$product_array = $db_handle->runQuery("SELECT * FROM tblproduct ORDER BY id ASC");
if (!empty($product_array)) { 
foreach($product_array as $key=>$value){
?>
<div class="product-item">
	<form method="post" action="index.php?action=add&sku=<?php echo $product_array[$key]["sku"]; ?>">
	<div><strong><?php echo $product_array[$key]["name"]; ?></strong></div>
	<div class="product-price"><?php echo "$".$product_array[$key]["price"]; ?></div>
	<div><input type="text" name="quantity" value="1" size="2" /><input type="submit" value="Add to cart" class="btnAddAction" /></div>
	</form>
</div>
<?php }} ?>