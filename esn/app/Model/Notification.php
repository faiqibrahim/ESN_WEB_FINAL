<?php
App::uses('AppModel', 'Model');

/**
 * Notification Model
 *
 * @property User $User
 */
class Notification extends AppModel
{

    /**
     * Validation rules
     *
     * @var array
     */
    public $validate = array(
        'user_id' => array(
            'numeric' => array(
                'rule' => array('numeric'),
                //'message' => 'Your custom message here',
                //'allowEmpty' => false,
                //'required' => false,
                //'last' => false, // Stop validation after this rule
                //'on' => 'create', // Limit validation to 'create' or 'update' operations
            ),
        )
    );

    //The Associations below have been created with all possible keys, those that are not needed can be removed

    /**
     * belongsTo associations
     *
     * @var array
     */
    public $belongsTo = array(
        'User' => array(
            'className' => 'User',
            'foreignKey' => 'user_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        )
    );

    private function saveNotification($notification, $user_id)
    {
        $data = array(
            'Notification' => array(
                'user_id' => $user_id,
                'notification' => $notification,
                'status' => '0'
            ));
        $this->create();
        return $this->save($data);
    }

    public function saveMessageNotification($chat_id, $user_id)
    {

        $chat = $this->User->Chat->findById($chat_id);
        $chats_users = $chat['User'];
        $saveSuccess = true;
        $_user_ = $this->User->findById($user_id);
        $sender_name = $_user_['User']['firstname'];
        foreach ($chats_users as $user) {
            if ($user['id'] != $user_id) {
                $_user = $this->User->findById($user['id']);
                $notification = "You received new message from $sender_name";
                $saveSuccess = $this->saveNotification($notification, $_user['User']['id']);
            }

        }
        return $saveSuccess;

    }

    private function saveGroupNotification($notification, $group_id)
    {
        $group = $this->User->Group->findById($group_id);
        $group_users = $group['GroupUser'];
        $notification = $notification . " in " . $group['Group']['title'];
        $saveSuccess = true;
        foreach ($group_users as $groupUser) {
            $user_id = $groupUser['user_id'];
            $saveSuccess = $this->saveNotification($notification, $user_id);
        }
        return $saveSuccess;

    }

    public function saveAnnouncementNotification($group_id)
    {
        return $this->saveGroupNotification('New announcement', $group_id);
    }

    public function saveTaskNotification($group_id)
    {
        return $this->saveGroupNotification('New Task', $group_id);

    }

    public function saveQuestionNotification($group_id)
    {
        $group = $this->User->Group->findById($group_id);
        $owner_id = $group['Group']['user_id'];
        $group_name = $group['Group']['title'];
        $owner_notification = "New Question in group $group_name";
        $saveSuccess = $this->saveNotification($owner_notification, $owner_id);
        if ($saveSuccess)
            $notification = "New Question";
        $saveSuccess = $this->saveGroupNotification($notification, $group_id);
        return $saveSuccess;

    }

    public function saveContentNotification($group_id)
    {
        return $this->saveGroupNotification('New Content', $group_id);

    }

    public function saveConnectionNotification($user_id, $contact_id)
    {
        $user = $this->User->findById($contact_id);
        $sender_name = $user['User']['firstname'];
        $notification = "$sender_name connected to you";
        return $this->saveNotification($notification, $user_id);

    }

    public function markRead($notification, $user_id)
    {
        $conditions = array(
            'Notification.notification' => $notification,
            'Notification.user_id' => $user_id,
        );
        $data = array(
            'Notification.status' => 'true'
        );
        return $this->updateAll($data, $conditions);

    }

    public function getNotifications($user_id)
    {
        $options = array();
        $options['conditions'] = array(
            'Notification.user_id' => $user_id,
            'Notification.status' => '0'
        );
        $options['group'] = array(
            'Notification.notification'
        );
        $options['order'] = array(
            'Notification.created' => 'DESC'
        );
        $data = $this->find('all', $options);
        return $data;
    }

}