<?php
if (!isset($GLOBALS['basePath'])) {
  header('HTTP/1.1 403 Forbidden');
  exit;
}

class RenderingService {

  static function sendNotFound() {
    $uri = $_SERVER['REQUEST_URI'];
    if ($uri != '/favicon.ico' && $uri != '/robots.txt' && $uri != '/apple-touch-icon.png' && $uri != '/apple-touch-icon-precomposed.png') {
      Log::logPublic('pagenotfound','uri=' . $_SERVER['REQUEST_URI']);
    }
    RenderingService::displayMessage([
      'status' => Response::$NOT_FOUND,
      'title' => 'Page not found',
      'note' => 'The requested page was not found on this website'
    ]);
  }

  static function displayMessage($options,$path = "") {
    if (!isset($options['status'])) {
      $options['status'] = Response::$NOT_FOUND;
    }
    $xml = '<?xml version="1.0" encoding="UTF-8"?>';
    $xml .= '<message xmlns="http://uri.in2isoft.com/onlinepublisher/publishing/error/1.0/">';
    if (isset($options['title'])) {
      $xml .= '<title>' . Strings::escapeXML($options['title']) . '</title>';
    }
    if (isset($options['note'])) {
      $xml .= '<note>' . Strings::escapeXML($options['note']) . '</note>';
    }
    $xml .= '</message>';
    header("Content-Type: text/html; charset=UTF-8");
    Response::sendStatus($options['status']);
    echo RenderingService::applyStylesheet($xml,"basic","error",$path,$path,$path,'',false,'en');
  }

  static function buildPageContext($id,$nextPage,$previousPage) {
    $output = '<context>';

    // Front pages
    $sql = "select specialpage.*,page.path from specialpage,page where page.disabled=0 and specialpage.page_id = page.id";
    $result = Database::select($sql);
    while ($row = Database::next($result)) {
      if ($row['type'] == 'home') {
        $output .= '<home page="' . $row['page_id'] . '"' . ($row['path'] != '' ? ' path="' . Strings::escapeEncodedXML($row['path']) . '"' : '') . ($row['language'] != '' ? ' language="' . Strings::escapeEncodedXML(strtolower($row['language'])) . '"' : '') . '/>';
      }
    }
    Database::free($result);

    // Translations
    $sql = "select page.id,page.language,page.path from page_translation,page" .
    " where page.id=page_translation.translation_id and page.disabled=0" .
    " and page_translation.page_id=" . Database::int($id) . " order by language";
    $result = Database::select($sql);
    while ($row = Database::next($result)) {
      $output .= '<translation page="' . $row['id'] . '"' . ($row['path'] != '' ? ' path="' . Strings::escapeEncodedXML($row['path']) . '"' : '') . ($row['language'] != '' ? ' language="' . Strings::escapeEncodedXML(strtolower($row['language'])) . '"' : '') . '/>';
    }
    Database::free($result);
    if ($nextPage > 0) {
      $output .= '<next id="' . $nextPage . '"/>';
    }
    if ($previousPage > 0) {
      $output .= '<previous id="' . $previousPage . '"/>';
    }
    $output .= '</context>';

    if (ConfigurationService::isDebug()) {
      // TODO Only in debug mode for now
      // Parameters
      $output .= '<parameters>';
      $sql = "select name,value from parameter";
      $result = Database::select($sql);
      while ($row = Database::next($result)) {
        $output .= '<parameter name="' . Strings::escapeEncodedXML($row['name']) . '" category="design"><![CDATA[' . $row['value'] . ']]></parameter>';
      }
      Database::free($result);
      $output .= '</parameters>';
    }
    return $output;
  }

