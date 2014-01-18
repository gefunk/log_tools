<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Attachments extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->helper(array('form', 'url'));
		$this->load->library('pngquant');
		$this->load->model('attachments/attachmentmodel');
		$this->load->model('attachments/datastore');
	}

	
	/**
	* Asynchronously Upload files to Remote Data Store
	* This service accepts only PDF's, the PDF's are converted to images and stored remotely
	* 
	*/
	function async_convert_pdf(){

		// set execution time to be forever, this will allow long running processes
		ini_set('MAX_EXECUTION_TIME', -1);

		$file_name = $this->input->post("contract_filename");
		// locally located file
		$local_file = $this->config->item('upload_directory').$file_name;
		// the document id / upload id is the document id in mongo
		$upload_id = $this->input->post("document_id");
		// where to store the file on the remote server
		$remote_path = $upload_id;

		// update progress with uploading message
		$this->attachmentmodel->update_document_progress($upload_id, "Uploading to Data Store", 0);		
		// upload the files to s3
		if($this->datastore->put($local_file, $remote_path.'/'.$file_name)){
			// mark file as uploaded
			$this->attachmentmodel->update_document_progress($upload_id, "PDF Upload Complete", 100);
			
			// initialize number of pages, in development we set this to 5
			$number_of_pages = 5;

			/*
			 * Get actual page count from pdfinfo, must be installed in ubuntu - sudo apt-get install pdfinfo
			 */
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
					// strip extraneous data
   					$img->stripImage(); 
					// set depth to 8
					$img->setimagedepth(8);
					
					$page_name = '/'.($page_number+1).'.png';
					$compressed_name = '/'.($page_number+1).'-c.png';
					$local_page = $dir.$page_name;
					$img->writeImage($local_page); // Write to disk

					// clean up image
					ob_clean(); // clear buffer
					$img->destroy();
					
					/**
					* compress the file
					*/
					$compressed_file =  $dir.$compressed_name;
					file_put_contents($compressed_file, $this->pngquant->compress_png($local_page));
					
					// upload the compressed image to amazon
					if($this->datastore->put($compressed_file, $remote_path.'/pages/'.$page_name)){
						// update progress
						$conversion_percent = (($page_number+1) / $number_of_pages)*100;
						// record total pages in document, remove leading '/' and insert the page name
						$this->attachmentmodel->insert_uploaded_page(substr($page_name,1), $upload_id);
						$this->attachmentmodel->update_document_progress($upload_id, "Page Conversion", $conversion_percent);
						// delete local image file(s)
						unlink($local_page);
						unlink($compressed_file);
					}


				}

				log_message("info", "Number of pages: ".$number_of_pages." in contract upload id ".$upload_id);
				
			}

			$this->attachmentmodel->update_document_progress($upload_id, "Completed", 100);
			log_message("info", "Completed uploading files to s3");
			// clean up the contract local file
			unlink($local_file);
			// clean up the image directory
			system('rm -rf ' . escapeshellarg($dir));

		}



	}

}