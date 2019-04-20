<?php 
session_start();
$book_ids=array();
session_destroy();

//check if Add to cart button has been submitted
if(filter_input(INPUT_POST,'add_to_cart')){
    if(isset($_SESSION['shopping_cart'])){
        //keep track of how many books are in the cart
        $count = count($_SESSION['shopping_cart']);

        //creat sequential array for matching array keys to books ids
        $book_ids = array_column($_SESSION['shopping_cart'],id);
        if(!in_array(filter_input(INPUT_GET,'id'),$book_ids)){
            $_SESSION['shopping_cart'][$count]=array(
                'id'=>filter_input(INPUT_GET,'id'),
                'name'=>filter_input(INPUT_POST,'name'),
                'price'=>filter_input(INPUT_POST,'price'),
                'quantity'=>filter_input(INPUT_POST,'quantity')   
            );
        }
        else{// already exists, increase quantity
            //match array key to the id of the book being added to the cart
          for($i=0;i<count($book_ids);$i++){
              if($book_ids[i]==filter_input(INPUT_GET,'id')){
                  //add item quantity to the existing book in the cart
                  $_SESSION['shopping_cart'][i]['quantity'] += filter_input(INPUT_POST,'quantity');
              }
          }
        }

    }else{//if shopping cart doesn't exist,create first product with array key 0
        //create array using submitted form data, start from key 0 and fill it with values

        $_SESSION['shopping_cart'][0] = array(
           'id'=>filter_input(INPUT_GET,'id'),
           'name'=>filter_input(INPUT_POST,'name'),
           'price'=>filter_input(INPUT_POST,'price'),
           'quantity'=>filter_input(INPUT_POST,'quantity')
        );

    }
}
pre_r($_SESSION);
print_r($array);
function pre_r($array){
    echo '<pre>';
    print_r($array);
    echo '<pre>';
}
?>

<!DOCTYPE <!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Page Title</title>
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
            <div class="col-sm-4 col-md-3">
               <form method="post"action="index.php?action=add&id=<?php echo $book['id']; ?>">
                  <div class="books">
                    <img src="<?php echo $book['image']; ?>" alt="" class="img-responsive">
                    <h4 class="text-info"><?php echo $book['name']; ?></h4>
                    <h4>N<?php echo $book['price'] ; ?></h4>
                    <input type="text" name="quantity"class="form-control" value="1">
                    <input type="hidden" name="name" value="<?php echo $book['name'] ; ?>">
                    <input type="hidden" name="price" value="<?php echo $book['price'] ; ?>">
                    <input type="submit" name="add_to_cart" style="margin-top:5px" class="btn btn-info" value="Add to Cart">
                </div>
               </form>
            </div>

            <?php
        endwhile;
    endif;
endif;
?>
</div>
    
</body>
</html>
