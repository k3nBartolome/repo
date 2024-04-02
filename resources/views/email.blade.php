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
        <p style="margin: 10px 0;">Please see below for the most updated cap file.</p>
        <div style="margin: 10px 0">
            <p style="margin: 10px 0;">PER SITE:</p>
            <table style="width: 70%; border-collapse: collapse;">
                <thead>
                    <tr>
                        <th style="padding: 5px; text-align: left; background-color: blue; color: white;">
                            Site</th>
                        <th style="padding: 5px; text-align: left; background-color: blue; color: white;">
                            Jan</th>
                        <th style="padding: 5px; text-align: left; background-color: blue; color: white;">
                            Feb</th>
                        <th style="padding: 5px; text-align: left; background-color: blue; color: white;">
                            Mar</th>
                        <th style="padding: 5px; text-align: left; background-color: blue; color: white;">
                            Apr</th>
                        <th style="padding: 5px; text-align: left; background-color: blue; color: white;">
                            May</th>
                        <th style="padding: 5px; text-align: left; background-color: blue; color: white;">
                            Jun</th>
                        <th style="padding: 5px; text-align: left; background-color: blue; color: white;">
                            Jul</th>
                        <th style="padding: 5px; text-align: left; background-color: blue; color: white;">
                            Aug</th>
                        <th style="padding: 5px; text-align: left; background-color: blue; color: white;">
                            Sep</th>
                        <th style="padding: 5px; text-align: left; background-color: blue; color: white;">
                            Oct</th>
                        <th style="padding: 5px; text-align: left; background-color: blue; color: white;">
                            Nov</th>
                        <th style="padding: 5px; text-align: left; background-color: blue; color: white;">
                            Dec</th>
                        <th style="padding: 5px; text-align: left; background-color: blue; color: white;">
                            Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($mappedGroupedClasses as $index => $data)
                    <tr @if($loop->last) class="last-row" @endif>
                        <td style="border: 1px solid #ccc; padding: 5px; text-align: left; ">{{
                        $data['Site'] }}</td>
                        <td style="border: 1px solid #ccc; padding: 5px; text-align: left; ">{{
                        $data['January'] }}</td>
                        <td style="border: 1px solid #ccc; padding: 5px; text-align: left; ">{{
                        $data['February'] }}</td>
                        <td style="border: 1px solid #ccc; padding: 5px; text-align: left; ">{{
                        $data['March'] }}</td>
                        <td style="border: 1px solid #ccc; padding: 5px; text-align: left; ">{{
                        $data['April'] }}</td>
                        <td style="border: 1px solid #ccc; padding: 5px; text-align: left; ">{{
                        $data['May'] }}</td>
                        <td style="border: 1px solid #ccc; padding: 5px; text-align: left; ">{{
                        $data['June'] }}</td>
                        <td style="border: 1px solid #ccc; padding: 5px; text-align: left; ">{{
                        $data['July'] }}</td>
                        <td style="border: 1px solid #ccc; padding: 5px; text-align: left; ">{{
                        $data['August'] }}</td>
                        <td style="border: 1px solid #ccc; padding: 5px; text-align: left; ">{{
                        $data['September'] }}</td>
                        <td style="border: 1px solid #ccc; padding: 5px; text-align: left; ">{{
                        $data['October'] }}</td>
                        <td style="border: 1px solid #ccc; padding: 5px; text-align: left; ">{{
                        $data['November'] }}</td>
                        <td style="border: 1px solid #ccc; padding: 5px; text-align: left; ">{{
                        $data['December'] }}</td>
                        <td style="border: 1px solid #ccc; padding: 5px; text-align: left; ">{{
                        $data['GrandTotalByProgram'] }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div style="margin: 10px 0">
            <p style="margin: 10px 0;">PER SITE INTERNAL:</p>
            <table style="width: 70%; border-collapse: collapse;">
                <thead>
                    <tr>
                        <th style="padding: 5px; text-align: left; background-color: blue; color: white;">
                            Site</th>
                        <th style="padding: 5px; text-align: left; background-color: blue; color: white;">
                            Jan</th>
                        <th style="padding: 5px; text-align: left; background-color: blue; color: white;">
                            Feb</th>
                        <th style="padding: 5px; text-align: left; background-color: blue; color: white;">
                            Mar</th>
                        <th style="padding: 5px; text-align: left; background-color: blue; color: white;">
                            Apr</th>
                        <th style="padding: 5px; text-align: left; background-color: blue; color: white;">
                            May</th>
                        <th style="padding: 5px; text-align: left; background-color: blue; color: white;">
                            Jun</th>
                        <th style="padding: 5px; text-align: left; background-color: blue; color: white;">
                            Jul</th>
                        <th style="padding: 5px; text-align: left; background-color: blue; color: white;">
                            Aug</th>
                        <th style="padding: 5px; text-align: left; background-color: blue; color: white;">
                            Sep</th>
                        <th style="padding: 5px; text-align: left; background-color: blue; color: white;">
                            Oct</th>
                        <th style="padding: 5px; text-align: left; background-color: blue; color: white;">
                            Nov</th>
                        <th style="padding: 5px; text-align: left; background-color: blue; color: white;">
                            Dec</th>
                        <th style="padding: 5px; text-align: left; background-color: blue; color: white;">
                            Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($mappedInternalClasses as $index => $data)
                    <tr @if($loop->last) class="last-row" @endif>
                        <td style="border: 1px solid #ccc; padding: 5px; text-align: left; ">{{
                        $data['Site'] }}</td>
                        <td style="border: 1px solid #ccc; padding: 5px; text-align: left; ">{{
                        $data['January'] }}</td>
                        <td style="border: 1px solid #ccc; padding: 5px; text-align: left; ">{{
                        $data['February'] }}</td>
                        <td style="border: 1px solid #ccc; padding: 5px; text-align: left; ">{{
                        $data['March'] }}</td>
                        <td style="border: 1px solid #ccc; padding: 5px; text-align: left; ">{{
                        $data['April'] }}</td>
                        <td style="border: 1px solid #ccc; padding: 5px; text-align: left; ">{{
                        $data['May'] }}</td>
                        <td style="border: 1px solid #ccc; padding: 5px; text-align: left; ">{{
                        $data['June'] }}</td>
                        <td style="border: 1px solid #ccc; padding: 5px; text-align: left; ">{{
                        $data['July'] }}</td>
                        <td style="border: 1px solid #ccc; padding: 5px; text-align: left; ">{{
                        $data['August'] }}</td>
                        <td style="border: 1px solid #ccc; padding: 5px; text-align: left; ">{{
                        $data['September'] }}</td>
                        <td style="border: 1px solid #ccc; padding: 5px; text-align: left; ">{{
                        $data['October'] }}</td>
                        <td style="border: 1px solid #ccc; padding: 5px; text-align: left; ">{{
                        $data['November'] }}</td>
                        <td style="border: 1px solid #ccc; padding: 5px; text-align: left; ">{{
                        $data['December'] }}</td>
                        <td style="border: 1px solid #ccc; padding: 5px; text-align: left; ">{{
                        $data['GrandTotalByProgram'] }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div style="margin: 10px 0">
            <p style="margin: 10px 0;">PER SITE EXTERNAL:</p>
            <table style="width: 70%; border-collapse: collapse;">
                <thead>
                    <tr>
                        <th style="padding: 5px; text-align: left; background-color: blue; color: white;">
                            Site</th>
                        <th style="padding: 5px; text-align: left; background-color: blue; color: white;">
                            Jan</th>
                        <th style="padding: 5px; text-align: left; background-color: blue; color: white;">
                            Feb</th>
                        <th style="padding: 5px; text-align: left; background-color: blue; color: white;">
                            Mar</th>
                        <th style="padding: 5px; text-align: left; background-color: blue; color: white;">
                            Apr</th>
                        <th style="padding: 5px; text-align: left; background-color: blue; color: white;">
                            May</th>
                        <th style="padding: 5px; text-align: left; background-color: blue; color: white;">
                            Jun</th>
                        <th style="padding: 5px; text-align: left; background-color: blue; color: white;">
                            Jul</th>
                        <th style="padding: 5px; text-align: left; background-color: blue; color: white;">
                            Aug</th>
                        <th style="padding: 5px; text-align: left; background-color: blue; color: white;">
                            Sep</th>
                        <th style="padding: 5px; text-align: left; background-color: blue; color: white;">
                            Oct</th>
                        <th style="padding: 5px; text-align: left; background-color: blue; color: white;">
                            Nov</th>
                        <th style="padding: 5px; text-align: left; background-color: blue; color: white;">
                            Dec</th>
                        <th style="padding: 5px; text-align: left; background-color: blue; color: white;">
                            Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($mappedExternalClasses as $index => $data)
                    <tr @if($loop->last) class="last-row" @endif>
                        <td style="border: 1px solid #ccc; padding: 5px; text-align: left; ">{{
                        $data['Site'] }}</td>
                        <td style="border: 1px solid #ccc; padding: 5px; text-align: left; ">{{
                        $data['January'] }}</td>
                        <td style="border: 1px solid #ccc; padding: 5px; text-align: left; ">{{
                        $data['February'] }}</td>
                        <td style="border: 1px solid #ccc; padding: 5px; text-align: left; ">{{
                        $data['March'] }}</td>
                        <td style="border: 1px solid #ccc; padding: 5px; text-align: left; ">{{
                        $data['April'] }}</td>
                        <td style="border: 1px solid #ccc; padding: 5px; text-align: left; ">{{
                        $data['May'] }}</td>
                        <td style="border: 1px solid #ccc; padding: 5px; text-align: left; ">{{
                        $data['June'] }}</td>
                        <td style="border: 1px solid #ccc; padding: 5px; text-align: left; ">{{
                        $data['July'] }}</td>
                        <td style="border: 1px solid #ccc; padding: 5px; text-align: left; ">{{
                        $data['August'] }}</td>
                        <td style="border: 1px solid #ccc; padding: 5px; text-align: left; ">{{
                        $data['September'] }}</td>
                        <td style="border: 1px solid #ccc; padding: 5px; text-align: left; ">{{
                        $data['October'] }}</td>
                        <td style="border: 1px solid #ccc; padding: 5px; text-align: left; ">{{
                        $data['November'] }}</td>
                        <td style="border: 1px solid #ccc; padding: 5px; text-align: left; ">{{
                        $data['December'] }}</td>
                        <td style="border: 1px solid #ccc; padding: 5px; text-align: left; ">{{
                        $data['GrandTotalByProgram'] }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div style="margin: 10px 0">
            <p style="margin: 10px 0;">B2 SUMMARY:</p>
            <table style="width: 70%; border-collapse: collapse;">
                <thead>
                    <tr>
                        <th style="padding: 5px; text-align: left; background-color: blue; color: white;">
                            Site</th>
                        <th style="padding: 5px; text-align: left; background-color: blue; color: white;">
                            Jan</th>
                        <th style="padding: 5px; text-align: left; background-color: blue; color: white;">
                            Feb</th>
                        <th style="padding: 5px; text-align: left; background-color: blue; color: white;">
                            Mar</th>
                        <th style="padding: 5px; text-align: left; background-color: blue; color: white;">
                            Apr</th>
                        <th style="padding: 5px; text-align: left; background-color: blue; color: white;">
                            May</th>
                        <th style="padding: 5px; text-align: left; background-color: blue; color: white;">
                            Jun</th>
                        <th style="padding: 5px; text-align: left; background-color: blue; color: white;">
                            Jul</th>
                        <th style="padding: 5px; text-align: left; background-color: blue; color: white;">
                            Aug</th>
                        <th style="padding: 5px; text-align: left; background-color: blue; color: white;">
                            Sep</th>
                        <th style="padding: 5px; text-align: left; background-color: blue; color: white;">
                            Oct</th>
                        <th style="padding: 5px; text-align: left; background-color: blue; color: white;">
                            Nov</th>
                        <th style="padding: 5px; text-align: left; background-color: blue; color: white;">
                            Dec</th>
                        <th style="padding: 5px; text-align: left; background-color: blue; color: white;">
                            Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($mappedB2Classes as $index => $data)
                    <tr @if($loop->last) class="last-row" @endif>
                        <td style="border: 1px solid #ccc; padding: 5px; text-align: left; ">{{
                        $data['Site'] }}</td>
                        <td style="border: 1px solid #ccc; padding: 5px; text-align: left; ">{{
                        $data['January'] }}</td>
                        <td style="border: 1px solid #ccc; padding: 5px; text-align: left; ">{{
                        $data['February'] }}</td>
                        <td style="border: 1px solid #ccc; padding: 5px; text-align: left; ">{{
                        $data['March'] }}</td>
                        <td style="border: 1px solid #ccc; padding: 5px; text-align: left; ">{{
                        $data['April'] }}</td>
                        <td style="border: 1px solid #ccc; padding: 5px; text-align: left; ">{{
                        $data['May'] }}</td>
                        <td style="border: 1px solid #ccc; padding: 5px; text-align: left; ">{{
                        $data['June'] }}</td>
                        <td style="border: 1px solid #ccc; padding: 5px; text-align: left; ">{{
                        $data['July'] }}</td>
                        <td style="border: 1px solid #ccc; padding: 5px; text-align: left; ">{{
                        $data['August'] }}</td>
                        <td style="border: 1px solid #ccc; padding: 5px; text-align: left; ">{{
                        $data['September'] }}</td>
                        <td style="border: 1px solid #ccc; padding: 5px; text-align: left; ">{{
                        $data['October'] }}</td>
                        <td style="border: 1px solid #ccc; padding: 5px; text-align: left; ">{{
                        $data['November'] }}</td>
                        <td style="border: 1px solid #ccc; padding: 5px; text-align: left; ">{{
                        $data['December'] }}</td>
                        <td style="border: 1px solid #ccc; padding: 5px; text-align: left; ">{{
                        $data['GrandTotalByProgram'] }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div style="margin: 10px 0">
            <p style="margin: 10px 0;">PER PROGRAM:</p>
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr>
                        <th style="padding: 5px; text-align: left; background-color: blue; color: white;">
                            Site</th>
                        <th style="padding: 5px; text-align: left; background-color: blue; color: white;">
                            Program</th>
                        <th style="padding: 5px; text-align: left; background-color: blue; color: white;">
                            Jan</th>
                        <th style="padding: 5px; text-align: left; background-color: blue; color: white;">
                            Feb</th>
                        <th style="padding: 5px; text-align: left; background-color: blue; color: white;">
                            Mar</th>
                        <th style="padding: 5px; text-align: left; background-color: blue; color: white;">
                            Apr</th>
                        <th style="padding: 5px; text-align: left; background-color: blue; color: white;">
                            May</th>
                        <th style="padding: 5px; text-align: left; background-color: blue; color: white;">
                            Jun</th>
                        <th style="padding: 5px; text-align: left; background-color: blue; color: white;">
                            Jul</th>
                        <th style="padding: 5px; text-align: left; background-color: blue; color: white;">
                            Aug</th>
                        <th style="padding: 5px; text-align: left; background-color: blue; color: white;">
                            Sep</th>
                        <th style="padding: 5px; text-align: left; background-color: blue; color: white;">
                            Oct</th>
                        <th style="padding: 5px; text-align: left; background-color: blue; color: white;">
                            Nov</th>
                        <th style="padding: 5px; text-align: left; background-color: blue; color: white;">
                            Dec</th>
                        <th style="padding: 5px; text-align: left; background-color: blue; color: white;">
                            Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($mappedClasses as $index => $data)
                    <tr @if($loop->last) class="last-row" @endif>
                        <td style="border: 1px solid #ccc; padding: 5px; text-align: left; ">{{
                        $data['Site'] }}</td>
                        <td style="border: 1px solid #ccc; padding: 5px; text-align: left; ">{{
                        $data['Program'] }}</td>
                        <td style="border: 1px solid #ccc; padding: 5px; text-align: left; ">{{
                        $data['Jan'] }}</td>
                        <td style="border: 1px solid #ccc; padding: 5px; text-align: left; ">{{
                        $data['Feb'] }}</td>
                        <td style="border: 1px solid #ccc; padding: 5px; text-align: left; ">{{
                        $data['Mar'] }}</td>
                        <td style="border: 1px solid #ccc; padding: 5px; text-align: left; ">{{
                        $data['Apr'] }}</td>
                        <td style="border: 1px solid #ccc; padding: 5px; text-align: left; ">{{
                        $data['May'] }}</td>
                        <td style="border: 1px solid #ccc; padding: 5px; text-align: left; ">{{
                        $data['Jun'] }}</td>
                        <td style="border: 1px solid #ccc; padding: 5px; text-align: left; ">{{
                        $data['Jul'] }}</td>
                        <td style="border: 1px solid #ccc; padding: 5px; text-align: left; ">{{
                        $data['Aug'] }}</td>
                        <td style="border: 1px solid #ccc; padding: 5px; text-align: left; ">{{
                        $data['Sep'] }}</td>
                        <td style="border: 1px solid #ccc; padding: 5px; text-align: left; ">{{
                        $data['Oct'] }}</td>
                        <td style="border: 1px solid #ccc; padding: 5px; text-align: left; ">{{
                        $data['Nov'] }}</td>
                        <td style="border: 1px solid #ccc; padding: 5px; text-align: left; ">{{
                        $data['Dec'] }}</td>
                        <td style="border: 1px solid #ccc; padding: 5px; text-align: left; ">{{
                        $data['GrandTotalByProgram'] }}</td>
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