<?php
/**
 * @author Br. Saket
 * Credits: user3132270 of stackOverflow
 * Creates Search model for Array Data Provider just like Search Model for Active Data Provider
 * @param $items: The data array. 
 * @param $searchAttributes:The columns should match the data array ($items.). if any element is missing, it will be
 * that column will not be shown
 * @return $searchModel to be used as value for filterModel parameter in ArrayDataProvider. 
 * It contains three return Parameter:
 * 'searchModel', 'searchColumn', 'data'. They have to be used as under:
 * use 'data' as value for 'allModels' parameter of ArrayDataProvider in CONTROLLER
 * pass $searchData as parameter in the action of Controller for rendering the view
 * in VIEW use it in something like this:
 		<?php Pjax::begin(); ?>   
		<?php 
			echo Gridview::widget(
	         	[
	        		'dataProvider'=>$dataProvider,
	        		'filterModel'=>$searchData['searchModel'],
	        		'columns' => array_merge(
	        			[            
	          				['class' => 'yii\grid\SerialColumn'],
		        		],
		        		$searchData['searchColumns']
	        		),
	    		]
			);
     	?>
    <?php Pjax::end(); ?>
 * DONT forget the Pjax at the beginning and the end of the grid for this to function.
 */

namespace common\components;
use Yii;

class FilterForArrayDataProvider{

	public static function filter($items,$searchAttributes)
	{
		
		$searchModel = [];
		$searchColumns = [];

		foreach ($searchAttributes as $searchAttribute) 
		{
		    $filterName = 'filter' . $searchAttribute;
		    $filterValue = Yii::$app->request->getQueryParam($filterName, '');
		    $searchModel[$searchAttribute] = $filterValue;
		    $searchColumns[] = [
		        'attribute' => $searchAttribute,
		        'filter' => '<input class="form-control" name="' . $filterName . '" value="' . $filterValue . '" type="text">',
		        'value' => $searchAttribute,
		    ];
		    $items = array_filter($items, function($item) use (&$filterValue, &$searchAttribute) 
		    {
		        	return strlen($filterValue) > 0 ? stripos('/^' . strtolower($item[$searchAttribute]) . '/', 	strtolower($filterValue)) : true;
		    });
		}

		$searchData=['searchModel'=>$searchModel, 'searchColumns'=>$searchColumns,'data'=>$items ];
	return $searchData;
	}

}


