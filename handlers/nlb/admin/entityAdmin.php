<?php

switch($_GET['action'])
{
	case 'list':
		echo 'listing entities';
		break;
	case 'create':
		class_exists($_GET['entity_type']) || require(NLB_LIB_ROOT.$_GET['entity_type'].'.class.php');
		$e = new $_GET['entity_type'];
		$e->setType(strtolower($_GET['entity_type']));
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
		echo 'editing entity '.$_GET['eid'];
		break;
	case 'delete':
		echo 'deleting entity '.$_GET['eid'];
		break;
	case 'setstatus':
		echo 'updating status of entity '.$_GET['eid'].' to '.$_GET['statusid'];
		break;
	case 'save':
		echo 'saving entity';
		break;
	default:
		echo 'No action specified... Nothing to do!';
}