  static function applyStylesheet(&$xmlData,$design,$template,$path,$urlPath,$navigationPath,$pagePath,$preview,$language) {
    global $basePath;

    if (function_exists('xslt_create')) {
      $incPath = '../../';
    }
    else {
      $incPath = $path;
    }
    if (Console::isFromConsole()) {
      $incPath = $basePath;
    }
    $contentDesign = 'basic';
    if (file_exists($basePath . 'style/' . $design . '/xslt/' . $template . '.xsl')) {
      $contentDesign = $design;
    }
    if (Request::getBoolean('print')) {
      $mainFile = 'main_print';
      $mainDesign = 'basic';
    } else if (Request::getBoolean('mini')) {
      $mainFile = 'main_mini';
      $mainDesign = 'basic';
    } else if (Request::getBoolean('content')) {
      $mainFile = 'main_content';
      $mainDesign = 'basic';
    } else {
      $mainFile = 'main';
      $mainDesign = $design;
    }

    $mainPath = $incPath . 'style/' . $mainDesign . '/xslt/' . $mainFile . '.xsl';
    $templatePath = $incPath . 'style/' . $contentDesign . '/xslt/' . $template . '.xsl';


    $variables = [
      'design' => $design,
      'path' => $urlPath,
      'navigation-path' => $navigationPath,
      'page-path' => $pagePath,
      'template' => $template,
      'preview' => $preview,
      'editor' => false,
      'mini' => Request::getBoolean('mini'),
      'language' => $language
    ];

    if (Request::exists('dev')) {
      $variables['development'] = Request::getBoolean('dev') ? 'true' : 'false';
    }

    $encoding = ConfigurationService::isUnicode() ? 'UTF-8' : 'ISO-8859-1';
    $xsl = '<?xml version="1.0" encoding="' . $encoding . '"?>' .
      '<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" version="1.0">' .
      '<xsl:output method="html" indent="no" encoding="' . $encoding . '"/>' .
      '<xsl:include href="' . $templatePath . '"/>' .
      '<xsl:include href="' . $mainPath . '"/>' .
      RenderingService::renderVariables($variables) .
      '<xsl:template match="/"><xsl:apply-templates/></xsl:template>' .
      '</xsl:stylesheet>';

    return XslService::transform($xmlData,$xsl);
  }

  static function renderVariables($values) {

    $protocol = Request::isSecure() ? 'https' : 'http';
    $absolutePath = $protocol . '://' . @$_SERVER['HTTP_HOST'];
    $absolutePagePath = $absolutePath . @$_SERVER['REQUEST_URI'];
    $absolutePath .= '/';

    $userId = 0;
    $userName = '';
    $userTitle = '';
    if ($user = ExternalSession::getUser()) {
      $userId = $user['id'];
      $userName = $user['username'];
      $userTitle = $user['title'];
    }
    $variables = [
      'design' => 'basic',
      'path' => '',
      'navigation-path' => '',
      'page-path' => '',
      'absolute-path' => $absolutePath,
      'data-path' => ConfigurationService::getDataUrl(),
      'absolute-page-path' => $absolutePagePath,
      'template' => 'document',
      'userid' => $userId,
      'username' => $userName,
      'protocol' => $protocol,
      'usertitle' => $userTitle,
      'preview' => 'false',
      'editor' => 'false',
      'mini' => 'false',
      'internal-logged-in' => InternalSession::isLoggedIn(),
      'development' => ConfigurationService::isDebug(),
      'urlrewrite' => ConfigurationService::isUrlRewrite(),
      'timestamp' => ConfigurationService::getDeploymentTime(),
      'language' => 'en',
      'statistics' => ConfigurationService::isGatherStatistics()
    ];
    $xsl = '';
    foreach ($variables as $name => $value) {
      $val = isset($values[$name]) ? $values[$name] : $value;
      if (is_bool($val)) {
        $val = $val ? 'true' : 'false';
      }
      $xsl .= '<xsl:variable name="' . Strings::escapeEncodedXML($name) . '">' .
        Strings::escapeEncodedXML($val) .
      '</xsl:variable>';
    }
    return $xsl;
  }

  static function renderFragment($xml) {
    $xml = '<?xml version="1.0" encoding="UTF-8"?>' . $xml;
    $xsl = '<?xml version="1.0" encoding="UTF-8"?>' .
      '<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" version="1.0">' .
      RenderingService::renderVariables(['preview' => true]) .
      '<xsl:output method="html" indent="no" encoding="UTF-8"/>' .
      '<xsl:include href="' . FileSystemService::getFullPath('style/basic/xslt/util.xsl') . '"/>' .
      '<xsl:include href="' . FileSystemService::getFullPath('style/basic/xslt/document.xsl') . '"/>' .
      '</xsl:stylesheet>';
    return XslService::transform($xml,$xsl);
  }

