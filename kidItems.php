<?php require_once 'config.php' ;
	if(isset($_GET["search"])){
		$min=$_GET["min"];
		$max=$_GET["max"];
		$categories_arry=$_GET["cat"];
		$myArray=array();
		foreach($categories_arry as $categories_strg){
			$myArray[]="'".$categories_strg."'";
		}
		$chk=implode(",",$myArray);
		$sql="SELECT * FROM item where category like'%kid%' AND size='s' AND type IN ($chk) AND unit_price BETWEEN $min AND $max " ;
	}else{
		$sql="SELECT * FROM item where category like '%kid%' AND size='s'"; 
	}
	?>


<?php include 'header.php'?>
	
    <link rel="stylesheet" href="src/css/test1.css" type="text/css">
	



</head>
<body>
        <div class="center">
                    
     <?php if($result=$conn->query($sql)){

	if($result->num_rows>0){


		while($row=$result->fetch_assoc()){

			echo ("<div class='item-box' style='background-image: url(".$row['image'].");'>");

			echo ("<div class='item-box-overlay'>");

			echo ("<center><a href='itemDetails.php?id=".$row['item_code']."'><button class='item-view-button'>View Item</button></a></center>");


			echo ("</div>");

			echo ("<div class='item-box-disc'><p>" .$row['name']."</p></div>");

			echo ("</div>");


		}



	}else
		echo "no result";
		//no rows







}else
	echo "Failed";;//queryfailed

	
?>
   




 



            </div>
    </div> 
    
  

 <script type="text/javascript" src="src/Js/priceSlider.js"></script>

