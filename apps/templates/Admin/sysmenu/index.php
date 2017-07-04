<!-- 头部开始部分代码 -->
<?php echo $this->fetch('common/header-start.php');?>
<!-- Gritter -->
<link href="//static.tudouyu.cn/AdminInspinia/2.7.1/js/plugins/gritter/jquery.gritter.css" rel="stylesheet">
<!-- 头部结束部分代码 -->
<?php echo $this->fetch('common/header-end.php');?>
<body>
<div id="wrapper">
    <!-- 主体内容导航栏 -->
    <?php echo $this->fetch('common/main-left-navbar.php');?>
    <div id="page-wrapper" class="gray-bg dashbard-1">
        <!-- 主体顶部导航 -->
        <?php echo $this->fetch('common/main-top-navbar.php');?>
        <!-- 主体内容 -->
        <div class="wrapper wrapper-content  animated fadeInRight">
            <div class="row">
                <div class="col-md-4">
                    <div id="nestable-menu">
                        <div class="col-md-4">
                            <select class="form-control" name="moduleType" id="moduleType">
                                <?php foreach ($moduleTypeList as $moduleK => $moduleV){?>
                                <option value="<?php echo $moduleK;?>" <?php echo $moduleType == $moduleK ? 'selected':''?>><?php echo $moduleV;?></option>
                                <?php }?>
                            </select>
                        </div>
                        <button type="button" data-action="expand-all" class="btn btn-white btn-sm">全部展开</button>
                        <button type="button" data-action="collapse-all" class="btn btn-white btn-sm">全部收缩</button>
                        <button type="button" class="btn btn-outline btn-primary btn-sm" data-toggle="modal" data-target="#myModal" onclick="javascript:$('#form')[0].reset();"><i class="fa fa-plus"></i>添加菜单</button>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="ibox ">
                        <div class="ibox-title">
                            <h5>菜单列表</h5>
                        </div>
                        <div class="ibox-content">

                            <p class="m-b-lg">
                                你可以通过拖拽来调整菜单所属层级和菜单顺序。
                            </p>
                            <div class="dd" id="nestable">
                                <?php echo $nestableHtml;?>
                            </div>
                            <div class="m-t-md">
                                <h5>Serialised Output</h5>
                            </div>
                            <textarea id="nestable2-output" class="form-control"></textarea>
                        </div>

                    </div>
                </div>
            </div>
        </div>
        <div class="modal inmodal" id="myModal" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content animated fadeIn">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                        <h4 class="modal-title">添加菜单</h4>
                    </div>
                    <div class="modal-body">
                        <form role="form" id="form">
                            <input type="hidden" name="moduleType" id="moduleType" value="<?php echo $moduleType;?>">
                            <input type="hidden" name="menuId" id="menuId" value="0">
                            <div class="form-group">
                                <label>菜单名称</label>
                                <input type="text" placeholder="输入菜单名称" class="form-control" name="menuName" id="menuName" required>
                            </div>
                            <div class="form-group">
                                <label>父级菜单</label>
                                <select class="form-control m-b __web-inspector-hide-shortcut__" name="parentMenuId">
                                    <option value="0">请选择</option>
                                    <?php echo $menuTreeOption;?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>访问链接</label>
                                <input type="text" placeholder="例如：/Admin/Index/index" class="form-control" name="url" required>
                            </div>
                            <div class="form-group">
                                <label>菜单图标样式</label>
                                <input type="text" placeholder="例如：fa fa-sitemap" class="form-control" name="iconClass" id="iconClass">
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-white" data-dismiss="modal">关闭</button>
                        <button type="button" class="btn btn-primary" onclick="javascript:$('#form').submit();">保存</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- 主体页脚 -->
        <?php echo $this->fetch('common/main-footer.php');?>
    </div>
    <!-- 聊天窗口 -->
    <?php echo $this->fetch('common/small-chat-box.php');?>
    <!-- 右侧边栏 -->
    <?php echo $this->fetch('common/right-sidebar.php');?>
</div>
<!-- 文档页脚代码开始 -->
<?php echo $this->fetch('common/footer-start.php');?>
<!-- Nestable List -->
<script src="//static.tudouyu.cn/AdminInspinia/2.7.1/js/plugins/nestable/jquery.nestable.js"></script>

<script>
    $(document).ready(function(){
        //模块选择
        $("#moduleType").on('change', function () {
            window.location.href = '<?php echo $this->currentUrl?>?moduleType='+$(this).val();
        });
        //可嵌套列表
        var updateOutput = function (e) {
            var list = e.length ? e : $(e.target),
                output = list.data('output');
            if (window.JSON) {
                output.val(window.JSON.stringify(list.nestable('serialize')));//, null, 2));
            } else {
                output.val('JSON browser support required for this demo.');
            }
        };

        // activate Nestable for list
        $('#nestable').nestable({
            group: 1
        }).on('change', updateOutput);

        // output initial serialised data
        updateOutput($('#nestable').data('output', $('#nestable2-output')));

        $('#nestable-menu').on('click', function (e) {
            var target = $(e.target),
                action = target.data('action');
            if (action === 'expand-all') {
                $('.dd').nestable('expandAll');
            }
            if (action === 'collapse-all') {
                $('.dd').nestable('collapseAll');
            }
        });
        //表单验证
        $("#form").validate({
            rules: {
                menuName:{
                    required: true,
                },
                url: {
                    required: true,
                },
            },
            submitHandler: function(form) {
                form.ajaxSubmit({
                    type:'post',
                    dataType:'json',
                    success:function(data) {
                        switch (data.status){
                            case 'success':
                                toastr.success(data.message, data.title);
                                if (data.redirectUrl){
                                    setTimeout(function(){
                                        window.location.href = data.redirectUrl;
                                    }, 1000);
                                }
                                break;
                            case 'error':
                                toastr.error(data.message, data.title);
                                break;
                            case 'info':
                                toastr.info(data.message, data.title);
                                break;
                            default:
                                toastr.warning(data.message, data.title);
                                break;
                        }
                    }
                });
            }
        });
    });
</script>
<!-- 文档页脚代码结束 -->
<?php echo $this->fetch('common/footer-end.php');?>