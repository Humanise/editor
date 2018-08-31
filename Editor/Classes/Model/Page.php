<?php
if (!isset($GLOBALS['basePath'])) {
  header('HTTP/1.1 403 Forbidden');
  exit;
}

Entity::$schema['Page'] = [
  'table' => 'page',
  'properties' => [
    'id' => ['type' => 'int'],
    'title' => ['type' => 'string'],
    'description' => ['type' => 'string'],
    'keywords' => ['type' => 'string'],
    'templateId' => ['type' => 'int', 'column' => 'template_id', 'relation' => ['class' => 'Template', 'property' => 'id']],
    'frameId' => ['type' => 'int', 'column' => 'frame_id', 'relation' => ['class' => 'Frame', 'property' => 'id']],
    'designId' => ['type' => 'int', 'column' => 'design_id', 'relation' => ['class' => 'Design', 'property' => 'id']],
    'nextPage' => ['type' => 'int', 'column' => 'next_page', 'relation' => ['class' => 'Page', 'property' => 'id']],
    'previousPage' => ['type' => 'int', 'column' => 'previous_page', 'relation' => ['class' => 'Page', 'property' => 'id']],
    'language' => ['type' => 'string'],
    'data' => ['type' => 'string'],
    'index' => ['type' => 'string'],
    'path' => ['type' => 'string'],
    'name' => ['type' => 'string'],
    'searchable' => ['type' => 'boolean'],
    'dynamic' => ['type' => 'boolean'],
    'secure' => ['type' => 'boolean'],
    'disabled' => ['type' => 'boolean'],
    'created' => ['type' => 'datetime'],
    'changed' => ['type' => 'datetime'],
    'published' => ['type' => 'datetime']
  ]
];
class Page extends Entity {

  var $title;
  var $description;
  var $keywords;
  var $templateId;
  var $frameId;
  var $designId;
  var $language;
  var $searchable;
  var $dynamic;
  var $secure;
  var $disabled;
  var $created;
  var $changed;
  var $published;
  var $data;
  var $index;
  var $name;
  var $path;
  var $nextPage;
  var $previousPage;

  function setName($name) {
    $this->name = $name;
  }

  function getName() {
    return $this->name;
  }

  function setPath($path) {
    $this->path = $path;
  }

  function getPath() {
    return $this->path;
  }

  function setTitle($title) {
    $this->title = $title;
  }

  function getTitle() {
    return $this->title;
  }

  function setDescription($description) {
    $this->description = $description;
  }

  function getDescription() {
    return $this->description;
  }

  function setKeywords($keywords) {
    $this->keywords = $keywords;
  }

  function getKeywords() {
    return $this->keywords;
  }

  function setData($data) {
    $this->data = $data;
  }

  function getData() {
    return $this->data;
  }

  function setIndex($index) {
    $this->index = $index;
  }

  function getIndex() {
    return $this->index;
  }

  function setTemplateId($templateId) {
    $this->templateId = $templateId;
  }

  function getTemplateId() {
    return $this->templateId;
  }

  function setFrameId($frameId) {
    $this->frameId = $frameId;
  }

  function getFrameId() {
    return $this->frameId;
  }

  function setDesignId($designId) {
    $this->designId = $designId;
  }

  function getDesignId() {
    return $this->designId;
  }

  function setLanguage($language) {
    $this->language = $language;
  }

  function getLanguage() {
    return $this->language;
  }

  function setSearchable($searchable) {
    $this->searchable = $searchable;
  }

  function getSearchable() {
    return $this->searchable;
  }

  function setSecure($secure) {
    $this->secure = $secure;
  }

  function getSecure() {
    return $this->secure;
  }

  function setDynamic($dynamic) {
    $this->dynamic = $dynamic;
  }

  function getDynamic() {
    return $this->dynamic;
  }

  function setDisabled($disabled) {
    $this->disabled = $disabled;
  }

  function getDisabled() {
    return $this->disabled;
  }

  function setCreated($created) {
    $this->created = $created;
  }

  function getCreated() {
    return $this->created;
  }

  function setChanged($changed) {
    $this->changed = $changed;
  }

  function getChanged() {
    return $this->changed;
  }

  function setPublished($published) {
    $this->published = $published;
  }

  function getPublished() {
    return $this->published;
  }

  function setNextPage($nextPage) {
    $this->nextPage = $nextPage;
  }

  function getNextPage() {
    return $this->nextPage;
  }

  function setPreviousPage($previousPage) {
    $this->previousPage = $previousPage;
  }

  function getPreviousPage() {
    return $this->previousPage;
  }


/////////////////////////// Special ///////////////////////////

  /**
   * WARNING: Only for persistent pages
   */
  function getTemplateUnique() {
    if ($template = Template::load($this->templateId)) {
      return $template->getUnique();
    }
    return null;
  }

  function getIcon() {
    return 'common/page';
  }

///////////////////////// Persistence /////////////////////////

  static function load($id) {
    return PageService::load($id);
  }

  function create() {
    return PageService::create($this);
  }

  function save() {
    return PageService::save($this);
  }

  function publish() {
    PublishingService::publishPage($this->id);
  }

  function remove() {
    $this->delete();
  }

  function delete() {
    return PageService::delete($this);
  }
}
?>