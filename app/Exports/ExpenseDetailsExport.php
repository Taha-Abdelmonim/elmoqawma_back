<?php
  
  namespace App\Exports;
  
  use Illuminate\Contracts\View\View;
  use Maatwebsite\Excel\Concerns\FromCollection;
  use Maatwebsite\Excel\Concerns\FromView;
  
  class ExpenseDetailsExport implements FromView
  {
    public $array;
    public function __construct($array)
    {
      $this->array = $array;
    }
    public function view(): View
    {
      return view('pdf.expense_details', [
        // 'from' => $this->array['from'],
        // 'to' => $this->array['to'],
        'expenseDetails' => $this->array['expenseDetails'],
      ]);
    }
    
    public function columnFormats(): array
    {
      return [
        'I' =>  "0",
      ];
    }
  }

