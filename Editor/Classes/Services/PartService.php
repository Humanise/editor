<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes.Services
 */
if (!isset($GLOBALS['basePath'])) {
    header('HTTP/1.1 403 Forbidden');
    exit;
}

class PartService {

  static function load($type, $id) {
    if (!$id) {
      return null;
    }
    $class = ucfirst($type) . 'Part';
    if (!ClassService::load($class)) {
      return null;
    }
    return ModelService::load($class,$id);
  }

  static function remove($part) {

    ModelService::remove($part);

    $sql = "delete from link where part_id = @id";
    Database::delete($sql, $part->getId());

    $sql = "delete from part_link where part_id = @id";
    Database::delete($sql, $part->getId());

    // Delete relations
    $schema = PartService::getSchema($part->getType());
    if (isset($schema['relations']) && is_array($schema['relations'])) {
      foreach ($schema['relations'] as $field => $info) {
        $sql = "delete from @name(table) where @name(column) = @id";
        Database::delete($sql, [
          'table' => $info['table'],
          'column' => $info['fromColumn'],
          'id' => $part->getId()
        ]);
      }
    }
  }

  static private function getSchema($type) {
    $class = PartService::getClassName($type);
    if (array_key_exists($class,Entity::$schema)) {
      return Entity::$schema[$class];
    }
    return null;
  }

  static function save($part) {
    $controller = PartService::getController($part->getType());
    if ($part->isPersistent()) {
      if ($controller) {
        $controller->beforeSave($part);
      }
      PartService::update($part);
    } else {
      $schema = PartService::getSchema($part->getType());

      $sql = "insert into part (type,style,dynamic,created,updated) values (@text(type),@text(style),@boolean(dynamic),now(),now())";
      $part->setId(Database::insert($sql, [
        'type' => $part->getType(),
        'style' => $part->getStyle(),
        'dynamic' => $part->isDynamic()
      ]));


      $values = SchemaService::buildSqlValueStructure($part,$schema);
      $values['part_id'] = ['int' => $part->getId()];
      Database::insert([
        'table' => 'part_' . $part->getType(),
        'values' => $values
      ]);

      if (isset($schema['relations']) && is_array($schema['relations'])) {
        foreach ($schema['relations'] as $field => $info) {
          $getter = 'get' . ucfirst($field);
          $ids = $part->$getter();
          if ($ids !== null) {
            foreach ($ids as $id) {
              Database::insert([
                'table' => $info['table'],
                'values' => [
                  $info['fromColumn'] => ['int' => $part->getId()],
                  $info['toColumn'] => ['int' => $id],
                ]
              ]);
            }
          }
        }
      }
      if ($controller) {
        $changed = $controller->beforeSave($part);
        if ($changed) {
          PartService::update($part);
        }
      }
    }
  }

  static function update($part) {
    $sql = "update part set updated = now(), dynamic = @boolean(dynamic), style = @text(style) where id = @int(id)";
    Database::update($sql,['id' => $part->getId(), 'style' => $part->getStyle(), 'dynamic' => $part->isDynamic()]);

    $schema = PartService::getSchema($part->getType());
    $values = SchemaService::buildSqlValueStructure($part,$schema);
    if ($values) {
      Database::update([
        'table' => 'part_' . $part->getType(),
        'values' => $values,
        'where' => ['part_id' => ['int' => $part->getId()]]
      ]);
    }
    // Update relations
    if (isset($schema['relations']) && is_array($schema['relations'])) {
      foreach ($schema['relations'] as $field => $info) {
        $sql = "delete from @name(table) where @name(column) = @id";
        Database::delete($sql, [
          'table' => $info['table'],
          'column' => $info['fromColumn'],
          'id' => $part->getId()
        ]);
        $getter = 'get' . ucfirst($field);
        $ids = $part->$getter();
        if ($ids !== null) {
          foreach ($ids as $id) {
            Database::insert([
              'table' => $info['table'],
              'values' => [
                $info['fromColumn'] => ['int' => $part->getId()],
                $info['toColumn'] => ['int' => $id],
              ]
            ]);
          }
        }
      }
    }
  }

  static function getClassName($type) {
    if (Strings::isBlank($type)) {
      return null;
    }
    return ucfirst($type) . 'Part';
  }

  /**
   * Creates a new part based on the type
   */
  static function newInstance($type) {
    $class = PartService::getClassName($type);
    if (!ClassService::load($class)) {
      return null;
    }
    return new $class;
  }

  /** Gets the controller for a type */
  static function getController($type) {
    global $basePath;
    if (!$type) {
      Log::debug('Unable to get controller for no type');
      return null;
    }
    $class = ucfirst($type) . 'PartController';
    $path = $basePath . 'Editor/Classes/Parts/' . $class . '.php';
    if (!file_exists($path)) {
      Log::debug('Unable to find controller for: ' . $type);
      return null;
    }
    require_once $path;
    return new $class;
  }

  static function getPageIdsForPart($partId) {
    $sql = "select page_id as id from document_section where part_id=@int(partId)";
    return Database::selectIntArray($sql,['partId' => $partId]);
  }

