<?php
    include('conn.php');
    include('emoji.php');

    $result = []; //接口返回数组

    //接收pyhon传过来的json数据
    $input = file_get_contents("php://input");
    $input = json_decode($input,true);
    if(!isset($input['msg'])){
        $result['code'] = 404;
        $result['msg'] = 'WEB服务器没接收到信息-_-';
        echo json_encode($result);
        exit();
    }

    //根据appkey查询用户id
    $appkey = $input['appkey'];
    $appsecret = $input['appsecret'];
    //$appkey = '23663491';
    //$appsecret = '190c9e9428d27bfe47f498bf6017cd3e';
    $query = "SELECT * FROM apikey where appkey='".$appkey."' and appsecret='".$appsecret."' and status > -1";
    $row = $db->query($query)->fetch();
    $userid = '';
    if(!$row){
        $result['code'] = 202;
        $result['msg'] = 'appkey参数错误';
        echo json_encode($result);
        exit();
    }else{
        $userid = $row['userid'];
    }

    //开始保存微信消息数据
    $saveflag = msg_save($input,$userid,$db);
    if($saveflag == 1){
        $result['code'] = 200;
        $result['msg'] = '消息保存成功';
    }else{
        $result['code'] = 201;
        $result['msg'] = '消息已存在';
    }
    echo json_encode($result);
    exit();

    //保存消息
    function msg_save($input,$userid,$db){
        $msgdata = $input['msgdata'];
        $msgchat = $input['msgchat'];
        $msgtochat = $input['msgtochat'];
        $msgmember = $input['msgmember'];
        $msgid = $msgdata['MsgId'];
        $query = "SELECT * FROM wxmsg where msgid='".$msgid."' and userid=".$userid;
        $row = $db->query($query)->fetch();
        if($row !== false){
            return 0;
        }

        $newmsgid = NumToStr($msgdata['NewMsgId']);
        $msgsource = 1;
        $chatroommembers = $msgchat['MemberCount'];
        $fromusername = $msgchat['UserName'];
        $actualusername = '';
        $fromnickname = addslashes(json_encode(emoji_docomo_to_unified($msgchat['NickName'])));
        $tousername = $msgtochat['UserName'];
        $tonickname = '';
        if(isset($msgtochat['NickName'])){
            $tonickname = addslashes(json_encode(emoji_docomo_to_unified($msgtochat['NickName'])));
        }
        /*
        if($msgchat['ContactFlag'] <= 0){
            $tonickname = addslashes(json_encode('自己'));
        }else{
            $tonickname = addslashes(json_encode('好友'));
        }
        */
        $isat = -1;
        $chatroomname = '';
        $chatroomowner = '';
        $remarkname = '';
        $displayname = '';
        $remarkflag = 0;
        if(strlen($msgchat['RemarkName']) > 0){
            $remarkflag = 1;
            $remarkname = addslashes(json_encode(emoji_docomo_to_unified($msgchat['RemarkName'])));
        }
        if(isset($msgtochat['RemarkName']) && strlen($msgtochat['RemarkName']) > 0){
            $remarkflag = 2;
            $remarkname = addslashes(json_encode(emoji_docomo_to_unified($msgtochat['RemarkName'])));
        }
        if(isset($msgdata['ActualUserName'])){
            $msgsource = 2;
            $chatroomname = addslashes(json_encode(emoji_docomo_to_unified($msgtochat['NickName'])));
            if(count($msgmember) > 0){
                $fromusername = $msgmember['UserName'];
                $fromnickname = addslashes(json_encode(emoji_docomo_to_unified($msgmember['NickName'])));
                $tousername = $msgchat['UserName'];
                $tonickname = addslashes(json_encode(emoji_docomo_to_unified($msgchat['NickName'])));
                $chatroomname = addslashes(json_encode(emoji_docomo_to_unified($msgchat['NickName'])));
                $displayname = addslashes(json_encode(emoji_docomo_to_unified($msgmember['DisplayName'])));
            }
            $actualusername = $msgdata['ActualUserName'];
            if($msgdata['isAt']){
                $isat = 1;
            }else{
                $isat = 0;
            }
            $remarkname = '';
        }
        $msgtype = $msgdata['MsgType'];
        $msgtypetext = $msgdata['Type'];
        $content = addslashes(json_encode(emoji_docomo_to_unified($msgdata['Content'])));
        $status = $msgdata['Status'];
        $createtime = $msgdata['CreateTime'];
        $runtime = time();
        $msgtext = $msgdata['Text'];
        $query = "INSERT INTO wxmsg (userid,msgid,newmsgid,msgsource,fromusername,actualusername,fromnickname,tousername,tonickname,msgtype,msgtypetext,content,status,createtime,runtime,isat,msgtext,chatroomname,chatroomowner,chatroommembers,remarkname,remarkflag,displayname) 
            VALUES (".$userid.",'".$msgid."','".$newmsgid."',".$msgsource.",'".$fromusername."','".$actualusername."','".$fromnickname."','".$tousername."','".$tonickname."',".$msgtype.",'".$msgtypetext."','".$content."',".$status.",".$createtime.",".$runtime.",".$isat.",'".$msgtext."','".$chatroomname."','".$chatroomowner."','".$chatroommembers."','".$remarkname."',".$remarkflag.",'".$displayname."')";
        //print_r($query);
        $aa = $db->exec($query);
        return 1;
    }

    //将科学计数法转换为原始数字字符串
    function NumToStr($num){ 
        if (stripos($num,'e')===false) return $num; 
        $num = trim(preg_replace('/[=\'"]/','',$num,1),'"');//出现科学计数法，还原成字符串 
        $result = ""; 
        while ($num > 0){ 
            $v = $num - floor($num / 10)*10; 
            $num = floor($num / 10); 
            $result   =   $v . $result; 
        }
        return $result; 
    }
?>