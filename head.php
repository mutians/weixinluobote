<?php
include('conn.php');
include('page.class.php');

$webtitle = '罗伯特小管家';

function callHead($title='',$menu='微信管理',$submenu='微信消息',$page=''){
    if(isset($_SESSION['usertype']) && $_SESSION['usertype'] == 0){
        $menuList = array(
            array('微信管理','icon-layers',array(
                array('微信消息','icon-layers','msgs.php'),
                array('微信联系人','icon-plus','#'),
                array('标签云','icon-plus','#')
            )),
            array('账户设置','icon-users',array(
                array('微信账号','icon-users','api.php'),
                array('个人资料','icon-pencil','profile.php')
            )),
            array('用户管理','icon-users',array(
                array('所有用户','icon-users','useradmin.php'),
                array('新增用户','icon-user-follow','useradd.php')
            ))
        );
    }else{
        $menuList = array(
            array('微信管理','icon-layers',array(
                array('微信消息','icon-layers','msgs.php'),
                array('微信联系人','icon-plus','#'),
                array('标签云','icon-plus','#')
            )),
            array('账户设置','icon-users',array(
                array('微信账号','icon-users','api.php'),
                array('个人资料','icon-pencil','profile.php')
            ))
        );
    }
	
?>
<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en">
    <!--<![endif]-->
    <!-- BEGIN HEAD -->

    <head>
        <meta charset="utf-8" />
        <title><?=$title?> | <?=$GLOBALS['webtitle']?></title>
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta content="width=device-width, initial-scale=1" name="viewport" />
        <meta content="" name="description" />
        <meta content="" name="author" />
        <!-- BEGIN GLOBAL MANDATORY STYLES -->
        <link href="/assets/global/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
        <link href="/assets/global/plugins/simple-line-icons/simple-line-icons.min.css" rel="stylesheet" type="text/css" />
        <link href="/assets/global/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <link href="/assets/global/plugins/uniform/css/uniform.default.css" rel="stylesheet" type="text/css" />
        <link href="/assets/global/plugins/bootstrap-switch/css/bootstrap-switch.min.css" rel="stylesheet" type="text/css" />
        <!-- END GLOBAL MANDATORY STYLES -->
        <!-- BEGIN THEME GLOBAL STYLES -->
        <link href="/assets/global/css/components-rounded.min.css" rel="stylesheet" id="style_components" type="text/css" />
        <link href="/assets/global/css/plugins.min.css" rel="stylesheet" type="text/css" />
        <!-- END THEME GLOBAL STYLES -->
        <!-- BEGIN THEME LAYOUT STYLES -->
        <link href="/assets/layouts/layout/css/layout.min.css" rel="stylesheet" type="text/css" />
        <link href="/assets/layouts/layout/css/themes/darkblue.min.css" rel="stylesheet" type="text/css" id="style_color" />
        <link href="/assets/layouts/layout/css/custom.min.css" rel="stylesheet" type="text/css" />
        <link href="/assets/pages/css/main.css" rel="stylesheet" type="text/css" />
        <!-- END THEME LAYOUT STYLES -->
        <link rel="shortcut icon" href="favicon.ico" />
        <style>
            .page-sidebar .page-sidebar-menu .sub-menu, .page-sidebar-closed.page-sidebar-fixed .page-sidebar:hover .page-sidebar-menu .sub-menu{display:block;}
        </style>
    </head>
    <!-- END HEAD -->
    <?php
        if($page == ''){
    ?>
    <body class="page-header-fixed page-sidebar-closed-hide-logo page-container-bg-solid page-content-white">
        <!-- BEGIN HEADER -->
        <div class="page-header navbar navbar-fixed-top">
            <!-- BEGIN HEADER INNER -->
            <div class="page-header-inner ">
                <!-- BEGIN LOGO -->
                <div class="page-logo">
                    <a href="/" class="logo-default">
                        <?=$GLOBALS['webtitle']?> </a>
                    <div class="menu-toggler sidebar-toggler"> </div>
                </div>
                <!-- END LOGO -->
                <!-- BEGIN RESPONSIVE MENU TOGGLER -->
                <a href="javascript:;" class="menu-toggler responsive-toggler" data-toggle="collapse" data-target=".navbar-collapse"> </a>
                <!-- END RESPONSIVE MENU TOGGLER -->
                <!-- BEGIN TOP NAVIGATION MENU -->
                <div class="top-menu">
                    <ul class="nav navbar-nav pull-right">
                        <!-- BEGIN USER LOGIN DROPDOWN -->
                        <!-- DOC: Apply "dropdown-dark" class after below "dropdown-extended" to change the dropdown styte -->
                        <li class="dropdown dropdown-user">
                            <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
                                <img alt="" class="img-circle" src="/assets/layouts/layout/img/avatar.png" />
                                <span class="username username-hide-on-mobile"> <?=$_SESSION['nickname']?> </span>
                                <i class="fa fa-angle-down"></i>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-default">
                                <li>
                                    <a href="?t=logout">
                                        <i class="icon-logout"></i> 退出登录 </a>
                                </li>
                            </ul>
                        </li>
                        <!-- END USER LOGIN DROPDOWN -->
                    </ul>
                </div>
                <!-- END TOP NAVIGATION MENU -->
            </div>
            <!-- END HEADER INNER -->
        </div>
        <!-- END HEADER -->
        <!-- BEGIN HEADER & CONTENT DIVIDER -->
        <div class="clearfix"> </div>
        <!-- END HEADER & CONTENT DIVIDER -->
        <!-- BEGIN CONTAINER -->
        <div class="page-container">
            <?php
                if (isset($_SESSION['adminflag']) && $_SESSION['adminflag'] === true && isset($_SESSION['usertype']) && $_SESSION['usertype'] < 2){
            ?>
            <!-- BEGIN SIDEBAR -->
            <div class="page-sidebar-wrapper">
                <!-- BEGIN SIDEBAR -->
                <!-- DOC: Set data-auto-scroll="false" to disable the sidebar from auto scrolling/focusing -->
                <!-- DOC: Change data-auto-speed="200" to adjust the sub menu slide up/down speed -->
                <div class="page-sidebar navbar-collapse collapse">
                    <!-- BEGIN SIDEBAR MENU -->
                    <!-- DOC: Apply "page-sidebar-menu-light" class right after "page-sidebar-menu" to enable light sidebar menu style(without borders) -->
                    <!-- DOC: Apply "page-sidebar-menu-hover-submenu" class right after "page-sidebar-menu" to enable hoverable(hover vs accordion) sub menu mode -->
                    <!-- DOC: Apply "page-sidebar-menu-closed" class right after "page-sidebar-menu" to collapse("page-sidebar-closed" class must be applied to the body element) the sidebar sub menu mode -->
                    <!-- DOC: Set data-auto-scroll="false" to disable the sidebar from auto scrolling/focusing -->
                    <!-- DOC: Set data-keep-expand="true" to keep the submenues expanded -->
                    <!-- DOC: Set data-auto-speed="200" to adjust the sub menu slide up/down speed -->
                    <ul class="page-sidebar-menu  page-header-fixed " data-keep-expanded="false" data-auto-scroll="true" data-slide-speed="200">
                        <!-- DOC: To remove the sidebar toggler from the sidebar you just need to completely remove the below "sidebar-toggler-wrapper" LI element -->
                        <li class="sidebar-toggler-wrapper hide">
                            <!-- BEGIN SIDEBAR TOGGLER BUTTON -->
                            <div class="sidebar-toggler"> </div>
                            <!-- END SIDEBAR TOGGLER BUTTON -->
                        </li>
                        <?php
                        	foreach($menuList as $menuItem){
	                        	if($menuItem[0] == $menu){
	                        		echo '<li class="nav-item start active open">';
	                        	}else{
	                        		echo '<li class="nav-item open">';
	                        	}
                        ?>
                            <a href="javascript:;" class="nav-link nav-toggle">
                                <i class="<?=$menuItem[1]?>"></i>
                                <span class="title"><?=$menuItem[0]?></span>
                                <?php
		                        	if($menuItem[0] == $menu){
		                        		echo '<span class="selected"></span><span class="arrow open"></span>';
		                        	}else{
		                        		echo '<span class="arrow open"></span>';
		                        	}
		                        ?>
                            </a>
                            <ul class="sub-menu">
                            	<?php
		                        	foreach($menuItem[2] as $subMenuItem){
			                        	if($subMenuItem[0] == $submenu){
			                        		echo '<li class="nav-item start active open">';
			                        	}else{
			                        		echo '<li class="nav-item">';
			                        	}
		                        ?>
                                    <a href="<?=$subMenuItem[2]?>" class="nav-link">
                                        <i class="<?=$subMenuItem[1]?>"></i>
                                        <span class="title"><?=$subMenuItem[0]?></span>
                                        <?php
				                        	if($subMenuItem[0] == $submenu){
				                        		echo '<span class="selected"></span>';
				                        	}
				                        ?>
                                    </a>
                                </li>
                                <?php
		                    		}
		                    	?>
                            </ul>
                        </li>
                    	<?php
                    		}
                    	?>
                    </ul>
                    <!-- END SIDEBAR MENU -->
                    <!-- END SIDEBAR MENU -->
                </div>
                <!-- END SIDEBAR -->
            </div>
            <!-- END SIDEBAR -->
            <?php
                }
            ?>

            <!-- BEGIN CONTENT -->
            <div class="page-content-wrapper">
                <!-- BEGIN CONTENT BODY -->
                <div class="page-content">
                    <!-- BEGIN PAGE HEADER-->
                    <!-- BEGIN PAGE BAR -->
                    <div class="page-bar">
                        <ul class="page-breadcrumb">
                            <li>
                                <a href="#"><?=$menu?></a>
                            </li>
                        </ul>
                    </div>
                    <h3 class="page-title"> <?=$title?>
                        <small></small>
                    </h3>
                    <!-- END PAGE BAR-->
                    <!-- END PAGE HEADER-->
<?php
    }
}
?>