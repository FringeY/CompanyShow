<?php
    namespace Home\Controller;
    use Think\Controller;
    class AdminController extends BaseController {
        public function index () {
            $Info = M('information');
            $Bg = M('background');
            $info = $Info -> find();
            $bgs = $Bg -> select();
            $this -> assign('info', $info);
            $this -> assign('bgs', $bgs);
            $this -> display();
        }

        public function reviseInfo () {
            $Info = M('information');

            if (!I('post.title') || !I('post.phone') || !I('post.email') || !I('post.address1') || !I('post.address2')) {
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

        public function addBackground () {
            $Bg = M('background');

            $upload = new \Think\Upload();
            $upload->maxSize = '3145728';
            $upload->exts = array('jpg','JPG','PNG', 'png','JPEG' ,'jpeg');
            $upload->rootPath  =  "./Public/img/";
            $upload->savePath = 'bg/';
            $upload->saveName = time().'_'.mt_rand();
            // 上传文件
            $info   =   $upload->uploadOne($_FILES['addbg']);

            // 上传错误提示错误信息
            if (!$info) {
                $this -> error($upload -> getError());
            } else {// 上传成功
                $data = array (
                    'imgurl' => $info['savepath'].$info['savename']
                );
                $Bg -> where($bg) -> add($data);
                $this->success('添加成功');
            }
        }

        public function changeBackground () {
            $Bg = M('background');
            $bg = array (
                'id' => I('post.id')
            );

            $upload = new \Think\Upload();
            $upload->maxSize = '3145728';
            $upload->exts = array('jpg','JPG','PNG', 'png','JPEG' ,'jpeg');
            $upload->rootPath  =  "./Public/img/";
            $upload->savePath = 'bg/';
            $upload->saveName = time().'_'.mt_rand();
            // 上传文件
            $info   =   $upload->uploadOne($_FILES['changebg']);

            // 上传错误提示错误信息
            if (!$info) {
                $this -> error($upload -> getError());
            } else {// 上传成功
                $imgurl = './Public/img/'.$Bg -> where($bg) -> getField('imgurl');
                if (file_exists($imgurl)) {
                    unlink($imgurl);
                }
                $data = array (
                    'imgurl' => $info['savepath'].$info['savename']
                );
                $Bg -> where($bg) -> save($data);
                $this->success('修改成功');
            }
        }

        public function delBackground () {
            $Bg = M('background');
            $bg = array (
                'id' => I('post.id')
            );
            if (($Bg -> count()) <= 1) {
                $res = array (
                    'status' => 400,
                    'info' => '删除失败, 最少保存一张图片'
                );
            } else {
                $imgurl = './Public/img/'.$Bg -> where($bg) -> getField('imgurl');
                if (file_exists($imgurl)) {
                    unlink($imgurl);
                }
                $Bg -> where($bg) -> delete();
                $res = array (
                    'status' => 200,
                    'info' => '删除成功'
                );
            }
            $this->ajaxReturn($res);
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

        public function guest () {
            $Guest = M('guest');
            $guests = $Guest -> order('id desc') -> select();
            $this -> assign('guests', $guests);
            $this -> display('Admin:guests');
        }

        public function guestpost () {
            $Guest = M('guest');
            $data = array (
                'name' => I('post.name'),
                'phone' => I('post.phone'),
                'email' => I('post.email'),
                'message' => I('post.message')
            );
            $Guest -> add($data);
            $this -> ajaxReturn('');
        }

        public function intro () {
            $Intro = M('introduction');
            $info = $Intro -> find();
            $this -> assign('info', $info);
            $this -> display('Admin:intro');
        }

        public function finance () {
            $Finance = M('finance');
            $info = $Finance -> find();
            $this -> assign('info', $info);
            $this -> display('Admin:finance');
        }

        public function service () {
            $Service = M('service');
            $services = $Service -> select();
            $service = array (
                'id' => I('get.id')
            );

            $info = $Service -> where($service) -> find();
            $this -> assign('info', $info);
            $this -> assign('services', $services);
            $this -> display('Admin:service');
        }

        public function team () {
            $Team = M('team');
            $members = $Team -> select();
            $member = array (
                'id' => I('get.id')
            );

            $info = $Team -> where($member) -> find();
            $this -> assign('info', $info);
            $this -> assign('members', $members);
            $this -> display('Admin:team');
        }

        public function reviseIntro () {
            $Intro = M('introduction');

            if (!I('post.brief') || !I('post.content')) {
                $this -> error('简介,详情不得为空');
            }

            $upload = new \Think\Upload();
            $upload->maxSize = '3145728';
            $upload->exts = array('jpg','JPG','PNG', 'png','JPEG' ,'jpeg');
            $upload->rootPath  =  "./Public/img/";
            $upload->savePath = 'intro/';
            $upload->saveName = time().'_'.mt_rand();
            // 上传文件
            $info   =   $upload->uploadOne($_FILES['changepic']);

            // 上传错误提示错误信息
            if (!$info) {
                // $this -> error($upload -> getError());
                $data = array (
                    'brief' => I('post.brief'),
                    'content' => I('post.content')
                );
                $Intro -> where('id = 0') -> save($data);
                $this->success('修改成功');
            } else {// 上传成功
                $imgurl = './Public/img/'.$Intro -> where('id = 0') -> getField('imgurl');
                if (file_exists($imgurl)) {
                    unlink($imgurl);
                }
                $data = array (
                    'brief' => I('post.brief'),
                    'content' => I('post.content'),
                    'imgurl' => $info['savepath'].$info['savename']
                );
                $Intro -> where('id = 0') -> save($data);
                $this->success('修改成功');
            }

        }

        public function reviseFinance () {
            $Finance = M('finance');

            if (!I('post.brief') || !I('post.content')) {
                $this -> error('简介,详情不得为空');
            }

            $upload = new \Think\Upload();
            $upload->maxSize = '3145728';
            $upload->exts = array('jpg','JPG','PNG', 'png','JPEG' ,'jpeg');
            $upload->rootPath  =  "./Public/img/";
            $upload->savePath = 'finance/';
            $upload->saveName = time().'_'.mt_rand();
            // 上传文件
            $info   =   $upload->uploadOne($_FILES['changepic']);

            // 上传错误提示错误信息
            if (!$info) {
                // $this -> error($upload -> getError());
                $data = array (
                    'brief' => I('post.brief'),
                    'content' => I('post.content')
                );
                $Finance -> where('id = 0') -> save($data);
                $this->success('修改成功');
            } else {// 上传成功
                $imgurl = './Public/img/'.$Finance -> where('id = 0') -> getField('imgurl');
                if (file_exists($imgurl)) {
                    unlink($imgurl);
                }
                $data = array (
                    'brief' => I('post.brief'),
                    'content' => I('post.content'),
                    'imgurl' => $info['savepath'].$info['savename']
                );
                $Finance -> where('id = 0') -> save($data);
                $this->success('修改成功');
            }

        }


        public function reviseService () {
            $Service = M('service');
            $service = array (
                'id' => I('post.id')
            );

            if (!I('post.name') || !I('post.brief') || !I('post.content') || !I('post.kind')) {
                $this -> error('简介,详情,类别不得为空');
            }

            $upload = new \Think\Upload();
            $upload->maxSize = '3145728';
            $upload->exts = array('jpg','JPG','PNG', 'png','JPEG' ,'jpeg');
            $upload->rootPath  =  "./Public/img/";
            $upload->savePath = 'service/';
            $upload->saveName = time().'_'.mt_rand();
            // 上传文件
            $info   =   $upload->uploadOne($_FILES['changepic']);

            // 上传错误提示错误信息
            if (!$info) {
                // $this -> error($upload -> getError());
                $data = array (
                    'name' => I('post.name'),
                    'brief' => I('post.brief'),
                    'content' => I('post.content'),
                    'kind' => I('post.kind')
                );
                $Service -> where($service) -> save($data);
                $this->success('修改成功');
            } else {// 上传成功
                $imgurl = './Public/img/'.$Service -> where($service) -> getField('imgurl');
                if (file_exists($imgurl)) {
                    unlink($imgurl);
                }
                $data = array (
                    'name' => I('post.name'),
                    'brief' => I('post.brief'),
                    'content' => I('post.content'),
                    'kind' => I('post.kind'),
                    'imgurl' => $info['savepath'].$info['savename']
                );
                $Service -> where($service) -> save($data);
                $this->success('修改成功');
            }
        }

        public function reviseTeam () {
            $Team = M('team');
            $member = array (
                'id' => I('post.id')
            );

            if (!I('post.name') || !I('post.content')) {
                $this -> error('简介,详情,类别不得为空');
            }

            $upload = new \Think\Upload();
            $upload->maxSize = '3145728';
            $upload->exts = array('jpg','JPG','PNG', 'png','JPEG' ,'jpeg');
            $upload->rootPath  =  "./Public/img/";
            $upload->savePath = 'team/';
            $upload->saveName = time().'_'.mt_rand();
            // 上传文件
            $info   =   $upload->uploadOne($_FILES['changepic']);

            // 上传错误提示错误信息
            if (!$info) {
                // $this -> error($upload -> getError());
                $data = array (
                    'name' => I('post.name'),
                    'content' => I('post.content'),
                );
                $Team -> where($member) -> save($data);
                $this->success('修改成功');
            } else {// 上传成功
                $imgurl = './Public/img/'.$Team -> where($member) -> getField('imgurl');
                if (file_exists($imgurl)) {
                    unlink($imgurl);
                }
                $data = array (
                    'name' => I('post.name'),
                    'content' => I('post.content'),
                    'imgurl' => $info['savepath'].$info['savename']
                );
                $Team -> where($member) -> save($data);
                $this->success('修改成功');
            }
        }
    }
