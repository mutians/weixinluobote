<?php
	include('head.php');

    @$act = Trim($_REQUEST["act"]);
    if($act == 'loadurl'){
        $url = $_REQUEST["url"];
        $SSL = substr($url, 0, 8) == "https://" ? true : false;
        $ch = curl_init();
        if($SSL){
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // 信任任何证书 
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2); // 检查证书中是否设置域名 
        }
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Expect:')); //避免data数据过长问题
        $result = curl_exec($ch);
        if(curl_errno($ch)){
            echo '错误';
        }else{
            echo $result;
        }
        exit();
    }
    
    if($act == 'reg'){
        @$usertype = 1;
        @$username = str_replace(" ","",trim($_POST["username"]));
        @$password = sha1(str_replace(" ","",trim($_POST["password"])));
        @$nickname = str_replace(" ","",trim($_POST["nickname"]));
        @$status = 1;
        $query = "SELECT * FROM users where username='".$username."' and status > -1";
        $rows = $db->query($query)->fetchAll();
        $num = count($rows);
        if ($num >= 1){
            echo "<script type='text/javascript'>alert('手机号码已存在');history.go(-1)</script>";
        }else{
            $query = "INSERT INTO users (username, password, usertype, nickname, adddate, status, lastlogintime, lastloginip) 
            VALUES ('".$username."', '".$password."', '".$usertype."', '".$nickname."', '".time()."', '".$status."', '".time()."', '".getIP()."')";
            $db->exec($query);
            $query = "SELECT * FROM users where username='".$username."' and status > -1";
            $rows = $db->query($query)->fetchAll();
            $num = count($rows);
            if ($num >= 1){
                $row = $rows[0];
                if($row['password'] == $password && $row['status'] > 0){
                    $_SESSION['nickname'] = $row['nickname'];
                    $_SESSION['username'] = $row['username'];
                    $_SESSION['adminflag'] = true;
                    $_SESSION['usertype'] = $row['usertype'];
                    $_SESSION['userid'] = $row['id'];
                    $query = "UPDATE users SET lastlogintime='".time()."', lastloginip='".getIP()."' WHERE id='".$row['id']."'";
                    $db->exec($query);
                    echo "<script type='text/javascript'>window.location.href='/msgs.php'</script>";
                    exit();
                }
                if($row['password'] !== $password){
                    echo "<script type='text/javascript'>alert('密码错误');history.go(-1)</script>";
                    exit();
                }
                if($row['status'] == 0){
                    echo "<script type='text/javascript'>alert('账号已被禁用');history.go(-1)</script>";
                    exit();
                }
            }else if($num <= 0){
                echo "<script type='text/javascript'>alert('账号不存在');history.go(-1)</script>";
                exit();
            }
        }
    }

    if($act == 'login'){
        @$username = str_replace(" ","",trim($_POST["username"]));
        @$password = sha1(str_replace(" ","",trim($_POST["password"])));
        $query = "SELECT * FROM users where username='".$username."'";
        $rows = $db->query($query)->fetchAll();
        $num = count($rows);
        if ($num >= 1){
            $row = $rows[0];
            if($row['password'] == $password && $row['status'] > 0){
                $_SESSION['nickname'] = $row['nickname'];
                $_SESSION['username'] = $row['username'];
                $_SESSION['adminflag'] = true;
                $_SESSION['usertype'] = $row['usertype'];
                $_SESSION['userid'] = $row['id'];
                $query = "UPDATE users SET lastlogintime='".time()."', lastloginip='".getIP()."' WHERE id='".$row['id']."'";
                $db->exec($query);
                if(isset($_COOKIE["urltmp"]) && strlen($_COOKIE["urltmp"]) > 0){
                    echo "<script type='text/javascript'>window.location.href=decodeURIComponent('".$_COOKIE["urltmp"]."')</script>";
                }else{
                    echo "<script type='text/javascript'>window.location.href='/msgs.php'</script>";
                }
                exit();
            }
            if($row['password'] !== $password){
                echo "<script type='text/javascript'>alert('密码错误');history.go(-1)</script>";
                exit();
            }
            if($row['status'] == 0){
                echo "<script type='text/javascript'>alert('账号已被禁用');history.go(-1)</script>";
                exit();
            }
        }else if($num <= 0){
            echo "<script type='text/javascript'>alert('账号不存在');history.go(-1)</script>";
            exit();
        }
    }

    if (isset($_SESSION['adminflag']) and $_SESSION['adminflag'] === true){
        $db = null;
        echo "<script type='text/javascript'>window.location.href='/msgs.php'</script>";
        exit();
    }

	callHead('登录','','','login');
