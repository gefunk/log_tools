<?php 
 
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

use Aws\Common\Aws;
use Aws\Common\Enum\Region;
use Aws\S3\Enum\CannedAcl;
use Aws\S3\Exception\S3Exception;
use Aws\S3\Enum\StorageClass;

class AwsInterface{   
    private $aws;
    private $s3Client;
    public $debug=false;

    function __construct(){
        $CI =& get_instance();
        $this->aws = Aws::factory(array(
          'key'    => $CI->config->item('aws_access_key_id'),
          'secret' => $CI->config->item('aws_secret')
        ));
        $this->s3Client = $this->aws->get('s3');
    }
     
    function isValidBucketName($bucket_name){
        try {
            if($this->s3Client->isValidBucketName($bucket_name)){
                return true;
            }
            return false;
        } catch (S3Exception $e) {
            if($this->debug){
                return $e->getMessage();
            }
            else{
                return false;
            }
        }   
    }
     
    function doesBucketExist($bucket_name){
        try {
            if($this->s3Client->doesBucketExist($bucket_name)){
                return true;
            }
            return false;
        } catch (S3Exception $e) {
            if($this->debug){
                return $e->getMessage();
            }
            else{
                return false;
            }
        }
    }
     
    function createBucket($bucket_name){
        if($this->isValidBucketName($bucket_name)){
            if($this->doesBucketExist($bucket_name)){
                return false;
            }
            else{
                try{
                    $this->s3Client->createBucket(
                    array(
                        'Bucket'=>$bucket_name,
                        'ACL'   => CannedAcl::PUBLIC_READ
                        //add more items if required here
                    ));
                    return true;
                } catch(S3Exception $e){
                    if($this->debug){
                        return $e->getMessage();
                    }
                    else{
                        return false;
                    }
                }   
            }
        }
        else{
            if($this->debug){
                return $e->getMessage();
            }
            else{
                return false;
            }
        }
    }
     
    function _return_bucket_policy($bucket_name = ''){
        return '{
            "Version": "2008-10-17",
            "Id": "PolicyForCloudFrontPrivateContent",
            "Statement": [
                {
                    "Sid": "1",
                    "Effect": "Allow",
                    "Principal": {
                        "AWS": "arn:aws:iam::cloudfront:user/CloudFront Origin Access Identity '.$this->cfIdentity.'"
                    },
                    "Action": "s3:GetObject",
                    "Resource": "arn:aws:s3:::'.$bucket_name.'/*"
                }
            ]
        }';
    }
     
    function putBucketPolicy($bucket_name){
        if($this->doesBucketExist($bucket_name)){
            try{
                $this->s3Client->putBucketPolicy(
                array(
                    'Bucket'=>$bucket_name, 
                    'Policy'=>$this->_return_bucket_policy($bucket_name)
                ));
                return true;
            } catch(S3Exception $e){
                if($this->debug){
                    return $e->getMessage();
                }
                else{
                    return false;
                }
            }
        }
        else{
            return false;
        }
    }
     
    function deleteBucket($bucket_name){
        try {
            $this->s3Client->clearBucket($bucket_name);
            $this->s3Client->deleteBucket(array(
                'Bucket' => $bucket_name
            ));
            return true;
        } catch (S3Exception $e) {
            if($this->debug){
                return $e->getMessage();
            }
            else{
                return false;
            }
        }
    }
     
    function doesObjectExist($bucket_name, $key){
        try {
            if($this->s3Client->doesObjectExist($bucket_name, $key)){
                return true;    
            }
            return false;
        } catch(S3Exception $e){
            if($this->debug){
                return $e->getMessage();
            }
            else{
                return false;
            }
        }   
    }
     
	/**
	* Upload the file to Amazon
	* @param $bucket_name the name of the bucket to upload to
	* @param $key key for this file
	* @param $source local file path
	*/
    function putObject($bucket_name, $key, $source){
        try {
            $this->s3Client->putObject(array(
                'Bucket'=> $bucket_name,
                'Key'   => $key,
                'SourceFile'  => $source,
                'ACL'   => CannedAcl::BUCKET_OWNER_FULL_CONTROL,
				'StorageClass' => StorageClass::REDUCED_REDUNDANCY
            ));
            return true;
        } catch(S3Exception $e){
            if($this->debug){
                return $e->getMessage();
            }
            else{
                return false;
            }
        }
    }
     
    function deleteObject($bucket_name, $objects){
        try {
            $this->s3Client->deleteObject(array(
                'Bucket'  =>$bucket_name,
                'Objects' =>$objects
            ));
            return true;
        } catch (S3Exception $e) {
            if($this->debug){
                return $e->getMessage();
            }
            else{
                return false;
            }
        }
    }
     
    // $objects should be array(array('Key'=>$key), array('Key'=>$key2)); testing needed here before implementation
    function deleteObjects($bucket_name, $keys){
        try {
            $this->s3Client->deleteObjects(array(
                'Bucket' => $bucket_name,
                'Key'=> $keys
            ));
            return true;
        } catch (S3Exception $e) {
            if($this->debug){
                return $e->getMessage();
            }
            else{
                return false;
            }
        }   
    }
     

	/**
	* Gets a temporary url for a s3 object
	* @param $keypath, the full path to the remote object
	* @return returns a string representing the url
	*/
	function getUrlForRemoteFile($keypath)
	{
		// get() returns a Guzzle\Http\Message\Request object
		$request = $this->s3Client->get($keypath);
		
		error_log("Request object: ".$request);

		// Create a signed URL from a completely custom HTTP request that
		// will last for 10 minutes from the current time
		$signedUrl = $this->s3Client->createPresignedUrl($request, '+10 minutes');
		
		error_log("Signed Url: ".$signedUrl);
		
		return $signedUrl;
	}
     
     
}