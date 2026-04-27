<?php

namespace App\Livewire;

use App\Models\Transaction;
use App\Models\Category;
use App\Models\Account;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Response;

class ReportManager extends Component
{
    public $dateFrom;
    public $dateTo;
    public $categoryId = '';
    public $type = 'all';
    public $accountId = '';
    public $includeInitialBalance = false;
    public $initialBalance = 0;

    public function mount()
    {
        $this->dateFrom = date('Y-m-01'); // Start of current month
        $this->dateTo = date('Y-m-d');
        $this->recalculateInitialBalance();
    }

    public function getFilteredTransactions()
    {
        $query = Transaction::where('user_id', Auth::id())
            ->with('category', 'account')
            ->whereBetween('date', [$this->dateFrom, $this->dateTo]);

        if ($this->categoryId) {
            $query->where('category_id', $this->categoryId);
        }

        if ($this->type !== 'all') {
            $query->where('type', $this->type);
        }

        if ($this->accountId) {
            $query->where('account_id', $this->accountId);
        }

        return $query->orderBy('date', 'desc')->get();
    }

    public function updatedAccountId()
    {
        $this->recalculateInitialBalance();
    }

    public function updatedDateFrom()
    {
        $this->recalculateInitialBalance();
    }

    public function updatedIncludeInitialBalance()
    {
        $this->recalculateInitialBalance();
    }

    public function recalculateInitialBalance()
    {
        if ($this->dateFrom) {
            // Get current balance of selected account or all accounts
            $query = Account::where('user_id', Auth::id());
            if ($this->accountId) {
                $query->where('id', $this->accountId);
            }
            $currentBalance = $query->sum('balance');

            // Calculate net change from dateFrom until now
            // We need to subtract ALL transactions that happened on or after dateFrom 
            // to find the balance as it was at the START of dateFrom.
            $netSince = Transaction::where('user_id', Auth::id())
                ->when($this->accountId, function($q) {
                    return $q->where('account_id', $this->accountId);
                })
                ->where('date', '>=', $this->dateFrom)
                ->get()
                ->sum(function($t) {
                    return $t->type === 'income' ? $t->amount : -$t->amount;
                });

            $this->initialBalance = $currentBalance - $netSince;
        } else {
            $this->initialBalance = 0;
        }
    }

    public function getNetCashflow()
    {
        $transactions = $this->getFilteredTransactions();
        $total = $transactions->sum(function($t) {
            return $t->type === 'income' ? $t->amount : -$t->amount;
        });
        
        if ($this->includeInitialBalance) {
            return $this->initialBalance + $total;
        }
        return $total;
    }

    public function exportPdf()
    {
        $transactions = $this->getFilteredTransactions();
        $user = Auth::user();
        $account = $this->accountId ? Account::find($this->accountId) : null;
        
        $data = [
            'title' => 'Financial Report',
            'date' => date('m/d/Y'),
            'transactions' => $transactions,
            'user' => $user,
            'dateFrom' => $this->dateFrom,
            'dateTo' => $this->dateTo,
            'currency' => $user->currency ?? '$',
            'account' => $account,
            'includeInitialBalance' => $this->includeInitialBalance,
            'initialBalance' => $this->includeInitialBalance ? $this->initialBalance : 0,
            'netCashflow' => $this->getNetCashflow()
        ];

        $pdf = Pdf::loadView('reports.pdf', $data);
        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->stream();
        }, 'SpendWise_Report_' . date('Ymd') . '.pdf');
    }

    public function exportExcel()
    {
        $transactions = $this->getFilteredTransactions();
        $currency = Auth::user()->currency ?? '$';
        $account = $this->accountId ? Account::find($this->accountId) : null;

        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=SpendWise_Report_" . date('Ymd') . ".csv",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $columns = ['Date', 'Category', 'Account', 'Type', 'Notes', 'Amount (' . $currency . ')'];
        
        $includeInitialBalance = $this->includeInitialBalance;
        $initialBalance = $this->includeInitialBalance ? $this->initialBalance : 0;
        $netCashflow = $this->getNetCashflow();

        $dateFrom = $this->dateFrom;
        $callback = function() use($transactions, $columns, $account, $includeInitialBalance, $initialBalance, $netCashflow, $dateFrom) {
            $file = fopen('php://output', 'w');
            // Add account info header if selected
            if ($account) {
                fputcsv($file, ['Account:', $account->name]);
            }
            fputcsv($file, []); // Empty row
            fputcsv($file, $columns);
            // Add initial balance as first row if included
            if ($includeInitialBalance && $initialBalance != 0) {
                fputcsv($file, [date('Y-m-d', strtotime($dateFrom)), '—', $account ? $account->name : 'N/A', 'Initial Balance', '', number_format($initialBalance, 2)]);
            }
            foreach ($transactions as $t) {
                $row['Date']     = $t->date;
                $row['Category'] = $t->category->name ?? 'Uncategorized';
                $row['Account']  = $t->account->name ?? 'N/A';
                $row['Type']     = ucfirst($t->type);
                $row['Notes']    = $t->notes;
                $row['Amount']   = ($t->type === 'income' ? '+' : '-') . number_format($t->amount, 2);
                fputcsv($file, array($row['Date'], $row['Category'], $row['Account'], $row['Type'], $row['Notes'], $row['Amount']));
            }
            // Add summary
            fputcsv($file, []); // Empty row
            fputcsv($file, [$includeInitialBalance ? 'Closing Balance:' : 'Net Cashflow:', ($netCashflow >= 0 ? '+' : '') . number_format($netCashflow, 2)]);
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function render()
    {
        $categories = Category::forUser()->orderBy('name')->get();
        $accounts = Account::where('user_id', Auth::id())->orderBy('name')->get();
        $preview = $this->getFilteredTransactions()->take(10); // Show only top 10 in preview

        return view('livewire.report-manager', [
            'categories' => $categories,
            'accounts' => $accounts,
            'preview' => $preview,
            'count' => $this->getFilteredTransactions()->count(),
            'total' => $this->getNetCashflow(),
            'initialBalance' => $this->includeInitialBalance ? $this->initialBalance : 0
        ]);
    }
}
