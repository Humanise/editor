<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.System
 */
require_once '../../../Include/Private.php';

$con = Database::getConnection();
$status = mysqli_stat($con);
$server = mysqli_get_server_info($con);
$host = mysqli_get_host_info($con);
$protocol = mysqli_get_proto_info($con);
$client = mysqli_get_client_info();

$writer = new ListWriter();

$writer->startList()->
  startHeaders()->
    header(['title' => ['Property', 'da' => 'Egenskab'], 'width' => 30])->
    header(['title' => ['Value', 'da' => 'Værdi'], 'width' => 70])->
  endHeaders()->
  startRow()->
    cell('Server')->cell($server)->
  endRow()->
  startRow()->
    cell('Client')->cell($client)->
  endRow()->
  startRow()->
    cell('Host')->cell($host)->
  endRow()->
  startRow()->
    cell('Protocol')->cell($protocol)->
  endRow()->
  startRow()->
    cell('Status')->cell($status)->
  endRow()->
endList();
?>