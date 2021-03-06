<?php

namespace App\Http\Controllers\staff;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
use View;
use Validator;
use Hash;
use Carbon\Carbon;
use Input;
use Mail;
use Form;
use Auth;
use File;
use Config;
use App\Staff;
use App\Employee;
use App\Task;
use XeroLaravel;
use Cart;
use Datatables;
use URL;
use App\Http\Controllers\logdata\LogController;
 

class StaffCalenderController extends Controller
{
    function today_task()
    {
        $task = new Task();
        $staff = new Staff();
        $id = 0;
        $sessionData=Session::get('adminLog');
        $id = $sessionData['adminID'];
        $staffID = Session::get('staff_ID');

        $today_task_ar = array();
        $list_today_task_ar = array();

        $today_date = date('Y-m-d');
        $taks_ar = array('staff_id' => $id);

        ///->where('task_assign','like','%'.$staffID.'%')

        /*->orWhere(DB::raw("year(created_at)"),'=',date('Y'))
        ->orWhere(DB::raw("month(created_at)"),'=',date('m'))
        ->orWhere(DB::raw("day(created_at)"),'=',date('d'))*/

        //->orWhere('created_at','like',date('Y-m-d').'%')

        //Today Task
        $result_today = Task::select('*')
            ->where(DB::raw(" month(assign_date)" ) , '=',  date('m'))
            ->where(DB::raw(" year(assign_date)" ) , '=',  date('Y'))
            ->where(DB::raw(" day(assign_date)" ) ,  '=', date('d'))
            ->where('read_status','=',0)
            ->where('role','=','staff')
            ->where('task_assign','=',$staffID)
            ->get();

        if(!empty($result_today))
        {
            $no = 0;
            foreach ($result_today as $key => $value)
            {
                $today_task_ar[] = $value['title'];
                $list_today_task_ar[$no]['task_id'] = $value['task_id'];
                $list_today_task_ar[$no]['title'] = $value['title'];
                $list_today_task_ar[$no]['assign_date'] = date('m/d/Y',strtotime($value['assign_date']));
                $list_today_task_ar[$no]['completion_date'] = date('m/d/Y',strtotime($value['completion_date']));
                $list_today_task_ar[$no]['task_priority'] = $value['task_priority'];

                $staff_assign_name = '';
                $staff_assign_name_ar = array();
                if($value['task_assign'] != '')
                {
                    $staff_task_assign = explode(',',$value['task_assign']);
                    foreach($staff_task_assign as $key_assign => $value_assign)
                    {
                        $result_staff_task_assign = array();

                        if($value->role == 'staff'){
                            $result_staff_task_assign = Staff::select('first_name','last_name')->where('staff_id','=',$value_assign)->first();
                        }

                        if($value->role == 'employee'){
                            $result_staff_task_assign = Employee::select('first_name','last_name')->where('employee_id','=',$value_assign)->first();
                        }

                        if(!empty($result_staff_task_assign)){
                            $staff_assign_name_ar[] = $result_staff_task_assign->first_name.' '.$result_staff_task_assign->last_name;
                        }
                    }
                }
                $list_today_task_ar[$no]['task_staff'] = implode(',',$staff_assign_name_ar);
                $no++;
            }
            \Session::put('staffTodaytask' , $today_task_ar);
            \Session::save();

            \Session::put('staffTodaytask_list' , $list_today_task_ar);
            \Session::save();
        }

        //Update OverDue Task Details
        $result_task = Task::select('task_id','task_status')
            ->where('completion_date','<',date('Y-m-d').'T%')
            ->where('task_status','=','PENDING')
            ->get();

        /*$result_task = Task::select('task_id','task_status')
            ->where(DB::raw(" month(completion_date)" ) , '=',  date('m'))
            ->where(DB::raw(" year(completion_date)" ) , '=',  date('Y'))
            ->where(DB::raw(" day(completion_date)" ) ,  '<', date('d'))
            ->where('task_status','=','PENDING')
            ->get();*/

        if(!empty($result_task))
        {
            $update_array['task_status'] = 'OVERDUE';
            foreach($result_task as $key => $value)
            {
                $task->where('task_id', $value->task_id)->update($update_array);
            }
        }
    }

