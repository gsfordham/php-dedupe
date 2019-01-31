<?php //main.php
	//File deduper
	
	//Requires
	require_once("filer.php");
	
	//Main function
	function main(string $cd = ""){
		$stdin = fopen("php://stdin", "r");
		$invalid_uin = "Invalid input. After debugging, this will restart loop.\n";
		$out = array("err" => 0, "msg" => "");
		
		if(empty($cd)){
			 $cd = getcwd() . "/";
		}
		
		//Prompt
		print("Do you wish to dedupe the current directory: \"{$cd}\"? [y/n]");
		
		//Get input
		switch($in = strtolower(trim(fgets($stdin)))){
			case empty($in) == TRUE:
			case $in == "":
			case $in == "\n":
				print($invalid_uin);
				return 1; //Error
				break;
			case $in == "yes":
			case $in == "y":
				print("Preparing to dedupe...\n");
				$out = Dedupe\Filer\scan($cd);
				if($out["err"] != 0){
					print("ERROR DEDUPING: " . $out["msg"] . "\n");
					return 1;
				}
				break;
			case $in == "no":
			case $in == "n":
				print("Ok, canceled. Exiting.\n");
				return 0;
				break;
			default:
				print($invalid_uin);
				return 1; //Error
				break;
		}
		printf("Finished checking for duplicates!\n\t%s\n", $out["msg"]);
		return 0;
	}
	
	main(); //Run main loop
?> 