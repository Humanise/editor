<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes.Interface
 */

if (!isset($GLOBALS['basePath'])) {
  header('HTTP/1.1 403 Forbidden');
  exit;
}
class DiagramData {

  var $nodes = [];
  var $edges = [];

  function addNode($node) {
      $this->nodes[] = $node;
  }

  function addEdge($edge=null) {
    if ($edge == null) {
      $edge = new DiagramEdge();
      $this->edges[] = $edge;
      return $edge;
    } else {
        $this->edges[] = $edge;
    }
  }

}
?>