<?php
$target_dir = "/home/ronipe/public_html/PDFconvert/";
$target_file = $target_dir . basename($_FILES["file"]["name"]);
$file_type=$_FILES['file']['type'];

if ($file_type=="application/pdf") {
    
    $ext=explode('.',$_FILES['file']['name']);
    $extension = $ext[1];
    $newname='Input';
    $target_file=$target_dir.$newname.'.'.$extension;
    
    if(move_uploaded_file($_FILES["file"]["tmp_name"], $target_file))
    {
    echo "The file is uploaded";

    }
    else {
    echo "Problem uploading file";
    }
}
else {
 echo "You may only upload PDFs.<br>";
}


?>