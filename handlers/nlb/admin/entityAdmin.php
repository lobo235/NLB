<?php

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
		$GLOBALS['app']->loadClass('services', 'EntityService');
		$entityService = EntityService::getInstance();
		if(isset($_GET['type']) && $_GET['type'] != '')
		{
			$vars['entities'] = $entityService->getEntities($_GET['type']);
			$pageVars['title'] = $_GET['type'].' List';
		}
		else
		{
			$vars['entities'] = $entityService->getEntities();
			$pageVars['title'] = 'Entity List';
		}
		$pageVars['sidebar1_content'] = $UI->renderTemplate('admin-entityMenu.tpl', NULL, 5);
		$pageVars['content'] = $UI->renderTemplate('admin-entityList.tpl', $vars, 5);
		break;
	case 'create':
		$GLOBALS['app']->loadClass('dom', $_GET['entity_type']);
		$e = new $_GET['entity_type'];
		$e->setType($_GET['entity_type']);
		if(isset($_GET['default_params']) && is_array($_GET['default_params']))
		{
			foreach($_GET['default_params'] as $field => $value)
			{
				$e->setField($field, $value);
			}
		}
		$allColumns = $e->getColumns();
		$specialFields = $e->getSpecialFields();
		$vars['entity'] = $e;
		$vars['columns'] = array();
		$useWysiwyg = FALSE;
		prepareFields($allColumns, $specialFields, $useWysiwyg, $vars);
		if($useWysiwyg)
		{
			$UI->registerAsset('js/ckeditor/ckeditor.js', FALSE, FALSE);
		}
		$pageVars['title'] = 'Create '.$_GET['entity_type'];
		$pageVars['sidebar1_content'] = $UI->renderTemplate('admin-entityMenu.tpl', NULL, 5);
		$pageVars['content'] = $UI->renderTemplate('admin-entityForm.tpl', $vars, 5);
		break;
	case 'edit':
		$e = new Entity($_GET['eid']);
		$entity_type = $e->getType();
		$GLOBALS['app']->loadClass('dom', $entity_type);
		$e = new $entity_type();
		$e->lookupUsingEid($_GET['eid']);
		$allColumns = $e->getColumns();
		$specialFields = $e->getSpecialFields();
		$vars['entity'] = $e;
		$vars['columns'] = array();
		$useWysiwyg = FALSE;
		prepareFields($allColumns, $specialFields, $useWysiwyg, $vars);
		if($useWysiwyg)
		{
			$UI->registerAsset('js/ckeditor/ckeditor.js', FALSE, FALSE);
		}
		$pageVars['title'] = 'Edit '.$entity_type.' '.$_GET['eid'];
		$pageVars['sidebar1_content'] = $UI->renderTemplate('admin-entityMenu.tpl', NULL, 5);
		$pageVars['content'] = $UI->renderTemplate('admin-entityForm.tpl', $vars, 5);
		break;
	case 'delete':
		$e = new Entity($_GET['eid']);
		$entity_type = $e->getType();
		$GLOBALS['app']->loadClass('dom', $entity_type);
		$e = new $entity_type();
		$e->lookupUsingEid($_GET['eid']);
		$e->delete();
		if(isset($_SERVER['HTTP_REFERER']))
		{
			header('Location: '.$_SERVER['HTTP_REFERER']);
		}
		else
		{
			header('Location: '.$app->l('admin/entities'));
		}
		exit();
		break;
	case 'setstatus':
		echo 'updating status of entity '.$_GET['eid'].' to '.$_GET['statusid'];
		break;
	case 'save':
		$GLOBALS['app']->loadClass('dom', $_POST['type']);
		$e = new $_POST['type'];
		if(isset($_POST['eid']) && $_POST['eid'] != '')
		{
			$e->lookupUsingEid($_POST['eid']);
		}
		foreach($_POST as $key => $val)
		{
			if($key != 's' && $key != 'nlb_referrer')
			{
				$e->setField($key, $val);
			}
		}
		try
		{
			$e->save();
			if(isset($_POST['nlb_referrer']))
			{
				header('Location: '.$_POST['nlb_referrer']);
			}
			else
			{
				header('Location: '.$app->l('admin/entities'));
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