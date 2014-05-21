<?php
namespace JobQueue;

use Zend\Db\Sql\Sql;
use Zend\Paginator\Adapter\DbSelect;
use Zend\Paginator\Paginator;

use Zend\Db\TableGateway\AbstractTableGateway;
use Zend\Db\Adapter\Adapter;
use Zend\Db\ResultSet\ResultSet;
use Zend\ServiceManager\ServiceLocatorAwareInterface;

class JobQueueModel extends AbstractTableGateway 
{

    public $table = 'jobqueue_details';
    
    protected $_serviceLocator;
    
    /**
     * Constructor
     *
     * @access pubic
     * @param Adapter $adapter
     *            // Adapter instance
     */
    public function __construct($adapter)
    {
    	$this->adapter = $adapter;
    	$this->resultSetPrototype = new ResultSet(ResultSet::TYPE_ARRAY);
    	$this->initialize();
    }

    /**
     * Fetch Job Queue Details
     *
     * @access public
     * @param array $where
     *            // Conditions
     * @param array $columns
     *            // Specific column names
     * @param string $orderBy
     *            // Order By conditions
     * @param boolean $paging
     *            // Flag for paging
     * @return array
     */
    public function getJobQueueDetails($where = array(), $columns = array(), $orderBy = '', $paging = false)
    {
        try {
            $sql = new Sql($this->getAdapter());
            $select = $sql->select()->from(array(
                't1' => $this->table
            ));
            
            if (count($where) > 0) {
                $select->where($where);
            }
            
            if (count($columns) > 0) {
                $select->columns($columns);
            }
            
            if (! empty($orderBy)) {
                $select->order($orderBy);
            }
            
            if ($paging) {
                
                $dbAdapter = new DbSelect($select, $this->getAdapter());
                $paginator = new Paginator($dbAdapter);
                
                return $paginator;
            } else {
                $statement = $sql->prepareStatementForSqlObject($select);
                
                $categories = $this->resultSetPrototype->initialize($statement->execute())
                    ->toArray();
                
                return $categories;
            }
        } catch (\Exception $e) {
            throw new \Exception($e->getPrevious()->getMessage());
        }
    }

	/**
	 * @desc To save the JobQueueID in DB
	 * @param unknown $data
	 * @throws \Exception
	 */
    public function saveJobQueue($data)
    {
        try {
            $sql = new Sql($this->getAdapter());
            $insert = $sql->insert($this->table);
            $insert->values($data);
            $statement = $sql->prepareStatementForSqlObject($insert);
            $statement->execute();
        } catch (\Exception $e) {
            throw new \Exception($e->getPrevious()->getMessage());
        }
    }
    

    
    
}
