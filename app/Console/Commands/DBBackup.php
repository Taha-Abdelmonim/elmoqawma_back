<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;

class DBBackup extends Command
{

	protected $signature = 'db:backup';


	protected $description = 'Backup Database And Files';


	public function handle()
	{
		$pathCopy = public_path("../backup");
		$images = public_path("images");
		shell_exec("cp -r $images $pathCopy");
		//		 To BackUp Database
		$fileName = Carbon::now()->format("Y_m_d_H_i_s") . ".sql";
		$command = "mysqldump --user=" . env("DB_USERNAME") . " --password=" . env("DB_PASSWORD") . " --host=" . env("DB_HOST") . " " . env("DB_DATABASE") . " > " . public_path("../backup/database/") . $fileName;
		exec($command);
		return Command::SUCCESS;
	}
}
