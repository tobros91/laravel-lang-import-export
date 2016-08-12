<?php

namespace UFirst\LangImportExport\Console;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use \UFirst\LangImportExport\Facades\LangListService;

use Lang;

class ExportToCsvCommand extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'lang-export:csv';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = "Exports the language files to CSV files";

	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments()
	{
		return array(
			array('locale', InputArgument::REQUIRED, 'The locale to be exported.'),
			array('group', InputArgument::REQUIRED, 'The group (which is the name of the language file without the extension)'),
		);
	}

	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getOptions()
	{
		return array(
			array('delimiter', 'd', InputOption::VALUE_OPTIONAL, 'The optional delimiter parameter sets the field delimiter (one character only).', ','),
			array('enclosure', 'c', InputOption::VALUE_OPTIONAL, 'The optional enclosure parameter sets the field enclosure (one character only).', '"'),
			array('output', 'o', InputOption::VALUE_OPTIONAL, 'Redirect the output to this file'),
			array('mirror', 'm', InputOption::VALUE_OPTIONAL, 'Locales to mirror from master'),
		);
	}

	/**
	 * Execute the console command.
	 *
	 * @return void
	 */
	public function fire()
	{
		$locale = $this->argument('locale');
		$group  = $this->argument('group');

		$delimiter = $this->option('delimiter');
		$enclosure = $this->option('enclosure');

		$mirror = $this->option('mirror');

		if(!empty($mirror)) {
			$mirror = explode(",", $mirror);
		}


		$strings = LangListService::loadLangList($locale, $group);
		

		// Create output device and write CSV.
		$output = $this->option('output');
		if (empty($output) || !($out = fopen($output, 'w'))) {
			$out = fopen('php://output', 'w');
		}

		// Write CSV lintes
		foreach ($strings as $key => $value) {

			
			$row = array($key, $value);

			foreach($mirror AS $m) {


				$mirror_value = Lang::get($key, array(), $m);

				array_push($row, $mirror_value);
			}
			



			fputcsv($out, $row, $delimiter, $enclosure);
		}

		fclose($out);
	}
}