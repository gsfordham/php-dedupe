<?php //filer.php
	
	//Dedupe\Filer namespace
	namespace Dedupe\Filer {
		function scan(string $cd) : array {
			$files = scandir($cd);
			$filehashes = array();
			$count = 0;
			$dupes_raw = 0;
			$dupes_fix = 0;
			
			$dupes_arr = array();
			
			$out = array("err" => 0, "msg" => "");
			
			$times = array("start" => time());
			$dupes_dir = ($cd . "dupes-" . $times["start"] . "/");
			
			if(!mkdir($dupes_dir)){
				$out["err"] = 1;
				$out["msg"] = "Failed to generate dupes directory\n";
				return $out;
			}
			
			
			//Hash all the files and sort them into an array
			foreach($files as $file){
				$count++;
				if($count % 50 == 0){print("Handled {$count} files...\n");}
				$hash = hash_file("sha512", $file);
				if(filetype($file) == "file"){
					if(!isset($filehashes[$hash])){
						$filehashes[$hash] = array($file);
					}else{
						array_push($filehashes[$hash], $file);
					}
				}
			}
			
			$times["after_hash"] = time();
			
			//Check each array item for dupes
			foreach($filehashes as $key => $isdupe){
				$ct = count($isdupe);
				if($ct > 1){
					$dupes_raw += $ct;
					$dupes_fix += ($ct - 1);
					$dupes_arr[$key] = $isdupe;
				}
			}
			
			//Move the duplicates
			$out = move_dupes($cd, $dupes_dir, $dupes_arr);
			if($out["err"] != 0){
				return $out;
			}
			
			$times["after_finish"] = time();
			
			$out["msg"] = sprintf("There are %d duplicates (%d copies) of files in this folder, which contains %d non-directory files.\n", $dupes_raw, $dupes_fix, count($filehashes));
			return $out;
		}
		
		function move_dupes(string $in_dir, string $out_dir, array $arr) : array {
			$out = array("err" => 0, "msg" => "");
			$count = 1;
			
			foreach($arr as $key => $val){
				$count = 1;
				foreach($val as $v){
					//Skip the index file
					if($count == 1){
						$count++;
						continue;
					}
					$ext = pathinfo("{$in_dir}{$v}", PATHINFO_EXTENSION);
					if(!rename("{$in_dir}{$v}", "{$out_dir}{$key}-{$count}.{$ext}")){
						$out["err"] = 1;
						$out["msg"] = ("Failed to move one or more files");
						return $out;
					}
					$count++;
				}
			}
			return $out;
		}
	}
?>
