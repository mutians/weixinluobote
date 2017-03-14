<?php
    include('head.php');
    loginCheck();

    if($_SESSION['usertype'] > 0){
        echo "<script type='text/javascript'>window.location.href='index.php'</script>";
        exit();
    }

    $query = "SELECT * FROM users where status > -1 order by id desc";
    $rows = $db->query($query)->fetchAll();
    
    callHead('所有用户','用户管理','所有用户');
?>
                    <div class="row">
                        <div class="col-md-12">
                            <!-- BEGIN EXAMPLE TABLE PORTLET-->
                            <div class="portlet light bordered">
                                <div class="portlet-body">
                                    <table class="table table-striped table-bordered table-hover table-checkable order-column" id="sample_1">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>用户名</th>
                                                <th>昵称</th>
                                                <th>用户类型</th>
                                                <th>注册时间</th>
                                                <th>最后登录</th>
                                                <th>IP</th>
                                                <th>状态</th>
                                                <th>操作</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                                foreach($rows as $row){
                                            ?>
                                            <tr class="odd gradeX">
                                                <td><?=$row['id']?></td>
                                                <td><?=$row['username']?></td>
                                                <td><?=$row['nickname']?></td>
                                                <td><?=formatUserType($row['usertype'])?></td>
                                                <td><?=formatDate($row['adddate'],1)?></td>
                                                <td>
                                                <?php
                                                    if(strlen($row['lastlogintime']) > 1){
                                                        echo date('Y-m-d H:i:s',$row['lastlogintime']);
                                                    }
                                                ?>
                                                </td>
                                                <td><?=$row['lastloginip']?></td>
                                                <td><?=formatUserStatus($row['status'])?></td>
                                                <td>
                                                    <?php
                                                        if($_SESSION['usertype'] <= $row['usertype']){
                                                            if($row['usertype'] > 0){
                                                                if($row['status'] == 0){
                                                    ?>
                                                    <a class="btn btn-outline btn-sm dark" href="useredit.php?id=<?=$row['id']?>&act=enabled&userstatus=1">
                                                            <i class="fa fa-check-square"></i> 启用
                                                    </a>
                                                    <?php
                                                                }else{
                                                    ?>
                                                    <a class="btn btn-outline btn-sm dark" href="useredit.php?id=<?=$row['id']?>&act=enabled&userstatus=0">
                                                            <i class="fa fa-check-square"></i> 禁用
                                                    </a>
                                                    <?php
                                                                }
                                                            }
                                                    ?>
                                                    <a class="btn btn-outline btn-sm dark" href="useredit.php?id=<?=$row['id']?>">
                                                        <i class="fa fa-edit"></i> 编辑
                                                    </a>
                                                    <?php
                                                            if($row['usertype'] > 0){
                                                    ?>
                                                    <a class="btn btn-outline btn-sm dark" href="useredit.php?id=<?=$row['id']?>&act=delete" data-toggle="confirmation" data-original-title="确认要删除吗？" data-placement="left">
                                                            <i class="fa fa-trash-o"></i> 删除
                                                    </a>
                                                    <?php
                                                            }
                                                        }
                                                    ?>
                                                </td>
                                            </tr>
                                            <?php
                                                }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <!-- END EXAMPLE TABLE PORTLET-->
                        </div>
                    </div>
        <?php
            include('foot.php');
        ?>
        <!-- BEGIN PAGE LEVEL PLUGINS -->
        <script src="/assets/global/scripts/datatable.js" type="text/javascript"></script>
        <script src="/assets/global/plugins/datatables/datatables.min.js" type="text/javascript"></script>
        <script src="/assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.js" type="text/javascript"></script>
        <script src="/assets/pages/scripts/table-datatables-managed.js" type="text/javascript"></script>
        <script src="/assets/global/plugins/bootstrap-confirmation/bootstrap-confirmation.js" type="text/javascript"></script>
        <!-- END PAGE LEVEL PLUGINS -->