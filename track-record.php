<?php
/**
 * Plugin Name: Track Record
 * Plugin URI: https://theactivist.news/track-record
 * Description: Displays performance of investments using AlphaVantage data.
 * Version: 1.0
 * Author: The Activist
 * Author URI: https://theactivist.news
 */

function stock( $atts ) {
	$a = shortcode_atts( array(
			'symbol' => 'AAPL',
			'date' => '2022-01-01',
		),	$atts );

//Declare variables

	$symbol = $a['symbol'];
	$date = $a['date'];
	$url = str_replace("XXXX", $symbol, "https://www.alphavantage.co/query?function=TIME_SERIES_DAILY_ADJUSTED&symbol=XXXX&outputsize=full&apikey=KSSEAXNHAHCG6SD5");
	$json = file_get_contents($url);
	$data = json_decode($json, true);
	$time_series = $data["Time Series (Daily)"];
	$start_date = date_format(date_sub(date_create($date),date_interval_create_from_date_string("0 days")),"Y-m-d");
	$start_date_less_one = date_format(date_sub(date_create($date),date_interval_create_from_date_string("1 days")),"Y-m-d");
	$start_date_less_two = date_format(date_sub(date_create($date),date_interval_create_from_date_string("2 days")),"Y-m-d");
	$start_date_less_three = date_format(date_sub(date_create($date),date_interval_create_from_date_string("3 days")),"Y-m-d");
	$start_date_less_four = date_format(date_sub(date_create($date),date_interval_create_from_date_string("4 days")),"Y-m-d");	
	$exists = array_key_exists($start_date,$time_series);
	$exists_less_one = array_key_exists($start_date_less_one,$time_series);
	$exists_less_two = array_key_exists($start_date_less_two,$time_series);
	$exists_less_three = array_key_exists($start_date_less_three,$time_series);
	$exists_less_four = array_key_exists($start_date_less_four,$time_series);
	$fmt = numfmt_create( 'en_US', NumberFormatter::CURRENCY );

//  Find start date in array

	if($exists) {
		$d = $start_date;
	} elseif($exists_less_one) {
		$d = $start_date_less_one;
	} elseif($exists_less_two) {
		$d = $start_date_less_two;
	} elseif($exists_less_three) {
		$d = $start_date_less_three;
	} elseif($exists_less_four) {
		$d = $start_date_less_four;
	} else(die("Date not found"));

//	Find adjusted closing price on start date

	$start_date_array = $time_series["$d"];
	$close = numfmt_format_currency($fmt, $start_date_array["5. adjusted close"], "USD");

// Filter time series for dates since publication date

	$keys = (array_keys($time_series));
	foreach ($keys as $value) {
		if (strtotime($value) >= strtotime($d)) {
			$filter[] = $value;
		}
	}
	
// Find lowest daily low price since publication date
	
	$flip = array_flip($filter);
	$intersect = array_intersect_key($time_series,$flip);
	foreach ($intersect as $arr) {
		$lows[] = $arr["3. low"];
	}
	$low = numfmt_format_currency($fmt, min($lows), "USD");
	
// Compute percentage change from price at publication to lowest low since publication

	$x = ((min($lows) - $start_date_array["5. adjusted close"])/$start_date_array["5. adjusted close"]);
	$change = sprintf("%.2f%%", $x * 100);

// Return HTML table row containing computed data
	
	return "
	<html>
		<table>
			<tbody>
				<tr>
					<td>$symbol</td>
					<td>$date</td>
					<td>$close</td>
					<td>$low</td>
					<td>$change</td>
				</tr>
			</tbody>
		</table>
	</html>
	";
		
	exit;

}

add_shortcode( 'trackrec', 'stock' );

?>