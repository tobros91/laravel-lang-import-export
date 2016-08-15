<?php

namespace UFirst\LangImportExport\Console;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use \UFirst\LangImportExport\Facades\LangListService;

class ImportFromCsvCommand extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'lang-import:csv';

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
			array('file', InputArgument::REQUIRED, 'The CSV file to be imported'),
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
			array('escape',    'e', InputOption::VALUE_OPTIONAL, 'The escape character (one character only). Defaults as a backslash.', '\\'),
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
		$file   = $this->argument('file');

		$delimiter = $this->option('delimiter');
		$enclosure = $this->option('enclosure');
		$escape    = $this->option('escape');

		$strings = array();
		

		

		$groups = array();

		// If group = all we find all groups in given csv file and add to group array.

		if($group == 'all') {

			// Get all groups in csv

			// Create output device and write CSV.
			if (($input_fp = fopen($file, 'r')) === FALSE) {
				$this->error('Can\'t open the input file!');
			}

			while (($data = fgetcsv($input_fp, 0, $delimiter, $enclosure, $escape)) !== FALSE) {

				$group_in_csv = explode(".", $data[0]);
				$group_in_csv = $group_in_csv[0];

				if(!in_array($group_in_csv, $groups)) {
					array_push($groups, $group_in_csv);
				}

			}

			fclose($input_fp);

		} else {

			array_push($groups, $group);

		}

		// Loop all groups and write lang files

		foreach($groups AS $group) {

			// Create output device and write CSV.
			if (($input_fp = fopen($file, 'r')) === FALSE) {
				$this->error('Can\'t open the input file!');
			}

			// Write CSV lintes
			while (($data = fgetcsv($input_fp, 0, $delimiter, $enclosure, $escape)) !== FALSE) {

				$group_in_csv = explode(".", $data[0]);
				$group_in_csv = $group_in_csv[0];

				if($group_in_csv == $group) {

					$strings[$data[0]] = $data[1];

				}
			}

			fclose($input_fp);
			LangListService::writeLangList($locale, $group, $strings);

		}
	}
}