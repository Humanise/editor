<?
class TemplateController {
	
	var $id;
	
	/**
	 * Should be overridden
	 * @protected
	 */
    function TemplateController($id) {
		$this->id = $id;
    }
    
	/**
	 * Should be overridden
	 */
    function build() {
        return array('data' => '', 'dynamic' => false, 'index' => '');
    }
    
	/**
	 * Should be overridden
	 */
    function import(&$node) {
    }
    
	/**
	 * May be overridden
	 */
    function ajax() {
    }
	
	/**
	 * @static
	 */
	function getController($template,$id) {
		global $basePath;
		$ctrlClass = ucfirst($template).'Controller';
		require_once($basePath.'Editor/Template/'.$template.'/'.$ctrlClass.'.php');
		$object = new $ctrlClass ($id);
		return $object;
	}
}
?>