  static function applyFrameDynamism($id,&$data) {
    $sql = "select id,maxitems,sortdir,sortby,timetype,timecount,UNIX_TIMESTAMP(startdate) as startdate,UNIX_TIMESTAMP(enddate) as enddate from frame_newsblock where frame_id=" . Database::int($id);
    $result = Database::select($sql);
    while ($row = Database::next($result)) {
      $blockId = $row['id'];
      $maxitems = $row['maxitems'];
      $sortBy = 'news.' . $row['sortby'];
      // Find sort direction
      if ($row['sortdir'] == 'descending') {
        $sortDir = 'DESC';
      }
      else {
        $sortDir = 'ASC';
      }
      $timetype = $row['timetype'];
      if ($timetype == 'always') {
        $timeSql = ''; // no time managing for always
      }
      else if ($timetype == 'now') {
        // Create sql for active news
        $timeSql = " and ((news.startdate is null and news.enddate is null) or (news.startdate<=now() and news.enddate>=now()) or (news.startdate<=now() and news.enddate is null) or (news.startdate is null and news.enddate>=now()))";
      }
      else {
        $count = $row['timecount'];
        if ($timetype == 'interval') {
          $start = intval($row['startdate']);
          $end = intval($row['enddate']);
        }
        else if ($timetype == 'hours') {
          $start = mktime(date("H") - $count,date("i"),date("s"),date("m"),date("d"),date("Y"));
          $end = time();
        }
        else if ($timetype == 'days') {
          $start = mktime(date("H"),date("i"),date("s"),date("m"),date("d") - $count,date("Y"));
          $end = time();
        }
        else if ($timetype == 'weeks') {
          $start = mktime(date("H"),date("i"),date("s"),date("m"),date("d") - ($count * 7),date("Y"));
          $end = time();
        }
        else if ($timetype == 'months') {
          $start = mktime(date("H"),date("i"),date("s"),date("m") - $count,date("d"),date("Y"));
          $end = time();
        }
        else if ($timetype == 'years') {
          $start = mktime(date("H"),date("i"),date("s"),date("m"),date("d"),date("Y") - $count);
          $end = time();
        }
        $timeSql = " and ((news.startdate is null and news.enddate is null) or (news.startdate>=" . Database::datetime($start) . " and news.startdate<=" . Database::datetime($end) . ") or (news.enddate>=" . Database::datetime($start) . " and news.enddate<=" . Database::datetime($end) . ") or (news.enddate>=" . Database::datetime($start) . " and news.startdate is null) or (news.startdate<=" . Database::datetime($end) . " and news.enddate is null))";
      }
      $newsData = '';
      $sql = "select distinct object.data from object, news, newsgroup_news, frame_newsblock_newsgroup, frame_newsblock where object.id = news.object_id and news.object_id=newsgroup_news.news_id and newsgroup_news.newsgroup_id=frame_newsblock_newsgroup.newsgroup_id and frame_newsblock_newsgroup.frame_newsblock_id=" . Database::int($blockId) . $timeSql . " order by " . $sortBy . " " . $sortDir;
      $resultNews = Database::select($sql);
      while ($rowNews = Database::next($resultNews)) {
        $newsData .= $rowNews['data'];
        $maxitems--;
        if ($maxitems == 0) break;
      }
      Database::free($resultNews);
      $data = str_replace("<!--newsblock#" . $blockId . "-->", $newsData, $data);
    }
    Database::free($result);
    return $data;
  }

  static function applyContentDynamism($id,$template,&$data) {
    $state = ['data' => $data, 'redirect' => false, 'override' => false];
    if ($controller = TemplateService::getController($template)) {
      if (method_exists($controller,'dynamic')) {
        $controller->dynamic($id,$state);
        return $state;
      }
    }
    return $state;
  }

  static function handleMissingPage($path) {
    // See if there is a page redirect
    $sql = "select page.id,page.path from path left join page on page.id=path.page_id where path.path=" . Database::text($path);
    if ($row = Database::selectFirst($sql)) {
      if ($row['path'] != '') {
        Response::redirectMoved(Strings::concatUrl(ConfigurationService::getBaseUrl(),$row['path']));
      } else if ($row['id'] > 0) {
        Response::redirectMoved(ConfigurationService::getBaseUrl() . '?id=' . $row['id']);
      } else {
        RenderingService::sendNotFound();
      }
    } else {
      RenderingService::sendNotFound();
    }
  }

