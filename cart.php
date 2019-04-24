<?php 
session_start();
$book_ids=array();
//session_destroy();

//check if add to cart button has been submitted
if(filter_input(INPUT_POST,'add_to_cart')){
    if(isset($_SESSION['shopping_cart'])){

        //keep track of how many products are in the shopping cart
      $count = count($_SESSION['shopping_cart']); 
       
      //create sequential array for matching array keys to product id's
      $book_ids = array_column($_SESSION['shopping_cart'],'id');

      if(!in_array(filter_input(INPUT_GET,'id'),$book_ids)){

        $_SESSION['shopping_cart'][$count] = array
        (
            'id' => filter_input(INPUT_GET,'id'),
            'name' => filter_input(INPUT_POST,'name'),
            'price' => filter_input(INPUT_POST,'price'),
            'quantity' => filter_input(INPUT_POST,'quantity')


        );
      }else{//product already exists increase quantity
          //match array key to the id of the product being added to the cart
          for($i = 0; $i < count($book_ids); $i++){
              if($book_ids[$i] == filter_input(INPUT_GET,'id')){ 

                //add item quantity to the existing book in the array
                $_SESSION['shopping_cart'][$i]['quantity'] += filter_input(INPUT_POST,'quantity');
              }
          }
      }

    }
    else{//if shopping cart doesn't exist create, create first product with array key 0
        //create array using submitted form data, start from key 0 and fill it with values
        $_SESSION['shopping_cart'][0] = array
        (
            'id' => filter_input(INPUT_GET,'id'),
            'name' => filter_input(INPUT_POST,'name'),
            'price' => filter_input(INPUT_POST,'price'),
            'quantity' => filter_input(INPUT_POST,'quantity')


        );

    }
}

if(filter_input(INPUT_GET,'action')=='delete'){
    //loop through all products in the shopping cart till it matches with the GET id variable
    foreach($_SESSION['shopping_cart'] as $key => $book){
        if($book['id'] == filter_input(INPUT_GET,'id')){
            //remove product from the shopping cart when it matches with the GET id
            unset($_SESSION['shopping_cart'][$key]);
        }
    }
    //reset session array keys so that they match with  book_ids numeric array
    $_SESSION['shopping_cart']=array_values($_SESSION['shopping_cart']);
    
}


//pre_r($_SESSION);
function pre_r($array){
    echo '<pre>';
    print_r($array);
    echo '</pre>';
}
?>
 <!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Computer Science Bookshop</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" media="screen" href="cart.css">
    <script src="main.js"></script>
</head>
<body>

<div class="container">
<?php

$connect = mysqli_connect('localhost','root','','cart');
$query ='SELECT * FROM books ORDER by id ASC';
$result = mysqli_query($connect,$query);

if($result):
    if(mysqli_num_rows($result)>0):
        while($book = mysqli_fetch_assoc($result)):
           // print_r($book);
            ?>
            <div class="col-sm-4 col-md-3"><!--this div is responsible for displaying each book in the shop along with it's relevant details-->
               <form method="post" action="cart.php?action=add&id=<?php echo $book['id']; ?>">
                  <div class="books">
                    <img src="<?php echo $book['image']; ?>" class="img-responsive"/>
                    <h4 class="text-info"><?php echo $book['name']; ?></h4>
                    <h4>N<?php echo $book['price'] ; ?></h4>
                    <input type="text" name="quantity" class="form-control" value="1"/>
                    <input type="hidden" name="name" value="<?php echo $book['name'] ; ?>"/>
                    <input type="hidden" name="price" value="<?php echo $book['price'] ; ?>"/>
                    <input type="submit" name="add_to_cart" style="margin-top:5px" class="btn btn-block btn-info" value="Add to Cart"/>
                </div>
               </form>
            </div>

            <?php
        endwhile;
    endif;
endif;
?>
<div style="clear:both"></div>
<br>
<div class="table-responsive">
<table class="table">
<tr><th colspan="5"><h3>Order details</h3></th></tr>
<tr>
<th width="40%">Product Name</th>
<th width="10%">Quantity</th>
<th width="20%">Price</th>
<th width="15%">Total</th>
<th width="5%">Action</th>
</tr>
<?php
if(!empty($_SESSION['shopping_cart'])):
    $total = 0;
    foreach($_SESSION['shopping_cart'] as $key => $book):
?>
<tr>
<td><?php echo $book['name']; ?></td>
<td><?php echo $book['quantity']; ?></td>
<td><?php echo $book['price']; ?></td>
<td><?php echo number_format($book['quantity'] * $book['price'],2 ); ?></td>
<td>
<a href="cart.php?action=delete&id=<?php echo $book['id'] ; ?>" >
<div class="btn-danger">Remove</div>
</a>
</td>
</tr>
<?php 
$total = $total + ($book['quantity'] * $book['price']);
    endforeach;
?>
<tr>
<td colspan="3" align="right"> Total</td>
<td align = "right">N<?php echo number_format($total,2); ?></td>
<td></td>

</tr>
<tr>
<!--show checkout button only if the shopping cart is not empty-->
<td colspan="5">
<?php 
if(isset($_SESSION['shopping_cart'])):
    if(count($_SESSION['shopping_cart'])>0):
?>
 <a href="#" class="button">Checkout</a>
    <?php endif; endif; ?>
</td>
</tr>
    <?php endif; ?>
</table>
</div>
</div>
    
</body>
</html>
