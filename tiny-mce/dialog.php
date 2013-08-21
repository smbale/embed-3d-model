<?php

include_once '../../../../wp-blog-header.php';

define("LAO3D_PERSONAL_DEFAULT_WIDTH", 600);
define("LAO3D_PERSONAL_DEFAULT_HEIGHT", 450);

//lao3d-related options name define.
define("LAO3D_PERSONAL_OPTION_WIDTH", "lao3d_personal_width");
define("LAO3D_PERSONAL_OPTION_HEIGHT", "lao3d_personal_height");

?>

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>插入3D模型</title>
    <link rel="stylesheet" href="../../../../wp-includes/js/tinymce/themes/advanced/skins/wp_theme/dialog.css">
    <script src="../../../../wp-includes/js/tinymce/tiny_mce_popup.js" type="text/javascript"></script>
    <script src="../../../../wp-includes/js/jquery/jquery.js" type="text/javascript"></script>
    <style media="screen" type="text/css">
        *
        {
            font: 14px '宋体' ,Tahoma,Helvetica, 'SimSun' ,sans-serif;
            color: #444;
            text-align: left;
        }
        
        #title
        {
            border-bottom: 1px solid #ccc;
            padding: 5px;
            margin-bottom: 20px;
        }
        
        a
        {
            text-decoration: none;
        }
        
        .pbn
        {
            color: #999;
        }
        
        .xg1
        {
            margin: 10px 0;
        }
    </style>
    <script type="text/javascript">

        function getrealmodelsrc(src) {
            tmp = /modelid=([0-9]+)|^\s*([0-9]+)\s*$/.exec(src);            
            if (tmp)                
                 return "http://www.lao3d.com/model_link.html?modelid=" + (tmp[1]? tmp[1] : tmp[2]);            
            else
                return "";
        }

        function code_builder() {
            code = '[lao3dp ';
            code += 'src="' + getrealmodelsrc(document.getElementById('lao3durl').value) + '" ';
            code += 'width="' + document.getElementById('width').value + '" ';
            code += 'height="' + document.getElementById('height').value + '" ';
            code += '/]';
            tinyMCEPopup.execCommand('mceInsertContent', false, code);
            tinyMCEPopup.close();

            return false;
        };

    </script>
</head>
<body>
    <form method='post'>
    <h1 id="title">
        插入3D链接</h1>
    <div>
        在嵌入3D模型前,需要先把您的模型上传至捞3D(Lao3D.com)<a href="http://www.lao3d.com/" style="color: #0000E3"
            target="_blank">,点击这里上传模型！</a>
    </div>
    <table cellpadding="0" cellspacing="0" style="margin: 20px 0; width: 90%;">
        <tbody>
            <tr>
                <th width="74%" class="pbn">
                    请输入捞3D三维模型地址
                </th>
                <th width="13%" class="pbn">
                    宽(可选)
                </th>
                <th width="13%" class="pbn">
                    高(可选)
                </th>
            </tr>
            <tr>
                <td>
                    <input type="text" id="lao3durl" autocomplete="off" style="width: 95%;">
                </td>
                <td>
                    <input id="width" size="3" value="<?php 
                                                      $op_width = get_option(LAO3D_PERSONAL_OPTION_WIDTH);
                                                      echo empty($op_width)? LAO3D_PERSONAL_DEFAULT_WIDTH: $op_width; ?>" class="px p_fre" autocomplete="off">
                </td>
                <td>
                    <input id="height" size="3" value="<?php 
                                                       $op_height = get_option(LAO3D_PERSONAL_OPTION_HEIGHT);
                                                       echo empty($op_height)? LAO3D_PERSONAL_DEFAULT_HEIGHT:$op_height; ?>" class="px p_fre" autocomplete="off">
                </td>
            </tr>
        </tbody>
    </table>
    <div style="border-bottom: 1px solid #ccc; margin: 10px 0; padding: 10px;">
        如何获得捞3D三维模型地址?有以下两种方式:<br>
        在捞3D网站中的模型展示页面,复制地址栏Url,粘贴即可.<br>
        在捞3D网站中的模型展示页面下方,找到分享链接复制HTML代码,粘贴即可.</div>
    <div style="float: right; margin: 10px 0">
        <input type="submit" value="提交" name="insert" id="insert" onclick="code_builder()"
            style="background: #2A70C0; color: #fff; width: 4em; text-align: center;">
    </div>
    </form>
</body>
</html>
