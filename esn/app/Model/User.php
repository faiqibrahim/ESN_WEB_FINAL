<?php
App::uses('AppModel', 'Model');
App::uses('BlowfishPasswordHasher', 'Controller/Component/Auth');

/**
 * User Model
 *
 * @property Answer $Answer
 * @property Boardmessage $Boardmessage
 * @property Contact $Contact
 * @property Notification $Notification
 * @property Group $Group
 * @property Message $Message
 * @property Post $Post
 * @property Question $Question
 * @property Request $Request
 * @property Solution $Solution
 * @property Education $Education
 * @property Interest $Interest
 * @property Role $Role
 */
class User extends AppModel
{

    /**
     * Validation rules
     *
     * @var array
     */
    public $validate = array(
        'username' => array(
            'notEmpty' => array(
                'rule' => array('notEmpty'),
                //'message' => 'Your custom message here',
                //'allowEmpty' => false,
                //'required' => false,
                //'last' => false, // Stop validation after this rule
                //'on' => 'create', // Limit validation to 'create' or 'update' operations
            ),
        ),
        'password' => array(
            'notEmpty' => array(
                'rule' => array('notEmpty'),
                //'message' => 'Your custom message here',
                //'allowEmpty' => false,
                //'required' => false,
                //'last' => false, // Stop validation after this rule
                //'on' => 'create', // Limit validation to 'create' or 'update' operations
            ),
        ),
        'email' => array(
            'email' => array(
                'rule' => array('email'),
                //'message' => 'Your custom message here',
                //'allowEmpty' => false,
                //'required' => false,
                //'last' => false, // Stop validation after this rule
                //'on' => 'create', // Limit validation to 'create' or 'update' operations
            ),
        ),
        'firstname' => array(
            'notEmpty' => array(
                'rule' => array('notEmpty'),
                //'message' => 'Your custom message here',
                //'allowEmpty' => false,
                //'required' => false,
                //'last' => false, // Stop validation after this rule
                //'on' => 'create', // Limit validation to 'create' or 'update' operations
            ),
        ),
        'lastname' => array(
            'notEmpty' => array(
                'rule' => array('notEmpty'),
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
     * hasMany associations
     *
     * @var array
     */
    public $hasMany = array(
        'Answer' => array(
            'className' => 'Answer',
            'foreignKey' => 'user_id',
            'dependent' => false,
            'conditions' => '',
            'fields' => '',
            'order' => '',
            'limit' => '',
            'offset' => '',
            'exclusive' => '',
            'finderQuery' => '',
            'counterQuery' => ''
        ),
        'Boardmessage' => array(
            'className' => 'Boardmessage',
            'foreignKey' => 'user_id',
            'dependent' => false,
            'conditions' => '',
            'fields' => '',
            'order' => '',
            'limit' => '',
            'offset' => '',
            'exclusive' => '',
            'finderQuery' => '',
            'counterQuery' => ''
        ),
        'Notification' => array(
            'className' => 'Notification',
            'foreignKey' => 'user_id',
            'dependent' => false,
            'conditions' => '',
            'fields' => '',
            'order' => '',
            'limit' => '',
            'offset' => '',
            'exclusive' => '',
            'finderQuery' => '',
            'counterQuery' => ''
        ),
        'Contact' => array(
            'className' => 'Contact',
            'foreignKey' => 'user_id',
            'dependent' => false,
            'conditions' => '',
            'fields' => '',
            'order' => '',
            'limit' => '',
            'offset' => '',
            'exclusive' => '',
            'finderQuery' => '',
            'counterQuery' => ''
        ),
        'Group' => array(
            'className' => 'Group',
            'foreignKey' => 'user_id',
            'dependent' => false,
            'conditions' => '',
            'fields' => '',
            'order' => '',
            'limit' => '',
            'offset' => '',
            'exclusive' => '',
            'finderQuery' => '',
            'counterQuery' => ''
        ),
        'JoinRequest' => array(
            'className' => 'JoinRequest',
            'foreignKey' => 'user_id',
            'dependent' => false,
            'conditions' => '',
            'fields' => '',
            'order' => '',
            'limit' => '',
            'offset' => '',
            'exclusive' => '',
            'finderQuery' => '',
            'counterQuery' => ''
        ),
        'Message' => array(
            'className' => 'Message',
            'foreignKey' => 'user_id',
            'dependent' => false,
            'conditions' => '',
            'fields' => '',
            'order' => '',
            'limit' => '',
            'offset' => '',
            'exclusive' => '',
            'finderQuery' => '',
            'counterQuery' => ''
        ),
        'Post' => array(
            'className' => 'Post',
            'foreignKey' => 'user_id',
            'dependent' => false,
            'conditions' => '',
            'fields' => '',
            'order' => array('Post.created' => 'DESC'),
            'limit' => '',
            'offset' => '',
            'exclusive' => '',
            'finderQuery' => '',
            'counterQuery' => ''
        ),
        'Question' => array(
            'className' => 'Question',
            'foreignKey' => 'user_id',
            'dependent' => false,
            'conditions' => '',
            'fields' => '',
            'order' => '',
            'limit' => '',
            'offset' => '',
            'exclusive' => '',
            'finderQuery' => '',
            'counterQuery' => ''
        ),
        'Request' => array(
            'className' => 'Request',
            'foreignKey' => 'user_id',
            'dependent' => false,
            'conditions' => '',
            'fields' => '',
            'order' => '',
            'limit' => '',
            'offset' => '',
            'exclusive' => '',
            'finderQuery' => '',
            'counterQuery' => ''
        ),
        'Solution' => array(
            'className' => 'Solution',
            'foreignKey' => 'user_id',
            'dependent' => false,
            'conditions' => '',
            'fields' => '',
            'order' => '',
            'limit' => '',
            'offset' => '',
            'exclusive' => '',
            'finderQuery' => '',
            'counterQuery' => ''
        )
    );


    /**
     * hasAndBelongsToMany associations
     *
     * @var array
     */
    public $hasAndBelongsToMany = array(
        'Education' => array(
            'className' => 'Education',
            'joinTable' => 'users_educations',
            'foreignKey' => 'user_id',
            'associationForeignKey' => 'education_id',
            'unique' => 'keepExisting',
            'conditions' => '',
            'fields' => '',
            'order' => '',
            'limit' => '',
            'offset' => '',
            'finderQuery' => '',
        ),
        'Interest' => array(
            'className' => 'Interest',
            'joinTable' => 'users_interests',
            'foreignKey' => 'user_id',
            'associationForeignKey' => 'interest_id',
            'unique' => 'keepExisting',
            'conditions' => '',
            'fields' => '',
            'order' => '',
            'limit' => '',
            'offset' => '',
            'finderQuery' => '',
        ),
        'Role' => array(
            'className' => 'Role',
            'joinTable' => 'users_roles',
            'foreignKey' => 'user_id',
            'associationForeignKey' => 'role_id',
            'unique' => 'keepExisting',
            'conditions' => '',
            'fields' => '',
            'order' => '',
            'limit' => '',
            'offset' => '',
            'finderQuery' => '',
        ),
        'Message' => array(
            'className' => 'Message',
            'joinTable' => 'messages_users',
            'foreignKey' => 'user_id',
            'associationForeignKey' => 'message_id',
            'unique' => 'keepExisting',
            'conditions' => '',
            'fields' => '',
            'order' => '',
            'limit' => '',
            'offset' => '',
            'finderQuery' => '',
        ),
        'Chat' => array(
            'className' => 'Chat',
            'joinTable' => 'chats_users',
            'foreignKey' => 'user_id',
            'associationForeignKey' => 'chat_id',
            'unique' => 'keepExisting',
            'conditions' => '',
            'fields' => '',
            'order' => '',
            'limit' => '',
            'offset' => '',
            'finderQuery' => '',
        )
    );

    public function saveUser($data)
    {
        $data[$this->alias]['profilephoto_thumb'] = 'http://imadevelopers.com/esn/app/webroot/img/' . 'thumbs' . '/' . 'sample_profile_photo.jpg';
        $data[$this->alias]['coverphoto_thumb'] = 'http://imadevelopers.com/esn/app/webroot/img/' . 'thumbs' . '/' . 'sample_cover_photo.jpg';
        $data[$this->alias]['tagline'] = 'Write a line about yourself';
        $data[$this->alias]['contact'] = '+00-000-000-0000';
        $role['Role'][0] = 2;
        $data['Role'] = $role;
        return $this->save($data);
    }

    public function beforeSave($options = array())
    {
        if (isset($this->data[$this->alias]['password'])) {
            $passwordHasher = new BlowfishPasswordHasher();
            $this->data[$this->alias]['password'] = $passwordHasher->hash(
                $this->data[$this->alias]['password']
            );
        }
        return true;
    }

    public function getUserSuggestions($id)
    {
        //Test

        //Original
        $_interests = $this->UsersInterest->findAllByUserId($id);
        $interests_ids = array();
        foreach ($_interests as $interest) {
            array_push($interests_ids, $interest['UsersInterest']['interest_id']);
        }
        $options = array(
            'conditions' => array('UsersInterest.interest_id' => $interests_ids, 'UsersInterest.user_id !=' => $id),
            'order' => array('COUNT(UsersInterest.interest_id) DESC'),
            'group' => array('UsersInterest.user_id')
        );
        $r = $this->UsersInterest->find('all', $options);
        $user_ids = array();
        foreach ($r as $temp) {
            $contact_id = $temp['UsersInterest']['user_id'];
            if (!$this->areConnected($id, $contact_id)) {
                array_push($user_ids, $contact_id);
            }
        }

        $options = array();
        $options['conditions'] = array('User.id' => $user_ids);
        $this->recursive = -1;
        $final_users = $this->find('all', $options);
        return $final_users;
    }

    public function areConnected($user, $contact)
    {
        $options = array('conditions' => array('Contact.user_id' => $user, 'Contact.user_id1' => $contact));
        $result = $this->Contact->find('all', $options);
        if (sizeof($result) > 0) {
            return true;
        } else {
            return false;
        }

    }

    public function getGroupSuggestions($id)
    {
        $_interests = $this->UsersInterest->findAllByUserId($id);
        $interests_ids = array();
        foreach ($_interests as $interest) {
            array_push($interests_ids, $interest['UsersInterest']['interest_id']);
        }

        $_groups = $this->Group->GroupUser->findAllByUserId($id);
        $groups_ids = array();
        foreach ($_groups as $group) {
            array_push($groups_ids, $group['GroupUser']['group_id']);
        }
        $_groups_owned = $this->Group->findAllByUserId($id);
        foreach ($_groups_owned as $group) {
            array_push($groups_ids, $group['Group']['id']);
        }
        $options = array(
            'conditions' => array('GroupsInterest.interest_id' => $interests_ids, "NOT" => array('GroupsInterest.group_id' => $groups_ids)),
            'order' => array('COUNT(GroupsInterest.interest_id) DESC'),
            'group' => array('GroupsInterest.group_id')
        );
        $r = $this->Group->GroupsInterest->find('all', $options);
        $_group_ids = array();
        foreach ($r as $temp) {
            array_push($_group_ids, $temp['GroupsInterest']['group_id']);
        }
        $_options = array();
        $_options['conditions'] = array('Group.id' => $_group_ids);
        $this->Group->recursive = -1;
        $final_groups = $this->Group->find('all', $_options);
        return $final_groups;
    }

}
