<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Attachments extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->helper(array('form', 'url'));
		$this->load->model('attachments/attachmentmodel');
		$this->load->model('attachments/datastore');
	}

	function index()
	{
		$this->load->view('upload', array('error' => ' ' ));
	}


	/** Asynchronous upload of Contracts **/

	/**
	* Asynchronously Upload files to server
	* @param the key to use when creating a folder in the s3 bucket or locally
	*/
	function async_upload_contract_remote(){

		// set execution time to be forever
		ini_set('MAX_EXECUTION_TIME', -1);

		// local_files is the local file path
		$contract_id = $this->input->post("contract_id");
		$file_name = $this->input->post("contract_filename");
		$local_file = $this->config->item('upload_directory').$file_name;
		// remote_paths is where to put the file in the remote location
		$remote_path = $this->input->post("remote_path");

		// upload the files to s3
		if($this->datastore->put($local_file, $remote_path.'/'.$file_name)){
			// successful upload

			// save the contract into the db
			$upload_id = $this->attachmentmodel->insert_uploaded_contract($contract_id, $file_name);
			// get the number of pages from the pdf
			$number_of_pages = 10;

			if (defined('ENVIRONMENT') && (ENVIRONMENT != 'development')){
				$command = "pdfinfo $local_file";
				$output = shell_exec($command);
				// find page count
				preg_match('/Pages:\s+([0-9]+)/', $output, $pagecountmatches);
				$number_of_pages = $pagecountmatches[1];
			}

			// generate unique directory to store pages
			$dir = $this->config->item("upload_directory")."/".uniqid();
			// make the random directory
			if (mkdir($dir, 0777, true)) {

				// make all the pages
				for($page_number = 0; $page_number < $number_of_pages; $page_number++){
					$page = $local_file."[".$page_number."]";
					log_message("info", "Working on Contract ".$local_file." page: ".$page);
					$img = new Imagick();
					// keep it clear - set to high resolution
					$resolution = $this->config->item('pdf_image_dpi');
					$img->setResolution( $resolution, $resolution );
					$img->readImage($page);


					// increase the contrast by 2
					$img->contrastImage(1);
					$img->contrastImage(2);
					$img->contrastImage(3);
					
					
					
					// rescale image to be readable - 816 X 1056 = 8.5 x 11
					$img->scaleImage($this->config->item('pdf_image_width'),0);
					$d = $img->getImageGeometry();
					$h = $d['height'];
					if($h > $this->config->item('pdf_image_height')) {
					    $img->scaleImage(0,$this->config->item('pdf_image_height'));
					}
					
					 //* Convert to png */
					$img->setImageFormat( "png" );
					// set the new dpi
					// resize image to printer resolution
					$img->setResolution(72,72);
					
					$page_name = '/page-'.($page_number+1).'.png';
					$local_page = $dir.$page_name;
					$img->writeImage($local_page);       // Write to disk
					ob_clean(); // clear buffer
					$img->destroy();

					// upload the image to amazon
					if($this->datastore->put($local_page, $remote_path.'/pages/'.$page_name)){
						// update progress
						$progress = (($page_number+1) / $number_of_pages)*100;
						$this->attachmentmodel->update_contract_process_progress($progress, $upload_id);
						// delete local image file
						unlink($local_page);
					}


				}

				log_message("info", "Number of pages: ".$number_of_pages." in contract upload id ".$upload_id);
				// record total pages in document
				$this->attachmentmodel->insert_uploaded_contract_page($number_of_pages, $upload_id);
			}


			log_message("info", "Completed uploading files to s3");
			// clean up the contract local file
			unlink($local_file);
			// clean up the image directory
			system('rm -rf ' . escapeshellarg($dir));

		}



	}

}