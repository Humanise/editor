<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes.Services
 */
if (!isset($GLOBALS['basePath'])) {
  header('HTTP/1.1 403 Forbidden');
  exit;
}

class FrameService {

  static function save($frame) {
    $hierarchy = Hierarchy::load($frame->getHierarchyId());
    if (!$hierarchy) {
      return false;
    }
    $sql = [
      'table' => 'frame',
      'values' => [
        'title' => ['text' => $frame->getTitle()],
        'name' => ['text' => $frame->getName()],
        'bottomtext' => ['text' => $frame->getBottomText()],
        'hierarchy_id' => ['int' => $frame->getHierarchyId()],
        'changed' => ['datetime' => time()],
        'searchenabled' => ['boolean' => $frame->getSearchEnabled()],
        'searchpage_id' => ['int' => $frame->getSearchPageId()],
        'userstatusenabled' => ['boolean' => $frame->getUserStatusEnabled()],
        'userstatuspage_id' => ['int' => $frame->getLoginPageId()]
      ],
      'where' => [ 'id' => $frame->getId()]
    ];
    if ($frame->getId() > 0) {
      Database::update($sql);
    } else {
      $frame->id = Database::insert($sql);
    }
    return true;
  }

  static function getLinks($frame,$position) {
    $links = [];
    $sql = "select frame_link.*,page.title as page_title,object.title as object_title from frame_link left join page on page.id=`frame_link`.`target_id` left join object on object.id=`frame_link`.`target_id` where frame_link.frame_id = @id and frame_link.position = @text(position) order by frame_link.`index`";
    $result = Database::select($sql, ['id' => $frame->getId(), 'position' => $position]);
    while ($row = Database::next($result)) {
      $link = [
        'text' => $row['title'],
        'kind' => $row['target_type'],
      ];
      if ($row['target_type'] == 'page') {
        $link['value'] = $row['target_id'];
        $link['info'] = $row['page_title'];
        $link['icon'] = 'common/page';
      } else if ($row['target_type'] == 'file') {
        $link['value'] = $row['target_id'];
        $link['info'] = $row['object_title'];
        $link['icon'] = 'monochrome/file';
      } else if ($row['target_type'] == 'url') {
        $link['value'] = $row['target_value'];
        $link['info'] = $row['target_value'];
        $link['icon'] = 'monochrome/globe';
      } else if ($row['target_type'] == 'email') {
        $link['value'] = $row['target_value'];
        $link['info'] = $row['target_value'];
        $link['icon'] = 'monochrome/email';
      }
      $links[] = $link;
    }
    return $links;
  }

  static function replaceLinks($frame,$topLinks,$bottomLinks) {
    if (!is_object($frame)) {
      Log::debug('No frame provided');
      return;
    }
    $sql = "delete from frame_link where frame_id = @id";
    Database::delete($sql, $frame->getId());
    FrameService::_createLinks($frame,$topLinks,'top');
    FrameService::_createLinks($frame,$bottomLinks,'bottom');
  }

  static function _createLinks($frame,$links,$position) {
    $index = 0;
    foreach ($links as $link) {
      $id = 0;
      $value = null;
      if ($link->kind == 'page') {
        $type = 'page';
        $id = $link->value;
      } else if ($link->kind == 'file') {
        $type = 'file';
        $id = $link->value;
      } else if ($link->kind == 'url') {
        $type = 'url';
        $value = $link->value;
      } else if ($link->kind == 'email') {
        $type = 'email';
        $value = $link->value;
      }
      if ($type) {
        $sql = [
          'table' => 'frame_link',
          'values' => [
            'frame_id' => ['int' => $frame->getId()],
            'position' => ['text' => $position],
            'title' => ['text' => $link->text],
            'target_type' => ['text' => $type],
            'target_id' => ['int' => $id],
            'target_value' => ['text' => $value],
            'index' => ['int' => $index]
          ]
        ];
        Database::insert($sql);
      }
      $index++;
    }
  }

  static function load($id) {
    return ModelService::load('Frame',$id);
  }

  static function canRemove($frame) {
    $sql = "select count(id) as num from page where frame_id = @id";
    if ($row = Database::selectFirst($sql, $frame->getId())) {
      return $row['num'] == 0;
    }
    return true;
  }

  static function remove($frame) {
    if ($frame->getId() > 0 && FrameService::canRemove($frame)) {
      $sql = "delete from frame where id = @id";
      Database::delete($sql, $frame->getId());
      $sql = "delete from frame_link where frame_id = @id";
      Database::delete($sql, $frame->getId());

      $sql = "select * from frame_newsblock where frame_id = @id";
      $result = Database::select($sql, $frame->getId());
      while ($row = Database::next($result)) {
        $sql = 'delete from frame_newsblock_newsgroup where frame_newsblock_id = @id';
        Database::delete($sql, $row['id']);
      }
      Database::free($result);

      $sql = "delete from frame_newsblock where frame_id = @id";
      Database::delete($sql, $frame->getId());

      return true;
    }
    return false;
  }

  static function search() {
    $list = [];
    $sql = "select id,title,bottomtext,name,hierarchy_id,UNIX_TIMESTAMP(changed) as changed from frame order by name";
    $result = Database::select($sql);
    while ($row = Database::next($result)) {
      $frame = new Frame();
      $frame->setId($row['id']);
      $frame->setTitle($row['title']);
      $frame->setName($row['name']);
      $frame->setBottomText($row['bottomtext']);
      $frame->setHierarchyId($row['hierarchy_id']);
      $frame->setChanged(intval($row['changed']));
      $list[] = $frame;
    }
    Database::free($result);
    return $list;
  }

  static function getNewsBlocks($frame) {
    $sql = "select id,`index`,title,sortby,sortdir,maxitems,timetype,timecount,UNIX_TIMESTAMP(startdate) as startdate,UNIX_TIMESTAMP(enddate) as enddate from frame_newsblock where frame_id = @id order by `index`";
    $blocks = Database::selectAll($sql, $frame->getId());
    foreach ($blocks as &$block) {
      $sql = "select newsgroup_id from frame_newsblock_newsgroup where frame_newsblock_id = @id";
      $block['groups'] = Database::selectArray($sql, $block['id']);
    }
    return $blocks;
  }

  static function replaceNewsBlocks($frame,$blocks) {

    if (!is_object($frame)) {
      Log::debug('No frame provided');
      return;
    }
    // Delete existing blocks
    $sql = "delete from frame_newsblock where frame_id = @id";
    Database::delete($sql, $frame->getId());
    // Delete unassociated news groups
    $sql = "delete from frame_newsblock_newsgroup where frame_newsblock_id not in (select id from frame_newsblock)";
    Database::delete($sql);

    if (!is_array($blocks)) {
      return;
    }

    for ($i = 0; $i < count($blocks); $i++) {
      $block = $blocks[$i];
      $sql = [
        'table' => 'frame_newsblock',
        'values' => [
          'frame_id' => ['int' => $frame->getId()],
          'title' => ['text' => $block->title],
          'sortby' => ['text' => $block->sortby],
          'sortdir' => ['text' => $block->sortdir],
          'maxitems' => ['int' => $block->maxitems],
          'timetype' => ['text' => $block->timetype],
          'timecount' => ['int' => $block->timecount],
          'startdate' => ['datetime' => $block->startdate],
          'enddate' => ['datetime' => $block->enddate]
        ]
      ];
      Database::insert($sql);
    }
  }
}
?>