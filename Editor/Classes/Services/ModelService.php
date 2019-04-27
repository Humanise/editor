<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes.Services
 */
if (!isset($GLOBALS['basePath'])) {
  header('HTTP/1.1 403 Forbidden');
  exit;
}

class ModelService {

  static function buildSelect($class) {
    $hierarchy = ClassService::_getHierarchy($class);
    return ModelService::_buildSelect($hierarchy);
  }

  static function _buildSelect($hierarchy) {
    $sqlColumns = [];
    $sqlTables = [];
    $sqlWhere = [];
    foreach ($hierarchy as $class) {
      if (!array_key_exists($class,Entity::$schema)) {
        continue;
      }
      $info = Entity::$schema[$class];
      $sqlTables[] = $info['table'];
      foreach ($info['properties'] as $field => $fieldInfo) {
        $column = isset($fieldInfo['column']) ? $fieldInfo['column'] : $field;
        if ($fieldInfo['type'] == 'datetime') {
          $sqlColumns[] = 'UNIX_TIMESTAMP(`' . $info['table'] . '`.`' . $column . '`) as ' . $info['table'] . '_' . $column;
        } else {
          $sqlColumns[] = '`' . $info['table'] . '`.`' . $column . '` as ' . $info['table'] . '_' . $column;
        }
      }
    }
    for ($i = 1; $i < count($sqlTables); $i++) {
      $sqlWhere[] = $sqlTables[$i - 1] . '.id = ' . $sqlTables[$i] . '.' . $sqlTables[$i - 1] . '_id';
    }
    $sqlWhere[] = '`' . $sqlTables[0] . '`.`id` = @int(id)';
    $sql = 'select ' . join($sqlColumns,',') . ' from `' . join($sqlTables,'`,`') . '`';
    if (count($sqlWhere) > 0) {
      $sql .= ' where ' . join($sqlWhere,' and ');
    }
    return $sql;
  }

  static function load($class,$id) {
    $hierarchy = ClassService::_getHierarchy($class);
    $sql = ModelService::_buildSelect($hierarchy);
    if ($row = Database::selectFirst($sql,['id' => $id])) {
      $obj = new $class();
      for ($i = 0; $i < count($hierarchy); $i++) {
        if (!isset(Entity::$schema[$hierarchy[$i]])) {
          continue;
        }
        $info = Entity::$schema[$hierarchy[$i]];
        foreach ($info['properties'] as $field => $fieldInfo) {
          $column = isset($fieldInfo['column']) ? $fieldInfo['column'] : $field;
          $method = 'set' . ucfirst($field);
          $raw = $row[$info['table'] . '_' . $column];
          if ($fieldInfo['type'] == 'int') {
            $raw = intval($raw);
          } else if ($fieldInfo['type'] == 'float') {
            $raw = floatval($raw);
          } else if ($fieldInfo['type'] == 'datetime') {
            $raw = $raw ? intval($raw) : null;
          } else if ($fieldInfo['type'] == 'boolean') {
            $raw = $raw == 1 ? true : false;
          }
          $obj->$method($raw);
        }
        if (isset($info['relations']) && is_array($info['relations'])) {
          foreach ($info['relations'] as $field => $info) {
            $setter = 'set' . ucfirst($field);
            $sql = "select @name(to) as id from @name(table) where @name(from) = @int(id)";
            $ids = Database::getIds($sql, [
              'id' => $id,
              'table' => $info['table'],
              'to' => $info['toColumn'],
              'from' => $info['fromColumn']
            ]);
            $obj->$setter($ids);
          }
        }

      }
      return $obj;
    }
    return null;
  }

  static function save($object) {
    $success = true;
    $class = get_class($object);
    $hierarchy = ClassService::_getHierarchy($class);
    if (!$object->getId()) {
      for ($i = 0; $i < count($hierarchy); $i++) {
        if (!isset(Entity::$schema[$hierarchy[$i]])) {
            continue;
        }
        $info = Entity::$schema[$hierarchy[$i]];
        // TODO Support inheritance
        $sql = 'insert into `' . $info['table'] . '` ';
        $sql .= '(' . SchemaService::buildSqlColumns($info,['id']) . ')';
        $sql .= ' values (' . SchemaService::buildSqlValues($object,$info,['id']) . ')';

        $id = Database::insert($sql);
        $object->setId($id);
      }
    } else {
      for ($i = 0; $i < count($hierarchy); $i++) {
        if (!isset(Entity::$schema[$hierarchy[$i]])) {
          continue;
        }
        $info = Entity::$schema[$hierarchy[$i]];
        // TODO Support inheritance
        $sql = 'update `' . $info['table'] . '` ';
        $sql .= 'set ' . SchemaService::buildSqlSetters($object,$info,['id']);
        $sql .= ' where id=@int(id)';

        $success = $success && Database::update($sql,['id' => $object->getId()]);
      }
    }
    return $success;
  }

  static function remove($object) {
    $success = true;
    $class = get_class($object);
    $hierarchy = ClassService::_getHierarchy($class);
    for ($i = 0; $i < count($hierarchy); $i++) {
      if (!isset(Entity::$schema[$hierarchy[$i]])) {
        continue;
      }
      // TODO Support inheritance
      $info = Entity::$schema[$hierarchy[$i]];
      $idColumn = isset($info['identity']) ? $info['identity'] : 'id';
      // TODO return if successfull
      $sql = 'delete from @name(table) where @name(column) = @int(id)';
      $params = ['id' => $object->getId(), 'table' => $info['table'], 'column' => $idColumn];
      $success = $success && Database::delete($sql, $params) > 0;
    }
    return $success;
  }
}