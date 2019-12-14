<?php

namespace app\admin\controller;

class System extends Common {

    public function index() {
        $this->assign('site', config()['site']);
        return $this->fetch();
    }

    public function save(){
        $data = $_POST;
        $file = $data['file'];
        unset($data['file']);
        unset($data['__hash__']);

        if($file == 'site.php'){
            if($data['title'] == ''){ $data['title'] = '静静设计'; }

            $arrurl           = explode('/', $_SERVER['PHP_SELF']);
            if($arrurl[1]     == 'admin.php'){
                $rooturl      = 'http://' . $_SERVER['SERVER_NAME'] . '/';
            } else {
                $rooturl      = 'http://' . $_SERVER['SERVER_NAME'] . $arrurl[1] . '/';
            }

            if($rooturl                 == ''){ $data['rooturl'] = $rooturl; }
            if($data['domain']          == ''){ $data['domain'] = 'http://www.jing-ui.com'; }
            if($data['title']           == ''){ $data['title'] = '静静设计'; }
            if($data['keywords']        == ''){ $data['keywords'] = 'JingJingui设计,网页设计,网站设计'; }
            if($data['description']     == ''){ $data['description'] = '设计师静静个人网站，分享设计，分享人生，提供优质的网页设计、UI设计、APP用户体验改善、UE设计等各项设计服务！'; }
            if($data['blogTitle']       == ''){ $data['blogTitle'] = '静静设计'; }
            if($data['blogKeywords']    == ''){ $data['blogKeywords'] = 'JingJingui设计,网页设计,网站设计'; }
            if($data['blogDescription'] == ''){ $data['blogDescription'] = '设计师静静个人网站，分享设计，分享人生，提供优质的网页设计、UI设计、APP用户体验改善、UE设计等各项设计服务！'; }
            if($data['contact']         == ''){ $data['contact'] = 'JingJingUi'; }
            if($data['company']         == ''){ $data['company'] = 'JingJingUI'; }
            if($data['email']           == ''){ $data['email'] = 'joucik@qq.com'; }
            if($data['phone']           == ''){ $data['phone'] = '123456'; }
            if($data['address']         == ''){ $data['address'] = '中国 • 上海'; }
            if($data['icp']             == ''){ $data['icp'] = ''; };
        }

        $content = "<?php\r\n // jingjingui 网站配置文件 \r\n return array(\r\n";
        foreach($data as $key => $value){
            $content .= "\t'$key' => '$value', \r\n";
            config($key, $value);
        }

        $content .= "); \r\n ?>";

        $r = @chmod($file, 0777);
        $hand = file_put_contents(__DIR__ . '/../../../config/site.php', $content);

        if(!$hand){
            $this->error($file, '写入失败！');
        } else {
            $cacheFile = env('runtime_path') . '~runtime.php';
            $result = false;

            if(is_file($cacheFile)){ $result = unlink($cacheFile); }

            if($result){
                $this->success('更新成功！');
            } else {
                $this->success('保存成功！');
            }
        }
    }
}