  static function buildPage($id, $path = null, $parameters = []) {
    $sql = "select page.id,page.path,page.secure,UNIX_TIMESTAMP(page.published) as published," .
      " page.title,page.description,page.language,page.keywords,page.data,page.dynamic,page.next_page,page.previous_page," .
      " template.unique as template,frame.id as frameid,frame.title as frametitle," .
      " frame.data as framedata,frame.dynamic as framedynamic,design.`unique` as design," .
      " design.parameters," .
      " hierarchy.data as hierarchy, " .
      " setting.value as analytics_key" .
      " from page,template,frame,design,hierarchy" .
      " left join setting on setting.subdomain='googleanalytics' and setting.`key`='webprofile'" .
      " where page.frame_id=frame.id and page.template_id=template.id" .
      " and page.disabled=0" .
      " and page.design_id=design.object_id and frame.hierarchy_id=hierarchy.id";
    if ($id > 0) {
      $sql .= " and page.id = @id";
    } else {
      $sql .= " and (page.path = @text(path)";
      if (Strings::isNotBlank($path)) {
        $sql .= " or page.path = @text(pathA) or page.path = @text(pathB)";
      }
      $sql .= ") order by page.path desc";
    }
    if ($row = Database::selectFirst($sql, ['id' => $id, 'path' => $path, 'pathA' => $path . '/', 'pathB' => '/' . $path])) {
      if (Request::getBoolean('ajax')) {
        if ($controller = TemplateService::getController($row['template'])) {
          if (method_exists($controller,'ajax')) {
            $controller->ajax($id);
            exit;
          }
        }
      }

      $data = $row['data'];
      $template = $row['template'];
      $redirect = false;

      if (Strings::isNotBlank($row['path']) && Strings::isBlank($path) && $id > 0) {
        //Log::debug('Redirect: requested:('.$path.') page:('.$row['path'].') id:('.$id.')');
        if ($row['path']) {
          $redirect = Strings::concatUrl(ConfigurationService::getBaseUrl(),$row['path']);
          $query = [];
          foreach ($parameters as $parameter) {
            if ($parameter['name'] != 'id' && Strings::isNotBlank($parameter['name'])) {
              $query[] = $parameter['name'] . '=' . $parameter['value'];
            }
          }
          if (count($query) > 0) {
            $redirect .= '?' . join($query,'&');
          }
          //Log::debug('Redirect::: '.$redirect);
          //Log::debug($row);
        }
      }
      else if ($row['dynamic']) {
        $content = RenderingService::applyContentDynamism($row['id'],$template,$data);
        $data = $content['data'];
        $redirect = $content['redirect'];
      }
      $framedata = $row['framedata'];
      if ($row['framedynamic']) {
        $framedata = RenderingService::applyFrameDynamism($row['frameid'],$framedata);
      }
      $xml = RenderingService::buildXML([
        'id' => $row['id'],
        'title' => $row['title'],
        'description' => $row['description'],
        'keywords' => $row['keywords'],
        'published' => $row['published'],
        'analytics_key' => $row['analytics_key'],
        'language' => $row['language'],
        'next_page' => $row['next_page'],
        'previous_page' => $row['previous_page'],
        'parameters' => $row['parameters'],
        'hierarchy' => $row['hierarchy'],
        'frametitle' => $row['frametitle'],
        'frame' => $framedata,
        'content' => $data
      ]);
      return [
        'id' => $row['id'],
        'xml' => $xml,
        'design' => $row['design'],
        'template' => $template,
        'published' => $row['published'],
        'secure' => $row['secure'],
        'language' => strtolower($row['language']),
        'redirect' => $redirect,
        'dynamic' => $row['dynamic'],
        'framedynamic' => $row['framedynamic']
      ];
    }
    else {
      return false;
    }
  }

  static function _getAgent() {
    if (isset($_SERVER['HTTP_USER_AGENT'])) {
      $str = $_SERVER['HTTP_USER_AGENT'];
      $analyzer = new UserAgentAnalyzer($str);
      return $analyzer->getShortID();
    }
    return '';
  }

  static function findPage($type) {
    $sql = "select page_id from specialpage where type=" . Database::text($type) . " order by language asc";
    $row = Database::selectFirst($sql);
    if ($row) {
      return intval($row['page_id']);
    }
    return null;
  }

  static function buildDateTag($tag,$stamp) {
    return '<' . $tag . ' unix="' . $stamp . '" day="' . date('d',$stamp) . '" weekday="' . date('d',$stamp) . '" yearday="' . date('z',$stamp) . '" month="' . date('m',$stamp) . '" year="' . date('Y',$stamp) . '" hour="' . date('H',$stamp) . '" minute="' . date('i',$stamp) . '" second="' . date('s',$stamp) . '" offset="' . date('Z',$stamp) . '" timezone="' . date('T',$stamp) . '"/>';
  }

