<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes.Services
 */
if (!isset($GLOBALS['basePath'])) {
  header('HTTP/1.1 403 Forbidden');
  exit;
}

class HierarchyService {

  static function createHierarchy($hierarchy) {
    if (!$hierarchy) {
      Log::debug('No hierarchy');
      return;
    }
    $data = '<hierarchy xmlns="http://uri.in2isoft.com/onlinepublisher/publishing/hierarchy/1.0/"/>';

    $sql = "insert into hierarchy (name,language,data,changed,published)" .
      " values (@text(name),@text(language),@text(data),now(),now())";
    $hierarchy->setId(Database::insert($sql, [
      'name' => $hierarchy->getName(),
      'language' => $hierarchy->getLanguage(),
      'data' => $data
    ]));
  }

  static function getHierarchyItemForPage($page) {
    $sql = "select * from hierarchy_item where target_type='page' and target_id = @int(id)";
    return Database::selectFirst($sql,['id' => $page->getId()]);
  }

  static function updateHierarchy($hierarchy) {
    if (!$hierarchy) {
      Log::warn('No hierarchy');
      return;
    }
    $sql = "update hierarchy set " .
      "name = @text(name)" .
      ",language = @text(language)" .
      " where id = @id";
    $result = Database::update($sql, [
      'name' => $hierarchy->getName(),
      'language' => $hierarchy->getLanguage(),
      'id' => $hierarchy->getId()
    ]);
    EventService::fireEvent('update','hierarchy',null,$hierarchy->getId());
    return $result;
  }

  static function canDeleteHierarchy($id) {
    $sql = "select count(id) as num from hierarchy_item where hierarchy_id = @int(id)";
    if ($row = Database::selectFirst($sql,['id' => $id])) {
      if ($row['num'] == 0) {
        return true;
      }
    }
    return false;
  }

  static function getLatestId() {
    $sql = "select max(id) as id from hierarchy";
    if ($row = Database::selectFirst($sql)) {
      return intval($row['id']);
    }
    return null;
  }

  static function getItemByPageId($id) {
    $sql = "select id from `hierarchy_item` where `target_type`='page' and target_id=@int(id)";
    $result = Database::selectFirst($sql,['id' => $id]);
    if ($result) {
      return HierarchyItem::load($result['id']);
    }
    return null;
  }

  static function findItems($query = []) {
    $params = [];
    $sql = "SELECT id,title,hidden,target_type,target_value,target_id,hierarchy_id,parent,`index` from hierarchy_item";
    if (isset($query['parent'])) {
      $sql .= " where parent=@int(parent)";
      $params['parent'] = $query['parent'];
    }
    $sql .= " order by `index`";
    $items = [];
    $result = Database::select($sql,$params);
    while ($row = Database::next($result)) {
      $items[] = HierarchyService::_rowToItem($row);
    }
    Database::free($result);
    return $items;
  }

  static function loadItem($id) {
    $sql = "SELECT id,title,hidden,target_type,target_value,target_id,hierarchy_id,parent,`index` from hierarchy_item where id=@int(id)";
    if ($row = Database::selectFirst($sql,['id' => $id])) {
      return HierarchyService::_rowToItem($row);
    }
    return null;
  }

  static function _rowToItem($row) {
    $item = new HierarchyItem();
    $item->setId(intval($row['id']));
    $item->setTitle($row['title']);
    $item->setHidden($row['hidden'] == 1);
    $item->setTargetType($row['target_type']);
    $item->setHierarchyId(intval($row['hierarchy_id']));
    $item->setParent(intval($row['parent']));
    $item->setIndex(intval($row['index']));
    if ($row['target_type'] == 'page' || $row['target_type'] == 'pageref' || $row['target_type'] == 'file') {
      $item->setTargetValue($row['target_id']);
    } else {
      $item->setTargetValue($row['target_value']);
    }
    $sql = "SELECT * from hierarchy_item where parent=@int(id)";
    $item->canDelete = Database::isEmpty($sql,['id' => $row['id']]);
        return $item;
    }

  static function markHierarchyChanged($id) {
    $sql = "update hierarchy set changed=now() where id = @id";
    Database::update($sql, $id);
  }

