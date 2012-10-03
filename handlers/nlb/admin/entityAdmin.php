<?php

switch($_GET['action'])
{
	case 'list':
		echo 'listing entities';
		break;
	case 'create':
		class_exists($_GET['entity_type']) || require(NLB_LIB_ROOT.'dom/'.$_GET['entity_type'].'.class.php');
		$e = new $_GET['entity_type'];
		$e->setType($_GET['entity_type']);
		$allcolumns = $e->getColumns();
		$vars['entity'] = $e;
		$vars['columns'] = array();
		foreach($allcolumns as $table_name => $columns)
		{
			foreach($columns as $column)
			{
				$vars['columns'][] = $column;
			}
		}
		$pageVars['content'] = $UI->renderTemplate('admin-addEntity.tpl', $vars);
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
		foreach($allcolumns as $table_name => $columns)
		{
			foreach($columns as $column)
			{
				$vars['columns'][] = $column;
			}
		}
		$pageVars['content'] = $UI->renderTemplate('admin-addEntity.tpl', $vars);
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
            header('Location: '.$_SERVER['HTTP_REFERER']);
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