<?php
App::uses('AppController', 'Controller');

/**
 * Answers Controller
 *
 * @property Answer $Answer
 * @property PaginatorComponent $Paginator
 */
class AnswersController extends AppController
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
    public function index()
    {
        $this->Answer->recursive = 0;
        $this->set('answers', $this->Paginator->paginate());
    }

    public function beforeFilter()
    {
        parent::beforeFilter();
        $this->Auth->allow('add', 'findbyQuestion');
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
        if (!$this->Answer->exists($id)) {
            throw new NotFoundException(__('Invalid answer'));
        }
        $options = array('conditions' => array('Answer.' . $this->Answer->primaryKey => $id));
        $this->set('answer', $this->Answer->find('first', $options));
    }

    public function findByQuestion($id = null)
    {
        if ($this->request->is('post') || $this->request->is('get')) {
            $temp_data = $this->Answer->Question->findById($id);

            $group_id = $temp_data['Group']['id'];
            $owner_id = $temp_data['Group']['user_id'];

            $authorized = false;

            if ($owner_id == $this->Auth->user('id')) {
                $authorized = true;
            } else {
                $options = array('conditions' => array('GroupUser.user_id' => $this->Auth->user('id'), 'GroupUser.group_id' => $group_id));
                $gu = $this->Answer->Question->Group->GroupUser->find('first', $options);
                $user_id = $gu['User']['id'];
                if ($user_id != null) {
                    $authorized = true;
                }
            }

            if ($authorized) {
                $answers = $this->Answer->findAllByQuestionId($id);
                $result['success'] = true;
                $result['answers'] = $answers;
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
     * add method
     *
     * @return void
     */
    public function add()
    {
        if ($this->request->is('post')) {
            $authorized = false;
            $question_id = $this->request->data['Answer']['question_id'];
            $temp = $this->Answer->Question->findById($question_id);
            $group_id = $temp['Group']['id'];
            $owner_id = $temp['Group']['user_id'];
            $user_id = $this->Auth->user('id');
            if ($owner_id != null && $user_id == $owner_id) {
                $authorized = true;
            }
            if (!$authorized) {
                $options = array('conditions' => array('GroupUser.group_id' => $group_id, 'GroupUser.user_id' => $user_id));
                $group = $this->Answer->Question->Group->GroupUser->find('first', $options);
                if (isset($group['User'])) {
                    $authorized = true;
                }
            }
            if ($authorized) {
                $this->Answer->create();
                $this->request->data['Answer']['user_id'] = $this->Auth->user('id');
                if ($this->Answer->save($this->request->data)) {
                    $result['message'] = 'Answer Added.';
                    $result['success'] = true;
                    $result['answer'] = $this->Answer->findById($this->Answer->id);
                    $this->set(array(
                        'result' => $result,
                        '_serialize' => array('result')
                    ));
                } else {
                    $result['message'] = 'Answer could not be added.';
                    $result['success'] = false;
                    $this->set(array(
                        'result' => $result,
                        '_serialize' => array('result')
                    ));
                }
            } else {
                $result['message'] = 'You are not authorized to perform this action.';
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
        if (!$this->Answer->exists($id)) {
            throw new NotFoundException(__('Invalid answer'));
        }
        if ($this->request->is(array('post', 'put'))) {
            if ($this->Answer->save($this->request->data)) {
                $this->Session->setFlash(__('The answer has been saved.'));
                return $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash(__('The answer could not be saved. Please, try again.'));
            }
        } else {
            $options = array('conditions' => array('Answer.' . $this->Answer->primaryKey => $id));
            $this->request->data = $this->Answer->find('first', $options);
        }
        $questions = $this->Answer->Question->find('list');
        $users = $this->Answer->User->find('list');
        $this->set(compact('questions', 'users'));
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
        $this->Answer->id = $id;
        if (!$this->Answer->exists()) {
            throw new NotFoundException(__('Invalid answer'));
        }
        $this->request->allowMethod('post', 'delete');
        if ($this->Answer->delete()) {
            $this->Session->setFlash(__('The answer has been deleted.'));
        } else {
            $this->Session->setFlash(__('The answer could not be deleted. Please, try again.'));
        }
        return $this->redirect(array('action' => 'index'));
    }
}
