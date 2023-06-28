<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes
 */
if (!isset($GLOBALS['basePath'])) {
  header('HTTP/1.1 403 Forbidden');
  exit;
}

Entity::$schema['Calendarsource'] = [
    'table' => 'calendarsource',
    'properties' => [
      'url' => ['type' => 'string'],
      'synchronized' => ['type' => 'datetime'],
      'syncInterval' => ['type' => 'int', 'column' => 'sync_interval'],
      'filter' => ['type' => 'string'],
      'displayTitle' => ['type' => 'string', 'column' => 'display_title']
    ]
];
class Calendarsource extends ModelObject {
  var $url;
  var $synchronized;
  var $syncInterval;
  var $filter;
  var $displayTitle;

  function __construct() {
    parent::__construct('calendarsource');
  }

  static function load($id) {
    return ModelObject::get($id,'calendarsource');
  }

  function setUrl($url) {
    $this->url = $url;
  }

  function getUrl() {
    return $this->url;
  }

  function setSynchronized($synchronized) {
    $this->synchronized = $synchronized;
  }

  function getSynchronized() {
    return $this->synchronized;
  }

  function setSyncInterval($syncInterval) {
    $this->syncInterval = $syncInterval;
  }

  function getSyncInterval() {
    return $this->syncInterval;
  }

  function setFilter($filter) {
    $this->filter = $filter;
  }

  function getFilter() {
    return $this->filter;
  }

  function setDisplayTitle($displayTitle) {
    $this->displayTitle = $displayTitle;
  }

  function getDisplayTitle() {
    return $this->displayTitle;
  }

  function getIcon() {
    return 'common/internet';
  }

  function removeMore() {
    $sql = "delete from calendarsource_event where calendarsource_id=@int(id)";
    Database::delete($sql, ['id' => $this->id]);
  }

  function isInSync() {
    return (time() - $this->synchronized < $this->syncInterval);
  }

  function synchronize($force = false) {
    global $basePath;
    if ($this->isInSync() && $force == false) {
      return;
    }
    Log::debug('Syncing: ' . $this->url);
    $this->synchronized = time();
    $sql = "update calendarsource set synchronized=@datetime(time) where object_id=@int(id)";
    Database::update($sql,['time' => time(), 'id' => $this->id]);
    if (strpos($this->url,'dbu.dk') !== false) {
      $this->synchronizeDBU();
    } else if (strpos($this->url,'kampe.dhf.dk') !== false) {
      $this->synchronizeDBU();
    } else {
      $this->synchronizeVCal();
    }
  }

  function getParsedFilter() {
    $parsed = [];
    if ($this->filter) {
      preg_match('/home=([\w\W]+)/i', $this->filter, $result);
      if ($result) {
        $parsed['home'] = $result[1];
      }
      preg_match('/away=([\w\W]+)/i', $this->filter, $result);
      if ($result) {
        $parsed['away'] = $result[1];
      }
      preg_match('/location=([\w\W]+)/i', $this->filter, $result);
      if ($result) {
        $parsed['location'] = $result[1];
      }
      preg_match('/location!=([\w\W]+)/i', $this->filter, $result);
      if ($result) {
        $parsed['!location'] = $result[1];
      }
    }
    return $parsed;
  }

  function synchronizeDBU() {
    global $basePath;
    $parser = new DBUCalendarParser();
    $cal = $parser->parseURL($this->url);
    if ($cal) {
      $homeMode = strpos($this->getTitle(),'hjemmekampe') !== false;
      $guestMode = strpos($this->getTitle(),'udekampe') !== false;
      $filter = $this->getParsedFilter();
      $events = $cal->getEvents();
      $sql = "delete from calendarsource_event where calendarsource_id=@int(id)";
      Database::delete($sql, ['id' => $this->id]);
      //return;
      foreach($events as $event) {
        if (($homeMode && strpos($event->getHomeTeam(),'Hals') === false) || ($guestMode && strpos($event->getGuestTeam(),'Hals') === false)) {
          continue;
        }
        if (isset($filter['home']) && strpos($event->getHomeTeam(),$filter['home']) === false) {
          continue;
        }
        if (isset($filter['away']) && strpos($event->getGuestTeam(),$filter['away']) === false) {
          continue;
        }
        if (isset($filter['location']) && strpos($event->getLocation(),$filter['location']) === false) {
          continue;
        }
        if (isset($filter['!location']) && strpos($event->getLocation(),$filter['!location']) !== false) {
          continue;
        }
        $summary = $event->getHomeTeam() . " - " . $event->getGuestTeam();
        if ($event->getScore()) {
          $summary .= ' (' . $event->getScore() . ')';
        }
        $query = [
          'table' => 'calendarsource_event',
          'values' => [
            'calendarsource_id' => ['int' => $this->id],
            'summary' => ['text' => $summary],
            'location' => ['text' => $event->getLocation()],
            'startdate' => ['datetime' => $event->getStartDate()],
            'enddate' => ['datetime' => $event->getEndDate()]
          ]
        ];
        Database::insert($query);
      }
    }
  }

