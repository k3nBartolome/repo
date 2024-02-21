<!DOCTYPE html>
<html>

<head>
    <title>TA Insights</title>
    <style>
        th, td {
            height: 5px;
        }
    </style>
    
</head>

<body style="background-color: #f3f4f6; font-family: Arial, sans-serif; color: #333;">
    <div>
        <strong>
            <p style="font-size: 20px; font-weight: bold; margin-bottom: 20px;">Test email only. This will go live by Feb 28 9PM. Please update data.</p>
        </strong>
        <p style="margin: 10px 0;">TA Insights</p>
        <p style="margin: 10px 0;">Please see below for the most updated cap file.</p>
        <div style="margin: 10px 0">
        <p style="margin: 10px 0;">PER SITE:</p>
        <table style="width: 70%; border-collapse: collapse;">
            <thead>
                <tr>
                    <th
                       style="padding: 5px; text-align: left; background-color: blue; color: white;">
                        Site</th>
                    <th
                       style="padding: 5px; text-align: left; background-color: blue; color: white;">
                        Jan</th>
                    <th
                       style="padding: 5px; text-align: left; background-color: blue; color: white;">
                        Feb</th>
                    <th
                       style="padding: 5px; text-align: left; background-color: blue; color: white;">
                        Mar</th>
                    <th
                       style="padding: 5px; text-align: left; background-color: blue; color: white;">
                        Apr</th>
                    <th
                       style="padding: 5px; text-align: left; background-color: blue; color: white;">
                        May</th>
                    <th
                       style="padding: 5px; text-align: left; background-color: blue; color: white;">
                        Jun</th>
                    <th
                       style="padding: 5px; text-align: left; background-color: blue; color: white;">
                        Jul</th>
                    <th
                       style="padding: 5px; text-align: left; background-color: blue; color: white;">
                        Aug</th>
                    <th
                       style="padding: 5px; text-align: left; background-color: blue; color: white;">
                        Sep</th>
                    <th
                       style="padding: 5px; text-align: left; background-color: blue; color: white;">
                        Oct</th>
                    <th
                       style="padding: 5px; text-align: left; background-color: blue; color: white;">
                        Nov</th>
                    <th
                       style="padding: 5px; text-align: left; background-color: blue; color: white;">
                        Dec</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($mappedGroupedClasses as $index => $data)
                <tr @if($loop->last) class="last-row" @endif>
                    <td style="border: 1px solid #ccc; padding: 5px; text-align: left; truncate">{{
                        $data['Site'] }}</td>
                    <td style="border: 1px solid #ccc; padding: 5px; text-align: left; truncate">{{
                        $data['January'] }}</td>
                    <td style="border: 1px solid #ccc; padding: 5px; text-align: left; truncate">{{
                        $data['February'] }}</td>
                    <td style="border: 1px solid #ccc; padding: 5px; text-align: left; truncate">{{
                        $data['March'] }}</td>
                    <td style="border: 1px solid #ccc; padding: 5px; text-align: left; truncate">{{
                        $data['April'] }}</td>
                    <td style="border: 1px solid #ccc; padding: 5px; text-align: left; truncate">{{
                        $data['May'] }}</td>
                    <td style="border: 1px solid #ccc; padding: 5px; text-align: left; truncate">{{
                        $data['June'] }}</td>
                    <td style="border: 1px solid #ccc; padding: 5px; text-align: left; truncate">{{
                        $data['July'] }}</td>
                    <td style="border: 1px solid #ccc; padding: 5px; text-align: left; truncate">{{
                        $data['August'] }}</td>
                    <td style="border: 1px solid #ccc; padding: 5px; text-align: left; truncate">{{
                        $data['September'] }}</td>
                    <td style="border: 1px solid #ccc; padding: 5px; text-align: left; truncate">{{
                        $data['October'] }}</td>
                    <td style="border: 1px solid #ccc; padding: 5px; text-align: left; truncate">{{
                        $data['November'] }}</td>
                    <td style="border: 1px solid #ccc; padding: 5px; text-align: left; truncate">{{
                        $data['December'] }}</td>
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
                    <th
                       style="padding: 5px; text-align: left; background-color: blue; color: white;">
                        Site</th>
                    <th
                       style="padding: 5px; text-align: left; background-color: blue; color: white;">
                        Program</th>
                    <th
                       style="padding: 5px; text-align: left; background-color: blue; color: white;">
                        Jan</th>
                    <th
                       style="padding: 5px; text-align: left; background-color: blue; color: white;">
                        Feb</th>
                    <th
                       style="padding: 5px; text-align: left; background-color: blue; color: white;">
                        Mar</th>
                    <th
                       style="padding: 5px; text-align: left; background-color: blue; color: white;">
                        Apr</th>
                    <th
                       style="padding: 5px; text-align: left; background-color: blue; color: white;">
                        May</th>
                    <th
                       style="padding: 5px; text-align: left; background-color: blue; color: white;">
                        Jun</th>
                    <th
                       style="padding: 5px; text-align: left; background-color: blue; color: white;">
                        Jul</th>
                    <th
                       style="padding: 5px; text-align: left; background-color: blue; color: white;">
                        Aug</th>
                    <th
                       style="padding: 5px; text-align: left; background-color: blue; color: white;">
                        Sep</th>
                    <th
                       style="padding: 5px; text-align: left; background-color: blue; color: white;">
                        Oct</th>
                    <th
                       style="padding: 5px; text-align: left; background-color: blue; color: white;">
                        Nov</th>
                    <th
                       style="padding: 5px; text-align: left; background-color: blue; color: white;">
                        Dec</th>
                    <th
                       style="padding: 5px; text-align: left; background-color: blue; color: white;">
                        Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($mappedClasses as $index => $data)
                <tr @if($loop->last) class="last-row" @endif>
                    <td style="border: 1px solid #ccc; padding: 5px; text-align: left; truncate">{{
                        $data['Site'] }}</td>
                    <td style="border: 1px solid #ccc; padding: 5px; text-align: left; truncate">{{
                        $data['Program'] }}</td>
                    <td style="border: 1px solid #ccc; padding: 5px; text-align: left; truncate">{{
                        $data['Jan'] }}</td>
                    <td style="border: 1px solid #ccc; padding: 5px; text-align: left; truncate">{{
                        $data['Feb'] }}</td>
                    <td style="border: 1px solid #ccc; padding: 5px; text-align: left; truncate">{{
                        $data['Mar'] }}</td>
                    <td style="border: 1px solid #ccc; padding: 5px; text-align: left; truncate">{{
                        $data['Apr'] }}</td>
                    <td style="border: 1px solid #ccc; padding: 5px; text-align: left; truncate">{{
                        $data['May'] }}</td>
                    <td style="border: 1px solid #ccc; padding: 5px; text-align: left; truncate">{{
                        $data['Jun'] }}</td>
                    <td style="border: 1px solid #ccc; padding: 5px; text-align: left; truncate">{{
                        $data['Jul'] }}</td>
                    <td style="border: 1px solid #ccc; padding: 5px; text-align: left; truncate">{{
                        $data['Aug'] }}</td>
                    <td style="border: 1px solid #ccc; padding: 5px; text-align: left; truncate">{{
                        $data['Sep'] }}</td>
                    <td style="border: 1px solid #ccc; padding: 5px; text-align: left; truncate">{{
                        $data['Oct'] }}</td>
                    <td style="border: 1px solid #ccc; padding: 5px; text-align: left; truncate">{{
                        $data['Nov'] }}</td>
                    <td style="border: 1px solid #ccc; padding: 5px; text-align: left; truncate">{{
                        $data['Dec'] }}</td>
                    <td style="border: 1px solid #ccc; padding: 5px; text-align: left; truncate">{{
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