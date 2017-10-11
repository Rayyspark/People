<?php
	$Title = 'Search People';
	include_once('inc/Head.php');	
	include_once('inc/Functions.php');
	 //iniializing session
    if (!session_start()){
        session_start(); 
    }
    // Class instance
    $People = new People();
    $PeopleCon = $People->GetCon();
    $Msg = ''; 
    include_once('inc/Auth.php');
	
	//when search button is clicked
	if (isset($_POST['SearchBtn'])){
		$SchTxt =$People->CleanData($_POST['SchTxt']);
		
		//Validating all Data
		if ($SchTxt == ''){
			$Msg = '<h5 class="alert alert-danger">Please enter Search word</h5>';
		}elseif(strlen($SchTxt) < 2){
				$Msg = '<h5 class="alert alert-danger">Search word is too short.</h5>';
			}else{

				//Checking Text
				$SearchExploded = explode (' ', $SchTxt);
                $N = 0; 
				foreach( $SearchExploded as $SearchEach ) {
                    $N++;
					$Construct = '';
                        if($N == 1){
                            $Construct .=" Uname LIKE '%$SearchEach%' OR Items LIKE '%$SearchEach%'";
						} else{
							$Construct .=" Uname LIKE '%$SearchEach%' OR Items LIKE '%$SearchEach%'";
						}

                }
				
				$Sql = "SELECT * FROM users WHERE $Construct";
				$RS = $People->RunQuery($PeopleCon,$Sql);
				if($Sql){
					if($RS){
						$Nm = '';
						$Pn = '';
						$I = 0;
						while($Row = mysqli_fetch_assoc($RS)){
							$Nm .= '<tr><td>'.$People->CleanData($Row['Uname']).'</td><td>'.$People->CleanData($Row['Items']).'</td></tr>';
							$I++;
						}
						
						if ($I < 1){
							$Msg = '<h5 class="alert alert-danger">No Record Found!</h5>';
						}else{
							$Msg ='<h5 class="alert alert-success"> '.$I.' Record Found </h5>';

							$_SESSION['Rec'] = $Nm;
				
						}
						
					}else{
						$Msg = '<h5 class="alert alert-danger">No Record Found!</h5>';
					}
					
				}else{
					$Msg = '<h5 class="alert alert-danger">Query Failed!</h5>';
				}
				
			}
			
	}
?>		
		<div class="row">
		    <section class="col-md-10 col-md-offset-1 ">
		    		<div class="panel panel-default">
		         		<div class="panel-body  ">
		         		  <h5>Please fill in the data below:</h5>
							<?php
								if(isset($Msg) && $Msg != ''){
									echo $Msg;
									$Msg ='';
								}
							?>
							<div class="col-md-8">
								<form  class="" method="POST"  enctype="multipart/form-data" action="">
									<div class="form-group">
										<input class="form-control" type="text" name="SchTxt" placeholder="Please enter,Name or Item" required
										maxlength="100" value="<?php if(isset($_POST['SearchBtn'])){ echo $People->CleanData($_POST['SchTxt']); } ?>">
									</div>
									<div class="form-group">
										<button name="SearchBtn" type="submit" class="btn btn-success glyphicon glyphicon-search ">
											Search
										</button>
									</div>
								</form>
                  			</div>
                  		</div>
                  	</div>
            </section>
            <?php
            	if(isset($_SESSION['Rec'])){
            	echo '
            		<section class="col-md-10 col-md-offset-1 ">
				    	<div class="panel panel-default">
				         	<div class="panel-body  ">
				         		 <div class="table-responsive">
						         	<table class="table table-striped" >
		                                <tr class="active">
		                                    <th>Username</th>
		                                    <th>Items</th>
		                                 </tr>
				                  		'.$_SESSION["Rec"].'
				                  	</table>
			                  	</div>
		                  	</div>
		                </div>
		            </section>
		         ';
            		unset($_SESSION["Rec"]);
            	} 
            ?>
        </div>
    
<?php
	include_once('inc/Footer.php') ;
?>
