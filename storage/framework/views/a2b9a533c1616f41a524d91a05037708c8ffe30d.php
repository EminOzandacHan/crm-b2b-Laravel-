

<?php $__env->startSection('pagecss'); ?>
    <link href="<?php echo e(asset('assets/css/plugins/chosen/chosen.css')); ?>" rel="stylesheet">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('contentPages'); ?>
        <div class="row wrapper border-bottom white-bg page-heading">
            <div class="col-lg-10">
                <h2>Edit Task</h2>
                <ol class="breadcrumb">
                    <li>
                        <a href="<?php echo e(URL::to('/dealer')); ?>">Home</a>
                    </li>
                    <li>
                        <a href="<?php echo e(URL::to('/dealer/task')); ?>">All Task</a>
                    </li>
                    <li class="active">
                        <strong>All Task list</strong>
                    </li>
                </ol>
            </div>
        </div>
        <div class="wrapper wrapper-content animated fadeInRight">
            <div class="row">
                <div class="col-lg-12">
                    <div class="ibox float-e-margins">
                        <div class="ibox-title">
                            <h5>Edit Task</h5>
                            <div class="ibox-tools">
                                <a class="collapse-link">
                                    <i class="fa fa-chevron-up"></i>
                                </a>
                            </div>
                        </div>
                        <div class="ibox-content">
                            <form id="task_edit" name="task_edit" method="POST" action="<?php echo e(action('dealer\DealerTaskController@editData')); ?>" class="form-horizontal" enctype="multipart/form-data" style="min-height: 100vh;">

                                <input name="_token" type="hidden" id="hidden_token" value="<?php echo e(csrf_token()); ?>"/>
                                <input name="id" type="hidden" id="id" value="<?php echo e($task->id); ?>"/>
                                <input name="task_id" type="hidden" id="task_id" value="<?php echo e($task->task_id); ?>"/>


                                <div class="form-group">
                                    <label class="col-sm-2 control-label">Task Priority</label>
                                    <div class="col-sm-10">
                                        <select data-placeholder="Choose a Status..." class="form-control"  style="width: 100%;" tabindex="2" name="task_priority" id="task_priority">
                                            <?php
                                            foreach($priority_status as $key=> $value)
                                            { ?>
                                            <option value="<?php echo e($key); ?>"  <?php  if($task->task_priority == $key){ echo 'selected'; } ?> >
                                                <?php echo e($value); ?>

                                            </option>
                                            <?php  } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="hr-line-dashed"></div>


                                <div class="form-group">
                                    <label class="col-sm-2 control-label">Title</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="title" name="title" placeholder="jack" value="<?php echo e($task->title); ?>">
                                    </div>
                                </div>
                                <div class="hr-line-dashed"></div>

                                <div class="form-group">
                                    <label class="col-sm-2 control-label">Start Date</label>
                                    <div class="col-sm-10">
                                        <input type="text" required id="assign_date"  class="form-control datetd date" placeholder="dd/mm/yyyy" name="assign_date" value="<?php echo e(date('d-m-Y',strtotime($task->assign_date))); ?>">
                                    </div>
                                </div>
                                <div class="hr-line-dashed"></div>

                                <div class="form-group">
                                    <label class="col-sm-2 control-label">Completion Date</label>
                                    <div class="col-sm-10">
                                        <input type="text" required id="completion_date"  class="form-control datetd date" placeholder="dd/mm/yyyy" name="completion_date" value="<?php echo e(date('d-m-Y',strtotime($task->completion_date))); ?>">
                                    </div>
                                </div>
                                <div class="hr-line-dashed"></div>

                                <div class="form-group">
                                    <label class="col-sm-2 control-label">Description</label>
                                    <div class="col-sm-10">
                                        <textarea class="form-control" id="description" name="description" rows="8"><?php echo e($task->description); ?></textarea>
                                    </div>
                                </div>
                                <div class="hr-line-dashed"></div>

                                <div class="form-group">
                                    <label class="col-sm-2 control-label">Task Status</label>
                                    <div class="col-sm-10">
                                        <select data-placeholder="Choose a Status..." class="form-control"  style="width: 100%;" tabindex="2" name="task_status" id="task_status">
                                            <option value="">Select</option>
                                            <?php
                                            foreach($task_status as $key=> $value)
                                            { ?>
                                            <option value="<?php echo e($key); ?>"  <?php  if($task->task_status == $key){ echo 'selected'; } ?> >
                                                <?php echo e($value); ?>

                                            </option>
                                            <?php  } ?>
                                        </select>
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
		<?php $__env->stopSection(); ?>

        <?php $__env->startSection('pagescript'); ?>
            <?php echo $__env->make('dealer.includes.commonscript', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
            <script  src="<?php echo e(asset('assets/js/plugins/iCheck/icheck.min.js')); ?>"></script>
            <script src="<?php echo e(asset('assets/js/plugins/datapicker/bootstrap-datepicker.js')); ?>"></script>
            <script src="<?php echo e(asset('assets/js/plugins/chosen/chosen.jquery.js')); ?>"></script>

            <script type="text/javascript">
                $(function ()
                {

                    $('.i-checks').iCheck({
                        checkboxClass: 'icheckbox_square-green',
                        radioClass: 'iradio_square-green',
                    }).on('ifToggled', function() {

                        if($('input[name="send_mailMessage"]').val() == 0){
                            $('input[name="send_mailMessage"]').val(1);
                        }else{
                            $('input[name="send_mailMessage"]').val(0);
                        }
                    });


                    var config = {
                        '.chosen-select'           : {},
                        '.chosen-select-deselect'  : {allow_single_deselect:true},
                        '.chosen-select-no-single' : {disable_search_threshold:10},
                        '.chosen-select-no-results': {no_results_text:'Oops, nothing found!'},
                        '.chosen-select-width'     : {width:"95%"}
                    }
                    for (var selector in config) {
                        $(selector).chosen(config[selector]);
                    }

                    <?php  if(isset($task_assign_selected) && !empty($task_assign_selected))
                        {
                            foreach($task_assign_selected as $key => $value){
                        ?>
                            console.log('hii');
                            $('.chosen-select').val('<?php echo $value;?>').end().trigger('chosen:updated');
                    <?php } } else if(isset($task_assign_selected) && !empty($task_assign_selected)) { ?>
                            console.log('hii');
                            $('.chosen-select').val('<?php echo $task_assign_selected; ?>').end().trigger('chosen:updated');
                    <?php } ?>


                    $("#task_status").select2({
                        placeholder: "Select a Status",
                        allowClear: true
                    });


                    $('.date').datepicker({
                        todayBtn: "linked",
                        keyboardNavigation: false,
                        forceParse: false,
                        calendarWeeks: true,
                        autoclose: true,
                        format: 'dd-mm-yyyy'
                    });

                    $('.date').datepicker('update');


                    $('#btn_reset').click(function ()
                    {
                        window.location.reload();
                    });

                    $('#assign_date').datepicker({
                        format: 'dd-mm-yyyy'
                    }).on('changeDate', function(e) {
                        $('#task_edit').formValidation('revalidateField', 'assign_date');
                    });

                    $('#completion_date').datepicker({
                        format: 'dd-mm-yyyy'
                    }).on('changeDate', function(e) {
                        $('#task_edit').formValidation('revalidateField', 'completion_date');
                    });

                    $('#task_edit').formValidation({
                        framework: 'bootstrap',
                            excluded: ':disabled',
                            message: 'This value is not valid',
                            icon: {
                                valid: 'glyphicon glyphicon-ok',
                                invalid: 'glyphicon glyphicon-remove',
                                validating: 'glyphicon glyphicon-refresh'
                            },
                            fields: {
                                title: {
                                    validators: {
                                        notEmpty: {
                                            message: 'Enter Title'
                                        }
                                    }
                                },
                                assign_date: {
                                    validators: {
                                        notEmpty: {
                                            message: 'Enter Date'
                                        },
                                        date: {
                                            format: 'DD-MM-YYYY',
                                            max: 'completion_date',
                                            message: 'The Assign date is not a valid'
                                        }
                                    }
                                },
                                completion_date: {
                                    validators: {
                                        notEmpty: {
                                            message: 'Enter Date'
                                        },
                                        date: {
                                            format: 'DD-MM-YYYY',
                                            min: 'assign_date',
                                            message: 'The Completion date is not a valid'
                                        }
                                    }
                                },
                                description: {
                                    validators: {
                                        notEmpty: {
                                            message: 'Enter Description !'
                                        }
                                    }
                                }
                            }
                    });
                });
            </script>
        <?php $__env->stopSection(); ?>
<?php echo $__env->make('dealer.layouts.masterdealer', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>