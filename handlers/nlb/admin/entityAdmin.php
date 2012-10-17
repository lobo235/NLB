<?php

switch($_GET['action'])
{
	case 'list':
		class_exists('EntityService') || require(NLB_LIB_ROOT.'services/EntityService.class.php');
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
		class_exists($_GET['entity_type']) || require(NLB_LIB_ROOT.'dom/'.$_GET['entity_type'].'.class.php');
		$e = new $_GET['entity_type'];
		$e->setType($_GET['entity_type']);
		$allcolumns = $e->getColumns();
		$vars['entity'] = $e;
		$vars['columns'] = array();
		$useWysiwyg = false;
		foreach($allcolumns as $table_name => $columns)
		{
			foreach($columns as $column)
			{
				if($column->isType('wysiwyg'))
					$useWysiwyg = true;
				$vars['columns'][] = $column;
			}
		}
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
		class_exists($entity_type) || require(NLB_LIB_ROOT.'dom/'.$entity_type.'.class.php');
		$e = new $entity_type();
		$e->lookupUsingEid($_GET['eid']);
		$allcolumns = $e->getColumns();
		$vars['entity'] = $e;
		$vars['columns'] = array();
		$useWysiwyg = false;
		foreach($allcolumns as $table_name => $columns)
		{
			foreach($columns as $column)
			{
				if($column->isType('wysiwyg'))
					$useWysiwyg = true;
				$vars['columns'][] = $column;
			}
		}
		if($useWysiwyg)
		{
			$UI->registerAsset('js/ckeditor/ckeditor.js', FALSE, FALSE);
		}
		$pageVars['title'] = 'Edit '.$entity_type.' '.$_GET['eid'];
		$pageVars['sidebar1_content'] = $UI->renderTemplate('admin-entityMenu.tpl', NULL, 5);
		$pageVars['content'] = $UI->renderTemplate('admin-entityForm.tpl', $vars, 5);
		break;
	case 'delete':
		echo 'deleting entity '.$_GET['eid'];
		break;
	case 'setstatus':
		echo 'updating status of entity '.$_GET['eid'].' to '.$_GET['statusid'];
		break;
	case 'save':
		class_exists($_POST['type']) || require(NLB_LIB_ROOT.'dom/'.$_POST['type'].'.class.php');
		$e = new $_POST['type'];
		foreach($_POST as $key => $val)
		{
			if($key != 's')
			{
				$e->setField($key, $val);
			}
		}
		try
		{
			$e->save();
            header('Location: '.$_POST['nlb_referrer']);
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