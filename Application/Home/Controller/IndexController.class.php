<?php
    namespace Home\Controller;
    use Think\Controller;
    class IndexController extends Controller {
        public function index (){
            $Info = M('information');
            $Intro = M('introduction');
            $Article = M('articles');
            $Service = M('service');
            $Team = M('team');
            $Finance = M('finance');
            $Bg = M('background');

            $info = $Info -> find();
            $intro = $Intro -> find();
            $finance = $Finance -> find();
            $articles = $Article -> select();
            $services = $Service -> select();
            $members = $Team -> select();
            $bg = $Bg -> select();

            $pattern = array("\r\n", "\r", "\n");
            $intro['content'] = str_replace($pattern, '<br>', $intro['content']);
            $finance['content'] = str_replace($pattern, '<br>', $finance['content']);

            foreach ($articles as $key => $val) {
                $replacement = '<br>';
                $string = $val['content'];
                $articles[$key]['content'] = str_replace($pattern, $replacement, $string);
            }

            foreach ($members as $key => $val) {
                $replacement = '<br>';
                $string = $val['content'];
                $members[$key]['content'] = str_replace($pattern, $replacement, $string);
            }

            foreach ($services as $key => $val) {
                $services[$key]['kind'] = explode("\n", $val['kind']);
                $replacement = '<br>';
                $string = $val['content'];
                $services[$key]['content'] = str_replace($pattern, $replacement, $string);
            }

            $this -> assign('info', $info);
            $this -> assign('articles', $articles);
            $this -> assign('intro', $intro);
            $this -> assign('services', $services);
            $this -> assign('members', $members);
            $this -> assign('finance', $finance);
            $this -> assign('bg', $bg);
            $this -> display();
        }
    }
