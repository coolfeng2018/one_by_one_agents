
<div class="tab-content">
    <div class="tab-pane active" id="tab_0">
        <div class="portlet box green">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-pencil"></i>权限节点修改
                </div>
            </div>
            <div class="portlet-body form">
                <!-- BEGIN FORM-->
                <form action="#" class="form-horizontal" method="post">
                    {{ csrf_field() }}
                    <div class="form-body">
                        @foreach ($res as $resources)
                        <div class="form-group">
                            <div class="col-md-4">
                                <input type="hidden" id="id" class="form-control input-circle" value="{{ $resources->id }}">
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-4">
                                <input type="hidden" id="pid" class="form-control input-circle" value="{{ $resources->pid }}">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-3 control-label">名称</label>
                            <div class="col-md-4">
                                <input type="text" id="name" class="form-control input-circle" value="{{$resources->name}}">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-3 control-label">URL</label>
                            <div class="col-md-4">
                                <input type="text" id="url" class="form-control input-circle" value="{{$resources->url}}">
                            </div>
                        </div>


                            <div class="form-group">
                                <label class="col-md-3 control-label">菜单状态</label>
                                <div class="col-md-4">
                                    <select id="type" class="form-control input-circle">
                                        <option value ="0" @if($resources->type==0) selected @endif>否</option>
                                        <option value ="1" @if($resources->type==1) selected @endif>是</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-3 control-label">状态</label>
                                <div class="col-md-4">
                                    <select id="status" class="form-control input-circle">
                                        <option value ="1"  @if($resources->type==1) selected @endif>有效</option>
                                        <option value ="0"  @if($resources->type==0) selected @endif>无效</option>
                                    </select>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="form-actions">
                        <div class="row">
                            <div class="col-md-offset-3 col-md-9">
                                <button id="save" type="button" class="btn btn-circle green">修改</button>
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
        var id=$('#id').val();
        var pid=$('#pid').val();
        var name=$('#name').val();
        var url=$('#url').val();
        var type=$('#type').val();
        var status=$('#status').val();

        $.ajax( {
            type : "post",
            url : "/access/save",
            dataType : 'json',
            data : {'_token':'{{csrf_token()}}',id:id,pid:pid,name:name,url:url,type:type,status:status},
            success : function(data) {
                if(data.status){
                    Layout.loadAjaxContent(data.url);
                }else{
                    alert('修改失败');
                }
            }
        });
    })
})
</script>

