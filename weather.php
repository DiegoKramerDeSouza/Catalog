<?php
    $BASE_URL = "http://query.yahooapis.com/v1/public/yql";

    $yql_query = 'select * from weather.forecast where woeid in (select woeid from geo.places(1) where text="brasilia") and u="c"';
    $yql_query_url = $BASE_URL . "?q=" . urlencode($yql_query) . "&format=json";
	
    // Make call with cURL
    $session = curl_init($yql_query_url);
	curl_setopt($session, CURLOPT_RETURNTRANSFER,true);
	$json = curl_exec($session);
	$retcode = curl_getinfo($session, CURLINFO_HTTP_CODE);
	if($retcode == 200){
		// Convert JSON to PHP object
		$phpObj =  json_decode($json);
		//echo '<pre>';print_r($phpObj).'<pre>';
		if(isset($phpObj->query->results->channel->item->condition->temp) && isset($phpObj->query->results->channel->item->condition->text)){
			$temperatura = $phpObj->query->results->channel->item->condition->temp;
			$clima = $phpObj->query->results->channel->item->condition->text;
		} else {
			$temperatura = '<i class="fa fa-spinner fa-pulse fa-lg fa-fw"></i>';
			$clima = "NOTFOUND";
		}
	} else {
		$temperatura = '<i class="fa fa-spinner fa-pulse fa-3x fa-fw"></i>
						<span class="sr-only">Loading...</span>';
		$clima = "NOTFOUND";
	}
    
	
?>