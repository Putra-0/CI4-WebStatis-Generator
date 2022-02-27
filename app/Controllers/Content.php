<?php

namespace App\Controllers;

use App\Controllers\AdminController;

class Content extends AdminController
{
    public function index()
    {
        $r = $this->response->setContentType('application/json');
        $path = $this->contentPath.'content/';
        $d = dir($path);
        $result = [];

        while (($file = $d->read()) !== false){
            $ext = explode('.',$file)[1] ?? null;
            if(is_file($path.$file && $ext=='md')){
                $result[] = ['path' => '', 'name' => explode('.', $file)[0] ];
            }else if(is_dir($path.$file)){
                $subpath = $path.$file.'/';
                $dd = dir($subpath);
                while(($f = $dd->read())  !== false){
                    $ext = explode('.',$f)[1] ?? null;
                    if(is_file($subpath.$f) && $ext == 'md'){
                        $file = $file == '.' ? '' : $file;
                        $result[] = ['path' => $file, 'name' => explode('.', $f)[0]];
                    }
                }
            }
        }
        $d->close();
        return $r->setJSON($result);
    }

    public function part()
    {
        $r = $this->response->setContentType('application/json');
        $content = file_get_contents($this->contentPath .'content/'. str_replace(':','/',$this->request->getVar('data')).'.md'); 
        return $r->setJSON($content); 
    }

    public function save()
    {
        $r = $this->response->setContentType('application/json');
        $foo = $this->generateContent(str_replace(':', '/', $this->request->getVar('name')), $this->request->getVar('content'));
		return $r->setJSON( ['status'=>'ok', 'name'=>$foo] );
    }

}
