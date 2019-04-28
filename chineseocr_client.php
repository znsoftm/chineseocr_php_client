<?

define("OCRSRV","http://10.95.134.139:9898/ocr");

function PostRawData($url,$data)
{
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,$url );
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS,$data);
        curl_setopt($ch, CURLOPT_HTTPHEADER,  array('Content-Type: text/plain'));
        $retdata =curl_exec($ch);
        if($retdata === false)
        {
          echo 'Curl error: ' . curl_error($ch);
        }
        else
        {
          return $retdata;
        }
}

function DoOCR($filename,$ocrsrv)
{

     if(!file_exists($filename))

        return false;

     $imgdata=file_get_contents($filename);

     $imgdata=base64_encode($imgdata);

     $imgdata="data:image/jpeg;base64,".$imgdata;

     $Param["imgString"]=iconv("gbk//TRANSLIT","UTF-8",$imgdata);

     $Param["billModel"]=iconv("gbk//TRANSLIT","UTF-8","Í¨ÓÃOCR");
     $Param=json_encode($Param);

     return  PostRawData(OCRSRV,$Param);

}


// testing example 

$data=DoOCR("/soft/ocrtest/ocr_test.png",OCRSRV);
$data=json_decode($data,true);
$str="";
foreach($data["res"] as $key=>$val)
{
        $str.=$val["text"];
}

echo $str;

?>
