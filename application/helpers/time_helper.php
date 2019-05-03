<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

define('TIMEZONE', 'Asia/Singapore');

if ( ! function_exists('datenow'))
{
	function datenow($format='Y-m-d H:i:s')
	{
		$current_datetime = new DateTime(null, new DateTimeZone(TIMEZONE));
		return $current_datetime->format($format);
	}
}

if ( ! function_exists('dateformat'))
{
	function dateformat($date, $format='Y-m-d H:i:s')
	{
		if($date == '0000-00-00 00:00:00' || $date == '0000-00-00' || (stristr($date, '0000-00-00') && $format=='Y-m-d')
			 || (stristr($date, '00:00:00') && $format=='H:i'))
		{
			return false;
		}

		$date = new DateTime($date, new DateTimeZone(TIMEZONE));
		return $date->format($format);
	}
}

if ( ! function_exists('timestampformat'))
{
	function timestampformat($timestamp, $format='Y-m-d H:i:s', $modify='')
	{
		$date = new DateTime(null, new DateTimeZone(TIMEZONE));
		#divide by 1000 if milliseconds is included in timestamp
		$timestamp = $timestamp/1000;
		$date->setTimestamp($timestamp);
		
		if(!empty($modify))
		{
			$date->modify($modify);
		}

		if($format=='timestamp')
		{
			return timestampnow($date->format('Y-m-d H:i:s'));
		}
		
		return $date->format($format);
	}
}

if ( ! function_exists('timestampnow'))
{
	function timestampnow($date_value=null)
	{
		$date = new DateTime($date_value, new DateTimeZone(TIMEZONE));
		$current = $date->getTimestamp();
		#multiply by 1000 to produce timestamp with milliseconds
		return $current * 1000;
	}
}

if (! function_exists('createDateRange'))
{
	/**
	 * Returns every date between two dates as an array
	 * @param string $startDate the start of the date range
	 * @param string $endDate the end of the date range
	 * @param string $format DateTime format, default is Y-m-d
	 * @return array returns every date between $startDate and $endDate, formatted as "Y-m-d"
	 */
	function createDateRange($startDate, $endDate, $format = "Y-m-d")
	{
	    $begin = new DateTime($startDate);
	    $end = new DateTime($endDate);

	    $interval = new DateInterval('P1D'); // 1 Day
	    $dateRange = new DatePeriod($begin, $interval, $end);

	    $range = [];
	    foreach ($dateRange as $date) {
	        $range[] = $date->format($format);
	    }

	    return $range;
	}
}
// ------------------------------------------------------------------------

/* End of file time_helper.php */
/* Location: ./senta/application/helpers/time_helper.php */