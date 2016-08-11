<?php

namespace UFirst\LangImportExport;

use \Riimu\Kit\PHPEncoder\PHPEncoder as PHPEncoder;

use Lang;

class LangListService {

	public function loadLangList($locale, $group) {
		$translations = Lang::getLoader()->load($locale, $group);
		$translations_with_prefix = array_dot(array($group => $translations));
		return $translations_with_prefix;
	}

	public function writeLangList($locale, $group, $new_translations) {



		$translations = Lang::getLoader()->load($locale, $group);


		// If lang file exists but is empty we reset $translations cuz somehow it get's returned as int 1
		if(!is_array($translations)) {
			$translations = array();
		}


		foreach($new_translations as $key => $value) {
			array_set($translations, $key, $value);
		}

		// Run array thrue https://github.com/Riimu/Kit-PHPEncoder to make lang file more readable 
		
		$encoder = new PHPEncoder();

		$translations = $encoder->encode($translations[$group], [
		    'array.inline' => false,
		    'array.omit' => false,
		    'array.indent' => 4,
		    'string.escape' => false,
		    'array.align' => true,

		]);
			

		$language_file = base_path("resources/lang/{$locale}/{$group}.php");

		// If file does not exist create an empty so is_writable() function does not fail

		if(!file_exists($language_file)) {

			$fp = fopen($language_file, 'w');
			fclose($fp);
			
		}


		$header = "<?php\n\nreturn ";
	

		if (is_writable($language_file) && ($fp = fopen($language_file, 'w')) !== FALSE) {

			fputs($fp, $header.$translations.";\n");
			fclose($fp);
		} else {
			throw new \Exception("Cannot open language file at {$language_file} for writing. Check the file permissions.");
		}
	}

}
