<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Gstr1 extends MY_Controller {

	public function __construct()
	{
		parent::__construct();
		if (!$this->ion_auth->logged_in())
		{
			redirect('auth/login', 'refresh');
		}
	}

	public function index()
	{
			
	}
}

class Itm_det {
 	public $txval; //int
 	public $rt; //int
 	public $camt; //int
 	public $samt; //int
 	public $csamt; //int

}
class Itms {
 	public $num; //int
	public $itm_det; //Itm_det

}
class Inv {
 	public $inum; //String
 	public $idt; //Date
 	public $val; //int
 	public $pos; //String
 	public $rchrg; //String
 	public $itms; //array( Itms )
 	public $inv_typ; //String

}
class BB {
 	public $ctin; //String
 	public $inv; //array( Inv )

}
class BCs {
 	public $rt; //int
 	public $sply_ty; //String
 	public $pos; //String
 	public $typ; //String
 	public $txval; //int
 	public $camt; //int
 	public $samt; //int
 	public $csamt; //int

}
class Itm_det {
 	public $rt; //int
 	public $txval; //int
 	public $camt; //int
 	public $samt; //int
 	public $csamt; //int

}
class Itms {
 	public $num; //int
	public $itm_det; //Itm_det

}
class Nt {
 	public $ntty; //String
 	public $nt_num; //String
 	public $nt_dt; //Date
 	public $p_gst; //String
 	public $inum; //String
 	public $idt; //Date
 	public $val; //int
 	public $itms; //array( Itms )

}
class Cdnr {
 	public $ctin; //String
 	public $nt; //array( Nt )

}
class Data {
 	public $num; //int
	public $hsn_sc; //String
 	public $desc; //String
 	public $uqc; //String
 	public $qty; //int
 	public $val; //int
 	public $txval; //int
 	public $iamt; //int
 	public $camt; //int
 	public $samt; //int
 	public $csamt; //int
}
class Hsn {
 	public $data; //array( Data )

}
class Docs {
 	public $num; //int
 	public $from; //String
 	public $to; //String
 	public $totnum; //int
 	public $cancel; //int
 	public $net_issue; //int
}
class Doc_det {
 	public $doc_num; //int
 	public $doc_typ; //String
 	public $docs; //array( Docs )	
}
class Doc_issue {
 	public $doc_det; //array( Doc_det )
}

class Application {
 	public $gstin; //String
 	public $fp; //Date
 	public $gt; //int
 	public $cur_gt; //int
 	public $b2b; //array( BB )
 	public $b2cs; //array( BCs )
 	public $cdnr; //array( Cdnr )
	public $hsn; //Hsn
	public $doc_issue; //Doc_issue
}

