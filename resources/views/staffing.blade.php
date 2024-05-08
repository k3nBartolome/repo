<!DOCTYPE html>
<html>

<head>
    <title>TA Insights</title>
    <style>
        th,
        td {
            height: 5px;
        }
    </style>

</head>

<body style="font-family: Arial, sans-serif; color: #333;">
    <div>
        <p style="margin: 10px 0;">Hi Team,</p>
        <p style="margin: 10px 0;">Please see below for the most updated Staffing Hiring Updates.</p>
        <div style="margin: 10px 0">
            <p style="margin: 10px 0;">YTD</p>
            <table style="width: 70%; border-collapse: collapse;">
                <thead>
                    <tr>
                        <th style="padding: 5px; text-align: left; background-color: blue; color: white;">
                            Month</th>
                        <th style="padding: 5px; text-align: left; background-color: blue; color: white;">
                            Total Target</th>
                        <th style="padding: 5px; text-align: left; background-color: blue; color: white;">
                            Internals</th>
                        <th style="padding: 5px; text-align: left; background-color: blue; color: white;">
                            Externals</th>
                        <th style="padding: 5px; text-align: left; background-color: blue; color: white;">
                            Total</th>
                        <th style="padding: 5px; text-align: left; background-color: blue; color: white;">
                            fillrate</th>
                        <th style="padding: 5px; text-align: left; background-color: blue; color: white;">
                            Day 1</th>
                        <th style="padding: 5px; text-align: left; background-color: blue; color: white;">
                            Day 1%</th>
                        <th style="padding: 5px; text-align: left; background-color: blue; color: white;">
                            Pipeline Total</th>
                        <th style="padding: 5px; text-align: left; background-color: blue; color: white;">
                            Hires to Goal%</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($ytd as $index => $data)
                        <tr @if ($loop->last) class="last-row" @endif>
                            <td style="border: 1px solid #ccc; padding: 5px; text-align: left; ">{{ $data['month'] }}
                            </td>
                            <td style="border: 1px solid #ccc; padding: 5px; text-align: left; ">
                                {{ $data['total_target'] }}</td>
                            <td style="border: 1px solid #ccc; padding: 5px; text-align: left; ">{{ $data['internal'] }}
                            </td>
                            <td style="border: 1px solid #ccc; padding: 5px; text-align: left; ">{{ $data['external'] }}
                            </td>
                            <td style="border: 1px solid #ccc; padding: 5px; text-align: left; ">{{ $data['total'] }}
                            </td>
                            <td style="border: 1px solid #ccc; padding: 5px; text-align: left; ">{{ $data['fillrate'] }}
                            </td>
                            <td style="border: 1px solid #ccc; padding: 5px; text-align: left; ">{{ $data['day_1'] }}
                            </td>
                            <td style="border: 1px solid #ccc; padding: 5px; text-align: left; ">{{ $data['day_1sup'] }}
                            </td>
                            <td style="border: 1px solid #ccc; padding: 5px; text-align: left; ">
                                {{ $data['pipeline_total'] }}</td>
                            <td style="border: 1px solid #ccc; padding: 5px; text-align: left; ">
                                {{ $data['hires_goal'] }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div style="margin: 10px 0">
            <p style="margin: 10px 0;">WTD</p>
            <table style="width: 70%; border-collapse: collapse;">
                <thead>
                    <tr>
                        <th style="padding: 5px; text-align: left; background-color: blue; color: white;">
                            Month</th>
                        <th style="padding: 5px; text-align: left; background-color: blue; color: white;">
                            Week Start</th>
                        <th style="padding: 5px; text-align: left; background-color: blue; color: white;">
                            Week End</th>
                        <th style="padding: 5px; text-align: left; background-color: blue; color: white;">
                            Total Target</th>
                        <th style="padding: 5px; text-align: left; background-color: blue; color: white;">
                            Overall Pipeline</th>
                        <th style="padding: 5px; text-align: left; background-color: blue; color: white;">
                            Pipeline To Goal</th>
                        <th style="padding: 5px; text-align: left; background-color: blue; color: white;">
                            Total Internals</th>
                        <th style="padding: 5px; text-align: left; background-color: blue; color: white;">
                            Total Externals</th>
                        <th style="padding: 5px; text-align: left; background-color: blue; color: white;">
                            For JO</th>
                        <th style="padding: 5px; text-align: left; background-color: blue; color: white;">
                            For Testing</th>
                        <th style="padding: 5px; text-align: left; background-color: blue; color: white;">
                            Internal </th>
                        <th style="padding: 5px; text-align: left; background-color: blue; color: white;">
                            External</th>
                        <th style="padding: 5px; text-align: left; background-color: blue; color: white;">
                            Total SU</th>
                        <th style="padding: 5px; text-align: left; background-color: blue; color: white;">
                            Fill Rate</th>
                        <th style="padding: 5px; text-align: left; background-color: blue; color: white;">
                            Day 1</th>
                        <th style="padding: 5px; text-align: left; background-color: blue; color: white;">
                            Day 1%</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($wtd as $data1)
                        <tr @if ($loop->last) class="last-row" @endif>
                            @if (is_array($data1))
                                <td style="border: 1px solid #ccc; padding: 5px; text-align: left;">
                                    {{ isset($data1['month']) ? $data1['month'] : '' }}</td>
                                <td style="border: 1px solid #ccc; padding: 5px; text-align: left;">
                                    {{ isset($data1['week_start']) ? $data1['week_start'] : '' }}</td>
                                <td style="border: 1px solid #ccc; padding: 5px; text-align: left;">
                                    {{ isset($data1['week_end']) ? $data1['week_end'] : '' }}</td>
                                <td style="border: 1px solid #ccc; padding: 5px; text-align: left; ">
                                    {{ isset($data1['total_target']) ? $data1['total_target'] : 'N/A' }}</td>
                                <td style="border: 1px solid #ccc; padding: 5px; text-align: left; ">
                                    {{ isset($data1['pipeline_total']) ? $data1['pipeline_total'] : 'N/A' }}</td>
                                <td style="border: 1px solid #ccc; padding: 5px; text-align: left; ">
                                    {{ isset($data1['pipeline_goal']) ? $data1['pipeline_goal'] : 'N/A' }}</td>
                                <td style="border: 1px solid #ccc; padding: 5px; text-align: left; ">
                                    {{ isset($data1['total_internal']) ? $data1['total_internal'] : 'N/A' }}</td>
                                <td style="border: 1px solid #ccc; padding: 5px; text-align: left; ">
                                    {{ isset($data1['total_external']) ? $data1['total_external'] : 'N/A' }}</td>
                                <td style="border: 1px solid #ccc; padding: 5px; text-align: left; ">
                                    {{ isset($data1['jo']) ? $data1['jo'] : 'N/A' }}</td>
                                <td style="border: 1px solid #ccc; padding: 5px; text-align: left; ">
                                    {{ isset($data1['versant']) ? $data1['versant'] : 'N/A' }}</td>
                                <td style="border: 1px solid #ccc; padding: 5px; text-align: left; ">
                                    {{ isset($data1['internal']) ? $data1['internal'] : 'N/A' }}</td>
                                <td style="border: 1px solid #ccc; padding: 5px; text-align: left; ">
                                    {{ isset($data1['external']) ? $data1['external'] : 'N/A' }}</td>
                                <td style="border: 1px solid #ccc; padding: 5px; text-align: left; ">
                                    {{ isset($data1['total_show_ups']) ? $data1['total_show_ups'] : 'N/A' }}</td>
                                <td style="border: 1px solid #ccc; padding: 5px; text-align: left; ">
                                    {{ isset($data1['fill_rate']) ? $data1['fill_rate'] : 'N/A' }}</td>
                                <td style="border: 1px solid #ccc; padding: 5px; text-align: left; ">
                                    {{ isset($data1['day_1']) ? $data1['day_1'] : 'N/A' }}</td>
                                <td style="border: 1px solid #ccc; padding: 5px; text-align: left; ">
                                    {{ isset($data1['day_1sup']) ? $data1['day_1sup'] : 'N/A' }}</td>
                            @endif


                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div style="margin: 10px 0">
            <p style="margin: 10px 0;">WEEKLY PIPE</p>
            <table style="width: 70%; border-collapse: collapse;">
                <thead>
                    <tr>
                        <th style="padding: 5px; text-align: left; background-color: blue; color: white;">
                            Week Name</th>
                        <th style="padding: 5px; text-align: left; background-color: blue; color: white;">
                            Site Name</th>
                        <th style="padding: 5px; text-align: left; background-color: blue; color: white;">
                            Program Name</th>
                        <th style="padding: 5px; text-align: left; background-color: blue; color: white;">
                                Program Group</th>
                        <th style="padding: 5px; text-align: left; background-color: blue; color: white;">
                            Total Target</th>
                        <th style="padding: 5px; text-align: left; background-color: blue; color: white;">
                            Internals</th>
                        <th style="padding: 5px; text-align: left; background-color: blue; color: white;">
                            Externals</th>
                        <th style="padding: 5px; text-align: left; background-color: blue; color: white;">
                            Total</th>
                        <th style="padding: 5px; text-align: left; background-color: blue; color: white;">
                            fillrate</th>
                        <th style="padding: 5px; text-align: left; background-color: blue; color: white;">
                            Day 1</th>
                        <th style="padding: 5px; text-align: left; background-color: blue; color: white;">
                            Day 1%</th>
                        <th style="padding: 5px; text-align: left; background-color: blue; color: white;">
                            Pipeline Total</th>
                        <th style="padding: 5px; text-align: left; background-color: blue; color: white;">
                            Hires to Goal%</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($weeklyPipe as $data1)
                        <tr @if ($loop->last) class="last-row" @endif>
                            @if (is_array($data1))
                                <td style="border: 1px solid #ccc; padding: 5px; text-align: left;">
                                    {{ isset($data1['week_name']) ? $data1['week_name'] : '' }}</td>
                                <td style="border: 1px solid #ccc; padding: 5px; text-align: left;">
                                    {{ isset($data1['site_name']) ? $data1['site_name'] : '' }}</td>
                                <td style="border: 1px solid #ccc; padding: 5px; text-align: left;">
                                    {{ isset($data1['program_name']) ? $data1['program_name'] : '' }}</td>
                                <td style="border: 1px solid #ccc; padding: 5px; text-align: left; ">
                                    {{ isset($data1['program_group']) ? $data1['program_group'] : 'N/A' }}</td>
                                <td style="border: 1px solid #ccc; padding: 5px; text-align: left; ">
                                    {{ isset($data1['total_target']) ? $data1['total_target'] : 'N/A' }}</td>
                                <td style="border: 1px solid #ccc; padding: 5px; text-align: left; ">
                                    {{ isset($data1['show_ups_internal']) ? $data1['show_ups_internal'] : 'N/A' }}</td>
                                <td style="border: 1px solid #ccc; padding: 5px; text-align: left; ">
                                    {{ isset($data1['show_ups_external']) ? $data1['show_ups_external'] : 'N/A' }}</td>
                                <td style="border: 1px solid #ccc; padding: 5px; text-align: left; ">
                                    {{ isset($data1['show_ups_total']) ? $data1['show_ups_total'] : 'N/A' }}</td>
                                <td style="border: 1px solid #ccc; padding: 5px; text-align: left; ">
                                    {{ isset($data1['fillrate']) ? $data1['fillrate'] : 'N/A' }}</td>
                                <td style="border: 1px solid #ccc; padding: 5px; text-align: left; ">
                                    {{ isset($data1['day_1']) ? $data1['day_1'] : 'N/A' }}</td>
                                <td style="border: 1px solid #ccc; padding: 5px; text-align: left; ">
                                    {{ isset($data1['day_1sup']) ? $data1['day_1sup'] : 'N/A' }}</td>
                                <td style="border: 1px solid #ccc; padding: 5px; text-align: left; ">
                                    {{ isset($data1['pipeline_total']) ? $data1['pipeline_total'] : 'N/A' }}</td>
                                <td style="border: 1px solid #ccc; padding: 5px; text-align: left; ">
                                    {{ isset($data1['hires_goal']) ? $data1['hires_goal'] : 'N/A' }}</td>
                            @endif


                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <p style="margin: 10px 0;font-weight: bold;">Thanks,</p>
        <p style="margin: 10px 0;font-weight: bold;">TA Reports Team</p>
    </div>
</body>

</html>
