<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Optimization
 */
require_once '../../../Include/Private.php';

$writer = new ListWriter();

$writer->startList()->
	startHeaders()->
		header(['title' => ['Profile', 'da' => 'Profil'], 'width' => '30'])->
		header(['title' => ['Address', 'da' => 'Adresse']])->
		header(['width' => 1 ])->
	endHeaders();

$settings = OptimizationService::getSettings();

if (is_object($settings) && is_array($settings->profiles)) {
	foreach ($settings->profiles as $profile) {

		$writer->startRow()->
			startCell(['icon'=>'common/page'])->text($profile->name)->endCell()->
			startCell()->
				text($profile->url)->
				startIcons()->
					icon(['revealing' => true, 'icon' => 'monochrome/round_arrow_right', 'action' => true,
            'data' => ['action' => 'visit', 'url' => $profile->url]
          ])->
				endIcons()->
			endCell()->
			startCell()->
				startIcons()->
					icon(['revealing' => true, 'icon' => 'monochrome/delete', 'action' => true,
            'data' => ['action' => 'delete', 'url' => $profile->url]
          ])->
				endIcons()->
			endCell()->
		endRow();
	}
}
$writer->endList();