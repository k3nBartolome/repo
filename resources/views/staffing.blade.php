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
                    <tr @if($loop->last) class="last-row" @endif>
                        <td style="border: 1px solid #ccc; padding: 5px; text-align: left; ">{{
                        $data['month'] }}</td>
                        <td style="border: 1px solid #ccc; padding: 5px; text-align: left; ">{{
                        $data['total_target'] }}</td>
                        <td style="border: 1px solid #ccc; padding: 5px; text-align: left; ">{{
                        $data['internal'] }}</td>
                        <td style="border: 1px solid #ccc; padding: 5px; text-align: left; ">{{
                        $data['external'] }}</td>
                        <td style="border: 1px solid #ccc; padding: 5px; text-align: left; ">{{
                        $data['total'] }}</td>
                        <td style="border: 1px solid #ccc; padding: 5px; text-align: left; ">{{
                        $data['fillrate'] }}</td>
                        <td style="border: 1px solid #ccc; padding: 5px; text-align: left; ">{{
                        $data['day_1'] }}</td>
                        <td style="border: 1px solid #ccc; padding: 5px; text-align: left; ">{{
                        $data['day_1sup'] }}</td>
                        <td style="border: 1px solid #ccc; padding: 5px; text-align: left; ">{{
                        $data['pipeline_total'] }}</td>
                        <td style="border: 1px solid #ccc; padding: 5px; text-align: left; ">{{
                        $data['hires_goal'] }}</td>
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