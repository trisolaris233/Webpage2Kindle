<?php

// 检查是否有post参数
if(!empty($_POST)) {
    // 设置文件头
    header('Content-type: application/json; charset=utf-8');
    // $data = file_get_contents('php://input');
    // $data = json_decode($data, TRUE);
    $data = $_POST;
    $python_dir = "E:\py\py2\python";

    require('class.phpmailer.php');
    require('class.smtp.php');


    // 函数， 发送title.html到email_address
    function send_mail($email_address, $title) {
        $mail = new PHPMailer();
        $mail->isSMTP();

        $mail->Host = "smtp.ym.163.com";
        $mail->Post = 25;
        $mail->SMTPAuth = true;
        $mail->CharSet = "UTF-8";
        $mail->Encoding = "base64";
        $mail->Username = "qwq@lose7.org";
        $mail->Password = "xxxxxxxxxx";
        $mail->Subject = "convert";
        $mail->From = "qwq@lose7.org";
        $mail->FromName = "qwq";
        $mail->AddAddress($email_address, "qwq");
        $mail->AddAttachment(iconv('utf-8', 'gb2312', sprintf("%s.html", $title)), sprintf("%s.html", $title));
        $mail->Body= "w";
        
        if(!$mail->Send()) {
            return $mail->ErrorInfo;
        }
        else {
            return "success";
        }
    }

    // 判断参数
    if(isset($data['url']) && isset($data['email']) && isset($data['title'])) {
        $url = $data['url'];
        $email = $data['email'];
        $title = $data['title'];
        
        $res = shell_exec(iconv('utf-8', 'gbk', sprintf("%s summary.py -i %s -o %s.html", $python_dir, $url, $title)));
        // echo sprintf("%s summary.py -i %s -o %s.html", $python_dir, $url, $title)."\npython:".$res;
        // exit();
        // exit();
        // 推送邮件
        $msg = send_mail($email, $title);
        // 删除文件
        unlink(iconv('utf-8', 'gbk', sprintf("%s.html", $title)));
        if($msg != "success") {
            echo json_encode(
                array(
                    'code'=>0,
                    'msg'=>$msg
                )
            );
            exit();
        } else {
            echo json_encode(
                array(
                    'code'=>1,
                    'msg'=>'successfully!'
                )
            );
            exit();
        }
        
    }
    else {
        echo json_encode(
            array(
                'code'=>0,
                'msg'=>'错误的参数'
            )
        );
        exit();
    }
}

?>


<!DOCTYPE html>
<html lang="zh-CN">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- 上述3个meta标签*必须*放在最前面，任何其他内容都*必须*跟随其后！ -->
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="../../favicon.ico">

    <title>Web2Page2Kindle</title>

    <!-- Bootstrap core CSS -->
    <link href="https://cdn.bootcss.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">


    <!-- Custom styles for this template -->
    <link href="starter-template.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>


    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://cdn.bootcss.com/html5shiv/3.7.3/html5shiv.min.js"></script>
      <script src="https://cdn.bootcss.com/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

    <style>
        .col-center-block {
        float: none;
        display: block;
        margin-left: auto;
        margin-right: auto;
    }
    </style>

    <script>
        function check_filename(filename) {
            var chars = "\\/:*?\"<>|]"
            for(i = 0; i < chars.length; i++) {
                if(filename.indexOf(chars[i]) != -1) {
                    return false
                }
            }
            return true;
        }
        $(document).ready(function(){
            $("#push").click(function(){
                // console.log("push pressed");
                var targeturl = $("#targeturl").val()
                var kindlemail = $("#kindlemail").val()
                var filename = $("#filename").val()
                if(!check_filename(filename)) {
                    $("#res").remove()
                    $("#content").append('<div class="alert alert-danger" id="res">非法的文件名!</div>')
                    return;
                }
                $("#res").remove()
                $("#content").append('<div class="alert alert-success" id="res">推送中, 请稍等</div>')
                // console.log(targeturl)
                // console.log(kindlemail)
                // console.log(filename)
                if(targeturl != "" && kindlemail !="" && filename != "") {
                    $("#push").attr("disabled",true);
                    // console.log(targeturl)
                    // console.log(kindlemail)
                    // console.log(filename)
                    $.ajax({
                        type: 'post',
                        url: 'index.php',
                        dataType: 'json',
                        contentType: "application/x-www-form-urlencoded; charset=utf-8",
                        data: {
                            url: targeturl,
                            email: kindlemail,
                            title: filename
                        },
                        success: function(res) {
                            $("#res").remove()
                            // console.log("success")
                            if(res.code != 1) {
                                $("#content").append('<div class="alert alert-danger" id="res">'+res.msg+'</div>')
                            }
                            else {
                                $("#content").append('<div class="alert alert-success" id="res">推送成功</div>')
                            }
                        },
                        error: function (jqXHR, textStatus, errorThrown) {
                            $("#res").remove()
                            console.log(jqXHR)
                            if(jqXHR.status == 200) {
                                $("#content").append('<div class="alert alert-success" id="res">推送成功</div>')
                            }
                        }
                    })
                }
                else {
                    $("#content").append('<div class="alert alert-danger" id="res">请完整填写信息!</div>')
                }
                $("#res").remove()
                $("#push").attr("disabled",false);
            })
        })
    </script>
</head>

<body>
    <div class="container">
        <nav class="navbar navbar-default" role="navigation">
            <div class="container-fluid">
                <div class="navbar-header">
                    <a class="navbar-brand" href="#">Webpage2Kindle</a>
                </div>
                <div>
                    <ul class="nav navbar-nav">
                        <li class="active"><a href="#" id="homepage">主页</a></li>
                        <li><a href="help.html" id="help">帮助</a></li>
                        <li><a href="about.html" id="about">关于</a></li>
                    </ul>
                </div>
            </div>
        </nav>
        <div class="col-xs-6 col-md-6 col-center-block" id="content">
            <div class="form-horizontal" role="form">
                <div class="form-group">
                    <label for="firstname" class="col-sm-2 control-label">邮箱:</label>
                    <div class="col-sm-10">
                        <input type="email" class="form-control" id="kindlemail" placeholder="kindle邮箱">
                    </div>
                </div>
                <div class="form-group">
                    <label for="lastname" class="col-sm-2 control-label">网址:</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="targeturl" placeholder="要推送的网址">
                    </div>
                </div>
                <div class="form-group">
                    <label for="lastname" class="col-sm-2 control-label">标题:</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="filename" placeholder="显示在kindle中的文件名">
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-offset-2 col-sm-10">
                        <button class="btn btn-default" id="push">推送</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="https://cdn.bootcss.com/jquery/1.12.4/jquery.min.js"></script>
    <script>
        window.jQuery || document.write('<script src="../../assets/js/vendor/jquery.min.js"><\/script>')
    </script>
    <script src="https://cdn.bootcss.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</body>

</html>