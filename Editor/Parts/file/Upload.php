<?php
/**
 * @package OnlinePublisher
 * @subpackage Parts.Movie
 */
require_once '../../Include/Private.php';

$response = FileService::createUploadedFile();

if ($response->getSuccess()) {
	MoviePartController::setLatestUploadId($response->getObject()->getId());
	In2iGui::respondUploadSuccess();
} else {
	MoviePartController::setLatestUploadId(null);
	Log::debug('Unable to upload file');
	In2iGui::respondUploadFailure();
}
?>