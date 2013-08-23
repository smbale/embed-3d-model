<?php
//上传Lao3d
include_once '../../../../../wp-blog-header.php';
require_once '../Lao3d_PHP_SDK_V1.0/Lao3dApiV1.php';
require_once '../../lao3d-common.php';


$errret = '{"ret": 1, "msg": "上传失败"}';

if (!empty($_FILES) && $_FILES["obj"]["error"] == 0) {
    $tempFile = $_FILES['obj']['tmp_name'];	
    $targetPath = pathinfo($tempFile, PATHINFO_DIRNAME);
	$targetFile = rtrim($targetPath,'/') . '/' . $_FILES['obj']['name'];    
	
	// Validate the file type
	$fileTypes = array('zip','rar'); // File extensions
	$fileParts = pathinfo($_FILES['obj']['name']);
	    
	if (in_array($fileParts['extension'],$fileTypes) && move_uploaded_file($tempFile,$targetFile)) {
	               
        $modelName = $_POST['modelName'];
        $tags = $_POST['tags'];
        $introduce = $_POST['introduce'];        
    
        $priviledge = get_option(LAO3D_ENTERPRISE_OPTION_PRIVILEDGE, LAO3D_ENTERPRISE_DEFAULT_PRIVILEDGE);
        $apikey = get_option(LAO3D_ENTERPRISE_OPTION_API, LAO3D_ENTERPRISE_DEFAULT_API);
        $libname = get_option(LAO3D_ENTERPRISE_OPTION_LIBNAME, LAO3D_ENTERPRISE_DEFAULT_LIBNAME);
    
        $sdk = new Lao3dApiV1($apikey);
        $sdk->setServerName('www.lao3d.com');
    
        $params = array(
		    'modelName' => $modelName,
		    'libraryName' => $libname,
		    'tags'=>$tags,
            'priviledge'=>$priviledge,
            'introduce'=>$introduce,  
	    );
    
        $array_files = array(
            'obj' => ('@'.$targetFile),
            //'icon' => '@c:\\095859.png',
        );
    
        $res = $sdk->apiUploadFile('/openApi/uploadModel', $params, $array_files);
        if(!is_array($res) || !isset($res['ret'])){
            echo $errret;
        }
        else{        
            echo json_encode($res);
        }
        
        unlink($targetFile);
	}
    else
    {
        echo $errret;
    }
}
else
{
    echo $errret;
}
?>