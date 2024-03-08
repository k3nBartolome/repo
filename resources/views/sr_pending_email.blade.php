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
        <p style="margin: 10px 0;">Hi Team,</p>
        <p style="margin: 10px 0;">Please find below our SR Pending Movement Report as of {{ date('F j, Y') }}(Date coverage January 01 - {{ date('F j') }} )</p>

        
        @if(isset($mappedResult['2. ONLINE ASSESSMENT']))
            <p>The Sourcing Team has a total pending of <strong>{{ $mappedResult['2. ONLINE ASSESSMENT']['TotalCount'] }}</strong>
                pending online assessments, <strong>{{ $mappedResult['2. ONLINE ASSESSMENT']['MaxAppStepCount'] }}</strong> of which are
                still <strong>{{ strtolower($mappedResult['2. ONLINE ASSESSMENT']['MaxAppStep']) }}</strong>
                .</p>
        @else
            <p>No data available for Online Assessment.</p>
        @endif
        
        @php
            $totalPendingHS = ($mappedResult['3. INITIAL INTERVIEW']['TotalCount'] ?? 0) + ($mappedResult['4. BEHAVIORAL INTERVIEW']['TotalCount'] ?? 0) + ($mappedResult['5. OPERATIONS VALIDATION']['TotalCount'] ?? 0);
        @endphp

        <p>The H&S Team has a total pending movement of <strong>{{ $totalPendingHS }}</strong> - 
            <strong>{{ $mappedResult['3. INITIAL INTERVIEW']['TotalCount'] ?? 'N/A' }}</strong> pending initial interviews, 
            <strong>{{ $mappedResult['4. BEHAVIORAL INTERVIEW']['TotalCount'] ?? 'N/A' }}</strong> pending behavioral interviews, 
            <strong>{{ $mappedResult['5. OPERATIONS VALIDATION']['TotalCount'] ?? 'N/A' }}</strong> OV.</p>
    </div>


    <div style="margin: 10px 0">
    <div style="padding: 1rem 2rem 2rem 0;">
        <div style="background-color: white; box-shadow: 0 4px 6px 0 hsla(0, 0%, 0%, 0.07), 0 2px 4px 0 hsla(0, 0%, 0%, 0.06); border-radius: 0.5rem; overflow-x: auto; overflow-y: auto;">
            <table style="min-width: 100%; border-collapse: collapse; border: 2px solid #d1d5db;">
                <thead>
                    <tr class="border-b-4 border-gray-300 bg-gray-100 text-center">
                        <th style="padding: 5px; text-align: left; background-color: gray; color: white;">Step</th>
                        <th style="padding: 5px; text-align: left; background-color: gray; color: white;">Bridgetowne</th>
                        <th style="padding: 5px; text-align: left; background-color: gray; color: white;">Clark</th>
                        <th style="padding: 5px; text-align: left; background-color: gray; color: white;">Davao</th>
                        <th style="padding: 5px; text-align: left; background-color: gray; color: white;">Makati</th>
                        <th style="padding: 5px; text-align: left; background-color: gray; color: white;">MOA</th>
                        <th style="padding: 5px; text-align: left; background-color: gray; color: white;">QC North Edsa</th>
                        <th style="padding: 5px; text-align: left; background-color: gray; color: white;">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($formattedResult as $item)
                    @if(isset($item['Step']))
                    <tr style="border: 1px solid #ccc; padding: 5px; font-weight: bold; background-color: #60a5fa;">
                    @else
                    <tr style="border: 1px solid #ccc;">
                    @endif
                        <td style="text-align: left;">
                            {{ $item['Step'] ?? $item['AppStep'] }}
                        </td>
                        <td style="border: 1px solid #ccc; padding: 5px; text-align: center;">{{ $item['Bridgetowne'] ?? '' }}</td>
                        <td style="border: 1px solid #ccc; padding: 5px; text-align: center;">{{ $item['Clark'] ?? '' }}</td>
                        <td style="border: 1px solid #ccc; padding: 5px; text-align: center;">{{ $item['Davao'] ?? '' }}</td>
                        <td style="border: 1px solid #ccc; padding: 5px; text-align: center;">{{ $item['Makati'] ?? '' }}</td>
                        <td style="border: 1px solid #ccc; padding: 5px; text-align: center;">{{ $item['MOA'] ?? '' }}</td>
                        <td style="border: 1px solid #ccc; padding: 5px; text-align: center;">{{ $item['QC North EDSA'] ?? '' }}</td>
                        <td style="border: 1px solid #ccc; padding: 5px; text-align: center; font-weight: bold;">{{ $item['TotalCount'] ?? '' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
    
    

    <p style="margin: 10px 0;font-weight: bold;">Thanks,</p>
    <p style="margin: 10px 0;font-weight: bold;">TA Reports Team</p>
</body>

</html>
