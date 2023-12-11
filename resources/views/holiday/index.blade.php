@extends('layouts.app')

@push('datatable-styles')
    {{-- @include('sections.datatable_css')
    <style>
        .filter-box {
            z-index: 2;
        }

    </style> --}}
@endpush

@section('filter-section')
    {{-- <x-filters.filter-box>
        <!-- DATE START -->
        <div class="select-box d-flex py-2 px-lg-2 px-md-2 px-0 border-right-grey border-right-grey-sm-0">
            <p class="mb-0 pr-3 f-14 text-dark-grey d-flex align-items-center">@lang('app.month')</p>
            <div class="select-month">
                <select class="form-control select-picker" name="month" id="month" data-live-search="true" data-size="8">
                    @foreach ($months as $month)
                        <option @if ($currentMonth == $loop->iteration) selected @endif value="{{ $loop->iteration }}">{{ ucfirst($month) }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <!-- MONTH END -->

        <!-- YEAR START -->
        <div class="select-box d-flex py-2 px-lg-2 px-md-2 px-0 border-right-grey border-right-grey-sm-0">
            <p class="mb-0 pr-3 f-14 text-dark-grey d-flex align-items-center">@lang('app.year')</p>
            <div class="select-year">
                <select class="form-control select-picker" name="year" id="year" data-live-search="true" data-size="8">
                    @foreach ($years as $year)
                        <option @if ($year == $currentYear) selected @endif
                            value="{{ $year }}">{{ $year }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <!-- YEAR END -->

        <!-- SEARCH BY TASK START -->
        <div class="task-search d-flex  py-1 px-lg-3 px-0 border-right-grey align-items-center">
            <form class="w-100 mr-1 mr-lg-0 mr-md-1 ml-md-1 ml-0 ml-lg-0">
                <div class="input-group bg-grey rounded">
                    <div class="input-group-prepend">
                        <span class="input-group-text border-0 bg-additional-grey">
                            <i class="fa fa-search f-13 text-dark-grey"></i>
                        </span>
                    </div>
                    <input type="text" class="form-control f-14 p-1 border-additional-grey" id="search-text-field"
                        placeholder="@lang('app.startTyping')">
                </div>
            </form>
        </div>
        <!-- SEARCH BY TASK END -->

        <!-- RESET START -->
        <div class="select-box d-flex py-1 px-lg-2 px-md-2 px-0">
            <x-forms.button-secondary class="btn-xs d-none" id="reset-filters" icon="times-circle">
                @lang('app.clearFilters')
            </x-forms.button-secondary>
        </div>
        <!-- RESET END -->
    </x-filters.filter-box>
@endsection

@php
$addPermission = user()->permission('add_holiday');
@endphp

@section('content')
    <!-- CONTENT WRAPPER START -->
    <div class="content-wrapper">
        <!-- Add Task Export Buttons Start -->
        <div class="d-block d-lg-flex d-md-flex action-bar justify-content-between ">
            <div id="table-actions" class="flex-grow-1 align-items-center">
                @if ($addPermission == 'all' || $addPermission == 'added')
                    <x-forms.link-primary :link="route('holidays.create')" class="mr-3 openRightModal float-left mb-2 mb-lg-0 mb-md-0"
                        icon="plus">
                        @lang('modules.holiday.addNewHoliday')
                    </x-forms.link-primary>
                    <x-forms.button-secondary icon="check" class="mr-3 float-left mb-2 mb-lg-0 mb-md-0" id="mark-holiday">
                        @lang('modules.holiday.markSunday')
                    </x-forms.button-secondary>
                @endif
            </div>


            <x-datatable.actions>
                <div class="select-status mr-3 pl-3">
                    <select name="action_type" class="form-control select-picker" id="quick-action-type" disabled>
                        <option value="">@lang('app.selectAction')</option>
                        <option value="delete">@lang('app.delete')</option>
                    </select>
                </div>
            </x-datatable.actions>

            <div class="btn-group ml-3" role="group" aria-label="Basic example">
                <a href="{{ route('holidays.index') }}" class="btn btn-secondary f-14 btn-active" data-toggle="tooltip"
                    data-original-title="@lang('modules.leaves.tableView')"><i class="side-icon bi bi-list-ul"></i></a>

                <a href="{{ route('holidays.calendar') }}" class="btn btn-secondary f-14" data-toggle="tooltip"
                    data-original-title="@lang('app.menu.calendar')"><i class="side-icon bi bi-calendar"></i></a>
            </div>
        </div>

        <!-- holiday table Box Start -->
        <div class="d-flex flex-column w-tables rounded mt-3 bg-white">

            {!! $dataTable->table(['class' => 'table table-hover border-0 w-100']) !!}

        </div>
        <!-- leave table End -->

    </div>
    <!-- CONTENT WRAPPER END --> --}}

<div>
    @php
    $users= App\Models\User::where('role_id',5)->get();
    @endphp
    <div class="form-group">
        <form action="{{ route('holydays.developer_performence') }}"
              method="POST">
            {{ csrf_field() }}
            <label for="projectmanager">NAME:</label>
            <select name="developerID"
                    id="dropdown1"
                    required>
                @foreach($users as $user)
                <option value="{{$user->id}}">{{$user->name}}</option>
                @endforeach
            </select>
            <label for="start_date">Start Date:</label>
            <input type="date"
                   name="start_date"
                   id="start_date"
                   required>

            <label for="end_date">End Date:</label>
            <input type="date"
                   name="end_date"
                   id="end_date"
                   required>

            <button type="submit"
                    class="btn btn-primary">Submit</button>
        </form>
    </div>

@if(isset($username))
<h4>Developer Name:{{$username}}</h4>
<h4 style="color: rgba(16, 47, 200, 0.777)">Date:{{ $startDate1->format('Y-m-d') }} to {{ $endDate1->format('Y-m-d') }} </h4>
<h4>Recieved Task : {{$number_of_tasks_received}}</h4>
<h4>Primary Pages: {{$number_of_tasks_received_primary_page}}</h4>
<h4>Secondary Pages : {{$number_of_tasks_received_secondary_page}}</h4>
<h4>Others : {{$number_of_tasks_received-$number_of_tasks_received_primary_page-$number_of_tasks_received_secondary_page}}</h4><br><br>


<h4>Submit Task (For This Month) : {{$submit_number_of_tasks_for_this_month}}</h4>
<h4>Submit Task for Primary Page (For This Month): {{$submit_number_of_tasks_primary_page_for_this_month}}</h4>
<h4>Submit Task for Secondary Page(For This Month) : {{$submit_number_of_tasks_secondary_page_for_this_month}}</h4>
<h4>Submit Task for Others(For This Month) : {{$submit_number_of_tasks_for_this_month-$submit_number_of_tasks_primary_page_for_this_month-$submit_number_of_tasks_secondary_page_for_this_month}}</h4><br><br>

<h4>Submit Task (In This Month) : {{$submit_number_of_tasks_in_this_month}}</h4>
<h4>Submit Task for Primary Page (In This Month): {{$submit_number_of_tasks_primary_page_in_this_month}}</h4>
<h4>Submit Task for Secondary Page(In This Month) : {{$submit_number_of_tasks_secondary_page_in_this_month}}</h4>
<h4>Submit Task for Others(In This Month) : {{$submit_number_of_tasks_in_this_month-$submit_number_of_tasks_primary_page_in_this_month-$submit_number_of_tasks_secondary_page_in_this_month}}</h4><br><br>

<h4>Approved Task By Lead Developer in first Atempt(For This Month) : {{$first_attempt_approve_task_for_this_month}}</h4>
<h4>Approved Task By Lead Developer in first Atempt for Primary Page(For This Month): {{$first_attempt_approve_task_primary_page_for_this_month}}</h4>
<h4>Approved Task By Lead Developer in first Atempt for Secondary Page(For This Month) : {{$first_attempt_approve_task_secondary_page_for_this_month}}</h4>
<h4>Approved Task By Lead Developer in first Atempt for Others(For This Month) : {{$first_attempt_approve_task_for_this_month-$first_attempt_approve_task_primary_page_for_this_month-$first_attempt_approve_task_secondary_page_for_this_month}}</h4><br><br>


<h4>Approved Task By Lead Developer in first Atempt(In This Month) : {{$first_attempt_approve_task_in_this_month}}</h4>
<h4>Approved Task By Lead Developer in first Atempt for Primary Page(In This Month): {{$first_attempt_approve_task_primary_page_in_this_month}}</h4>
<h4>Approved Task By Lead Developer in first Atempt for Secondary Page(In This Month) : {{$first_attempt_approve_task_secondary_page_in_this_month}}</h4>
<h4>Approved Task By Lead Developer in first Atempt for Others(In This Month) : {{$first_attempt_approve_task_in_this_month-$first_attempt_approve_task_primary_page_in_this_month-$first_attempt_approve_task_secondary_page_in_this_month}}</h4><br><br>


<h4>Approved Task By Client in first Atempt(For This Month) : {{$first_attempt_approve_task_for_this_month_client}}</h4>
<h4>Approved Task By Client in first Atempt for Primary Page(For This Month): {{$first_attempt_approve_task_primary_page_for_this_month_client}}</h4>
<h4>Approved Task By Client in first Atempt for Secondary Page(For This Month) : {{$first_attempt_approve_task_secondary_page_for_this_month_client}}</h4>
<h4>Approved Task By Client in first Atempt for Others(For This Month) : {{$first_attempt_approve_task_for_this_month_client-$first_attempt_approve_task_primary_page_for_this_month_client-$first_attempt_approve_task_secondary_page_for_this_month_client}}</h4><br><br>


<h4>Approved Task By Client in first Atempt(In This Month) : {{$first_attempt_approve_task_in_this_month_client}}</h4>
<h4>Approved Task By Client in first Atempt for Primary Page(In This Month): {{$first_attempt_approve_task_primary_page_in_this_month_client}}</h4>
<h4>Approved Task By Client in first Atempt for Secondary Page(In This Month) : {{$first_attempt_approve_task_secondary_page_in_this_month_client}}</h4>
<h4>Approved Task By Client in first Atempt for Others(In This Month) : {{$first_attempt_approve_task_in_this_month_client-$first_attempt_approve_task_primary_page_in_this_month_client-$first_attempt_approve_task_secondary_page_in_this_month_client}}</h4><br><br>


<h4> Average number of attempts needed for approval By Lead Developer(For This Month) : {{round($average_submission_aproval_for_this_month,2)}}</h4>
<h4> Average number of attempts needed for approval By Lead Developer(In This Month) : {{round($average_submission_aproval_in_this_month,2)}}</h4><br>

<h4> Average number of attempts needed for approval By Client(For This Month) : {{round($average_submission_aproval_for_this_month_client,2)}}</h4>
<h4> Average number of attempts needed for approval By Client(In This Month) : {{round($average_submission_aproval_in_this_month_client,2)}}</h4><br><br>

<h4> Percentage of tasks with revisions: {{round($percentage_of_tasks_with_revision,2)}}%</h4><br>
<h4> Total Number of Revisions : {{round($number_of_total_revision_for_this_month,2)}}</h4><br><br>

<h4> Average task submission time(For This Month): {{round($average_submission_time_for_this_month,2)}} Hours</h4>
<h4> Average task submission time(In This Month): {{round($average_submission_time_in_this_month,2)}} Hours</h4><br><br>

<h4> Average task submission time(For This Month): {{round($average_submission_day_for_this_month,2)}} Days</h4>
<h4> Average task submission time(In This Month): {{round($average_submission_day_in_this_month,2)}} Days</h4><br><br>

<h4> Percentage of tasks where deadline was missed : {{round($percentage_of_tasks_deadline,2)}}%</h4><br>
<h4> Percentage of tasks where given estimated time was missed: {{round($percentage_number_task_cross_estimate_time,2)}}%</h4><br>

<h4>Number of disputes filed: {{$number_of_dispute_filed_own}}</h4><br>
<h4>Number of disputes(Overall): {{$number_of_dispute_filed_all}}</h4><br>
<h4>Number of disputes lost(Raised By Developer): {{ $number_of_dispute_lost_own}}</h4><br>
<h4>Number of disputes lost(Overall): {{ $number_of_dispute_lost_all}}</h4><br>
<h4> Hours spent in revisions : {{round($spent_revision_developer,2)}} Hours</h4><br>

<h4> Average number of in-progress tasks: {{round($average_in_progress_date_range ,2)}}</h4><br>

@endif

<div>

    <h3>Developer Task Details </h3>
    @if(isset($username))
    <h4>Developer Name:{{$username}}</h4>
    @endif
    <table class="table table-striped">
        <thead>
            <tr>
                <th>SL NO</th>
                <th>Task ID</th>
                <th>Task Title</th>
                <th>Task Type</th>
                <th>Page Type</th>
                <th>Page Name</th>
                <th>Task Type Other</th>
                <th>Task Start Date</th>
               

            </tr>

        </thead>
        <tbody> @if(isset($developer_task_data))
            @foreach ($developer_task_data as $key => $row)
            <tr>
                <td>{{$key+1}}</td>
                <td>{{ $row->id }}</td>
                <td>{{ $row->heading }}</td>
                <td>{{ $row->task_type }}</td>
                <td>{{ $row->page_type }}</td>
                <td>{{ $row->page_name }}</td>
                <td>{{ $row->task_type_other }}</td>
                <td>{{ $row->created_at }}</td>
            </tr>
            @endforeach
            @endif
        </tbody>
    </table><br><br>
    
   <h3>Developer Task Submission Details (For This Month)</h3>
   
     <table class="table table-striped">
        <thead>
            <tr>
                <th>SL NO</th>
                <th>Task ID</th>
                <th>Task Title</th>
                <th>Task assign Date</th>
                <th>Task Submission Date</th>
               

            </tr>

        </thead>
        <tbody> @if(isset($submit_number_of_tasks_for_this_month_table))
            @foreach ($submit_number_of_tasks_for_this_month_table as $key => $row)
            <tr>
                <td>{{$key+1}}</td>
                <td>{{ $row->id }}</td>
                <td>{{ $row->heading }}</td>
                <td>{{ $row->created_at }}</td>
                <td>{{ $row->submission_date }}</td>
            </tr>
            @endforeach
            @endif
        </tbody>
    </table><br><br>
 
<h3>Developer Task Submission Details (In This Month)</h3>
    
     <table class="table table-striped">
        <thead>
            <tr>
                <th>SL NO</th>
                <th>Task ID</th>
                <th>Task Title</th>
                <th>Task assign Date</th>
                <th>Task Submission Date</th>
            </tr>
        </thead>
        <tbody> @if(isset($submit_number_of_tasks_in_this_month_table))
            @foreach ($submit_number_of_tasks_in_this_month_table as $key => $row)
            <tr>
                <td>{{$key+1}}</td>
                <td>{{ $row->id }}</td>
                <td>{{ $row->heading }}</td>
                <td>{{ $row->created_at }}</td>
                <td>{{ $row->submission_date }}</td>
            </tr>
            @endforeach
            @endif
        </tbody>
    </table>
    
</div>

<div>
    @php
    $users= App\Models\User::where('role_id',6)->get();
    @endphp
    <div class="form-group">
        <form action="{{ route('holydays.developer_performence') }}"
              method="POST">
            {{ csrf_field() }}
            <label for="projectmanager">NAME:</label>
            <select name="developerID"
                    id="dropdown1"
                    required>
                @foreach($users as $user)
                <option value="{{$user->id}}">{{$user->name}}</option>
                @endforeach
            </select>
            <label for="start_date">Start Date:</label>
            <input type="date"
                   name="start_date"
                   id="start_date"
                   required>

            <label for="end_date">End Date:</label>
            <input type="date"
                   name="end_date"
                   id="end_date"
                   required>

            <button type="submit"
                    class="btn btn-primary">Submit</button>
        </form>
    </div>

 @if(isset($username_lead))

<h4>Lead Developer Name:{{$username_lead}}</h4>
<h4 style="color: rgba(16, 47, 200, 0.777)">Date:{{ $startDate1->format('Y-m-d') }} to {{ $endDate1->format('Y-m-d') }} </h4>
<h4>Recieve Task : {{$number_of_tasks_received_lead}}</h4>
<h4>Submit Task (For This Month) : {{$submit_number_of_tasks_for_this_month_lead}}</h4>
<h4>Submit Task (In This Month) : {{$submit_number_of_tasks_in_this_month_lead}}</h4><br><br>

<h4>Approved Task By Project Manager in first Atempt(For This Month) : {{$first_attempt_approve_task_for_this_month_lead}}</h4>
<h4>Approved Task By Project Manager in first Atempt(In This Month) : {{$first_attempt_approve_task_in_this_month_lead}}</h4>
<h4>Approved Task By Client in first Atempt(For This Month) : {{$first_attempt_approve_task_for_this_month_client_lead}}</h4>
<h4>Approved Task By Client in first Atempt(In This Month) : {{$first_attempt_approve_task_in_this_month_client_lead}}</h4><br><br>

<h4> Average number of attempts needed for approval By Project Manager(For This Month) : {{round($average_submission_aproval_for_this_month_lead,2)}}</h4>
<h4> Average number of attempts needed for approval By Project Manager(In This Month) : {{round($average_submission_aproval_in_this_month_lead,2)}}</h4><br>

<h4> Average number of attempts needed for approval By Client(For This Month) : {{round($average_submission_aproval_for_this_month_client_lead,2)}}</h4>
<h4> Average number of attempts needed for approval By Client(In This Month) : {{round($average_submission_aproval_in_this_month_client_lead,2)}}</h4><br><br>

<h4> Percentage of tasks with revisions: {{round($percentage_of_tasks_with_revision_lead,2)}}%</h4><br>
<h4> Total Number of Revisions : {{round($number_of_total_revision_for_this_month_lead,2)}}</h4><br><br>

<h4> Average task submission time(For This Month): {{round($average_submission_day_for_this_month_lead,2)}} Days</h4>
<h4> Average task submission time(In This Month): {{round($average_submission_day_in_this_month_lead,2)}} Days</h4><br><br>

<h4> Percentage of tasks where deadline was missed : {{round($percentage_of_tasks_deadline_lead,2)}}%</h4><br>
<h4> Percentage of tasks where given estimated time was missed: {{round($percentage_number_task_cross_estimate_time_lead,2)}}%</h4><br>

<h4>Number of disputes filed: {{$number_of_dispute_filed_own_lead}}</h4><br>
<h4>Number of disputes(Overall): {{$number_of_dispute_filed_all_lead}}</h4><br>
<h4>Number of disputes lost(Raised By Lead Developer): {{ $number_of_dispute_lost_own_lead}}</h4><br>
<h4>Number of disputes lost(Overall): {{ $number_of_dispute_lost_all_lead}}</h4><br>
<h4> Hours spent in revisions : {{round($spent_revision_developer_lead,2)}} Hours</h4><br>
<h4> Average number of in-progress tasks: {{round($average_in_progress_date_range_lead ,2)}}</h4><br>

 @endif
 
@endsection

@push('scripts')

    {{-- @include('sections.datatable_js')

    <script>
        $('#holiday-table').on('preXhr.dt', function(e, settings, data) {
            var month = $('#month').val();
            var year = $('#year').val();
            var searchText = $('#search-text-field').val();

            data['month'] = month;
            data['year'] = year;
            data['searchText'] = searchText;
        });

        const showTable = () => {
            window.LaravelDataTables["holiday-table"].draw();
        }

        $('#search-text-field, #month, #year').on('change keyup',
            function() {
                if ($('#month').val() != "") {
                    $('#reset-filters').removeClass('d-none');
                    showTable();
                } else if ($('#year').val() != "") {
                    $('#reset-filters').removeClass('d-none');
                    showTable();
                } else if ($('#search-text-field').val() != "") {
                    $('#reset-filters').removeClass('d-none');
                    showTable();
                } else {
                    $('#reset-filters').addClass('d-none');
                    showTable();
                }
            });

        $('#reset-filters').click(function() {
            $('#filter-form')[0].reset();
            $('#month').val('{{ $currentMonth }}');
            $('#year').val('{{ $currentYear }}');
            $('.filter-box .select-picker').selectpicker("refresh");
            $('#reset-filters').addClass('d-none');
            showTable();
        });

        $('#quick-action-type').change(function() {
            const actionValue = $(this).val();

            if (actionValue != '') {
                $('#quick-action-apply').removeAttr('disabled');
            } else {
                $('#quick-action-apply').attr('disabled', true);
                $('.quick-action-field').addClass('d-none');
            }
        });

        $('#quick-action-apply').click(function() {
            const actionValue = $('#quick-action-type').val();
            if (actionValue == 'delete') {
                Swal.fire({
                    title: "@lang('messages.sweetAlertTitle')",
                    text: "@lang('messages.recoverRecord')",
                    icon: 'warning',
                    showCancelButton: true,
                    focusConfirm: false,
                    confirmButtonText: "@lang('messages.confirmDelete')",
                    cancelButtonText: "@lang('app.cancel')",
                    customClass: {
                        confirmButton: 'btn btn-primary mr-3',
                        cancelButton: 'btn btn-secondary'
                    },
                    showClass: {
                        popup: 'swal2-noanimation',
                        backdrop: 'swal2-noanimation'
                    },
                    buttonsStyling: false
                }).then((result) => {
                    if (result.isConfirmed) {
                        applyQuickAction();
                    }
                });

            } else {
                applyQuickAction();
            }
        });

        $('body').on('click', '.delete-table-row', function() {
            var id = $(this).data('holiday-id');
            Swal.fire({
                title: "@lang('messages.sweetAlertTitle')",
                text: "@lang('messages.recoverRecord')",
                icon: 'warning',
                showCancelButton: true,
                focusConfirm: false,
                confirmButtonText: "@lang('messages.confirmDelete')",
                cancelButtonText: "@lang('app.cancel')",
                customClass: {
                    confirmButton: 'btn btn-primary mr-3',
                    cancelButton: 'btn btn-secondary'
                },
                showClass: {
                    popup: 'swal2-noanimation',
                    backdrop: 'swal2-noanimation'
                },
                buttonsStyling: false
            }).then((result) => {
                if (result.isConfirmed) {
                    var url = "{{ route('holidays.destroy', ':id') }}";
                    url = url.replace(':id', id);

                    var token = "{{ csrf_token() }}";

                    $.easyAjax({
                        type: 'POST',
                        url: url,
                        data: {
                            '_token': token,
                            '_method': 'DELETE'
                        },
                        success: function(response) {
                            if (response.status == "success") {
                                showTable();
                            }
                        }
                    });
                }
            });
        });

        const applyQuickAction = () => {
            var rowdIds = $("#holiday-table input:checkbox:checked").map(function() {
                return $(this).val();
            }).get();

            var url = "{{ route('holidays.apply_quick_action') }}?row_ids=" + rowdIds;

            $.easyAjax({
                url: url,
                container: '#quick-action-form',
                type: "POST",
                disableButton: true,
                buttonSelector: "#quick-action-apply",
                data: $('#quick-action-form').serialize(),
                success: function(response) {
                    if (response.status == 'success') {
                        showTable();
                        resetActionButtons();
                        deSelectAll();
                    }
                }
            })
        };

        $('body').on('click', '.show-holiday', function() {
            var holidayId = $(this).data('holiday-id');

            var url = '{{ route('holidays.show', ':id') }}';
            url = url.replace(':id', holidayId);

            $(MODAL_LG + ' ' + MODAL_HEADING).html('...');
            $.ajaxModal(MODAL_LG, url);
        });

        $('body').on('click', '#mark-holiday', function() {
            var url = "{{ route('holidays.mark_holiday') }}?year" + $('#year').val();

            $(MODAL_LG + ' ' + MODAL_HEADING).html('...');
            $.ajaxModal(MODAL_LG, url);
        });
    </script> --}}
@endpush