  static function createItem($options) {
    if (Strings::isBlank(@$options['title'])) {
      Log::debug('No title');
      return false;
    }
    if (!in_array(@$options['targetType'],['page', 'pageref', 'file', 'email', 'url'])) {
      Log::debug('Invalid targetType');
      return false;
    }
    if (!isset($options['hidden'])) {
      Log::debug('hidden not set');
      return false;
    }
    if (!isset($options['targetValue'])) {
      Log::debug('targetValue not set');
      return false;
    }
    if (!isset($options['parent'])) {
      Log::debug('parent not set');
      return false;
    }
    if (!isset($options['hierarchyId'])) {
      Log::debug('hierarchyId not set');
      return false;
    }
    $sql = "select id from hierarchy where id = @id";
    if (!$row = Database::selectFirst($sql, $options['hierarchyId'])) {
      Log::debug('hierarchy not found');
      return false;
    }
    if ($options['parent'] > 0) {
      $sql = "select id from hierarchy_item where id = @int(parent) and hierarchy_id = @int(hierarchy)";
      if (!$row = Database::selectFirst($sql, ['parent' => $options['parent'], 'hierarchy' => $options['hierarchyId']])) {
        Log::debug('parent not found');
        return false;
      }
    }
    // find index
    if (isset($options['index'])) {
      $sql = "select id, `index` from hierarchy_item where `index` >= @int(index) and parent = @int(parent) and hierarchy_id = @int(hierarchy) order by `index`";
      $result = Database::select($sql,['index' => $options['index'], 'parent' => $options['parent'], 'hierarchy' => $options['hierarchyId']]);
      while ($row = Database::next($result)) {
        $sql = "update hierarchy_item set `index`=@int(index) where `id`=@int(id)";
        Database::update($sql,[
          'id' => $row['id'],
          'index' => intval($row['index']) + 1
        ]);
      }
      Database::free($result);
      $index = $options['index'];
    } else {
      $sql = "select max(`index`) as `index` from hierarchy_item where parent = @int(parent) and hierarchy_id = @int(hierarchy)";
      if ($row = Database::selectFirst($sql, ['parent' => $options['parent'], 'hierarchy' => $options['hierarchyId']])) {
        $index = $row['index'] + 1;
      } else {
        $index = 1;
      }
    }

    $target_id = null;
    $target_value = null;
    if ($options['targetType'] == 'page' || $options['targetType'] == 'pageref' || $options['targetType'] == 'file') {
      $target_id = $options['targetValue'];
    } else {
      $target_value = $options['targetValue'];
    }

    $sql = [
      'table' => 'hierarchy_item',
      'values' => [
        'title' => ['text' => $options['title']],
        'hidden' => ['boolean' => $options['hidden']],
        'type' => ['text' => 'item'],
        'hierarchy_id' => ['int' => $options['hierarchyId']],
        'parent' => ['int' => $options['parent']],
        'index' => ['int' => $index],
        'target_type' => ['text' => $options['targetType']],
        'target_id' => ['int' => $target_id],
        'target_value' => ['text' => $target_value]
      ]
    ];
    $id = Database::insert($sql);
    HierarchyService::markHierarchyChanged($options['hierarchyId']);
    EventService::fireEvent('update','hierarchy',null,$options['hierarchyId']);
    return $id;
  }

  static function deleteItem($id) {

    // Load info about item
    $sql = "select * from hierarchy_item where id = @id";
    $row = Database::selectFirst($sql, $id);
    if (!$row) {
      Log::debug('Cannot find item');
      return null;
    }
    // Check that no children exists
    $sql = "select * from hierarchy_item where parent = @id";
    if (Database::selectFirst($sql, $id)) {
      Log::debug('Will not delete item with parents');
      return null;
    }
    $parent = $row['parent'];
    $hierarchyId = $row['hierarchy_id'];

    // Delete item
    $sql = "delete from hierarchy_item where id = @id";
    Database::delete($sql, $id);

    // Fix positions
    $sql = "select id from hierarchy_item where parent = @int(parent) and hierarchy_id = @int(hierarchy) order by `index`";
    $result = Database::select($sql, ['parent' => $parent, 'hierarchy' => $hierarchyId]);

    $index = 1;
    while ($row = Database::next($result)) {
      $sql = "update hierarchy_item set `index` = @int(index) where id = @int(id)";
      Database::update($sql, ['index' => $index, 'id' => $row['id']]);
      $index++;
    }
    Database::free($result);

    // Mark hierarchy as changed
    $sql = "update hierarchy set changed=now() where id = @id";
    Database::update($sql, $hierarchyId);

    EventService::fireEvent('update','hierarchy',null,$hierarchyId);
    return $hierarchyId;
  }

