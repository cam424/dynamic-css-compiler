<?php
	if (extension_loaded('zlib')) {
		ob_start('ob_gzhandler');
	}
	header ('content-type: text/css; charset: UTF-8');
	header ('cache-control: must-revalidate');
	
	//$seconds_to_cache = time() + $seconds_to_cache;
	$seconds_to_cache = 0;
	$ts = gmdate("D, d M Y H:i:s", $seconds_to_cache ) . " GMT";
	header("Expires: $ts");
	header("Pragma: cache");
	header("Cache-Control: max-age=$seconds_to_cache");


	//ob_start('compress');

	function css_cleanup($css) {
		$replace = array(
			//"#/\*.*?\*/#s" => "",  // Strip C style comments.
			"#\s\s+#"      => " ", // Strip excess whitespace.
		);
		$search = array_keys($replace);
		$css = preg_replace($search, $replace, $css);
		
		$replace = array(
			": "	=>	":",
			"; "	=>	";",
			" {"	=>	"{",
			" }"	=>	"}",
			", "	=>	",",
			"{ "	=>	"{",
			",\n"	=>	",", // Don't wrap multiple selectors.
			"\n}"	=>	"}", // Don't wrap closing braces.
			"} "	=>	"}\n", // Put each rule on it's own line.
			"*/" 	 =>	"*/\n",
			"*/\n\n"	=>	"*/\n",
			"*/\n\n/*"	=>	"*/\n/*"
		);
		$search = array_keys($replace);
		$css = str_replace($search, $replace, $css);
		return trim($css);
	}

	$path = dirname(__FILE__);

	$directory = new \RecursiveDirectoryIterator($path);
	$iterator = new \RecursiveIteratorIterator($directory);
	$paths_arr = new RegexIterator($iterator, '/(.*).css/i');
	foreach($paths_arr as $tt):
		$filepath = $tt->getPathname();
		if(preg_match('/\.css/i',$filepath)):
			$files_to_cache[] = $filepath;
		endif;
	endforeach;

	// THESE ARE ALL MY PAGE SPECIFIC STYLES
	if(isset($_GET['files']) && !empty($_GET['files'])):
		$fileCss = strtolower($_GET['files']);
		$fileItems = explode(',', $fileCss);
		$alreadyadded = array();
		foreach($fileItems as $fileItem):
			//shared
			if(preg_match('/ctgy|prod/i' , $fileItem)):
				if(!in_array( 'layout/page-prodctgy.css', $files_to_cache)):
					$files_to_cache[] = 'layout/page-prodctgy.css';
				endif;
			endif;
			//layout/
			$file = "layout/{$fileItem}.css";
			if(file_exists($file)):
				$files_to_cache[] = $file;
				$alreadyadded[] = $file;
				if(preg_match('/ctgy/i' , $fileItem )):
					$customassets = array('custom/facets.css');
					foreach($customassets as $customasset):
						if(file_exists($customasset)):
							$files_to_cache[] = $customasset;
						endif;
					endforeach;
				endif;
			endif;
			//custom/
			$file2 = "custom/{$fileItem}.css";
			if(file_exists($file2)):
				$files_to_cache[] = $file2;
				$alreadyadded[] = $file;
			endif;
			
		endforeach;	
	endif;
	foreach ($files_to_cache as $css_file):
		// Loop the css array and concatenate them
		$css_content = file_get_contents($css_file);
		// Run the cleanup function
		//echo "\n/*--> FILE : {$css_file} <--*/\n";
		echo css_cleanup($css_content);
	endforeach;
	
	if (extension_loaded('zlib')):
		ob_end_flush();
	endif;
?>
