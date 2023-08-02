<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes.Services
 */
if (!isset($GLOBALS['basePath'])) {
  header('HTTP/1.1 403 Forbidden');
  exit;
}

class TemplateService {

  static function getTemplateByUnique($unique) {
    $sql = "select id,`unique` from `template` where `unique` = @text(unique)";
    if ($row = Database::selectFirst($sql, ['unique' => $unique])) {
      $template = new Template();
      $template->setId(intval($row['id']));
      $template->setUnique($row['unique']);
      return $template;
    }
    return null;
  }

  static function getController($type) {
    $class = ucfirst($type) . 'TemplateController';
    if (class_exists($class)) {
      return new $class;
    }
    return null;
  }

  static function getAvailableTemplates() {
    return FileSystemService::listDirs(FileSystemService::getFullPath("Editor/Template/"));
  }

  static function install($key) {
    if (!Template::loadByUnique($key)) {
      $template = new Template();
      $template->setUnique($key);
      $template->save();
    }
  }

  static function uninstall($key) {
    if ($template = Template::loadByUnique($key)) {
      $template->remove();
    }
  }

  static function getInstalledTemplates() {
    $arr = [];
    $sql = "select id,`unique` from `template`";
    $result = Database::select($sql);
    while ($row = Database::next($result)) {
      $arr[] = ["id" => $row['id'], "unique" => $row['unique']];
    }
    Database::free($result);
    return $arr;
  }

  static function getInstalledTemplateKeys() {
    $arr = [];
    $sql = "select `unique` from `template`";
    $result = Database::select($sql);
    while ($row = Database::next($result)) {
      $arr[] = $row['unique'];
    }
    Database::free($result);
    return $arr;
  }

  static function getUsedTemplates() {
    $arr = [];
    $sql = "select distinct `template`.`unique` from `template`,`page` where page.template_id=template.id";
    $result = Database::select($sql);
    while ($row = Database::next($result)) {
      $arr[] = $row['unique'];
    }
    Database::free($result);
    return $arr;
  }

  /**
   * @static
   */
  static function getTemplatesKeyed() {
    $output = [];
    $templates = TemplateService::getInstalledTemplates();
    for ($i = 0; $i < count($templates); $i++) {
      $unique = $templates[$i]['unique'];
      $info = TemplateService::getTemplateInfo($unique);
      $info['id'] = $templates[$i]['id'];
      $output[$unique] = $info;
    }
    return $output;
  }

  // returns all installed templates sorted by name
  static function getTemplatesSorted() {
    $output = [];
    $templates = TemplateService::getInstalledTemplates();
    for ($i = 0; $i < count($templates); $i++) {
      $unique = $templates[$i]['unique'];
      $info = TemplateService::getTemplateInfo($unique);
      $info['id'] = $templates[$i]['id'];
      $output[] = $info;
    }
    usort($output,['TemplateService', 'compareTemplates']);
    return $output;
  }

  // Used to sort arrays of tools
  static function compareTemplates($templateA, $templateB) {
    $a = $templateA['name'];
    $b = $templateB['name'];
    if ($a == $b) {
      return 0;
    }
    return ($a < $b) ? -1 : 1;
  }

  static function getTemplateInfo($unique) {
    global $basePath;
    if ($out = InternalSession::getSessionCacheVar('template.info.' . $unique)) {
      return $out;
    }
    else {
      $out = ['unique' => $unique, 'icon' => null, 'name' => null, 'description' => null];
      $filename = $basePath . "Editor/Template/" . $unique . "/info.xml";
      $file = file($filename);
      if ($file === false) {
        error_log('Not found: ' . $filename);
        return $out;
      }
      $data = implode("", $file);

      $parser = xml_parser_create('UTF-8');
      xml_parser_set_option($parser, XML_OPTION_CASE_FOLDING, 0);
      xml_parser_set_option($parser, XML_OPTION_SKIP_WHITE, 1);
      xml_parse_into_struct($parser, $data, $values, $tags);
      xml_parser_free($parser);
      foreach ($values as $key) {
        switch($key['tag']) {
          case 'icon' : $out['icon'] = $key['value']; break;
          case 'name' : $out['name'] = $key['value']; break;
          case 'status' : $out['status'] = $key['value']; break;
          case 'description' : $out['description'] = $key['value']; break;
        }
      }
      InternalSession::setSessionCacheVar('template.info.' . $unique,$out);
      return $out;
    }
  }
}