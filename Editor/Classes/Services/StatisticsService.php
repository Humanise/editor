<?php
if (!isset($GLOBALS['basePath'])) {
  header('HTTP/1.1 403 Forbidden');
  exit;
}

class StatisticsService {

  static function registerPage($options) {
    $ip = getenv("REMOTE_ADDR");
    $method = 'GET'; //getenv('REQUEST_METHOD');
    //$uri = getenv('REQUEST_URI');
    $language = getenv('HTTP_ACCEPT_LANGUAGE');
    $session = session_id();
    $agent = $_SERVER['HTTP_USER_AGENT'];
    $userhost = '';
    if(isset($_SERVER['REMOTE_HOST'])) {
      $userhost = $_SERVER['REMOTE_HOST'];
    }
    $country = '';
    $sql = "insert into statistics (time,type,value,ip,country,agent,method,uri,language,session,referer,host) values (" .
    "now(),'page'," . Database::int($options['id']) . "," . Database::text($ip) . "," . Database::text($country) . "," . Database::text($agent) . "," . Database::text($method) . "," . Database::text($options['uri']) . "," . Database::text($language) . "," . Database::text($session) . "," . Database::text($options['referrer']) . "," . Database::text($userhost) . ")";
    Database::insert($sql);
  }

  static function getPageHits($rows) {
    $ids = [];
    $counts = [];
    foreach ($rows as $row) {
      $ids[] = $row['id'];
    }
    if (count($ids) > 0) {
      $sql = "select count(id) as hits,value as id from statistics where type='page' and value in (" . join(',', $ids) . ") group by value";
      $result = Database::selectAll($sql);
      foreach ($result as $row) {
        $counts[$row['id']] = $row['hits'];
      }
    }
    return $counts;
  }

  static function searchVisits($query) {
    $sql = 'SELECT count(distinct statistics.session) as sessions, count(distinct statistics.ip) as ips, count(statistics.id) as hits,date_format(statistics.time, "%Y%m%d") as `key`,date_format(statistics.time, "%d-%m-%Y") as label';
    $sql .= ' FROM statistics';
    $sql .= StatisticsService::_buildWhere($query);
    $sql .= ' group by label,`key` order by `key` desc limit 500';
    return Database::selectAll($sql);
  }

  static function searchAgents($query) {
    $sql = "SELECT UNIX_TIMESTAMP(max(statistics.time)) as lasttime,UNIX_TIMESTAMP(min(statistics.time)) as firsttime, count(distinct id) as visits,count(distinct ip) as ips,count(distinct session) as sessions,agent from statistics";
    $sql .= StatisticsService::_buildWhere($query);
    $sql .= " group by agent order by lasttime desc";
    return Database::selectAll($sql);
  }

  static function searchPages($query) {
    $sql = "select UNIX_TIMESTAMP(max(statistics.time)) as lasttime,UNIX_TIMESTAMP(min(statistics.time)) as firsttime,count(distinct statistics.id) as visits,count(distinct statistics.session) as sessions,count(distinct statistics.ip) as ips,page.title as page_title,page.id as page_id from statistics left join page on statistics.value=page.id where statistics.type='page'";
    $sql .= StatisticsService::_buildWhere($query,false);
    $sql .= " group by statistics.value order by visits desc";
    return Database::select($sql);
  }

  static function searchPaths($query) {
    $sql = "select UNIX_TIMESTAMP(max(statistics.time)) as lasttime,UNIX_TIMESTAMP(min(statistics.time)) as firsttime,count(distinct statistics.id) as visits,count(distinct statistics.session) as sessions,count(distinct statistics.ip) as ips,statistics.uri,page.title as page_title,page.id as page_id from statistics left join page on statistics.value=page.id where statistics.type='page'";
    $sql .= StatisticsService::_buildWhere($query,false);
    $sql .= " group by statistics.uri,page_title,page_id order by visits desc limit 100";
    return Database::select($sql);
  }

