<?php
include_once  dirname(__DIR__)."\models\post.php";
include_once  dirname(__DIR__)."\models\path.php";
include_once  dirname(__DIR__)."\config\config.php";
class FTP
{
	private $posts;
	private $ftp;
	private $path;

	public function upload($table,$id)
	{
		$this->posts = new post();
		$this->path = new path();	
		$con = ftp_connect(ftp_server) or die("Could not connect to ftp_server");
        $ftp = ftp_login($con, ftp_username, ftp_password);
       	//Lay 1 record theo id
        $result = $this->posts->one_record($table,$id);  
		
        $fileput = dirname(__DIR__)."/models/new.html";
		$imgput = dirname(__DIR__)."/upload/images/";
		$filepath = dirname(__DIR__)."/upload/document/";
		//Cap nhat trang thai "Public" khi upload
        $this->posts->setstatusupload($table,$id);
        //Tao du lieu html de chen tham so -> upload
        $newcontent= $result[2];
        //Tao folder (ten folder = id) tren FTP server
		$new = $result[0];
		$images = 'images';
		$document = 'document';
		$path = $result[4];
		$filename='';
		if ($path) {
			$url = $this->path->getfolder($path);
			$d = $this->path->getname($path);
			$path = $url[0];
			$filename = $url[$d];
		} 
		$folder_list = ftp_nlist($con,'.');
		$folderimage_list = ftp_nlist($con,$images);
		$folderdocument_list = ftp_nlist($con,$document);
		//KTra thu muc images va document da ton tai chua?
		if ($this->path->checkfolder($folder_list,$images)) {
			ftp_mkdir($con,$images);
		}
		if ($this->path->checkfolder($folder_list,$document)) {
			ftp_mkdir($con,$document);
		}		
		//KT folderr new = id da ton tai chua	
		if ($filename) {
			if ($this->path->checkfolder($folderimage_list,$filename)) {
				ftp_mkdir($con,$images.'/'.$filename);	
			}	
			if ($this->path->checkfolder($folderdocument_list,$filename)) {
				ftp_mkdir($con,$document.'/'.$filename);	
			}	
		}else{
			if ($this->path->checkfolder($folderimage_list,$new)) {
				ftp_mkdir($con,$images.'/'.$new);
			}
			if ($this->path->checkfolder($folderdocument_list,$new)) {
				ftp_mkdir($con,$document.'/'.$new);	
			}
		}
		//Lay list file trong folder
		if ($filename) {
			$file_list_image = ftp_nlist($con,$images.'/'.$filename);
		}else{
			$file_list_image = ftp_nlist($con,$images.'/'.$new);
		}
		//$file_list_image = scandir(dirname(__DIR__)."/FTP/".$images.'/'.$new);
		$newlist_image = array();
		$newlist_document = array();
		if ($filename) {
			$file_list_document = ftp_nlist($con,$document.'/'.$filename); 
		}else{
			$file_list_document = ftp_nlist($con,$document.'/'.$new); 
		}
		//Load HTML from a string
		$content = new DOMDocument();
		$content->loadHTML($newcontent);
		//Tim kiem cac thanh phan voi tagname = 'img'
		$imageTags = $content->getElementsByTagName('img');
		//var_dump($imageTags); exit();
	    foreach($imageTags as $tag) {
	    	//Lay ten image trong src
	        $imgname = basename($tag->getAttribute('src'));
	        //gan ten nhung anh tai len ftp vao $newlist_image
	        $newlist_image[] = $imgname;
	        //upload img len FTP server
	        if ($result[4]) {
	        	ftp_put($con,$images."/".$filename."/".$imgname,$imgput.$imgname, FTP_BINARY);
	        }else{
	        	ftp_put($con,$images."/".$new."/".$imgname,$imgput.$imgname, FTP_BINARY);
	        }
	        //lay src trong the 'img'
	        $imgsrc = $tag->getAttribute('src');
	        //thay doi gia tri src trong the 'img'
	        if ($result[4]) {
	        	$newcontent = str_replace($imgsrc,"/".$images."/".$filename."/".$imgname,$newcontent);
	        }else{
	        	$newcontent = str_replace($imgsrc,"/".$images."/".$new."/".$imgname,$newcontent);
	        }
	    }
	    //tim kiem nhung image k upload len trong folder images
	    foreach ($file_list_image as $key => $value_file_list_image) {
	    	$kq = false;
	    	foreach ($newlist_image as $key => $value_newlist_image) {
	    		if ($filename) {
	    			if ($value_file_list_image == $images.'/'.$filename.'/'.$value_newlist_image) {
		    			$kq = true;
		    		}
	    		}else{
		    		if ($value_file_list_image == $images.'/'.$new.'/'.$value_newlist_image) {
		    			$kq = true;
		    		}
	    		}
	    	}
	    	if (!$kq) {
	    		//xoa
	    		ftp_delete($con,$value_file_list_image);
	    	}
	    }
	    $fileTags = $content->getElementsByTagName('a');
	    foreach ($fileTags as $key => $tag) {
	    	//Lay ten file trong href
	    	$imagename = basename($tag->getAttribute('href'));
	    	//gan ten nhung file tai len ftp vao $newlist_document
	        $newlist_document[] = $imagename;
	    	//upload file len FTP server
	        if ($result[4]) {
	        	ftp_put($con,$document."/".$filename."/".$imagename,$filepath.$imagename, FTP_BINARY);
	        }else{
	        	ftp_put($con,$document."/".$new."/".$imagename,$filepath.$imagename, FTP_BINARY);
	        }
	        //lay href trong the 'a'
	        $filehref = $tag->getAttribute('href');
	        //thay doi gia tri href trong the 'a'
	        
	        if ($result[4]) {
	        	$newcontent = str_replace($filehref,"/".$document."/".$filename."/".$imgname,$newcontent);
	        }else{
	        	$newcontent = str_replace($filehref,"/".$document."/".$new."/".$imagename,$newcontent);
	        }
	    }
	    //tim kiem nhung file k upload len trong folder images
	    foreach ($file_list_document as $key => $value_file_list_document) {
	    	$kq = false;
	    	foreach ($newlist_document as $key => $value_newlist_document) {
	    		if ($filename) {
	    			if ($value_file_list_document == $document.'/'.$filename.'/'.$value_newlist_document) {
		    			$kq = true;
		    		}
	    		}else{
		    		if ($value_file_list_document == $document.'/'.$new.'/'.$value_newlist_document) {
		    			$kq = true;
		    		}
		    	}	
	    	}
	    	if (!$kq) {
	    		//xoa
	    		ftp_delete($con,$value_file_list_document);
	    	}
	    }
	    //Add Template	
	    $template = $this->posts->one_record('template',$result[5]);
	    $file_template = file_get_contents(dirname(__DIR__)."//".$template[2],'r+');
  		$newcontent = str_replace('<p class="formcreatepost"></p>',$newcontent,$file_template);
  		$newcontent = str_replace('<title></title>','<title>'.$result[1].'</title>',$newcontent);
		//Banner 
		if (preg_match_all('/\<!-- area id=(.+)\-->/', $result[2], $matches2))
		{
			foreach ($matches2[1] as $key => $value) {
				$area = $this->posts->one_record('aras',$value); 
				$filearea = file_get_contents(dirname(__DIR__)."/models/area.txt",'r+');
				$filearea = str_replace('area_width',$area[2]."px",$filearea);
				$filearea = str_replace('area_height',$area[3]."px",$filearea);
				$list_banner_id = explode(',',$area[6]);
				$banner_length = $area[5];
				if (count($list_banner_id) < $area[5]) {
					$banner_length = count($list_banner_id);
				}
				$banner_content = '';
				$float = '';
				if ($area[4] == '1') {
					$float = ';float: left';
				}
				for ($i=0; $i < $banner_length; $i++) { 
					$banner = $this->posts->one_record('banners',$list_banner_id[$i]); 
					$img_height = $area[3]*$banner[5]/100 - 10;
					$banner_content .= '<div style="width: '.$banner[4].'% ;height: '.$banner[5].'%'.$float.' "><p style="height:10px;">'.$banner[2].'</p><img src="/'.$images.'/'.$filename.'/'.$banner[3].'" style="width: 100%; height: '.$img_height.'px;"></div>';
					ftp_put($con,$images."/".$filename."/".$banner[3],dirname(__DIR__).'/assets/images/'.$banner[3], FTP_BINARY);
				}
				$filearea = str_replace('banner',$banner_content,$filearea); 
				//Them banner lien quan vao page 
				$newcontent = str_replace("<!-- area id=".$value."-->",$filearea,$newcontent); 
			} 
		}
		//Add Feedback
  		$file_feedback = file_get_contents(dirname(__DIR__)."/models/feedback.html",'r+');
  		$file_feedback = str_replace('page_id_value',$result[0],$file_feedback); 
  		//Replace Q&A
  		$file_feedback = str_replace('QUESTION1',QUESTION1,$file_feedback);
  		$file_feedback = str_replace('QUESTION2',QUESTION2,$file_feedback);
  		$file_feedback = str_replace('ANSWER_Q1_1',ANSWER_Q1_1,$file_feedback);
  		$file_feedback = str_replace('ANSWER_Q1_2',ANSWER_Q1_2,$file_feedback);
  		$file_feedback = str_replace('ANSWER_Q1_3',ANSWER_Q1_3,$file_feedback);
  		$file_feedback = str_replace('ANSWER_Q2_1',ANSWER_Q2_1,$file_feedback);
  		$file_feedback = str_replace('ANSWER_Q2_2',ANSWER_Q2_2,$file_feedback);
  		$file_feedback = str_replace('ANSWER_Q2_3',ANSWER_Q2_3,$file_feedback);
  		$newcontent = str_replace('<!-- feedback -->',$file_feedback,$newcontent); 
		//Auto link
  		$link_value = '';
  		$subject = $result[2];
		$pattern = '/\<!-- auto link id=(.+)\-->/';
		if (preg_match_all($pattern, $subject, $matches))
		{
			foreach ($matches[1] as $key => $value) {
				$link = $this->posts->get_list_page_public($value);
				$link = explode(',', implode('', $link[0]));
				//Tao list link
		  		foreach ($link as $key => $valuelink) {
		  			$page = $this->posts->one_record('posts',$valuelink);
		  			if ($result[0] != $page[0]) {
		  				$link_value .= '<li><a href="'.$this->path->getlinkpub($page[4]).'">'.$page[1].'</a></li>';
		  			}
		  		}
		  		$filetxt = file_get_contents(dirname(__DIR__)."/models/link.txt",'r+');
			    $filetxt = str_replace('autolink',$link_value,$filetxt);
			    $link_value = '';
			    //Them link lien quan vao page
			  	$newcontent = str_replace("<!-- auto link id=".$value."-->",$filetxt,$newcontent);
			}
		}
	    //Mo file html
		$file = fopen(dirname(__DIR__).'/models/new.html', 'r+');
		//Clear file
        file_put_contents(dirname(__DIR__)."/models/new.html", "");
        //Them du lieu vao
        fputs($file, $newcontent);
		fclose($file);
		//Upload file html len FTP server
		if ($result[4]) {
			unset($url[$d]);
			$folderhtml_list = ftp_nlist($con,'.');
			foreach ($url as $key => $value) {
				$kq = '';
				for ($i=0; $i <=$key ; $i++) { 
					$kq .= $url[$i].'/'; 
				}
				ftp_mkdir($con,$kq);
			}
			$foldercss_list = ftp_nlist($con,$kq);
			if($this->path->checkfolder($foldercss_list,$kq.$filename)){
			    ftp_mkdir($con,$kq.$filename);
			}
			ftp_put($con,$kq."/".$filename."/".$filename.".html",$fileput, FTP_BINARY);
		}else{
		    $foldercss_list = ftp_nlist($con,'.');
		    if($this->path->checkfolder($foldercss_list,$result[0])){
			    ftp_mkdir($con,$result[0]);
			}
			ftp_put($con,$result[0]."/".$result[0].".html",$fileput, FTP_BINARY);
		}
		//put css js
		if($template[3]){
		    $listcs = scandir(dirname(__DIR__)."/".$template[3]);
		    unset($listcs[0]);
    		unset($listcs[1]);
    		foreach($listcs as $value){
    		    if(substr($value,-5,5) != '.html'){
    		    	$listfolder_end = scandir(dirname(__DIR__)."/".$template[3]."/".$value);
    		        unset($listfolder_end[0]);
    		        unset($listfolder_end[1]);
    		        if ($result[4]) {
    		            //$listfolder_kq = ftp_nlist($con,$kq);
    		            ftp_mkdir($con,$kq.$filename."/".$value);
    		            foreach($listfolder_end as $filecs){
    		                $filecsput = dirname(__DIR__)."/".$template[3]."/".$value."/".$filecs;
    		                ftp_put($con,$kq.$filename."/".$value."/".$filecs,$filecsput, FTP_BINARY);
    		            }
    		        }else{
    		        	ftp_mkdir($con,$result[0]."/".$value);
    		            foreach($listfolder_end as $filecs){
    		                $filecsput = dirname(__DIR__)."/".$template[3]."/".$value."/".$filecs;
    		                ftp_put($con,$result[0]."/".$value."/".$filecs,$filecsput, FTP_BINARY);
    		            }
    		        }
    		    }
    		}
		}
		ftp_close($con);
	}
	public function pppp($table,$id)
	{
		$this->posts = new post();
		$result = $this->posts->one_record('posts',$id);
		$subject = $result[2];
		$pattern = '/\<!-- auto link id=(.+)\-->/';
		
		if (preg_match_all($pattern, $subject, $matches))
		{
			foreach ($matches[1] as $key => $value) {
				echo $value."<br>";
			}
		}
		exit();
	}
	public function delete($id)
	{
		$con = ftp_connect(ftp_server) or die("Could not connect to ftp_server");
        $ftp = ftp_login($con, ftp_username, ftp_password);
        $posts = new post();
        $path = new path();
        $result = $posts->one_record('posts',$id);
        $urlfolder = $result[4];
		if ($urlfolder) {
			$url = $path->getfolder($urlfolder);
			$d = $path->getname($urlfolder);
			$path = $url[0];
			$filename = $url[$d];
			unset($url[$d]);
			$kq = '';
			foreach ($url as $key => $value) {
				$kq .= $value.'/';
			}
			ftp_delete($con,$kq.$filename."/".$filename.'.html');
			$listcs = ftp_nlist($con,$kq.$filename);
			foreach ($listcs as $key => $value) {
				$listfilecs = ftp_nlist($con,$value);
				foreach($listfilecs as $key=>$valuecs){
			        ftp_delete($con,$valuecs);
			    }
				ftp_rmdir($con,$value);
			}
	        ftp_rmdir($con,$kq."/".$filename);
			foreach (array_reverse($url,true) as $key => $value) {
				$kq = '';
				foreach ($url as $key2 => $value2) {
					if ($key > $key2) {
						$kq .= $value2.'/';
					}
				}
				ftp_rmdir($con,$kq.$value);
			}
			$folderimage_list = ftp_nlist($con,'images/'.$filename);
			unset($folderimage_list[0]);
			unset($folderimage_list[1]);
	        if ($folderimage_list) {
	        	foreach ($folderimage_list as $key => $value) {
		        	ftp_delete($con,"public_html/images/".$filename."/".$value);
	        	}
	        }
	        $folderdocument_list = ftp_nlist($con,'document/'.$filename);
	        unset($folderdocument_list[0]);
			unset($folderdocument_list[1]);
	        if ($folderdocument_list) {
	        	foreach ($folderdocument_list as $key => $value) {
		        	ftp_delete($con,"public_html/document/".$filename."/".$value);
	        	}
	        }
	        ftp_rmdir($con, 'images/'.$filename);
	        ftp_rmdir($con, 'document/'.$filename);
		}else{
			ftp_delete($con, $id."/".$id.'.html');
	        $folderimage_list = ftp_nlist($con,'images/'.$id);
	        if ($folderimage_list) {
	        	foreach ($folderimage_list as $key => $value) {
		        	ftp_delete($con,$value);
	        	}
	        }
	        $folderdocument_list = ftp_nlist($con,'document/'.$id);
	        if ($folderdocument_list) {
	        	foreach ($folderdocument_list as $key => $value) {
		        	ftp_delete($con,$value);
	        	}
	        }
	        ftp_rmdir($con, 'images/'.$id);
	        ftp_rmdir($con, 'document/'.$id);
	        $template = $posts->one_record('template',$result[5]);
	        if($template[3]){
	            $listfile = ftp_nlist($con,$id);
	            unset($listfile[0]);
    		    unset($listfile[1]);
    		    foreach($listfile as $value){
    		        $listcs = ftp_nlist($con,$id."/".$value);
    		        foreach($listcs as $csvalue){
    		            ftp_delete($con,$id."/".$value."/".$csvalue);
    		        }
    		        ftp_rmdir($con, $id."/".$value);
    		    }
    		    ftp_rmdir($con, $id);
	        }
		}
	}
}
?>