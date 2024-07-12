<?php


namespace app\api\controller\csmipr;

use app\common\library\Upload;
use app\common\exception\UploadException;
use addons\csmipr\library\xcore\xcore\base\XcAMyApi;
 

class XcmyApi extends XcAMyApi
{
    public function xinit()
    {
    }
    
    public function upload()
    {
        $attachment = null;
        //默认普通上传文件
        $file = $this->request->file('file');
        try {
            $upload = new Upload($file);
            $attachment = $upload->upload();
        } catch (UploadException $e) {
            $this->error($e->getMessage());
        }

        $this->success(__('Uploaded successful'), ['url' => $attachment->url, 'fullurl' => cdnurl($attachment->url, true)]);
    }
    
}
