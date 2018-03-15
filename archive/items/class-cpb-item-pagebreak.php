<?php

class CPB_Item_Pagebreak extends CPB_Item {
	
	protected $name = 'Pagebreak';
	
	protected $slug = 'pagebreak';
	
	protected $form_size = 'small';
	
	
	public function item( $settings , $content ){
		
		$html = '<div class="cpb-pagebreak"></div>';
		
		return $html;
		
	}// end item
	
	
	public function form( $settings , $content ){
		
		$html = ''; 
		
		return array('Basic' => $html );
		
	} // end form
	
	protected function editor( $settings , $content ){
		
		$html = '<div class="cpb-item cpb-pagebreak cpb-layout-item"  data-id="' . $this->get_id() . '">';
		
			$html .= '<div><span>Page break</span></div>';
		
		$html .= '</div>';
		
		return $html;
		
	}
	
	protected function admin_css(){
		
		$style = '.cpb-pagebreak { padding-bottom: 2rem;}';
		
		$style .= '.cpb-pagebreak > div { position:relative;text-align:center;}';
		
		$style .= '.cpb-pagebreak > div:before { content:"";position:absolute;height:2px;background:#ccc;width:100%;top:50%;left:0;}'; 
		
		$style .= '.cpb-pagebreak > div span { display:inline-block;padding:0.5rem 1rem;background:#f1f1f1;position:relative;color:#999;text-transform:uppercase;}';
		
		$style .= '.cpb-pagebreak > div span:before,.cpb-pagebreak > div span:after {width:0;height:0;content:"";display:block;border-color:#ccc;margin: 0 auto;}'; 
		
		$style .= '.cpb-pagebreak > div span:before{ border-left: 30px solid transparent;border-right: 30px solid transparent;border-bottom: 1rem solid #ccc;}';
		
		$style .= '.cpb-pagebreak > div span:after{ border-left: 30px solid transparent;border-right: 30px solid transparent;border-top: 1rem solid #ccc;}';
		
		return $style;
		
	} // end admin_css
	
	protected function css() {
		
		$style = '';
		
		return $style;
		
	}
	
	
	protected function clean( $settings ){
		
		$clean = array();
		
		return $clean;
	}
	
	
}