<table class="table table-striped table-bordered table-hover table-header-fixed dataTable" id="bookings_calendar_view_admin" role="grid">
@if (count($result))
    <tbody>
        @foreach($result['items'] as $int=>$statuses)
            <tr role="row" class="odd">
                <td style="padding-right:0px;">
                    {{$int}}
                </td>
                @foreach($statuses as $status)
                    <td class="isfreetime" style="padding:4px 8px; overflow:hidden; max-width:150px;  ">
                        <span data-resource="3" data-time="00:00">{{ $status['status'] }}</span>
                    </td>
                @endforeach
            </tr>
        @endforeach
    </tbody>
    <thead>
        <tr role="row">
            <th width="5%" class="sorting_disabled" rowspan="1" colspan="1" style="width: 51px; min-width: 51px;">
                Time
            </th>
            @foreach($result['resouses'] as $resourse)
            <th style="width: 798px; min-width: 798px;" class="sorting_disabled" rowspan="1" colspan="1">
                {{$resourse['resouse_name']}}
            </th>
            @endforeach
        </tr>
    </thead>
    <tfoot>
        <tr>
            <th width="5%" rowspan="1" colspan="1" style="width: 51px; min-width: 51px;"> 
                Time 
            </th>
            @foreach($result['resouses'] as $resourse)
            <th style="width: 798px; min-width: 798px;" class="sorting_disabled" rowspan="1" colspan="1">
                {{$resourse['resouse_name']}}
            </th>
            @endforeach
        </tr>
    </tfoot>
@else
    <thead>
        <tr role="row">
            <th>
                Data not found
            </th>
        </tr>
    </thead>
@endif
</table>