  function synchronizeVCal() {
    $parser = new VCalParser();
    $cal = $parser->parseURL($this->url);
    if ($cal) {
      $events = $cal->getEvents();
      $sql = "delete from calendarsource_event where calendarsource_id = @int(id)";
      Database::delete($sql, ['id' => $this->id]);
      foreach($events as $event) {

        $recurring = false;
        $frequency = null;
        $until = null;
        $interval = null;
        $count = null;
        $bymonth = null;
        $bymonthday = null;
        $byday = null;
        $byyearday = null;
        $byweeknumber = null;
        $weekstart = null;
        if ($event->isRecurring()) {
          $rule = $event->getRecurrenceRules();
          $rule = $rule[0];
          $recurring = true;
          $frequency = $rule->getFrequency();
          $until = $rule->getUntil();
          $interval = $rule->getInterval();
          $count = $rule->getCount();
          $weekstart = $rule->getWeekStart();
          if ($rule->getByMonth() != null) {
            $bymonth = implode(',', $rule->getByMonth());
          }
          if ($rule->getByMonthDay() != null) {
            $bymonthday = implode(',', $rule->getByMonthDay());
          }
          if ($rule->getByDay() != null) {
            $byday = implode(',', $rule->getByDay());
          }
          if ($rule->getByYearDay() != null) {
            $byyearday = implode(',', $rule->getByYearDay());
          }
          if ($rule->getByWeekNumber() != null) {
            $byweeknumber = implode(',', $rule->getByWeekNumber());
          }
        }


        $query = [
          'table' => 'calendarsource_event',
          'values' => [
            'calendarsource_id' => ['int' => $this->id],
            'summary' => ['text' => $event->getSummary()],
            'description' => ['text' => $event->getDescription()],
            'location' => ['text' => $event->getLocation()],
            'startdate' => ['datetime' => $event->getStartDate()],
            'enddate' => ['datetime' => $event->getEndDate()],
            'duration' => ['int' => $event->getDuration()],
            'uniqueid' => ['text' => $event->getUniqueId()],
            'recurring' => ['boolean' => $recurring],
            'frequency' => ['text' => $frequency],
            'until' => ['datetime' => $until],
            'interval' => ['int' => $interval],
            'count' => ['int' => $count],
            'weekstart' => ['text' => $weekstart],
            'bymonth' => ['text' => $bymonth],
            'bymonthday' => ['text' => $bymonthday],
            'byday' => ['text' => $byday],
            'byyearday' => ['text' => $byyearday],
            'byweeknumber' => ['text' => $byweeknumber],
            'url' => ['text' => $event->getUrl()]
          ]
        ];
        Database::insert($query);
      }
    }
  }

  function getEvents($query = []) {
    $events = [];
    $sql = "select id,summary,description,url,recurring,uniqueid,location,unix_timestamp(startdate) as startdate,unix_timestamp(enddate) as enddate,`duration`" .
    " from calendarsource_event where calendarsource_id = @int(id) and recurring=0";
    if (isset($query['startDate']) && isset($query['endDate'])) {
      $sql .= " and not (startdate > @datetime(endDate) or endDate < @datetime(startDate))";
    }
    $sql .= " order by startdate desc";

    $result = Database::select($sql, [
      'id' => $this->id,
      'endDate' => isset($query['endDate']) ? $query['endDate'] : null,
      'startDate' => isset($query['startDate']) ? $query['startDate'] : null
    ]);

    while ($row = Database::next($result)) {
      $events[] = $this->_buildEvent($row);
    }
    Database::free($result);

    // Get recurring events
    $sql = "select id,summary,description,url,recurring,uniqueid,location,unix_timestamp(startdate) as startdate,unix_timestamp(enddate) as enddate,`duration`" .
    ",frequency,unix_timestamp(until) as until,`count`,`interval`,byday" .
    " from calendarsource_event where calendarsource_id = @int(id) and recurring=1 order by startdate desc";

    $result = Database::select($sql, ['id' => $this->id]);
    while ($row = Database::next($result)) {
      $this->_analyzeReccursion($row,$events,$query);
    }
    Database::free($result);

    if (@$query['sort'] == 'startDate') {
      usort($events,['Calendarsource', '_startDateComparator']);
    }

    return $events;
  }

