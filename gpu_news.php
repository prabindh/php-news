<?php

$MAX_TRAFFIC_LIMIT=1500 * 1024; //bytes
$MAX_TIME_LIMIT=4000; //seconds
$MAX_NEWS_ENTRIES=600; //entries
$dtime1 = DateTime::createFromFormat("d-m-Y", "10-09-2014");
$CUTOFF_NEWS_DATE=$dtime1->format("U");

$keywords = "Oculus|Carmack|Vulkan|DirectX|Dx12|UWP|OpenACC|WebGL|GPU|(3D\sGraphics)|Exynos|Mali|LLVM|Mesa|Gallium3d|OpenCL|Visualization|HPC|(Machine\sLearning)|(Neural\sNetworks)|(Deep\sLearning)|WebVR|CUDA|Mantle|EGL|Qt5|Emscripten|Webkit|Chromium|HTML5|GeForce|RenderScript|Hololens|Tessellation|(Ray\sTracing)|(Gear\sVR)|(Terrain\sEditor)|(Game\sEngine)|Unity3D|Unity|(Physically\sBased\sRendering)|(Artificial\sIntelligence)|Docker|ADAS|Virtualization|(Android\sAuto)|AMOLED|(Collision\sDetection)|3DMark|CryEngine";

$news_array_url = array();
$news_array_linktext = array();
// URL to search
$urls = array("http://www.gamesindustry.biz/rss/gamesindustry_news_feed.rss","http://research.microsoft.com/apps/catalog/default.aspx?t=news", "http://www.tomshardware.com", "http://www.techradar.com","https://kishonti.net","http://vgl.serc.iisc.ernet.in/news.php","https://www.reddit.com/r/vulkan", "http://www.extremetech.com/tag/directx", "https://www.reddit.com/r/machinelearning", "http://www.theregister.co.uk", "http://thenextweb.com", "http://www.androidpolice.com", "http://www.androidauthority.com", "http://www.electronicsweekly.com", "http://www.prnewswire.com/news-releases", "http://www.infoworld.com", "http://www.theinquirer.net", "https://plus.google.com/+YannLeCunPhD/posts", "http://www.anandtech.com/rss", "http://www.valvesoftware.com", "http://www.linux.com/news/software/linux-kernel", "https://www.automotivelinux.org/news/in-the-news", "https://www.hpcwire.com", "http://arstechnica.com/cars", "http://www.cnet.com/google-io", "http://www.futurity.org", "http://devblogs.nvidia.com/parallelforall", "http://www.siggraph.org/discover/news", "http://developer.amd.com/community/blog", "https://blog.xenproject.org" , "http://streamcomputing.eu/blog", "http://www.renderingpipeline.com", "http://community.arm.com/groups/arm-mali-graphics/blog", "http://mashable.com/tech", "https://research.nvidia.com/news", "https://software.intel.com/en-us/intel-rendering-research/publications", "https://forum.beyond3d.com/forums/architecture-and-products.38/index.rss", "https://forum.beyond3d.com/forums/graphics-and-semiconductor-industry.45/index.rss", "https://forum.beyond3d.com/forums/rendering-technology-and-apis.40/index.rss", "https://forum.beyond3d.com/forums/mobile-graphics-architectures-and-ip.32/index.rss", "https://news.ycombinator.com", "http://newsoffice.mit.edu/rss/topic/machine-learning", "http://www.geeks3d.com", "http://www.realtimerendering.com", "http://store.steampowered.com/news/?feed=steam_blog&headlines=1", "http://urho3d.github.io/latest-news.html", "http://venturebeat.com/tag/game-engine", "http://jmonkeyengine.org/feed", "http://www.roadtovr.com/?s=opengl", "http://www.computervisionblog.com", "http://fastml.com/atom.xml", "http://cs.stanford.edu/people/karpathy", "http://genivi.org/newsletter", "http://linuxgizmos.com/feed", "http://www.qnx.com/news/release.html", "http://genode.org/news/aggregator/rss", "https://www.linux.com/feeds/all-content", "http://www.gdconf.com", "http://feeds.feedburner.com/OLED-Display-News", "http://www.html5gamedevs.com/rss/forums/1-html5-game-devs-forum", "http://blogs.unity3d.com/feed", "http://webglfundamentals.org/atom.xml", "http://www.theverge.com/rss/frontpage", "http://www.techpowerup.com/rss/index.php", "http://www.fudzilla.com/news/graphics", "http://feeds.feedburner.com/gpgpuorg", "http://uploadvr.com/?s=vr+news", "http://www.shapeways.com/creator/tools?li=nav", "http://forum.processing.org/two/categories/glsl-shaders/feed.rss", "http://feeds.feedburner.com/MarxentTopAugmentedRealityAppsDeveloperForAndroidIos", "http://vrfocus.com/archives/category/news", "https://timdettmers.wordpress.com", "http://www.theplatform.net/feed");

