<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $title }}</title>
    <style>
        body { font-family: 'Helvetica', sans-serif; color: #1e293b; line-height: 1.5; }
        .header { text-align: center; border-bottom: 2px solid #f1f5f9; padding-bottom: 20px; margin-bottom: 30px; }
        .logo { font-size: 24px; font-weight: bold; color: #0f172a; }
        .logo span { color: #ca8a04; }
        .report-info { margin-bottom: 30px; }
        .report-info table { width: 100%; }
        .report-info td { vertical-align: top; font-size: 12px; }
        .table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        .table th { background: #f8fafc; border-bottom: 2px solid #e2e8f0; text-align: left; padding: 12px; font-size: 11px; text-transform: uppercase; letter-spacing: 1px; }
        .table td { padding: 12px; border-bottom: 1px solid #f1f5f9; font-size: 11px; }
        .amount { text-align: right; font-weight: bold; }
        .income { color: #16a34a; }
        .expense { color: #1e293b; }
        .footer { margin-top: 50px; text-align: center; font-size: 10px; color: #94a3b8; border-top: 1px solid #f1f5f9; padding-top: 20px; }
        .summary { margin-top: 30px; float: right; width: 250px; }
        .summary table { width: 100%; border-top: 2px solid #0f172a; }
        .summary td { padding: 8px 0; font-size: 12px; }
        .total-row { font-weight: bold; font-size: 16px !important; border-top: 1px solid #e2e8f0; }
    </style>
</head>
<body>
    <div class="header">
        <div class="logo">Spend<span>Wise</span></div>
        <div style="font-size: 14px; color: #64748b; margin-top: 5px;">Professional Financial Statement</div>
    </div>

    <div class="report-info">
        <table>
            <tr>
                <td>
                    <strong>Prepared For:</strong><br>
                    {{ $user->name }}<br>
                    {{ $user->email }}
                </td>
                <td style="text-align: right;">
                    <strong>Report Period:</strong><br>
                    {{ date('M d, Y', strtotime($dateFrom)) }} - {{ date('M d, Y', strtotime($dateTo)) }}<br>
                    <strong>Generated On:</strong> {{ $date }}<br>
                    @if($account)<strong>Account:</strong> {{ $account->name }}@endif
                </td>
            </tr>
        </table>
    </div>

    <table class="table">
        <thead>
            <tr>
                <th>Date</th>
                <th>Category</th>
                <th>Type</th>
                <th>Notes</th>
                <th style="text-align: right;">Amount ({{ $currency }})</th>
            </tr>
        </thead>
        <tbody>
            @php $totalIncome = 0; $totalExpense = 0; @endphp
            @if($includeInitialBalance && $initialBalance != 0)
                <tr style="background: #eff6ff;">
                    <td>{{ date('M d, Y', strtotime($dateFrom)) }}</td>
                    <td>—</td>
                    <td>Initial Balance</td>
                    <td style="color: #64748b;">—</td>
                    <td class="amount" style="color: #2563eb;">{{ number_format($initialBalance, 2) }}</td>
                </tr>
            @endif
            @foreach($transactions as $t)
                @php 
                    if($t->type == 'income') $totalIncome += $t->amount;
                    else $totalExpense += $t->amount;
                @endphp
                <tr>
                    <td>{{ date('M d, Y', strtotime($t->date)) }}</td>
                    <td>{{ $t->category->name ?? 'Uncategorized' }}</td>
                    <td>{{ ucfirst($t->type) }}</td>
                    <td style="color: #64748b;">{{ $t->notes }}</td>
                    <td class="amount {{ $t->type == 'income' ? 'income' : 'expense' }}">
                        {{ $t->type == 'income' ? '+' : '-' }} {{ number_format($t->amount, 2) }}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="summary">
        <table>
            @if($includeInitialBalance && $initialBalance != 0)
            <tr>
                <td>Initial Balance:</td>
                <td style="text-align: right; color: #2563eb;">{{ number_format($initialBalance, 2) }}</td>
            </tr>
            @endif
            <tr>
                <td>Total Income:</td>
                <td style="text-align: right; color: #16a34a;">+ {{ number_format($totalIncome, 2) }}</td>
            </tr>
            <tr>
                <td>Total Expenses:</td>
                <td style="text-align: right;">- {{ number_format($totalExpense, 2) }}</td>
            </tr>
            <tr class="total-row">
                <td>{{ $includeInitialBalance ? 'Closing Balance:' : 'Net Cashflow:' }}</td>
                <td style="text-align: right; {{ $netCashflow >= 0 ? 'color: #16a34a;' : 'color: #dc2626;' }}">
                    {{ $netCashflow >= 0 ? '+' : '' }}{{ number_format($netCashflow, 2) }}
                </td>
            </tr>
        </table>
    </div>

    <div style="clear: both;"></div>

    <div class="footer">
        This is a computer-generated document from SpendWise Expense Tracker.<br>
        &copy; {{ date('Y') }} SpendWise. All rights reserved.
    </div>
</body>
</html>
