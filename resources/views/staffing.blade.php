<!DOCTYPE html>
<html>

<head>
    <title>TA Insights</title>
    <style>
        th,
        td {
            height: 5px;
        }

        .red-bg {
            background-color: #ff6f6f;
        }

        .green-bg {
            background-color: #90EE90;
        }

        .yellow-bg {
            background-color: #ffd966;
        }

        .white-bg {
            background-color: white;
        }
    </style>

</head>

<body style="font-family: Arial, sans-serif; color: #333;">
    <div>
        <p style="margin: 10px 0;">Hi Team,</p>
        <p style="margin: 10px 0;">Please see below for the most updated Staffing Hiring Updates.</p>

        <div style="margin: 10px 0">
            <p style="margin: 10px 0;">MONTHLY VIEW</p>
            <table style="width: 70%; border-collapse: collapse;">
                <thead>
                    <tr>
                        <th style="padding: 5px; text-align: left; background-color: blue; color: white;">
                            Month</th>
                        <th style="padding: 5px; text-align: left; background-color: blue; color: white;">
                            Week Name</th>
                        <th style="padding: 5px; text-align: left; background-color: blue; color: white;">
                            Total Target</th>
                        <th style="padding: 5px; text-align: left; background-color: blue; color: white;">
                            Overall Pipeline</th>
                        <th style="padding: 5px; text-align: left; background-color: blue; color: white;">
                            Pipeline To Goal%</th>
                        <th style="padding: 5px; text-align: left; background-color: blue; color: white;">
                            Total Internals</th>
                        <th style="padding: 5px; text-align: left; background-color: blue; color: white;">
                            Total Externals</th>
                        <th style="padding: 5px; text-align: left; background-color: blue; color: white;">
                            For JO</th>
                        <th style="padding: 5px; text-align: left; background-color: blue; color: white;">
                            For Testing</th>
                        <th style="padding: 5px; text-align: left; background-color: blue; color: white;">
                            OV</th>
                        <th style="padding: 5px; text-align: left; background-color: blue; color: white;">
                            Internal </th>
                        <th style="padding: 5px; text-align: left; background-color: blue; color: white;">
                            External</th>
                        <th style="padding: 5px; text-align: left; background-color: blue; color: white;">
                            Total SU</th>
                        <th style="padding: 5px; text-align: left; background-color: blue; color: white;">
                            Fill Rate%</th>
                        <th style="padding: 5px; text-align: left; background-color: blue; color: white;">
                            Day 1</th>
                        <th style="padding: 5px; text-align: left; background-color: blue; color: white;">
                            Day 1%</th>
                            <th style="padding: 5px; text-align: left; background-color: blue; color: white;">
                                Hires to Goal%</th>
                            <th style="padding: 5px; text-align: left; background-color: blue; color: white;">
                                Status</th>

                    </tr>
                </thead>
                <tbody>
                    @foreach ($wtd as $data1)
                        <tr @if ($loop->last) class="last-row" @endif>
                            @if (is_array($data1))
                                <td style="border: 1px solid #ccc; padding: 5px; text-align: left;">
                                    {{ isset($data1['month']) ? $data1['month'] : '' }}
                                </td>
                                <td style="border: 1px solid #ccc; padding: 5px; text-align: left;">
                                    {{ isset($data1['week_name']) ? $data1['week_name'] : '' }}
                                </td>
                                <td style="border: 1px solid #ccc; padding: 5px; text-align: left; ">
                                    {{ isset($data1['total_target']) ? $data1['total_target'] : 'N/A' }}
                                </td>
                                <td style="border: 1px solid #ccc; padding: 5px; text-align: left; ">
                                    {{ isset($data1['pipeline_total']) ? $data1['pipeline_total'] : 'N/A' }}
                                </td>
                                <td style="border: 1px solid #ccc; padding: 5px; text-align: left; ">
                                    {{ isset($data1['pipeline_goal']) ? $data1['pipeline_goal'] : 'N/A' }}%
                                </td>
                                <td style="border: 1px solid #ccc; padding: 5px; text-align: left; ">
                                    {{ isset($data1['total_internal']) ? $data1['total_internal'] : 'N/A' }}
                                </td>
                                <td style="border: 1px solid #ccc; padding: 5px; text-align: left; ">
                                    {{ isset($data1['total_external']) ? $data1['total_external'] : 'N/A' }}
                                </td>
                                <td style="border: 1px solid #ccc; padding: 5px; text-align: left; ">
                                    {{ isset($data1['jo']) ? $data1['jo'] : 'N/A' }}
                                </td>
                                <td style="border: 1px solid #ccc; padding: 5px; text-align: left; ">
                                    {{ isset($data1['versant']) ? $data1['versant'] : 'N/A' }}
                                </td>
                                <td style="border: 1px solid #ccc; padding: 5px; text-align: left; ">
                                    {{ isset($data1['ov']) ? $data1['ov'] : 'N/A' }}
                                </td>
                                <td style="border: 1px solid #ccc; padding: 5px; text-align: left; ">
                                    {{ isset($data1['internal']) ? $data1['internal'] : 'N/A' }}
                                </td>
                                <td style="border: 1px solid #ccc; padding: 5px; text-align: left; ">
                                    {{ isset($data1['external']) ? $data1['external'] : 'N/A' }}
                                </td>
                                <td style="border: 1px solid #ccc; padding: 5px; text-align: left; ">
                                    {{ isset($data1['total_show_ups']) ? $data1['total_show_ups'] : 'N/A' }}
                                </td>
                                <td style="border: 1px solid #ccc; padding: 5px; text-align: left; ">
                                    {{ isset($data1['fill_rate']) ? $data1['fill_rate'] : 'N/A' }}%
                                </td>
                                <td style="border: 1px solid #ccc; padding: 5px; text-align: left; ">
                                    {{ isset($data1['day_1']) ? $data1['day_1'] : 'N/A' }}
                                </td>
                                <td style="border: 1px solid #ccc; padding: 5px; text-align: left; ">
                                    {{ isset($data1['day_1sup']) ? $data1['day_1sup'] : 'N/A' }} %
                                </td>
                                <td style="border: 1px solid #ccc; padding: 5px; text-align: left;">
                                    {{ isset($data1['hires_goal']) ? $data1['hires_goal'] : 'N/A' }}%
                                </td>
                                <td @if (isset($data1['color_status'])) class="@if ($data1['color_status'] == 'Red')red-bg @elseif($data1['color_status'] == 'Green')green-bg @elseif($data1['color_status'] == 'Yellow')yellow-bg @else white-bg @endif"@endif>
                                    {{ $data1['color_status'] }}
                                </td>
                            @endif


                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div style="margin: 10px 0">
            <p style="margin: 10px 0;">WEEKLY VIEW</p>
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
                            Fill Rate%</th>
                        <th style="padding: 5px; text-align: left; background-color: blue; color: white;">
                            For JO</th>
                        <th style="padding: 5px; text-align: left; background-color: blue; color: white;">
                            For Testing</th>
                        <th style="padding: 5px; text-align: left; background-color: blue; color: white;">
                            OV</th>
                        <th style="padding: 5px; text-align: left; background-color: blue; color: white;">
                            Day 1</th>
                        <th style="padding: 5px; text-align: left; background-color: blue; color: white;">
                            Day 1%</th>
                        <th style="padding: 5px; text-align: left; background-color: blue; color: white;">
                            Pipeline Total</th>
                        <th style="padding: 5px; text-align: left; background-color: blue; color: white;">
                            Hires to Goal%</th>
                        <th style="padding: 5px; text-align: left; background-color: blue; color: white;">
                            Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($weeklyPipe as $data1)
                        <tr @if ($loop->first) class="last-row" @endif>
                            @if (is_array($data1))
                                <td style="border: 1px solid #ccc; padding: 5px; text-align: left;">
                                    {{ isset($data1['week_name']) ? $data1['week_name'] : '' }}
                                </td>
                                <td style="border: 1px solid #ccc; padding: 5px; text-align: left;">
                                    {{ isset($data1['site_name']) ? $data1['site_name'] : '' }}
                                </td>
                                <td style="border: 1px solid #ccc; padding: 5px; text-align: left;">
                                    {{ isset($data1['program_name']) ? $data1['program_name'] : '' }}
                                </td>
                                <td style="border: 1px solid #ccc; padding: 5px; text-align: left;">
                                    {{ isset($data1['program_group']) ? $data1['program_group'] : 'N/A' }}
                                </td>
                                <td style="border: 1px solid #ccc; padding: 5px; text-align: left;">
                                    {{ isset($data1['total_target']) ? $data1['total_target'] : 'N/A' }}
                                </td>
                                <td style="border: 1px solid #ccc; padding: 5px; text-align: left;">
                                    {{ isset($data1['show_ups_internal']) ? $data1['show_ups_internal'] : 'N/A' }}
                                </td>
                                <td style="border: 1px solid #ccc; padding: 5px; text-align: left;">
                                    {{ isset($data1['show_ups_external']) ? $data1['show_ups_external'] : 'N/A' }}
                                </td>
                                <td style="border: 1px solid #ccc; padding: 5px; text-align: left;">
                                    {{ isset($data1['show_ups_total']) ? $data1['show_ups_total'] : 'N/A' }}
                                </td>
                                <td style="border: 1px solid #ccc; padding: 5px; text-align: left;">
                                    {{ isset($data1['fillrate']) ? $data1['fillrate'] : 'N/A' }}%
                                </td>
                                <td style="border: 1px solid #ccc; padding: 5px; text-align: left;">
                                    {{ isset($data1['day_1']) ? $data1['day_1'] : 'N/A' }}
                                </td>
                                <td style="border: 1px solid #ccc; padding: 5px; text-align: left;">
                                    {{ isset($data1['pending_jo']) ? $data1['pending_jo'] : 'N/A' }}
                                </td>
                                <td style="border: 1px solid #ccc; padding: 5px; text-align: left;">
                                    {{ isset($data1['pending_berlitz']) ? $data1['pending_berlitz'] : 'N/A' }}
                                </td>
                                <td style="border: 1px solid #ccc; padding: 5px; text-align: left;">
                                    {{ isset($data1['pending_ov']) ? $data1['pending_ov'] : 'N/A' }}
                                </td>
                                <td style="border: 1px solid #ccc; padding: 5px; text-align: left;">
                                    {{ isset($data1['day_1sup']) ? $data1['day_1sup'] : 'N/A' }}%
                                </td>
                                <td style="border: 1px solid #ccc; padding: 5px; text-align: left;">
                                    {{ isset($data1['pipeline_total']) ? $data1['pipeline_total'] : 'N/A' }}
                                </td>
                                <td style="border: 1px solid #ccc; padding: 5px; text-align: left;">
                                    {{ isset($data1['hires_goal']) ? $data1['hires_goal'] : 'N/A' }}%
                                </td>
                                <td @if (isset($data1['color_status'])) class="@if ($data1['color_status'] == 'Red')red-bg @elseif($data1['color_status'] == 'Green')green-bg @elseif($data1['color_status'] == 'Yellow')yellow-bg @else white-bg @endif"@endif>
                                    {{ $data1['color_status'] }}
                                </td>
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
