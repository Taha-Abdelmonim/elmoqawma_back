<?php

namespace App\Exports;

use App\Models\Whatsapp;
use Maatwebsite\Excel\Concerns\FromCollection;

class UsersExport implements FromCollection
{

	public function collection()
	{
		return Whatsapp::select("name", "phone", "section")->get();
	}
}
