<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
class Barcode extends CI_Controller {
 
    function __construct() {
        parent::__construct();
    }
     
	// function index()
	// {
	// 	// $kode = '1234';
	// 	// echo '<img src="'.base_url().'barcode/bikin_barcode/'.$kode.'">';
	// 	// echo $this-> bar128($kode);
	// }

	// public function set_barcode() {
	// 	$code = "123";
	// 	//load library
	// 	$this->load->library('zend');
	// 	//load in folder Zend
	// 	$this->zend->load('Zend/Barcode');
	// 	//generate barcode
	// 	// Zend_Barcode::render('code128', 'image', array('text'=>$code), array());

	// 	// $code = time().'1222';
    //     // imagepng($file,"barcode/{$code}.png");
	// 	// $data['barcode'] = $code.'.png';
	// 	$file = Zend_Barcode::render('code128', 'image', array('text'=>$code), array());
    //     $code = time().'1222';
    //     imagepng($file,"barcode/{$code}.png");
	// 	$data['barcode'] = $code.'.png';
    //     $this->load->view('cetak_barcode',$data);
	// }

	// public function set_barcode($code = ""){
	// 	//generate barcode
	// 	$code = "12345";
	// 	// $this->load->library('zend');
	// 	$this->load->library('zend');
	// 	$this->zend->load('Zend/Barcode');
    //     $file = Zend_Barcode::render('code128', 'image', array('text'=>$code), array());
    //     // $code = time().'1222';
    //     // imagepng($file,"barcode/{$code}.png");
    //     // $data['barcode'] = $code.'.png';
    //     $this->load->view('cetak_barcode',$data);
    // }
	public function barcode(){
		$qty = $this->input->post_get('qty',true);
		$barcode = $this->input->post_get('barcode',true);
		$mbottom = $this->input->post_get('mbottom',true);
		
		$data['qty'] = $qty;
		$data['barcode'] = $barcode;
		$data['mbottom'] = ($mbottom)? $mbottom : 10;
		$this->load->view('cetak_barcode',$data);
		
		// echo $html;
	}

	public function generate_barcode() {
		$barcode = $this->input->get('barcode',true);
		$this->load->library('zend');
		$this->zend->load('Zend/Barcode');
		$file = Zend_Barcode::render('code128', 'image', array('text'=>$barcode), array());
		$code = time().'1222';
		imagepng($file,"barcode/{$code}.png");
		return $code.'.png';
		// $file = Zend_Barcode::render('code128', 'image', array('text'=>$code), array());
	}
 
}
 
?>
