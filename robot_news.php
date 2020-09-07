<?php
/**
  Controller to get information about specific topics from sources
  Author: Prabindh Sundareson
  Email: prabindh@yahoo.com
  
  TODO: Auto discovery and addition of new sources, 
        dynamic ordering of topics based on sentiments
*/


$MAX_TRAFFIC_LIMIT=2000 * 1024; //bytes
$MAX_TIME_LIMIT=4000; //seconds
$MAX_NEWS_ENTRIES=600; //entries
$dtime1 = DateTime::createFromFormat("d-m-Y", "10-09-2014");
$CUTOFF_NEWS_DATE=$dtime1->format("U");

$keywords = "DARPA|EV3|Robotics|(Object\sRecognition)|SLAM|Evolution|Turing|iCub|Nao|Microbot|(Deep\sLearning)|Humanoid|Neuroscience|Openworm|EV3Dev|Robot|Drone|Sensors|(Computer\sVision)|(Object\sRecognition)|(Motor\sController)";

$news_array_url = array();
$news_array_linktext = array();
// URL to search
$urls = array("http://www.livescience.com/home/feed/site.xml", "http://newsoffice.mit.edu/rss/topic/robotics", "http://feeds.sciencedaily.com/sciencedaily/matter_energy/robotics", "http://www.roboticstrends.com/site/rss", "http://feeds.feedblitz.com/Gizmag", "http://www.openworm.org/", "http://firstlegoleague.org/", "https://education.lego.com/en-us/lesi/middle-school/mindstorms-education-ev3/all-about-ev3/hardware", "http://www.ev3dev.org/news/atom.xml", "http://www.reddit.com/r/robotics", "https://en.wikipedia.org/wiki/Deep_learning", "http://www.ros.org/news", "http://www.computervisionblog.com", "http://fastml.com/atom.xml", "http://cs.stanford.edu/people/karpathy", "http://robohub.org/feed", "http://blog.rock-robotics.org/rss", "http://robotsquare.com/feed", "http://www.robotc.net/blog/tag/ev3", "http://www.legoengineering.com/category/news", "https://www.pololu.com/blog.atom", "http://robotglobe.org", "http://www.technobuffalo.com");

set_time_limit($MAX_TIME_LIMIT);
include("libs/PHPCrawler.class.php");

function handleRssInfo($xmllink)
{
	global $news_array_url; 
	global $news_array_linktext;
	global $keywords;
	global $totalnews;
	global $CUTOFF_NEWS_DATE;
	$xmlDoc = new DOMDocument();
	$err = $xmlDoc->load($xmllink);

	if($err === FALSE) return;
	
	//get and output "<item>" elements
	$x=$xmlDoc->getElementsByTagName('item');
	for ($i=0; $i<$x->length; $i++) 
	{
	  $item_title=$x->item($i)->getElementsByTagName('title')->item(0)->childNodes->item(0)->nodeValue;
	  $item_link=$x->item($i)->getElementsByTagName('link')->item(0)->childNodes->item(0)->nodeValue;

  	  if (preg_match('/\b('.$keywords.')\b/i',$item_title))
	  {
		  array_push($news_array_url,$item_link);
		  array_push($news_array_linktext, $item_title);
		  $totalnews ++;
		  echo "[Total news = ".$totalnews."]";
	  }
	}
}

class MyCrawler extends PHPCrawler 
{
  function handleDocumentInfo($DocInfo) 
  {
	global $news_array_url; 
	global $news_array_linktext;
	global $keywords;
	global $totalnews;
	global $CUTOFF_NEWS_DATE;

	for ($desc=0; $desc<count($DocInfo->links_found_url_descriptors);$desc++)
	{

		//$pattern = '/(' . implode('|', $keywords) . ')/'; // $pattern = /(one|two|three)/
	//	if (stripos($DocInfo->links_found_url_descriptors[$desc]->linktext,'opengl') !== false) {
		if(
			 (0 === preg_match('/(jpg|png|jpeg|div|span|img|gif|#|@)/i',$DocInfo->links_found_url_descriptors[$desc]->linktext)) &&
			(count(array_filter(explode(" ",$DocInfo->links_found_url_descriptors[$desc]->linktext))) >= 3)
		)
		{

			if (
				(0 === preg_match('/\b('.$keywords.')\b/i',$DocInfo->links_found_url_descriptors[$desc]->linktext))
			) continue;

//echo "[".$desc."] = ".$DocInfo->links_found_url_descriptors[$desc]->linktext;			

			 //get 'Last-Modified' header from the Document Info
			$last_modified = strtotime(PHPCrawlerUtils::getHeaderValue($DocInfo->header, 'last-modified'));
			//if 'Last-Modified' header not found then get 'Date' header
			if (!$last_modified)
				$last_modified = strtotime(PHPCrawlerUtils::getHeaderValue($DocInfo->header, 'date'));

			if (
				($last_modified > $CUTOFF_NEWS_DATE)
			)
			{
				if(!in_array($DocInfo->links_found_url_descriptors[$desc]->linktext, $news_array_linktext))
				{
					array_push($news_array_url,$DocInfo->links_found_url_descriptors[$desc]->url_rebuild);
					array_push($news_array_linktext, $DocInfo->links_found_url_descriptors[$desc]->linktext);
					$totalnews ++;
				}
			}
		}
	}    
	flush();
  } 
}