  /** Get a list of all available parts */
  static function getAvailableParts() {
    global $basePath;
    $arr = FileSystemService::listDirs($basePath . "Editor/Parts/");
    for ($i = 0; $i < count($arr); $i++) {
      if (substr($arr[$i],0,3) == 'CVS') {
        unset($arr[$i]);
      }
    }
    return $arr;
  }

  /** A map of all available parts */
  static function getParts() {
    return [
      'header' => [ 'name' => ['da' => 'Overskrift', 'en' => 'Header'] ],
      'text' => [ 'name' => ['da' => 'Tekst', 'en' => 'Text'] ],
      'listing' => [ 'name' => ['da' => 'Punktopstilling', 'en' => 'Bullet list'] ],
      'image' => [ 'name' => ['da' => 'Billede', 'en' => 'Image'] ],
      'imagegallery' => [ 'name' => ['da' => 'Billedgalleri', 'en' => 'Image gallery'] ],
      'horizontalrule' => [ 'name' => ['da' => 'Adskiller', 'en' => 'Divider'] ],
      'table' => [ 'name' => ['da' => 'Tabel', 'en' => 'Table'] ],
      'richtext' => [ 'name' => ['da' => 'Rig tekst', 'en' => 'Rich text'] ],
      'file' => [ 'name' => ['da' => 'Fil', 'en' => 'File'] ],
      'person' => [ 'name' => ['da' => 'Person', 'en' => 'Person'] ],
      'news' => [ 'name' => ['da' => 'Nyheder', 'en' => 'News'] ],
      'formula' => [ 'name' => ['da' => 'Formular', 'en' => 'Formula'] ],
      'list' => [ 'name' => ['da' => 'Liste', 'en' => 'List'] ],
      'mailinglist' => [ 'name' => ['da' => 'Postliste', 'en' => 'Mailing list'] ],
      'html' => [ 'name' => ['da' => 'HTML', 'en' => 'HTML'] ],
      'poster' => [ 'name' => ['da' => 'Plakat', 'en' => 'Poster'] ],
      'map' => [ 'name' => ['da' => 'Kort', 'en' => 'Map'] ],
      'movie' => [ 'name' => ['da' => 'Film', 'en' => 'Movie'] ],
      'menu' => [ 'name' => ['da' => 'Menu', 'en' => 'Menu'] ],
      'widget' => [ 'name' => ['da' => 'Widget', 'en' => 'Widget'] ],
      'authentication' => [ 'name' => ['da' => 'Adgangskontrol', 'en' => 'Authentication'] ],
      'custom' => [ 'name' => ['da' => 'Speciel', 'en' => 'Custom'] ]
    ];
  }

  /** The part menu structure */
  static function getPartMenu() {
    $parts = PartService::getParts();
    $menu = [
      'header' => $parts['header'],
      'text' => $parts['text'],
      'listing' => $parts['listing'],
      'image' => $parts['image'],
      'horizontalrule' => $parts['horizontalrule'],
      'table' => $parts['table'],
      'x' => 'divider',
      'richtext' => $parts['richtext'],
      'file' => $parts['file'],
      'imagegallery' => $parts['imagegallery'],
      'y' => 'divider',
      'advanced' => ['name' => ['da' => 'Avanceret', 'en' => 'Advanced'], 'children' => [
        'person' => $parts['person'],
        'news' => $parts['news'],
        'formula' => $parts['formula'],
        'list' => $parts['list'],
        'mailinglist' => $parts['mailinglist'],
        'html' => $parts['html'],
        'poster' => $parts['poster'],
        'map' => $parts['map'],
        'movie' => $parts['movie'],
        'menu' => $parts['menu'],
        'widget' => $parts['widget'],
        'custom' => $parts['custom'],
        'authentication' => $parts['authentication']
      ]]
    ];
    return $menu;
  }

  /** Gets all available controllers */
  static function getAllControllers() {
    $controllers = [];
    $parts = PartService::getParts();
    foreach ($parts as $key => $value) {
      $controllers[] = PartService::getController($key);
    }
    return $controllers;
  }

  /**
   * Used to sort arrays of tools
   */
  static function compareParts($partA, $partB) {
    $a = $partA['priority'];
    $b = $partB['priority'];
    if ($a == $b) {
      return 0;
    }
    return ($a < $b) ? -1 : 1;
  }

  /** Get the possible link text for a certain part */
  static function getLinkText($partId) {
    $text = '';
    $sql = "select text from part_text where part_text.part_id = @id
      union select text from part_header where part_header.part_id = @id
      union select text from part_listing where part_listing.part_id = @id
      union select html as text from part_table where part_table.part_id = @id";
    $result = Database::select($sql, ['id' => $partId]);
    while ($row = Database::next($result)) {
      $text .= ' ' . $row['text'];
    }
    Database::free($result);
    return trim($text);
  }

  /** Get the first link for a part */
  static function getSingleLink($part, $sourceType = null) {
    $sql = "select part_link.*,page.path from part_link left join page on page.id=part_link.target_value and part_link.target_type='page' where part_id = @id";
    if (!is_null($sourceType)) {
      $sql .= " and source_type = @text(type)";
    }
    return Database::selectFirst($sql, ['id' => $part->getId(), 'type' => $sourceType]);
  }

  /** Remove all existing links for a part */
  static function removeLinks($part) {
    $sql = "delete from part_link where part_id = @id";
    Database::delete($sql, $part->getId());
  }
}