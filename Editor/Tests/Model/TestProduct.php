<?php
/**
 * @package OnlinePublisher
 * @subpackage Tests.Model
 */

if (!isset($GLOBALS['basePath'])) {
  header('HTTP/1.1 403 Forbidden');
  exit;
}

class TestProduct extends AbstractObjectTest {

  function TestProduct() {
    parent::AbstractObjectTest('product');
  }

  function testIt() {
    $type = new Producttype();
    $type->save();

    $product = new Product();
    $product->setProductTypeId($type->getId());
    $product->save();

    $product2 = new Product();
    $product2->setProductTypeId($type->getId());
    $product2->save();


    $product->updateAttributes([
      ['name' => 'weight', 'value' => '3 kg.'],
      ['name' => 'height', 'value' => '30 cm.']
    ]);

    $attr = $product->getAttributes();
    $this->assertEqual(2, count($attr));
    $this->assertEqual('weight', $attr[0]['name']);
    $this->assertEqual('3 kg.', $attr[0]['value']);

    $product->updatePrices([
      ['amount' => 20, 'type' => 'units', 'price' => 23.45, 'currency' => 'USD'],
      ['amount' => 2000, 'type' => 'grams', 'price' => 34587, 'currency' => 'DKR']
    ]);

    $prices = $product->getPrices();
    $this->assertEqual(2, count($prices));
    $this->assertEqual(20, $prices[0]['amount']);
    $this->assertEqual('units', $prices[0]['type']);
    $this->assertEqual(23.45, $prices[0]['price']);
    $this->assertEqual('USD', $prices[0]['currency']);

    $group1 = new Productgroup();
    $group1->save();

    $group2 = new Productgroup();
    $group2->save();

    $product->updateGroupIds([$group1->getId(), $group2->getId()]);
    $groupIds = $product->getGroupIds();
    $this->assertEqual(2, count($groupIds));

    $found = Product::find(['productgroup' => $group1->getId()]);
    $this->assertEqual(1, count($found['result']));

    $found = Product::find(['producttype' => $type->getId()]);
    $this->assertEqual(2, count($found['result']));

    $this->assertFalse($type->canRemove());

    $product->remove();
    $product2->remove();
    $type->remove();
    $group1->remove();
    $group2->remove();
  }
}
?>