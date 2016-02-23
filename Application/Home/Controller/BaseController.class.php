<?php
namespace Home\Controller;
use Think\Controller;
class BaseController extends Controller {
    public function _initialize() {
        $id = session('user.id');
        if (!id) {
            $this->error('请先登录', U('Login/index'));
        } else {
            $this->assign('username', $username);
            $this->assign('id', $id);
        }
    }
}