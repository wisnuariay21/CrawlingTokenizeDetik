	<?php  
	require_once __DIR__ .'/vendor/autoload.php';
	include_once('simple_html_dom.php');
	?>
	
	<!DOCTYPE html>
	<html>
	<head>
		<title>CRAWLING & TOKENIZE</title>
	</head>
	<body>
		<head>CRAWLING DETIK.COM</head>

		<form method="POST">
			<label>Cari :</label>
			<input type="text" name="textfind">
			<input type="submit" name="buttonfind">
		</form>

			 	<?php 
				if(isset($_POST['buttonfind']))
				{
					$stemmerFactory = new \Sastrawi\Stemmer\StemmerFactory();
					$stemmer = $stemmerFactory->createStemmer();

					$stopwordFactory = new \Sastrawi\StopWordRemover\StopWordRemoverFactory();
					$stopword = $stopwordFactory->createStopWordRemover();

					$find = $_POST['textfind'];
					$html = file_get_html("https://www.detik.com/search/searchall?query=" .$find. "&siteid=2");
					$i=0;
					foreach ($html->find('article') as $news) 
					{
						if($i > 10) break;
						else
						{
							echo "<table" ?> border=1 <?php ">";
						 	echo "<tr>";
						 		echo "<th>GAMBAR</th>";
						 		echo "<th>JUDUL</th>";
						 		echo "<th>TANGGAL</th>";
						 	echo "</tr>";

							$datenews = $news->find('span[class="date"]',0)->innertext;
							$titlenews = $news->find('h2[class="title"]', 0)->innertext;
							$linknews = $news->find('a', 0)->href;
							$imagenews = $news->find('img',0)->src;
							$outputStemming = $stemmer->stem($titlenews);
							$outputStopWord = $stopword->remove($outputStemming);
				?>			
							<tr>
					 			<td> <img src=<?php echo $imagenews ?> > </td>
					 			<td> <a href=<?php echo $linknews ?> > <?php echo $titlenews ?> </a> </td>
					 			<td> <?php echo $datenews ?></td>
					 		</tr>
				<?php  
						}
						$i++;						

						 echo "<table" ?> border=1 <?php ">";
						 	echo "<tr>";
						 		echo "<th>UNIGRAM</th>";
						 		echo "<th>BIGRAM</th>";
						 		echo "<th>TRIGRAM</th>";
						 	echo "</tr>";
						  echo "<h1>HASIL TOKENISASI : " .$titlenews. "</h1>";
						 	
						 	echo "<tr>";
						 	$result = explode(' ', $outputStopWord);	
						 	for ($i=0; $i < count($result); $i++) 
						 	{ 
						 		echo "<td>".$result[$i]."</td>";
						 		if(isset($result[$i+1]))
						 		{
						 			echo "<td>$result[$i] ".$result[$i+1]."</td>";
						 		}
						 		else{echo "<td></td>";}

						 		if(isset($result[$i+2]))
						 		{
						 			echo "<td>$result[$i] ".$result[$i+1]." " .$result[$i+2]. "</td>";
						 		}
						 		else{echo "<td></td>";}	
						 		echo "</tr>";
						 	}
			 				echo "</table>";
			 				echo "<br></br>";

					}
				}
				?>
			 </table>

			
			
	</body>
	</html>
	