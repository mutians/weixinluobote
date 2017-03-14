<?php
    include('head.php');
    include('emoji.php');
    loginCheck();

    @$id = $_SESSION['userid'];
    $page_no = 1;
    $page_size = 20;
    if (isset($_REQUEST["page_no"])) $page_no = Trim($_REQUEST["page_no"]);
    if (is_numeric($page_no)){
        $page_no = intval($page_no);
    }else{
        $page_no = 1;
    }
    if ($page_no <= 0){
        $page_no = 1;
    }
    $startno = ($page_no-1)*$page_size;
    $msgsource = 0;
    if (isset($_REQUEST["msgsource"])) $msgsource = urldecode(Trim($_REQUEST["msgsource"]));
    $fromusername = '';
    if (isset($_REQUEST["fromusername"])) $fromusername = urldecode(Trim($_REQUEST["fromusername"]));
    $actualusername = '';
    if (isset($_REQUEST["actualusername"])) $actualusername = urldecode(Trim($_REQUEST["actualusername"]));
    $tousername = '';
    if (isset($_REQUEST["tousername"])) $tousername = urldecode(Trim($_REQUEST["tousername"]));
    $query = "SELECT count(id) as total FROM wxmsg where userid='".$id."' and status > -1";
    if($msgsource > 0){
        $query .=  " and msgsource=".$msgsource;
    }
    if(strlen($fromusername) > 0){
        if($msgsource == 1){
            $query .=  " and (fromusername='".$fromusername."' or tousername='".$fromusername."')";
        }else{
            $query .=  " and fromusername='".$fromusername."'";
        }
    }
    if(strlen($actualusername) > 0){
        $query .=  " and actualusername='".$actualusername."'";
    }
    if(strlen($tousername) > 0){
        if($msgsource == 1 && strlen($actualusername) <= 0){
            $query .=  " and (fromusername='".$tousername."' or tousername='".$tousername."')";
        }else{
            $query .=  " and tousername='".$tousername."'";
        }
    }
    $row = $db->query($query)->fetch();
    $total_results = intval($row['total']);
    $query = "SELECT * FROM wxmsg where userid='".$id."' and status > -1";
    if($msgsource > 0){
        $query .=  " and msgsource=".$msgsource;
    }
    if(strlen($fromusername) > 0){
        if($msgsource == 1){
            $query .=  " and (fromusername='".$fromusername."' or tousername='".$fromusername."')";
        }else{
            $query .=  " and fromusername='".$fromusername."'";
        }
    }
    if(strlen($actualusername) > 0){
        $query .=  " and actualusername='".$actualusername."'";
    }
    if(strlen($tousername) > 0){
        if($msgsource == 1 && strlen($actualusername) <= 0){
            $query .=  " and (fromusername='".$tousername."' or tousername='".$tousername."')";
        }else{
            $query .=  " and tousername='".$tousername."'";
        }
    }
    $query .= " order by createtime desc,id desc LIMIT $startno,$page_size";
    //var_dump($query);
    $rows = $db->query($query)->fetchAll();

    if(isset($_REQUEST['act']) && $_REQUEST['act'] == 'search'){
        $q = $_REQUEST['q'];
        $page_no = $_REQUEST['page_no'];
        $page_size = $_REQUEST['page_size'];
        $cat = $_REQUEST['cat'];
        $itemloc = $_REQUEST['itemloc'];
        $sort = $_REQUEST['sort'];
        $sortby = $_REQUEST['sortby'];
        $sortfull = '';
        if(strlen($sort) > 0){
            $sortfull = $sort.$sortby;
        }
        $is_tmall = $_REQUEST['is_tmall'];
        $is_overseas = $_REQUEST['is_overseas'];
        $start_price = $_REQUEST['start_price'];
        $end_price = $_REQUEST['end_price'];
        $start_tk_rate = $_REQUEST['start_tk_rate'];
        $end_tk_rate = $_REQUEST['end_tk_rate'];
        $platform = $_REQUEST['platform'];

        $c = new TopClient;
        $c->appkey = $appkey;
        $c->secretKey = $secret;
        $c->format = 'json';
        $req = new TbkItemGetRequest;
        //需返回的字段列表
        $req->setFields("num_iid,title,pict_url,small_images,reserve_price,zk_final_price,user_type,provcity,item_url,seller_id,volume,nick");
        //查询关键词
        $req->setQ($q);
        //后台类目ID，用,分割，最大10个，该ID可以通过taobao.itemcats.get接口获取到
        if(strlen($cat) > 0){
            $req->setCat($cat);
        }
        //所在地
        if(strlen($itemloc) > 0){
            $req->setItemloc("杭州");
        }
        //排序_des（降序），排序_asc（升序），销量（total_sales），淘客佣金比率（tk_rate）， 累计推广量（tk_total_sales），总支出佣金（tk_total_commi）
        if(strlen($sortfull) > 0){
            $req->setSort($sortfull);
        }
        //是否商城商品，设置为true表示该商品是属于淘宝商城商品，设置为false或不设置表示不判断这个属性
        $req->setIsTmall($is_tmall);
        //是否海外商品，设置为true表示该商品是属于海外商品，设置为false或不设置表示不判断这个属性
        $req->setIsOverseas($is_overseas);
        //折扣价范围下限，单位：元
        if(strlen($start_price) > 0){
            $req->setStartPrice($start_price);
        }
        //折扣价范围上限，单位：元
        if(strlen($end_price) > 0){
            $req->setEndPrice($end_price);
        }
        //淘客佣金比率上限，如：1234表示12.34%
        if(strlen($start_tk_rate) > 0){
            $req->setStartTkRate($start_tk_rate);
        }
        //淘客佣金比率下限，如：1234表示12.34%
        if(strlen($end_tk_rate) > 0){
            $req->setEndTkRate($end_tk_rate);
        }
        //链接形式：1：PC，2：无线，默认：１ 
        $req->setPlatform($platform);
        //第几页，默认：１
        $req->setPageNo($page_no);
        //页大小，默认20，1~100
        $req->setPageSize($page_size);
        $result = $c->execute($req);
        //print_r(json_encode($result));
        if(isset($result->code)){
            $result->status = 1;
        }else{
            $result->status = 0;
            $pages = ceil($result->total_results/$page_size);
        }
        //echo $result;
        //exit();
    }

    callHead('微信消息','微信管理','微信消息');
