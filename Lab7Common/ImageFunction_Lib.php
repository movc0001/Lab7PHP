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
        $filePath = $destinationPath."/".$_FILES['txtUpload']['name'][$upLoadFileIndex];
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

function resamplePicture($filePath, $destinationPath, $maxWidth, $maxHeight, $upLoadFileIndex = -1)
{
	if (!file_exists($destinationPath))
	{
		mkdir($destinationPath);
	}

	$imageDetails = getimagesize($filePath);
	
	$originalResource = null;
	if ($imageDetails[2] == IMAGETYPE_JPEG) 
	{
		$originalResource = imagecreatefromjpeg($filePath);
	} 
	elseif ($imageDetails[2] == IMAGETYPE_PNG) 
	{
		$originalResource = imagecreatefrompng($filePath);
	} 
	elseif ($imageDetails[2] == IMAGETYPE_GIF) 
	{
		$originalResource = imagecreatefromgif($filePath);
	}
	$widthRatio = $imageDetails[0] / $maxWidth;
	$heightRatio = $imageDetails[1] / $maxHeight;
	$ratio = max($widthRatio, $heightRatio);
	
	$newWidth = $imageDetails[0] / $ratio;
	$newHeight = $imageDetails[1] / $ratio;
	
	$newImage = imagecreatetruecolor($newWidth, $newHeight);
	
	$success = imagecopyresampled($newImage, $originalResource, 0, 0, 0, 0, $newWidth, $newHeight, $imageDetails[0], $imageDetails[1]);
	
	if (!$success)
	{
		imagedestroy(newImage);
		imagedestroy(originalResource);
		return "";
	}
	$pathInfo = pathinfo($filePath);
	$newFilePath = $destinationPath."/".$pathInfo['filename'];
	if ($imageDetails[2] == IMAGETYPE_JPEG) 
	{
		$newFilePath .= ".jpg";
		$success = imagejpeg($newImage, $newFilePath, 100);
	} 
	elseif ($imageDetails[2] == IMAGETYPE_PNG) 
	{
		$newFilePath .= ".png";
		$success = imagepng($newImage, $newFilePath, 0);
	} 
	elseif ($imageDetails[2] == IMAGETYPE_GIF) 
	{
		$newFilePath .= ".gif";
		$success = imagegif($newImage, $newFilePath);
	}
	
	imagedestroy($newImage);
	imagedestroy($originalResource);
	
	if (!$success)
	{
		return "";
	}
	else
	{
		return newFilePath;
	}
}

function rotateImage($filePath, $degrees)
{
	$imageDetails = getimagesize($filePath);
	
	$originalResource = null;
	if ($imageDetails[2] == IMAGETYPE_JPEG) 
	{
		$originalResource = imagecreatefromjpeg($filePath);
	} 
	elseif ($imageDetails[2] == IMAGETYPE_PNG) 
	{
		$originalResource = imagecreatefrompng($filePath);
	} 
	elseif ($imageDetails[2] == IMAGETYPE_GIF) 
	{
		$originalResource = imagecreatefromgif($filePath);
	}
	
	$rotatedResource = imagerotate($originalResource, $degrees, 0);
	
	if ($imageDetails[2] == IMAGETYPE_JPEG) 
	{
		$success = imagejpeg($rotatedResource, $filePath, 100);
	} 
	elseif ($imageDetails[2] == IMAGETYPE_PNG) 
	{
		$success = imagepng($rotatedResource, $filePath, 0);
	} 
	elseif ($imageDetails[2] == IMAGETYPE_GIF) 
	{
		$success = imagegif($rotatedResource, $filePath);
	}
	
	imagedestroy($rotatedResource);
	imagedestroy($originalResource);
}