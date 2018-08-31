<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes.Templates
 */
if (!isset($GLOBALS['basePath'])) {
  header('HTTP/1.1 403 Forbidden');
  exit;
}

class AuthenticationTemplateController extends TemplateController
{
  function __construct() {
    parent::__construct('authentication');
  }

  function create($page) {
    $sql = "insert into authentication (page_id,title) values (@int(pageId), @text(title))";
    Database::insert($sql, ['pageId' => $page->getId(), 'title' => $page->getTitle()]);
  }

  function delete($page) {
    $sql = "delete from authentication where page_id = @id";
    Database::delete($sql, $page->getId());
  }

  function isClientSide() {
    return true;
  }

  function build($id) {
    $data = '<authentication xmlns="http://uri.in2isoft.com/onlinepublisher/publishing/authentication/1.0/">';
    $sql = "select title from authentication where page_id = @id";
    if ($row = Database::selectFirst($sql, $id)) {
      $data .= '<title>' . Strings::escapeXML($row['title']) . '</title>';
      $index = $row['title'];
    }
    $data .= '<!--dynamic-->';
    $data .= '</authentication>';
    return ['data' => $data, 'index' => '', 'dynamic' => true];
  }

  function dynamic($id,&$state) {
    $xml = '';

    if (Request::exists('page')) {
      $xml .= '<target type="page" id="' . Request::getInt('page') . '"/>';
    }
    if (Request::getBoolean('logout')) {
      if (Request::exists('page')) {
        $state['redirect'] = './?id=' . Request::getInt('page');
      } else {
        $xml .= '<message type="loggedout"/>';
      }
    }

    if (Request::exists('username') && Request::exists('password')) {
      if (strlen(Request::getString('username')) == 0) {
          $xml .= '<message type="nousername"/>';
      }
      elseif (strlen(Request::getString('password')) == 0) {
          $xml .= '<message type="nopassword"/>';
      }
      else {
        if ($user = ExternalSession::logIn(Request::getString('username'),Request::getString('password'))) {
          if (Request::exists('page')) {
            $state['redirect'] = './?id=' . Request::getInt('page');
          }
          else {
            $xml .= '<message type="loggedin"/>';
          }
        }
        else {
          $xml .= '<message type="usernotfound"/>';
        }
      }
    }
    $state['data'] = str_replace('<!--dynamic-->', $xml, $state['data']);
  }

}