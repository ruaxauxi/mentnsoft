<?php


const guest = "guest";  
const base = 'base';
const customer = 'group1';// customers
const admin = 'group2'; //admin
const group3 ='group3';
const group33 = 'group33';
const group4 ='group4';
const group5 ='group5';
const group6 ='group6';

 
return array(
    'acl' => array(
    		'roles' => array(
    				guest   => null,
    		        base => guest,    		         
    		        group3 => base,
    		        group33 => group3,
        		    group4 => base,
        		    group5 => base,
    		        group6  => base, 
    				customer  => base,  
        		    admin       => customer,
    		         
    		),
    		'resources' => array(
    				'allow' => array(
    						'Vhdang\Controller\User' => array(
    						        'login'   => guest, 
    						        'logout'    => base,
    						        'address' => customer,
    						        'transfer'    => customer,  
    						        'changepasswd' => customer,  						      						      
    								'all'       => array(admin,base),

    						    
    						        'customer'      => array(group3),
    						    
    						),
    				       
    				      'Vhdang\Controller\Index'   => array(
    				            'index'     => guest,
    				            'all'       => admin,
    				      ),
    				     'Vhdang\Controller\Syserror' => array(
    				            'all'   => guest
    				     ),
    				    'Vhdang\Controller\Customer'    => array(
    				     	     'all'    => customer,
    				            
    				     ),
    				    /* 
    				    <li><a href="<?php echo $this->url('admin',array('action'=> 'viewcustomertransfer'))?>">Lịch sử CK</a></li>
    				    <li><a href="<?php echo $this->url('admin',array('action'=> 'viewcustomerorder'))?>">YC Đặt hàng</a></li>
    				    <li><a href="<?php echo $this->url('admin',array('action'=> 'viewcustomerordered'))?>">KQ Mua hàng</a></li>
    				    <li><a href="<?php echo $this->url('admin',array('action'=> 'viewcustomerbalance'))?>">Theo dõi Balance</a></li> */
    				    'Vhdang\Controller\Admin' => array(
                                'all' => admin,
                                'changepasswd' => base,
                                'shipment' => array(group3,group5,group6),
    				        
                                'shipped' => array(group3,group5,group6),
    				            'viewshipment' => array(group3,group5,group6),
    				        
    				        
    				            'viewcustomertransfer' => group3,
        				        'viewcustomerorder' => group3,
        				        'viewcustomerordered' => group3,
        				        'viewcustomerbalance' => group3,
    				            'loadmorecustomertrans' => group3,
    				        
    				            'checkshipment' => array(group3,group5,group6),

    				            'createshipment' => array(group3,group5,group6),
    				            'searchshipmentorder' => array(group3,group5,group6),
    				            'createshipmentajaxadd' => array(group3,group5,group6),
    				        
                                'shippingweight' => array(group3,group6),
    				        
    				            'deleteshipment'    => group3,
    				            'shipdel'           => array(group3,group5),
    				        
                                'savecustomerrequest' => array(group3,group4,group6),
                                'order' => array(group3,group4,group6),
    				            'checkorderno' => array(group3,group4,group6),
    				            'orderdetails' => array(group3,group4,group6),
    				            'checkorders' => array(group3,group5),
    				        
    				            'saveorder' => array(group3,group4,group6),
    				            'orderlist' => array(group3,group4,group6),
    				            'vieworder' => array(group3,group4,group6),

    				            'customerorders'    => array(group3,group6),
    				            'orderchecked'    => array(group3,group6),
    				        
    				            //'cancelorder'   => '',
    				            'question'      => array(group3,group6),
    				            'approvedcustomerorders'    =>array(group3,group6),
    				            
    				            'usertransfer'  => array(group3),
    				            'transfermgm'   => array(group3),
    				            'deltransfer'   => array(group33),
    				            'confirmtransfer'   => array(group33),
    				            
    				            
    				            'sendmessage'   => base,
    				            'viewanswer'    => base,
    				            'delquestion'   => base,
    				            'delrequest'    => base,
    				            'viewallcustomeraddress' => base,
    				        
    				            // Tong ket
    				            
    				            'tongketdung' => array(admin,group5,group33), // admin, dung, diem
    				            'viewshippingfee' => array(admin,group33),
    				            'viewadditionalfee' => array(admin,group33),
    				            'viewsummarize' => array(group3),
    				            
    				            'tongthukh' => array(admin,group33),
    				        
        				        'tongtienhang' => array(admin,group33),
    				        
    				            'chitiettongthukh' => array(group3,group5),
    				            'shippingfeedetail' => array(group3,group5),
    				            'chitietlinhtinh' => array(group3,group5),    				        
    				            'chitiettongtienhang' => array(group3,group5),
    				            
        				        'chitiettongshippingfee' => array(group3,group5),
    				            'chitiettongchiphi' => array(group3,group5),
    				            'chitietgiaodichkhac' => array(group3,group5),
    				            'chitiettongketdung' => array(group3,group5), 
    				
    				        
    				            //'stores'        => '',
    				            //'xrates'        => '',
    				            //'updatebalance' => '',
    				            //'shipmentfinalizing'    => ''
    				            //'deleteshipment'
    				            //'deleteshipped'
    				            //'orderdel'    => 
    				    ),
    						
    					'Vhdang\Controller\Image' => array(
    								'all'   => base
    					),         
    				),
    		    'deny'    => array(
    		        'Vhdang\Controller\User' => array(
    		    	         'transfer'  => base,
    		                  'address' => base,
    		          ),
    		        'Vhdang\Controller\Customer'  => array(
    		        	     'all'   => base
    		        ),
    		        'Vhdang\Controller\Image' => array(
    		        	     'all'   => customer
    		        ),
    		        
    	       ) 
    		)
    )
);