?>
                    <link href="/assets/global/plugins/emoji/emoji.css" rel="stylesheet" type="text/css" />
                    <div class="row">
                        <div class="col-lg-12 col-xs-12 col-sm-12">
                            <div class="portlet light bordered">
                                <div class="portlet-body">
                                    <!-- BEGIN: Msg Content -->
                                    <div class="mt-comments">
                                        <?php
                                            if($total_results > 0){
                                                $p = new Page($total_results,10,$page_no,$page_size); //$p=new Page(总页数,显示页数,当前页码,每页显示条数,[链接]);
                                        ?>
                                        <div class="pageinfo"><?=$p->showPages(1)?></div>
                                        <?php
                                                foreach ($rows as $key => $value) {
                                        ?>
                                        <div class="mt-comment">
                                            
                                            <div class="mt-comment-body">
                                                <div class="mt-comment-info">
                                                    <div class="mt-comment-author">
                                                        <?php
                                                            $fromnickname = emoji_unified_to_html(json_decode($value['fromnickname']));
                                                            if($value['msgsource'] == 2){
                                                                if(strlen(json_decode($value['displayname'])) > 0){
                                                                    $fromnickname = emoji_unified_to_html(json_decode($value['displayname'])).'('.emoji_unified_to_html(json_decode($value['fromnickname'])).')';
                                                                }
                                                                if(strlen(json_decode($value['remarkname'])) > 0){
                                                                    $fromnickname = emoji_unified_to_html(json_decode($value['remarkname']));
                                                                }
                                                                echo '<i class="fa fa-users" aria-hidden="true"></i> <a href="?tousername='.$value['tousername'].'&msgsource=2">'.emoji_unified_to_html(json_decode($value['chatroomname'])).'</a> -> <a href="?tousername='.$value['tousername'].'&actualusername='.$value['actualusername'].'&msgsource=2">'.$fromnickname.'</a>';
                                                            }else{
                                                                if(strlen(json_decode($value['remarkname'])) > 0){
                                                                    if($value['remarkflag'] == 1){
                                                                        $value['fromnickname'] = $value['remarkname'];
                                                                    }
                                                                    if($value['remarkflag'] == 2){
                                                                        $value['tonickname'] = $value['remarkname'];
                                                                    }
                                                                }
                                                                echo '<i class="fa fa-user" aria-hidden="true"></i> <a href="?fromusername='.$value['fromusername'].'&msgsource=1">'.emoji_unified_to_html(json_decode($value['fromnickname'])).'</a> -> <a href="?tousername='.$value['tousername'].'&msgsource=1">'.emoji_unified_to_html(json_decode($value['tonickname'])).'</a>';
                                                            }
                                                        ?>
                                                    </div>
                                                    <div class="mt-comment-date"><?=date('Y-m-d H:i:s',$value['createtime'])?></div>
                                                </div>
                                                <div class="mt-comment-text"><?=nl2br(emoji_unified_to_html(json_decode($value['content'])))?></div>
                                            </div>
                                        </div>
                                        <?php
                                                }
                                        ?>
                                        <div class="pageinfo"><?=$p->showPages(1)?></div>
                                        <?php
                                            }else{
                                                echo '<div>没有数据</div>';
                                            }
                                        ?>
                                    </div>
                                    <!-- END: Msg Content -->
                                </div>
                            </div>
                        </div>
                    </div>
        <?php
            include('foot.php');
        ?>