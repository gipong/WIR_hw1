<?php
	//require('config.php');
	$link = mysql_connect('127.0.0.1','root','') or die('mysqli_connent:error');
	mysql_query("SET NAMES 'utf8'",$link);
	mysql_select_db('se2');
	
	//include_once('simple_html_dom.php');
	//$source = 'url';
	$stopWords = array('a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z', 'about', 'above', 'above', 'across', 'after', 'afterwards', 'again', 'against', 'all', 'almost', 'alone', 'along', 'already', 'also','although','always','am','among', 'amongst', 'amoungst', 'amount', 'an', 'and', 'another', 'any','anyhow','anyone','anything','anyway', 'anywhere', 'are', 'around', 'as', 'at', 'back','be','became', 'because','become','becomes', 'becoming', 'been', 'before', 'beforehand', 'behind', 'being', 'below', 'beside', 'besides', 'between', 'beyond', 'bill', 'both', 'bottom','but', 'by', 'call', 'can', 'cannot', 'cant', 'co', 'con', 'could', 'couldnt', 'cry', 'de', 'describe', 'detail', 'do', 'done', 'down', 'due', 'during', 'each', 'eg', 'eight', 'either', 'eleven','else', 'elsewhere', 'empty', 'enough', 'etc', 'even', 'ever', 'every', 'everyone', 'everything', 'everywhere', 'except', 'few', 'fifteen', 'fify', 'fill', 'find', 'fire', 'first', 'five', 'for', 'former', 'formerly', 'forty', 'found', 'four', 'from', 'front', 'full', 'further', 'get', 'give', 'go', 'had', 'has', 'hasnt', 'have', 'he', 'hence', 'her', 'here', 'hereafter', 'hereby', 'herein', 'hereupon', 'hers', 'herself', 'him', 'himself', 'his', 'how', 'however', 'hundred', 'ie', 'if', 'in', 'inc', 'indeed', 'interest', 'into', 'is', 'it', 'its', 'itself', 'keep', 'last', 'latter', 'latterly', 'least', 'less', 'ltd', 'made', 'many', 'may', 'me', 'meanwhile', 'might', 'mill', 'mine', 'more', 'moreover', 'most', 'mostly', 'move', 'much', 'must', 'my', 'myself', 'name', 'namely', 'neither', 'never', 'nevertheless', 'next', 'nine', 'no', 'nobody', 'none', 'noone', 'nor', 'not', 'nothing', 'now', 'nowhere', 'of', 'off', 'often', 'on', 'once', 'one', 'only', 'onto', 'or', 'other', 'others', 'otherwise', 'our', 'ours', 'ourselves', 'out', 'over', 'own','part', 'per', 'perhaps', 'please', 'put', 'rather', 're', 'same', 'see', 'seem', 'seemed', 'seeming', 'seems', 'serious', 'several', 'she', 'should', 'show', 'side', 'since', 'sincere', 'six', 'sixty', 'so', 'some', 'somehow', 'someone', 'something', 'sometime', 'sometimes', 'somewhere', 'still', 'such', 'system', 'take', 'ten', 'than', 'that', 'the', 'their', 'them', 'themselves', 'then', 'thence', 'there', 'thereafter', 'thereby', 'therefore', 'therein', 'thereupon', 'these', 'they', 'thickv', 'thin', 'third', 'this', 'those', 'though', 'three', 'through', 'throughout', 'thru', 'thus', 'to', 'together', 'too', 'top', 'toward', 'towards', 'twelve', 'twenty', 'two', 'un', 'under', 'until', 'up', 'upon', 'us', 'very', 'via', 'was', 'we', 'well', 'were', 'what', 'whatever', 'when', 'whence', 'whenever', 'where', 'whereafter', 'whereas', 'whereby', 'wherein', 'whereupon', 'wherever', 'whether', 'which', 'while', 'whither', 'who', 'whoever', 'whole', 'whom', 'whose', 'why', 'will', 'with', 'within', 'without', 'would', 'yet', 'you', 'your', 'yours', 'yourself', 'yourselves', 'the');
	
	function getContens($data) {
		global $stopWords;
			
		$url = file_get_contents($data);

		$url = preg_replace('/<script[^>]*?>.*?<\/script>/si', ' ', $url); 
		$content = strtolower(strip_tags($url));
		//unset($url);
		$content = preg_replace('/[[:punct:]]/', '', $content);

		$tokens = preg_split('/\s+/', $content);
		//$tokens = array_unique($tokens);
		unset($content);
			
		$tokens = array_diff($tokens, $stopWords);
		unset($stopWords);
		return $tokens;
	
	}
	
	function fileIndex() {
		$dataset2012 = glob('./dataset2012/*.html');
		
		//$dictionary = array();
		$docCount = array();
		$terms = array();

		//foreach($dataset2012 as $dataID => $data) {
		while(list($dataID, $data) = each($dataset2012)) {

			$tokens = getContens($data);
			$docCount[$dataID] = count($tokens);

			
			foreach($tokens as $token) {
				/*
				if(!isset($dictionaryhttp://localhost/phpmyadmin/main.php?token=4b3b8092a4b4eca620349bd6aa922fb4[$token])) {
					$dictionary[$token] = array('df' => 0, 'postings' => array());
				}
				if(!isset($dictionary[$token]['postings'][$dataID])) {
					$dictionary[$token]['df']++;
					$dictionary[$token]['postings'][$dataID] = array('tf' => 0 );
				}
				*/
				$token = preg_replace('/[[:punct:]]/', '', $token);
				if(strlen($token)>2 and strlen($token)<16) {
					$tokenTable = "CREATE TABLE IF NOT EXISTS `se2`.`$token`
					(
						dataID int NOT NULL,
						t int NOT NULL,
						tf float NOT NULL,
						tfidf float NOT NULL,
						PRIMARY KEY (dataID)
					)";
					$tokenTable = mysql_query($tokenTable);
					
					if (isset($tokenTable)){				
					$insertToken = "INSERT INTO $token (dataID,t , tf, tfidf) VALUES($dataID, 0, 0, 0)";
					mysql_query($insertToken);
					mysql_query("UPDATE `se2`.`$token` SET t = t +1 WHERE dataID='$dataID'");
					mysql_query("UPDATE `se2`.`$token` SET tf = t/'$docCount[$dataID]' WHERE dataID='$dataID'");
					array_push($terms, $token);
					}
				
					//$dictionary[$token]['postings'][$dataID]['tf']++;
				}
			}
			
		}

		sort($terms);
		$terms = array_unique($terms);
		
		foreach($terms as $term) {
		$all = "SELECT * FROM `$term`";
		$check = mysql_num_rows(mysql_query($all));
		
		if(isset($check) && $check>0){
			$query = mysql_query($all);
			$df = mysql_fetch_row(mysql_query("SELECT COUNT(dataID) FROM `$term`"));
			while($list = mysql_fetch_array($query)) {
				$ans = $list[2]*log(100/$df[0], 10);
				@mysql_query("UPDATE `se2`.`$term` SET tfidf = $ans WHERE dataID=$list[0]");
			}
		}
		
		}
		echo 'update term frequency!<br />';
	//	return array('docCount' => $docCount, 'terms' => $terms);
		
	}
	
/*	
	function updateTfidf() {
		
		$index = fileIndex();
		$terms = $index['terms'];
		$docCount = $index['docCount'];
		
		while ($term = current($terms)) {
			if($query = mysql_query("SELECT COUNT(dataID) FROM `$term`")) {
				$docCount = mysql_fetch_now($query);
			}
			//$getTfidf = $index['dictionary'][$term];
			//foreach($getTfidf['postings'] as $dataID => $postings) {
			
		//	while(list($dataID, $postings) = each($getTfidf['postings'])) {
		//		echo $term.' in '.$dataID.' TFIDF is  '.($postings['tf'] * log($docCount / $getTfidf['df'], 2)).'<br />';
		//	}
			
			if($query = mysql_query("SELECT * FROM `$term`")) {
				$count = mysql_fetch_now($query);
			}
			next($terms);
		}
	
	}
*/	
	
	//updateTfidf();
	fileIndex();


?>

