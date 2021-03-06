

<?php $__env->startSection('pagecss'); ?>

    <style>
        a,a:hover, a:active, a:focus, a.class-refresh{
            outline: 0;
        }
        .fc-event{
            line-height: 1.4em;
        }
    </style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('contentPages'); ?>
        <div class="row wrapper border-bottom white-bg page-heading">
            <div class="col-lg-10">
                <h2>Calendar</h2>
                <ol class="breadcrumb">
                    <li>
                        <a href="<?php echo e(URL::to('/employee')); ?>">Home</a>
                    </li>
                    <li>
                        <a href="<?php echo e(URL::to('/employee/task')); ?>">All Task</a>
                    </li>
                    <li class="active">
                        <strong> Calendar </strong>
                    </li>
                </ol>
            </div>
        </div>

            <?php echo $__env->make('employee.task.taskCalenderData', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
		<?php $__env->stopSection(); ?>

        <?php $__env->startSection('pagescript'); ?>
            <script src="<?php echo e(asset('assets/js/plugins/fullcalendar/moment.min.js')); ?>"></script>
            <?php echo $__env->make('employee.includes.commonscript', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
            <!-- Full Calendar -->
            <script src="<?php echo e(asset('assets/js/plugins/fullcalendar/fullcalendar.min.js')); ?>"></script>

            <?php echo $__env->make('employee.task.taskCalenderScript', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
        <?php $__env->stopSection(); ?>
<?php echo $__env->make('employee.layouts.masteremployee', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>