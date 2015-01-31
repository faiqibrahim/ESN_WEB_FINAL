<?php
App::uses('AppController', 'Controller');

/**
 * Posts Controller
 *
 * @property Post $Post
 * @property PaginatorComponent $Paginator
 */
class PostsController extends AppController
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
        $this->Auth->allow('findByUser', 'getForUserProfile', 'findByGroup', 'add', 'delete', 'edit', 'getHomePosts');
    }

    public function index()
    {
        $this->Post->recursive = 0;
        $this->set('posts', $this->Paginator->paginate());
    }

    public function findByUser($id = null)
    {
        if ($this->request->is('post') || $this->request->is('get')) {
            if ($this->Auth->user('id') != null) {
                $posts = $this->Post->findAllByUserId($id);
                $result['success'] = true;
                $result['posts'] = $posts;
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

    private function addArrayToArray($src_array, $values)
    {
        foreach ($values as $value) {
            array_push($src_array, $value);
        }
        return $src_array;
    }


    public function getHomePosts()
    {
        if ($this->request->is('post') || $this->request->is('get')) {
            $user_id = $this->Auth->user('id');
            $result = array();
            $result['posts'] = array();
            if ($user_id != null) {
                $result['posts'] = $this->addArrayToArray($result['posts'], $this->Post->findAllByUserId($user_id));
                $followers = $this->Post->User->Contact->find('all', array('conditions' => array('Contact.user_id' => $user_id)));
                foreach ($followers as $follower) {
                    $u_id = $follower['User']['id'];
                    $result['posts'] = $this->addArrayToArray($result['posts'], $this->Post->find('all', array('conditions' => array('Post.user_id' => $u_id, 'Post.group_id' => null))));
                }
                $groups = $this->Post->Group->GroupUser->findAllByUserId($user_id);
                foreach ($groups as $group) {
                    $group_id = $group['Group']['id'];
                    $result['posts'] = $this->addArrayToArray($result['posts'], $this->Post->find('all', array('conditions' => array('Post.group_id' => $group_id))));
                }
                usort($result['posts'], function ($a, $b) {
                    $ad = new DateTime($a['Post']['modified']);
                    $bd = new DateTime($b['Post']['modified']);

                    if ($ad == $bd) {
                        return 0;
                    }

                    return $ad < $bd ? 1 : -1;
                });
                $result['success'] = true;
                $this->set(array(
                    'result' => $result,
                    '_serialize' => array('result')
                ));
            } else {
                $result['success'] = false;
                $result['message'] = 'Not Authorized';
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

    public function getForUserProfile($id = null)
    {
        if ($this->request->is('post') || $this->request->is('get')) {
            if ($this->Auth->user('id') != null) {
                $options = array('conditions' => array('Post.group_id' => null, 'Post.user_id' => $id));
                $posts = $this->Post->find('all', $options);
                $result['success'] = true;
                $result['posts'] = $posts;
                usort($result['posts'], function ($a, $b) {
                    $ad = new DateTime($a['Post']['created']);
                    $bd = new DateTime($b['Post']['created']);

                    if ($ad == $bd) {
                        return 0;
                    }

                    return $ad < $bd ? 1 : -1;
                });
                $this->set(array(
                    'result' => $result,
                    '_serialize' => array('result')
                ));
            } else {
                $result['success'] = false;
                $result['posts'] = null;
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

    public function findByGroup($id = null)
    {
        if ($this->request->is('post') || $this->request->is('get')) {
            $authorized = false;
            $user_id = $this->Auth->user('id');
            $g = $this->Post->Group->findById($id);
            $owner_id = $g['Group']['user_id'];

            if ($user_id == $owner_id) {
                $authorized = true;
            }
            $temp = $this->Post->Group->GroupUser->find('first', array('conditions' => array('GroupUser.user_id' => $user_id, 'GroupUser.group_id' => $id)));
            if (sizeof($temp) > 0) {
                $authorized = true;
            }
            if ($authorized) {
                $options = array(
                    'conditions' => array('Post.group_id' => $id),
                    'order' => array('Post.created' => 'DESC'));
                $posts = $this->Post->find('all', $options);
                $result['success'] = true;
                $result['posts'] = $posts;
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
        if (!$this->Post->exists($id)) {
            throw new NotFoundException(__('Invalid post'));
        }
        $options = array('conditions' => array('Post.' . $this->Post->primaryKey => $id));
        $this->set('post', $this->Post->find('first', $options));
    }

    /**
     * add method
     *
     * @return void
     */
    public function add()
    {
        if ($this->request->is('post')) {
            $this->Post->create();
            $this->request->data['Post']['user_id'] = $this->Auth->user('id');

            if ($this->Post->save($this->request->data)) {
                $new_post = $this->Post->findById($this->Post->id);
                $result['success'] = true;
                $result['post'] = $new_post;
                $result['message'] = 'Post has been saved';
                $this->set(array(
                    'result' => $result,
                    '_serialize' => array('result')
                ));
            } else {
                $result['success'] = false;
                $result['message'] = 'Post could not be saved';
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
     * edit method
     *
     * @throws NotFoundException
     * @param string $id
     * @return void
     */
    public function edit($id = null)
    {
        if ($this->request->is('post')) {
            if (!$this->Post->exists($id)) {
                $result['success'] = false;
                $result['message'] = 'Invalid Post';
                $this->set(array(
                    'result' => $result,
                    '_serialize' => array('result')
                ));
            } else {
                $p = $this->Post->findById($id);
                if ($p['Post']['user_id'] == $this->Auth->user('id')) {
                    if ($this->Post->save($this->request->data)) {
                        $result['success'] = true;
                        $result['post'] = $this->Post->findById($id);
                        $result['message'] = 'Post has been saved';
                        $this->set(array(
                            'result' => $result,
                            '_serialize' => array('result')
                        ));
                    } else {
                        $result['success'] = false;
                        $result['message'] = 'Post could not be saved';
                        $this->set(array(
                            'result' => $result,
                            '_serialize' => array('result')
                        ));
                    }
                } else {
                    $result['success'] = false;
                    $result['message'] = 'You are not authorized to perform this action';
                    $this->set(array(
                        'result' => $result,
                        '_serialize' => array('result')
                    ));
                }


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
     * delete method
     *
     * @throws NotFoundException
     * @param string $id
     * @return void
     */
    public function delete($id = null)
    {
        if ($this->request->is('post') || $this->request->is('delete')) {
            $this->Post->id = $id;
            if (!$this->Post->exists()) {
                $result['success'] = false;
                $result['message'] = 'Invalid Post';
                $this->set(array(
                    'result' => $result,
                    '_serialize' => array('result')
                ));
            } else {
                $p=$this->Post->findById($id);
                if ($p['Post']['user_id'] == $this->Auth->user('id')) {
                    if ($this->Post->delete()) {
                        $result['success'] = true;
                        $result['message'] = 'The post has been deleted';
                        $this->set(array(
                            'result' => $result,
                            '_serialize' => array('result')
                        ));
                    } else {
                        $result['success'] = false;
                        $result['message'] = 'The post could not be deleted';
                        $this->set(array(
                            'result' => $result,
                            '_serialize' => array('result')
                        ));
                    }
                } else {
                    $result['success'] = false;
                    $result['message'] = 'You are not authorized to perform this action';
                    $this->set(array(
                        'result' => $result,
                        '_serialize' => array('result')
                    ));
                }

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
}
