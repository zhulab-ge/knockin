<?php
/*
 * Analyze the NGS reads with PHP script
 * Loading the fastaq file and probe sequence file
 * php -d memory_limit=-1 Seqfind.php sample.fq probes.txt
 */
if (!(file_exists($argv[1])&&file_exists($argv[2]))) {
	echo 'file not exist';
	return FALSE;
}
echo "Wait a few minutes...\n";
$result=readsClassify($argv[1], $argv[2]); // ngs file and probe file
foreach ($result as $val){ foreach ($val as $v) echo implode(" ", $v)."\n"; }

function readsClassify($ngsfile,$probe_file){
	$content=explode("\n", file_get_contents($probe_file));
	$probes=array(); $primers=array();
	foreach ($content as $val) {
		if (strlen($val)>10&&$val[0]!='#'){
			$arr=explode(" ", trim($val));
			if (isset($arr[5])) {
				$arr[5]=(int)$arr[5];
				$arr[6]=0;
				$probes[$arr[0]][$arr[2]]=$arr;
			}
		}
	}
	$primers['seq']=array();
	foreach ($probes as $key=>$val){
		$primers[$key]=count($probes[$key]); //group num
		$primers['seq']=array_merge($primers['seq'],getTruncatedStr($probes[$key][0][1], 15,$key));
	}
	$fp=fopen($ngsfile, 'r');
	$fpout=fopen('example_result.txt', 'w');
	$sample=array();
	while ( ! feof ( $fp )) {
		$group='00000000000000000000X';
		fgets($fp);
		$line=trim(fgets($fp));
		fgets($fp);fgets($fp);
		$p=substr($line, 2,15);
		if (isset($primers['seq'][$p])){
			$key=$primers['seq'][$p];
			$probes[$key][0][6]++;
			$group[$probes[$key][0][5]]='1';
			for ($i=1;$i<$primers[$key];$i++){
				if (strpos($line, $probes[$key][$i][1])!=FALSE){
					$probes[$key][$i][6]++;
					$group[$probes[$key][$i][5]]='1';
				}
			}
		}
		if (!isset($sample[$group])) $sample[$group]=1;
		else $sample[$group]++;
		if ($sample[$group]<11) fwrite($fpout, $line."\t".$group."\n");
	}
	fclose($fpout);
	fclose($fp);
	return $probes;
}
// get the truncated string and write to array as keys
function getTruncatedStr($str,$len,$val){
	$arr=array();
	for ($i=$len;$i<=strlen($str);$i++) $arr[substr($str, $i-$len,$len)]=$val;
	return $arr;
}


?>

