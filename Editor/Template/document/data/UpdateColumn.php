<?php
/**
 * @package OnlinePublisher
 * @subpackage Templates.Document
 */
require_once '../../../Include/Private.php';

DocumentTemplateEditor::updateColumn([
  'id' => Request::getInt('id'),
  'width' => Request::getString('width'),
  'left' => Request::getString('left'),
  'right' => Request::getString('right'),
  'top' => Request::getString('top'),
  'bottom' => Request::getString('bottom')
]);
?>