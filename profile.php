<?php
    include('head.php');
    loginCheck2();
    
    @$id = $_SESSION['userid'];
    $query = "SELECT * FROM users where id='".$id."' and status > -1";
    $rows = $db->query($query)->fetchAll();
    $row = $rows[0];
    @$act = Trim($_REQUEST["act"]);
    if ($act == "useredit"){
        @$password = str_replace(" ","",trim($_POST["password"]));
        if(strlen($password) > 0){
            $password = sha1($password);
        }
        @$nickname = str_replace(" ","",trim($_POST["nickname"]));
        @$corp = trim($_POST["corp"]);
        if(strlen($password) > 0){
            $query = "UPDATE users SET password='".$password."', nickname='".$nickname."' WHERE id='".$id."' and status > -1";
        }else{
            $query = "UPDATE users SET nickname='".$nickname."' WHERE id='".$id."' and status > -1";
        }
        $db->exec($query);
        echo "<script type='text/javascript'>alert('保存成功');window.location.href='profile.php'</script>";
        exit();
    }
    callHead('个人资料','账户设置','个人资料');
?>
					<div class="row">
                        <div class="col-md-12">
                            <!-- BEGIN SAMPLE FORM PORTLET-->
                            <div class="portlet light bordered">
                                <div class="portlet-body form">
                                    <form class="form-horizontal" role="form" method="post" action="?act=useredit" id="useredit">
                                        <input type="hidden" name="id" id="id" value="<?=$row['id']?>" checked>
                                        <div class="form-body">
                                            <div class="alert alert-danger display-hide">
                                                <button class="close" data-close="alert"></button> 信息填写有误，请检查
                                            </div>
                                            <div class="alert alert-success display-hide">
                                                <button class="close" data-close="alert"></button> 验证通过
                                            </div>
                                            <?php
                                                if($row['usertype'] == 0){
                                            ?>
                                            <div class="form-group">
                                                <label class="col-md-2 control-label">用户类型<span class="required"></span></label>
                                                <div class="col-md-10"><p class="form-control-static"><?=formatUserType($row['usertype'])?></p></div>
                                            </div>
                                            <?php
                                                }
                                            ?>
                                            <div class="form-group">
                                                <label class="col-md-2 control-label">手机号码<span class="required"></span></label>
                                                <div class="col-md-10"><p class="form-control-static"><?=$row['username']?></p></div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-md-2 control-label">上次登录<span class="required"></span></label>
                                                <div class="col-md-10">
                                                	<p class="form-control-static">
                                                		<?php
                                                			if(strlen($row['lastlogintime']) > 1){
                                                				echo date('Y-m-d H:i:s',$row['lastlogintime']);
                                                			}else{
                                                				echo '--';
                                                			}
                                                		?>
                                                	</p>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-md-2 control-label">登录密码</label>
                                                <div class="col-md-10">
                                                    <input type="password" class="form-control" placeholder="****************" name="password" id="password">
                                                    <span class="help-block">长度6-16位</span>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-md-2 control-label">昵称<span class="required"> * </span></label>
                                                <div class="col-md-10">
                                                    <div class="input-icon right">
                                                        <i class="fa"></i>
                                                        <input type="text" class="form-control" placeholder="" name="nickname" id="nickname" value="<?=$row['nickname']?>">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-actions">
                                            <div class="row">
                                                <div class="col-md-offset-2 col-md-10">
                                                    <button type="submit" class="btn green">保存</button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <!-- END SAMPLE FORM PORTLET-->
                        </div>
                    </div>
<?php
    include('foot.php');
?>
                    <!-- BEGIN PAGE LEVEL PLUGINS -->
                    <script src="/assets/global/plugins/select2/js/select2.full.min.js" type="text/javascript"></script>
                    <script src="/assets/global/plugins/jquery-validation/js/jquery.validate.min.js" type="text/javascript"></script>
                    <script src="/assets/global/plugins/jquery-validation/js/additional-methods.min.js" type="text/javascript"></script>
                    <!-- END PAGE LEVEL PLUGINS -->
                    <!-- BEGIN PAGE LEVEL SCRIPTS -->
                    <script type="text/javascript">
                        var FormValidation = function () {
                            var handleValidation = function() {

                                var form1 = $('#useredit');
                                var error1 = $('.alert-danger', form1);
                                var success1 = $('.alert-success', form1);

                                form1.validate({
                                    errorElement: 'span', //default input error message container
                                    errorClass: 'help-block help-block-error', // default input error message class
                                    focusInvalid: false, // do not focus the last invalid input
                                    ignore: "",  // validate all fields including form hidden input
                                    rules: {
                                    	password: {
                                            minlength: 6,
                                            maxlength: 16,
                                            required: false
                                        },
                                        nickname: {
                                            minlength: 2,
                                            required: true
                                        },
                                        corp: {
                                            minlength: 2,
                                            required: true
                                        }
                                    },

                                    invalidHandler: function (event, validator) { //display error alert on form submit              
                                        success1.hide();
                                        error1.show();
                                        App.scrollTo(error1, -200);
                                    },

                                    errorPlacement: function (error, element) { // render error placement for each input type
                                        var icon = $(element).parent('.input-icon').children('i');
                                        icon.removeClass('fa-check').addClass("fa-warning");  
                                        icon.attr("data-original-title", error.text()).tooltip({'container': 'body'});
                                    },

                                    highlight: function (element) { // hightlight error inputs
                                        $(element)
                                            .closest('.form-group').removeClass("has-success").addClass('has-error'); // set error class to the control group   
                                    },

                                    unhighlight: function (element) { // revert the change done by hightlight
                                        
                                    },

                                    success: function (label, element) {
                                        var icon = $(element).parent('.input-icon').children('i');
                                        $(element).closest('.form-group').removeClass('has-error').addClass('has-success'); // set success class to the control group
                                        icon.removeClass("fa-warning").addClass("fa-check");
                                    },

                                    submitHandler: function (form) {
                                        success1.show();
                                        error1.hide();
                                        form[0].submit(); // submit the form
                                    }
                                });
                            }

                            return {
                                //main function to initiate the module
                                init: function () {
                                    handleValidation();
                                }
                            };
                        }();

                        jQuery(document).ready(function() {
                            FormValidation.init();
                        });
                    </script>
                    <!-- END PAGE LEVEL SCRIPTS -->
