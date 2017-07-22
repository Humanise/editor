<?php
/**
 * @package OnlinePublisher
 * @subpackage Info
 */

if (!isset($GLOBALS['basePath'])) {
  header('HTTP/1.1 403 Forbidden');
  exit;
}
$HUMANISE_EDITOR_SCHEMA = [
  'tables' =>
   [
    0 =>
     [
      'name' => 'address',
      'columns' =>
       [
        'object_id' =>
         [
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
        'street' =>
         [
          'type' => 'varchar(255)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
        'zipcode' =>
         [
          'type' => 'varchar(255)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
        'city' =>
         [
          'type' => 'varchar(255)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
        'country' =>
         [
          'type' => 'varchar(255)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
      ],
    ],
    1 =>
     [
      'name' => 'authentication',
      'columns' =>
       [
        'page_id' =>
         [
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ],
        'title' =>
         [
          'type' => 'varchar(255)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
      ],
    ],
    2 =>
     [
      'name' => 'cachedurl',
      'columns' =>
       [
        'object_id' =>
         [
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ],
        'url' =>
         [
          'type' => 'varchar(255)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
        'synchronized' =>
         [
          'type' => 'datetime',
          'collation' => NULL,
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
        'mimeType' =>
         [
          'type' => 'varchar(50)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
      ],
    ],
    3 =>
     [
      'name' => 'calendar',
      'columns' =>
       [
        'object_id' =>
         [
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ],
      ],
    ],
    4 =>
     [
      'name' => 'calendar_event',
      'columns' =>
       [
        'calendar_id' =>
         [
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ],
        'event_id' =>
         [
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ],
      ],
    ],
    5 =>
     [
      'name' => 'calendarsource',
      'columns' =>
       [
        'object_id' =>
         [
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ],
        'url' =>
         [
          'type' => 'varchar(255)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
        'synchronized' =>
         [
          'type' => 'datetime',
          'collation' => NULL,
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
        'sync_interval' =>
         [
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'YES',
          'default' => '3600',
          'key' => '',
          'extra' => '',
        ],
        'display_title' =>
         [
          'type' => 'varchar(255)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
        'filter' =>
         [
          'type' => 'varchar(255)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
      ],
    ],
    6 =>
     [
      'name' => 'calendarsource_event',
      'columns' =>
       [
        'id' =>
         [
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => NULL,
          'key' => 'PRI',
          'extra' => 'auto_increment',
        ],
        'calendarsource_id' =>
         [
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
        'summary' =>
         [
          'type' => 'text',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
        'description' =>
         [
          'type' => 'text',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
        'startdate' =>
         [
          'type' => 'datetime',
          'collation' => NULL,
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
        'enddate' =>
         [
          'type' => 'datetime',
          'collation' => NULL,
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
        'timestamp' =>
         [
          'type' => 'datetime',
          'collation' => NULL,
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
        'uniqueid' =>
         [
          'type' => 'varchar(255)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
        'location' =>
         [
          'type' => 'text',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
        'recurring' =>
         [
          'type' => 'tinyint(4)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ],
        'frequency' =>
         [
          'type' => 'varchar(20)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
        'until' =>
         [
          'type' => 'datetime',
          'collation' => NULL,
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
        'count' =>
         [
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
        'interval' =>
         [
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
        'bymonth' =>
         [
          'type' => 'varchar(255)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
        'bymonthday' =>
         [
          'type' => 'varchar(255)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
        'byday' =>
         [
          'type' => 'varchar(255)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
        'byyearday' =>
         [
          'type' => 'varchar(255)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
        'byweeknumber' =>
         [
          'type' => 'varchar(255)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
        'weekstart' =>
         [
          'type' => 'char(2)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
        'duration' =>
         [
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
        'url' =>
         [
          'type' => 'varchar(1024)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
      ],
    ],
    7 =>
     [
      'name' => 'calendarviewer',
      'columns' =>
       [
        'page_id' =>
         [
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ],
        'title' =>
         [
          'type' => 'varchar(255)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
        'weekview_starthour' =>
         [
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ],
        'standard_view' =>
         [
          'type' => 'varchar(128)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => 'week',
          'key' => '',
          'extra' => '',
        ],
      ],
    ],
    8 =>
     [
      'name' => 'calendarviewer_object',
      'columns' =>
       [
        'id' =>
         [
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => NULL,
          'key' => 'PRI',
          'extra' => 'auto_increment',
        ],
        'page_id' =>
         [
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ],
        'object_id' =>
         [
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ],
      ],
    ],
    9 =>
     [
      'name' => 'design',
      'columns' =>
       [
        'object_id' =>
         [
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
        'id' =>
         [
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => NULL,
          'key' => 'PRI',
          'extra' => 'auto_increment',
        ],
        'unique' =>
         [
          'type' => 'varchar(255)',
          'collation' => 'utf8_danish_ci',
          'null' => 'NO',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
        'name' =>
         [
          'type' => 'varchar(255)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
        'parameters' =>
         [
          'type' => 'text',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
      ],
    ],
    10 =>
     [
      'name' => 'design_parameter',
      'columns' =>
       [
        'id' =>
         [
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => NULL,
          'key' => 'PRI',
          'extra' => 'auto_increment',
        ],
        'design_id' =>
         [
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ],
        'key' =>
         [
          'type' => 'varchar(255)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
        'type' =>
         [
          'type' => 'varchar(255)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
        'value' =>
         [
          'type' => 'varchar(255)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
      ],
    ],
    11 =>
     [
      'name' => 'document',
      'columns' =>
       [
        'page_id' =>
         [
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ],
      ],
    ],
    12 =>
     [
      'name' => 'document_column',
      'columns' =>
       [
        'id' =>
         [
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => NULL,
          'key' => 'PRI',
          'extra' => 'auto_increment',
        ],
        'row_id' =>
         [
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ],
        'index' =>
         [
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ],
        'page_id' =>
         [
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ],
        'width' =>
         [
          'type' => 'varchar(50)',
          'collation' => 'utf8_danish_ci',
          'null' => 'NO',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
        'top' =>
         [
          'type' => 'varchar(10)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
        'bottom' =>
         [
          'type' => 'varchar(10)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
        'left' =>
         [
          'type' => 'varchar(10)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
        'right' =>
         [
          'type' => 'varchar(10)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
      ],
    ],
    13 =>
     [
      'name' => 'document_row',
      'columns' =>
       [
        'id' =>
         [
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => NULL,
          'key' => 'PRI',
          'extra' => 'auto_increment',
        ],
        'page_id' =>
         [
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ],
        'index' =>
         [
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ],
        'top' =>
         [
          'type' => 'varchar(10)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
        'bottom' =>
         [
          'type' => 'varchar(10)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
        'spacing' =>
         [
          'type' => 'varchar(10)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
      ],
    ],
    14 =>
     [
      'name' => 'document_section',
      'columns' =>
       [
        'id' =>
         [
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => NULL,
          'key' => 'PRI',
          'extra' => 'auto_increment',
        ],
        'page_id' =>
         [
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ],
        'column_id' =>
         [
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ],
        'index' =>
         [
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ],
        'type' =>
         [
          'type' => 'varchar(20)',
          'collation' => 'utf8_danish_ci',
          'null' => 'NO',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
        'top' =>
         [
          'type' => 'varchar(10)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
        'bottom' =>
         [
          'type' => 'varchar(10)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
        'left' =>
         [
          'type' => 'varchar(10)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
        'right' =>
         [
          'type' => 'varchar(10)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
        'part_id' =>
         [
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'YES',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ],
        'float' =>
         [
          'type' => 'varchar(10)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
        'width' =>
         [
          'type' => 'varchar(10)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
      ],
    ],
    15 =>
     [
      'name' => 'email_validation_session',
      'columns' =>
       [
        'id' =>
         [
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => NULL,
          'key' => 'PRI',
          'extra' => 'auto_increment',
        ],
        'unique' =>
         [
          'type' => 'varchar(255)',
          'collation' => 'utf8_danish_ci',
          'null' => 'NO',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
        'user_id' =>
         [
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ],
        'email' =>
         [
          'type' => 'varchar(255)',
          'collation' => 'utf8_danish_ci',
          'null' => 'NO',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
        'timelimit' =>
         [
          'type' => 'datetime',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0000-00-00 00:00:00',
          'key' => '',
          'extra' => '',
        ],
      ],
    ],
    16 =>
     [
      'name' => 'emailaddress',
      'columns' =>
       [
        'object_id' =>
         [
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ],
        'address' =>
         [
          'type' => 'varchar(255)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
        'containing_object_id' =>
         [
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ],
      ],
    ],
    17 =>
     [
      'name' => 'event',
      'columns' =>
       [
        'object_id' =>
         [
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
        'startdate' =>
         [
          'type' => 'datetime',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0000-00-00 00:00:00',
          'key' => '',
          'extra' => '',
        ],
        'enddate' =>
         [
          'type' => 'datetime',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0000-00-00 00:00:00',
          'key' => '',
          'extra' => '',
        ],
        'location' =>
         [
          'type' => 'varchar(255)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
      ],
    ],
    18 =>
     [
      'name' => 'feedback',
      'columns' =>
       [
        'object_id' =>
         [
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
        'name' =>
         [
          'type' => 'varchar(255)',
          'collation' => 'utf8_danish_ci',
          'null' => 'NO',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
        'email' =>
         [
          'type' => 'varchar(255)',
          'collation' => 'utf8_danish_ci',
          'null' => 'NO',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
        'message' =>
         [
          'type' => 'mediumtext',
          'collation' => 'utf8_danish_ci',
          'null' => 'NO',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
      ],
    ],
    19 =>
     [
      'name' => 'file',
      'columns' =>
       [
        'object_id' =>
         [
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
        'filename' =>
         [
          'type' => 'varchar(255)',
          'collation' => 'utf8_danish_ci',
          'null' => 'NO',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
        'size' =>
         [
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ],
        'type' =>
         [
          'type' => 'varchar(255)',
          'collation' => 'utf8_danish_ci',
          'null' => 'NO',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
      ],
    ],
    20 =>
     [
      'name' => 'filegroup',
      'columns' =>
       [
        'object_id' =>
         [
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
      ],
    ],
    21 =>
     [
      'name' => 'filegroup_file',
      'columns' =>
       [
        'file_id' =>
         [
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ],
        'filegroup_id' =>
         [
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ],
      ],
    ],
    22 =>
     [
      'name' => 'frame',
      'columns' =>
       [
        'id' =>
         [
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => NULL,
          'key' => 'PRI',
          'extra' => 'auto_increment',
        ],
        'title' =>
         [
          'type' => 'varchar(255)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
        'name' =>
         [
          'type' => 'varchar(255)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
        'hierarchy_id' =>
         [
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ],
        'searchbuttontitle' =>
         [
          'type' => 'varchar(255)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
        'searchenabled' =>
         [
          'type' => 'tinyint(4)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ],
        'searchpage_id' =>
         [
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'YES',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ],
        'searchpages' =>
         [
          'type' => 'tinyint(4)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ],
        'searchimages' =>
         [
          'type' => 'tinyint(4)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ],
        'searchfiles' =>
         [
          'type' => 'tinyint(4)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ],
        'searchnews' =>
         [
          'type' => 'tinyint(4)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ],
        'searchpersons' =>
         [
          'type' => 'tinyint(4)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ],
        'searchproducts' =>
         [
          'type' => 'tinyint(4)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ],
        'data' =>
         [
          'type' => 'text',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
        'changed' =>
         [
          'type' => 'datetime',
          'collation' => NULL,
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
        'published' =>
         [
          'type' => 'datetime',
          'collation' => NULL,
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
        'bottomtext' =>
         [
          'type' => 'text',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
        'dynamic' =>
         [
          'type' => 'tinyint(4)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ],
        'userstatusenabled' =>
         [
          'type' => 'tinyint(4)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ],
        'userstatuspage_id' =>
         [
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'YES',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ],
      ],
    ],
    23 =>
     [
      'name' => 'frame_link',
      'columns' =>
       [
        'id' =>
         [
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => NULL,
          'key' => 'PRI',
          'extra' => 'auto_increment',
        ],
        'frame_id' =>
         [
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ],
        'position' =>
         [
          'type' => 'varchar(10)',
          'collation' => 'utf8_danish_ci',
          'null' => 'NO',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
        'index' =>
         [
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ],
        'target' =>
         [
          'type' => 'varchar(10)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
        'target_type' =>
         [
          'type' => 'varchar(10)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
        'target_value' =>
         [
          'type' => 'text',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
        'target_id' =>
         [
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
        'alternative' =>
         [
          'type' => 'varchar(255)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
        'title' =>
         [
          'type' => 'varchar(255)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
      ],
    ],
    24 =>
     [
      'name' => 'frame_newsblock',
      'columns' =>
       [
        'id' =>
         [
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => NULL,
          'key' => 'PRI',
          'extra' => 'auto_increment',
        ],
        'frame_id' =>
         [
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ],
        'index' =>
         [
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ],
        'title' =>
         [
          'type' => 'varchar(255)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
        'sortby' =>
         [
          'type' => 'varchar(20)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
        'sortdir' =>
         [
          'type' => 'varchar(20)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
        'maxitems' =>
         [
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
        'timetype' =>
         [
          'type' => 'varchar(20)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
        'timecount' =>
         [
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
        'startdate' =>
         [
          'type' => 'datetime',
          'collation' => NULL,
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
        'enddate' =>
         [
          'type' => 'datetime',
          'collation' => NULL,
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
      ],
    ],
    25 =>
     [
      'name' => 'frame_newsblock_newsgroup',
      'columns' =>
       [
        'id' =>
         [
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => NULL,
          'key' => 'PRI',
          'extra' => 'auto_increment',
        ],
        'frame_newsblock_id' =>
         [
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ],
        'newsgroup_id' =>
         [
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ],
      ],
    ],
    26 =>
     [
      'name' => 'guestbook',
      'columns' =>
       [
        'page_id' =>
         [
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ],
        'title' =>
         [
          'type' => 'varchar(255)',
          'collation' => 'utf8_danish_ci',
          'null' => 'NO',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
        'text' =>
         [
          'type' => 'mediumtext',
          'collation' => 'utf8_danish_ci',
          'null' => 'NO',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
      ],
    ],
    27 =>
     [
      'name' => 'guestbook_item',
      'columns' =>
       [
        'id' =>
         [
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => NULL,
          'key' => 'PRI',
          'extra' => 'auto_increment',
        ],
        'page_id' =>
         [
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ],
        'time' =>
         [
          'type' => 'datetime',
          'collation' => NULL,
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
        'text' =>
         [
          'type' => 'text',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
        'name' =>
         [
          'type' => 'varchar(255)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
      ],
    ],
    28 =>
     [
      'name' => 'hierarchy',
      'columns' =>
       [
        'id' =>
         [
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => NULL,
          'key' => 'PRI',
          'extra' => 'auto_increment',
        ],
        'name' =>
         [
          'type' => 'varchar(255)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
        'data' =>
         [
          'type' => 'text',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
        'changed' =>
         [
          'type' => 'datetime',
          'collation' => NULL,
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
        'published' =>
         [
          'type' => 'datetime',
          'collation' => NULL,
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
        'language' =>
         [
          'type' => 'varchar(5)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
      ],
    ],
    29 =>
     [
      'name' => 'hierarchy_item',
      'columns' =>
       [
        'id' =>
         [
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => NULL,
          'key' => 'PRI',
          'extra' => 'auto_increment',
        ],
        'hierarchy_id' =>
         [
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
        'parent' =>
         [
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ],
        'index' =>
         [
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ],
        'type' =>
         [
          'type' => 'varchar(255)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
        'title' =>
         [
          'type' => 'varchar(255)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
        'alternative' =>
         [
          'type' => 'varchar(255)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
        'target' =>
         [
          'type' => 'varchar(50)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
        'target_type' =>
         [
          'type' => 'varchar(255)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
        'target_id' =>
         [
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
        'target_value' =>
         [
          'type' => 'text',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
        'hidden' =>
         [
          'type' => 'tinyint(4)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ],
      ],
    ],
    30 =>
     [
      'name' => 'html',
      'columns' =>
       [
        'page_id' =>
         [
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ],
        'html' =>
         [
          'type' => 'text',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
        'valid' =>
         [
          'type' => 'tinyint(4)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '1',
          'key' => '',
          'extra' => '',
        ],
        'title' =>
         [
          'type' => 'varchar(255)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
      ],
    ],
    31 =>
     [
      'name' => 'image',
      'columns' =>
       [
        'object_id' =>
         [
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
        'filename' =>
         [
          'type' => 'varchar(255)',
          'collation' => 'utf8_danish_ci',
          'null' => 'NO',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
        'size' =>
         [
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ],
        'width' =>
         [
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ],
        'height' =>
         [
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ],
        'type' =>
         [
          'type' => 'varchar(10)',
          'collation' => 'utf8_danish_ci',
          'null' => 'NO',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
      ],
    ],
    32 =>
     [
      'name' => 'imagegallery',
      'columns' =>
       [
        'page_id' =>
         [
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => 'PRI',
          'extra' => '',
        ],
        'title' =>
         [
          'type' => 'varchar(255)',
          'collation' => 'utf8_danish_ci',
          'null' => 'NO',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
        'text' =>
         [
          'type' => 'text',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
        'imagesize' =>
         [
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '48',
          'key' => '',
          'extra' => '',
        ],
        'showtitle' =>
         [
          'type' => 'tinyint(1)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '1',
          'key' => '',
          'extra' => '',
        ],
        'shownote' =>
         [
          'type' => 'tinyint(1)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '1',
          'key' => '',
          'extra' => '',
        ],
        'rotate' =>
         [
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ],
      ],
    ],
    33 =>
     [
      'name' => 'imagegallery_custom_info',
      'columns' =>
       [
        'id' =>
         [
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => NULL,
          'key' => 'PRI',
          'extra' => 'auto_increment',
        ],
        'page_id' =>
         [
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ],
        'image_id' =>
         [
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ],
        'title' =>
         [
          'type' => 'varchar(255)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
        'note' =>
         [
          'type' => 'text',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
      ],
    ],
    34 =>
     [
      'name' => 'imagegallery_object',
      'columns' =>
       [
        'id' =>
         [
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => NULL,
          'key' => 'PRI',
          'extra' => 'auto_increment',
        ],
        'page_id' =>
         [
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ],
        'object_id' =>
         [
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ],
        'position' =>
         [
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ],
      ],
    ],
    35 =>
     [
      'name' => 'imagegroup',
      'columns' =>
       [
        'object_id' =>
         [
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
      ],
    ],
    36 =>
     [
      'name' => 'imagegroup_image',
      'columns' =>
       [
        'image_id' =>
         [
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ],
        'imagegroup_id' =>
         [
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ],
      ],
    ],
    37 =>
     [
      'name' => 'issue',
      'columns' =>
       [
        'object_id' =>
         [
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ],
        'kind' =>
         [
          'type' => 'varchar(255)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
        'issuestatus_id' =>
         [
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ],
      ],
    ],
    38 =>
     [
      'name' => 'issuestatus',
      'columns' =>
       [
        'object_id' =>
         [
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ],
      ],
    ],
    39 =>
     [
      'name' => 'link',
      'columns' =>
       [
        'id' =>
         [
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => NULL,
          'key' => 'PRI',
          'extra' => 'auto_increment',
        ],
        'page_id' =>
         [
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ],
        'part_id' =>
         [
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ],
        'source_type' =>
         [
          'type' => 'varchar(10)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
        'source_text' =>
         [
          'type' => 'text',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
        'target' =>
         [
          'type' => 'varchar(10)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
        'target_type' =>
         [
          'type' => 'varchar(10)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
        'target_value' =>
         [
          'type' => 'text',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
        'target_id' =>
         [
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
        'alternative' =>
         [
          'type' => 'varchar(255)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
      ],
    ],
    40 =>
     [
      'name' => 'log',
      'columns' =>
       [
        'id' =>
         [
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => NULL,
          'key' => 'PRI',
          'extra' => 'auto_increment',
        ],
        'time' =>
         [
          'type' => 'datetime',
          'collation' => NULL,
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
        'category' =>
         [
          'type' => 'varchar(50)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
        'event' =>
         [
          'type' => 'varchar(50)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
        'entity' =>
         [
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
        'message' =>
         [
          'type' => 'varchar(255)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
        'user_id' =>
         [
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
        'ip' =>
         [
          'type' => 'varchar(255)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
        'session' =>
         [
          'type' => 'varchar(255)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
      ],
    ],
    41 =>
     [
      'name' => 'mailinglist',
      'columns' =>
       [
        'object_id' =>
         [
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
      ],
    ],
    42 =>
     [
      'name' => 'milestone',
      'columns' =>
       [
        'object_id' =>
         [
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ],
        'deadline' =>
         [
          'type' => 'datetime',
          'collation' => NULL,
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
        'containing_object_id' =>
         [
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ],
        'completed' =>
         [
          'type' => 'tinyint(1)',
          'collation' => NULL,
          'null' => 'YES',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ],
      ],
    ],
    43 =>
     [
      'name' => 'news',
      'columns' =>
       [
        'object_id' =>
         [
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ],
        'startdate' =>
         [
          'type' => 'datetime',
          'collation' => NULL,
          'null' => 'YES',
          'default' => '0000-00-00 00:00:00',
          'key' => '',
          'extra' => '',
        ],
        'enddate' =>
         [
          'type' => 'datetime',
          'collation' => NULL,
          'null' => 'YES',
          'default' => '0000-00-00 00:00:00',
          'key' => '',
          'extra' => '',
        ],
        'image_id' =>
         [
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
      ],
    ],
    44 =>
     [
      'name' => 'newsgroup',
      'columns' =>
       [
        'object_id' =>
         [
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ],
      ],
    ],
    45 =>
     [
      'name' => 'newsgroup_news',
      'columns' =>
       [
        'news_id' =>
         [
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ],
        'newsgroup_id' =>
         [
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ],
      ],
    ],
    46 =>
     [
      'name' => 'newssource',
      'columns' =>
       [
        'object_id' =>
         [
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ],
        'url' =>
         [
          'type' => 'varchar(255)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
        'synchronized' =>
         [
          'type' => 'datetime',
          'collation' => NULL,
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
        'sync_interval' =>
         [
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'YES',
          'default' => '3600',
          'key' => '',
          'extra' => '',
        ],
      ],
    ],
    47 =>
     [
      'name' => 'newssourceitem',
      'columns' =>
       [
        'object_id' =>
         [
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ],
        'newssource_id' =>
         [
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ],
        'text' =>
         [
          'type' => 'text',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
        'date' =>
         [
          'type' => 'datetime',
          'collation' => NULL,
          'null' => 'YES',
          'default' => '0000-00-00 00:00:00',
          'key' => '',
          'extra' => '',
        ],
        'url' =>
         [
          'type' => 'varchar(255)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
        'guid' =>
         [
          'type' => 'varchar(255)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
      ],
    ],
    48 =>
     [
      'name' => 'object',
      'columns' =>
       [
        'id' =>
         [
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => NULL,
          'key' => 'PRI',
          'extra' => 'auto_increment',
        ],
        'title' =>
         [
          'type' => 'varchar(255)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
        'type' =>
         [
          'type' => 'varchar(50)',
          'collation' => 'utf8_danish_ci',
          'null' => 'NO',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
        'note' =>
         [
          'type' => 'text',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
        'data' =>
         [
          'type' => 'text',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
        'created' =>
         [
          'type' => 'datetime',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0000-00-00 00:00:00',
          'key' => '',
          'extra' => '',
        ],
        'updated' =>
         [
          'type' => 'datetime',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0000-00-00 00:00:00',
          'key' => '',
          'extra' => '',
        ],
        'published' =>
         [
          'type' => 'datetime',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0000-00-00 00:00:00',
          'key' => '',
          'extra' => '',
        ],
        'searchable' =>
         [
          'type' => 'tinyint(4)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '1',
          'key' => '',
          'extra' => '',
        ],
        'index' =>
         [
          'type' => 'text',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
        'owner_id' =>
         [
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ],
      ],
    ],
    49 =>
     [
      'name' => 'object_link',
      'columns' =>
       [
        'id' =>
         [
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => NULL,
          'key' => 'PRI',
          'extra' => 'auto_increment',
        ],
        'object_id' =>
         [
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ],
        'target' =>
         [
          'type' => 'varchar(10)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
        'target_type' =>
         [
          'type' => 'varchar(10)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
        'target_value' =>
         [
          'type' => 'text',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
        'alternative' =>
         [
          'type' => 'varchar(255)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
        'title' =>
         [
          'type' => 'varchar(255)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
        'position' =>
         [
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ],
      ],
    ],
    50 =>
     [
      'name' => 'page',
      'columns' =>
       [
        'id' =>
         [
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => NULL,
          'key' => 'PRI',
          'extra' => 'auto_increment',
        ],
        'title' =>
         [
          'type' => 'varchar(100)',
          'collation' => 'utf8_danish_ci',
          'null' => 'NO',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
        'description' =>
         [
          'type' => 'text',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
        'keywords' =>
         [
          'type' => 'varchar(255)',
          'collation' => 'utf8_danish_ci',
          'null' => 'NO',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
        'template_id' =>
         [
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ],
        'data' =>
         [
          'type' => 'longtext',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
        'created' =>
         [
          'type' => 'datetime',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0000-00-00 00:00:00',
          'key' => '',
          'extra' => '',
        ],
        'changed' =>
         [
          'type' => 'datetime',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0000-00-00 00:00:00',
          'key' => '',
          'extra' => '',
        ],
        'published' =>
         [
          'type' => 'datetime',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0000-00-00 00:00:00',
          'key' => '',
          'extra' => '',
        ],
        'design_id' =>
         [
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ],
        'frame_id' =>
         [
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ],
        'index' =>
         [
          'type' => 'text',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
        'dynamic' =>
         [
          'type' => 'tinyint(4)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ],
        'language' =>
         [
          'type' => 'varchar(5)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
        'searchable' =>
         [
          'type' => 'tinyint(4)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '1',
          'key' => '',
          'extra' => '',
        ],
        'secure' =>
         [
          'type' => 'tinyint(4)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ],
        'disabled' =>
         [
          'type' => 'tinyint(4)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ],
        'name' =>
         [
          'type' => 'varchar(255)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
        'path' =>
         [
          'type' => 'varchar(255)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
        'next_page' =>
         [
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ],
        'previous_page' =>
         [
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ],
      ],
    ],
    51 =>
     [
      'name' => 'page_cache',
      'columns' =>
       [
        'page_id' =>
         [
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
        'stamp' =>
         [
          'type' => 'datetime',
          'collation' => NULL,
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
        'version' =>
         [
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ],
        'html' =>
         [
          'type' => 'mediumtext',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
        'path' =>
         [
          'type' => 'varchar(1024)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
      ],
    ],
    52 =>
     [
      'name' => 'page_history',
      'columns' =>
       [
        'id' =>
         [
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => NULL,
          'key' => 'PRI',
          'extra' => 'auto_increment',
        ],
        'page_id' =>
         [
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ],
        'user_id' =>
         [
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ],
        'time' =>
         [
          'type' => 'datetime',
          'collation' => NULL,
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
        'data' =>
         [
          'type' => 'longtext',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
        'message' =>
         [
          'type' => 'text',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
      ],
    ],
    53 =>
     [
      'name' => 'page_translation',
      'columns' =>
       [
        'id' =>
         [
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => NULL,
          'key' => 'PRI',
          'extra' => 'auto_increment',
        ],
        'page_id' =>
         [
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ],
        'translation_id' =>
         [
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ],
      ],
    ],
    54 =>
     [
      'name' => 'pageblueprint',
      'columns' =>
       [
        'object_id' =>
         [
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ],
        'design_id' =>
         [
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ],
        'frame_id' =>
         [
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ],
        'template_id' =>
         [
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ],
      ],
    ],
    55 =>
     [
      'name' => 'parameter',
      'columns' =>
       [
        'id' =>
         [
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => NULL,
          'key' => 'PRI',
          'extra' => 'auto_increment',
        ],
        'name' =>
         [
          'type' => 'varchar(255)',
          'collation' => 'utf8_danish_ci',
          'null' => 'NO',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
        'level' =>
         [
          'type' => 'varchar(255)',
          'collation' => 'utf8_danish_ci',
          'null' => 'NO',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
        'language' =>
         [
          'type' => 'varchar(5)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
        'value' =>
         [
          'type' => 'text',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
      ],
    ],
    56 =>
     [
      'name' => 'part',
      'columns' =>
       [
        'id' =>
         [
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => NULL,
          'key' => 'PRI',
          'extra' => 'auto_increment',
        ],
        'type' =>
         [
          'type' => 'varchar(50)',
          'collation' => 'utf8_danish_ci',
          'null' => 'NO',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
        'created' =>
         [
          'type' => 'datetime',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0000-00-00 00:00:00',
          'key' => '',
          'extra' => '',
        ],
        'updated' =>
         [
          'type' => 'datetime',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0000-00-00 00:00:00',
          'key' => '',
          'extra' => '',
        ],
        'dynamic' =>
         [
          'type' => 'tinyint(4)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ],
      ],
    ],
    57 =>
     [
      'name' => 'part_authentication',
      'columns' =>
       [
        'part_id' =>
         [
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ],
      ],
    ],
    58 =>
     [
      'name' => 'part_file',
      'columns' =>
       [
        'part_id' =>
         [
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ],
        'file_id' =>
         [
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ],
        'text' =>
         [
          'type' => 'varchar(255)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
      ],
    ],
    59 =>
     [
      'name' => 'part_formula',
      'columns' =>
       [
        'part_id' =>
         [
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ],
        'receivername' =>
         [
          'type' => 'varchar(255)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
        'receiveremail' =>
         [
          'type' => 'varchar(255)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
        'recipe' =>
         [
          'type' => 'text',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
      ],
    ],
    60 =>
     [
      'name' => 'part_header',
      'columns' =>
       [
        'part_id' =>
         [
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ],
        'level' =>
         [
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '1',
          'key' => '',
          'extra' => '',
        ],
        'text' =>
         [
          'type' => 'text',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
        'textalign' =>
         [
          'type' => 'varchar(50)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
        'fontfamily' =>
         [
          'type' => 'varchar(50)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
        'fontsize' =>
         [
          'type' => 'varchar(50)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
        'lineheight' =>
         [
          'type' => 'varchar(50)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
        'fontweight' =>
         [
          'type' => 'varchar(50)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
        'color' =>
         [
          'type' => 'varchar(50)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
        'wordspacing' =>
         [
          'type' => 'varchar(50)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
        'letterspacing' =>
         [
          'type' => 'varchar(50)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
        'textdecoration' =>
         [
          'type' => 'varchar(50)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
        'textindent' =>
         [
          'type' => 'varchar(50)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
        'texttransform' =>
         [
          'type' => 'varchar(50)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
        'fontstyle' =>
         [
          'type' => 'varchar(50)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
        'fontvariant' =>
         [
          'type' => 'varchar(50)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
      ],
    ],
    61 =>
     [
      'name' => 'part_horizontalrule',
      'columns' =>
       [
        'part_id' =>
         [
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ],
      ],
    ],
    62 =>
     [
      'name' => 'part_html',
      'columns' =>
       [
        'part_id' =>
         [
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ],
        'html' =>
         [
          'type' => 'text',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
      ],
    ],
    63 =>
     [
      'name' => 'part_image',
      'columns' =>
       [
        'part_id' =>
         [
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ],
        'image_id' =>
         [
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ],
        'align' =>
         [
          'type' => 'varchar(10)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
        'greyscale' =>
         [
          'type' => 'tinyint(4)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ],
        'adaptive' =>
         [
          'type' => 'tinyint(4)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ],
        'scalemethod' =>
         [
          'type' => 'varchar(20)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
        'scalewidth' =>
         [
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
        'scaleheight' =>
         [
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
        'scalepercent' =>
         [
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
        'text' =>
         [
          'type' => 'varchar(255)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
        'frame' =>
         [
          'type' => 'varchar(30)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
      ],
    ],
    64 =>
     [
      'name' => 'part_imagegallery',
      'columns' =>
       [
        'part_id' =>
         [
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ],
        'imagegroup_id' =>
         [
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ],
        'height' =>
         [
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'YES',
          'default' => '64',
          'key' => '',
          'extra' => '',
        ],
        'width' =>
         [
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'YES',
          'default' => '64',
          'key' => '',
          'extra' => '',
        ],
        'framed' =>
         [
          'type' => 'tinyint(4)',
          'collation' => NULL,
          'null' => 'YES',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ],
        'show_title' =>
         [
          'type' => 'tinyint(4)',
          'collation' => NULL,
          'null' => 'YES',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ],
        'variant' =>
         [
          'type' => 'varchar(10)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
        'frame' =>
         [
          'type' => 'varchar(30)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
      ],
    ],
    65 =>
     [
      'name' => 'part_link',
      'columns' =>
       [
        'id' =>
         [
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => NULL,
          'key' => 'PRI',
          'extra' => 'auto_increment',
        ],
        'part_id' =>
         [
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ],
        'source_type' =>
         [
          'type' => 'varchar(20)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
        'source_text' =>
         [
          'type' => 'text',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
        'target' =>
         [
          'type' => 'varchar(10)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
        'target_type' =>
         [
          'type' => 'varchar(10)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
        'target_value' =>
         [
          'type' => 'text',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
        'alternative' =>
         [
          'type' => 'varchar(255)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
        'title' =>
         [
          'type' => 'varchar(255)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
        'position' =>
         [
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ],
      ],
    ],
    66 =>
     [
      'name' => 'part_list',
      'columns' =>
       [
        'part_id' =>
         [
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ],
        'title' =>
         [
          'type' => 'varchar(255)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
        'align' =>
         [
          'type' => 'varchar(20)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
        'width' =>
         [
          'type' => 'varchar(20)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
        'maxitems' =>
         [
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'YES',
          'default' => '10',
          'key' => '',
          'extra' => '',
        ],
        'maxtextlength' =>
         [
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
        'variant' =>
         [
          'type' => 'varchar(50)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => 'box',
          'key' => '',
          'extra' => '',
        ],
        'time_count' =>
         [
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'YES',
          'default' => '7',
          'key' => '',
          'extra' => '',
        ],
        'time_type' =>
         [
          'type' => 'varchar(255)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => 'days',
          'key' => '',
          'extra' => '',
        ],
        'show_source' =>
         [
          'type' => 'tinyint(4)',
          'collation' => NULL,
          'null' => 'YES',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ],
        'show_text' =>
         [
          'type' => 'tinyint(4)',
          'collation' => NULL,
          'null' => 'YES',
          'default' => '1',
          'key' => '',
          'extra' => '',
        ],
        'show_timezone' =>
         [
          'type' => 'tinyint(4)',
          'collation' => NULL,
          'null' => 'YES',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ],
        'sort_direction' =>
         [
          'type' => 'varchar(10)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => 'ascending',
          'key' => '',
          'extra' => '',
        ],
        'timezone' =>
         [
          'type' => 'varchar(255)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => 'days',
          'key' => '',
          'extra' => '',
        ],
      ],
    ],
    67 =>
     [
      'name' => 'part_list_object',
      'columns' =>
       [
        'part_id' =>
         [
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ],
        'object_id' =>
         [
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ],
      ],
    ],
    68 =>
     [
      'name' => 'part_listing',
      'columns' =>
       [
        'part_id' =>
         [
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ],
        'text' =>
         [
          'type' => 'text',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
        'textalign' =>
         [
          'type' => 'varchar(50)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
        'fontfamily' =>
         [
          'type' => 'varchar(50)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
        'fontsize' =>
         [
          'type' => 'varchar(50)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
        'lineheight' =>
         [
          'type' => 'varchar(50)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
        'fontweight' =>
         [
          'type' => 'varchar(50)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
        'color' =>
         [
          'type' => 'varchar(50)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
        'wordspacing' =>
         [
          'type' => 'varchar(50)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
        'letterspacing' =>
         [
          'type' => 'varchar(50)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
        'textdecoration' =>
         [
          'type' => 'varchar(50)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
        'textindent' =>
         [
          'type' => 'varchar(50)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
        'texttransform' =>
         [
          'type' => 'varchar(50)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
        'fontstyle' =>
         [
          'type' => 'varchar(50)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
        'fontvariant' =>
         [
          'type' => 'varchar(50)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
        'type' =>
         [
          'type' => 'varchar(20)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
      ],
    ],
    69 =>
     [
      'name' => 'part_mailinglist',
      'columns' =>
       [
        'part_id' =>
         [
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ],
      ],
    ],
    70 =>
     [
      'name' => 'part_mailinglist_mailinglist',
      'columns' =>
       [
        'part_id' =>
         [
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ],
        'mailinglist_id' =>
         [
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ],
      ],
    ],
    71 =>
     [
      'name' => 'part_map',
      'columns' =>
       [
        'part_id' =>
         [
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ],
        'provider' =>
         [
          'type' => 'varchar(50)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
        'latitude' =>
         [
          'type' => 'decimal(20,17)',
          'collation' => NULL,
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
        'longitude' =>
         [
          'type' => 'decimal(20,17)',
          'collation' => NULL,
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
        'text' =>
         [
          'type' => 'text',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
        'maptype' =>
         [
          'type' => 'varchar(50)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
        'markers' =>
         [
          'type' => 'text',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
        'zoom' =>
         [
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ],
        'width' =>
         [
          'type' => 'varchar(11)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
        'height' =>
         [
          'type' => 'varchar(11)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
        'frame' =>
         [
          'type' => 'varchar(50)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
      ],
    ],
    72 =>
     [
      'name' => 'part_menu',
      'columns' =>
       [
        'part_id' =>
         [
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ],
        'hierarchy_id' =>
         [
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
        'variant' =>
         [
          'type' => 'varchar(255)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
        'header' =>
         [
          'type' => 'varchar(255)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
        'depth' =>
         [
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ],
      ],
    ],
    73 =>
     [
      'name' => 'part_movie',
      'columns' =>
       [
        'part_id' =>
         [
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ],
        'file_id' =>
         [
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ],
        'image_id' =>
         [
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ],
        'text' =>
         [
          'type' => 'text',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
        'code' =>
         [
          'type' => 'text',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
        'url' =>
         [
          'type' => 'text',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
        'width' =>
         [
          'type' => 'varchar(20)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
        'height' =>
         [
          'type' => 'varchar(20)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
      ],
    ],
    74 =>
     [
      'name' => 'part_news',
      'columns' =>
       [
        'part_id' =>
         [
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ],
        'align' =>
         [
          'type' => 'varchar(20)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
        'width' =>
         [
          'type' => 'varchar(20)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
        'news_id' =>
         [
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
        'mode' =>
         [
          'type' => 'varchar(20)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
        'title' =>
         [
          'type' => 'varchar(255)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
        'sortby' =>
         [
          'type' => 'varchar(20)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
        'sortdir' =>
         [
          'type' => 'varchar(20)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
        'maxitems' =>
         [
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
        'timetype' =>
         [
          'type' => 'varchar(20)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
        'timecount' =>
         [
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
        'startdate' =>
         [
          'type' => 'datetime',
          'collation' => NULL,
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
        'enddate' =>
         [
          'type' => 'datetime',
          'collation' => NULL,
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
        'variant' =>
         [
          'type' => 'varchar(50)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => 'box',
          'key' => '',
          'extra' => '',
        ],
      ],
    ],
    75 =>
     [
      'name' => 'part_news_newsgroup',
      'columns' =>
       [
        'part_id' =>
         [
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ],
        'newsgroup_id' =>
         [
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ],
      ],
    ],
    76 =>
     [
      'name' => 'part_person',
      'columns' =>
       [
        'part_id' =>
         [
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ],
        'align' =>
         [
          'type' => 'varchar(50)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
        'person_id' =>
         [
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ],
        'show_firstname' =>
         [
          'type' => 'int(1)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '1',
          'key' => '',
          'extra' => '',
        ],
        'show_middlename' =>
         [
          'type' => 'int(1)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '1',
          'key' => '',
          'extra' => '',
        ],
        'show_surname' =>
         [
          'type' => 'int(1)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '1',
          'key' => '',
          'extra' => '',
        ],
        'show_initials' =>
         [
          'type' => 'int(1)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ],
        'show_nickname' =>
         [
          'type' => 'int(1)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ],
        'show_jobtitle' =>
         [
          'type' => 'int(1)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '1',
          'key' => '',
          'extra' => '',
        ],
        'show_sex' =>
         [
          'type' => 'int(1)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ],
        'show_email_job' =>
         [
          'type' => 'int(1)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '1',
          'key' => '',
          'extra' => '',
        ],
        'show_email_private' =>
         [
          'type' => 'int(1)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '1',
          'key' => '',
          'extra' => '',
        ],
        'show_phone_job' =>
         [
          'type' => 'int(1)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '1',
          'key' => '',
          'extra' => '',
        ],
        'show_phone_private' =>
         [
          'type' => 'int(1)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '1',
          'key' => '',
          'extra' => '',
        ],
        'show_streetname' =>
         [
          'type' => 'int(1)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '1',
          'key' => '',
          'extra' => '',
        ],
        'show_zipcode' =>
         [
          'type' => 'int(1)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '1',
          'key' => '',
          'extra' => '',
        ],
        'show_city' =>
         [
          'type' => 'int(1)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '1',
          'key' => '',
          'extra' => '',
        ],
        'show_country' =>
         [
          'type' => 'int(1)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '1',
          'key' => '',
          'extra' => '',
        ],
        'show_webaddress' =>
         [
          'type' => 'int(1)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '1',
          'key' => '',
          'extra' => '',
        ],
        'show_image' =>
         [
          'type' => 'int(1)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '1',
          'key' => '',
          'extra' => '',
        ],
      ],
    ],
    77 =>
     [
      'name' => 'part_poster',
      'columns' =>
       [
        'part_id' =>
         [
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ],
        'recipe' =>
         [
          'type' => 'text',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
      ],
    ],
    78 =>
     [
      'name' => 'part_richtext',
      'columns' =>
       [
        'part_id' =>
         [
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ],
        'html' =>
         [
          'type' => 'text',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
      ],
    ],
    79 =>
     [
      'name' => 'part_table',
      'columns' =>
       [
        'part_id' =>
         [
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ],
        'html' =>
         [
          'type' => 'text',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
      ],
    ],
    80 =>
     [
      'name' => 'part_text',
      'columns' =>
       [
        'part_id' =>
         [
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ],
        'text' =>
         [
          'type' => 'text',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
        'textalign' =>
         [
          'type' => 'varchar(50)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
        'fontfamily' =>
         [
          'type' => 'varchar(50)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
        'fontsize' =>
         [
          'type' => 'varchar(50)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
        'lineheight' =>
         [
          'type' => 'varchar(50)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
        'fontweight' =>
         [
          'type' => 'varchar(50)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
        'color' =>
         [
          'type' => 'varchar(50)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
        'wordspacing' =>
         [
          'type' => 'varchar(50)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
        'letterspacing' =>
         [
          'type' => 'varchar(50)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
        'textdecoration' =>
         [
          'type' => 'varchar(50)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
        'textindent' =>
         [
          'type' => 'varchar(50)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
        'texttransform' =>
         [
          'type' => 'varchar(50)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
        'fontstyle' =>
         [
          'type' => 'varchar(50)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
        'fontvariant' =>
         [
          'type' => 'varchar(50)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
        'image_id' =>
         [
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
        'imagefloat' =>
         [
          'type' => 'varchar(50)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => 'left',
          'key' => '',
          'extra' => '',
        ],
        'imagewidth' =>
         [
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
        'imageheight' =>
         [
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
      ],
    ],
    81 =>
     [
      'name' => 'part_widget',
      'columns' =>
       [
        'part_id' =>
         [
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ],
        'key' =>
         [
          'type' => 'varchar(100)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
        'data' =>
         [
          'type' => 'text',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
      ],
    ],
    82 =>
     [
      'name' => 'path',
      'columns' =>
       [
        'object_id' =>
         [
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ],
        'path' =>
         [
          'type' => 'text',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
        'page_id' =>
         [
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
      ],
    ],
    83 =>
     [
      'name' => 'person',
      'columns' =>
       [
        'object_id' =>
         [
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ],
        'firstname' =>
         [
          'type' => 'varchar(50)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
        'middlename' =>
         [
          'type' => 'varchar(50)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
        'surname' =>
         [
          'type' => 'varchar(50)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
        'initials' =>
         [
          'type' => 'varchar(10)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
        'nickname' =>
         [
          'type' => 'varchar(20)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
        'jobtitle' =>
         [
          'type' => 'varchar(30)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => '0000-00-00 00:00:00',
          'key' => '',
          'extra' => '',
        ],
        'sex' =>
         [
          'type' => 'varchar(10)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
        'email_job' =>
         [
          'type' => 'varchar(50)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
        'email_private' =>
         [
          'type' => 'varchar(50)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
        'phone_job' =>
         [
          'type' => 'varchar(20)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
        'phone_private' =>
         [
          'type' => 'varchar(20)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
        'streetname' =>
         [
          'type' => 'varchar(50)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
        'zipcode' =>
         [
          'type' => 'varchar(4)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
        'city' =>
         [
          'type' => 'varchar(30)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
        'country' =>
         [
          'type' => 'varchar(30)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
        'webaddress' =>
         [
          'type' => 'varchar(30)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
        'image_id' =>
         [
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'YES',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ],
      ],
    ],
    84 =>
     [
      'name' => 'person_mailinglist',
      'columns' =>
       [
        'person_id' =>
         [
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ],
        'mailinglist_id' =>
         [
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ],
      ],
    ],
    85 =>
     [
      'name' => 'persongroup',
      'columns' =>
       [
        'object_id' =>
         [
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ],
      ],
    ],
    86 =>
     [
      'name' => 'persongroup_person',
      'columns' =>
       [
        'person_id' =>
         [
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ],
        'persongroup_id' =>
         [
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ],
      ],
    ],
    87 =>
     [
      'name' => 'personrole',
      'columns' =>
       [
        'object_id' =>
         [
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ],
        'person_id' =>
         [
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ],
      ],
    ],
    88 =>
     [
      'name' => 'phonenumber',
      'columns' =>
       [
        'object_id' =>
         [
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ],
        'number' =>
         [
          'type' => 'varchar(255)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
        'context' =>
         [
          'type' => 'varchar(255)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
        'containing_object_id' =>
         [
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ],
      ],
    ],
    89 =>
     [
      'name' => 'problem',
      'columns' =>
       [
        'object_id' =>
         [
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ],
        'deadline' =>
         [
          'type' => 'datetime',
          'collation' => NULL,
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
        'containing_object_id' =>
         [
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ],
        'completed' =>
         [
          'type' => 'tinyint(1)',
          'collation' => NULL,
          'null' => 'YES',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ],
        'milestone_id' =>
         [
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ],
        'priority' =>
         [
          'type' => 'float',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ],
      ],
    ],
    90 =>
     [
      'name' => 'product',
      'columns' =>
       [
        'object_id' =>
         [
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ],
        'number' =>
         [
          'type' => 'varchar(100)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
        'image_id' =>
         [
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'YES',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ],
        'producttype_id' =>
         [
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'YES',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ],
        'allow_offer' =>
         [
          'type' => 'tinyint(4)',
          'collation' => NULL,
          'null' => 'YES',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ],
      ],
    ],
    91 =>
     [
      'name' => 'productattribute',
      'columns' =>
       [
        'id' =>
         [
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => NULL,
          'key' => 'PRI',
          'extra' => 'auto_increment',
        ],
        'product_id' =>
         [
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ],
        'name' =>
         [
          'type' => 'varchar(255)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
        'value' =>
         [
          'type' => 'varchar(255)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
        'index' =>
         [
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ],
      ],
    ],
    92 =>
     [
      'name' => 'productgroup',
      'columns' =>
       [
        'object_id' =>
         [
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ],
      ],
    ],
    93 =>
     [
      'name' => 'productgroup_product',
      'columns' =>
       [
        'product_id' =>
         [
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ],
        'productgroup_id' =>
         [
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ],
      ],
    ],
    94 =>
     [
      'name' => 'productoffer',
      'columns' =>
       [
        'object_id' =>
         [
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => NULL,
          'key' => 'PRI',
          'extra' => 'auto_increment',
        ],
        'offer' =>
         [
          'type' => 'double',
          'collation' => NULL,
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
        'product_id' =>
         [
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ],
        'person_id' =>
         [
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ],
        'expiry' =>
         [
          'type' => 'datetime',
          'collation' => NULL,
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
      ],
    ],
    95 =>
     [
      'name' => 'productprice',
      'columns' =>
       [
        'id' =>
         [
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => NULL,
          'key' => 'PRI',
          'extra' => 'auto_increment',
        ],
        'product_id' =>
         [
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ],
        'amount' =>
         [
          'type' => 'double',
          'collation' => NULL,
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
        'type' =>
         [
          'type' => 'varchar(255)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
        'price' =>
         [
          'type' => 'double',
          'collation' => NULL,
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
        'currency' =>
         [
          'type' => 'varchar(5)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
        'index' =>
         [
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ],
      ],
    ],
    96 =>
     [
      'name' => 'producttype',
      'columns' =>
       [
        'object_id' =>
         [
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ],
      ],
    ],
    97 =>
     [
      'name' => 'project',
      'columns' =>
       [
        'object_id' =>
         [
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ],
        'parent_project_id' =>
         [
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ],
      ],
    ],
    98 =>
     [
      'name' => 'relation',
      'columns' =>
       [
        'from_type' =>
         [
          'type' => 'varchar(255)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => 'object',
          'key' => '',
          'extra' => '',
        ],
        'from_object_id' =>
         [
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ],
        'to_type' =>
         [
          'type' => 'varchar(255)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => 'object',
          'key' => '',
          'extra' => '',
        ],
        'to_object_id' =>
         [
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ],
        'kind' =>
         [
          'type' => 'varchar(255)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
      ],
    ],
    99 =>
     [
      'name' => 'remotepublisher',
      'columns' =>
       [
        'object_id' =>
         [
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ],
        'url' =>
         [
          'type' => 'varchar(255)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
      ],
    ],
    100 =>
     [
      'name' => 'review',
      'columns' =>
       [
        'object_id' =>
         [
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ],
        'accepted' =>
         [
          'type' => 'tinyint(4)',
          'collation' => NULL,
          'null' => 'YES',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ],
        'date' =>
         [
          'type' => 'datetime',
          'collation' => NULL,
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
      ],
    ],
    101 =>
     [
      'name' => 'search',
      'columns' =>
       [
        'page_id' =>
         [
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => 'PRI',
          'extra' => '',
        ],
        'title' =>
         [
          'type' => 'varchar(255)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
        'text' =>
         [
          'type' => 'text',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
        'pagesenabled' =>
         [
          'type' => 'tinyint(4)',
          'collation' => NULL,
          'null' => 'YES',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ],
        'pageslabel' =>
         [
          'type' => 'varchar(255)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
        'pagesdefault' =>
         [
          'type' => 'tinyint(4)',
          'collation' => NULL,
          'null' => 'YES',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ],
        'pageshidden' =>
         [
          'type' => 'tinyint(4)',
          'collation' => NULL,
          'null' => 'YES',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ],
        'imagesenabled' =>
         [
          'type' => 'tinyint(4)',
          'collation' => NULL,
          'null' => 'YES',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ],
        'imageslabel' =>
         [
          'type' => 'varchar(255)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
        'imagesdefault' =>
         [
          'type' => 'tinyint(4)',
          'collation' => NULL,
          'null' => 'YES',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ],
        'imageshidden' =>
         [
          'type' => 'tinyint(4)',
          'collation' => NULL,
          'null' => 'YES',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ],
        'filesenabled' =>
         [
          'type' => 'tinyint(4)',
          'collation' => NULL,
          'null' => 'YES',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ],
        'fileslabel' =>
         [
          'type' => 'varchar(255)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
        'filesdefault' =>
         [
          'type' => 'tinyint(4)',
          'collation' => NULL,
          'null' => 'YES',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ],
        'fileshidden' =>
         [
          'type' => 'tinyint(4)',
          'collation' => NULL,
          'null' => 'YES',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ],
        'newsenabled' =>
         [
          'type' => 'tinyint(4)',
          'collation' => NULL,
          'null' => 'YES',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ],
        'newslabel' =>
         [
          'type' => 'varchar(255)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
        'newsdefault' =>
         [
          'type' => 'tinyint(4)',
          'collation' => NULL,
          'null' => 'YES',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ],
        'newshidden' =>
         [
          'type' => 'tinyint(4)',
          'collation' => NULL,
          'null' => 'YES',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ],
        'personsenabled' =>
         [
          'type' => 'tinyint(4)',
          'collation' => NULL,
          'null' => 'YES',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ],
        'personslabel' =>
         [
          'type' => 'varchar(255)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
        'personsdefault' =>
         [
          'type' => 'tinyint(4)',
          'collation' => NULL,
          'null' => 'YES',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ],
        'personshidden' =>
         [
          'type' => 'tinyint(4)',
          'collation' => NULL,
          'null' => 'YES',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ],
        'productsenabled' =>
         [
          'type' => 'tinyint(4)',
          'collation' => NULL,
          'null' => 'YES',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ],
        'productslabel' =>
         [
          'type' => 'varchar(255)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
        'productsdefault' =>
         [
          'type' => 'tinyint(4)',
          'collation' => NULL,
          'null' => 'YES',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ],
        'productshidden' =>
         [
          'type' => 'tinyint(4)',
          'collation' => NULL,
          'null' => 'YES',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ],
        'buttontitle' =>
         [
          'type' => 'varchar(255)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
      ],
    ],
    102 =>
     [
      'name' => 'securityzone',
      'columns' =>
       [
        'object_id' =>
         [
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
        'authentication_page_id' =>
         [
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ],
      ],
    ],
    103 =>
     [
      'name' => 'securityzone_page',
      'columns' =>
       [
        'securityzone_id' =>
         [
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ],
        'page_id' =>
         [
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ],
      ],
    ],
    104 =>
     [
      'name' => 'securityzone_user',
      'columns' =>
       [
        'securityzone_id' =>
         [
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ],
        'user_id' =>
         [
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ],
      ],
    ],
    105 =>
     [
      'name' => 'setting',
      'columns' =>
       [
        'id' =>
         [
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => NULL,
          'key' => 'PRI',
          'extra' => 'auto_increment',
        ],
        'domain' =>
         [
          'type' => 'varchar(30)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
        'subdomain' =>
         [
          'type' => 'varchar(30)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
        'key' =>
         [
          'type' => 'varchar(30)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
        'value' =>
         [
          'type' => 'text',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
        'user_id' =>
         [
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'YES',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ],
      ],
    ],
    106 =>
     [
      'name' => 'specialpage',
      'columns' =>
       [
        'id' =>
         [
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => NULL,
          'key' => 'PRI',
          'extra' => 'auto_increment',
        ],
        'page_id' =>
         [
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ],
        'type' =>
         [
          'type' => 'varchar(30)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
        'language' =>
         [
          'type' => 'varchar(11)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
      ],
    ],
    107 =>
     [
      'name' => 'statistics',
      'columns' =>
       [
        'id' =>
         [
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => NULL,
          'key' => 'PRI',
          'extra' => 'auto_increment',
        ],
        'ip' =>
         [
          'type' => 'varchar(255)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
        'country' =>
         [
          'type' => 'varchar(255)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
        'agent' =>
         [
          'type' => 'varchar(4096)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
        'method' =>
         [
          'type' => 'varchar(255)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
        'uri' =>
         [
          'type' => 'varchar(4096)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
        'language' =>
         [
          'type' => 'varchar(255)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
        'type' =>
         [
          'type' => 'varchar(10)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
        'value' =>
         [
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
        'session' =>
         [
          'type' => 'varchar(255)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
        'time' =>
         [
          'type' => 'datetime',
          'collation' => NULL,
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
        'referer' =>
         [
          'type' => 'varchar(4096)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
        'host' =>
         [
          'type' => 'varchar(255)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
        'robot' =>
         [
          'type' => 'tinyint(4)',
          'collation' => NULL,
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
        'known' =>
         [
          'type' => 'tinyint(4)',
          'collation' => NULL,
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
      ],
    ],
    108 =>
     [
      'name' => 'task',
      'columns' =>
       [
        'object_id' =>
         [
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ],
        'deadline' =>
         [
          'type' => 'datetime',
          'collation' => NULL,
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
        'containing_object_id' =>
         [
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ],
        'completed' =>
         [
          'type' => 'tinyint(1)',
          'collation' => NULL,
          'null' => 'YES',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ],
        'milestone_id' =>
         [
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ],
        'priority' =>
         [
          'type' => 'float',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ],
      ],
    ],
    109 =>
     [
      'name' => 'template',
      'columns' =>
       [
        'id' =>
         [
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => NULL,
          'key' => 'PRI',
          'extra' => 'auto_increment',
        ],
        'unique' =>
         [
          'type' => 'varchar(50)',
          'collation' => 'utf8_danish_ci',
          'null' => 'NO',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
      ],
    ],
    110 =>
     [
      'name' => 'testphrase',
      'columns' =>
       [
        'object_id' =>
         [
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ],
      ],
    ],
    111 =>
     [
      'name' => 'tool',
      'columns' =>
       [
        'id' =>
         [
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => NULL,
          'key' => 'PRI',
          'extra' => 'auto_increment',
        ],
        'unique' =>
         [
          'type' => 'varchar(255)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
      ],
    ],
    112 =>
     [
      'name' => 'user',
      'columns' =>
       [
        'object_id' =>
         [
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
        'username' =>
         [
          'type' => 'varchar(50)',
          'collation' => 'utf8_danish_ci',
          'null' => 'NO',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
        'password' =>
         [
          'type' => 'varchar(50)',
          'collation' => 'utf8_danish_ci',
          'null' => 'NO',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
        'email' =>
         [
          'type' => 'varchar(50)',
          'collation' => 'utf8_danish_ci',
          'null' => 'NO',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
        'language' =>
         [
          'type' => 'varchar(5)',
          'collation' => 'utf8_danish_ci',
          'null' => 'NO',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
        'internal' =>
         [
          'type' => 'tinyint(1)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ],
        'external' =>
         [
          'type' => 'tinyint(1)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ],
        'administrator' =>
         [
          'type' => 'tinyint(1)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ],
        'secure' =>
         [
          'type' => 'tinyint(1)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ],
      ],
    ],
    113 =>
     [
      'name' => 'user_permission',
      'columns' =>
       [
        'id' =>
         [
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => NULL,
          'key' => 'PRI',
          'extra' => 'auto_increment',
        ],
        'user_id' =>
         [
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ],
        'entity_type' =>
         [
          'type' => 'varchar(50)',
          'collation' => 'utf8_danish_ci',
          'null' => 'NO',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
        'entity_id' =>
         [
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ],
        'permission' =>
         [
          'type' => 'varchar(50)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
      ],
    ],
    114 =>
     [
      'name' => 'watermeter',
      'columns' =>
       [
        'object_id' =>
         [
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'YES',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ],
        'number' =>
         [
          'type' => 'varchar(50)',
          'collation' => 'utf8_danish_ci',
          'null' => 'NO',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
      ],
    ],
    115 =>
     [
      'name' => 'waterusage',
      'columns' =>
       [
        'object_id' =>
         [
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'YES',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ],
        'watermeter_id' =>
         [
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'YES',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ],
        'value' =>
         [
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ],
        'date' =>
         [
          'type' => 'datetime',
          'collation' => NULL,
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
        'status' =>
         [
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ],
        'source' =>
         [
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ],
      ],
    ],
    116 =>
     [
      'name' => 'weblog',
      'columns' =>
       [
        'page_id' =>
         [
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
        'pageblueprint_id' =>
         [
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
        'title' =>
         [
          'type' => 'varchar(255)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
      ],
    ],
    117 =>
     [
      'name' => 'weblog_webloggroup',
      'columns' =>
       [
        'page_id' =>
         [
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ],
        'webloggroup_id' =>
         [
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ],
      ],
    ],
    118 =>
     [
      'name' => 'weblogentry',
      'columns' =>
       [
        'object_id' =>
         [
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'YES',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ],
        'text' =>
         [
          'type' => 'text',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
        'date' =>
         [
          'type' => 'datetime',
          'collation' => NULL,
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
        'page_id' =>
         [
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ],
      ],
    ],
    119 =>
     [
      'name' => 'webloggroup',
      'columns' =>
       [
        'object_id' =>
         [
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ],
      ],
    ],
    120 =>
     [
      'name' => 'webloggroup_weblogentry',
      'columns' =>
       [
        'weblogentry_id' =>
         [
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ],
        'webloggroup_id' =>
         [
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ],
      ],
    ],
  ],
]
?>