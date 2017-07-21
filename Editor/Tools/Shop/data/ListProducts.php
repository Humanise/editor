<?php
/**
 * @package OnlinePublisher
 * @subpackage Shop
 */
require_once '../../../Include/Private.php';

$producttype = Request::getInt('producttype');
$productgroup = Request::getInt('productgroup');
$windowSize = Request::getInt('windowSize',30);
$windowNumber = Request::getInt('windowNumber',1);

$query = ['windowSize' => $windowSize, 'windowNumber' => $windowNumber];
if ($producttype>0) {
  $query['producttype'] = $producttype;
}
if ($productgroup>0) {
  $query['productgroup'] = $productgroup;
}

$list = Product::find($query);
$products = $list['result'];

$writer = new ListWriter();

$writer->startList()->
  window(['total'=>$list['total'], 'size'=>$list['windowSize'], 'page'=>$list['windowPage']])->
  startHeaders()->
    header(['title'=>['Product', 'da'=>'Produkt'], 'width'=>40])->
    header(['title'=>['Number', 'da'=>'Nummer'], 'width'=>30])->
    header(['title'=>'Type', 'width'=>30])->
  endHeaders();

foreach ($products as $product) {
  $type = Producttype::load($product->getProductTypeId());
  $writer->
  startRow(['id'=>$product->getId(), 'kind'=>$product->getType(), 'icon'=>'common/product', 'title'=>$product->getTitle()])->
    startCell(['icon'=>'common/product'])->text($product->getTitle())->endCell()->
    startCell()->text($product->getNumber())->endCell()->
    startCell()->text($type ? $type->getTitle() : '?')->endCell()->
  endRow();
}
$writer->endList();
?>