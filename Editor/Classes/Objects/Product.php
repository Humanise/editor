<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes
 */
if (!isset($GLOBALS['basePath'])) {
  header('HTTP/1.1 403 Forbidden');
  exit;
}

Entity::$schema['Product'] = [
  'table' => 'product',
  'properties' => [
      'number' => ['type' => 'string'],
      'productTypeId' => ['type' => 'int', 'column' => 'producttype_id'],
      'imageId' => ['type' => 'int', 'column' => 'image_id'],
      'allowOffer' => ['type' => 'boolean', 'column' => 'allow_offer']
    ]
];

class Product extends Object {
  var $number;
  var $productTypeId;
  var $imageId;
  var $allowOffer;

  function Product() {
    parent::Object('product');
    $this->productTypeId = 0;
    $this->imageId = 0;
  }

  static function load($id) {
    return Object::get($id,'product');
  }

  function setNumber($number) {
    $this->number = $number;
  }

  function getNumber() {
    return $this->number;
  }

  function setProductTypeId($id) {
    $this->productTypeId = $id;
  }

  function getProductTypeId() {
    return $this->productTypeId;
  }

  function setImageId($id) {
    $this->imageId = $id;
  }

  function getImageId() {
    return $this->imageId;
  }

  function setAllowOffer($allow) {
    $this->allowOffer = $allow;
  }

  function getAllowOffer() {
    return $this->allowOffer;
  }

  function isAllowOffer() {
    return $this->allowOffer;
  }

  function sub_publish() {
    $data = '<product xmlns="' . parent::_buildnamespace('1.0') . '">' .
    '<allow-offer>' . ($this->allowOffer ? 'true' : 'false') . '</allow-offer>' .
    '<number>' . Strings::escapeEncodedXML($this->number) . '</number>' .
    '<attributes>';
    $attributes = $this->getAttributes();
    foreach ($attributes as $attribute) {
      $data .= '<attribute name="' . Strings::escapeEncodedXML($attribute['name']) . '">' .
      Strings::escapeXMLBreak($attribute['value'],'<break/>') .
      '</attribute>';
    }
    $data .= '</attributes>' .
    '<prices>';
    $prices = $this->getPrices();
    foreach ($prices as $price) {
      $data .= '<price' .
        ' amount="' . Strings::escapeEncodedXML($price['amount']) . '"' .
        ' type="' . Strings::escapeEncodedXML($price['type']) . '"' .
        ' price="' . Strings::escapeEncodedXML($price['price']) . '"' .
        ' currency="' . Strings::escapeEncodedXML($price['currency']) . '"' .
        '/>';
    }
    $data .= '</prices>';
    $data .= ObjectService::getObjectData($this->imageId);
    $data .= '</product>';
    return $data;
  }

  function removeMore() {
    $sql = "delete from productgroup_product where product_id=@int(id)";
    Database::delete($sql, ['id' => $this->id]);
    $sql = "delete from productattribute where product_id=@int(id)";
    Database::delete($sql, ['id' => $this->id]);
    $sql = "delete from productprice where product_id=@int(id)";
    Database::delete($sql, ['id' => $this->id]);
    $sql = "delete from productoffer where product_id=@int(id)";
    Database::delete($sql, ['id' => $this->id]);
  }


  /////////////////////////////// Special ///////////////////////

  function getAttributes() {
    $atts = [];
    $sql = "select * from productattribute where product_id=@int(id) order by `index`";
    $result = Database::select($sql, ['id' => $this->id]);
    while ($row = Database::next($result)) {
      $atts[] = ['name' => $row['name'], 'value' => $row['value']];
    }
    Database::free($result);
    return $atts;
  }

  function getPrices() {
    $atts = [];
    $sql = "select * from productprice where product_id=@int(id) order by `index`";
    $result = Database::select($sql, ['id' => $this->id]);
    while ($row = Database::next($result)) {
      $atts[] = ['amount' => $row['amount'], 'type' => $row['type'], 'price' => floatval($row['price']), 'currency' => $row['currency']];
    }
    Database::free($result);
    return $atts;
  }

  function updateGroupIds($ids) {
    $ids = ObjectService::getValidIds($ids);
    $sql = "delete from productgroup_product where product_id=@int(id)";
    Database::delete($sql, ['id' => $this->id]);
    foreach ($ids as $id) {
      $sql = "insert into productgroup_product (productgroup_id,product_id) values (@int(groupId),@int(productId))";
      Database::insert($sql, ['groupId' => $id, 'productId' => $this->id]);
    }
  }

  function getGroupIds() {
    $sql = "select productgroup_id as id from productgroup_product where product_id=@int(id)";
    return Database::getIds($sql, ['id' => $this->id]);
  }

  function updateAttributes($attributes) {
    $sql = "delete from productattribute where product_id=@int(id)";
    Database::delete($sql, ['id' => $this->id]);
    for ($i = 0; $i < count($attributes); $i++) {
      $att = $attributes[$i];
      $sql = "insert into productattribute (product_id,`name`,`value`,`index`) values (@int(id), @text(name), @text(value), @int(index))";
      Database::insert($sql, ['id' => $this->id, 'name' => $att['name'], 'value' => $att['value'], 'index' => $i]);
    }
  }

  function updatePrices($prices) {
    $sql = "delete from productprice where product_id = @int(id)";
    Database::delete($sql, ['id' => $this->id]);
    for ($i = 0; $i < count($prices); $i++) {
      $att = $prices[$i];
      $sql = "insert into productprice (product_id,`amount`,`type`,price,currency,`index`) values (@int(id),@float(amount),@text(type),@float(price),@text(currency),@int(index))";
      Database::insert($sql, [
        'id' => $this->id,
        'amount' => $att['amount'],
        'type' => $att['type'],
        'price' => $att['price'],
        'currency' => $att['currency'],
        'index' => $i
      ]);
    }
  }

  static function find($query = []) {
    $parts = [];
    $parts['columns'] = 'object.id';
    $parts['tables'] = 'product,object';
    $parts['limits'] = 'object.id=product.object_id';
    $parts['ordering'] = 'object.title';
    $parameters = [];
    if (isset($query['productgroup'])) {
      $parts['tables'] .= ',productgroup_product';
    }
    if (isset($query['producttype'])) {
      $parts['limits'] .= " and product.producttype_id = @int(producttype)";
      $parameters['producttype'] = $query['producttype'];
    }
    if (isset($query['productgroup'])) {
      $parts['limits'] .= " and productgroup_product.product_id = object.id and productgroup_product.productgroup_id = @int(productgroup)";
      $parameters['productgroup'] = $query['productgroup'];
    }
    $list = ObjectService::_find($parts, $query, $parameters);
    $list['result'] = [];
    foreach ($list['rows'] as $row) {
      $list['result'][] = Product::load($row['id']);
    }
    return $list;
  }

  /////////////////////////// GUI /////////////////////////

  function getIcon() {
    return 'common/product';
  }
}
?>