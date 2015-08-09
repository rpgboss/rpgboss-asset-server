<?php
/**
 * The Welcome Controller.
 *
 * A basic controller example.  Has examples of how to set the
 * response body and status.
 *
 * @package  app
 * @extends  Controller
 */
class Controller_ApiV1 extends \LayoutController
{

    /* creates a compressed zip file */
    private function create_zip($files = array(),$destination = '',$overwrite = false) {
        //if the zip file already exists and overwrite is false, return false
        if(file_exists($destination) && !$overwrite) { return false; }
        //vars
        $valid_files = array();
        //if files were passed in...
        if(is_array($files)) {
            //cycle through each file
            foreach($files as $file) {
                //make sure the file exists
                if(file_exists($file)) {
                    $valid_files[] = $file;
                }
            }
        }
        //if we have good files...
        if(count($valid_files)) {
            //create the archive
            $zip = new ZipArchive();
            if($zip->open($destination,$overwrite ? ZIPARCHIVE::OVERWRITE : ZIPARCHIVE::CREATE) !== true) {
                return false;
            }
            //add the files
            foreach($valid_files as $file) {
                $zip->addFile($file,$file);
            }
            //debug
            //echo 'The zip archive contains ',$zip->numFiles,' files with a status of ',$zip->status;

            //close the zip -- done!
            $zip->close();

            //check to make sure the file exists
            return file_exists($destination);
        }
        else
        {
            return false;
        }
    }

    protected function peformLogin()
    {
        $username = $this->param('username');
        $password = $this->param('password');

        if(Auth::login($username, $password)) {
            $sessionid = \Auth\Auth::get('login_hash');
        } else {
            $sessionid = '';
        }

        return $sessionid;
    }

    public function action_login()
    {

        $this->no_render();

        $sessionid = $this->peformLogin();

        $response = \Fuel\Core\Response::forge($sessionid);
        $response->set_header('Content-Type', 'text/plain; charset=utf-8');

        return $response;
    }

    public function action_login_with_redirect()
    {
        $this->no_render();

        $sessionid = $this->peformLogin();

        if($sessionid!='') {
            return \Fuel\Core\Response::redirect(\Fuel\Core\Input::get('redirect'));
        } else {
            return \Fuel\Core\Response::redirect('/login');
        }
    }

    public function action_checkpackagedownload()
    {
        $this->no_render();

        $id = $this->param('id');

        $package = Model_Package::find($id);

        $url = $package->url;

        $headers = get_headers($url, 1);
        $type = $headers["Content-Type"];

        $allHeaders = array();

        if(is_array($type)) {
            $allHeaders = $type;
        } else {
            $allHeaders[] = $type;
        }

        $result = 0;

        foreach($allHeaders as $header) {
            if(preg_match('#png|jpg|jpeg|mp3|zip|octet-stream|audio/mpeg3|audio/x-mpeg-3|text/javascript|application/javascript#i', $header)) {
                $result = 1;
            }
        }

        return \Fuel\Core\Response::forge($result);
    }

    public function action_downloadpackage()
    {
        $result = $this->action_checkpackagedownload();

        if($result->body()==1) {

            $id = $this->param('id');

            $package = Model_Package::find($id);

            $time = time();

            \Fuel\Core\File::create_dir("./",$time);

            $file = file_get_contents($package->url);

            $filename = basename($package->url);

            if(!is_dir(DOCROOT.'/'.$package->id)) {
                \Fuel\Core\File::create_dir(DOCROOT,$package->id);
            }

            \Fuel\Core\File::delete_dir(DOCROOT.'/'.$package->id, true);
            \Fuel\Core\File::create_dir(DOCROOT,$package->id);


            $urlDownloadFilePath = "./" . $package->id . '/' . $filename;
            $urlDownloadVersion = "./" . $package->id . '/version';
            $urlDownloadZipFilename = $package->id . ".zip";
            $urlDownloadZipPath = "./" . $package->id . "/" . $urlDownloadZipFilename;

            file_put_contents($urlDownloadFilePath, $file);
            file_put_contents($urlDownloadVersion, $package->version);

            $created = $this->create_zip(array(
                $urlDownloadFilePath,
                $urlDownloadVersion
            ),$urlDownloadZipPath,true);

            $response = new \Fuel\Core\Response();
            $response->set_header('Content-Type', 'application/octet-stream');
            $response->set_header('Cache-Control', 'must-revalidate, post-check=0, pre-check=0');
            $response->set_header('Pragma', 'public');
            $response->set_header('Expires', '0');
            $response->set_header('Content-Disposition', "attachment; filename=".$urlDownloadZipFilename);
            $response->set_header('Content-Transfer-Encoding', 'binary');
            $response->set_header('Content-Length', filesize($urlDownloadZipPath));
            $response->body(readfile($urlDownloadZipPath));
            return $response;
        }
    }


}
