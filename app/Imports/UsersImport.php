<?php

namespace App\Imports;

use App\Models\Whatsapp;
use Maatwebsite\Excel\Concerns\ToModel;

use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class UsersImport implements ToModel, WithStartRow, WithValidation, WithHeadingRow
{

	public function model(array $row)
	{
		return Whatsapp::create([
			'name' => $row["name"],
			'phone' => $row["phone"],
			'section' => $row["section"],
		]);
		/*new Whatsapp([
			'name' => $row['name'],
			'phone' => $row['phone'],
			'section' => $row['section'],
		]);*/

	}


	public function startRow(): int
	{
		return 2;
	}


	public function rules(): array
	{
		return [
			'name' => 'required',
		];
	}
}
