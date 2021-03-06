@extends('admin/layouts/masteradmin')

@section('pagecss')
@stop

@section('contentPages')
        <div class="row wrapper border-bottom white-bg page-heading">
            <div class="col-lg-10">
                <h2>Edit Attribute</h2>
                <ol class="breadcrumb">
                    <li>
                        <a href="{{URL::to('/admin')}}">Home</a>
                    </li>
                    <li>
                        <a href="{{URL::to('/admin/attributeList')}}">All Attribute List</a>
                    </li>
                    <li class="active">
                        <strong>All Attribute list</strong>
                    </li>
                </ol>
            </div>
        </div>
        <div class="wrapper wrapper-content animated fadeInRight">
            <div class="row">
                <div class="col-lg-12">
                    <div class="ibox float-e-margins">
                        <div class="ibox-title">
                            <h5>Edit Attribute <small>Attribute Name</small></h5>
                            <div class="ibox-tools">
                                <a class="collapse-link">
                                    <i class="fa fa-chevron-up"></i>
                                </a>
                            </div>
                        </div>
                        <div class="ibox-content">
                            <form id="group_edit" name="group_edit" method="POST" action="{{ action('admin\AttributeController@editData') }}" class="form-horizontal" enctype="multipart/form-data" style="min-height: 100vh;">

                                <input name="_token" type="hidden" id="hidden_token" value="{{ csrf_token() }}"/>
                                <input name="attributeID" type="hidden" id="attributeID" value="{{  $attribute->attributeID }}"/>

                                <div class="form-group">
                                    <label class="col-sm-2 control-label">Group name</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="attributeName" name="attributeName" placeholder="jack" value="{{ $attribute->attributeName }}">
                                    </div>
                                </div>
                                <div class="hr-line-dashed"></div>

                                <div class="form-group">
                                    <div class="col-sm-4 col-sm-offset-2">
                                        <input type="submit" class="btn btn-primary" value="Update"/>
                                        <button class="btn btn-white" type="button" id="btn_reset">Reset</button>
                                    </div>
                                </div>

                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
		@stop()

        @section('pagescript')
            @include('admin.includes.commonscript')

            <script type="text/javascript">
                $(function ()
                {

                    $('#btn_reset').click(function ()
                    {
                        $('input[type="text"]').each(function(){
                            $(this).val('');
                        });

                        window.location.reload();
                    });

                    $('#group_edit').formValidation(
                    {
                        framework: 'bootstrap',
                        excluded: ':disabled',
                        message: 'This value is not valid',
                        icon: {
                            valid: 'glyphicon glyphicon-ok',
                            invalid: 'glyphicon glyphicon-remove',
                            validating: 'glyphicon glyphicon-refresh'
                        },
                        fields: {
						attributeName: {
                                validators: {
                                    notEmpty: {
                                        message: 'Enter Attribute Name'
                                    },
                                    stringLength: {
                                        min: 3,
                                      
                                        message: 'The Field must be more than 3 characters long'
                                    },
                                    regexp: {
                                        regexp: /^[a-z0-9\s]+$/i,
                                        message: 'This Field can consist of alphabetical characters and spaces only'
                                    }
                                }
                            }
                        }
                    });
                });
            </script>
        @stop()