  function _analyzeReccursion($row, &$events,$query) {
    // Skip if until < startdate
    if ($row['until'] > 0 && $row['until'] < @$query['startDate']) {
      return;
    }
    if ($row['frequency'] == 'DAILY' || $row['frequency'] == 'WEEKLY' || $row['frequency'] == 'MONTHLY' || $row['frequency'] == 'YEARLY') {
      //Log::debug($row);
      // Build maximum 1000 events
      $running = true;
      for ($i = 0; $i < 1000 && $running; $i++) {
        if ($row['interval'] == 0 || (($i) % $row['interval']) == 0) {
          $futureEvents = $this->_createFutureEvents($row,$row['frequency'],$i);
          foreach ($futureEvents as $futureEvent) {
            $event = $this->_buildEvent($futureEvent);
            if ($event['startDate'] > @$query['endDate']) {
              $running = false;
            } elseif ($row['count'] > 0 && $row['count'] <= $i) {
              $running = false;
            } elseif ($row['until'] > 0 && $row['until'] < $event['startDate']) {
              $running = false;
            } elseif ($event['startDate'] > @$query['startDate']) {
              $events[] = $event;
            }
          }
        }
      }
    }
  }

  function _createFutureEvents($row,$by,$count) {
    $events = [];
    if ($by == 'WEEKLY') {
      if ($row['byday']) {
        $dayNums = ['MO' => 0, 'TU' => 1, 'WE' => 2, 'TH' => 3, 'FR' => 4, 'SA' => 5, 'SU' => 6];
        $weekday = Dates::getWeekDay($row['startdate']);
        $byDays = @split(",",$row['byday']);
        foreach ($byDays as $day) {
          $new = $row;
          $extra = $dayNums[$day] - $weekday;
          //error_log('byday: '.$weekday.' > '.$day.'/'.$extra);
          $new['startdate'] = Dates::addDays($new['startdate'],$count * 7 + $extra);
          $new['enddate'] = Dates::addDays($new['enddate'],$count * 7 + $extra);
          $events[] = $new;
        }
      } else {
        $row['startdate'] = Dates::addDays($row['startdate'],$count * 7);
        $row['enddate'] = Dates::addDays($row['enddate'],$count * 7);
        $events[] = $row;
      }
    } elseif ($by == 'DAILY') {
      $row['startdate'] = Dates::addDays($row['startdate'],$count);
      $row['enddate'] = Dates::addDays($row['enddate'],$count);
      $events[] = $row;
    } elseif ($by == 'MONTHLY') {
      $row['startdate'] = Dates::addMonths($row['startdate'],$count);
      $row['enddate'] = Dates::addMonths($row['enddate'],$count);
      $events[] = $row;
    } elseif ($by == 'YEARLY') {
      $row['startdate'] = Dates::addYears($row['startdate'],$count);
      $row['enddate'] = Dates::addYears($row['enddate'],$count);
      $events[] = $row;
    }
    return $events;
  }

  function sort(&$events) {
    usort($events,['Calendarsource', '_startDateComparator']);
  }

  static function _startDateComparator($a, $b) {
    $a = $a['startDate'];
    $b = $b['startDate'];
    if (!$a) $a = 0;
    if (!$b) $b = 0;
    if ($a == $b) {
      return 0;
    }
    return ($a < $b) ? -1 : 1;
  }

  function _buildEvent($row) {
    $event = [
      'id' => $row['id'],
      'summary' => $row['summary'],
      'description' => $row['description'],
      'location' => $row['location'],
      'uniqueId' => $row['uniqueid'],
      'url' => $row['url'],
      'recurring' => $row['recurring'],
      'startDate' => $row['startdate'],
      'endDate' => $row['enddate'],
      'calendarTitle' => $this->title,
      'calendarDisplayTitle' => $this->displayTitle
    ];
    if ($row['duration'] > 0) {
      $event['endDate'] = Dates::addSeconds($row['startdate'],$row['duration']);
    }
    return $event;
  }

}
?>
