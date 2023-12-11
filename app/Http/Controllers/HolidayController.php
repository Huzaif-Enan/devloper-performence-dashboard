<?php

namespace App\Http\Controllers;

use App\DataTables\HolidayDataTable;
use App\Helper\Reply;
use App\Http\Requests\CommonRequest;
use App\Http\Requests\Holiday\CreateRequest;
use App\Http\Requests\Holiday\UpdateRequest;
use App\Models\AttendanceSetting;
use App\Models\GoogleCalendarModule;
use App\Models\Holiday;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use App\Services\Google;
use Illuminate\Support\Facades\DB;

class HolidayController extends AccountBaseController
{

    public function __construct()
    {
        parent::__construct();
        $this->pageTitle = 'app.menu.holiday';
    }

    public function index(HolidayDataTable $dataTable)
    {
        $viewPermission = user()->permission('view_holiday');
        abort_403(!in_array($viewPermission, ['all', 'added']));

        $this->currentYear = now()->format('Y');
        $this->currentMonth = now()->month;

        /* year range from last 5 year to next year */
        $years = [];
        $latestFifthYear = (int)Carbon::now()->subYears(5)->format('Y');
        $nextYear = (int)Carbon::now()->addYear()->format('Y');

        for ($i = $latestFifthYear; $i <= $nextYear; $i++) {
            $years[] = $i;
        }

        $this->years = $years;

        /* months */
        $this->months = ['january', 'february', 'march', 'april', 'may', 'june', 'july', 'august', 'september', 'october', 'november', 'december'];

        return $dataTable->render('holiday.index', $this->data);

    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|mixed|void
     */
    public function create()
    {
        $this->addPermission = user()->permission('add_holiday');
        abort_403(!in_array($this->addPermission, ['all', 'added']));

        if (request()->ajax()) {
            $this->pageTitle = __('app.menu.holiday');
            $html = view('holiday.ajax.create', $this->data)->render();
            return Reply::dataOnly(['status' => 'success', 'html' => $html, 'title' => $this->pageTitle]);
        }

        $this->view = 'holiday.ajax.create';

        return view('holiday.create', $this->data);

    }

    /**
     *
     * @param CreateRequest $request
     * @return void
     */
    public function store(CreateRequest $request)
    {
        $this->addPermission = user()->permission('add_holiday');

        abort_403(!in_array($this->addPermission, ['all', 'added']));

        $occassions = $request->occassion;
        $dates = $request->date;

        foreach ($dates as $index => $value) {
            if ($value != '') {

                $holiday = new Holiday();
                $holiday->date = Carbon::createFromFormat($this->global->date_format, $value)->format('Y-m-d');
                $holiday->occassion = $occassions[$index];
                $holiday->save();

                if($holiday) {
                    $holiday->event_id = $this->googleCalendarEvent($holiday);
                    $holiday->save();
                }
            }
        }

        if (request()->has('type')) {
            return redirect(route('holidays.index'));
        }

        $redirectUrl = urldecode($request->redirect_url);

        if ($redirectUrl == '') {
            $redirectUrl = route('holidays.index');
        }

        return Reply::successWithData(__('messages.holidayAddedSuccess'), ['redirectUrl' => $redirectUrl]);

    }

    /**
     * Display the specified holiday.
     */
    public function show(Holiday $holiday)
    {
        $this->holiday = $holiday;
        $this->viewPermission = user()->permission('view_holiday');
        abort_403(!($this->viewPermission == 'all' || ($this->viewPermission == 'added' && $this->holiday->added_by == user()->id)));

        $this->pageTitle = __('app.menu.holiday');

        if (request()->ajax()) {
            $html = view('holiday.ajax.show', $this->data)->render();
            return Reply::dataOnly(['status' => 'success', 'html' => $html, 'title' => $this->pageTitle]);
        }

        $this->view = 'holiday.ajax.show';

        return view('holiday.create', $this->data);

    }

    /**
     * @param Holiday $holiday
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|mixed|void
     */
    public function edit(Holiday $holiday)
    {
        $this->holiday = $holiday;
        $this->editPermission = user()->permission('edit_holiday');

        abort_403(!($this->editPermission == 'all' || ($this->editPermission == 'added' && $this->holiday->added_by == user()->id)));

        $this->pageTitle = __('app.menu.holiday');

        if (request()->ajax()) {
            $html = view('holiday.ajax.edit', $this->data)->render();
            return Reply::dataOnly(['status' => 'success', 'html' => $html, 'title' => $this->pageTitle]);
        }

        $this->view = 'holiday.ajax.edit';

        return view('holiday.create', $this->data);

    }

    /**
     * @param UpdateRequest $request
     * @param Holiday $holiday
     * @return array|void
     */
    public function update(UpdateRequest $request, Holiday $holiday)
    {
        $this->editPermission = user()->permission('edit_holiday');
        abort_403(!($this->editPermission == 'all' || ($this->editPermission == 'added' && $this->holiday->added_by == user()->id)));

        $data = $request->all();
        $data['date'] = Carbon::createFromFormat($this->global->date_format, $request->date)->format('Y-m-d');

        $holiday->update($data);

        if($holiday){
            $holiday->event_id = $this->googleCalendarEvent($holiday);
            $holiday->save();
        }

        return Reply::successWithData(__('messages.updateSuccess'), ['redirectUrl' => route('holidays.index')]);

    }

    /**
     * @param Holiday $holiday
     * @return array|void
     */
    public function destroy(Holiday $holiday)
    {
        $deletePermission = user()->permission('delete_holiday');
        abort_403(!($deletePermission == 'all' || ($deletePermission == 'added' && $holiday->added_by == user()->id)));

        $holiday->delete();
        return Reply::successWithData(__('messages.holidayDeletedSuccess'), ['redirectUrl' => route('holidays.index')]);

    }

    public function calendar(Request $request)
    {
        $this->viewPermission = user()->permission('view_holiday');

        abort_403(!($this->viewPermission == 'all' || $this->viewPermission == 'added'));

        $this->pageTitle = 'app.menu.calendar';

        if (request('start') && request('end')) {
            $holidayArray = array();

            $holidays = Holiday::orderBy('date', 'ASC');

            if (request()->searchText != '') {
                $holidays->where('holidays.occassion', 'like', '%' . request()->searchText . '%');
            }

            $holidays = $holidays->get();

            foreach ($holidays as $key => $holiday) {

                $holidayArray[] = [
                    'id' => $holiday->id,
                    'title' => $holiday->occassion,
                    'start' => $holiday->date->format('Y-m-d'),
                    'end' => $holiday->date->format('Y-m-d'),
                ];
            }

            return $holidayArray;
        }

        return view('holiday.calendar.index', $this->data);

    }

    public function applyQuickAction(Request $request)
    {
        abort_403(!in_array(user()->permission('edit_leave'), ['all', 'added']));

        if ($request->action_type === 'delete') {
            $this->deleteRecords($request);
            return Reply::success(__('messages.deleteSuccess'));
        }

        return Reply::error(__('messages.selectAction'));


    }

    protected function deleteRecords($request)
    {
        abort_403(user()->permission('delete_holiday') != 'all');

        Holiday::whereIn('id', explode(',', $request->row_ids))->delete();
    }

    public function markHoliday()
    {
        $this->addPermission = user()->permission('add_holiday');
        abort_403(!in_array($this->addPermission, ['all', 'added']));

        $this->weekMap = [
            0 => __('app.sunday'),
            1 => __('app.monday'),
            2 => __('app.tuesday'),
            3 => __('app.wednesday'),
            4 => __('app.thursday'),
            5 => __('app.friday'),
            6 => __('app.saturday'),
        ];

        $this->holidaysArray = $this->weekMap;

        return view('holiday.mark-holiday.index', $this->data);
    }

    public function missingNumber($num_list)
    {
        // construct a new array
        $new_arr = range(1, 7);

        if(is_null($num_list))
        {
            return $new_arr;
        }

        return array_diff($new_arr, $num_list);
    }

    public function markDayHoliday(CommonRequest $request)
    {
        $this->addPermission = user()->permission('add_holiday');
        abort_403(!in_array($this->addPermission, ['all', 'added']));

        if (!$request->has('office_holiday_days')) {
            return Reply::error(__('messages.checkDayHoliday'));
        }

        $year = now()->format('Y');

        if ($request->has('year')) {
            $year = $request->has('year');
        }

        $dayss = [];
        $this->days = AttendanceSetting::WEEKDAYS;;

        if ($request->office_holiday_days != null && count($request->office_holiday_days) > 0) {
            foreach ($request->office_holiday_days as $holiday) {
                $dayss[] = $this->days[($holiday)];
                $day = $holiday;

                $dateArray = $this->getDateForSpecificDayBetweenDates($year . '-01-01', $year . '-12-31', ($day));

                foreach ($dateArray as $date) {
                    Holiday::firstOrCreate([
                        'date' => $date,
                        'occassion' => $this->days[$day]
                    ]);
                }

                $this->googleCalendarEventMulti($day, $year, $this->days);

            }
        }

        return Reply::successWithData(__('messages.holidayAddedSuccess'), ['redirectUrl' => route('holidays.index')]);
    }

    public function getDateForSpecificDayBetweenDates($startDate, $endDate, $weekdayNumber)
    {
        $startDate = strtotime($startDate);
        $endDate = strtotime($endDate);

        $dateArr = [];

        do {
            if (date('w', $startDate) != $weekdayNumber) {
                $startDate += (24 * 3600); // add 1 day
            }
        } while (date('w', $startDate) != $weekdayNumber);


        while ($startDate <= $endDate) {
            $dateArr[] = date('Y-m-d', $startDate);
            $startDate += (7 * 24 * 3600); // add 7 days
        }

        return ($dateArr);
    }

    //  $totalCost = DB::table('users')
    //     ->join('projects', 'users.id', '=', 'projects.pm_id')
    //     ->join('project_milestones', 'projects.id', '=', 'project_milestones.project_id')
    //     ->join('payments', 'project_milestones.invoice_id', '=', 'payments.invoice_id')
    //     //->whereNotNull('project_milestones.invoice_id')
    //     ->whereBetween('payments.paid_on', [$startDate, $releaseEndDate])
    //     ->whereBetween('project_milestones.created_at', [$startDate, $assignEndDate])
    //     ->where('users.id', $pmId)
    //         //->sum('payments.amount');
    //         ->sum('project_milestones.cost');


    public function developerPerformence(Request $request)
    {

        $devId = $request->input('developerID');
        // $this->username = DB::table('users')->where('id',$devId)->value('name');
        $startDate = $request->input('start_date');
        $endDate1 = $request->input('end_date');
        $endDate = Carbon::parse($endDate1)->addDays(1)->format('Y-m-d');
      
        $this->startDate1 = Carbon::parse($startDate);
        $this->endDate1 = Carbon::parse($endDate1);
        $user_role = DB::table('users')->select('role_id')->where('id', $devId)->first();

      if($user_role->role_id==5){

        $this->username = DB::table('users')->where('id', $devId)->value('name');
        $this->developer_task_data = DB::table('tasks')
        ->join('task_users', 'tasks.id', '=', 'task_users.task_id')
        ->join('task_types', 'tasks.id', '=', 'task_types.task_id')
        ->select('tasks.id', 'tasks.heading', 'task_types.task_type', 'task_types.page_type', 'task_types.page_name', 'task_types.task_type_other', 'tasks.created_at',)
        ->where('tasks.created_at', '>=', $startDate)
        ->where('tasks.created_at', '<', $endDate)
        ->where('task_users.user_id', $devId)
        ->get();

        $this->number_of_tasks_received = DB::table('tasks')
            ->join('task_users', 'tasks.id', '=', 'task_users.task_id')
            ->where('tasks.created_at','>=', $startDate)
            ->where('tasks.created_at', '<', $endDate)
            ->where('task_users.user_id', $devId)
            ->count();

            $this->number_of_tasks_received_primary_page = DB::table('tasks')
            ->join('task_users', 'tasks.id', '=', 'task_users.task_id')
            ->join('task_types', 'tasks.id', '=', 'task_types.task_id')
            ->where('tasks.created_at', '>=', $startDate)
            ->where('tasks.created_at', '<', $endDate)
            ->where('task_types.page_type', '=', 'Primary Page Development')
            ->where('task_users.user_id', $devId)
            ->count();

        $this->number_of_tasks_received_secondary_page = DB::table('tasks')
            ->join('task_users', 'tasks.id', '=', 'task_users.task_id')
            ->join('task_types', 'tasks.id', '=', 'task_types.task_id')
            ->where('tasks.created_at', '>=', $startDate)
            ->where('tasks.created_at', '<', $endDate)
            ->where('task_types.page_type', '=', 'Secondary Page Development')
            ->where('task_users.user_id', $devId)
            ->count();

        $this->submit_number_of_tasks_for_this_month = DB::table('task_submissions')
        ->join('tasks', 'task_submissions.task_id', '=', 'tasks.id')
        ->where('task_submissions.created_at', '>=', $startDate)
        ->where('task_submissions.created_at', '<', $endDate)
        ->where('tasks.created_at', '>=', $startDate)
        ->where('tasks.created_at', '<', $endDate)
        ->where('task_submissions.user_id', $devId)
        ->where('task_submissions.submission_no', '=', 1)
        ->distinct('task_submissions.created_at')
        ->distinct('tasks.id') 
        ->count();

        $this->submit_number_of_tasks_in_this_month = DB::table('task_submissions')
        ->join('tasks', 'task_submissions.task_id', '=', 'tasks.id')
        ->where('task_submissions.created_at', '>=', $startDate)
        ->where('task_submissions.created_at', '<', $endDate)
        ->where('task_submissions.user_id', $devId)
        ->where('task_submissions.submission_no', '=', 1)
        ->distinct('task_submissions.created_at')
        ->distinct('tasks.id')
        ->count();

        $this->submit_number_of_tasks_primary_page_for_this_month = DB::table('task_submissions')
        ->join('tasks', 'task_submissions.task_id', '=', 'tasks.id')
        ->join('task_types', 'task_submissions.task_id', '=', 'task_types.task_id')
        ->where('task_submissions.created_at', '>=', $startDate)
        ->where('task_submissions.created_at', '<', $endDate)
        ->where('tasks.created_at', '>=', $startDate)
        ->where('tasks.created_at', '<', $endDate)
        ->where('task_submissions.user_id', $devId)
        ->where('task_submissions.submission_no', '=', 1)
        ->where('task_types.page_type', '=', 'Primary Page Development')
        ->distinct('task_submissions.created_at')
        ->distinct('tasks.id') 
        ->count();

        $this->submit_number_of_tasks_primary_page_in_this_month = DB::table('task_submissions')
        ->join('tasks', 'task_submissions.task_id', '=', 'tasks.id')
        ->join('task_types', 'task_submissions.task_id', '=', 'task_types.task_id')
        ->where('task_submissions.created_at', '>=', $startDate)
        ->where('task_submissions.created_at', '<', $endDate)
        ->where('task_submissions.user_id', $devId)
        ->where('task_submissions.submission_no', '=', 1)
        ->where('task_types.page_type', '=', 'Primary Page Development')
        ->distinct('task_submissions.created_at')
        ->distinct('tasks.id')
        ->count();

        $this->submit_number_of_tasks_secondary_page_for_this_month = DB::table('task_submissions')
        ->join('tasks', 'task_submissions.task_id', '=', 'tasks.id')
        ->join('task_types', 'task_submissions.task_id', '=', 'task_types.task_id')
        ->where('task_submissions.created_at', '>=', $startDate)
        ->where('task_submissions.created_at', '<', $endDate)
        ->where('tasks.created_at', '>=', $startDate)
        ->where('tasks.created_at', '<', $endDate)
        ->where('task_submissions.user_id', $devId)
        ->where('task_submissions.submission_no', '=', 1)
        ->where('task_types.page_type', '=', 'Secondary Page Development')
        ->distinct('task_submissions.created_at')
        ->distinct('tasks.id')
        ->count();

        $this->submit_number_of_tasks_secondary_page_in_this_month = DB::table('task_submissions')
        ->join('tasks', 'task_submissions.task_id', '=', 'tasks.id')
        ->join('task_types', 'task_submissions.task_id', '=', 'task_types.task_id')
        ->where('task_submissions.created_at', '>=', $startDate)
        ->where('task_submissions.created_at', '<', $endDate)
        ->where('task_submissions.user_id', $devId)
        ->where('task_submissions.submission_no', '=', 1)
        ->where('task_types.page_type', '=', 'Secondary Page Development')
        ->distinct('task_submissions.created_at')
        ->distinct('tasks.id')
        ->count();


        //-----------------------------number of tasks approved in first attempt(in cycle) Client-----------------------//

        $number_of_tasks_approved = DB::table('tasks')
        ->join('task_users', 'tasks.id', '=', 'task_users.task_id')
        ->join('task_approves', 'tasks.id', '=', 'task_approves.task_id')
        ->select('tasks.id')
        ->where('tasks.board_column_id', '=', 4)
        ->where('tasks.updated_at', '>=', $startDate)
        ->where('tasks.updated_at', '<', $endDate)
        ->where('task_approves.created_at', '>=', $startDate)
        ->where('task_approves.created_at', '<', $endDate)
        ->where('task_users.user_id', $devId)
        ->get();
        $first_attempt_approve_task = 0;
        foreach ($number_of_tasks_approved as $task) {

           

            $number_of_tasks = DB::table('task_submissions')
            ->join('tasks', 'task_submissions.task_id', '=', 'tasks.id')
                ->where('task_submissions.task_id', $task->id)
                ->distinct('task_submissions.created_at')
                ->count();
            if ($number_of_tasks == 1) {
                $first_attempt_approve_task++;
            }
        }

        $number_of_tasks_approved = DB::table('tasks')
        ->join('task_users', 'tasks.id', '=', 'task_users.task_id')
        ->join('task_approves', 'tasks.id', '=', 'task_approves.task_id')
        ->join('task_types', 'tasks.id', '=', 'task_types.task_id')
        ->select('tasks.id')
        ->where('tasks.board_column_id', '=', 4)
        ->where('tasks.updated_at', '>=', $startDate)
        ->where('tasks.updated_at', '<', $endDate)
        ->where('task_approves.created_at', '>=', $startDate)
        ->where('task_approves.created_at', '<', $endDate)
        ->where('task_types.page_type', '=', 'Primary Page Development')
        ->where('task_users.user_id', $devId)
        ->get();
        $first_attempt_approve_task_primary_page = 0;
        foreach ($number_of_tasks_approved as $task) {
            

            $number_of_tasks = DB::table('task_submissions')
            ->join('tasks', 'task_submissions.task_id', '=', 'tasks.id')
                ->where('task_submissions.task_id', $task->id)
                ->distinct('task_submissions.created_at')
                ->count();
            if ($number_of_tasks == 1) {
                $first_attempt_approve_task_primary_page++;
            }
        }

        $number_of_tasks_approved = DB::table('tasks')
        ->join('task_users', 'tasks.id', '=', 'task_users.task_id')
        ->join('task_approves', 'tasks.id', '=', 'task_approves.task_id')
        ->join('task_types', 'tasks.id', '=', 'task_types.task_id')
        ->select('tasks.id')
         ->where('tasks.board_column_id', '=', 4)
        ->where('tasks.updated_at', '>=', $startDate)
         ->where('tasks.updated_at', '<', $endDate)
        ->where('task_approves.created_at', '>=', $startDate)
        ->where('task_approves.created_at', '<', $endDate)
        ->where('task_types.page_type', '=', 'Secondary Page Development')
        ->where('task_users.user_id', $devId)
        ->get();
        $first_attempt_approve_task_secondary_page = 0;
        foreach ($number_of_tasks_approved as $task) {
           

            $number_of_tasks = DB::table('task_submissions')
            ->join('tasks', 'task_submissions.task_id', '=', 'tasks.id')
                ->where('task_submissions.task_id', $task->id)
                ->distinct('task_submissions.created_at')
                ->count();
            if ($number_of_tasks == 1) {
                $first_attempt_approve_task_secondary_page++;
            }
        }

        $this->first_attempt_approve_task_in_this_month_client = $first_attempt_approve_task;
        $this->first_attempt_approve_task_primary_page_in_this_month_client = $first_attempt_approve_task_primary_page;
        $this->first_attempt_approve_task_secondary_page_in_this_month_client = $first_attempt_approve_task_secondary_page;

        //-----------------------------number of tasks approved in first attempt(for cycle) Client-----------------------//

        $number_of_tasks_approved = DB::table('tasks')
        ->join('task_users', 'tasks.id', '=', 'task_users.task_id')
        ->join('task_approves', 'tasks.id', '=', 'task_approves.task_id')
        ->select('tasks.id')
        ->where('tasks.board_column_id', '=', 4)
        ->where('tasks.updated_at', '>=', $startDate)
        ->where('tasks.updated_at', '<', $endDate)
        ->where('task_approves.created_at', '>=', $startDate)
        ->where('task_approves.created_at', '<', $endDate)
        ->where('tasks.created_at', '>=', $startDate)
        ->where('tasks.created_at', '<', $endDate)
        ->where('task_users.user_id', $devId)
        ->get();

        $first_attempt_approve_task = 0;
        foreach ($number_of_tasks_approved as $task) {
            $number_of_tasks = DB::table('task_submissions')
            ->join('tasks', 'task_submissions.task_id', '=', 'tasks.id')
                ->where('task_submissions.task_id', $task->id)
                ->distinct('task_submissions.created_at')
                ->count();
            if ($number_of_tasks == 1) {
                $first_attempt_approve_task++;
            }
        }

        $number_of_tasks_approved = DB::table('tasks')
        ->join('task_users', 'tasks.id', '=', 'task_users.task_id')
        ->join('task_approves', 'tasks.id', '=', 'task_approves.task_id')
        ->join('task_types', 'tasks.id', '=', 'task_types.task_id')
        ->select('tasks.id')
        ->where('tasks.board_column_id', '=', 4)
        ->where('tasks.updated_at', '>=', $startDate)
        ->where('tasks.updated_at', '<', $endDate)
        ->where('task_approves.created_at', '>=', $startDate)
        ->where('task_approves.created_at', '<', $endDate)
        ->where('tasks.created_at', '>=', $startDate)
        ->where('tasks.created_at', '<', $endDate)
        ->where('task_types.page_type', '=', 'Primary Page Development')
        ->where('task_users.user_id', $devId)
        ->get();

        $first_attempt_approve_task_primary_page = 0;
        foreach ($number_of_tasks_approved as $task) {
            
            $number_of_tasks = DB::table('task_submissions')
            ->join('tasks', 'task_submissions.task_id', '=', 'tasks.id')
                ->where('task_submissions.task_id', $task->id)
                ->distinct('task_submissions.created_at')
                ->count();
            if ($number_of_tasks == 1) {
                $first_attempt_approve_task_primary_page++;
            }
        }

        $number_of_tasks_approved = DB::table('tasks')
        ->join('task_users', 'tasks.id', '=', 'task_users.task_id')
        ->join('task_approves', 'tasks.id', '=', 'task_approves.task_id')
        ->join('task_types', 'tasks.id', '=', 'task_types.task_id')
        ->select('tasks.id')
        ->where('tasks.board_column_id', '=', 4)
        ->where('tasks.updated_at', '>=', $startDate)
        ->where('tasks.updated_at', '<', $endDate)
        ->where('task_approves.created_at', '>=', $startDate)
        ->where('task_approves.created_at', '<', $endDate)
        ->where('tasks.created_at', '>=', $startDate)
        ->where('tasks.created_at', '<', $endDate)
        ->where('task_types.page_type', '=', 'Secondary Page Development')
        ->where('task_users.user_id', $devId)
        ->get();

        $first_attempt_approve_task_secondary_page = 0;
        foreach ($number_of_tasks_approved as $task) {

            $number_of_tasks = DB::table('task_submissions')
            ->join('tasks', 'task_submissions.task_id', '=', 'tasks.id')
                ->where('task_submissions.task_id', $task->id)
                ->distinct('task_submissions.created_at')
                ->count();
            if ($number_of_tasks == 1) {
                $first_attempt_approve_task_secondary_page++;
            }
        }

        $this->first_attempt_approve_task_for_this_month_client = $first_attempt_approve_task;
        $this->first_attempt_approve_task_primary_page_for_this_month_client = $first_attempt_approve_task_primary_page;
        $this->first_attempt_approve_task_secondary_page_for_this_month_client = $first_attempt_approve_task_secondary_page;


    //-----------------------------number of tasks approved in first attempt(in cycle) Lead Developer-----------------------//

        $number_of_tasks_approved = DB::table('tasks')
        ->join('task_users', 'tasks.id', '=', 'task_users.task_id')
        ->join('task_approves', 'tasks.id', '=', 'task_approves.task_id')
        ->select('tasks.id')
        ->where('task_approves.created_at', '>=', $startDate)
        ->where('task_approves.created_at', '<', $endDate)
        ->where('task_users.user_id', $devId)
        ->get();
        $first_attempt_approve_task=0;
        foreach ($number_of_tasks_approved as $task) {

            $min_approve_date = DB::table('task_approves')
                ->select('task_approves.created_at')
                ->where('task_approves.task_id', $task->id)
                ->orderBy('task_approves.created_at', 'asc')
                ->first();

            $number_of_tasks = DB::table('task_submissions')
                ->join('tasks', 'task_submissions.task_id', '=', 'tasks.id')
                ->where('task_submissions.task_id', $task->id)
                ->where('task_submissions.created_at', '<', $min_approve_date->created_at)
                ->distinct('task_submissions.created_at')
                ->count();
            if($number_of_tasks==1){
                $first_attempt_approve_task++;

            }                
        }

        $number_of_tasks_approved = DB::table('tasks')
        ->join('task_users', 'tasks.id', '=', 'task_users.task_id')
        ->join('task_approves', 'tasks.id', '=', 'task_approves.task_id')
        ->join('task_types', 'tasks.id', '=', 'task_types.task_id')
        ->select('tasks.id')
        ->where('task_approves.created_at', '>=', $startDate)
        ->where('task_approves.created_at', '<', $endDate)
        ->where('task_types.page_type', '=', 'Primary Page Development')
        ->where('task_users.user_id', $devId)
        ->get();
        $first_attempt_approve_task_primary_page = 0;
        foreach ($number_of_tasks_approved as $task) {
            $min_approve_date = DB::table('task_approves')
                ->select('task_approves.created_at')
                ->where('task_approves.task_id', $task->id)
                ->orderBy('task_approves.created_at', 'asc')
                ->first();

            $number_of_tasks = DB::table('task_submissions')
            ->join('tasks', 'task_submissions.task_id', '=', 'tasks.id')
            ->where('task_submissions.task_id', $task->id)
            ->where('task_submissions.created_at', '<', $min_approve_date->created_at)
            ->distinct('task_submissions.created_at')
            ->count();
            if ($number_of_tasks ==1) {
                $first_attempt_approve_task_primary_page++;
            }
        }

        $number_of_tasks_approved = DB::table('tasks')
        ->join('task_users', 'tasks.id', '=', 'task_users.task_id')
        ->join('task_approves', 'tasks.id', '=', 'task_approves.task_id')
        ->join('task_types', 'tasks.id', '=', 'task_types.task_id')
        ->select('tasks.id')
        ->where('task_approves.created_at', '>=', $startDate)
        ->where('task_approves.created_at', '<', $endDate)
        ->where('task_types.page_type', '=', 'Secondary Page Development')
        ->where('task_users.user_id', $devId)
        ->get();
        $first_attempt_approve_task_secondary_page = 0;
        foreach ($number_of_tasks_approved as $task) {
            $min_approve_date = DB::table('task_approves')
            ->select('task_approves.created_at')
            ->where('task_approves.task_id', $task->id)
            ->orderBy('task_approves.created_at', 'asc')
            ->first();

            $number_of_tasks = DB::table('task_submissions')
            ->join('tasks', 'task_submissions.task_id', '=', 'tasks.id')
            ->where('task_submissions.task_id', $task->id)
            ->where('task_submissions.created_at', '<', $min_approve_date->created_at)
            ->distinct('task_submissions.created_at')
             ->count();
            if ($number_of_tasks ==1) {
                $first_attempt_approve_task_secondary_page++;
            }
        }

      $this->first_attempt_approve_task_in_this_month= $first_attempt_approve_task;
      $this->first_attempt_approve_task_primary_page_in_this_month= $first_attempt_approve_task_primary_page;
      $this->first_attempt_approve_task_secondary_page_in_this_month= $first_attempt_approve_task_secondary_page;

      //-----------------------------number of tasks approved in first attempt(for cycle) Lead Developer-----------------------//

        $number_of_tasks_approved = DB::table('tasks')
        ->join('task_users', 'tasks.id', '=', 'task_users.task_id')
        ->join('task_approves', 'tasks.id', '=', 'task_approves.task_id')
        ->select('tasks.id')
        ->where('task_approves.created_at', '>=', $startDate)
        ->where('task_approves.created_at', '<', $endDate)
        ->where('tasks.created_at', '>=', $startDate)
        ->where('tasks.created_at', '<', $endDate)
        ->where('task_users.user_id', $devId)
        ->get();

        $first_attempt_approve_task = 0;
        foreach ($number_of_tasks_approved as $task) {

            $min_approve_date = DB::table('task_approves')
                ->select('task_approves.created_at')
                ->where('task_approves.task_id', $task->id)
                ->orderBy('task_approves.created_at', 'asc')
                ->first();

            $number_of_tasks = DB::table('task_submissions')
            ->join('tasks', 'task_submissions.task_id', '=', 'tasks.id')
                ->where('task_submissions.task_id', $task->id)
                ->where('task_submissions.created_at','<', $min_approve_date->created_at)
                ->distinct('task_submissions.created_at')
                ->count();
            if ($number_of_tasks == 1) {
                $first_attempt_approve_task++;
            }
        }

        $number_of_tasks_approved = DB::table('tasks')
        ->join('task_users', 'tasks.id', '=', 'task_users.task_id')
        ->join('task_approves', 'tasks.id', '=', 'task_approves.task_id')
        ->join('task_types', 'tasks.id', '=', 'task_types.task_id')
        ->select('tasks.id')
        ->where('task_approves.created_at', '>=', $startDate)
        ->where('task_approves.created_at', '<', $endDate)
        ->where('tasks.created_at', '>=', $startDate)
        ->where('tasks.created_at', '<', $endDate)
        ->where('task_types.page_type', '=', 'Primary Page Development')
        ->where('task_users.user_id', $devId)
        ->get();

        $first_attempt_approve_task_primary_page = 0;
        foreach ($number_of_tasks_approved as $task) {
            $min_approve_date = DB::table('task_approves')
                ->select('task_approves.created_at')
                ->where('task_approves.task_id', $task->id)
                ->orderBy('task_approves.created_at', 'asc')
                ->first();

            $number_of_tasks = DB::table('task_submissions')
            ->join('tasks', 'task_submissions.task_id', '=', 'tasks.id')
            ->where('task_submissions.task_id', $task->id)
            ->where('task_submissions.created_at', '<', $min_approve_date->created_at)
            ->distinct('task_submissions.created_at')
            ->count();
            if ($number_of_tasks == 1) {
                $first_attempt_approve_task_primary_page++;
            }
        }

        $number_of_tasks_approved = DB::table('tasks')
        ->join('task_users', 'tasks.id', '=', 'task_users.task_id')
        ->join('task_approves', 'tasks.id', '=', 'task_approves.task_id')
        ->join('task_types', 'tasks.id', '=', 'task_types.task_id')
        ->select('tasks.id')
        ->where('task_approves.created_at', '>=', $startDate)
        ->where('task_approves.created_at', '<', $endDate)
        ->where('tasks.created_at', '>=', $startDate)
        ->where('tasks.created_at', '<', $endDate)
        ->where('task_types.page_type', '=', 'Secondary Page Development')
        ->where('task_users.user_id', $devId)
        ->get();

        $first_attempt_approve_task_secondary_page = 0;
        foreach ($number_of_tasks_approved as $task) {

            $min_approve_date = DB::table('task_approves')
                ->select('task_approves.created_at')
                ->where('task_approves.task_id', $task->id)
                ->orderBy('task_approves.created_at', 'asc')
                ->first();

            $number_of_tasks = DB::table('task_submissions')
            ->join('tasks', 'task_submissions.task_id', '=', 'tasks.id')
                ->where('task_submissions.task_id', $task->id)
                ->where('task_submissions.created_at', '<', $min_approve_date->created_at)
                ->distinct('task_submissions.created_at')
                ->count();
            if ($number_of_tasks == 1) {
                $first_attempt_approve_task_secondary_page++;
            }
        }

        $this->first_attempt_approve_task_for_this_month = $first_attempt_approve_task;
        $this->first_attempt_approve_task_primary_page_for_this_month = $first_attempt_approve_task_primary_page;
        $this->first_attempt_approve_task_secondary_page_for_this_month = $first_attempt_approve_task_secondary_page;





        // --------------Average number of attempts needed for approval(in cycle) lead developer-----------------------------//

        $number_of_tasks_approved = DB::table('tasks')
        ->join('task_users', 'tasks.id', '=', 'task_users.task_id')
        ->join('task_approves', 'tasks.id', '=', 'task_approves.task_id')
        ->select('tasks.id')
        ->where('task_approves.created_at', '>=', $startDate)
        ->where('task_approves.created_at', '<', $endDate)
        ->where('task_users.user_id', $devId)
        ->get();
        $first_attempt_approve_task = 0;
        $count_submission_per_approval = 0;
        $total_approval = 0;
        foreach ($number_of_tasks_approved as $task) {

            $min_approve_date= DB::table('task_approves')
             ->select('task_approves.created_at')
             ->where('task_approves.task_id',$task->id)
             ->orderBy('task_approves.created_at', 'asc')
             ->first();

            $number_of_tasks = DB::table('task_submissions')
            ->select('task_submissions.submission_no')
            ->where('task_submissions.task_id', $task->id)
            ->where('task_submissions.created_at','<', $min_approve_date->created_at)
            ->distinct('task_submissions.created_at')
            ->orderBy('task_submissions.id', 'DESC')
            ->first();
            $max_submission = $number_of_tasks->submission_no;
            $count_submission_per_approval = $count_submission_per_approval + $max_submission;
            $total_approval++;
        }
        if ($total_approval > 0) {
            $this->average_submission_aproval_in_this_month = $count_submission_per_approval / $total_approval;
        } else {
            $this->average_submission_aproval_in_this_month = 0;
        }

        /////////for Cycle/////////////////
        $number_of_tasks_approved = DB::table('tasks')
        ->join('task_users', 'tasks.id', '=', 'task_users.task_id')
        ->join('task_approves', 'tasks.id', '=', 'task_approves.task_id')
        ->select('tasks.id')
        ->where('task_approves.created_at', '>=', $startDate)
        ->where('task_approves.created_at', '<', $endDate)
        ->where('tasks.created_at', '>=', $startDate)
        ->where('tasks.created_at', '<', $endDate)
        ->where('task_users.user_id', $devId)
        ->get();

        $first_attempt_approve_task = 0;
        $count_submission_per_approval = 0;
        $total_approval = 0;
        foreach ($number_of_tasks_approved as $task) {

            $min_approve_date = DB::table('task_approves')
            ->select('task_approves.created_at')
            ->where('task_approves.task_id', $task->id)
            ->orderBy('task_approves.created_at', 'asc')
            ->first();


            $number_of_tasks = DB::table('task_submissions')
            ->select('task_submissions.submission_no')
            ->where('task_submissions.task_id', $task->id)
            ->where('task_submissions.created_at', '<', $min_approve_date->created_at)
            ->distinct('task_submissions.created_at')
            ->orderBy('task_submissions.id', 'DESC')
            ->first();
            $max_submission = $number_of_tasks->submission_no;
            $count_submission_per_approval = $count_submission_per_approval + $max_submission;
            $total_approval++;
        }
        if ($total_approval > 0) {
            $this->average_submission_aproval_for_this_month = $count_submission_per_approval / $total_approval;
        } else {
            $this->average_submission_aproval_for_this_month = 0;
        }


        // --------------Average number of attempts needed for approval(in cycle) Client-----------------------------//

        $number_of_tasks_approved = DB::table('tasks')
        ->join('task_users', 'tasks.id', '=', 'task_users.task_id')
        ->join('task_approves', 'tasks.id', '=', 'task_approves.task_id')
        ->select('tasks.id')
        ->where('tasks.board_column_id','=',4)
        ->where('tasks.updated_at', '>=', $startDate)
        ->where('tasks.updated_at', '<', $endDate)
        ->where('task_approves.created_at', '>=', $startDate)
        ->where('task_approves.created_at', '<', $endDate)
        ->where('task_users.user_id', $devId)
        ->get();
        $first_attempt_approve_task = 0;
        $count_submission_per_approval = 0;
        $total_approval = 0;
        foreach ($number_of_tasks_approved as $task) {



            $number_of_tasks = DB::table('task_submissions')
            ->select('task_submissions.submission_no')
            ->where('task_submissions.task_id', $task->id)
            ->distinct('task_submissions.created_at')
            ->orderBy('task_submissions.id', 'DESC')
            ->first();
            $max_submission = $number_of_tasks->submission_no;
            $count_submission_per_approval = $count_submission_per_approval + $max_submission;
            $total_approval++;
        }
        if ($total_approval > 0) {
            $this->average_submission_aproval_in_this_month_client = $count_submission_per_approval / $total_approval;
        } else {
            $this->average_submission_aproval_in_this_month_client = 0;
        }

        /////////for Cycle/////////////////
        $number_of_tasks_approved = DB::table('tasks')
        ->join('task_users', 'tasks.id', '=', 'task_users.task_id')
        ->join('task_approves', 'tasks.id', '=', 'task_approves.task_id')
        ->select('tasks.id')
        ->where('tasks.board_column_id', '=', 4)
        ->where('tasks.updated_at', '>=', $startDate)
        ->where('tasks.updated_at', '<', $endDate)
        ->where('task_approves.created_at', '>=', $startDate)
        ->where('task_approves.created_at', '<', $endDate)
        ->where('tasks.created_at', '>=', $startDate)
        ->where('tasks.created_at', '<', $endDate)
        ->where('task_users.user_id', $devId)
        ->get();

        $first_attempt_approve_task = 0;
        $count_submission_per_approval = 0;
        $total_approval = 0;
        foreach ($number_of_tasks_approved as $task) {


            $number_of_tasks = DB::table('task_submissions')
            ->select('task_submissions.submission_no')
            ->where('task_submissions.task_id', $task->id)
            ->distinct('task_submissions.created_at')
            ->orderBy('task_submissions.id', 'DESC')
            ->first();
            $max_submission = $number_of_tasks->submission_no;
            $count_submission_per_approval = $count_submission_per_approval + $max_submission;
            $total_approval++;
        }
        if ($total_approval > 0) {
            $this->average_submission_aproval_for_this_month_client = $count_submission_per_approval / $total_approval;
        } else {
            $this->average_submission_aproval_for_this_month_client = 0;
        }

        //---------------------------------Percentage of Revision----------------------------------------------------//

        $total_task_assigned = DB::table('tasks')
            ->join('task_users', 'tasks.id', '=', 'task_users.task_id')
            ->select('tasks.id')
            ->where('tasks.created_at', '>=', $startDate)
            ->where('tasks.created_at', '<', $endDate)
            ->where('task_users.user_id', $devId)
            ->get();

        $assign_task_count_for_revision=0;
        $number_of_total_revision_for_this_month=0;
        foreach($total_task_assigned as $task){

              $current_task_id=$task->id;
              $disput_responsible_for_revision= DB::table('task_revision_disputes')
                           ->where('task_id', $current_task_id)
                           ->where(function ($query) use ($current_task_id, $devId) {
                                       $query->where('raised_by', $devId)
                                             ->orWhere('raised_against', $devId);
                                             
                             })
                           ->where(function ($query) {
                                      $query->where('raised_by_percent','>',0)
                                            ->orWhere('raised_against_percent','>',0);
                             })
                 
                         ->count();

            if($disput_responsible_for_revision>0) {
                  $disput_responsible_for_revision=1;
                } // one task for count one revision
            $responsible_for_revision = DB::table('task_revisions')
                                ->where('task_id', $current_task_id)
                                ->where('final_responsible_person','D')
                                ->count();

                  if ($responsible_for_revision > 0) {
                $responsible_for_revision = 1;
            }  // one task for count one revision
            
                $total_revision_dispute_without_dispute= $disput_responsible_for_revision+$responsible_for_revision;

                $number_of_total_revision_for_this_month= $number_of_total_revision_for_this_month+ $total_revision_dispute_without_dispute;
                
                //count of assign tasks
                $assign_task_count_for_revision++;    
        }

         $percentage_of_tasks_with_revision=0;
         if($assign_task_count_for_revision>0){
           $percentage_of_tasks_with_revision= ($number_of_total_revision_for_this_month/ $assign_task_count_for_revision)*100;
         }
            $this->percentage_of_tasks_with_revision=$percentage_of_tasks_with_revision;
            $this->assign_task_count_for_revision= $assign_task_count_for_revision;
            $this->number_of_total_revision_for_this_month = $number_of_total_revision_for_this_month;


        //------------------------------average Submission Time(Log Time)----------------------------------------------//
         
         //for this month
        $submit_number_of_tasks_for_this_month_count = $this->submit_number_of_tasks_for_this_month;

        $submit_number_of_tasks_for_this_month_data = DB::table('task_submissions')
        ->select('tasks.id')
        ->join('tasks', 'task_submissions.task_id', '=', 'tasks.id')
        ->where('task_submissions.created_at', '>=', $startDate)
        ->where('task_submissions.created_at', '<', $endDate)
        ->where('tasks.created_at', '>=', $startDate)
        ->where('tasks.created_at', '<', $endDate)
        ->where('task_submissions.user_id', $devId)
        ->where('task_submissions.submission_no', '=', 1)
        ->distinct('task_submissions.created_at')
        ->distinct('tasks.id')
        ->get();

        $log_time_minute_total_for_this_month=0;
        foreach($submit_number_of_tasks_for_this_month_data as $task){
               $log_time_per_task= DB::table('project_time_logs')
                   ->where('task_id', $task->id)
                   ->where('revision_status', 0)
                   ->groupBy('task_id')
                   ->sum('total_minutes');
            $log_time_minute_total_for_this_month= $log_time_minute_total_for_this_month+ $log_time_per_task;          
        }

        $average_submission_time_for_this_month=0;

        if($submit_number_of_tasks_for_this_month_count>0){
            $average_submission_time_for_this_month= $log_time_minute_total_for_this_month/ $submit_number_of_tasks_for_this_month_count;
            $average_submission_time_for_this_month = $average_submission_time_for_this_month / 60;

    }

        $this->average_submission_time_for_this_month = $average_submission_time_for_this_month;

      //in this month

        $submit_number_of_tasks_in_this_month_count = $this->submit_number_of_tasks_in_this_month;

        $log_time_minute_total_in_this_month = 0;
        $submit_number_of_tasks_in_this_month_data = DB::table('task_submissions')
        ->select('tasks.id')
        ->join('tasks', 'task_submissions.task_id', '=', 'tasks.id')
        ->where('task_submissions.created_at', '>=', $startDate)
        ->where('task_submissions.created_at', '<', $endDate)
        ->where('task_submissions.user_id', $devId)
        ->where('task_submissions.submission_no', '=', 1)
        ->distinct('task_submissions.created_at')
        ->distinct('tasks.id')
        ->get();

        foreach ($submit_number_of_tasks_in_this_month_data as $task) {
              $log_time_per_task= DB::table('project_time_logs')
                                ->where('task_id', $task->id)
                                ->where('revision_status', 0)
                                ->groupBy('task_id')
                                ->sum('total_minutes');
        $log_time_minute_total_in_this_month = $log_time_minute_total_in_this_month + $log_time_per_task;

        }

        $average_submission_time_in_this_month = 0;

    if ($submit_number_of_tasks_in_this_month_count > 0){
        $average_submission_time_in_this_month = $log_time_minute_total_in_this_month / $submit_number_of_tasks_in_this_month_count;
            $average_submission_time_in_this_month= $average_submission_time_in_this_month/60;
  
    }

        $this->average_submission_time_in_this_month= $average_submission_time_in_this_month;


        //------------------------------average Submission Time(Day)----------------------------------------------//

        //for this month

        //table start 
        $submit_number_of_tasks_for_this_month_table = DB::table('task_submissions')
        ->select('tasks.id', 'tasks.heading', 'tasks.created_at', 'task_submissions.created_at as submission_date')
        ->join('tasks', 'task_submissions.task_id', '=', 'tasks.id')
        ->where('task_submissions.created_at', '>=', $startDate)
        ->where('task_submissions.created_at', '<', $endDate)
        ->where('tasks.created_at', '>=', $startDate)
        ->where('tasks.created_at', '<', $endDate)
        ->where('task_submissions.user_id', $devId)
        ->where('task_submissions.submission_no', '=', 1)
        ->distinct('task_submissions.created_at')
        ->get();

        $submit_number_of_tasks_in_this_month_table = DB::table('task_submissions')
        ->select('tasks.id','tasks.heading','tasks.created_at', 'task_submissions.created_at  as submission_date')
        ->join('tasks', 'task_submissions.task_id', '=', 'tasks.id')
        ->where('task_submissions.created_at', '>=', $startDate)
        ->where('task_submissions.created_at', '<', $endDate)
        ->where('task_submissions.user_id', $devId)
        ->where('task_submissions.submission_no', '=', 1)
        ->distinct('task_submissions.created_at')
        ->get();
    
         $this->submit_number_of_tasks_for_this_month_table= $submit_number_of_tasks_for_this_month_table;
         $this->submit_number_of_tasks_in_this_month_table = $submit_number_of_tasks_in_this_month_table;

        //table end

        $this->submit_number_of_tasks_for_this_month_table=$submit_number_of_tasks_for_this_month_table;

        $submit_number_of_tasks_for_this_month_data = DB::table('task_submissions')
        ->select('tasks.id', 'tasks.created_at', 'task_submissions.created_at')
        ->selectRaw('DATEDIFF(task_submissions.created_at, tasks.created_at) AS total_duration')
        ->join('tasks', 'task_submissions.task_id', '=', 'tasks.id')
        ->where('task_submissions.created_at', '>=', $startDate)
        ->where('task_submissions.created_at', '<', $endDate)
        ->where('tasks.created_at', '>=', $startDate)
        ->where('tasks.created_at', '<', $endDate)
        ->where('task_submissions.user_id', $devId)
        ->where('task_submissions.submission_no', '=', 1)
        ->distinct('task_submissions.created_at')
        ->get();
        
       
         $sum_of_total_duration=0;
         $count_of_submission_task=0;
        foreach($submit_number_of_tasks_for_this_month_data as $task){
            $sum_of_total_duration= $sum_of_total_duration+ $task->total_duration;
            $count_of_submission_task++;
               
        }

        $average_submission_day_for_this_month = 0;

        if ($count_of_submission_task> 0) {
            $average_submission_day_for_this_month = $sum_of_total_duration / $count_of_submission_task;
        }

        $this->average_submission_day_for_this_month = $average_submission_day_for_this_month;

        //in this month

        $submit_number_of_tasks_in_this_month_data = DB::table('task_submissions')
        ->select('tasks.id', 'tasks.created_at', 'task_submissions.created_at')
        ->selectRaw('DATEDIFF(task_submissions.created_at, tasks.created_at) AS total_duration')
        ->join('tasks', 'task_submissions.task_id', '=', 'tasks.id')
        ->where('task_submissions.created_at', '>=', $startDate)
        ->where('task_submissions.created_at', '<', $endDate)
        ->where('task_submissions.user_id', $devId)
        ->where('task_submissions.submission_no', '=', 1)
        ->distinct('task_submissions.created_at')
        ->get();

        $sum_of_total_duration = 0;
        $count_of_submission_task = 0;
        foreach ($submit_number_of_tasks_in_this_month_data as $task) {
            $sum_of_total_duration = $sum_of_total_duration + $task->total_duration;
            $count_of_submission_task++;
        }

        $average_submission_day_in_this_month = 0;

        if ($count_of_submission_task > 0) {
            $average_submission_day_in_this_month = $sum_of_total_duration / $count_of_submission_task;
        }

        $this->average_submission_day_in_this_month = $average_submission_day_in_this_month;


        //-----------Percentage of tasks where deadline was missed -----------------//

        $currentDate = Carbon::now()->format('Y-m-d');

        $number_of_tasks_received_for_deadline = DB::table('tasks')
            ->join('task_users', 'tasks.id', '=', 'task_users.task_id')
            ->where('tasks.created_at', '>=', $startDate)
            ->where('tasks.created_at', '<', $endDate)
            ->where('task_users.user_id', $devId)
            ->count();

        $number_of_tasks_received_for_deadline_data = DB::table('tasks')
            ->join('task_users', 'tasks.id', '=', 'task_users.task_id')
            ->select('tasks.*')
            ->where('tasks.created_at', '>=', $startDate)
            ->where('tasks.created_at', '<', $endDate)
            ->where('task_users.user_id', $devId)
            ->get();

        $number_of_tasks_cross_deadline=0;
        $percentage_of_tasks_deadline=0;
        foreach ($number_of_tasks_received_for_deadline_data as $task) {
            $dueDate = Carbon::parse($task->due_date)->format('Y-m-d');
        
            $updatedAt = Carbon::parse($task->updated_at)->format('Y-m-d');
      
            if ($dueDate < $updatedAt && $task->board_column_id == 4) {

                $number_of_tasks_cross_deadline++;
              
                  
            }else if($dueDate < $currentDate && $task->board_column_id != 4){

                $number_of_tasks_cross_deadline++;
                
            }
        }

        $this->number_of_tasks_cross_deadline= $number_of_tasks_cross_deadline;

       
           
        if($number_of_tasks_received_for_deadline>0){
        $percentage_of_tasks_deadline= ($number_of_tasks_cross_deadline/ $number_of_tasks_received_for_deadline)*100;}

        $this->percentage_of_tasks_deadline= $percentage_of_tasks_deadline;

        //Percentage of tasks where given estimated time was missed
        $number_of_tasks_received_for_missed_estimate_data = DB::table('tasks')
            ->join('task_users', 'tasks.id', '=', 'task_users.task_id')
            ->select('tasks.*')
            ->where('tasks.created_at', '>=', $startDate)
            ->where('tasks.created_at', '<', $endDate)
            ->where('task_users.user_id', $devId)
            ->get();
        $number_task_cross_estimate_time=0;
        $percentage_number_task_cross_estimate_time = 0;
        foreach($number_of_tasks_received_for_missed_estimate_data as $task){

            $log_time_per_task = DB::table('project_time_logs')
                ->where('task_id', $task->id)
                ->where('revision_status','=',0)
                ->groupBy('task_id')
                ->sum('total_minutes');

            $estimate_minutes_task= $task->estimate_hours*60+ $task->estimate_minutes;
            if($log_time_per_task> $estimate_minutes_task){
                $number_task_cross_estimate_time++;
            }

        }
        if($number_of_tasks_received_for_deadline>0){
        $percentage_number_task_cross_estimate_time = ($number_task_cross_estimate_time/ $number_of_tasks_received_for_deadline)*100;
        }
        
        $this->percentage_number_task_cross_estimate_time= $percentage_number_task_cross_estimate_time;
        $this->number_task_cross_estimate_time = $number_task_cross_estimate_time;
        $this->number_of_tasks_received_for_missed_estimate_data= $number_of_tasks_received_for_missed_estimate_data;

        
        //Number of disputes filed

          $this->number_of_dispute_filed_own= DB::table('task_revision_disputes')
                    ->where(function ($query) use ($devId) {
                           $query->where('raised_by', $devId);
                                
                  })
                  ->where('created_at', '>=', $startDate)
                  ->where('created_at', '<', $endDate)
                  ->count();

        $this->number_of_dispute_filed_all = DB::table('task_revision_disputes')
        ->where(function ($query) use ($devId) {
            $query->where('raised_by', $devId)
            ->orWhere('raised_against', $devId);
        })
            ->where('created_at', '>=', $startDate)
            ->where('created_at', '<', $endDate)
            ->count();

        // Number of disputes lost
        $this->number_of_dispute_lost_own = DB::table('task_revision_disputes')
            ->where(function ($query) use ( $devId) {
                $query->where('raised_by', $devId);
                    
            })
          ->where('winner', '!=', $devId)  
           ->where('created_at', '>=', $startDate)
           ->where('created_at', '<', $endDate)
           ->count();

        $this->number_of_dispute_lost_all = DB::table('task_revision_disputes')
        ->where(function ($query) use ($devId) {
            $query->where('raised_by', $devId)
            ->orWhere('raised_against', $devId);
        })
        ->where('winner', '!=', $devId)
        ->where('created_at', '>=', $startDate)
        ->where('created_at', '<', $endDate)
        ->count();

        //Hours spent in revisions 

        $spent_revision_developer = DB::table('project_time_logs')
                    ->where('user_id', $devId)
                    ->where('revision_status', 1)
                    ->where('created_at', '>=', $startDate)
                    ->where('created_at', '<', $endDate)
                    ->sum('total_minutes');

        $this->spent_revision_developer= $spent_revision_developer/60;


        // Average number of in-progress tasks

        $total_in_progress_date_range = DB::table('progress_tasks')
            ->where('user_id', $devId)
            ->where('created_at', '>=', $startDate)
            ->where('created_at', '<', $endDate)
            ->groupBy('user_id')
            ->sum('count_progress_task');

        $startDateString = $request->input('start_date');
        $endDateString = $request->input('end_date');

        $startDate4 = Carbon::parse($startDateString);
        $endDate4 = Carbon::parse($endDateString);

        $differenceInDays = (int) $endDate4->diffInDays($startDate4);

        $differenceInDays= $differenceInDays+1;
        $this->average_in_progress_date_range = $total_in_progress_date_range / $differenceInDays;

    }else{
            $this->username_lead = DB::table('users')->where('id', $devId)->value('name');
        
            $this->number_of_tasks_received_lead = DB::table('tasks')
                ->join('task_users', 'tasks.id', '=', 'task_users.task_id')
                ->where('tasks.created_at', '>=', $startDate)
                ->where('tasks.created_at', '<', $endDate)
                ->where('task_users.user_id', $devId)
                ->count();
            $this->submit_number_of_tasks_for_this_month_lead = DB::table('task_submissions')
            ->join('tasks', 'task_submissions.task_id', '=', 'tasks.id')
            ->where('task_submissions.created_at', '>=', $startDate)
            ->where('task_submissions.created_at', '<', $endDate)
            ->where('tasks.created_at', '>=', $startDate)
            ->where('tasks.created_at', '<', $endDate)
            ->where('task_submissions.user_id', $devId)
            ->where('task_submissions.submission_no', '=', 1)
            ->distinct('task_submissions.created_at')
            ->distinct('tasks.id')
            ->count();

            $this->submit_number_of_tasks_in_this_month_lead = DB::table('task_submissions')
            ->join('tasks', 'task_submissions.task_id', '=', 'tasks.id')
            ->where('task_submissions.created_at', '>=', $startDate)
            ->where('task_submissions.created_at', '<', $endDate)
            ->where('task_submissions.user_id', $devId)
            ->where('task_submissions.submission_no', '=', 1)
            ->distinct('task_submissions.created_at')
            ->distinct('tasks.id')
            ->count();


            //-----------------------------number of tasks approved in first attempt(in cycle) Client-----------------------//

            $number_of_tasks_approved = DB::table('tasks')
                ->join('task_users', 'tasks.id', '=', 'task_users.task_id')
                ->join('task_approves', 'tasks.id', '=', 'task_approves.task_id')
                ->select('tasks.id')
                ->where('tasks.board_column_id', '=', 4)
                ->where('tasks.updated_at', '>=', $startDate)
                ->where('tasks.updated_at', '<', $endDate)
                ->where('task_approves.created_at', '>=', $startDate)
                ->where('task_approves.created_at', '<', $endDate)
                ->where('task_users.user_id', $devId)
                ->get();
            $first_attempt_approve_task = 0;
            foreach ($number_of_tasks_approved as $task) {



                $number_of_tasks = DB::table('task_submissions')
                    ->join('tasks', 'task_submissions.task_id', '=', 'tasks.id')
                    ->where('task_submissions.task_id', $task->id)
                    ->distinct('task_submissions.created_at')
                    ->count();
                if ($number_of_tasks == 1) {
                    $first_attempt_approve_task++;
                }
            }
          
            $this->first_attempt_approve_task_in_this_month_client_lead = $first_attempt_approve_task;

            //-----------------------------number of tasks approved in first attempt(for cycle) Client-----------------------//

            $number_of_tasks_approved = DB::table('tasks')
                ->join('task_users', 'tasks.id', '=', 'task_users.task_id')
                ->join('task_approves', 'tasks.id', '=', 'task_approves.task_id')
                ->select('tasks.id')
                ->where('tasks.board_column_id', '=', 4)
                ->where('tasks.updated_at', '>=', $startDate)
                ->where('tasks.updated_at', '<', $endDate)
                ->where('task_approves.created_at', '>=', $startDate)
                ->where('task_approves.created_at', '<', $endDate)
                ->where('tasks.created_at', '>=', $startDate)
                ->where('tasks.created_at', '<', $endDate)
                ->where('task_users.user_id', $devId)
                ->get();

            $first_attempt_approve_task = 0;
            foreach ($number_of_tasks_approved as $task) {
                $number_of_tasks = DB::table('task_submissions')
                    ->join('tasks', 'task_submissions.task_id', '=', 'tasks.id')
                    ->where('task_submissions.task_id', $task->id)
                    ->distinct('task_submissions.created_at')
                    ->count();
                if ($number_of_tasks == 1) {
                    $first_attempt_approve_task++;
                }
            }

            $this->first_attempt_approve_task_for_this_month_client_lead = $first_attempt_approve_task;

            //-----------------------------number of tasks approved in first attempt(in cycle) PM-----------------------//

            $number_of_tasks_approved = DB::table('tasks')
                ->join('task_users', 'tasks.id', '=', 'task_users.task_id')
                ->join('task_approves', 'tasks.id', '=', 'task_approves.task_id')
                ->select('tasks.id')
                ->where('task_approves.created_at', '>=', $startDate)
                ->where('task_approves.created_at', '<', $endDate)
                ->where('task_users.user_id', $devId)
                ->get();
            $first_attempt_approve_task = 0;
            foreach ($number_of_tasks_approved as $task) {

                $min_approve_date = DB::table('task_approves')
                    ->select('task_approves.created_at')
                    ->where('task_approves.task_id', $task->id)
                    ->orderBy('task_approves.created_at', 'asc')
                    ->first();

                $number_of_tasks = DB::table('task_submissions')
                    ->join('tasks', 'task_submissions.task_id', '=', 'tasks.id')
                    ->where('task_submissions.task_id', $task->id)
                    ->where('task_submissions.created_at', '<', $min_approve_date->created_at)
                    ->distinct('task_submissions.created_at')
                    ->count();
                if ($number_of_tasks == 1) {
                    $first_attempt_approve_task++;
                }
            }

            $this->first_attempt_approve_task_in_this_month_lead = $first_attempt_approve_task;


            //-----------------------------number of tasks approved in first attempt(for cycle) PM-----------------------//

            $number_of_tasks_approved = DB::table('tasks')
                ->join('task_users', 'tasks.id', '=', 'task_users.task_id')
                ->join('task_approves', 'tasks.id', '=', 'task_approves.task_id')
                ->select('tasks.id')
                ->where('task_approves.created_at', '>=', $startDate)
                ->where('task_approves.created_at', '<', $endDate)
                ->where('tasks.created_at', '>=', $startDate)
                ->where('tasks.created_at', '<', $endDate)
                ->where('task_users.user_id', $devId)
                ->get();

            $first_attempt_approve_task = 0;
            foreach ($number_of_tasks_approved as $task) {

                $min_approve_date = DB::table('task_approves')
                    ->select('task_approves.created_at')
                    ->where('task_approves.task_id', $task->id)
                    ->orderBy('task_approves.created_at', 'asc')
                    ->first();

                $number_of_tasks = DB::table('task_submissions')
                    ->join('tasks', 'task_submissions.task_id', '=', 'tasks.id')
                    ->where('task_submissions.task_id', $task->id)
                    ->where('task_submissions.created_at', '<', $min_approve_date->created_at)
                    ->distinct('task_submissions.created_at')
                    ->count();
                if ($number_of_tasks == 1) {
                    $first_attempt_approve_task++;
                }
            }

            $this->first_attempt_approve_task_for_this_month_lead = $first_attempt_approve_task;
   
    }


        // --------------Average number of attempts needed for approval(in cycle) Project Manager-----------------------------//

        $number_of_tasks_approved = DB::table('tasks')
        ->join('task_users', 'tasks.id', '=', 'task_users.task_id')
        ->join('task_approves', 'tasks.id', '=', 'task_approves.task_id')
        ->select('tasks.id')
        ->where('task_approves.created_at', '>=', $startDate)
        ->where('task_approves.created_at', '<', $endDate)
        ->where('task_users.user_id', $devId)
        ->get();
        $first_attempt_approve_task = 0;
        $count_submission_per_approval = 0;
        $total_approval = 0;
        foreach ($number_of_tasks_approved as $task) {

            $min_approve_date = DB::table('task_approves')
            ->select('task_approves.created_at')
            ->where('task_approves.task_id', $task->id)
            ->orderBy('task_approves.created_at', 'asc')
            ->first();

            $number_of_tasks = DB::table('task_submissions')
            ->select('task_submissions.submission_no')
            ->where('task_submissions.task_id', $task->id)
                ->where('task_submissions.created_at', '<', $min_approve_date->created_at)
                ->distinct('task_submissions.created_at')
                ->orderBy('task_submissions.id', 'DESC')
                ->first();
            $max_submission = $number_of_tasks->submission_no;
            $count_submission_per_approval = $count_submission_per_approval + $max_submission;
            $total_approval++;
        }
        if ($total_approval > 0) {
            $this->average_submission_aproval_in_this_month_lead = $count_submission_per_approval / $total_approval;
        } else {
            $this->average_submission_aproval_in_this_month_lead = 0;
        }

        //////////////////////////////////////////////////////for Cycle////////////////////////////////////////////////
        
        $number_of_tasks_approved = DB::table('tasks')
        ->join('task_users', 'tasks.id', '=', 'task_users.task_id')
        ->join('task_approves', 'tasks.id', '=', 'task_approves.task_id')
        ->select('tasks.id')
        ->where('task_approves.created_at', '>=', $startDate)
        ->where('task_approves.created_at', '<', $endDate)
        ->where('tasks.created_at', '>=', $startDate)
        ->where('tasks.created_at', '<', $endDate)
        ->where('task_users.user_id', $devId)
        ->get();

        $first_attempt_approve_task = 0;
        $count_submission_per_approval = 0;
        $total_approval = 0;
        foreach ($number_of_tasks_approved as $task) {

            $min_approve_date = DB::table('task_approves')
            ->select('task_approves.created_at')
            ->where('task_approves.task_id', $task->id)
                ->orderBy('task_approves.created_at', 'asc')
                ->first();


            $number_of_tasks = DB::table('task_submissions')
            ->select('task_submissions.submission_no')
            ->where('task_submissions.task_id', $task->id)
                ->where('task_submissions.created_at', '<', $min_approve_date->created_at)
                ->distinct('task_submissions.created_at')
                ->orderBy('task_submissions.id', 'DESC')
                ->first();
            $max_submission = $number_of_tasks->submission_no;
            $count_submission_per_approval = $count_submission_per_approval + $max_submission;
            $total_approval++;
        }
        if ($total_approval > 0) {
            $this->average_submission_aproval_for_this_month_lead = $count_submission_per_approval / $total_approval;
        } else {
            $this->average_submission_aproval_for_this_month_lead = 0;
        }


        // --------------Average number of attempts needed for approval(in cycle) Client-----------------------------//

        $number_of_tasks_approved = DB::table('tasks')
        ->join('task_users', 'tasks.id', '=', 'task_users.task_id')
        ->join('task_approves', 'tasks.id', '=', 'task_approves.task_id')
        ->select('tasks.id')
        ->where('tasks.board_column_id', '=', 4)
        ->where('tasks.updated_at', '>=', $startDate)
        ->where('tasks.updated_at', '<', $endDate)
        ->where('task_approves.created_at', '>=', $startDate)
        ->where('task_approves.created_at', '<', $endDate)
        ->where('task_users.user_id', $devId)
        ->get();
        $first_attempt_approve_task = 0;
        $count_submission_per_approval = 0;
        $total_approval = 0;
        foreach ($number_of_tasks_approved as $task) {



            $number_of_tasks = DB::table('task_submissions')
            ->select('task_submissions.submission_no')
            ->where('task_submissions.task_id', $task->id)
                ->distinct('task_submissions.created_at')
                ->orderBy('task_submissions.id', 'DESC')
                ->first();
            $max_submission = $number_of_tasks->submission_no;
            $count_submission_per_approval = $count_submission_per_approval + $max_submission;
            $total_approval++;
        }
        if ($total_approval > 0) {
            $this->average_submission_aproval_in_this_month_client_lead = $count_submission_per_approval / $total_approval;
        } else {
            $this->average_submission_aproval_in_this_month_client_lead = 0;
        }

        /////////for Cycle/////////////////
        $number_of_tasks_approved = DB::table('tasks')
        ->join('task_users', 'tasks.id', '=', 'task_users.task_id')
        ->join('task_approves', 'tasks.id', '=', 'task_approves.task_id')
        ->select('tasks.id')
        ->where('tasks.board_column_id', '=', 4)
        ->where('tasks.updated_at', '>=', $startDate)
        ->where('tasks.updated_at', '<', $endDate)
        ->where('task_approves.created_at', '>=', $startDate)
        ->where('task_approves.created_at', '<', $endDate)
        ->where('tasks.created_at', '>=', $startDate)
        ->where('tasks.created_at', '<', $endDate)
        ->where('task_users.user_id', $devId)
        ->get();

        $first_attempt_approve_task = 0;
        $count_submission_per_approval = 0;
        $total_approval = 0;
        foreach ($number_of_tasks_approved as $task) {

            $number_of_tasks = DB::table('task_submissions')
            ->select('task_submissions.submission_no')
            ->where('task_submissions.task_id', $task->id)
                ->distinct('task_submissions.created_at')
                ->orderBy('task_submissions.id', 'DESC')
                ->first();
            $max_submission = $number_of_tasks->submission_no;
            $count_submission_per_approval = $count_submission_per_approval + $max_submission;
            $total_approval++;
        }
        if ($total_approval > 0) {
            $this->average_submission_aproval_for_this_month_client_lead = $count_submission_per_approval / $total_approval;
        } else {
            $this->average_submission_aproval_for_this_month_client_lead = 0;
        }

        //---------------------------------Percentage of Revision----------------------------------------------------//

        $total_task_assigned = DB::table('tasks')
            ->join('task_users', 'tasks.id', '=', 'task_users.task_id')
            ->select('tasks.id')
            ->where('tasks.created_at', '>=', $startDate)
            ->where('tasks.created_at', '<', $endDate)
            ->where('task_users.user_id', $devId)
            ->get();

        $assign_task_count_for_revision = 0;
        $number_of_total_revision_for_this_month = 0;
        foreach ($total_task_assigned as $task) {

            $current_task_id = $task->id;
            $disput_responsible_for_revision = DB::table('task_revision_disputes')
            ->where('task_id', $current_task_id)
                ->where(function ($query) use ($current_task_id, $devId) {
                    $query->where('raised_by', $devId)
                    ->orWhere('raised_against', $devId);
                })
                ->where(function ($query) {
                    $query->where('raised_by_percent', '>', 0)
                    ->orWhere('raised_against_percent', '>', 0);
                })

                ->count();

            if ($disput_responsible_for_revision > 0) {
                $disput_responsible_for_revision = 1;
            } // one task for count one revision
            $responsible_for_revision = DB::table('task_revisions')
            ->where('task_id', $current_task_id)
                ->where('final_responsible_person', 'LD')
                ->count();

            if ($responsible_for_revision > 0) {
                $responsible_for_revision = 1;
            }  // one task for count one revision

            $total_revision_dispute_without_dispute = $disput_responsible_for_revision + $responsible_for_revision;

            $number_of_total_revision_for_this_month = $number_of_total_revision_for_this_month + $total_revision_dispute_without_dispute;

            //count of assign tasks
            $assign_task_count_for_revision++;
        }

        $percentage_of_tasks_with_revision = 0;
        if ($assign_task_count_for_revision > 0) {
            $percentage_of_tasks_with_revision = ($number_of_total_revision_for_this_month / $assign_task_count_for_revision) * 100;
        }
        $this->percentage_of_tasks_with_revision_lead = $percentage_of_tasks_with_revision;
        $this->assign_task_count_for_revision_lead = $assign_task_count_for_revision;
        $this->number_of_total_revision_for_this_month_lead = $number_of_total_revision_for_this_month;

        //------------------------------average Submission Time(Day)----------------------------------------------//

        //for this month


        $submit_number_of_tasks_for_this_month_data = DB::table('task_submissions')
        ->select('tasks.id', 'tasks.created_at', 'task_submissions.created_at')
        ->selectRaw('DATEDIFF(task_submissions.created_at, tasks.created_at) AS total_duration')
        ->join('tasks', 'task_submissions.task_id', '=', 'tasks.id')
        ->where('task_submissions.created_at', '>=', $startDate)
        ->where('task_submissions.created_at', '<', $endDate)
        ->where('tasks.created_at', '>=', $startDate)
        ->where('tasks.created_at', '<', $endDate)
        ->where('task_submissions.user_id', $devId)
        ->where('task_submissions.submission_no', '=', 1)
        ->distinct('task_submissions.created_at')
        ->get();


        $sum_of_total_duration = 0;
        $count_of_submission_task = 0;
        foreach ($submit_number_of_tasks_for_this_month_data as $task) {
            $sum_of_total_duration = $sum_of_total_duration + $task->total_duration;
            $count_of_submission_task++;
        }

        $average_submission_day_for_this_month = 0;

        if ($count_of_submission_task > 0) {
            $average_submission_day_for_this_month = $sum_of_total_duration / $count_of_submission_task;
        }

        $this->average_submission_day_for_this_month_lead = $average_submission_day_for_this_month;

        //in this month

        $submit_number_of_tasks_in_this_month_data = DB::table('task_submissions')
        ->select('tasks.id', 'tasks.created_at', 'task_submissions.created_at')
        ->selectRaw('DATEDIFF(task_submissions.created_at, tasks.created_at) AS total_duration')
        ->join('tasks', 'task_submissions.task_id', '=', 'tasks.id')
        ->where('task_submissions.created_at', '>=', $startDate)
        ->where('task_submissions.created_at', '<', $endDate)
        ->where('task_submissions.user_id', $devId)
        ->where('task_submissions.submission_no', '=', 1)
        ->distinct('task_submissions.created_at')
        ->get();

        $sum_of_total_duration = 0;
        $count_of_submission_task = 0;
        foreach ($submit_number_of_tasks_in_this_month_data as $task) {
            $sum_of_total_duration = $sum_of_total_duration + $task->total_duration;
            $count_of_submission_task++;
        }

        $average_submission_day_in_this_month = 0;

        if ($count_of_submission_task > 0) {
            $average_submission_day_in_this_month = $sum_of_total_duration / $count_of_submission_task;
        }

        $this->average_submission_day_in_this_month_lead = $average_submission_day_in_this_month;

        //-----------Percentage of tasks where deadline was missed -----------------//

        $currentDate = Carbon::now()->format('Y-m-d');

        $number_of_tasks_received_for_deadline = DB::table('tasks')
            ->join('task_users', 'tasks.id', '=', 'task_users.task_id')
            ->where('tasks.created_at', '>=', $startDate)
            ->where('tasks.created_at', '<', $endDate)
            ->where('task_users.user_id', $devId)
            ->count();

        $number_of_tasks_received_for_deadline_data = DB::table('tasks')
            ->join('task_users', 'tasks.id', '=', 'task_users.task_id')
            ->select('tasks.*')
            ->where('tasks.created_at', '>=', $startDate)
            ->where('tasks.created_at', '<', $endDate)
            ->where('task_users.user_id', $devId)
            ->get();

        $number_of_tasks_cross_deadline = 0;
        $percentage_of_tasks_deadline = 0;
        foreach ($number_of_tasks_received_for_deadline_data as $task) {
            $dueDate = Carbon::parse($task->due_date)->format('Y-m-d');

            $updatedAt = Carbon::parse($task->updated_at)->format('Y-m-d');

            if ($dueDate < $updatedAt && $task->board_column_id == 4) {

                $number_of_tasks_cross_deadline++;
            } else if ($dueDate < $currentDate && $task->board_column_id != 4) {

                $number_of_tasks_cross_deadline++;
            }
        }

        $this->number_of_tasks_cross_deadline_lead = $number_of_tasks_cross_deadline;

        if ($number_of_tasks_received_for_deadline > 0) {
            $percentage_of_tasks_deadline = ($number_of_tasks_cross_deadline / $number_of_tasks_received_for_deadline) * 100;
        }

        $this->percentage_of_tasks_deadline_lead = $percentage_of_tasks_deadline;

        //Number of disputes filed

        $this->number_of_dispute_filed_own_lead = DB::table('task_revision_disputes')
        ->where(function ($query) use ($devId) {
            $query->where('raised_by', $devId);
        })
            ->where('created_at', '>=', $startDate)
            ->where('created_at', '<', $endDate)
            ->count();

        $this->number_of_dispute_filed_all_lead = DB::table('task_revision_disputes')
        ->where(function ($query) use ($devId) {
            $query->where('raised_by', $devId)
            ->orWhere('raised_against', $devId);
        })
            ->where('created_at', '>=', $startDate)
            ->where('created_at', '<', $endDate)
            ->count();

        // Number of disputes lost
        $this->number_of_dispute_lost_own_lead = DB::table('task_revision_disputes')
        ->where(function ($query) use ($devId) {
            $query->where('raised_by', $devId);
        })
        ->where('winner', '!=', $devId)
        ->where('created_at', '>=', $startDate)
        ->where('created_at', '<', $endDate)
        ->count();

        $this->number_of_dispute_lost_all_lead = DB::table('task_revision_disputes')
        ->where(function ($query) use ($devId) {
            $query->where('raised_by', $devId)
            ->orWhere('raised_against', $devId);
        })
            ->where('winner', '!=', $devId)
            ->where('created_at', '>=', $startDate)
            ->where('created_at', '<', $endDate)
            ->count();

        // Average number of in-progress tasks

        $total_in_progress_date_range = DB::table('progress_tasks')
        ->where('user_id', $devId)
        ->where('created_at', '>=', $startDate)
        ->where('created_at', '<', $endDate)
        ->groupBy('user_id')
        ->sum('count_progress_task');

        $startDateString = $request->input('start_date');
        $endDateString = $request->input('end_date');

        $startDate4 = Carbon::parse($startDateString);
        $endDate4 = Carbon::parse($endDateString);

        $differenceInDays = (int) $endDate4->diffInDays($startDate4);

        $differenceInDays = $differenceInDays + 1;
        $this->average_in_progress_date_range_lead = $total_in_progress_date_range / $differenceInDays;

        //Spent time in revision

        $total_task_assigned = DB::table('tasks')
            ->join('task_users', 'tasks.id', '=', 'task_users.task_id')
            ->select('tasks.id')
            ->where('tasks.created_at', '>=', $startDate)
            ->where('tasks.created_at', '<', $endDate)
            ->where('task_users.user_id', $devId)
            ->get();
        $total_spent_revision_developer=0;
        foreach ($total_task_assigned as $task) {
            $current_task_id = $task->id;
            $disput_responsible_for_revision = DB::table('task_revision_disputes')
            ->where('task_id', $current_task_id)
            ->where(function ($query) use ($current_task_id, $devId) {
                    $query->where('raised_by', $devId)
                    ->orWhere('raised_against', $devId);
                })
                ->where(function ($query) {
                    $query->where('raised_by_percent', '>', 0)
                    ->orWhere('raised_against_percent', '>', 0);
                })

                ->count();

            if ($disput_responsible_for_revision > 0) {
                 $get_sub_task = DB::table('sub_tasks')
                 ->select('id')
                 ->where('task_id', $current_task_id)
                 ->get();

                if ($get_sub_task->count() == 0) {

                    $spent_revision_developer = DB::table('project_time_logs')
                        ->where('task_id', $task->id)
                        ->where('revision_status', 1)
                        ->where('created_at', '>=', $startDate)
                        ->where('created_at', '<', $endDate)
                        ->groupBy('task_id')
                        ->sum('total_minutes');

                    $total_spent_revision_developer += $spent_revision_developer;
                    
                } else {

                    foreach ($get_sub_task as $subtask) {
                        $get_task = DB::table('tasks')
                            ->select('id')
                            ->where('subtask_id', $subtask->id)
                            ->first();
                        $spent_revision_developer = DB::table('project_time_logs')
                            ->where('task_id', $get_task->id)
                            ->where('revision_status', 1)
                            ->where('created_at', '>=', $startDate)
                            ->where('created_at', '<', $endDate)
                            ->groupBy('task_id')
                            ->sum('total_minutes');

                        $total_spent_revision_developer += $spent_revision_developer;
                    }

                    
                }
               
            } 

            $responsible_for_revision = DB::table('task_revisions')
            ->where('task_id', $current_task_id)
            ->where('final_responsible_person', 'LD')
            ->count();

            if ($responsible_for_revision > 0) {
                $get_sub_task = DB::table('sub_tasks')
                    ->select('id')
                    ->where('task_id', $current_task_id)
                    ->get();

                if ($get_sub_task->count() == 0) {

                    $spent_revision_developer = DB::table('project_time_logs')
                        ->where('task_id', $task->id)
                        ->where('revision_status', 1)
                        ->where('created_at', '>=', $startDate)
                        ->where('created_at', '<', $endDate)
                        ->groupBy('task_id')
                        ->sum('total_minutes');

                    $total_spent_revision_developer += $spent_revision_developer;
                } else {

                    foreach ($get_sub_task as $subtask) {
                        $get_task = DB::table('tasks')
                            ->select('id')
                            ->where('subtask_id', $subtask->id)
                            ->first();
                        $spent_revision_developer = DB::table('project_time_logs')
                            ->where('task_id', $get_task->id)
                            ->where('revision_status', 1)
                            ->where('created_at', '>=', $startDate)
                            ->where('created_at', '<', $endDate)
                            ->groupBy('task_id')
                            ->sum('total_minutes');

                        $total_spent_revision_developer += $spent_revision_developer;
                    }
                }
              
            } 
          
        }

        $this->spent_revision_developer_lead = $total_spent_revision_developer / 60;

        //Percentage of tasks where given estimated time was missed//
        
        $number_of_tasks_received_for_deadline = DB::table('tasks')
            ->join('task_users', 'tasks.id', '=', 'task_users.task_id')
            ->where('tasks.created_at', '>=', $startDate)
            ->where('tasks.created_at', '<', $endDate)
            ->where('task_users.user_id', $devId)
            ->count();

        $number_of_tasks_received_for_missed_estimate_data = DB::table('tasks')
            ->join('task_users', 'tasks.id', '=', 'task_users.task_id')
            ->select('tasks.*')
            ->where('tasks.created_at', '>=', $startDate)
            ->where('tasks.created_at', '<', $endDate)
            ->where('task_users.user_id', $devId)
            ->get();
        $number_task_cross_estimate_time = 0;
        $percentage_number_task_cross_estimate_time = 0;
        $total_log_time_per_task=0;
        foreach ($number_of_tasks_received_for_missed_estimate_data as $task) {

            $get_sub_task = DB::table('sub_tasks')
                ->select('id')
                ->where('task_id', $task->id)
                ->get();

            foreach ($get_sub_task as $subtask) {
                $get_task = DB::table('tasks')
                    ->select('id')
                    ->where('subtask_id', $subtask->id)
                    ->first();
                $log_time_per_task = DB::table('project_time_logs')
                ->where('task_id', $get_task->id)
                ->where('revision_status', '=', 0)
                ->groupBy('task_id')
                ->sum('total_minutes');
                $total_log_time_per_task+= $log_time_per_task;
            }

            $estimate_minutes_task = $task->estimate_hours * 60 + $task->estimate_minutes;

            if ($log_time_per_task > $estimate_minutes_task) {
                $number_task_cross_estimate_time++;
            }

            $total_log_time_per_task = 0;
        }

        if ($number_of_tasks_received_for_deadline > 0) {
            $percentage_number_task_cross_estimate_time = ($number_task_cross_estimate_time / $number_of_tasks_received_for_deadline) * 100;
        }

        $this->percentage_number_task_cross_estimate_time_lead = $percentage_number_task_cross_estimate_time;
        $this->number_task_cross_estimate_time_lead = $number_task_cross_estimate_time;
      
        return view('holiday.index', $this->data);
        
    }


    protected function googleCalendarEvent($event)
    {
        $module = GoogleCalendarModule::first();
        $googleAccount = global_setting();

        if ($googleAccount->google_calendar_status == 'active' && $googleAccount->google_calendar_verification_status == 'verified' && $googleAccount->token && $module->holiday_status == 1) {

            $google = new Google();

            $description = __('messages.invoiceDueOn');

            // Create event
            $google = $google->connectUsing($googleAccount->token);

            $eventData = new \Google_Service_Calendar_Event(array(
                'summary' => $event->occassion,
                'location' => $googleAccount->address,
                'description' => $description,
                'colorId' => 1,
                'start' => array(
                    'dateTime' => $event->date,
                    'timeZone' => $googleAccount->timezone,
                ),
                'end' => array(
                    'dateTime' => $event->date,
                    'timeZone' => $googleAccount->timezone,
                ),
                'reminders' => array(
                    'useDefault' => false,
                    'overrides' => array(
                        array('method' => 'email', 'minutes' => 24 * 60),
                        array('method' => 'popup', 'minutes' => 10),
                    ),
                ),
            ));

            try {
                if ($event->event_id) {
                    $results = $google->service('Calendar')->events->patch('primary', $event->event_id, $eventData);
                }
                else {
                    $results = $google->service('Calendar')->events->insert('primary', $eventData);
                }

                return $results->id;
            } catch (\Google\Service\Exception $error) {
                if(is_null($error->getErrors())) {
                    // Delete google calendar connection data i.e. token, name, google_id
                    $googleAccount->name = null;
                    $googleAccount->token = null;
                    $googleAccount->google_id = null;
                    $googleAccount->google_calendar_verification_status = 'non_verified';
                    $googleAccount->save();
                }
            }

        }

        return $event->event_id;
    }

    protected function googleCalendarEventMulti($day, $year, $days)
    {
        $googleAccount = global_setting();
        $module = GoogleCalendarModule::first();

        if ($googleAccount->google_calendar_status == 'active' && $googleAccount->google_calendar_verification_status == 'verified' && $googleAccount->token && $module->holiday_status == 1 )
        {
            $this->days = $days;
            $google = new Google();

            $allDays = $this->getDateForSpecificDayBetweenDates($year . '-01-01', $year . '-12-31', $day);

            $holiday = Holiday::where(DB::raw('DATE(`date`)'), $allDays[0])->first();

            $startDate = Carbon::parse($allDays[0]);

            $frequency = 'WEEKLY';

            $eventData = new \Google_Service_Calendar_Event();
            $eventData->setSummary($this->days[$day]);
            $eventData->setColorId(7);
            $eventData->setLocation('');

            $start = new \Google_Service_Calendar_EventDateTime();
            $start->setDateTime($startDate);
            $start->setTimeZone($googleAccount->timezone);

            $eventData->setStart($start);

            $end = new \Google_Service_Calendar_EventDateTime();
            $end->setDateTime($startDate);
            $end->setTimeZone($googleAccount->timezone);

            $eventData->setEnd($end);

            $dy = mb_strtoupper(substr($this->days[$day], 0, 2));

            $eventData->setRecurrence(array('RRULE:FREQ='.$frequency.';COUNT='.count($allDays).';BYDAY='.$dy));

            // Create event
            $google->connectUsing($googleAccount->token);
            // array for multiple

            try {
                if ($holiday->event_id) {
                    $results = $google->service('Calendar')->events->patch('primary', $holiday->event_id, $eventData);
                }
                else {
                    $results = $google->service('Calendar')->events->insert('primary', $eventData);
                }

                $holidays = Holiday::where('occassion', $this->days[$day])->get();

                foreach($holidays as $holiday){
                    $holiday->event_id = $results->id;
                    $holiday->save();
                }

                return;
            } catch (\Google\Service\Exception $error) {
                if(is_null($error->getErrors())) {
                    // Delete google calendar connection data i.e. token, name, google_id
                    $googleAccount->name = null;
                    $googleAccount->token = null;
                    $googleAccount->google_id = null;
                    $googleAccount->google_calendar_verification_status = 'non_verified';
                    $googleAccount->save();
                }
            }


            $holidays = Holiday::where('occassion', $this->days[$day])->get();

            foreach($holidays as $holiday){
                $holiday->event_id = $holiday->event_id;
                $holiday->save();
            }

            return;
        }
    }

}
