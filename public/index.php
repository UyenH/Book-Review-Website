<?php
try {
	include __DIR__ . '/../includes/autoload.php';
	
	$route = ltrim(strtok($_SERVER['REQUEST_URI'], '?'), '/');   // JG remove leading / and extract the string till ? from e.g. /ch14_FINAL-Website/public/index.php?joke/list?page=1

	
	//***********************JG  5/24/18 NEW line 8 - 42 ADAPTER to replace URL writing feature b/c .htaccess file is ignored by Apache********************	
	
	
	   //5/22/18 JG NEW4l: adapter to the code b/c of the .htaccess is ignored by apache
	if ($route == ltrim($_SERVER['REQUEST_URI'],  '/') ) 
	    $route = '';  //JG  5/21/18 NEW replaces by ''	    
	else
	    $route = $_SERVER['QUERY_STRING']; // 5/22/18 JG NEW1l: give the query = remaining string
      
	
	
	  //5/22/18 JG NEW6l: adapter to the code b/c of the .htaccess is ignored by apache
	if (strlen(strtok($route, '?')) <  strlen($route))  // string has a second ?
	{ 
		if (strpos($route, 'id')){ //6/7/18 JG is there id?
		   $_GET['id'] = substr ($route, strlen(strtok($route, '?')) + 4, strlen($route)); //6/7/18 JG extract id from e.g. joke/edit?id=37
	       
		  }
		if (strpos($route, 'page') && strpos($route, 'genre')) { //6/7/18 JG is there e.g. joke/list?page=2&genre=3 for display later ?
		   $_GET['page'] =  substr($route, strpos($route, '=') + 1, strpos($route, '&') - strpos($route, '=') - 1 );
           
		
		   $_GET['genre'] = substr ($route, strlen(strtok($route, '&')) + 7, strlen($route)); //6/7/18 JG extract genre id 
		                                                                                //from e.g. joke/list?page=2&genre=3
		   
		}
		else {
		//print('strpos($route, \'genre\') = '. strpos($route, 'genre') . '<br>');
		if (strpos($route, 'genre')){
				
	       $_GET['genre'] = substr ($route, strlen(strtok($route, '?')) + 7, strlen($route)); //6/7/18 JG extract genre id 
		                                                                                          //from e.g. joke/list?genre=6
																						  
		    
		  }
		  //print('strpos($route, \'book\') = '.strpos($route, 'bookId') . '<br>');
		if (strpos($route, 'bookId')){
			$_GET['bookId'] = substr ($route, strlen(strtok($route, '?')) + 8, strlen($route)); //6/7/18 JG extract genre id 
																								   //from e.g. joke/list?genre=6
																						   
			 
		   }
		//UHo new 3l: extract page number form book/review?bookId=#?page=#
		if(strpos($route, 'bookId') && strpos($route, 'page')){
			$_GET['page'] = substr ($route, strlen(strtok($route, '?')) + 16, strlen($route));
		}
		//UHo mod 3l: extract page number form book/list?page=#
		if (strpos($route, 'page') && !strpos($route, 'bookId')){
				$_GET['page'] = substr ($route, strlen(strtok($route, '?')) + 6, strlen($route)); //6/7/18 JG extract page id from e.g. joke/list?page=2	
			}
		} // end else
	
	$route = strtok($route, '?'); //retrieve the string between ? ? - for e.g. index?joke/edit?id=12
	} // end the 1st if	
	
	//****************************END OF JG  5/24/18 NEW line 8 - 42//****************************************************************************************

	$entryPoint = new \Ninja\EntryPoint($route, $_SERVER['REQUEST_METHOD'], new \Ijdb\IjdbRoutes());
	$entryPoint->run();
}
catch (PDOException $e) {
	$title = 'An error has occurred';

	$output = 'Database error: ' . $e->getMessage() . ' in ' .
	$e->getFile() . ':' . $e->getLine();

	include  __DIR__ . '/../templates/layout.html.php';
}
