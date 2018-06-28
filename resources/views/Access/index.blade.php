<div class="row">
    <div class="col-md-12">
        <!-- BEGIN EXAMPLE TABLE PORTLET-->
        <div class="portlet light bordered">
            <div class="portlet-title">
                <div class="caption font-dark">
                    <i class="icon-wrench font-dark"></i>
                    <span class="caption-subject bold uppercase"> 权限节点管理 </span>
                </div>
            </div>
            <div class="portlet-body">
                <div class="table-toolbar">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="btn-group">
                                <a class="ajaxify btn sbold green" href="/access/add?pid=0"> 新增模块
                                    <i class="fa fa-plus"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <table class="table table-striped table-bordered table-hover table-checkable order-column" id="sample_1">
                    <thead>
                    <tr>
                        <th> ID </th>
                        <th> 名称 </th>
                        <th> URL </th>
                        <th> 状态 </th>
                        <th> 菜单状态 </th>
                        <th> 创建时间 </th>
                        <th> 添加方法 </th>
                        <th> 操作 </th>
                    </tr>
                    </thead>
                    <tbody>
                        <div>

                            @foreach ($res as $k => $v)
                                <tr class="odd gradeX">
                                    <td>
                                         @if($v['pid']==0)
                                             <button type="button" class="btn sbold green">{{ $v['id'] }}</button>
                                         @else
                                             |-{{ $v['id'] }}
                                         @endif
                                    </td>
                                    <td>{{ $v['name'] }}</td>
                                    <td>{{ $v['url'] }}</td>
                                    <td>
                                        @if($v['status'] == 0)
                                            <span class="label label-sm label-warning">无效</span>
                                        @else
                                            <span class="label label-sm label-success">有效</span>
                                        @endif

                                    </td>
                                    <td>
                                        @if($v['type'] == 0)
                                            <span class="label label-sm label-danger">否</span>
                                        @else
                                            <span class="label label-sm label-info">是</span>
                                        @endif
                                    </td>
                                    <td>{{ $v['addtime'] }}</td>
                                    <td>
                                        @if($v['pid']==0)
                                            <a class="ajaxify btn sbold green" href="/access/add?pid={{ $v['id'] }}"> 添加方法
                                                <i class="fa fa-pencil"></i>
                                            </a>
                                        @endif
                                    </td>
                                    <td>
                                        <a class="ajaxify btn sbold green" href="/access/update?id={{ $v['id'] }}"> 修改
                                            <i class="fa fa-pencil"></i>
                                        </a>
                                        <a class="ajaxify btn sbold green" href="/access/delete?id={{ $v['id'] }}"> 删除
                                            <i class="fa fa-minus"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </div>
                    </tbody>

                </table>
            </div>
        </div>
        <!-- END EXAMPLE TABLE PORTLET-->
    </div>
</div>
