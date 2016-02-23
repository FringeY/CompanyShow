<?php
    namespace Home\Controller;
    use Think\Controller;
    class ArticleController extends Controller {
        public function index () {
            $Article = M('articles');
            $articles = $Article -> select();
            $this -> assign('articles', $articles);
            $this -> display('Admin:index');
        }

        public function article () {
            $Article = M('articles');
            $data = array (
                'id' => I('get.id')
            );

            if (!I('get.id') && I('get.id')!= 0) {
                $Article = M('articles');
                $articles = $Article -> select();
                $this -> assign('articles', $articles);
                $this -> display('Admin:index');
            } else {
                $article = $Article -> where($data) -> find();
                $articles = $Article -> select();

                if ($article) {
                    $info = array (
                        'id' => I('get.id'),
                        'article' => $article
                    );
                } else {
                    $this -> error('不存在此页面');
                }

                $this -> assign('info', $info);
                $this -> assign('articles', $articles);
                $this -> display('Admin:article');
            }
        }

        public function addArticle () {
            $Article = M('articles');
            if (!(I('post.title') && I('post.content'))) {
                $this -> error('标题和内容不得为空');
            }
            $upload = new \Think\Upload();
            $upload->maxSize = '3145728';
            $upload->exts = array('jpg','JPG','PNG', 'png','JPEG' ,'jpeg');
            $upload->rootPath = './Public/img/';
            $upload->savePath = 'articles/';
            $upload->saveName = time().'_'.mt_rand();
            // 上传文件
            $info   =   $upload->uploadOne($_FILES['pic']);
            // 上传错误提示错误信息
            if (!$info) {
                // $this -> error($upload -> getError());
                $data = array (
                    'title' => I('post.title'),
                    'content' => I('post.content'),
                    'date' => date('Y-m-d')
                );
                $Article->add($data);
                $this->success('上传成功');
            } else {// 上传成功
                $data = array (
                    'title' => I('post.title'),
                    'content' => I('post.content'),
                    'imgurl' => $info['savepath'].$info['savename'],
                    'date' => date('Y-m-d')
                );
                $Article->add($data);
                $this->success('上传成功');
            }
        }

        public function reviseArticle () {
            $Article = M('articles');
            $upload = new \Think\Upload();
            $upload->maxSize = '3145728';
            $upload->exts = array('jpg','JPG','PNG', 'png','JPEG' ,'jpeg');
            $upload->rootPath  =  "./Public/img/";
            $upload->savePath = 'articles/';
            $upload->saveName = time().'_'.mt_rand();
            // 上传文件
            $info   =   $upload->uploadOne($_FILES['changepic']) ? $upload->uploadOne($_FILES['changepic']) : $upload->uploadOne($_FILES['addpic']);
            $article = array (
                'id' => I('post.id')
            );
            // 上传错误提示错误信息
            if (!$info) {
                // $this -> error($upload -> getError());
                $data = array (
                    'title' => I('post.title'),
                    'content' => I('post.content'),
                    'date' => date('Y-m-d')
                );
                $Article->where($article)->save($data);
                $this->success('修改成功');
            } else {// 上传成功
                $imgurl = './Public/img/'.$Article -> where($article) -> getField('imgurl');
                if (file_exists($imgurl)) {
                    unlink($imgurl);
                }
                $data = array (
                    'title' => I('post.title'),
                    'content' => I('post.content'),
                    'imgurl' => $info['savepath'].$info['savename'],
                    'date' => date('Y-m-d')
                );
                dump($article);
                dump($data);
                $Article->where($article)->save($data);
                $this->success('修改成功');
            }
        }

        public function delArticle () {
            $Article = M('articles');
            $data = array (
                'id' => I('post.id')
            );
            $imgurl = './Public/img/'.$Article -> where($data) -> getField('imgurl');

            // 删除图片
            if (file_exists($imgurl)) {
                unlink($imgurl);
            }
            $Article -> where($data) -> delete();
            $res = array (
                'status' => 200,
                'info' => 'delete success'
            );
            $this->ajaxReturn($res);
        }
    }