?>
	<!-- BEGIN PAGE LEVEL STYLES -->
    <link href="/assets/pages/css/login.min.css" rel="stylesheet" type="text/css" />
    <!-- END PAGE LEVEL STYLES -->
    <body class=" login">
        <!-- BEGIN LOGO -->
        <div class="logo">
            <a href="/">
                <?=$GLOBALS['webtitle']?> </a>
        </div>
        <!-- END LOGO -->
        <!-- BEGIN LOGIN -->
        <div class="content">
            <!-- BEGIN LOGIN FORM -->
            <form class="login-form" action="/index.php?act=login" method="post">
                <h3 class="form-title font-green">登录</h3>
                <div class="alert alert-danger display-hide">
                    <button class="close" data-close="alert"></button>
                    <span> 输入手机号和密码 </span>
                </div>
                <div class="form-group">
                    <!--ie8, ie9 does not support html5 placeholder, so we just show field title for that-->
                    <label class="control-label visible-ie8 visible-ie9">用户名/手机号</label>
                    <input class="form-control form-control-solid placeholder-no-fix" type="text" autocomplete="off" placeholder="用户名/手机号" name="username" /> </div>
                <div class="form-group">
                    <label class="control-label visible-ie8 visible-ie9">密码</label>
                    <input class="form-control form-control-solid placeholder-no-fix" type="password" autocomplete="off" placeholder="密码" name="password" /> </div>
                <div class="form-actions">
                    <button type="submit" class="btn btn-block green uppercase">登录</button>
                </div>
                <div class="create-account">
                    <p>
                        <a href="javascript:;" id="register-btn" class="uppercase">申请新账号</a>
                    </p>
                </div>
            </form>
            <!-- END LOGIN FORM -->
            <!-- BEGIN REGISTRATION FORM -->
            <form class="register-form" action="/index.php?act=reg" method="post">
                <h3 class="font-green">申请新账号</h3>
                <div class="form-group">
                    <label class="control-label visible-ie8 visible-ie9">昵称</label>
                    <input class="form-control placeholder-no-fix" type="text" placeholder="昵称" name="nickname" /> </div>
                <div class="form-group">
                    <label class="control-label visible-ie8 visible-ie9">手机号</label>
                    <input class="form-control placeholder-no-fix" type="text" autocomplete="off" placeholder="手机号" name="username" /> </div>
                <div class="form-group">
                    <label class="control-label visible-ie8 visible-ie9">密码</label>
                    <input class="form-control placeholder-no-fix" type="password" autocomplete="off" id="register_password" placeholder="密码，6-16位" name="password" /> </div>
                <div class="form-actions">
                    <button type="button" id="register-back-btn" class="btn btn-default">返回</button>
                    <button type="submit" id="register-submit-btn" class="btn btn-success uppercase pull-right">提交</button>
                </div>
            </form>
            <!-- END REGISTRATION FORM -->
        </div>
        <div class="copyright"> <?=date('Y',time())?> &copy; <?=$GLOBALS['webtitle']?>.</div>
        <!--[if lt IE 9]>
        <script src="/assets/global/plugins/respond.min.js"></script>
        <script src="/assets/global/plugins/excanvas.min.js"></script> 
        <![endif]-->
        <!-- BEGIN CORE PLUGINS -->
        <script src="/assets/global/plugins/jquery.min.js" type="text/javascript"></script>
        <script src="/assets/global/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
        <script src="/assets/global/plugins/js.cookie.min.js" type="text/javascript"></script>
        <script src="/assets/global/plugins/bootstrap-hover-dropdown/bootstrap-hover-dropdown.min.js" type="text/javascript"></script>
        <script src="/assets/global/plugins/jquery-slimscroll/jquery.slimscroll.min.js" type="text/javascript"></script>
        <script src="/assets/global/plugins/jquery.blockui.min.js" type="text/javascript"></script>
        <script src="/assets/global/plugins/uniform/jquery.uniform.min.js" type="text/javascript"></script>
        <script src="/assets/global/plugins/bootstrap-switch/js/bootstrap-switch.min.js" type="text/javascript"></script>
        <!-- END CORE PLUGINS -->
        <!-- BEGIN PAGE LEVEL PLUGINS -->
        <script src="/assets/global/plugins/jquery-validation/js/jquery.validate.min.js" type="text/javascript"></script>
        <script src="/assets/global/plugins/jquery-validation/js/additional-methods.min.js" type="text/javascript"></script>
        <script src="/assets/global/plugins/select2/js/select2.full.min.js" type="text/javascript"></script>
        <!-- END PAGE LEVEL PLUGINS -->
        <!-- BEGIN THEME GLOBAL SCRIPTS -->
        <script src="/assets/global/scripts/app.min.js" type="text/javascript"></script>
        <!-- END THEME GLOBAL SCRIPTS -->
        <!-- BEGIN PAGE LEVEL SCRIPTS -->
        <script src="/assets/pages/scripts/login.min.js" type="text/javascript"></script>
        <!-- END PAGE LEVEL SCRIPTS -->

		<div class="statics" style="display:none;"><script src="//s4.cnzz.com/stat.php?id=1259219868&web_id=1259219868" language="JavaScript"></script></div>
    </body>
</html>