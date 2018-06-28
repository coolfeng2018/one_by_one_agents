
<div class="tab-content">
    <div class="tab-pane active" id="tab_0">
        <div class="portlet box green">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-plus"></i>权限节点添加
                </div>
            </div>
            <div class="portlet-body form">
                <!-- BEGIN FORM-->
                <form action="#" class="form-horizontal" method="post">
                    {{ csrf_field() }}
                    <div class="form-body">
                        <div class="form-group">
                            <label class="col-md-3 control-label"></label>
                            <div class="col-md-4">
                                <input type="hidden" id="pid" class="form-control input-circle" value="{{$pid}}">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-3 control-label">名称</label>
                            <div class="col-md-4">
                                <input type="text" id="name" class="form-control input-circle" placeholder="名称">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-3 control-label">URL</label>
                            <div class="col-md-4">
                                <input type="text" id="url" class="form-control input-circle" placeholder="URL">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-3 control-label">菜单状态</label>
                            <div class="col-md-4">
                                <select id="type" class="form-control input-circle">
                                    <option value ="0">否</option>
                                    <option value ="1">是</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-3 control-label">状态</label>
                            <div class="col-md-4">
                                <select id="status" class="form-control input-circle">
                                    <option value ="1">有效</option>
                                    <option value ="0">无效</option>
                                </select>
                            </div>
                        </div>

                    </div>
                    <div class="form-actions">
                        <div class="row">
                            <div class="col-md-offset-3 col-md-9">
                                <button id="save" type="button" class="btn btn-circle green">保存</button>
                            </div>
                        </div>
                    </div>
                </form>
                <!-- END FORM-->
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(function(){

        $("#save").click(function(){
            var pid=$('#pid').val();
            var name=$('#name').val();
            var url=$('#url').val();
            var type=$('#type').val();
            var status=$('#status').val();

            $.ajax( {
                type : "post",
                url : "/access/save",
                dataType : 'json',
                data : {'_token':'{{csrf_token()}}',pid:pid,name:name,url:url,type:type,status:status},
                success : function(data) {
                    if(data.status){
                        Layout.loadAjaxContent(data.url);
                    }else{
                        alert('添加失败');
                    }
                }
            });
        })

   })
</script>
