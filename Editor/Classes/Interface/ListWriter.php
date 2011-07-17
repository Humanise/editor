<?
/**
 * @package OnlinePublisher
 * @subpackage Classes.Interface
 */

class ListWriter {
	function startList($options=array()) {
		if ($options['unicode']==true) {
			header('Content-Type: text/xml; charset=utf-8');
			echo '<?xml version="1.0" encoding="UTF-8"?><list>';
		} else {
			header('Content-Type: text/xml; charset=iso-8859-1');
			echo '<?xml version="1.0" encoding="ISO-8859-1"?><list>';
		}
		return $this;
	}

	function endList() {
		echo '</list>';
	}
	
	function sort($key,$direction) {
		echo "<sort key='$key' direction='$direction'/>";
		return $this;
	}
	
	function window($options) {
		echo '<window total="'.$options['total'].'" size="'.$options['size'].'" page="'.$options['page'].'"/>';
		return $this;
	}
	
	function startHeaders() {
		echo '<headers>';
		return $this;
	}
	
	function endHeaders() {
		echo '</headers>';
		return $this;
	}
	
	function header($options=array()) {
		echo '<header';
		if (isset($options['title'])) {
			echo ' title="'.$options['title'].'"';
		}
		if (isset($options['width'])) {
			echo ' width="'.$options['width'].'"';
		}
		if (isset($options['key'])) {
			echo ' key="'.$options['key'].'"';
		}
		if (isset($options['sortable'])) {
			echo ' sortable="'.($options['sortable'] ? 'true' : 'false').'"';
		}
		echo '/>';
		return $this;
	}
	
	function startRow($options=array()) {
		echo '<row';
		if (isset($options['id'])) {
			echo ' id="'.$options['id'].'"';
		}
		if (isset($options['value'])) {
			echo ' value="'.$options['value'].'"';
		}
		if (isset($options['kind'])) {
			echo ' kind="'.$options['kind'].'"';
		}
		if (isset($options['level'])) {
			echo ' level="'.$options['level'].'"';
		}
		if (isset($options['data'])) {
			echo ' data="'.StringUtils::escapeXML(StringUtils::toJSON($options['data'])).'"';
		}
		echo '>';
		return $this;
	}
	
	function endRow() {
		echo '</row>';
		return $this;
	}
	
	function startCell($options=array()) {
		echo '<cell';
		if (isset($options['icon'])) {
			echo ' icon="'.$options['icon'].'"';
		}
		if (isset($options['wrap'])) {
			echo ' wrap="'.($options['wrap'] ? 'true' : 'false').'"';
		}
		echo '>';
		return $this;
	}
	
	function endCell() {
		echo '</cell>';
		return $this;
	}
	
	function startLine($options=array()) {
		echo '<line'.
		($options['dimmed'] ? ' dimmed="true"' : '').
		'>';
		return $this;
	}
	
	function endLine() {
		echo '</line>';
		return $this;
	}
	
	function startWrap() {
		echo '<wrap>';
		return $this;
	}
	
	function endWrap() {
		echo '</wrap>';
		return $this;
	}
	
	function startDelete() {
		echo '<delete>';
		return $this;
	}
	
	function endDelete() {
		echo '</delete>';
		return $this;
	}

	function text($text) {
		echo StringUtils::escapeXMLBreak($text,'<break/>');
		return $this;
	}

	function badge($text) {
		echo '<badge>';
		$this->text($text);
		echo '</badge>';
		return $this;
	}
	
	function object($options=array()) {
		echo '<object icon="'.$options['icon'].'">';
		$this->text($options['text']);
		echo '</object>';
		return $this;
	}
	
	function icon($options=array()) {
		echo '<icon icon="'.$options['icon'].'"';
		if (isset($options['data'])) {
			echo ' data="'.StringUtils::escapeXML(StringUtils::toJSON($options['data'])).'"';
		}
		if ($options['revealing']) {
			echo ' revealing="true"';
		}
		echo '/>';
		return $this;
	}

	function button($options=array()) {
		echo '<button text="'.StringUtils::escapeXML($options['text']).'"';
		if (isset($options['data'])) {
			echo ' data="'.StringUtils::escapeXML(StringUtils::toJSON($options['data'])).'"';
		}
		echo '/>';
		return $this;
	}
	
	function startIcons() {
		echo '<icons>';
		return $this;
	}
	
	function endIcons() {
		echo '</icons>';
		return $this;
	}
}
?>