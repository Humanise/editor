<?
/**
 * @package OnlinePublisher
 * @subpackage Classes
 */

class DBUCalendarParser {
	
	
	function parseURL($url) {
		$string = @file_get_contents($url);
		if (!$string) {
			return false;
		}
		$cal = new DBUCalendar();
		
		//$reg = '/<tr><td[^>]*>[0-9]+<\/td><td[^>]*>[^<]{3}<\/td><td>([0-9]{4})-([0-9]{2})-([0-9]{2})<\/td><td>([0-9]{2}):([0-9]{2})<\/td><td>([^<]+)<\/td><td>([^<]+)<\/td><td>([^<]+)<\/td>/i';
		$reg = '/<tr><td[^>]*>[0-9]+<\/td><td[^>]*>[^<]*<\/td><td[^>]*>[^<]*<\/td><td[^>]*>([0-9]{4})-([0-9]{2})-([0-9]{2})<\/td><td[^>]*>([0-9]{2}):([0-9]{2})<\/td><td[^>]*>([^<]+)<\/td><td[^>]*>([^<]*)<\/td><td[^>]*>([^<]*)<\/td><td[^>]*>([^<]*)<\/td>/i';
		//$reg = '/<tr><td[^>]*>[0-9]+<\/td><td[^>]*>[^<]*<\/td><td[^>]*>[^<]*<\/td><td[^>]*>([0-9]{4})-([0-9]{2})-([0-9]{2})<\/td><td>([0-9]{2}):([0-9]{2})<\/td><td>([^<]+)<\/td><td>([^<]+)<\/td><td>([^<]+)<\/td>/i';
		preg_match_all($reg,$string, $matches);
		
		error_log(print_r($matches,true));
		for ($i=0; $i < count($matches[0]); $i++) {
			$event = new DBUCalendarEvent();

			$year = intval($matches[1][$i]);
			$month = intval($matches[2][$i]);
			$day = intval($matches[3][$i]);
			$hour = intval($matches[4][$i]);
			$minute = intval($matches[5][$i]);
			
			$homeTeam = $matches[6][$i];
			$guestTeam = $matches[7][$i];
			$location = $matches[8][$i];
			$score = $matches[9][$i];

			$startDate = mktime ( $hour, $minute, $second, $month, $day, $year);
			$event->setStartDate($startDate);

			$endDate = mktime ( $hour+1, $minute+45, $second, $month, $day, $year);
			$event->setEndDate($endDate);
			
			$event->setHomeTeam($homeTeam);
			$event->setGuestTeam($guestTeam);
			$event->setLocation($location);
			$event->setScore($score);
			$cal->addEvent($event);
		}
		//header('Content-Type: text/plain');
		//print_r($cal);
		return $cal;
	}
}

class DBUCalendar {
	var $events = array();
	
	function addEvent($event) {
	    $this->events[] = $event;
	}

	function getEvents() {
	    return $this->events;
	}
	
}

class DBUCalendarEvent {
	
	var $location;
	var $homeTeam;
	var $guestTeam;
	var $startDate;
	var $endDate;
	var $score;

	function setLocation($location) {
	    $this->location = $location;
	}

	function getLocation() {
	    return $this->location;
	}
	
	function setHomeTeam($homeTeam) {
	    $this->homeTeam = $homeTeam;
	}

	function getHomeTeam() {
	    return $this->homeTeam;
	}
	
	function setGuestTeam($guestTeam) {
	    $this->guestTeam = $guestTeam;
	}

	function getGuestTeam() {
	    return $this->guestTeam;
	}
	
	function setStartDate($startDate) {
	    $this->startDate = $startDate;
	}

	function getStartDate() {
	    return $this->startDate;
	}
	
	function setEndDate($endDate) {
	    $this->endDate = $endDate;
	}

	function getEndDate() {
	    return $this->endDate;
	}
	
	function setScore($score) {
	    $this->score = $score;
	}

	function getScore() {
	    return $this->score;
	}
	
}