// URL to search
$url1s = array("http://www.gamesindustry.biz/rss/games_industry_news_feed.rss", "http://www.geeks3d.com", "http://www.realtimerendering.com", "https://forums.oculus.com/feed.php", "http://urho3d.github.io/latest-news.html", "http://venturebeat.com/tag/game-engine", "http://jmonkeyengine.org/feed", "http://www.roadtovr.com/?s=opengl", "http://www.computervisionblog.com", "http://fastml.com/atom.xml", "http://cs.stanford.edu/people/karpathy", "http://genivi.org/newsletter", "http://linuxgizmos.com/feed", "http://www.qnx.com/news/release.html", "http://genode.org/news/aggregator/rss", "https://www.linux.com/feeds/all-content", "http://www.gdconf.com", "http://feeds.feedburner.com/OLED-Display-News", "http://www.html5gamedevs.com/rss/forums/1-html5-game-devs-forum", "http://blogs.unity3d.com/feed", "http://webglfundamentals.org/atom.xml", "http://www.theverge.com/rss/frontpage", "http://www.techpowerup.com/rss/index.php", "http://www.fudzilla.com/news/graphics", "http://feeds.feedburner.com/gpgpuorg", "http://uploadvr.com/?s=vr+news", "http://www.shapeways.com/creator/tools?li=nav", "http://forum.processing.org/two/categories/glsl-shaders/feed.rss", "http://feeds.feedburner.com/MarxentTopAugmentedRealityAppsDeveloperForAndroidIos", "http://vrfocus.com/archives/category/news");
//Bad urls
//"http://people.idsia.ch/~juergen/deeplearning.html", 

//$urls=array("www.pcmag.com/encyclopedia/term/37072/3d-graphics");//"http://community.arm.com/groups/arm-mali-graphics/blog");
//$urls=array("https://forum.beyond3d.com/forums/architecture-and-products.38/index.rss", "https://forum.beyond3d.com/forums/graphics-and-semiconductor-industry.45/index.rss", "https://forum.beyond3d.com/forums/rendering-technology-and-apis.40/index.rss", "https://forum.beyond3d.com/forums/mobile-graphics-architectures-and-ip.32/index.rss");

//$urls=array("http://newsoffice.mit.edu/rss/topic/machine-learning");

set_time_limit($MAX_TIME_LIMIT);
include("libs/PHPCrawler.class.php");


$curr_num_items = 0;
function handleRssInfo($xmllink)
{
	global $news_array_url; 
	global $news_array_linktext;
	global $keywords;
	global $totalnews;
	global $CUTOFF_NEWS_DATE;
	global $curr_num_items;

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
		  $curr_num_items ++;
		  echo "[Total news = ".$totalnews."]";
	  }
	  if($curr_num_items > 30) break;
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
	global $curr_num_items;

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
	if($curr_num_items > 30) break;    
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

	$curr_num_items = 0;
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
<meta charset='utf-8'/><meta name='title' content='Latest GPU News'/><title>The Graphics World</title>
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

  $myfile = fopen("output_".(new DateTime())->getTimestamp().".html", "w") or die("Unable to open HTML file!");
  fwrite($myfile, $page);
  fclose($myfile);

?>


