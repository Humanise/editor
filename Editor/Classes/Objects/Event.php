<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes
 */
if (!isset($GLOBALS['basePath'])) {
  header('HTTP/1.1 403 Forbidden');
  exit;
}

Entity::$schema['Event'] = [
  'table' => 'event',
  'properties' => [
    'location' => ['type' => 'string'],
    'startdate' => ['type' => 'datetime'],
    'enddate' => ['type' => 'datetime']
  ]
];

class Event extends ModelObject {
  var $startdate;
  var $enddate;
  var $location;

  function __construct() {
    parent::__construct('event');
  }

  static function load($id) {
    return ModelObject::get($id,'event');
  }

  function removeMore() {
    $sql = "delete from calendar_event where event_id = @int(id)";
    Database::delete($sql, ['id' => $this->id]);
  }

  function setLocation($location) {
      $this->location = $location;
  }

  function getLocation() {
      return $this->location;
  }

  function setStartdate($stamp) {
    $this->startdate = $stamp;
  }

  function getStartdate() {
    return $this->startdate;
  }

  function setEnddate($stamp) {
    $this->enddate = $stamp;
  }

  function getEnddate() {
    return $this->enddate;
  }

  ////////////////////////////// Utils ///////////////////////////

  function getCalendarIds() {
    $sql = "select calendar_id as id from calendar_event where event_id=@int(id)";
    return Database::getIds($sql,['id' => $this->id]);
  }

  function updateCalendarIds($ids) {
    $sql = "delete from calendar_event where event_id=@int(id)";
    Database::delete($sql, ['id' => $this->id]);
    foreach ($ids as $id) {
      $sql = "insert into calendar_event (event_id, calendar_id) values (@int(eventId), @int(calendarId))";
      Database::insert($sql,['eventId' => $this->id, 'calendarId' => $id]);
    }
  }

  static function search($query = []) {
    $out = [];
    if (isset($query['calendarId'])) {
      $sql = "select object.id from object,event,calendar_event where object.id=event.object_id and object.id=calendar_event.event_id and calendar_event.calendar_id=@int(calendarId)";
    } else {
      $sql = "select id from object,event where object.id=event.object_id";
    }
    if (isset($query['startDate']) && isset($query['endDate'])) {
      $sql .= " and not (startdate > @datetime(endDate) or endDate < @datetime(startDate))";
    }
    $sql .= " order by event.startdate";
    $result = Database::select($sql, $query);
    $ids = [];
    while ($row = Database::next($result)) {
      $ids[] = $row['id'];
    }
    Database::free($result);
    foreach ($ids as $id) {
      $out[] = Event::load($id);
    }
    return $out;
  }

  static function getSimpleEvents($query = []) {
    $out = [];
    $sql = "select object.id,object.title,object.note,event.location,unix_timestamp(event.startdate) as startdate,unix_timestamp(event.enddate) as enddate ";
    if (isset($query['calendarId'])) {
      $sql .= "from object,event,calendar_event where object.id=event.object_id and object.id=calendar_event.event_id and calendar_event.calendar_id = @int(calendarId)";
    } else {
      $sql .= " from object,event where object.id=event.object_id";
    }
    if (isset($query['startDate']) && isset($query['endDate'])) {
      $sql .= " and not (startdate > @datetime(endDate) or endDate < @datetime(startDate))";
    }
    $sql .= " order by object.title";
    $result = Database::select($sql, $query);
    while ($row = Database::next($result)) {
      $out[] = [
        'id' => $row['id'],
        'summary' => $row['title'] . "\n" . $row['note'],
        'location' => $row['location'],
        'uniqueId' => $row['id'],
        'recurring' => false,
        'startDate' => $row['startdate'],
        'endDate' => $row['enddate'],
        'calendarTitle' => (isset($query['calendarTitle']) ? $query['calendarTitle'] : '')
      ];
    }
    Database::free($result);
    return $out;
  }


  ////////////////////////////// Persistence ////////////////////////

  function sub_publish() {
    $data = '<event xmlns="' . parent::_buildnamespace('1.0') . '">';
    if (isset($this->startdate)) {
      $data .= Dates::buildTag('startdate',$this->startdate);
    }
    if (isset($this->enddate)) {
      $data .= Dates::buildTag('enddate',$this->enddate);
    }
    $data .= '</event>';
    return $data;
  }
}
?>