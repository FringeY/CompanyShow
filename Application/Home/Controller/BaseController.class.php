<?php
namespace Home\Controller;
use Think\Controller;
class BaseController extends Controller {
    public function _initialize() {
        $id = session('user.id');
        $name = session('user.name');
        if (!id && !name) {
            $this->error('请先登录', U('Login/index'));
        } else {
            $this->assign('name', $name);
            $this->assign('id', $id);
        }
    }
}