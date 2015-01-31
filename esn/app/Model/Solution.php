<?php
App::uses('AppModel', 'Model');

/**
 * Solution Model
 *
 * @property Task $Task
 * @property User $User
 * @property Content $Content
 */
class Solution extends AppModel
{

    /**
     * Validation rules
     *
     * @var array
     */
    public $validate = array(
        'task_id' => array(
            'numeric' => array(
                'rule' => array('numeric'),
                //'message' => 'Your custom message here',
                //'allowEmpty' => false,
                //'required' => false,
                //'last' => false, // Stop validation after this rule
                //'on' => 'create', // Limit validation to 'create' or 'update' operations
            ),
        ),
        'user_id' => array(
            'numeric' => array(
                'rule' => array('numeric'),
                //'message' => 'Your custom message here',
                //'allowEmpty' => false,
                //'required' => false,
                //'last' => false, // Stop validation after this rule
                //'on' => 'create', // Limit validation to 'create' or 'update' operations
            ),
        ),
    );

    //The Associations below have been created with all possible keys, those that are not needed can be removed

    /**
     * belongsTo associations
     *
     * @var array
     */
    public $belongsTo = array(
        'Task' => array(
            'className' => 'Task',
            'foreignKey' => 'task_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        ),
        'User' => array(
            'className' => 'User',
            'foreignKey' => 'user_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        ),
        'Content' => array(
            'className' => 'Content',
            'foreignKey' => 'content_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        )
    );

    public function saveData($data = null)
    {
        if ($data == null) {
            return $this->save($data);
        } else {
            if (isset($data['Solution']['task_id'])) {
                $task_id = $data['Solution']['task_id'];
                $dt = new DateTime();
                $time_now = $dt->format('Y-m-d H:i:s');
                $this->Task->id = $task_id;
                if ($this->Task->exists()) {
                    $t=$this->Task->findById($task_id);
                    $task_end_date = $t['Task']['enddate'];
                    if ($time_now > $task_end_date) {
                        return false;
                    } else {
                        return $this->save($data);
                    }
                } else {
                    return $this->save($data);

                }
            } else {
                return $this->save($data);
            }
        }
    }


}