function set_crawler_props($my)
{
	global $MAX_TRAFFIC_LIMIT;
	// Only receive content of files with content-type "text/html"
	$my->addContentTypeReceiveRule("#text/html#");
	$my->addContentTypeReceiveRule("?feed?");

	// Ignore links to pictures, dont even request pictures, 
	$my->addURLFilterRule("#\.(js|jpg|jpeg|gif|png)$# i");

	// Store and send cookie-data like a browser does
	$my->enableCookieHandling(true);

	// Set the traffic-limit
	$my->setTrafficLimit($MAX_TRAFFIC_LIMIT);

	$my->setUserAgentString("Mozilla/5.0 (X11; Linux i686; rv:10.0.1) Gecko/20100101 Firefox/10.0.1 SeaMonkey/2.7.1");
}


$totalnews = 0;
	$crawler = new MyCrawler();
	set_crawler_props($crawler);

for($urlcount=0;$urlcount < count($urls) && $totalnews < $MAX_NEWS_ENTRIES;$urlcount++)
{
	if(
	  (stripos($urls[$urlcount], "rss") === FALSE) &&
	  (stripos($urls[$urlcount], "xml") === FALSE) &&
	  (stripos($urls[$urlcount], "feed") === FALSE) &&
	  (stripos($urls[$urlcount], "atom") === FALSE)
	)
		$crawler->setURL($urls[$urlcount]);
	else
		handleRssInfo($urls[$urlcount]);


	echo "[".$urlcount."] Processing ".$urls[$urlcount];
	// Thats enough, now here we go
	$crawler->go();
}

	if(0) {
	$crawler = new MyCrawler();
	set_crawler_props($crawler);
	for($urlcount=0;$urlcount < count($url1s) && $totalnews < $MAX_NEWS_ENTRIES;$urlcount++)
	{
		if(
		  (stripos($url1s[$urlcount], "rss") === FALSE) &&
		  (stripos($url1s[$urlcount], "xml") === FALSE) &&
		  (stripos($url1s[$urlcount], "feed") === FALSE) &&
		  (stripos($url1s[$urlcount], "atom") === FALSE)
		)
			$crawler->setURL($url1s[$urlcount]);
		else
			handleRssInfo($url1s[$urlcount]);


		echo "[".$urlcount."] Processing ".$url1s[$urlcount];
		// Thats enough, now here we go
		$crawler->go();
	}
	} //if 0

  //form the html page
  $page = "<!DOCTYPE HTML><html lang='en'><head>
<meta charset='utf-8'/><meta name='title' content='Latest Robotics News'/><title>The Robotics World</title>
		<style type='text/css'>
			body{
			    font-family:verdana;
			    line-height:1.1;
			}
			tr:nth-child(even) {
			    font-family:verdana;
				background-color: #FFFFFF;
			}
			tr:nth-child(odd) {
			    font-family:verdana;
				background-color: #F0F8FF;
			}
			table {			    font-size:0.9em;}
		</style> </head><body>";



	//Arrange in keyvalue pairs
	$tagged_array_url = array();
	$tagged_array_linktext = array();
	$keyword_array = explode("|",$keywords);
	$optspace = "";
	for($keywcount=0; $keywcount < count($keyword_array); $keywcount ++)
	{
		$tagged_array_linktext[$keyword_array[$keywcount]] = array();
		$tagged_array_url[$keyword_array[$keywcount]] = array();
		$keyw = $keyword_array[$keywcount];
		for($newscount=0;$newscount < count($news_array_linktext);$newscount++)
		{
			//if(stripos($news_array_linktext[$newscount], $keyw) !== FALSE)
			if(preg_match('/\b('.$keyw.')\b/i', $news_array_linktext[$newscount]))
			{
				array_push($tagged_array_linktext[$keyw], $news_array_linktext[$newscount]);
				array_push($tagged_array_url[$keyw], $news_array_url[$newscount]);
			}
		}
		if(count($tagged_array_linktext[$keyw])) 
		{
			$page = $page.str_replace(array("(",")","\s"), array("", "", " "), $keyw)."&nbsp;news&nbsp;[".count($tagged_array_linktext[$keyw])." items]<br>";
			$page = $page."<table>";
			if($optspace == "") $optspace = "&nbsp;&nbsp;"; else $optspace="";
			for($tagcount=0;$tagcount < count($tagged_array_linktext[$keyw]);$tagcount ++)
			{
				$page = $page."<tr><td>".$optspace.'<a href="'.$tagged_array_url[$keyw][$tagcount].'" target="_blank">'.$tagged_array_linktext[$keyw][$tagcount]."</a></td></tr>";
			}
			$page = $page."</table><br>";			
		}
	}
  $page = $page."</body></html>";

  $myfile = fopen("robot_news_".(new DateTime())->getTimestamp().".html", "w") or die("Unable to open HTML file!");
  fwrite($myfile, $page);
  fclose($myfile);

?>
