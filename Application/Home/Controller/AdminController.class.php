<?php
    namespace Home\Controller;
    use Think\Controller;
    class AdminController extends BaseController {
        public function index () {
            $Info = M('information');
            $info = $Info -> find();
            $this -> assign('info', $info);
            $this -> display();
        }

        public function reviseInfo () {
            $Info = M('information');

            if (!I('post.title') && I('post.phone') && I('post.email') && I('post.address1') && I('post.address2')) {
                $this -> error('任何信息不得为空');
            }
            $data = array (
                'title' => I('post.title'),
                'phone' => I('post.phone'),
                'email' => I('post.email'),
                'address1' => I('post.address1'),
                'address2' => I('post.address2')
            );
            $Info -> where('id = 0') -> save($data);
            $this->success('修改成功');
        }

        public function changePwd () {
            $User = M('user');

            if (!I('post.newpwd')) {
                $this -> error('密码不得为空');
            }
            $user = array (
                'id' => session('user.id')
            );
            $pwd = array (
                'password' => I('post.newpwd')
            );
            $User -> where($user) -> save($pwd);
            $this -> success('修改成功');
        }
    }