  static function hierarchyTraveller($id,$parent,$allowDisabled,$depth = 100) {
    if ($depth == 0) {
      return "";
    }
    $output = "";
    $sql = "select hierarchy_item.*,page.disabled,page.path from hierarchy_item" .
      " left join page on page.id = hierarchy_item.target_id and (hierarchy_item.target_type='page' or hierarchy_item.target_type='pageref')" .
      " where parent = @int(parent)" .
      " and hierarchy_id = @id" .
      " order by `index`";
    $result = Database::select($sql, ['parent' => $parent, 'id' => $id]);
    while ($row = Database::next($result)) {
      if ($row['disabled'] != 1 || $allowDisabled) {
        $output .= '<item title="' . Strings::escapeEncodedXML($row['title']) .
          '" alternative="' . Strings::escapeEncodedXML($row['alternative']) . '"';
        if ($row['target_type'] == 'page') {
          $output .= ' page="' . $row['target_id'] . '"';
          if (strlen($row['path']) > 0) {
            $output .= ' path="' . Strings::escapeEncodedXML($row['path']) . '"';
          }
        }
        if ($row['target_type'] == 'pageref') {
          $output .= ' page-reference="' . $row['target_id'] . '"';
          if (strlen($row['path']) > 0) {
            $output .= ' path="' . Strings::escapeEncodedXML($row['path']) . '"';
          }
        }
        else if ($row['target_type'] == 'file') {
          $output .= ' file="' . $row['target_id'] . '" filename="' . Strings::escapeEncodedXML(FileService::getFileFilename($row['target_id'])) . '"';
        }
        else if ($row['target_type'] == 'url') {
          $output .= ' url="' . Strings::escapeEncodedXML($row['target_value']) . '"';
        }
        else if ($row['target_type'] == 'email') {
          $output .= ' email="' . Strings::escapeEncodedXML($row['target_value']) . '"';
        }
        if ($row['target'] != '') {
          $output .= ' target="' . Strings::escapeEncodedXML($row['target']) . '"';
        }
        if ($row['hidden']) {
          $output .= ' hidden="true"';
        }
        $output .= '>' . HierarchyService::hierarchyTraveller($id,$row['id'],$allowDisabled,$depth - 1) . '</item>';
      }
    }
    Database::free($result);
    return $output;
  }

  static function getTree($hierarchyId) {
    return HierarchyService::getTreeLevel(0,$hierarchyId);
  }

  private static function getTreeLevel($parent,$hierarchyId) {
    $sql = "select hierarchy_item.*,page.disabled,page.path,page.id as pageid from hierarchy_item" .
      " left join page on page.id = hierarchy_item.target_id and (hierarchy_item.target_type='page' or hierarchy_item.target_type='pageref')" .
      " where parent = @int(parent)" .
      " and hierarchy_id = @int(hierarchy)" .
      " order by `index`";
    $result = Database::select($sql, ['parent' => $parent, 'hierarchy' => $hierarchyId]);
    $out = [];
    while ($row = Database::next($result)) {
      $icon = Hierarchy::getItemIcon($row['target_type']);
      if ($row['target_type'] == 'page' && !$row['pageid']) {
        $icon = "common/warning";
      }
      $item = [
        'title' => $row['title'],
        'icon' => $icon,
        'id' => intval($row['id']),
        'target' => [
          'type' => $row['target_type'],
          'id' => intval($row['target_id'])
        ],
        'children' => HierarchyService::getTreeLevel($row['id'],$hierarchyId)
      ];
      $out[] = $item;
    }
    Database::free($result);
    return $out;
  }
}