  static function _buildWhere($query,$prepend = true) {
    $where = [];
    if ($query->getStartTime()) {
      $where[] = 'statistics.time>=' . Database::datetime($query->getStartTime());
    }
    if ($query->getEndTime()) {
      $where[] = 'statistics.time<=' . Database::datetime($query->getEndTime());
    }
    if ($where) {
      return ($prepend ? ' where ' : ' and ') . implode(' and ', $where);
    }
    return '';
  }

  static function getVisitsChart($query) {
    $patterns = [
      'daily' => ['sql' => '%Y%m%d', 'php' => 'Ymd', 'div' => 60 * 60 * 24],
      'hourly' => ['sql' => '%Y%m%d%H', 'php' => 'YmdH', 'div' => 60 * 60],
      'monthly' => ['sql' => '%Y%m', 'php' => 'Ym', 'div' => 60 * 60 * 24 * 31],
      'yearly' => ['sql' => '%Y', 'php' => 'Y', 'div' => 60 * 60 * 24 * 365]
    ];

    $days = 100;

    $resolution = $query->getResolution();

    $sql = 'SELECT count(distinct statistics.session) as sessions, count(distinct statistics.ip) as ips, count(statistics.id) as hits,date_format(statistics.time, "' . $patterns[$resolution]['sql'] . '") as `key`,date_format(statistics.time, "%e") as label FROM statistics';
    $sql .= StatisticsService::_buildWhere($query);
    $sql .= '  group by `key`,label order by `key`';
    $rows = Database::selectAllKeys($sql,'key');

    if ($query->getStartTime()) {
      $start = $query->getStartTime();
    } else {
      $sql = "SELECT UNIX_TIMESTAMP(min(statistics.time)) as `min` from statistics " . StatisticsService::_buildWhere($query);
      $row = Database::selectFirst($sql);
      $start = intval($row['min']);
    }
    $end = Dates::getDayEnd();

    $days = floor(($end - $start) / $patterns[$resolution]['div']);

    $rows = StatisticsService::_fillGaps($rows,$days,$patterns,$resolution);
    $sets = [];
    $dimensions = ['sessions', 'ips', 'hits'];

        $labels = [];
    foreach ($rows as $row) {
      $labels[] = ['key' => $row['key'], 'label' => $row['label']];
    }

    foreach ($dimensions as $dim) {
      $entres = [];
      foreach ($rows as $row) {
        $entries[$row['key']] = $row[$dim];
      }
      $sets[] = ['type' => 'line', 'entries' => $entries];
    }
    return ['sets' => $sets, 'axis' => ['x' => ['labels' => $labels]]];
  }

  static function getPagesChart($query) {
    $sql = "select UNIX_TIMESTAMP(max(statistics.time)) as lasttime,UNIX_TIMESTAMP(min(statistics.time)) as firsttime,count(distinct statistics.id) as visits,count(distinct statistics.session) as sessions,count(distinct statistics.ip) as ips,page.title as page_title,page.id as page_id from statistics left join page on statistics.value=page.id where statistics.type='page'";
    $sql .= StatisticsService::_buildWhere($query,false);
    $sql .= " group by statistics.value order by visits desc limit 20";

    $rows = Database::selectAll($sql);

    $entries = [];
    foreach ($rows as $row) {
      $entries[$row['page_title']] = intval($row['visits']);
    }

    return ['sets' => [['type' => 'column', 'entries' => $entries]]];
  }

  static function _fillGaps($rows,$days,$patterns,$resolution) {
    $filled = [];
    $now = time();
    for ($i = $days; $i >= 0; $i--) {
      if ($resolution == 'daily') {
        $date = Dates::addDays($now,$i * -1);
      } else if ($resolution == 'monthly') {
        $date = Dates::addMonths($now,$i * -1);
      } else if ($resolution == 'yearly') {
        $date = Dates::addYears($now,$i * -1);
      } else {
        $date = Dates::addHours($now,$i * -1);
      }
      $key = date($patterns[$resolution]['php'],$date);
      if (array_key_exists($key,$rows)) {
        $filled[$key] = $rows[$key];
      } else {
        $filled[$key] = ['hits' => 0, 'sessions' => 0, 'ips' => 0, 'key' => $key, 'label' => date('j',$date)];
      }
    }
    return $filled;
  }
}