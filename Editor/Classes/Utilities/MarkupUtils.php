<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes.Utilities
 */

if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}
class MarkupUtils {

	static function findScriptSegments($str) {
		$start = '<script';
		$stop = '</script>';
		$segments = array();
		$pos = 0;
		while ($pos!==false) {
			$from = strpos($str,$start,$pos);
			if ($from===false) {
				$pos = false;
				continue;
			}
			$to = strpos($str,$stop,$from+strlen($start));
			if ($to!==false) {
				$to+=strlen($stop);
				$segments[] = array('from'=>$from,'to'=>$to);
				$pos = $to;
			} else {
				$pos = false;
			}
		}
		return $segments;
	}

    // TODO Test and perfect this
  static function htmlToXhtml($html) {
		$html = str_replace(['<br>','<hr>','&quot;','&nbsp;'], ['<br/>','<hr/>','&#34;','&#160;'], $html);
		$doc = DOMUtils::parseAnything('<div>' . $html . '</div>');
		return DOMUtils::getInnerXML($doc->documentElement);
  }

	static function moveScriptsToBottom($html) {
		if (strpos($html,'</body>') === false) {
			return $html;
		}

		$moved = array();
    preg_match_all("/<!--\\[if[\s\S]*endif\\]-->/uU", $html, $matches);
		$found = $matches[0];
		foreach ($found as $match) {
			if (strpos($match,'<script') === false) {
				continue;
			}
      if (strpos($match,'data-movable="false"') !== false) {
        continue;
      }
			$html = str_replace($match,'',$html);
			$moved[] = $match;
		}

		preg_match_all("/<script[^>]+\\/>|<script[^>]*>[\s\S]*<\\/script>/uU", $html, $matches);
		$found = $matches[0];
    $filtered = array();
    foreach ($found as $script) {
      if (strpos($script,'data-movable="false"')===false) {
        $filtered[] = $script;
      }
    }

		$html = str_replace($filtered, '', $html);
		$pos = strpos($html, '</body>');

		$moved = array_merge($moved, $filtered);

		$html = substr($html,0,$pos) . join($moved,'') . substr($html,$pos);
		return $html;
	}

	static function moveStyleToHead($html) {
    $start = strpos($html,'</head>');
		if ($start === false) {
			return $html;
		}

    preg_match_all("/(<!--\\[if[\s\S]*endif\\]-->)|(<style[^>]+\\/>|<style[^>]*>[\s\S]*<\\/style>)/uU", $html, $matches, PREG_PATTERN_ORDER, $start);
		$found = $matches[0];
    $filtered = array();
    foreach ($found as $snippet) {
			if (strpos($snippet,'<style') === false) {
				continue;
			}
      if (strpos($snippet,'data-movable="false"')===false) {
        $filtered[] = $snippet;
      }
    }

		$html = str_replace($filtered, '', $html);
		$pos = strpos($html, '</head>');

		$html = substr($html,0,$pos) . join($filtered,'') . substr($html,$pos);
		return $html;
	}
}