  // Returns true if the user has access to the page
  static function userHasAccessToPage($user,$page) {
    $sql = "select * from securityzone_page,securityzone_user where securityzone_page.securityzone_id=securityzone_user.securityzone_id and page_id=" . Database::int($page) . " and user_id=" . Database::int($user);
    if ($row = Database::selectFirst($sql)) {
      return true;
    }
    else {
      return false;
    }
  }

  // finds and redirects to the appropriate authentication page for the provided page
  // displays error otherwise
  static function goToAuthenticationPage($id,$path) {
    if ($authId = RenderingService::findAuthenticationPageForPage($id)) {
      Response::redirect('/?id=' . $authId . '&page=' . $id);
    }
    else {
      RenderingService::displayMessage([
        'status' => Response::$FORBIDDEN,
        'title' => 'You do not have access to this page',
        'note' => 'Also, there is not specified a way to gain access'
      ]);
    }
  }

  /**
     * Finds the appropriate authentication page for a given page
   * returns the id of the authentication page
   * returns false otherwise
     */
  static function findAuthenticationPageForPage($id) {
    $sql = "select authentication_page_id,page.id from securityzone, securityzone_page,page,page as authpage where securityzone.object_id=securityzone_page.securityzone_id and page.id= securityzone_page.page_id and authpage.id = authentication_page_id and page.id=@int(id)";
    if ($row = Database::selectFirst($sql,['id' => $id])) {
      return $row['authentication_page_id'];
    }
    else {
      Log::debug($sql . $id);
      return false;
    }
  }

  static function showFile($id) {
    $sql = "select * from file where object_id = @int(id)";
    if ($row = Database::selectFirst($sql,['id' => $id])) {
      Response::redirect('files/' . $row['filename']);
    } else {
      Log::logPublic('filenotfound','File-id:' . $id);
      RenderingService::displayMessage([
        'status' => Response::$NOT_FOUND,
        'title' => 'The file could not be found',
        'note' => 'The file may be removed or renamed'
      ]);
    }
  }

  static function getDesign($design) {
    if (Request::exists('designsession')) {
      $_SESSION['debug.design'] = Request::getString('designsession');
    }
    if (Request::getBoolean('resetdesign')) {
      unset($_SESSION['debug.design']);
    }
    if (Request::exists('design')) {
      $design = Request::getString('design');
    }
    else if (isset($_SESSION['debug.design'])) {
      $design = $_SESSION['debug.design'];
    }
    return $design;
  }

  static function writePage($id,$path,&$page,$relative,$samePageBaseUrl) {
    if (Request::getBoolean('viewsource')) {
      header('Content-type: text/xml');
      echo $page['xml'];
    } else {
      $html = RenderingService::applyStylesheet($page['xml'],RenderingService::getDesign($page['design']),$page['template'],'',$relative,$relative,$samePageBaseUrl,false,$page['language']);
      if (ConfigurationService::isOptimizeHTML()) {
        $html = MarkupUtils::moveScriptsToBottom($html);
        $html = MarkupUtils::moveStyleToHead($html);
      }
      $output = $html;
      if (Request::supportsGzip()) {
        $output = gzencode($output,9);
        header('Content-Encoding: gzip');
      }
      header('Content-Length: ' . strlen($output));
      header("Last-Modified: " . gmdate("D, d M Y H:i:s",$page['published']) . " GMT");
      header("Cache-Control: public");
      header("Expires: " . gmdate("D, d M Y H:i:s",time() + (60 * 60 * 1)) . " GMT"); // 1 hour
      header('Pragma: cache');
      header("Content-Type: text/html; charset=UTF-8");
      header('X-UA-Compatible: IE=edge');
      if ($page['design'] == 'humanise') {
        header('Link: <http://fonts.googleapis.com/css?family=Lato:300,400,700,900>; rel=preload; as=style');
        header('Link: </version' . ConfigurationService::getDeploymentTime() . '/api/style/humanise.css>; rel=preload; as=style');
      }
      echo $output;
      if (!$page['secure'] && !$page['dynamic'] && !$page['framedynamic']) {
        CacheService::createPageCache($page['id'],$path,$html);
      }
    }
  }

