<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes.Part
 */
if (!isset($GLOBALS['basePath'])) {
  header('HTTP/1.1 403 Forbidden');
  exit;
}

class FilePartController extends PartController
{
  function __construct() {
    parent::__construct('file');
  }

  static function createPart() {
    $part = new FilePart();
    $part->setFileId(FileService::getLatestFileId());
    $part->save();
    return $part;
  }

  function display($part,$context) {
    return $this->render($part,$context);
  }

  function editor($part,$context) {
    return '<div id="part_file_container">' . $this->render($part,$context) . '</div>' .

    $this->buildHiddenFields([
      'fileId' => $part->getFileId(),
      'text' => $part->getText()]) .
    '<script src="' . ConfigurationService::getBaseUrl() . 'Editor/Parts/file/script.js" type="text/javascript" charset="utf-8"></script>';
  }

  function getFromRequest($id) {
    $part = FilePart::load($id);
    $part->setFileId(Request::getInt('fileId'));
    $part->setText(Request::getString('text'));
    return $part;
  }

  function buildSub($part,$context) {
    $xml = '<file xmlns="' . $this->getNamespace() . '">';
    $sql = "select object.data,file.type from object,file where file.object_id = object.id and object.id = @id";
    if ($row = Database::selectFirst($sql, $part->getFileId())) {
      $xml .= '<info type="' . FileService::mimeTypeToLabel($row['type']) . '"/>';
      if (Strings::isNotBlank($part->getText())) {
        $xml .= '<text>' . Strings::escapeEncodedXML($part->getText()) . '</text>';
      }
      $xml .= $row['data'];
    }
    $xml .= '</file>';
    return $xml;
  }

  function importSub($node,$part) {
    if ($object = DOMUtils::getFirstDescendant($node,'object')) {
      if ($id = intval($object->getAttribute('id'))) {
        $part->setFileId($id);
      }
    }
    if ($text = DOMUtils::getFirstDescendant($node,'text')) {
      $part->setText(DOMUtils::getText($text));
    }
  }


  function getToolbars() {
    return [
      UI::translate(['File', 'da' => 'Fil']) => '
      <icon icon="common/new" text="{Add file; da:Tilføj fil}" name="addFile"/>
      <icon icon="common/search" text="{Select file; da:Vælg fil}" name="chooseFile"/>
      <divider/>
      <item label="{Text; da:Tekst}">
        <text-input name="text" width="200"/>
      </item>
    '
    ];
  }


  function getEditorUI($part,$context) {
    return '
    <window title="{Add file; da:Tilføj fil}" name="fileUploadWindow" width="300" padding="10">
      <upload name="fileUpload" url="../../Parts/file/Upload.php" widget="upload">
        <placeholder
          title="{Select a file on your computer; da:Vælg en fil på din computer...}"
          text="{The file size can at most be; da:Filens størrelse må højest være} ' . UI::formatBytes(FileSystemService::getMaxUploadSize()) . '."/>
      </upload>
      <buttons align="center" top="10">
        <button name="cancelUpload" text="{Close; da:Luk}"/>
        <button name="upload" text="{Select file...; da:Vælg fil...}" highlighted="true"/>
      </buttons>
    </window>
    ';
  }

  function setLatestUploadId($id) {
    $_SESSION['part.file.latest_upload_id'] = $id;
  }

  function getLatestUploadId() {
    return $_SESSION['part.file.latest_upload_id'];
  }
}
?>