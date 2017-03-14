<?php
    include('head.php');
    loginCheck2();
    
    @$id = $_SESSION['userid'];
    $query = "SELECT * FROM apikey where userid=".$id." and status > -1";
    $rows = $db->query($query)->fetchAll();
    if(count($rows) > 0){
        $row = $rows[0];
    }else{
        @$appkey = 23663490 + $id;
        @$appsecret = md5($id);
        $query = "INSERT INTO apikey (userid,appkey,appsecret,adddate,status) VALUES (".$id.",'".$appkey."','".$appsecret."','".time()."', 1)";
        $db->exec($query);
        $row = [];
        $row['appkey'] = $appkey;
        $row['appsecret'] = $appsecret;
    }
    callHead('微信账号','账户设置','微信账号');
?>
                    <link href="/assets/pages/css/profile.min.css" rel="stylesheet" type="text/css" />
					<div class="row">
                        <div class="col-md-12">
                            <div class="profile-sidebar">
                                <!-- PORTLET MAIN -->
                                <div class="portlet light profile-sidebar-portlet ">
                                    <!-- SIDEBAR USERPIC -->
                                    <div class="profile-userpic">
                                        <img src="../assets/pages/media/profile/profile_user.jpg" class="img-responsive" alt=""> </div>
                                    <!-- END SIDEBAR USERPIC -->
                                    <!-- SIDEBAR USER TITLE -->
                                    <div class="profile-usertitle">
                                        <div class="profile-usertitle-name"> 昵称 </div>
                                        <div class="profile-usertitle-job"> 签名 </div>
                                    </div>
                                    <!-- END SIDEBAR USER TITLE -->
                                    <!-- SIDEBAR MENU -->
                                    <div class="profile-usermenu">
                                        <ul class="nav">
                                            <li>APP KEY: <?=$row['appkey']?></li>
                                            <li>APP SECRET: <?=$row['appsecret']?></li>
                                        </ul>
                                    </div>
                                    <!-- END MENU -->
                                </div>
                                <!-- END PORTLET MAIN -->
                            </div>
                        </div>
                    </div>
<?php
    include('foot.php');
?>