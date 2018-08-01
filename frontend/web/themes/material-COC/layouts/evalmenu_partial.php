    <?php
    /**
     * Menu items for evaluator included here
     */
      
      use yii\helpers\Html;
      use common\models\User;
      use common\models\UserProfile;
      use yii\widgets\Menu;
      use yii\bootstrap\Nav;      
      use yii\bootstrap\NavBar;
      use kartik\dropdown;
      use kartik\nav\NavX;
      use yii\helpers\Url;

      ?>
        <div class="navbar-fixed" style="z-index: 1000">
          <nav>
            <div class="nav-wrapper">
                <?php
                  $id=Yii::$app->user->identity->_id;
                  $curruser = User::findIdentity($id);
                  $curruserprofile = UserProfile::findbyUserid($curruser->_id);

                  if ($curruserprofile):
                    $profileid =(string)($curruserprofile->_id);
                    $profilelink = '/user-profile/update?id='.$profileid;
                  else:
                    $profilelink = '/user-profile/create';
                  endif;    

                  $items =   
                          [ 
                      			[
                      				'label'=>'Dashboard','url'=>['/eval/dashboard?userId='.$id],
                      			],

                          	[
                          		'label' => 'My Actions', 'items'=>
                          		[
                              		[
                              			'label' => 'Edit Profile', 'url' => [$profilelink]
                              		],
                                	'<li class="divider"></li>',
                              		[
                              			'label' => 'Manage Users', 'items'=>
                              			[
                              				[
                              			     'label' => 'Approve Users', 'url' =>['/eval-auth/approve-user-list']
  	                              		],
  	                              		'<li class="divider"></li>',
  	                              		[
                    					           'label'=> 'List Users', 'url'=> ['/user-profile/existing-user-listing']
        		  				      	        ]
                              			]
                              		],
                              		'<li class="divider"></li>',
                              		[
                          				  'label' => 'Manage Reminders', 'items'=>
   									 	              [
                                         		[ 
                                              'label'=>'Centre Delinquency', 'url' => ['/reminder-trans/delinquency']
                                            ],
                                            '<li class="divider"></li>',
                                            [ 
                                         			'label'=>'Create Reminder', 'url' => ['/reminder-trans/index']
                                         		],
                                           	'<li class="divider"></li>',
                                         		[
                                         			'label'=> 'Sent Reminders', 'url'=> ['/reminder-trans/sent-reminders']
                       		  		 		        ]
                        				 	  ],
                                    'label' => 'Manage Reminders', 'items'=>
                                    [
                                            [ 
                                              'label'=>'Centre Delinquency', 'url' => ['/reminder-trans/delinquency']
                                            ],
                                            '<li class="divider"></li>',
                                            [ 
                                              'label'=>'Create Reminder', 'url' => ['/reminder-trans/index']
                                            ],
                                            '<li class="divider"></li>',
                                            [
                                              'label'=> 'Sent Reminders', 'url'=> ['/reminder-trans/sent-reminders']
                                            ]
                                    ],                                    
                        			     ],
                          		]
                        	  ],

                          	[
                          		'label'=>'Questionnaires','items'=>
                          		[
                            		[ 
                            			'label'=>'Submitted', 'url' =>['/questionnaire/indexeval?status=submitted']
                      			    ],
                            		'<li class="divider"></li>',
                              	[   
                              		'label'=>'Approved', 'url' =>['/questionnaire/indexeval?status=approved']
                              	],
                              	'<li class="divider"></li>',
                              	[   
                              	'label'=>'Sent for Rework', 'url' =>['/questionnaire/indexeval?status=rework']
                              	],
                            	]
                          	],

                          	[
                          		'label' => 'Questions', 'items'=>
                          		[
                                	[ 
                                		'label'=>'Sections', 'url' =>['/que-sections']
                                	],
                                	'<li class="divider"></li>',
                                	[ 
                                		'label'=>'Groups', 'url' =>['/que-groups']
                                	],
                                	'<li class="divider"></li>',
                                	[ 
                                		'label'=>'Structure', 'url' =>['/que-structure']
                                	],
                                	'<li class="divider"></li>',
                                	[ 
                                		'label'=>'Sequence', 'items'=>
                                		[
                                        [
                                        	'label'=>'Listing', 'url' =>['/que-sequence/index']
                                        ],
                                  	  '<li class="divider"></li>',
                                        [
                                        	'label'=>'Latest Seq', 'url' =>['/que-sequence/view']
                                        ], 
                                		], 
                                	],
                            	]
                      		  ],

                          	[
                          		'label' => 'Evaluation', 'items'=>
                          		[
	                              	[ 
	                              		'label'=>'Evaluation Criteria', 'url'=> ['/que-groups/eval-criteria']
	                              	],  
	                              	'<li class="divider"></li>',                               
	                              	[ 
	                              		'label'=>'Rates', 'url'=> ['/eval/view-allocation-master']
	                              	],
                                  	'<li class="divider"></li>',
                                  	[ 
                                    	'label'=>'Centre Allocation', 'url' => ['/allocation-details/index']
                                  	],    
                            	]
                                  
                            ],
                            [
              			  		    'label' => 'Utilities','items'=>
  	                      		[  
                                  [ 
                                      'label'=>'Centres', 'url'=>['/eval/centre-info-listing']
                                  ],
                                    '<li class="divider"></li>',
  	                              [ 
  	                              		'label'=>'Acharyas (chinmayamission.com)', 'url'=>['/wp-acharya/index']
  	                          		],
  	                              	'<li class="divider"></li>',
                                	[ 
                                		'label'=>'Locations (chinmayamission.com)', 'url'=>['/wp-location/index']
                                	],
                                  '<li class="divider"></li>',
                                  [ 
                                    'label'=>'Regional Heads', 'url'=>['/eval/regionheadlisting']
                                  ],
  	                        	]
                          	],

                          	[
                      			   'label' => 'Reports', 'items'=>
  	                      		[
                                  [ 
                                    'label'=>'Generate Report Data', 'url' =>['/que-summary/index']
                                  ],
                                    '<li class="divider"></li>',
                                  [ 
                                    'label'=>'Activities at a glance', 'url' =>['/que-summary/activitiesataglance']
                                  ],
                                    '<li class="divider"></li>',
  	                          		[ 
  	                          			'label'=>'Monthwise Marksheet', 'url' =>['/que-summary/monthwisemarksheet']
  	                          		],
                                    '<li class="divider"></li>',
                                    [ 
                                      'label'=>'Punctuality Statement', 'url'=> ['/que-summary/punctualitystatement']
                                    ],
  	                              	'<li class="divider"></li>',
  	                              	[ 
  	                              		'label'=>'Payment Summary', 'url'=> ['/allocation-details/summaryreport']
  	                              	],
  	                              	'<li class="divider"></li>',
  	                              	[ 
  	                              		'label'=>'Revised Rates Report', 'url'=> ['/allocation-details/revisedratesreport']
  	                              	],
  	                              	'<li class="divider"></li>',
  	                              	[ 
  	                              		'label'=>'Min-Max till date', 'url'=> ['/eval/minmax']
  	                              	],
  	                              	'<li class="divider"></li>',
  	                              	[ 
  	                              		'label'=>'Min-max - 6months', 'url'=> ['/eval/minmaxsixmonths']
  	                              	],
  	                    		  ]
                      		  ]
                          ];
                                         
                        if (!Yii::$app->user->isGuest and (Yii::$app->user->identity->username !==null)):
                              $items[] = 
                              '<li>'
                              . Html::beginForm(['/site/logout'], 'post')
                              .'<button class="btn btn-link" type="submit"><span style="color:white; text-decoration:none">Logout('.Yii::$app->user->identity->username.')</span></button>'
                              . Html::endForm()
                              . '</li>';
                        endif;
                         
                        if( \Yii::$app->mobileDetect->isDescktop()): //please note that the code has api as Descktop & not Desktop
                          	NavBar::begin(['brandLabel'=>'<img src="'.\Yii::getAlias("@web").'/themes/material-COC/assets/images/om.png" style="display: inline-block;"><span style="display: inline-block;">&nbsp;&nbsp;Evaluation</span>']);
                            echo NavX::widget(
                              [
                                'options' => ['class' =>'navbar-nav navbar-right card' ], 
                                'items' => $items,
                                'activateParents' => true,
                                'encodeLabels' => false
                              ]);
                          	NavBar::end();
	                     
	                    else:
                      		NavBar::begin(['brandLabel'=>'<img src="'.\Yii::getAlias("@web").'/themes/material-COC/assets/images/om.png" style="display: inline-block;"><span style="display: inline-block;">&nbsp;&nbsp;Evaluation</span>']);
                            echo Nav::widget(
                              [
                                'options' => ['class' =>'navbar-nav navbar-right card' ], 
                                'items' => $items,
                                'activateParents' => true,
                                'encodeLabels' => false
                              ]);
                          	NavBar::end();
                        endif;
                     ?>
                      
              </div>
          </nav>
      </div>

          