<?php
$connect = mysqli_connect('localhost','root','','cart');
$query ='SELECT * FROM books ORDER by id ASC';
$result = mysqli_query($connect,$query);

if($result){
    if(mysqli_num_rows($result)>0){
        while($book = mysqli_fetch_assoc($result)){
            print_r($book);
        }
    }
}