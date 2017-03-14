<?php
    include('head.php');
    loginCheck();

    if($_SESSION['usertype'] > 0){
        echo "<script type='text/javascript'>window.location.href='index.php'</script>";
        exit();
    }

    @$act = Trim($_REQUEST["act"]);
    if ($act == "useradd"){
        @$id = str_replace(" ","",trim($_POST["id"]));
        @$usertype = str_replace(" ","",trim($_POST["usertype"]));
        @$username = str_replace(" ","",trim($_POST["username"]));
        @$password = sha1(str_replace(" ","",trim($_POST["username"])));
        @$nickname = str_replace(" ","",trim($_POST["nickname"]));
        @$status = str_replace(" ","",trim($_POST["status"]));
        $query = "SELECT * FROM users where username='".$username."' and status > -1";
        $rows = $db->query($query)->fetchAll();
        $num = count($rows);
        if ($num >= 1){
            echo "<script type='text/javascript'>alert('手机号码已存在');history.go(-1)</script>";
        }else{
            $query = "INSERT INTO users (username, password, usertype, nickname, adddate, status) 
            VALUES ('".$username."', '".$password."', '".$usertype."', '".$nickname."', '".time()."', '".$status."')";
            $db->exec($query);
            echo "<script type='text/javascript'>window.location.href='useradmin.php'</script>";
            exit();
        }
    }
    callHead('新增用户','用户管理','新增用户');
?>

                    <div class="row">
                        <div class="col-md-12">
                            <!-- BEGIN SAMPLE FORM PORTLET-->
                            <div class="portlet light bordered">
                                <div class="portlet-body form">
                                    <form class="form-horizontal" role="form" method="post" action="?act=useradd" id="useradd">
                                        <div class="form-body">
                                            <div class="alert alert-danger display-hide">
                                                <button class="close" data-close="alert"></button> 信息填写有误，请检查
                                            </div>
                                            <div class="alert alert-success display-hide">
                                                <button class="close" data-close="alert"></button> 验证通过
                                            </div>
                                            <div class="form-group">
                                                <label class="col-md-2 control-label">用户类型<span class="required"> * </span></label>
                                                <div class="col-md-10">
                                                    <div class="radio-list">
                                                        <label class="radio-inline">
                                                            <input type="radio" name="usertype" id="usertype" value="2" checked> <?=formatUserType(2)?> <span class="help-inline"> 只能查看授权的项目</span></label>
                                                        <label class="radio-inline">
                                                            <input type="radio" name="usertype" id="usertype" value="1"> <?=formatUserType(1)?> <span class="help-inline"> 可以新增项目和用户、查看授权的项目</span></label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-md-2 control-label">手机号码<span class="required"> * </span></label>
                                                <div class="col-md-10">
                                                    <div class="input-icon right">
                                                        <i class="fa"></i>
                                                        <input type="text" class="form-control" placeholder="" name="username" id="username">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-md-2 control-label">登录密码</label>
                                                <div class="col-md-10">
                                                    <input type="text" class="form-control" placeholder="默认为手机号码，此处无需填写" name="password" id="password" disabled>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-md-2 control-label">姓名<span class="required"> * </span></label>
                                                <div class="col-md-10">
                                                    <div class="input-icon right">
                                                        <i class="fa"></i>
                                                        <input type="text" class="form-control" placeholder="" name="nickname" id="nickname">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-md-2 control-label">状态<span class="required"> * </span></label>
                                                <div class="col-md-10">
                                                    <div class="radio-list">
                                                        <label class="radio-inline">
                                                            <input type="radio" name="status" id="status" value="1" checked> 启用 </label>
                                                        <label class="radio-inline">
                                                            <input type="radio" name="status" id="status" value="0"> 禁用 </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-actions">
                                            <div class="row">
                                                <div class="col-md-offset-2 col-md-10">
                                                    <button type="submit" class="btn green">提交</button>
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

                                var form1 = $('#useradd');
                                var error1 = $('.alert-danger', form1);
                                var success1 = $('.alert-success', form1);

                                form1.validate({
                                    errorElement: 'span', //default input error message container
                                    errorClass: 'help-block help-block-error', // default input error message class
                                    focusInvalid: false, // do not focus the last invalid input
                                    ignore: "",  // validate all fields including form hidden input
                                    rules: {
                                        nickname: {
                                            minlength: 2,
                                            required: true
                                        },
                                        corp: {
                                            minlength: 2,
                                            required: true
                                        },
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
