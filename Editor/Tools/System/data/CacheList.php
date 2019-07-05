<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.System
 */
require_once '../../../Include/Private.php';

$writer = new ListWriter();

$writer->startList();
$writer->startHeaders();
$writer->header(['title' => 'Type', 'width' => 40]);
$writer->header(['title' => 'Cache']);
$writer->header(['width' => 1]);
$writer->endHeaders();

$cachedPages = CacheService::getNumberOfCachedPages();
$imageCache = CacheService::getImageCacheInfo();
$tempCache = CacheService::getTempCacheInfo();
$urlCache = CacheService::getUrlCacheInfo();

$writer->startRow();
$writer->startCell(['icon' => 'common/page'])->text('Sider')->endCell();
$writer->startCell()->text($cachedPages . ($cachedPages == 1 ? ' side' : ' sider'))->endCell();
$writer->startCell()->button(['text' => 'Ryd', 'data' => ['type' => 'pages']])->endCell();
$writer->endRow();

$writer->startRow();
$writer->startCell(['icon' => 'common/image'])->text('Billeder')->endCell();
$writer->startCell()->text($imageCache['count'] . ' billeder (' . UI::formatBytes($imageCache['size']) . ')')->endCell();
$writer->startCell()->button(['text' => 'Ryd', 'data' => ['type' => 'images']])->endCell();
$writer->endRow();

$writer->startRow();
$writer->startCell(['icon' => 'file/generic'])->text('Midlertidige filer')->endCell();
$writer->startCell()->text($tempCache['count'] . ' filer (' . UI::formatBytes($tempCache['size']) . ')')->endCell();
$writer->startCell()->button(['text' => 'Ryd', 'data' => ['type' => 'temp']])->endCell();
$writer->endRow();

$writer->startRow();
$writer->startCell(['icon' => 'common/internet'])->text('Internet adresser')->endCell();
$writer->startCell()->text($urlCache['count'] . ' filer (' . UI::formatBytes($urlCache['size']) . ')')->endCell();
$writer->startCell()->button(['text' => 'Ryd', 'data' => ['type' => 'urls']])->endCell();
$writer->endRow();


$writer->endList();
?>