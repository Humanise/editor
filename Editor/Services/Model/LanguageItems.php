<?php
/**
 * @package OnlinePublisher
 * @subpackage Services.Model
 */
require_once '../../Include/Private.php';

$writer = new ItemsWriter();

$writer->startItems()->
  item(['value'=>'', 'text'=>['None', 'da'=>'Intet']])->
  item(['value'=>'EN', 'text'=>['English', 'da'=>'Engelsk']])->
  item(['value'=>'DA', 'text'=>['Danish', 'da'=>'Dansk']])->
  item(['value'=>'DE', 'text'=>['German', 'da'=>'Tysk']])->
  item(['value'=>'ES', 'text'=>['Spanish', 'da'=>'Spansk']])->
  item(['value'=>'FR', 'text'=>['French', 'da'=>'Fransk']])->
endItems();
?>