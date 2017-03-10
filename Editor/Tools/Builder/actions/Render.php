<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Builder
 */
require_once '../../../Include/Private.php';

$path = Request::getString('path');

$data = [];
$sample = FileSystemService::join($path, 'sample.json');
$sample = FileSystemService::getFullPath($sample);
if (is_file($sample) && is_readable($sample)) {
  $data = Strings::fromJSON(file_get_contents($sample));
}
$design = 'humanise';

$inlineCSS = DesignService::getInlineCSS($design, true);

$twigTemplate = FileSystemService::join($path, 'template.twig');
if (file_exists(FileSystemService::getFullPath($twigTemplate))) {
  $rendered = RenderingService::applyTwigTemplate([
    'path' => $twigTemplate,
    'variables' => ['data' => $data]
  ]);
  echo '<!DOCTYPE html><html><head>' .
    '<style>' . $inlineCSS . '</style>' .
    '<link rel="stylesheet" href="../../../.././api/style/' . $design . '.css?development=true&' . time() . '"/>' .
    '<link rel="stylesheet" href="../../../../' . $path . '/inline.css?' . time() . '"/>' .
    '<link rel="stylesheet" href="../../../../' . $path . '/async.css?' . time() . '"/>' .
    '</head><body>' . $rendered . '</body></html>';
} else {
  echo 'Not found: ' . $twigTemplate;
}
?>