<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes.Modules.Review
 */

if (!isset($GLOBALS['basePath'])) {
  header('HTTP/1.1 403 Forbidden');
  exit;
}
class ReviewService {

  static function search($query) {
    $sql = '';
    $unreviewed = isset($query['unreviewed']) && !!$query['unreviewed'];
    $accepted = isset($query['accepted']) && !!$query['accepted'];
    $rejected = isset($query['rejected']) && !!$query['rejected'];
    if ($unreviewed) {
      $sql = "select page.id as page_id, page.title as page_title,'' as user_title, null as date, -1 as accepted
        from page where page.id not in (select relation.from_object_id from relation,review
        where relation.to_type='object' and relation.to_object_id=review.object_id)
        order by date desc,page_title";
    }
    if ($accepted || $rejected) {
      if ($sql) {
        $sql .= ' union ';
      }
      $sql .= "select page.id as page_id,page.title as page_title,user.title as user_title,UNIX_TIMESTAMP(review.date) as date,review.accepted
        from page,relation as page_review,relation as review_user,review,object as user
        where page_review.from_type='page' and page_review.from_object_id=page.id
        and page_review.to_type='object' and page_review.to_object_id=review.object_id
        and review_user.from_type='object' and review_user.from_object_id=review.object_id
        and review_user.to_type='object' and review_user.to_object_id=user.id";
      if ($accepted && !$rejected) {
        $sql .= ' and review.accepted=1';
      }
      if (!$accepted && $rejected) {
        $sql .= ' and review.accepted=0';
      }
      if (isset($query['span'])) {
        if ($query['span'] == 'day') {
          $sql .= ' and review.date < @datetime(day)';
        } else if ($query['span'] == 'week') {
          $sql .= ' and review.date < @datetime(week)';
        }
      }

    }
    $list = [];
    $result = Database::select($sql, [
      'day' => Dates::addDays(time(),-1),
      'week' => Dates::addDays(time(),-7)
    ]);
    while ($row = Database::next($result)) {
      $combo = new ReviewCombo();
      $combo->setPageId($row['page_id']);
      $combo->setPageTitle($row['page_title']);
      $combo->setAccepted($row['accepted']);
      $list[] = $combo;
    }
    Database::free($result);

    return $list;
  }
}
?>