<?php
    namespace Home\Controller;
    use Think\Controller;
    class IndexController extends Controller {
        public function index (){
            $Info = M('information');
            $Article = M('articles');
            $articles = $Article -> select();
            $info = $Info -> find();

            foreach ($articles as $key => $val) {
                $pattern = array("\r\n", "\r", "\n");
                $replacement = '<br>';
                $string = $val['content'];
                $articles[$key]['content'] = str_replace($pattern, $replacement, $string);

            }

            $this -> assign('info', $info);
            $this -> assign('articles', $articles);
            $this -> display();
        }
    }
