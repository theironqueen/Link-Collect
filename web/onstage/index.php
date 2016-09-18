<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="link collection">
    <meta name="author" content="游玩的兔子">
    <!-- 上述3个meta标签*必须*放在最前面，任何其他内容都*必须*跟随其后！ -->
    <title>link collect</title>

    <!-- Bootstrap -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/jquery-accordion-menu.css" rel="stylesheet" type="text/css" />



    <link href="css/index.css" rel="stylesheet">
    <link rel="shortcut icon" href="img/logo2.ico">
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="//cdn.bootcss.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="//cdn.bootcss.com/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
  <body>
    
    <section id="page-header">
      <header>
        <a id="logo" href="index.html"><img src="./img/logo.png" alt="logo"><span>Link Collect</span></a>
        <a id="admin" href="../backstage/index.php"><i class="glyphicon glyphicon-cog"></i>进入后台</a>
      </header>
    </section>
    <section id="page-body">
      <div class="container">
        <section id='page-nav' class='col-xs-3'>
          <div id="nav-box" class="jquery-accordion-menu black">
            <ul>
              <li class="active"><a href="#" title="0"><i class="fa fa-home"></i>常用链接</a></li>
              <?php 
                require_once("mainNav.php");
              ?>
            </ul>
          </div>
        </section>
        <section id='page-content' class='col-xs-9'>
          <div class="row">
            <div class="col-md-4 col-lg-3 col-sm-6 col-sm-push-6 col-md-push-8 col-lg-push-9">
              <div class="panel panel-default mypage-panel func-panel">
                <div class="panel-heading">
                  <h3 class="panel-title"></h3>
                </div>
                <div class="panel-body">
                  <div class="row">
                    <div class="stage">
                      <div class='layer'></div>
                      <div class='layer'></div>
                      <div class='layer'></div>
                      <div class='layer'></div>
                      <div class='layer'></div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="func-btn-box">
                      <div class="func-btn btn-2 add-tab"><i class='glyphicon glyphicon-tag'></i> <span>添加标签</span></div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="func-btn-box">
                      <div class="func-btn btn-2 add-link"><i class='glyphicon glyphicon-star'></i> <span>添加链接</span></div>
                    </div>
                  </div>
                </div>
                <div class="panel-page"><span class='glyphicon glyphicon-th-large'></span></div>
              </div>
            </div>
          </div>
        </section>
        <div id="link-modal" class="modal fade">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="LC_page_loader hide">
                  <div class="LC_spinner"></div>
              </div>
              <div class="modal-header">
                <button type="button" class="close " data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">添加链接</h4>
              </div>
              <div class="modal-body">
                <form class="form-horizontal">
                  <div class="form-group has-feedback">
                    <label class="control-label" for="tab-name">所属标签:</label>
                    <input type="text" class="form-control" id="tab-name" aria-describedby="tab-name-help" disabled>
                    <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                    <span id="tab-name-help" class="help-block">该内容自动生成，根据当前所点击的标签不同而改变</span>
                  </div>
                  <div class="form-group has-feedback">
                    <label class="control-label" for="link-name">链接名:</label>
                    <input type="text" class="form-control" id="link-name" aria-describedby="link-name-help">
                    <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                    <span id="link-name-help" class="help-block">请输入链接名</span>
                  </div>
                  <div class="form-group has-feedback">
                    <label class="control-label" for="link-address">链接地址:</label>
                    <input type="text" class="form-control" id="link-address" aria-describedby="link-address-help">
                    <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                    <span id="link-address-help" class="help-block">请输入链接地址</span>
                  </div>
                </form>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
                <button type="button" class="btn btn-primary">确定</button>
              </div>
            </div><!-- /.modal-content -->
          </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->
        <div id="tab-modal" class="modal fade">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="LC_page_loader hide">
                  <div class="LC_spinner"></div>
              </div>
              <div class="modal-header">
                <button type="button" class="close " data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">添加标签</h4>
              </div>
              <div class="modal-body">
                <form class="form-horizontal">
                  <div class="form-group has-feedback">
                    <label class="control-label" for="parent-tab-name">所属标签:</label>
                    <input type="text" class="form-control" id="parent-tab-name" aria-describedby="parent-tab-name-help" disabled>
                    <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                    <span id="parent-tab-name-help" class="help-block">该内容自动生成，根据当前所点击的标签不同而改变</span>
                  </div>
                  <div class="form-group has-feedback">
                    <label class="control-label" for="new-tab-name">标签名:</label>
                    <input type="text" class="form-control" id="new-tab-name" aria-describedby="new-tab-name-help">
                    <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                    <span id="new-tab-name-help" class="help-block">请输入标签名</span>
                  </div>
                </form>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
                <button type="button" class="btn btn-primary">确定</button>
              </div>
            </div><!-- /.modal-content -->
          </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->
        <div id="help-modal" class="modal fade">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close " data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Help</h4>
              </div>
              <div class="modal-body">
                <h2>当前页面无法添加链接，请先在左侧导航选择链接所属标签。如果没有标签，请先添加标签</h2>
              </div>
            </div><!-- /.modal-content -->
          </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->
      </div>
    </section>
    <section id="page-background">
      <div class=""></div>
      <div class=""></div>
    </section>
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="js/jquery-1.9.1.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="js/bootstrap.min.js"></script>
    <script src="js/jquery-accordion-menu.js" type="text/javascript"></script>


    <script type="text/javascript" src="js/data.js"></script>
    <script type="text/javascript" src="js/tool.js"></script>
    <script src="js/index.js"></script>
  </body>
</html>
