<?php
/**
 * @package OnlinePublisher
 * @subpackage Info
 */

if (!isset($GLOBALS['basePath'])) {
  header('HTTP/1.1 403 Forbidden');
  exit;
}
$databaseTables = [

  'address' => [
      ["object_id", "int(11)", "YES", "", "", ""],
      ["street", "varchar(255)", "YES", "", "", ""],
      ["zipcode", "varchar(255)", "YES", "", "", ""],
      ["city", "varchar(255)", "YES", "", "", ""],
      ["country", "varchar(255)", "YES", "", "", ""]
  ],
  'authentication' => [
      ["page_id", "int(11)", "", "", "0", ""],
      ["title", "varchar(255)", "YES", "", "", ""]
  ],
  'cachedurl' => [
      ["object_id", "int(11)", "", "", "0", ""],
      ["url", "varchar(255)", "YES", "", "", ""],
      ["synchronized", "datetime", "YES", "", "", ""],
      ["mimeType", "varchar(50)", "YES", "", "", ""]
  ],
  'calendar' => [
      ["object_id", "int(11)", "", "", "0", ""]
  ],
  'calendar_event' => [
      ["calendar_id", "int(11)", "", "", "0", ""],
      ["event_id", "int(11)", "", "", "0", ""]
  ],
  'calendarsource' => [
      ["object_id", "int(11)", "", "", "0", ""],
      ["url", "varchar(255)", "YES", "", "", ""],
      ["synchronized", "datetime", "YES", "", "", ""],
      ["sync_interval", "int(11)", "NO", "", "3600", ""],
      ["display_title", "varchar(255)", "YES", "", "", ""],
      ["filter", "varchar(255)", "YES", "", "", ""]
  ],
  'calendarsource_event' => [
      ["id", "int(11)", "", "PRI", "", "auto_increment"],
      ["calendarsource_id", "int(11)", "YES", "", "", ""],
      ["summary", "text", "YES", "", "", ""],
      ["description", "text", "YES", "", "", ""],
      ["startdate", "datetime", "YES", "", "", ""],
      ["enddate", "datetime", "YES", "", "", ""],
      ["timestamp", "datetime", "YES", "", "", ""],
      ["uniqueid", "varchar(255)", "YES", "", "", ""],
      ["location", "text", "YES", "", "", ""],
      ["recurring", "tinyint(4)", "", "", "0", ""],
      ["frequency", "varchar(20)", "YES", "", "", ""],
      ["until", "datetime", "YES", "", "", ""],
      ["count", "int(11)", "YES", "", "", ""],
      ["interval", "int(11)", "YES", "", "", ""],
      ["bymonth", "varchar(255)", "YES", "", "", ""],
      ["bymonthday", "varchar(255)", "YES", "", "", ""],
      ["byday", "varchar(255)", "YES", "", "", ""],
      ["byyearday", "varchar(255)", "YES", "", "", ""],
      ["byweeknumber", "varchar(255)", "YES", "", "", ""],
      ["weekstart", "char(2)", "YES", "", "", ""],
      ["duration", "int(11)", "YES", "", "", ""],
      ["url", "varchar(1024)", "YES", "", "", ""]
  ],
  'calendarviewer' => [
      ["page_id", "int(11)", "", "", "0", ""],
      ["title", "varchar(255)", "YES", "", "", ""],
      ["weekview_starthour", "int(11)", "", "", "0", ""],
      ["standard_view", "varchar(128)", "NO", "", "week", ""]
  ],
  'calendarviewer_object' => [
      ["id", "int(11)", "", "PRI", "", "auto_increment"],
      ["page_id", "int(11)", "", "", "0", ""],
      ["object_id", "int(11)", "", "", "0", ""]
  ],
  'design' => [
      ["object_id", "int(11)", "YES", "", "", ""],
      ["id", "int(11)", "", "PRI", "", "auto_increment"],
      ["unique", "varchar(255)", "", "", "", ""],
      ["name", "varchar(255)", "YES", "", "", ""],
      ["parameters", "text", "YES", "", "", ""]
    ],
  'design_parameter' => [
      ["id", "int(11)", "", "PRI", "", "auto_increment"],
      ["design_id", "int(11)", "", "", "0", ""],
      ["key", "varchar(255)", "YES", "", "", ""],
      ["type", "varchar(255)", "YES", "", "", ""],
      ["value", "varchar(255)", "YES", "", "", ""]
    ],
  'document' => [
      ["page_id", "int(11)", "", "", "0", ""]
    ],
  'document_column' => [
      ["id", "int(11)", "", "PRI", "", "auto_increment"],
      ["row_id", "int(11)", "", "", "0", ""],
      ["index", "int(11)", "", "", "0", ""],
      ["page_id", "int(11)", "", "", "0", ""],
      ["width", "varchar(50)", "", "", "", ""],
      ["top", "varchar(10)", "YES", "", "", ""],
      ["bottom", "varchar(10)", "YES", "", "", ""],
      ["left", "varchar(10)", "YES", "", "", ""],
      ["right", "varchar(10)", "YES", "", "", ""],
      ["class", "varchar(255)", "YES", "", "", ""],
      ["style", "text", "YES", "", "", ""]
    ],
  'document_row' => [
      ["id", "int(11)", "", "PRI", "", "auto_increment"],
      ["page_id", "int(11)", "", "", "0", ""],
      ["index", "int(11)", "", "", "0", ""],
      ["top", "varchar(10)", "YES", "", "", ""],
      ["bottom", "varchar(10)", "YES", "", "", ""],
      ["spacing", "varchar(10)", "YES", "", "", ""],
      ["class", "varchar(255)", "YES", "", "", ""],
      ["layout", "varchar(255)", "YES", "", "", ""],
      ["style", "text", "YES", "", "", ""]
    ],
  'document_section' => [
      ["id", "int(11)", "", "PRI", "", "auto_increment"],
      ["page_id", "int(11)", "", "", "0", ""],
      ["column_id", "int(11)", "", "", "0", ""],
      ["index", "int(11)", "", "", "0", ""],
      ["type", "varchar(20)", "", "", "", ""],
      ["top", "varchar(10)", "YES", "", "", ""],
      ["bottom", "varchar(10)", "YES", "", "", ""],
      ["left", "varchar(10)", "YES", "", "", ""],
      ["right", "varchar(10)", "YES", "", "", ""],
      ["part_id", "int(11)", "YES", "", "0", ""],
      ["float", "varchar(10)", "YES", "", "", ""],
      ["width", "varchar(10)", "YES", "", "", ""],
      ["class", "varchar(255)", "YES", "", "", ""],
      ["style", "text", "YES", "", "", ""]
    ],
  'email_validation_session' => [
      ["id", "int(11)", "", "PRI", "", "auto_increment"],
      ["unique", "varchar(255)", "", "", "", ""],
      ["user_id", "int(11)", "", "", "0", ""],
      ["email", "varchar(255)", "", "", "", ""],
      ["timelimit", "datetime", "", "", "0000-00-00 00:00:00", ""]
    ],
  'emailaddress' => [
      ["object_id", "int(11)", "", "", "0", ""],
      ["address", "varchar(255)", "YES", "", "", ""],
      ["containing_object_id", "int(11)", "", "", "0", ""]
    ],
  'event' => [
      ["object_id", "int(11)", "YES", "", "", ""],
      ["startdate", "datetime", "", "", "0000-00-00 00:00:00", ""],
      ["enddate", "datetime", "", "", "0000-00-00 00:00:00", ""],
      ["location", "varchar(255)", "YES", "", "", ""]
    ],
  'feedback' => [
      ["object_id", "int(11)", "YES", "", "", ""],
      ["name", "varchar(255)", "", "", "", ""],
      ["email", "varchar(255)", "", "", "", ""],
      ["message", "mediumtext", "", "", "", ""]
    ],
  'file' => [
      ["object_id", "int(11)", "YES", "", "", ""],
      ["filename", "varchar(255)", "", "", "", ""],
      ["size", "int(11)", "", "", "0", ""],
      ["type", "varchar(255)", "", "", "", ""]
    ],
  'filegroup' => [
      ["object_id", "int(11)", "YES", "", "", ""]
    ],
  'filegroup_file' => [
      ["file_id", "int(11)", "", "", "0", ""],
      ["filegroup_id", "int(11)", "", "", "0", ""]
    ],
  'frame' => [
      ["id", "int(11)", "", "PRI", "", "auto_increment"],
      ["title", "varchar(255)", "YES", "", "", ""],
      ["name", "varchar(255)", "YES", "", "", ""],
      ["hierarchy_id", "int(11)", "", "", "0", ""],
      ["searchbuttontitle", "varchar(255)", "YES", "", "", ""],
      ["searchenabled", "tinyint(4)", "", "", "0", ""],
      ["searchpage_id", "int(11)", "YES", "", "0", ""],
      ["searchpages", "tinyint(4)", "", "", "0", ""],
      ["searchimages", "tinyint(4)", "", "", "0", ""],
      ["searchfiles", "tinyint(4)", "", "", "0", ""],
      ["searchnews", "tinyint(4)", "", "", "0", ""],
      ["searchpersons", "tinyint(4)", "", "", "0", ""],
      ["searchproducts", "tinyint(4)", "", "", "0", ""],
      ["data", "text", "YES", "", "", ""],
      ["changed", "datetime", "YES", "", "", ""],
      ["published", "datetime", "YES", "", "", ""],
      ["bottomtext", "text", "YES", "", "", ""],
      ["dynamic", "tinyint(4)", "", "", "0", ""],
      ["userstatusenabled", "tinyint(4)", "", "", "0", ""],
      ["userstatuspage_id", "int(11)", "YES", "", "0", ""]
    ],
  'frame_link' => [
      ["id", "int(11)", "", "PRI", "", "auto_increment"],
      ["frame_id", "int(11)", "", "", "0", ""],
      ["position", "varchar(10)", "", "", "", ""],
      ["index", "int(11)", "", "", "0", ""],
      ["target", "varchar(10)", "YES", "", "", ""],
      ["target_type", "varchar(10)", "YES", "", "", ""],
      ["target_value", "text", "YES", "", "", ""],
      ["target_id", "int(11)", "YES", "", "", ""],
      ["alternative", "varchar(255)", "YES", "", "", ""],
      ["title", "varchar(255)", "YES", "", "", ""]
    ],
  'frame_newsblock' => [
      ["id", "int(11)", "", "PRI", "", "auto_increment"],
      ["frame_id", "int(11)", "", "", "0", ""],
      ["index", "int(11)", "", "", "0", ""],
      ["title", "varchar(255)", "YES", "", "", ""],
      ["sortby", "varchar(20)", "YES", "", "", ""],
      ["sortdir", "varchar(20)", "YES", "", "", ""],
      ["maxitems", "int(11)", "YES", "", "", ""],
      ["timetype", "varchar(20)", "YES", "", "", ""],
      ["timecount", "int(11)", "YES", "", "", ""],
      ["startdate", "datetime", "YES", "", "", ""],
      ["enddate", "datetime", "YES", "", "", ""]
    ],
  'frame_newsblock_newsgroup' => [
      ["id", "int(11)", "", "PRI", "", "auto_increment"],
      ["frame_newsblock_id", "int(11)", "", "", "0", ""],
      ["newsgroup_id", "int(11)", "", "", "0", ""]
    ],
  'guestbook' => [
      ["page_id", "int(11)", "", "", "0", ""],
      ["title", "varchar(255)", "", "", "", ""],
      ["text", "mediumtext", "", "", "", ""]
    ],
  'guestbook_item' => [
      ["id", "int(11)", "", "PRI", "", "auto_increment"],
      ["page_id", "int(11)", "", "", "0", ""],
      ["time", "datetime", "YES", "", "", ""],
      ["text", "text", "YES", "", "", ""],
      ["name", "varchar(255)", "YES", "", "", ""]
    ],
  'hierarchy' => [
      ["id", "int(11)", "", "PRI", "", "auto_increment"],
      ["name", "varchar(255)", "YES", "", "", ""],
      ["data", "text", "YES", "", "", ""],
      ["changed", "datetime", "YES", "", "", ""],
      ["published", "datetime", "YES", "", "", ""],
      ["language", "varchar(5)", "YES", "", "", ""]
    ],
  'hierarchy_item' => [
      ["id", "int(11)", "", "PRI", "", "auto_increment"],
      ["hierarchy_id", "int(11)", "YES", "", "", ""],
      ["parent", "int(11)", "", "", "0", ""],
      ["index", "int(11)", "", "", "0", ""],
      ["type", "varchar(255)", "YES", "", "", ""],
      ["title", "varchar(255)", "YES", "", "", ""],
      ["alternative", "varchar(255)", "YES", "", "", ""],
      ["target", "varchar(50)", "YES", "", "", ""],
      ["target_type", "varchar(255)", "YES", "", "", ""],
      ["target_id", "int(11)", "YES", "", "", ""],
      ["target_value", "text", "YES", "", "", ""],
      ["hidden", "tinyint(4)", "", "", "0", ""]
    ],
  'html' => [
      ["page_id", "int(11)", "", "", "0", ""],
      ["html", "text", "YES", "", "", ""],
      ["valid", "tinyint(4)", "", "", "1", ""],
      ["title", "varchar(255)", "YES", "", "", ""]
    ],
  'image' => [
      ["object_id", "int(11)", "YES", "", "", ""],
      ["filename", "varchar(255)", "", "", "", ""],
      ["size", "int(11)", "", "", "0", ""],
      ["width", "int(11)", "", "", "0", ""],
      ["height", "int(11)", "", "", "0", ""],
      ["type", "varchar(10)", "", "", "", ""]
    ],
  'imagegallery' => [
      ["page_id", "int(11)", "", "PRI", "0", ""],
      ["title", "varchar(255)", "", "", "", ""],
      ["text", "text", "YES", "", "", ""],
      ["imagesize", "int(11)", "", "", "48", ""],
      ["showtitle", "tinyint(1)", "", "", "1", ""],
      ["shownote", "tinyint(1)", "", "", "1", ""],
      ["rotate", "int(11)", "", "", "0", ""]
    ],
  'imagegallery_custom_info' => [
      ["id", "int(11)", "", "PRI", "", "auto_increment"],
      ["page_id", "int(11)", "", "", "0", ""],
      ["image_id", "int(11)", "", "", "0", ""],
      ["title", "varchar(255)", "YES", "", "", ""],
      ["note", "text", "YES", "", "", ""]
    ],
  'imagegallery_object' => [
      ["id", "int(11)", "", "PRI", "", "auto_increment"],
      ["page_id", "int(11)", "", "", "0", ""],
      ["object_id", "int(11)", "", "", "0", ""],
      ["position", "int(11)", "", "", "0", ""]
    ],
  'imagegroup' => [
      ["object_id", "int(11)", "YES", "", "", ""]
    ],
  'imagegroup_image' => [
      ["image_id", "int(11)", "", "", "0", ""],
      ["position", "int(11)", "YES", "", "", ""],
      ["imagegroup_id", "int(11)", "", "", "0", ""]
    ],
  'issue' => [
      ["object_id", "int(11)", "", "", "0", ""],
      ["kind", "varchar(255)", "YES", "", "", ""],
      ["issuestatus_id", "int(11)", "", "", "0", ""]
    ],
  'issuestatus' => [
      ["object_id", "int(11)", "", "", "0", ""]
    ],
  'link' => [
      ["id", "int(11)", "", "PRI", "", "auto_increment"],
      ["page_id", "int(11)", "", "", "0", ""],
      ["part_id", "int(11)", "", "", "0", ""],
      ["source_type", "varchar(10)", "YES", "", "", ""],
      ["source_text", "text", "YES", "", "", ""],
      ["target", "varchar(10)", "YES", "", "", ""],
      ["target_type", "varchar(10)", "YES", "", "", ""],
      ["target_value", "text", "YES", "", "", ""],
      ["target_id", "int(11)", "YES", "", "", ""],
      ["alternative", "varchar(255)", "YES", "", "", ""]
    ],
  'listener' => [
      ["object_id", "int(11)", "", "", "0", ""],
      ["event", "varchar(255)", "YES", "", "", ""],
      ["latest_execution", "datetime", "YES", "", "", ""],
      ["interval", "int(11)", "NO", "", "3600", ""]
    ],
  'log' => [
      ["id", "int(11)", "", "PRI", "", "auto_increment"],
      ["time", "datetime", "YES", "", "", ""],
      ["category", "varchar(50)", "YES", "", "", ""],
      ["event", "varchar(50)", "YES", "", "", ""],
      ["entity", "int(11)", "YES", "", "", ""],
      ["message", "varchar(255)", "YES", "", "", ""],
      ["user_id", "int(11)", "YES", "", "", ""],
      ["ip", "varchar(255)", "YES", "", "", ""],
      ["session", "varchar(255)", "YES", "", "", ""]
    ],
  'mailinglist' => [
      ["object_id", "int(11)", "YES", "", "", ""]
      ],
  'milestone' => [
      ["object_id", "int(11)", "", "", "0", ""],
      ["deadline", "datetime", "YES", "", "", ""],
      ["containing_object_id", "int(11)", "", "", "0", ""],
      ["completed", "tinyint(1)", "YES", "", "0", ""]
      ],
  'news' => [
      ["object_id", "int(11)", "", "", "0", ""],
      ["startdate", "datetime", "YES", "", "0000-00-00 00:00:00", ""],
      ["enddate", "datetime", "YES", "", "0000-00-00 00:00:00", ""],
      ["image_id", "int(11)", "YES", "", "", ""]
    ],
  'newsgroup' => [
      ["object_id", "int(11)", "", "", "0", ""]
    ],
  'newsgroup_news' => [
      ["news_id", "int(11)", "", "", "0", ""],
      ["newsgroup_id", "int(11)", "", "", "0", ""]
    ],
  'newssource' => [
      ["object_id", "int(11)", "", "", "0", ""],
      ["url", "varchar(255)", "YES", "", "", ""],
      ["synchronized", "datetime", "YES", "", "", ""],
      ["sync_interval", "int(11)", "NO", "", "3600", ""]
    ],
  'newssourceitem' => [
      ["object_id", "int(11)", "", "", "0", ""],
      ["newssource_id", "int(11)", "", "", "0", ""],
      ["text", "text", "YES", "", "", ""],
      ["date", "datetime", "YES", "", "0000-00-00 00:00:00", ""],
      ["url", "varchar(255)", "YES", "", "", ""],
      ["guid", "varchar(255)", "YES", "", "", ""]
    ],
  'object' => [
      ["id", "int(11)", "", "PRI", "", "auto_increment"],
      ["title", "varchar(255)", "YES", "", "", ""],
      ["type", "varchar(50)", "", "", "", ""],
      ["note", "text", "YES", "", "", ""],
      ["data", "text", "YES", "", "", ""],
      ["created", "datetime", "", "", "0000-00-00 00:00:00", ""],
      ["updated", "datetime", "", "", "0000-00-00 00:00:00", ""],
      ["published", "datetime", "", "", "0000-00-00 00:00:00", ""],
      ["searchable", "tinyint(4)", "", "", "1", ""],
      ["index", "text", "YES", "", "", ""],
      ["owner_id", "int(11)", "", "", "0", ""]
    ],
  'object_link' => [
      ["id", "int(11)", "", "PRI", "", "auto_increment"],
      ["object_id", "int(11)", "", "", "0", ""],
      ["target", "varchar(10)", "YES", "", "", ""],
      ["target_type", "varchar(10)", "YES", "", "", ""],
      ["target_value", "text", "YES", "", "", ""],
      ["alternative", "varchar(255)", "YES", "", "", ""],
      ["title", "varchar(255)", "YES", "", "", ""],
      ["position", "int(11)", "", "", "0", ""]
    ],

  'page' => [
      ["id", "int(11)", "", "PRI", "", "auto_increment"],
      ["title", "varchar(100)", "", "", "", ""],
      ["description", "text", "YES", "", "", ""],
      ["keywords", "varchar(255)", "", "", "", ""],
      ["template_id", "int(11)", "", "", "0", ""],
      ["data", "longtext", "YES", "", "", ""],
      ["created", "datetime", "", "", "0000-00-00 00:00:00", ""],
      ["changed", "datetime", "", "", "0000-00-00 00:00:00", ""],
      ["published", "datetime", "", "", "0000-00-00 00:00:00", ""],
      ["design_id", "int(11)", "", "", "0", ""],
      ["frame_id", "int(11)", "", "", "0", ""],
      ["index", "text", "YES", "", "", ""],
      ["dynamic", "tinyint(4)", "", "", "0", ""],
      ["language", "varchar(5)", "YES", "", "", ""],
      ["searchable", "tinyint(4)", "", "", "1", ""],
      ["secure", "tinyint(4)", "", "", "0", ""],
      ["disabled", "tinyint(4)", "", "", "0", ""],
      ["name", "varchar(255)", "YES", "", "", ""],
      ["path", "varchar(255)", "YES", "", "", ""],
      ["next_page", "int(11)", "", "", "0", ""],
      ["previous_page", "int(11)", "", "", "0", ""]
    ],
  'page_cache' => [
      ["page_id", "int(11)", "YES", "", "", ""],
      ["stamp", "datetime", "YES", "", "", ""],
      ["version", "int(11)", "", "", "0", ""],
      ["html", "mediumtext", "YES", "", "", ""],
      ["path", "varchar(1024)", "YES", "", "", ""]
  ],
  'pageblueprint' => [
      ["object_id", "int(11)", "", "", "0", ""],
      ["design_id", "int(11)", "", "", "0", ""],
      ["frame_id", "int(11)", "", "", "0", ""],
      ["template_id", "int(11)", "", "", "0", ""]
    ],
  'page_history' => [
      ["id", "int(11)", "", "PRI", "", "auto_increment"],
      ["page_id", "int(11)", "", "", "0", ""],
      ["user_id", "int(11)", "", "", "0", ""],
      ["time", "datetime", "YES", "", "", ""],
      ["data", "longtext", "YES", "", "", ""],
      ["message", "text", "YES", "", "", ""]
    ],
  'page_translation' => [
      ["id", "int(11)", "", "PRI", "", "auto_increment"],
      ["page_id", "int(11)", "", "", "0", ""],
      ["translation_id", "int(11)", "", "", "0", ""]
    ],
  'parameter' => [
      ["id", "int(11)", "", "PRI", "", "auto_increment"],
      ["name", "varchar(255)", "", "", "", ""],
      ["level", "varchar(255)", "", "", "", ""],
      ["language", "varchar(5)", "YES", "", "", ""],
        ["value", "text", "YES", "", "", ""]
    ],
  'part' => [
      ["id", "int(11)", "", "PRI", "", "auto_increment"],
      ["type", "varchar(50)", "", "", "", ""],
      ["created", "datetime", "", "", "0000-00-00 00:00:00", ""],
      ["updated", "datetime", "", "", "0000-00-00 00:00:00", ""],
      ["style", "text", "YES", "", "", ""],
      ["dynamic", "tinyint(4)", "", "", "0", ""]
    ],
  'part_authentication' => [
      ["part_id", "int(11)", "", "", "0", ""]
    ],
  'part_custom' => [
      ["part_id", "int(11)", "", "", "0", ""],
      ["workflow_id", "int(11)", "", "", "0", ""],
      ["view_id", "int(11)", "", "", "0", ""]
    ],
  'part_file' => [
      ["part_id", "int(11)", "", "", "0", ""],
      ["file_id", "int(11)", "", "", "0", ""],
      ["text", "varchar(255)", "YES", "", "", ""]
    ],
  'part_formula' => [
      ["part_id", "int(11)", "", "", "0", ""],
      ["receivername", "varchar(255)", "YES", "", "", ""],
      ["receiveremail", "varchar(255)", "YES", "", "", ""],
      ["recipe", "text", "YES", "", "", ""]
    ],
  'part_header' => [
      ["part_id", "int(11)", "", "", "0", ""],
      ["level", "int(11)", "", "", "1", ""],
      ["text", "text", "YES", "", "", ""],
      ["textalign", "varchar(50)", "YES", "", "", ""],
      ["fontfamily", "varchar(50)", "YES", "", "", ""],
      ["fontsize", "varchar(50)", "YES", "", "", ""],
      ["lineheight", "varchar(50)", "YES", "", "", ""],
      ["fontweight", "varchar(50)", "YES", "", "", ""],
      ["color", "varchar(50)", "YES", "", "", ""],
      ["wordspacing", "varchar(50)", "YES", "", "", ""],
      ["letterspacing", "varchar(50)", "YES", "", "", ""],
      ["textdecoration", "varchar(50)", "YES", "", "", ""],
      ["textindent", "varchar(50)", "YES", "", "", ""],
      ["texttransform", "varchar(50)", "YES", "", "", ""],
      ["fontstyle", "varchar(50)", "YES", "", "", ""],
      ["fontvariant", "varchar(50)", "YES", "", "", ""],
    ],
  'part_horizontalrule' => [
      ["part_id", "int(11)", "", "", "0", ""]
    ],
  'part_html' => [
      ["part_id", "int(11)", "", "", "0", ""],
      ["html", "text", "YES", "", "", ""],
    ],
  'part_image' => [
      ["part_id", "int(11)", "", "", "0", ""],
      ["image_id", "int(11)", "", "", "0", ""],
      ["align", "varchar(10)", "YES", "", "", ""],
      ["greyscale", "tinyint(4)", "", "", "0", ""],
      ["adaptive", "tinyint(4)", "", "", "0", ""],
      ["scalemethod", "varchar(20)", "YES", "", "", ""],
      ["scalewidth", "int(11)", "YES", "", "", ""],
      ["scaleheight", "int(11)", "YES", "", "", ""],
      ["scalepercent", "int(11)", "YES", "", "", ""],
      ["text", "varchar(255)", "YES", "", "", ""],
      ["frame", "varchar(30)", "YES", "", "", ""]
    ],
  'part_imagegallery' => [
      ["part_id", "int(11)", "", "", "0", ""],
      ["imagegroup_id", "int(11)", "", "", "0", ""],
      ["height", "int(11)", "NO", "", "64", ""],
      ["width", "int(11)", "NO", "", "64", ""],
      ["framed", "tinyint(4)", "YES", "", "0", ""],
      ["show_title", "tinyint(4)", "YES", "", "0", ""],
      ["variant", "varchar(10)", "YES", "", "", ""],
      ["frame", "varchar(30)", "YES", "", "", ""]
    ],
  'part_link' => [
      ["id", "int(11)", "", "PRI", "", "auto_increment"],
      ["part_id", "int(11)", "", "", "0", ""],
      ["source_type", "varchar(20)", "YES", "", "", ""],
      ["source_text", "text", "YES", "", "", ""],
      ["target", "varchar(10)", "YES", "", "", ""],
      ["target_type", "varchar(10)", "YES", "", "", ""],
      ["target_value", "text", "YES", "", "", ""],
      ["alternative", "varchar(255)", "YES", "", "", ""],
      ["title", "varchar(255)", "YES", "", "", ""],
      ["position", "int(11)", "", "", "0", ""]
    ],
  'part_list' => [
      ["part_id", "int(11)", "", "", "0", ""],
      ["title", "varchar(255)", "YES", "", "", ""],
      ["align", "varchar(20)", "YES", "", "", ""],
      ["width", "varchar(20)", "YES", "", "", ""],
      ["maxitems", "int(11)", "YES", "", "10", ""],
      ["maxtextlength", "int(11)", "YES", "", "", ""],
      ["variant", "varchar(50)", "NO", "", "box", ""],
      ["time_count", "int(11)", "NO", "", "7", ""],
      ["time_type", "varchar(255)", "NO", "", "days", ""],
      ["show_source", "tinyint(4)", "NO", "", "0", ""],
      ["show_text", "tinyint(4)", "NO", "", "1", ""],
      ["show_timezone", "tinyint(4)", "NO", "", "0", ""],
      ["sort_direction", "varchar(10)", "NO", "", "ascending", ""],
      ["timezone", "varchar(255)", "NO", "", "days", ""]
    ],
  'part_list_object' => [
      ["part_id", "int(11)", "", "", "0", ""],
      ["object_id", "int(11)", "", "", "0", ""]
    ],
  'part_listing' => [
      ["part_id", "int(11)", "", "", "0", ""],
      ["text", "text", "YES", "", "", ""],
      ["textalign", "varchar(50)", "YES", "", "", ""],
      ["fontfamily", "varchar(50)", "YES", "", "", ""],
      ["fontsize", "varchar(50)", "YES", "", "", ""],
      ["lineheight", "varchar(50)", "YES", "", "", ""],
      ["fontweight", "varchar(50)", "YES", "", "", ""],
      ["color", "varchar(50)", "YES", "", "", ""],
      ["wordspacing", "varchar(50)", "YES", "", "", ""],
      ["letterspacing", "varchar(50)", "YES", "", "", ""],
      ["textdecoration", "varchar(50)", "YES", "", "", ""],
      ["textindent", "varchar(50)", "YES", "", "", ""],
      ["texttransform", "varchar(50)", "YES", "", "", ""],
      ["fontstyle", "varchar(50)", "YES", "", "", ""],
      ["fontvariant", "varchar(50)", "YES", "", "", ""],
      ["type", "varchar(20)", "YES", "", "", ""]
    ],
  'part_mailinglist' => [
      ["part_id", "int(11)", "", "", "0", ""],
    ],
  'part_mailinglist_mailinglist' => [
      ["part_id", "int(11)", "", "", "0", ""],
      ["mailinglist_id", "int(11)", "", "", "0", ""]
    ],
  'part_map' => [
      ["part_id", "int(11)", "", "", "0", ""],
      ["provider", "varchar(50)", "YES", "", "", ""],
      ["latitude", "decimal(20,17)", "YES", "", "", ""],
      ["longitude", "decimal(20,17)", "YES", "", "", ""],
      ["text", "text", "YES", "", "", ""],
      ["maptype", "varchar(50)", "YES", "", "", ""],
      ["markers", "text", "YES", "", "", ""],
      ["zoom", "int(11)", "", "", "0", ""],
      ["width", "varchar(11)", "YES", "", "", ""],
      ["height", "varchar(11)", "YES", "", "", ""],
      ["frame", "varchar(50)", "YES", "", "", ""]
    ],
  'part_menu' => [
      ["part_id", "int(11)", "", "", "0", ""],
      ["hierarchy_id", "int(11)", "YES", "", "", ""],
      ["variant", "varchar(255)", "YES", "", "", ""],
      ["header", "varchar(255)", "YES", "", "", ""],
        ["depth", "int(11)", "", "", "0", ""]
    ],
  'part_movie' => [
      ["part_id", "int(11)", "", "", "0", ""],
      ["file_id", "int(11)", "", "", "0", ""],
      ["image_id", "int(11)", "", "", "0", ""],
      ["text", "text", "YES", "", "", ""],
      ["code", "text", "YES", "", "", ""],
      ["url", "text", "YES", "", "", ""],
            ["width", "varchar(20)", "YES", "", "", ""],
      ["height", "varchar(20)", "YES", "", "", ""]
    ],
  'part_news' => [
      ["part_id", "int(11)", "", "", "0", ""],
      ["align", "varchar(20)", "YES", "", "", ""],
      ["width", "varchar(20)", "YES", "", "", ""],
      ["news_id", "int(11)", "YES", "", "", ""],
      ["mode", "varchar(20)", "YES", "", "", ""],
      ["title", "varchar(255)", "YES", "", "", ""],
      ["sortby", "varchar(20)", "YES", "", "", ""],
      ["sortdir", "varchar(20)", "YES", "", "", ""],
      ["maxitems", "int(11)", "YES", "", "", ""],
      ["timetype", "varchar(20)", "YES", "", "", ""],
      ["timecount", "int(11)", "YES", "", "", ""],
      ["startdate", "datetime", "YES", "", "", ""],
      ["enddate", "datetime", "YES", "", "", ""],
      ["variant", "varchar(50)", "YES", "", "box", ""]
    ],
  'part_news_newsgroup' => [
      ["part_id", "int(11)", "", "", "0", ""],
      ["newsgroup_id", "int(11)", "", "", "0", ""]
    ],
  'part_person' => [
      ["part_id", "int(11)", "", "", "0", ""],
      ["align", "varchar(50)", "YES", "", "", ""],
      ["person_id", "int(11)", "", "", "0", ""],
      ["show_firstname", "int(1)", "", "", "1", ""],
      ["show_middlename", "int(1)", "", "", "1", ""],
      ["show_surname", "int(1)", "", "", "1", ""],
      ["show_initials", "int(1)", "", "", "0", ""],
      ["show_nickname", "int(1)", "", "", "0", ""],
      ["show_jobtitle", "int(1)", "", "", "1", ""],
      ["show_sex", "int(1)", "", "", "0", ""],
      ["show_email_job", "int(1)", "", "", "1", ""],
      ["show_email_private", "int(1)", "", "", "1", ""],
      ["show_phone_job", "int(1)", "", "", "1", ""],
      ["show_phone_private", "int(1)", "", "", "1", ""],
      ["show_streetname", "int(1)", "", "", "1", ""],
      ["show_zipcode", "int(1)", "", "", "1", ""],
      ["show_city", "int(1)", "", "", "1", ""],
      ["show_country", "int(1)", "", "", "1", ""],
      ["show_webaddress", "int(1)", "", "", "1", ""],
      ["show_image", "int(1)", "", "", "1", ""]
    ],
  'part_poster' => [
      ["part_id", "int(11)", "", "", "0", ""],
      ["recipe", "text", "YES", "", "", ""]
  ],
  'part_richtext' => [
      ["part_id", "int(11)", "", "", "0", ""],
      ["html", "text", "YES", "", "", ""],
    ],
  'part_table' => [
      ["part_id", "int(11)", "", "", "0", ""],
      ["html", "text", "YES", "", "", ""],
    ],
  'part_text' => [
      ["part_id", "int(11)", "", "", "0", ""],
      ["text", "text", "YES", "", "", ""],
      ["textalign", "varchar(50)", "YES", "", "", ""],
      ["fontfamily", "varchar(50)", "YES", "", "", ""],
      ["fontsize", "varchar(50)", "YES", "", "", ""],
      ["lineheight", "varchar(50)", "YES", "", "", ""],
      ["fontweight", "varchar(50)", "YES", "", "", ""],
      ["color", "varchar(50)", "YES", "", "", ""],
      ["wordspacing", "varchar(50)", "YES", "", "", ""],
      ["letterspacing", "varchar(50)", "YES", "", "", ""],
      ["textdecoration", "varchar(50)", "YES", "", "", ""],
      ["textindent", "varchar(50)", "YES", "", "", ""],
      ["texttransform", "varchar(50)", "YES", "", "", ""],
      ["fontstyle", "varchar(50)", "YES", "", "", ""],
      ["fontvariant", "varchar(50)", "YES", "", "", ""],
      ["image_id", "int(11)", "YES", "", "", ""],
      ["imagefloat", "varchar(50)", "YES", "", "left", ""],
      ["imagewidth", "int(11)", "YES", "", "", ""],
      ["imageheight", "int(11)", "YES", "", "", ""]
    ],
  'part_widget' => [
      ["part_id", "int(11)", "", "", "0", ""],
      ["key", "varchar(100)", "YES", "", "", ""],
      ["data", "text", "YES", "", "", ""],
    ],
  'path' => [
      ["object_id", "int(11)", "", "", "0", ""],
      ["path", "text", "YES", "", "", ""],
      ["page_id", "int(11)", "YES", "", "", ""]
  ],
  'person' => [
      ["object_id", "int(11)", "", "", "0", ""],
      ["firstname", "varchar(50)", "YES", "", "", ""],
      ["middlename", "varchar(50)", "YES", "", "", ""],
      ["surname", "varchar(50)", "YES", "", "", ""],
      ["initials", "varchar(10)", "YES", "", "", ""],
      ["nickname", "varchar(20)", "YES", "", "", ""],
      ["jobtitle", "varchar(30)", "YES", "", "0000-00-00 00:00:00", ""],
      ["sex", "varchar(10)", "YES", "", "", ""],
      ["email_job", "varchar(50)", "YES", "", "", ""],
      ["email_private", "varchar(50)", "YES", "", "", ""],
      ["phone_job", "varchar(20)", "YES", "", "", ""],
      ["phone_private", "varchar(20)", "YES", "", "", ""],
      ["streetname", "varchar(50)", "YES", "", "", ""],
      ["zipcode", "varchar(4)", "YES", "", "", ""],
      ["city", "varchar(30)", "YES", "", "", ""],
      ["country", "varchar(30)", "YES", "", "", ""],
      ["webaddress", "varchar(30)", "YES", "", "", ""],
      ["image_id", "int(11)", "YES", "", "0", ""]
    ],
  'person_mailinglist' => [
      ["person_id", "int(11)", "", "", "0", ""],
      ["mailinglist_id", "int(11)", "", "", "0", ""]
    ],
    'persongroup' => [
      ["object_id", "int(11)", "", "", "0", ""]
    ],
  'personrole' => [
      ["object_id", "int(11)", "", "", "0", ""],
      ["person_id", "int(11)", "", "", "0", ""]
    ],
  'persongroup_person' => [
      ["person_id", "int(11)", "", "", "0", ""],
      ["persongroup_id", "int(11)", "", "", "0", ""]
    ],
  'phonenumber' => [
    ["object_id", "int(11)", "", "", "0", ""],
    ["number", "varchar(255)", "YES", "", "", ""],
    ["context", "varchar(255)", "YES", "", "", ""],
    ["containing_object_id", "int(11)", "", "", "0", ""]
      ],
  'problem' => [
      ["object_id", "int(11)", "", "", "0", ""],
      ["deadline", "datetime", "YES", "", "", ""],
      ["containing_object_id", "int(11)", "", "", "0", ""],
      ["completed", "tinyint(1)", "YES", "", "0", ""],
      ["milestone_id", "int(11)", "", "", "0", ""],
      ["priority", "float", "", "", "0", ""]
      ],
  'product' => [
      ["object_id", "int(11)", "", "", "0", ""],
      ["number", "varchar(100)", "YES", "", "", ""],
      ["image_id", "int(11)", "YES", "", "0", ""],
      ["producttype_id", "int(11)", "YES", "", "0", ""],
      ["allow_offer", "tinyint(4)", "NO", "", "0", ""]
    ],
  'productattribute' => [
      ["id", "int(11)", "", "PRI", "", "auto_increment"],
      ["product_id", "int(11)", "", "", "0", ""],
      ["name", "varchar(255)", "YES", "", "", ""],
      ["value", "varchar(255)", "YES", "", "", ""],
      ["index", "int(11)", "", "", "0", ""]
    ],
  'productgroup' => [
      ["object_id", "int(11)", "", "", "0", ""]
    ],
  'productgroup_product' => [
      ["product_id", "int(11)", "", "", "0", ""],
      ["productgroup_id", "int(11)", "", "", "0", ""]
    ],
  'productoffer' => [
      ["object_id", "int(11)", "", "PRI", "", "auto_increment"],
      ["offer", "double", "YES", "", "", ""],
      ["product_id", "int(11)", "", "", "0", ""],
      ["person_id", "int(11)", "", "", "0", ""],
      ["expiry", "datetime", "YES", "", "", ""]
    ],
  'productprice' => [
      ["id", "int(11)", "", "PRI", "", "auto_increment"],
      ["product_id", "int(11)", "", "", "0", ""],
      ["amount", "double", "YES", "", "", ""],
      ["type", "varchar(255)", "YES", "", "", ""],
      ["price", "double", "YES", "", "", ""],
      ["currency", "varchar(5)", "YES", "", "", ""],
      ["index", "int(11)", "", "", "0", ""]
    ],
  'producttype' => [
      ["object_id", "int(11)", "", "", "0", ""]
    ],
  'project' => [
        ["object_id", "int(11)", "", "", "0", ""],
        ["parent_project_id", "int(11)", "", "", "0", ""]
    ],
  'relation' => [
      ["from_type", "varchar(255)", "YES", "", "object", ""],
      ["from_object_id", "int(11)", "", "", "0", ""],
      ["to_type", "varchar(255)", "YES", "", "object", ""],
      ["to_object_id", "int(11)", "", "", "0", ""],
      ["kind", "varchar(255)", "YES", "", "", ""]
    ],
  'remotepublisher' => [
      ["object_id", "int(11)", "", "", "0", ""],
      ["url", "varchar(255)", "YES", "", "", ""]
    ],
  'review' => [
      ["object_id", "int(11)", "", "", "0", ""],
      ["accepted", "tinyint(4)", "YES", "", "0", ""],
      ["date", "datetime", "YES", "", "", ""]
    ],
  'search' => [
      ["page_id", "int(11)", "", "PRI", "0", ""],
      ["title", "varchar(255)", "YES", "", "", ""],
      ["text", "text", "YES", "", "", ""],
      ["pagesenabled", "tinyint(4)", "YES", "", "0", ""],
      ["pageslabel", "varchar(255)", "YES", "", "", ""],
      ["pagesdefault", "tinyint(4)", "YES", "", "0", ""],
      ["pageshidden", "tinyint(4)", "YES", "", "0", ""],
      ["imagesenabled", "tinyint(4)", "YES", "", "0", ""],
      ["imageslabel", "varchar(255)", "YES", "", "", ""],
      ["imagesdefault", "tinyint(4)", "YES", "", "0", ""],
      ["imageshidden", "tinyint(4)", "YES", "", "0", ""],
      ["filesenabled", "tinyint(4)", "YES", "", "0", ""],
      ["fileslabel", "varchar(255)", "YES", "", "", ""],
      ["filesdefault", "tinyint(4)", "YES", "", "0", ""],
      ["fileshidden", "tinyint(4)", "YES", "", "0", ""],
      ["newsenabled", "tinyint(4)", "YES", "", "0", ""],
      ["newslabel", "varchar(255)", "YES", "", "", ""],
      ["newsdefault", "tinyint(4)", "YES", "", "0", ""],
      ["newshidden", "tinyint(4)", "YES", "", "0", ""],
      ["personsenabled", "tinyint(4)", "YES", "", "0", ""],
      ["personslabel", "varchar(255)", "YES", "", "", ""],
      ["personsdefault", "tinyint(4)", "YES", "", "0", ""],
      ["personshidden", "tinyint(4)", "YES", "", "0", ""],
      ["productsenabled", "tinyint(4)", "YES", "", "0", ""],
      ["productslabel", "varchar(255)", "YES", "", "", ""],
      ["productsdefault", "tinyint(4)", "YES", "", "0", ""],
      ["productshidden", "tinyint(4)", "YES", "", "0", ""],
      ["buttontitle", "varchar(255)", "YES", "", "", ""]
    ],
  'securityzone' => [
      ["object_id", "int(11)", "YES", "", "", ""],
      ["authentication_page_id", "int(11)", "", "", "0", ""]
    ],
  'securityzone_page' => [
      ["securityzone_id", "int(11)", "", "", "0", ""],
      ["page_id", "int(11)", "", "", "0", ""]
    ],
  'securityzone_user' => [
      ["securityzone_id", "int(11)", "", "", "0", ""],
      ["user_id", "int(11)", "", "", "0", ""]
    ],
  'setting' => [
      ["id", "int(11)", "", "PRI", "", "auto_increment"],
      ["domain", "varchar(30)", "YES", "", "", ""],
      ["subdomain", "varchar(30)", "YES", "", "", ""],
      ["key", "varchar(30)", "YES", "", "", ""],
      ["value", "text", "YES", "", "", ""],
      ["user_id", "int(11)", "YES", "", "0", ""],
    ],
  'source' => [
      ["object_id", "int(11)", "", "", "0", ""],
      ["url", "varchar(255)", "YES", "", "", ""],
      ["synchronized", "datetime", "YES", "", "", ""],
      ["interval", "int(11)", "NO", "", "3600", ""]
    ],
  'stream' => [
      ["object_id", "int(11)", "", "", "0", ""]
    ],
  'streamitem' => [
      ["object_id", "int(11)", "", "", "0", ""],
      ["stream_id", "int(11)", "", "", "0", ""],
      ["data", "text", "YES", "", "", ""],
      ["identity", "text", "YES", "", "", ""],
      ["hash", "varchar(255)", "YES", "", "", ""],
      ["originaldate", "datetime", "YES", "", "", ""],
      ["retrievaldate", "datetime", "YES", "", "", ""]
    ],
  'specialpage' => [
      ["id", "int(11)", "", "PRI", "", "auto_increment"],
      ["page_id", "int(11)", "", "", "0", ""],
      ["type", "varchar(30)", "YES", "", "", ""],
      ["language", "varchar(11)", "YES", "", "", ""]
    ],
  'statistics' => [
      ["id", "int(11)", "", "PRI", "", "auto_increment"],
      ["ip", "varchar(255)", "YES", "", "", ""],
      ["country", "varchar(255)", "YES", "", "", ""],
      ["agent", "varchar(4096)", "YES", "", "", ""],
      ["method", "varchar(255)", "YES", "", "", ""],
      ["uri", "varchar(4096)", "YES", "", "", ""],
      ["language", "varchar(255)", "YES", "", "", ""],
      ["type", "varchar(10)", "YES", "", "", ""],
      ["value", "int(11)", "YES", "", "", ""],
      ["session", "varchar(255)", "YES", "", "", ""],
      ["time", "datetime", "YES", "", "", ""],
      ["referer", "varchar(4096)", "YES", "", "", ""],
      ["host", "varchar(255)", "YES", "", "", ""],
      ["robot", "tinyint(4)", "YES", "", "", ""],
      ["known", "tinyint(4)", "YES", "", "", ""]
    ],
  'task' => [
      ["object_id", "int(11)", "", "", "0", ""],
      ["deadline", "datetime", "YES", "", "", ""],
      ["containing_object_id", "int(11)", "", "", "0", ""],
      ["completed", "tinyint(1)", "YES", "", "0", ""],
      ["milestone_id", "int(11)", "", "", "0", ""],
      ["priority", "float", "", "", "0", ""]
      ],
  'template' => [
      ["id", "int(11)", "", "PRI", "", "auto_increment"],
      ["unique", "varchar(50)", "", "", "", ""]
    ],
  'testphrase' => [
      ["object_id", "int(11)", "", "", "0", ""]
    ],
  'tool' => [
      ["id", "int(11)", "", "PRI", "", "auto_increment"],
      ["unique", "varchar(255)", "YES", "", "", ""]
    ],
  'user' => [
      ["object_id", "int(11)", "YES", "", "", ""],
      ["username", "varchar(50)", "", "", "", ""],
      ["password", "varchar(50)", "", "", "", ""],
      ["email", "varchar(50)", "", "", "", ""],
      ["language", "varchar(5)", "", "", "", ""],
      ["internal", "tinyint(1)", "", "", "0", ""],
      ["external", "tinyint(1)", "", "", "0", ""],
      ["administrator", "tinyint(1)", "", "", "0", ""],
      ["secure", "tinyint(1)", "", "", "0", ""]
    ],
  'user_permission' => [
      ["id", "int(11)", "", "PRI", "", "auto_increment"],
      ["user_id", "int(11)", "", "", "0", ""],
      ["entity_type", "varchar(50)", "", "", "", ""],
      ["entity_id", "int(11)", "", "", "0", ""],
      ["permission", "varchar(50)", "YES", "", "", ""]
    ],
  'view' => [
      ["object_id", "int(11)", "YES", "", "0", ""],
      ["path", "varchar(255)", "", "", "", ""]
    ],
  'watermeter' => [
      ["object_id", "int(11)", "YES", "", "0", ""],
      ["number", "varchar(50)", "", "", "", ""]
    ],
  'waterusage' => [
      ["object_id", "int(11)", "YES", "", "0", ""],
      ["watermeter_id", "int(11)", "YES", "", "0", ""],
      ["value", "int(11)", "", "", "0", ""],
      ["date", "datetime", "YES", "", "", ""],
      ["status", "int(11)", "", "", "0", ""],
      ["source", "int(11)", "", "", "0", ""]
    ],
  'weblog' => [
      ["page_id", "int(11)", "YES", "", "", ""],
      ["pageblueprint_id", "int(11)", "YES", "", "", ""],
      ["title", "varchar(255)", "YES", "", "", ""]
    ],
  'weblog_webloggroup' => [
      ["page_id", "int(11)", "", "", "0", ""],
      ["webloggroup_id", "int(11)", "", "", "0", ""]
    ],
  'weblogentry' => [
      ["object_id", "int(11)", "YES", "", "0", ""],
      ["text", "text", "YES", "", "", ""],
      ["date", "datetime", "YES", "", "", ""],
      ["page_id", "int(11)", "", "", "0", ""]
    ],
  'webloggroup' => [
      ["object_id", "int(11)", "YES", "", "", ""]
    ],
  'webloggroup_weblogentry' => [
      ["weblogentry_id", "int(11)", "", "", "0", ""],
      ["webloggroup_id", "int(11)", "", "", "0", ""]
    ],
  'workflow' => [
      ["object_id", "int(11)", "YES", "", "", ""],
      ["recipe", "text", "YES", "", "", ""]
    ],
];
// Unicode! again
?>