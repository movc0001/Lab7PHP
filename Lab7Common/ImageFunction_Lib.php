<?php

function save_uploaded_file($destinationPath, $upLoadFileIndex = -1)
{
    if(!file_exists($destinationPath))
    {
        mkdir($destinationPath, 0777, true);
    }
    
    if($upLoadFileIndex == -1)
    {
        $tempFilePath = $_FILES['txtUpload']['tmp_name'];
        $filePath = $destinationPath."/".$_FILES['txtUpload']['name'];
    }
    else
    {
        $tempFilePath = $_FILES['txtUpload']['tmp_name'][$upLoadFileIndex];
//        $filePath = $destinationPath."/".$_FILES['txtUpload']['name'][$upLoadFileIndex];
        $filePath = $destinationPath."/".$_FILES['txtUpload']['name'];
    }
    
    $pathInfo = pathinfo($filePath);
    $dir = $pathInfo['dirname'];
    $fileName = $pathInfo['filename'];
    $ext = $pathInfo['extension'];
    
    $i="";
    while(file_exists($filePath))
    {
        $i++;
        $filePath = $dir."/".$fileName."_".$i.".".$ext;
    }
    move_uploaded_file($tempFilePath, $filePath);
    
    return $filePath;
}

function downloadFile($filePath)
{
    $fileName = basename($filePath);
    header("Content-Description:File Transfer");
    header("Content-Type:application/octet-stream");
    header("Content-Disposition:attachment; filename=\"$fileName\"");
    header("Expires:0");
    header("Cache-Control:must-revalidate");
    header("Pragma:private");
    header('Content-Length: '.  filesize($filePath));
    ob_clean();
    flush();
    readfile($filePath);
    flush();
}