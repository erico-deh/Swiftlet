<?php
/**
 * @package Swiftlet
 * @copyright 2009 ElbertF http://elbertf.com
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU Public License
 */

$contrSetup = array(
	'rootPath'   => '../../',
	'pageTitle'  => 'CKEditor config',
	'standAlone' => TRUE
	);

require($contrSetup['rootPath'] . '_model/init.php');

?>
CKEDITOR.config.baseHref = 'http://<?php echo $_SERVER['SERVER_NAME'] . $contr->absPath ?>';
CKEDITOR.config.height   = '400';
CKEDITOR.config.toolbar  = [
	['Format'],
	['Bold', 'Italic', 'Strike'],
	['NumberedList', 'BulletedList'],
	['Link', 'Unlink'],
	['Image', 'Flash', 'Table', 'SpecialChar'],
	['PasteText', 'PasteFromWord'],
	['RemoveFormat'],
	['Source']
	];

CKEDITOR.config.filebrowserBrowseUrl    = '<?php echo $view->rootPath ?>admin/files/?mode=ckeditor';
CKEDITOR.config.filebrowserWindowWidth  = '1000';
CKEDITOR.config.filebrowserWindowHeight = '75%';
<?php

$model->end();
?>