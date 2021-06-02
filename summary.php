<?php

	class User {
		
		private $name="";         //user name
		private $contactno="";    //user contact number
		private $address="";      //user address
		
		#constructor
		public function __construct($name,$contactno,$address){
			$this->name = $name;
			$this->contactno = $contactno;
			$this->address = $address;
		}
		
		#access $name value
		public function getName(){
			return $this->name;
		}
		
		#access $contactno value
		public function getContactNo(){
			return $this->contactno;
		}
		
		#access $address value
		public function getAddress(){
			return $this->address;
		}

	}

	class Product {
		
		private $prodName="";    //product name
		private $prodPrice="";   //product price
		
		#constructor
		public function __construct($prodName,$prodPrice){
			$this->prodName = $prodName;
			$this->prodPrice = $prodPrice;
		}
		
		#access $prodName value
		public function getPName(){
			return $this->prodName;
		}
		
		#access $prodPrice value
		public function getPPrice(){
			return $this->prodPrice;
		}		
		
	}

	class Transaction {
		
		private $prodamt = array();  //stores amount assigned by user per product
		private $products = array(); //stores products that have an amount
		private $discountpriv="";    //student, senior, pwd, none
		private $payment="";         //mode of payment
		
		#computations
		private $prodtotal = array(); //product price * product amount
		private $subtotal=0;          //summation of all values in prodtotal
		private $vat=0;               //value added tax (12% if non-senior or non-pwd)
		private $shippingfee=45;      //fixed shipping fee
		private $discount=0;          //discount depending on discountpriv (10% if student, 20% if senior and pwd, 0 if none)
		private $total=0;             //total amount due (subtotal+vat+shipping fee-discount)
		
		public function __construct($prodamt,$discountpriv,$payment){
			$this->prodamt = $prodamt;
			$this->discountpriv = $discountpriv;
			$this->payment = $payment;
		}
		
        #get $prodamt value
		public function getProdAmt(){
			return $this->prodamt;
		}
		
		#get $products value
		public function getProducts(){
			return $this->products;
		}
		
		#adds products 
		public function boughtProducts($p){
			
			$sprodamt = sizeof($this->prodamt);
			
			for ($x = 0; $x < $sprodamt; $x++){
				
				if($this->prodamt[$x] == 0) unset($this->prodamt[$x]);     //remove 0 values
				else $this->products[] = $p[$x];                           //add products with amount

			}

			$this->prodamt = array_values($this->prodamt); //reindex elements
			
        }
		
		#product total per product
		public function setProdTotal(){
			
			$sprodamt = sizeof($this->prodamt);
			
			for ($x = 0; $x < $sprodamt; $x++){
			
			    $this->prodtotal[] = $this->products[$x]->getPPrice() * $this->prodamt[$x];
				
			}
		}
		
		#get $prodtotal value
		public function getProdTotal(){
			return $this->prodtotal;
		}
		
		#$subtotal = summation of all values in $prodtotal
		public function setSubTotal(){
			foreach($this->prodtotal as $pt) $this->subtotal+= $pt;
		}
		
		#get $subtotal value
		public function getSubTotal(){
			return $this->subtotal;
		}
		
		#get $discountpriv value
		public function getDiscountPriv(){
			return $this->discountpriv;
		}
		
		#determines $vat and $discount values
		public function setVATNDiscount(){
			
			if($this->discountpriv == "none"){
				$this->vat = $this->subtotal * 0.12;
			}
			elseif($this->discountpriv == "student"){
				$this->vat = $this->subtotal * 0.12;
				$this->discount+=$this->subtotal * 0.10;
			}
			elseif($this->discountpriv == "senior"){
				$this->discount+=$this->subtotal * 0.20;
			}
			elseif($this->discountpriv == "pwd"){
				$this->discount+=$this->subtotal * 0.20;
			}
				
		}
		
		#$vat value
		public function getVAT(){
			return $this->vat;
		}
		
		#$discount value
		public function getDiscount(){
			return $this->discount;
		}
		
		#$shippingfee value
		public function getShippingFee(){
			return $this->shippingfee;
		}
		
		#computes total amount due
		public function setTotalAmt(){
			$this->total = $this->subtotal + $this->vat + $this->shippingfee - $this->discount;
		}
		
		#access $total value
		public function getTotalAmt(){
			return $this->total;
		}
		
	    #access $payment value
		public function getPayment(){
		   return $this->payment;
		}
		
	}

    #create 10 product instances for 10 products
    $Razer1 = new Product("Razer Viper Ultimate",7495.00);
    $Razer2 = new Product("Razer Viper",2395.00);
	$Razer3 = new Product("Razer Viper mini",1495.00);
	$Razer4 = new Product("Razer Basilisk Ultimate",7995.00);
	$Razer5 = new Product("Razer Basilisk V2",3995.00);
	$Razer6 = new Product("Razer Basilisk X Hyperspeed",2395.00);
	$Razer7 = new Product("Razer Deathadder V2 Pro",6495.00);
	$Razer8 = new Product("Razer Deathadder V2",2995.00);
	$Razer9 = new Product("Razer Deathadder V2 mini",2395.00);
	$Razer10 = new Product("Razer Mamba Elite",4295.00);
	$prods = array($Razer1,$Razer2,$Razer3,$Razer4,$Razer5,$Razer6,$Razer7,$Razer8,$Razer9,$Razer10); //all products

    #add details of user
    $user = new User($_POST['name'],$_POST['contactno'],$_POST['address']);
	
	#amount per product 
	$transact = new Transaction($_POST['razerqty'],$_POST['discount'],$_POST['payment']);
	
	#add products that have an amount
	$transact->boughtProducts($prods);
	
	#compute amount to be paid per amount of product
	$transact->setProdTotal();
	
	#compute subtotal
	$transact->setSubTotal();
	
	#determine vat and discount
	$transact->setVATNDiscount();
	
	#compute total amount due
	$transact->setTotalAmt();