  static function previewPage($options) {
    $pageId = $options['pageId'];
    $historyId = isset($options['historyId']) ? intval($options['historyId']) : -1;
    $relativePath = $options['relativePath'];
    $relativeUrl = isset($options['relativeUrl']) ? $options['relativeUrl'] : $relativePath;

    $sql = "select page.id,UNIX_TIMESTAMP(page.published) as published, page.description,page.language,page.keywords," .
    "page.title,page.dynamic,page.next_page,page.previous_page," .
    "template.unique,frame.id as frameid,frame.title as frametitle,frame.data as framedata,frame.dynamic as framedynamic," .
    " design.parameters," .
    "design.`unique` as design, hierarchy.id as hierarchy" .
    " from page,template,frame,design,hierarchy" .
    " where page.frame_id=frame.id and page.template_id=template.id and page.design_id=design.object_id" .
    " and frame.hierarchy_id=hierarchy.id and page.id = @id";
    if ($row = Database::selectFirst($sql, $pageId)) {
      $template = $row['unique'];
      $id = $row['id'];
      if ($historyId > 0) {
        $sql = "select data from page_history where id = @id";
        if ($hist = Database::selectFirst($sql, $historyId)) {
          $data = $hist['data'];
        } else {
          $data = PageService::getPagePreview($id, $template);
        }
      } else {
        $data = PageService::getPagePreview($id, $template);
      }

      $stuff = RenderingService::applyContentDynamism($id, $template, $data);
      $data = $stuff['data'];

      $framedata = $row['framedata'];
      if ($row['framedynamic']) {
        $framedata = RenderingService::applyFrameDynamism($row['frameid'],$framedata);
      }
      $design = $row['design'];
      if (Request::exists('design')) {
        $design = Request::getString('design');
      }
      else if (isset($_SESSION['debug.design'])) {
        $design = $_SESSION['debug.design'];
      }
      $xml = RenderingService::buildXML([
        'id' => $id,
        'title' => $row['title'],
        'description' => $row['description'],
        'keywords' => $row['keywords'],
        'published' => $row['published'],
        'language' => $row['language'],
        'next_page' => $row['next_page'],
        'previous_page' => $row['previous_page'],
        'parameters' => $row['parameters'],
        'hierarchy' => Hierarchy::build($row['hierarchy']),
        'frametitle' => $row['frametitle'],
        'frame' => $framedata,
        'content' => $data
      ]);
      $html = RenderingService::applyStylesheet($xml,$design,$template,$relativePath,$relativeUrl,'','?id=' . $id . '&amp;',true,strtolower($row['language']));
      if (ConfigurationService::isOptimizeHTML()) {
        $html = MarkupUtils::moveScriptsToBottom($html);
        $html = MarkupUtils::moveStyleToHead($html);
      }
      return $html;
    }
    Log::debug('Unable to find page: ' . $pageId);
    Log::debug($sql);
    return null;
  }

  static function buildXML($data) {
    $encoding = ConfigurationService::isUnicode() ? 'UTF-8' : 'ISO-8859-1';
    return '<?xml version="1.0" encoding="' . $encoding . '"?>' .
      '<page xmlns="http://uri.in2isoft.com/onlinepublisher/publishing/page/1.0/" id="' . $data['id'] . '" title="' . Strings::escapeEncodedXML($data['title']) . '">' .
      '<meta>' .
      '<description>' . Strings::escapeEncodedXML(preg_replace('/\s+/', ' ', $data['description'])) . '</description>' .
      '<keywords>' . Strings::escapeEncodedXML($data['keywords']) . '</keywords>' .
      RenderingService::buildDateTag('published',$data['published']) .
      '<language>' . Strings::escapeEncodedXML(strtolower($data['language'])) . '</language>' .
      (isset($row['analytics_key']) && !empty($row['analytics_key']) ? '<analytics key="' . Strings::escapeEncodedXML($row['analytics_key']) . '"/>' : '') .
      '</meta>' .
        '<design>' .
        $data['parameters'] .
        '</design>' .
      RenderingService::buildPageContext($data['id'],$data['next_page'],$data['previous_page']) .
      '<frame xmlns="http://uri.in2isoft.com/onlinepublisher/publishing/frame/1.0/" title="' . Strings::escapeEncodedXML($data['frametitle']) . '">' .
      $data['hierarchy'] .
      $data['frame'] .
      '</frame>' .
      '<content>' .
      $data['content'] .
      '</content>' .
      '</page>';
  }

  static function applyTwigTemplate($vars = []) {
    if (isset($vars['path'])) {
      $loader = new Twig_Loader_String();
      $twig = new Twig_Environment($loader);
      $path = FileSystemService::getFullPath($vars['path']);
      $template = file_get_contents($path);
      $data = isset($vars['variables']) ? $vars['variables'] : [];
      return $twig->render($template, $data);
    }
    return null;
  }
}