<?php

class_exists('DatabaseService') || require_once(NLB_LIB_ROOT.'services/DatabaseService.class.php');

function prepareFields($allColumns, $specialFields, &$useWysiwyg, &$vars)
{
	foreach($allColumns as $table_name => $columns)
	{
		foreach($columns as $column)
		{
			if($column->isType('wysiwyg'))
				$useWysiwyg = TRUE;
			$vars['columns'][] = $column;
		}
	}
	foreach($specialFields as $field)
	{
		if($column->isType('wysiwyg'))
			$useWysiwyg = TRUE;
		$vars['columns'][] = $field;
	}
}

switch($_GET['action'])
{
	case 'list':
		if(isset($_GET['type']) && $_GET['type'] != '')
		{
			class_exists($_GET['type']) || require_once(NLB_LIB_ROOT.'dom/'.$_GET['type'].'.class.php');
			$obj = new $_GET['type'];
			$objects = array();
			
			$tables = $obj->getTables();
			
			$query = "SELECT `".$obj->getPrimaryIdColumn()."` FROM `".end($tables)->getTableName()."` ORDER BY `".$obj->getPrimaryIdColumn()."`";
			
			$res = DatabaseService::getInstance()->getSelectArray($query);
			if(is_array($res) && count($res) > 0)
			{
				foreach($res as $row)
				{
					$objects[] = new $_GET['type']($row[$obj->getPrimaryIdColumn()]);
				}
			}
			
			$vars['template_obj'] = $obj;
			$vars['objects'] = $objects;
			$pageVars['title'] = $_GET['type'].' List';
		}
		$pageVars['sidebar1_content'] = $UI->renderTemplate('admin-dbObjectMenu.tpl', NULL, 5);
		$pageVars['content'] = $UI->renderTemplate('admin-dbObjectList.tpl', $vars, 5);
		break;
	case 'create':
		class_exists($_GET['type']) || require_once(NLB_LIB_ROOT.'dom/'.$_GET['type'].'.class.php');
		$obj = new $_GET['type'];
		if(isset($_GET['default_params']) && is_array($_GET['default_params']))
		{
			foreach($_GET['default_params'] as $field => $value)
			{
				$obj->setField($field, $value);
			}
		}
		$allColumns = $obj->getColumns();
		$specialFields = $obj->getSpecialFields();
		$vars['object'] = $obj;
		$vars['columns'] = array();
		$useWysiwyg = FALSE;
		prepareFields($allColumns, $specialFields, $useWysiwyg, $vars);
		if($useWysiwyg)
		{
			$UI->registerAsset('js/ckeditor/ckeditor.js', FALSE, FALSE);
		}
		$pageVars['title'] = 'Create '.$_GET['type'];
		$pageVars['sidebar1_content'] = $UI->renderTemplate('admin-dbObjectMenu.tpl', NULL, 5);
		$pageVars['content'] = $UI->renderTemplate('admin-dbObjectForm.tpl', $vars, 5);
		break;
	case 'edit':
		class_exists($_GET['type']) || require_once(NLB_LIB_ROOT.'dom/'.$_GET['type'].'.class.php');
		$obj = new $_GET['type']($_GET['object_id']);
		$allColumns = $obj->getColumns();
		$specialFields = $obj->getSpecialFields();
		$vars['object'] = $obj;
		$vars['columns'] = array();
		$useWysiwyg = FALSE;
		prepareFields($allColumns, $specialFields, $useWysiwyg, $vars);
		if($useWysiwyg)
		{
			$UI->registerAsset('js/ckeditor/ckeditor.js', FALSE, FALSE);
		}
		$pageVars['title'] = 'Edit '.$_GET['type'].' '.$obj->getField($obj->getPrimaryIdColumn());
		$pageVars['sidebar1_content'] = $UI->renderTemplate('admin-dbObjectMenu.tpl', NULL, 5);
		$pageVars['content'] = $UI->renderTemplate('admin-dbObjectForm.tpl', $vars, 5);
		break;
	case 'delete':
		class_exists($_GET['type']) || require_once(NLB_LIB_ROOT.'dom/'.$_GET['type'].'.class.php');
		$obj = new $_GET['type']($_GET['object_id']);
		
		$obj->delete();
		if(isset($_SERVER['HTTP_REFERER']))
		{
			header('Location: '.$_SERVER['HTTP_REFERER']);
		}
		else
		{
			header('Location: '.$app->l('admin/db-objects/'.$_GET['type']));
		}
		exit();
		break;
	case 'save':
		class_exists($_GET['type']) || require_once(NLB_LIB_ROOT.'dom/'.$_GET['type'].'.class.php');
		$obj = new $_GET['type'];
		if(isset($_POST[$obj->getPrimaryIdColumn()]) && $_POST[$obj->getPrimaryIdColumn()] != '')
		{
			$obj = new $_GET['type']($_POST[$obj->getPrimaryIdColumn()]);
		}
		foreach($_POST as $key => $val)
		{
			if($key != 's' && $key != 'nlb_referrer')
			{
				$obj->setField($key, $val);
			}
		}
		try
		{
			$obj->save();
			if(isset($_POST['nlb_referrer']) && $_POST['nlb_referrer'] != '')
			{
				header('Location: '.$_POST['nlb_referrer']);
			}
			else
			{
				header('Location: '.$app->l('admin/db-objects/'.$_GET['type']));
			}
			exit();
		}
		catch(DatabaseObjectException $e)
		{
			echo $e->getTraceAsString();
		}
		break;
	default:
		echo 'No action specified... Nothing to do!';
}