<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Central
 */
require_once '../../../Include/Private.php';

$writer = new ListWriter();

$writer->startList(array('unicode'=>true));
$writer->startHeaders();
$writer->header(array('title'=>'Titel'));
$writer->header(array('title'=>'Adresse'));
$writer->header(array('title'=>'Version'));
$writer->header(array('title'=>'Værktøjer'));
$writer->header(array('title'=>'Skabeloner'));
$writer->header(array('title'=>'E-mail'));
$writer->endHeaders();

$objects = Query::after('remotepublisher')->orderBy('title')->get();
foreach ($objects as $site) {
	$data = RemoteDataService::getRemoteData($site->getUrl().'services/info/json/');
	$templates = 'Unknown';
	$email = 'Unknown';
	if ($data->isHasData()) {
		$str = file_get_contents($data->getFile());
		$obj = StringUtils::fromJSON($str);
		$version = DateUtils::formatLongDate($obj->date);
	} else {
		$version = 'Unknown';
	}
	$writer->startRow(array('kind'=>'remotepublisher','id'=>$site->getId()))->
		startCell(array('wrap'=>false))->text($site->getTitle())->endCell()->
		startCell()->text($site->getUrl())->endCell()->
		startCell(array('wrap'=>false))->text($version)->endCell()->
		startCell(array('wrap'=>false));
		writeTools($writer,$obj);
		$writer->endCell()->
		startCell(array('wrap'=>false));
		writeTemplates($writer,$obj);
		$writer->endCell()->
		startCell(array('wrap'=>false));
		writeEmail($writer,$obj);
		$writer->endCell()->
	endRow();
}
$writer->endList();

function writeTemplates($writer,$obj) {
	if ($obj && property_exists($obj,'templates')) {
		$installed = $obj->templates->installed;
		$used = $obj->templates->used;
		foreach ($installed as $template) {
			$writer->object(array('icon'=>in_array($template,$used) ? 'common/success' : 'monochrome/round_question','text'=>$template));
		} 
	} else {
		$writer->object(array('icon'=>'monochrome/warning','text'=>'Not available'));		
	}
}

function writeTools($writer,$obj) {
	if ($obj && property_exists($obj,'tools')) {
		$installed = $obj->tools->installed;
		if (!$installed || !is_array($installed)) {
			$writer->text('not an array');
			return;
		}
		Log::debug($installed);
		foreach ($installed as $tool) {
			$writer->object(array('icon'=>'common/success','text'=>$tool));
		}
	} else {
		$writer->object(array('icon'=>'monochrome/warning','text'=>'Not available'));		
	}
}

function writeEmail($writer,$obj) {
	if ($obj && property_exists($obj,'email')) {
		$email = $obj->email;
		if ($email->enabled) {
			$writer->object(array('icon'=>'common/success','text'=>'Enabled'));
		} else {
			$writer->object(array('icon'=>'common/stop','text'=>'Not enabled'));
		}
		$writer->object(array('icon'=>$email->server ? 'common/success' : 'common/stop','text'=>'Server'));
		$writer->object(array('icon'=>$email->username ? 'common/success' : 'common/stop','text'=>'Username'));
		$writer->object(array('icon'=>$email->password ? 'common/success' : 'common/stop','text'=>'Password'));
		$writer->object(array('icon'=>$email->standardEmail ? 'common/success' : 'common/stop','text'=>'Standard email'));
		$writer->object(array('icon'=>$email->standardName ? 'common/success' : 'common/stop','text'=>'Standard name'));
		$writer->object(array('icon'=>$email->feedbackEmail ? 'common/success' : 'common/stop','text'=>'Feedback email'));
		$writer->object(array('icon'=>$email->feedbackName ? 'common/success' : 'common/stop','text'=>'Feedback name'));
	} else {
		$writer->object(array('icon'=>'monochrome/warning','text'=>'Not available'));
	}
}