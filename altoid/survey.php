
<?php

include 'config.php';

if(isset($_POST['survey'])){

    $name = $_POST['name'];
    $likes = $_POST['likes'];
    $rating = $_POST['rating'];
    $improvements = $_POST['improvements'];
    $recommend = $_POST['recommend']; 
    $comments = $_POST['comments'];

    $select = $conn->prepare("SELECT * FROM survey WHERE name = ?  AND likes = ? AND rating = ?AND improvements = ? AND recommend = ? AND comments = ?");
    $select->execute([$name,$likes,$rating,$improvements,$recommend,$comments]);

    if($select->rowCount()>0){
        $message  = 'survey already submitted';
    }else{
        $insert = $conn->prepare("INSERT INTO survey(name,likes,rating,improvements,recommend,comments) VALUES(?,?,?,?,?,?)");
        $insert->execute([$name,$likes,$rating,$improvements,$recommend,$comments]);
        $message = 'survey sent successfully';
    }
}

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Survey Form</title>
   <style>
   body {
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 20px;
    background-color:black;
    width:100%;
    

}

.container {
    max-width: 600px;
    margin: auto;
    background: #fff;
    padding: 20px;
    border-radius: 5px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    width: 100%;
}

h2 {
    text-align: center;
    color: #4b0082; /* Purple color for the heading */
}

.form-group {
    margin-bottom: 15px;
}

.form-group label {
    display: block;
    margin-bottom: 5px;
    color: #4b0082; /* Purple color for the labels */
}

.form-group input, .form-group textarea, .form-group select {
    width: 100%;
    padding: 8px;
    box-sizing: border-box;
    border: 1px solid #4b0082; /* Purple color for the borders */
    border-radius: 4px;
}

.form-group input[type="radio"], .form-group input[type="checkbox"] {
    width: auto;
    margin-right: 5px;
}

.form-group button {
    padding: 10px 15px;
    background-color: #4b0082; /* Purple color for the button */
    color: white;
    border: none;
    border-radius: 5px;
    cursor: pointer;
}

.form-group button:hover {
    background-color: #6a0dad; /* Darker purple color on hover */
}

textarea {
    resize: vertical;
}

    </style>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<?php
include("header.php");
?>


<div class="container">
    <marquee width="100%" direction="right" height="100px">
        <h2>Survey Form StudentSuccessHub</h2>
        </marquee> 
    <form action="" method="POST">
        <div class="form-group">
            <label for="name">Name (optional):</label>
            <input type="text" id="name" name="name">
        </div>
        
        <div class="form-group">
            <label for="email">Email (optional):</label>
            <input type="email" id="email" name="email">
        </div>
        
        <div class="form-group">
            <label>How would you rate your overall experience?</label>
            <select name="rating" id="rating">
              //<option value="1">1 - Very Poor</option>
                //<option value="2">2 - Poor</option>
                //<option value="3">3 - Average</option>
                //<option value="4">4 - Good</option>
                //<option value="5">5 - Excellent</option>
            </select>
        
      
        </div>
        
        <div class="form-group">
            <label for="likes">What did you like about your experience?</label>
            <textarea id="likes" name="likes" rows="4"></textarea>
        </div>
        
        <div class="form-group">
            <label for="improvements">What can we improve?</label>
            <textarea id="improvements" name="improvements" rows="4"></textarea>
        </div>
        
        <div class="form-group">
            <label>Would you recommend us to others?</label>
            <input type="radio" id="recommend_yes" name="recommend" value="Yes">
            <label for="recommend_yes">Yes</label>
            <input type="radio" id="recommend_no" name="recommend" value="No">
            <label for="recommend_no">No</label>
        </div>
        
        <div class="form-group">
            <label for="comments">Any additional comments or suggestions?</label>
            <textarea id="comments" name="comments" rows="4"></textarea>
        </div>
        
        <div class="form-group">
<input type="submit" name="survey" value="submit survey">
            
            
        </div>
    </form>
</div>
<div class="form-group">

    <a href="chat.php"><button >Back</button></a>
    <a href="index.php"><button >Homepage</button></a>
    
</div>


</body>
</html>