<?php
    namespace Home\Controller;
    use Think\Controller;
    class LoginController extends Controller {
    public function index () {
        $this -> display('Admin:login');
    }

    //登录
    public function login () {
        if (IS_POST){
            $id = I('post.username');
            $password = I('post.password');
            $data = $this->verify($id, $password);
        }else{
            $this->error('非法请求');
        }
    }

    //注销
    public function logout() {
        session(null);
        cookie(null);
        $this->success('注销成功!', U('Login/index'));
    }

    //验证
    private function verify($id, $password) {
        $where = array(
            'id' => $id,
            'password' => $password,
        );

        $data = M('user')->where($where)->find();
        $name = array(
            'id' => $id
        );

        if ($data) {
            session('user.id', $data['id']);
            $this->success('登录成功', U('Index/index'));
        } else {
            $this->error('用户名或密码错误');
        }
    }
}
