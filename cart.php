<!DOCTYPE <!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Page Title</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" media="screen" href="cart.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
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
            print_r($book);
            ?>
            <div class="col-sm-4 col-md-3">
            <form method="post"action="index.php?action=add&id=<?php echo $book['id']; ?>">
            <div class="books">
            <img src="<?php echo $book['image']; ?>" alt="" class="image-responsive">
            <h4 class="text-info"><?php echo $book['name']; ?></h4>
            <h4><?php echo $book['price'] ; ?></h4>
            <input type="text" name="quantity"class="form-control" value="1">
            <input type="hidden" name="name" value="<?php echo $book['name'] ; ?>">
            <input type="hidden" name="price" value="<?php echo $book['price'] ; ?>">
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
