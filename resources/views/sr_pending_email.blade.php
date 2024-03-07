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

<body style="background-color: #f3f4f6; font-family: Arial, sans-serif; color: #333;">
    <div>
        <strong>
            <p style="font-size: 20px; font-weight: bold; margin-bottom: 20px;">Test email only. Please update data.</p>
        </strong>
        <p style="margin: 10px 0;">Hi Team,</p>
        <p style="margin: 10px 0;">Please find below our SR Pending Movement Report as of Mar 1. (Date coverage Jan 1 -
            Feb 23 ).</p>
        <p>The Sourcing Team has a total pending of <strong>{{ $mappedResult['2. ONLINE ASSESSMENT']['TotalCount'] }}</strong>
            pending online assessments, <strong>{{ $mappedResult['2. ONLINE ASSESSMENT']['MaxAppStepCount'] }}</strong> of which are
            still <strong>{{ $mappedResult['2. ONLINE ASSESSMENT']['MaxAppStep'] }}</strong>.</p>
    </div>

    <p>The H&S Team has a total pending movement of <strong>{{ $totalPending }}</strong> - <strong>{{ $mappedResult['3. INITIAL INTERVIEW']['TotalCount'] }}</strong> pending initial interviews, <strong>{{ $mappedResult['4. BEHAVIORAL INTERVIEW']['TotalCount'] }}</strong> pending behavioral interviews, <strong>{{ $mappedResult['5. OPERATIONS VALIDATION']['TotalCount'] }}</strong> OV.</p>

    <div class="px-4 pb-4 pt-0">
        <div class="bg-white shadow-md rounded-lg overflow-x-auto overflow-y-auto">
            <table class="min-w-full border-collapse border-2 border-gray-300">
                <thead>
                    <tr class="border-b-4 border-gray-300 bg-gray-100 text-center">
                        <th class="border-4 border-gray-300 px-4 py-2">Step</th>
                        <th class="border-2 border-gray-300 px-4 py-2">Bridgetowne</th>
                        <th class="border-2 border-gray-300 px-4 py-2">Clark</th>
                        <th class="border-2 border-gray-300 px-4 py-2">Davao</th>
                        <th class="border-2 border-gray-300 px-4 py-2">Makati</th>
                        <th class="border-2 border-gray-300 px-4 py-2">MOA</th>
                        <th class="border-2 border-gray-300 px-4 py-2">QC North Edsa</th>
                        <th class="border-4 border-gray-300 px-4 py-2">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($formattedResult as $item)
                    <tr style="border: 4px solid #d1d5db; padding: 8px;">
                        <td style="{{ $item['Step'] ? 'font-weight: bold; background-color: #BFDBFE; padding: 8px;' : '' }} {{ $item['AppStep'] ? 'font-weight: bold; padding: 8px;' : '' }}">
                            {{ $item['Step'] ?: $item['AppStep'] }}
                        </td>
                        <td style="border: 2px solid #d1d5db; padding: 8px; text-align: center;">{{ $item['Bridgetowne'] }}</td>
                        <td style="border: 2px solid #d1d5db; padding: 8px; text-align: center;">{{ $item['Clark'] }}</td>
                        <td style="border: 2px solid #d1d5db; padding: 8px; text-align: center;">{{ $item['Davao'] }}</td>
                        <td style="border: 2px solid #d1d5db; padding: 8px; text-align: center;">{{ $item['Makati'] }}</td>
                        <td style="border: 2px solid #d1d5db; padding: 8px; text-align: center;">{{ $item['MOA'] }}</td>
                        <td style="border: 2px solid #d1d5db; padding: 8px; text-align: center;">{{ $item['QC North EDSA'] }}</td>
                        <td style="border: 4px solid #d1d5db; padding: 8px; text-align: center; font-weight: bold;">{{ $item['TotalCount'] }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <p style="margin: 10px 0;font-weight: bold;">Thanks,</p>
    <p style="margin: 10px 0;font-weight: bold;">TA Reports Team</p>
</body>

</html>
