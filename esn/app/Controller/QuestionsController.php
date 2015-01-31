<?php
App::uses('AppController', 'Controller');

/**
 * Questions Controller
 *
 * @property Question $Question
 * @property PaginatorComponent $Paginator
 */
class QuestionsController extends AppController
{

    /**
     * Components
     *
     * @var array
     */
    public $components = array('Paginator', 'RequestHandler');

    /**
     * index method
     *
     * @return void
     */
    public function beforeFilter()
    {
        parent::beforeFilter();
        $this->Auth->allow('add', 'getByGroup');
    }

    public function index()
    {
        $this->Question->recursive = 0;
        $this->set('questions', $this->Paginator->paginate());
    }

    public function getByGroup($id = null)
    {
        if ($this->request->is('post') || $this->request->is('get')) {
            $authorized = false;
            $user_id = $this->Auth->user('id');
            $g = $this->Question->Group->findById($id);
            $owner_id = $g['Group']['user_id'];

            if ($user_id == $owner_id) {
                $authorized = true;
            }
            $temp = $this->Question->Group->GroupUser->find('first', array('conditions' => array('GroupUser.user_id' => $user_id, 'GroupUser.group_id' => $id)));
            if (sizeof($temp) > 0) {
                $authorized = true;
            }
            if ($authorized) {
                $options = array(
                    'conditions' => array('Question.group_id' => $id),
                    'order' => array('Question.created' => 'DESC'));
                $posts = $this->Question->find('all', $options);
                $result['success'] = true;
                $result['questions'] = $posts;
                $this->set(array(
                    'result' => $result,
                    '_serialize' => array('result')
                ));
            } else {
                $result['success'] = false;
                $result['message'] = 'You are not authorized to perform this action';
                $this->set(array(
                    'result' => $result,
                    '_serialize' => array('result')
                ));
            }

        } else {
            $result['success'] = false;
            $result['message'] = 'Invalid Request';
            $this->set(array(
                'result' => $result,
                '_serialize' => array('result')
            ));
        }
    }

    /**
     * view method
     *
     * @throws NotFoundException
     * @param string $id
     * @return void
     */
    public function view($id = null)
    {
        if (!$this->Question->exists($id)) {
            throw new NotFoundException(__('Invalid question'));
        }
        $options = array('conditions' => array('Question.' . $this->Question->primaryKey => $id));
        $this->set('question', $this->Question->find('first', $options));
    }

    /**
     * add method
     *
     * @return void
     */
    public function add()
    {
        if ($this->request->is('post')) {
            $this->Question->create();
            $this->request->data['Question']['user_id'] = $this->Auth->user('id');
            if ($this->Question->save($this->request->data)) {
                $group_id = $this->request->data['Question']['group_id'];
                $this->Question->User->Notification->saveQuestionNotification($group_id);
                $result['message'] = 'Question Added.';
                $result['success'] = true;
                $result['question'] = $this->Question->findById($this->Question->id);
                $this->set(array(
                    'result' => $result,
                    '_serialize' => array('result')
                ));
            } else {
                $result['message'] = 'Question could not be added.';
                $result['success'] = false;
                $this->set(array(
                    'result' => $result,
                    '_serialize' => array('result')
                ));
            }
        } else {
            $result['message'] = 'Invalid Request';
            $result['success'] = false;
            $this->set(array(
                'result' => $result,
                '_serialize' => array('result')
            ));

        }
    }

    /**
     * edit method
     *
     * @throws NotFoundException
     * @param string $id
     * @return void
     */
    public function edit($id = null)
    {
        if (!$this->Question->exists($id)) {
            throw new NotFoundException(__('Invalid question'));
        }
        if ($this->request->is(array('post', 'put'))) {
            if ($this->Question->save($this->request->data)) {
                $this->Session->setFlash(__('The question has been saved.'));
                return $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash(__('The question could not be saved. Please, try again.'));
            }
        } else {
            $options = array('conditions' => array('Question.' . $this->Question->primaryKey => $id));
            $this->request->data = $this->Question->find('first', $options);
        }
        $users = $this->Question->User->find('list');
        $groups = $this->Question->Group->find('list');
        $this->set(compact('users', 'groups'));
    }

    /**
     * delete method
     *
     * @throws NotFoundException
     * @param string $id
     * @return void
     */
    public function delete($id = null)
    {
        $this->Question->id = $id;
        if (!$this->Question->exists()) {
            throw new NotFoundException(__('Invalid question'));
        }
        $this->request->allowMethod('post', 'delete');
        if ($this->Question->delete()) {
            $this->Session->setFlash(__('The question has been deleted.'));
        } else {
            $this->Session->setFlash(__('The question could not be deleted. Please, try again.'));
        }
        return $this->redirect(array('action' => 'index'));
    }
}
