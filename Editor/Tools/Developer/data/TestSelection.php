<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Developer
 */
require_once '../../../Include/Private.php';

$writer = new ItemsWriter();
$writer->startItems();

$groups = TestService::getGroups();
$writer->startItem(['title' => ['All tests', 'da' => 'Alle test'], 'value' => 'alltests', 'kind' => 'alltests', 'icon' => 'file/generic'])->endItem();
foreach ($groups as $group) {
  $writer->startItem(['title' => $group, 'value' => $group, 'kind' => 'testgroup', 'icon' => 'common/folder']);
  $tests = TestService::getTestsInGroup($group);
  foreach ($tests as $test) {
    $test = str_replace('.php','',$test);
    $writer->startItem(['title' => $test, 'value' => $group . '/' . $test, 'kind' => 'test', 'icon' => 'file/generic'])->endItem();
  }
  $writer->endItem();
}

$writer->endItems();
?>