?>

<html>
<style>
	    body {
  text-align: center;
  font-family: "Helvetica Neue", "Helvetica", Arial, Verdana, sans-serif;
  background: #f2f2f2;
}
.cf:before,
.cf:after {
    content: " "; /* 1 */
    display: table; /* 2 */
}
.cf:after {
    clear: both;
}
/* For IE 6/7 only Include this rule to trigger hasLayout and contain floats. */
.cf {
    *zoom: 1;
}

.crReciept {
  border: solid 1px rgba(0, 0, 0, 0.15);
  max-width: 400px;
  margin: 0 auto;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
  background: #f2f2f2;
  margin-bottom: 25px;
}
.crReciept h2 {
  color: #2E8B57;
}
.crReciept h3 span{
  color: #2E8B57;
}

.crTitle {
  max-width: 400px; 
  margin: 0 auto;
  
}
.crTitle img {
  /* float: left; */
  width: 150px
  
}
.crItem {
  margin: 0 20px;
  border-top: solid 1px #ccc;
  border-bottom: solid 1px #ccc;
  display: block;
  clear: both;
}
.crItem p:first-child {
  float: left;
  max-width: 70%;
  text-align: left;
}
.crAmount {
  font-size: 32px;
  font-weight: bold;
  line-height: 10px;
  float: right;
  margin: 22px 0;
}
.crAmount sup {
  font-size: 18px;
}

.paymentInfo {
  text-align: left;
  padding-left: 25px;
  padding-bottom: 10px;
}

.crReciept a {
  text-decoration: none;
  color: #000;
  font-weight: bold;
}
.crReciept a:hover {
  color: #FF0034;
}		
	</style>
    <head>
	    <title>Transaction Complete</title>
	</head>
	<body>
	<div class="crTitle">
	<h1>Payment Confirmation</h1>
	</div>
	<div class="crReciept">
	<h2>Payment Successful</h2>
	<h3>Method: <span><?php echo $transact->getPayment()?></span></h3>
	<div class="crItem cf">
		<p>Season Payed for with <?php echo $transact->getPayment()?>
		<p class="crAmount">Php <?php echo $transact->getTotalAmt()?></td><sup>00</sup></p>
	</div>
	
	<p class="paymentInfo">
		<strong>Payment Made By:</strong>
					<br> <?php echo $user->getName()?>
					<br> <?php echo $user->getContactNo()?>
					<br> <?php echo $user->getAddress()?>
					<br> <?php echo $transact->getDiscountPriv()?>
	</p>
	<p class="paymentInfo">
	<Table>
			
			<tr>
				<td><strong>Product Name</strong></td>
				<td><strong>Price</strong></td>
				<td><strong>Amount</strong></td>
				<td><strong>Product Total</strong></td>
			 </tr>
			
			<?php
				  for($x=0; $x < sizeof($transact->getProducts()); $x++){
					 echo "<tr>
							 <td>".$transact->getProducts()[$x]->getPName()."</td>
							 <td><center> ".$transact->getProducts()[$x]->getPPrice()."</center></td>
							 <td><center>".$transact->getProdAmt()[$x]."</center></td>
							 <td><center> ".$transact->getProdTotal()[$x]."</center></td>
						   </tr>";
				  }
			  ?>
	</table>
	</p>
	<p class="paymentInfo">
	<Table>
			<td colspan="5"><strong>Subtotal</strong></td>
			<td>Php <?php echo $transact->getSubTotal()?></td>
			  <tr>
				<td colspan="5" class="secname"><strong>VAT</strong></td>
				<td>Php <?php echo $transact->getVAT()?></td>
			  </tr>
			  <tr>
				<td colspan = "5" class="secname"><strong>Discount</strong></td>
				<td>Php <?php echo $transact->getDiscount()?></td>
			  </tr>
			  <tr>
				<td colspan = "5" class="secname"><strong>Shipping Fee</strong></td>
				<td>Php <?php echo $transact->getShippingFee()?></td>
			  </tr>
			  <tr>
				<td colspan = "5" class="secname"><strong>Total Amt Due</strong></td>
				<td>Php <?php echo $transact->getTotalAmt()?></td>
			  </tr>
	</p>
	</table>
	</body>
</html>