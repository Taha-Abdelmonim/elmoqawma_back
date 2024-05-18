<?php
  
  namespace App\Exports;
  
  use Illuminate\Contracts\View\View;
  use Maatwebsite\Excel\Concerns\FromCollection;
  use Maatwebsite\Excel\Concerns\FromView;
  
  class BankTransactionExport implements FromView
  {
    public $array;
    public function __construct($array)
    {
      $this->array = $array;
    }
    public function view(): View
    {
      return view('pdf.bank_transactions', [
        // 'from' => $this->array['from'],
        // 'to' => $this->array['to'],
        'bankTransactions' => $this->array['bankTransactions'],
      ]);
    }
    
    public function columnFormats(): array
    {
      return [
        'I' =>  "0",
      ];
    }
  }

