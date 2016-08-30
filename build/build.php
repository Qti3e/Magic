<?php
/*****************************************************************************
 *         In the name of God the Most Beneficent the Most Merciful          *
 *___________________________________________________________________________*
 *   This program is free software: you can redistribute it and/or modify    *
 *   it under the terms of the GNU General Public License as published by    *
 *   the Free Software Foundation, either version 3 of the License, or       *
 *   (at your option) any later version.                                     *
 *___________________________________________________________________________*
 *   This program is distributed in the hope that it will be useful,         *
 *   but WITHOUT ANY WARRANTY; without even the implied warranty of          *
 *   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the           *
 *   GNU General Public License for more details.                            *
 *___________________________________________________________________________*
 *   You should have received a copy of the GNU General Public License       *
 *   along with this program.  If not, see <http://www.gnu.org/licenses/>.   *
 *___________________________________________________________________________*
 *                             Created by  Qti3e                             *
 *        <http://Qti3e.Github.io>    LO-VE    <Qti3eQti3e@Gmail.com>        *
 *****************************************************************************/

$output = [
	'output'        => '',
	'lineCounts'    => 0
];
function _min($content){
	$count  = count($content);
	$out    = [];
	for($i  = 0;$i < $count;$i++){
		$line   = trim($content[$i]);
		if(substr($line,0,2) !== '//'){
			$out[]  = $line;
		}
	}
	return str_replace(["\t",'  '],' ',preg_replace('/\/\*\*.+?\*\//','',str_replace(['<?php','?>'],'',implode(' ',$out))));
}
function build($dir,&$output){
	try{
		$d  = dir($dir);
		while (false !== ($entry = $d->read())) {
			if(!in_array($entry,['.','..'])) {
				$e = $dir . '/' . $entry;
				if (is_file($e) && in_array(substr($e, -3), ['php', 'inc'])) {
					$output['lineCounts']   += count($file = file($e));
					$output['output']       .= _min($file);
				} elseif (is_dir($e)) {
					build($e, $output);
				}
			}
		}
		$d->close();
	}catch (Exception $e){
		echo "Error: ".$e->getMessage()."\r\n";
	}
}

build('../src',$output);
do{
	$output['output']   = str_replace(["{ "," {"],'{',$output['output'],$a);
	$output['output']   = str_replace([" }","} "],'}',$output['output'],$b);
	$output['output']   = str_replace(["; "," ;"],';',$output['output'],$c);
	$output['output']   = str_replace(["= "," ="],'=',$output['output'],$d);
	$z  = $a+$b+$c+$d;
}while($z !== 0);
$output['output']   = trim($output['output']);
file_put_contents('magic.min.php',file_get_contents('head.inc').$output['output']);