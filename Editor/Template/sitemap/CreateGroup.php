<?
/**
 * @package OnlinePublisher
 * @subpackage Templates.Sitemap
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/Functions.php';
require_once '../../Classes/Page.php';
require_once '../../Classes/Request.php';
require_once '../../Classes/InternalSession.php';

$id = InternalSession::getPageId();
$title = Request::getString('title');
$hierarchy = Request::getInt('hierarchy');

$sql = "select max(position) as position from sitemap_group where page_id=".$id;
if ($row = Database::selectFirst($sql)) {
    $position = $row['position']+1;
} else {
    $position = 1;
}

$sql = "insert into sitemap_group (page_id,title,position,hierarchy_id) values (".$id.",".Database::text($title).",".$position.",".$hierarchy.")";
Database::insert($sql);


Page::markChanged($id);

redirect('Groups.php');
?>