    function calenderData()
    {
        $staff = new Staff();
        $status_math = array('PENDING' => '#F9690E', 'WAITING' => '#F7CA18', 'OVERDUE' => '#6C7A89', 'COMPLETE' => '#26A65B', 'CANCEL' => '#F22613');

        $dt = Carbon::now();

        $data = array();
        $id = 0;
        $sessionData=Session::get('adminLog');
        $id = $sessionData['adminID'];
        $staffID = Session::get('staff_ID');

        $event = array();
        $data_ar = array();
        $today_task_ar = array();

        $today_date = date('Y-m-d');
        $taks_ar = array('staff_id' => $id);


        //All Task ID
        $result_task_data = Task::select('*')->orderBy('assign_date','desc')->get();
        if(!empty($result_task_data))
        {
            $event = array('pass' => 1);
            $no=0;
            foreach($result_task_data as $key => $value)
            {
                $data_ar = array();
                unset($value['id']);
                unset($value['created']);
                unset($value['updated_at']);
                unset($value['deleted_at']);

                $staffName = '';
                $staffName_ar = array();
                if($value['staff_id'] != '')
                {

                     $staffID = explode(',',$value['task_assign']);
                     foreach($staffID as $key_stff => $value_staff)
                     {
                        $select_staff = '';
                        if($value->role == 'staff'){
                            $select_staff = "`staff` WHERE `staff_id` = '".$value_staff."' LIMIT 1";
                        }
                        if($value->role == 'employee'){
                            $select_staff = "`employee` WHERE `employee_id` = '".$value_staff."' LIMIT 1";
                        }
                        $result = DB::table(DB::raw($select_staff))->get();
                        if(!empty($result))
                        {
                            foreach($result as $key_res => $value_res)
                            {
                                $staffName_ar[] = $value_res->first_name.' '.$value_res->last_name;
                            }
                        }
                     }
                }

                $data_ar = $value;
                $data_ar['color'] = '';
                $data_ar['task_user'] = '';

                if(!empty($staffName_ar)){
                    $data_ar['task_user'] = implode(',',$staffName_ar);
                }

                if (array_key_exists($value->task_status, $status_math)) {
                    $data_ar['color'] = $status_math[$value->task_status];
                }
                $event['task_data'][] = $data_ar;
                $no++;
            }
        }else{
            $event = array('pass' => 0);
        }

        $event = json_encode($event,true);

        //Today Task
        $this->today_task();

        return $event;
    }

    function calenderDragData()
    {
        $id = 0;
        $sessionData=Session::get('adminLog');
        $id = $sessionData['adminID'];
        $staffID = Session::get('staff_ID');

        $today = date('Y-m-d');
       /* $last_day = date('Y-m-d',strtotime('-7 days'));
        $next_day = date('Y-m-d',strtotime('+7 days'));

        $taks_ar = array('staff_id' => $id);
        //->where($taks_ar)
        $result_task = Task::select('*')
            ->where('task_assign','like','%'.$sessionData['unique_ID'].'%')
            ->whereBetween('assign_date',array($last_day, $next_day))
            ->orderBy('assign_date','desc')->get();*/

        /*$result_task = Task::select('*')
            ->where(DB::raw(" month(assign_date)" ) , '=',  date('m'))
            ->where(DB::raw(" year(assign_date)" ) , '=',  date('Y'))
            ->where(DB::raw(" day(assign_date)" ) ,  '=', date('d'))
            ->where('task_assign','like','%'.$staffID.'%')
            ->orderBy('assign_date','desc')->get();*/

        $result_task = Task::select('*')
            ->where('task_assign','like','%'.$sessionData['unique_ID'].'%')
            ->where('assign_date','like',$today.'%')
            ->orWhere('completion_date','like',$today.'%')
            ->orderBy('assign_date','desc')->get();

        return $result_task;
    }

    public function index()
    {
        $id = 0;
        $sessionData=Session::get('adminLog');
        $id = $sessionData['adminID'];

        if(isset($id) && ($id != 0))
        {
            $event = $this->calenderData();
            $data['event'] = $event;

            $data['task_data'] = $this->calenderDragData();

			return View::make('staff/task/taskCalender',$data);
        }else{
            return View::make('staff/index');
        }
    }

    public function changeTask()
    {
        $id = 0;
        $sessionData=Session::get('adminLog');
        $id = $sessionData['adminID'];

        $data = Input::all();
        $task = new Task();
        if(!empty($data))
        {
            $task_assigndate = date('Y-m-d',strtotime($data['data_assign_date']));
            $task_assigndate = $task_assigndate.'T'.date('H:i:s');
            $update_array['assign_date'] = $task_assigndate;

            $task_completiondate = date('Y-m-d',strtotime($data['data_completion_date']));
            $task_completiondate = $task_completiondate.'T'.date('H:i:s');
            $update_array['completion_date'] = $task_completiondate;

            $task->where('task_id', $data['data_taskID'])->update($update_array);

            $description = $sessionData['first_name'].' '.$sessionData['last_name'].' was Update Task Details';
            $logdata = array();
            $logdata['role'] = 'staff';
            $logdata['role_id'] = $id;
            $logdata['operation'] = 'Update Task Details';
            $logdata['description'] = $description;
            $logdata['role_date'] = date('Y-m-d');

            $result_logdata = (new LogController)->index($logdata);
        }
    }


    public function readTask()
    {
        $id = 0;
        $sessionData=Session::get('adminLog');
        $id = $sessionData['adminID'];

        $data = Input::all();
        $task = new Task();
        if(!empty($data))
        {
            $update_array['read_status'] = 1;
            $task->where('task_id', $data['data_taskID'])->update($update_array);

            $description = $sessionData['first_name'].' '.$sessionData['last_name'].' was Read Task Details';
            $logdata = array();
            $logdata['role'] = 'staff';
            $logdata['role_id'] = $id;
            $logdata['operation'] = 'Read Task Details';
            $logdata['description'] = $description;
            $logdata['role_date'] = date('Y-m-d');

            $result_logdata = (new LogController)->index($logdata);
        }
    }

}
