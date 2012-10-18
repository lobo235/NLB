<?php

class_exists('DatabaseTable') || require_once(NLB_LIB_ROOT.'dao/DatabaseTable.class.php');
class_exists('DatabaseColumn') || require_once(NLB_LIB_ROOT.'dao/DatabaseColumn.class.php');
class_exists('Entity') || require_once(NLB_LIB_ROOT.'dom/Entity.class.php');

/**
 * The Faq class represents a single Faq in the system
 */
class Faq extends Entity {
	/**
	 * The constructor for the Faq class
	 */
	public function __construct($faqid = NULL)
	{
		parent::__construct();
		$this->primaryIdColumn = 'faqid';
		
		$table = new DatabaseTable('faqs', 'faqid');
		$table->addColumn(new DatabaseColumn('faqid', 'hidden,primary,id'));
		$table->addColumn(new DatabaseColumn('question', 'text', 65536, array(
			'rows' => 6,
			'cols' => 80,
		)));
		$table->addColumn(new DatabaseColumn('answer', 'text,wysiwyg', 65536, array(
			'rows' => 6,
			'cols' => 80,
		)));
		$this->addTable($table);
		
		$this->setType('Faq');
		
		if($faqid !== NULL)
		{
			$this->setField('faqid', $faqid);
			$this->lookup();
		}
		$this->setIdentifierField('question');
	}

	/**
	 * Set the faqid for this Faq
	 * @param int $faqid the faq faqid
	 */
	public function setFaqid($faqid)
	{
		$this->setField('faqid', $faqid);
	}

	/**
	 * Set the question for this Faq
	 * @param string $question the question of this Faq
	 */
	public function setQuestion($question)
	{
		$this->setField('question', $question);
	}

	/**
	 * Set the answer for this Faq
	 * @param string $answer the answer of this Faq
	 */
	public function setAnswer($answer)
	{
		$this->setField('answer', $answer);
	}

	/**
	 * Returns the faqid of this Faq
	 * @return int the faqid for this Faq
	 */
	public function getFaqid()
	{
		return $this->getField('faqid');
	}

	/**
	 * Returns the question of this Faq
	 * @return string the question of this Faq
	 */
	public function getQuestion()
	{
		return $this->getField('question');
	}

	/**
	 * Returns the answer of this Faq
	 * @return string the answer of this Faq
	 */
	public function getAnswer()
	{
		return $this->getField('answer');
	}
}