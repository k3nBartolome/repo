<!DOCTYPE html>
<html>

<head>
    <title>Mail from App</title>
    <style>
        /* Custom CSS */
        body {
            background-color: #f3f4f6; /* Light gray background */
            font-family: Arial, sans-serif; /* Specify preferred font */
            color: #333; /* Text color */
            padding: 20px; /* Add some padding */
        }
        .email-heading {
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            margin-bottom: 20px;
        }
        th, td {
        border: 1px solid #ccc;
        padding: 8px;
        text-align: left;
        white-space: nowrap; /* Add this line */
    }
        th, .last-row {
            background-color: blue; /* Blue background */
            color: white; /* White text */
        }
        tr:not(.last-row):nth-child(even) {
            background-color: gray; /* Gray background */
            color: black; /* Black text */
        }
        p {
            margin: 10px 0;
        }
    </style>
</head>

<body>
    <div>
        <strong><p >Test Email</p></strong>
        <p >Hi Team,</p>
        <p >Please see below for the most updated cap file.</p>
        <table>
            <thead>
                <tr >
                    <th>Site</th>
                    <th>Jan</th>
                    <th>Feb</th>
                    <th>Mar</th>
                    <th>Apr</th>
                    <th>May</th>
                    <th>Jun</th>
                    <th>Jul</th>
                    <th>Aug</th>
                    <th>Sep</th>
                    <th>Oct</th>
                    <th>Nov</th>
                    <th>Dec</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($mappedGroupedClasses as $index => $data)
                    <tr @if($loop->last) class="last-row" @endif>
                        <td>{{ $data['Site'] }}</td>
                        <td>{{ $data['January'] }}</td>
                        <td>{{ $data['February'] }}</td>
                        <td>{{ $data['March'] }}</td>
                        <td>{{ $data['April'] }}</td>
                        <td>{{ $data['May'] }}</td>
                        <td>{{ $data['June'] }}</td>
                        <td>{{ $data['July'] }}</td>
                        <td>{{ $data['August'] }}</td>
                        <td>{{ $data['September'] }}</td>
                        <td>{{ $data['October'] }}</td>
                        <td>{{ $data['November'] }}</td>
                        <td>{{ $data['December'] }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <table>
            <thead>
                <tr >
                    <th>Site</th>
                    <th>Program</th>
                    <th>Jan</th>
                    <th>Feb</th>
                    <th>Mar</th>
                    <th>Apr</th>
                    <th>May</th>
                    <th>Jun</th>
                    <th>Jul</th>
                    <th>Aug</th>
                    <th>Sep</th>
                    <th>Oct</th>
                    <th>Nov</th>
                    <th>Dec</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($mappedClasses as $index => $data)
                    <tr @if($loop->last) class="last-row" @endif>
                        <td>{{ $data['Site'] }}</td>
                        <td>{{ $data['Program'] }}</td>
                        <td>{{ $data['Jan'] }}</td>
                        <td>{{ $data['Feb'] }}</td>
                        <td>{{ $data['Mar'] }}</td>
                        <td>{{ $data['Apr'] }}</td>
                        <td>{{ $data['May'] }}</td>
                        <td>{{ $data['Jun'] }}</td>
                        <td>{{ $data['Jul'] }}</td>
                        <td>{{ $data['Aug'] }}</td>
                        <td>{{ $data['Sep'] }}</td>
                        <td>{{ $data['Oct'] }}</td>
                        <td>{{ $data['Nov'] }}</td>
                        <td>{{ $data['Dec'] }}</td>
                        <td>{{ $data['GrandTotalByProgram'] }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <p>Thanks,</p>
        <p>TA Reports Team</p>
    </div>
</body>

</html>
