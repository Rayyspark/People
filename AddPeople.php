<?php
	$Title = 'Add People';
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
	//when save button is clicked
	if (isset($_POST['SaveBtn'])){
      
        if (isset($_SESSION['StrToken'])){
            unset($_SESSION['StrToken']);
            //Cleaning Data
            $Fname = $People->DbEscape($PeopleCon,($_POST['Fname'])); 
            $Lname = $People->DbEscape($PeopleCon,($_POST['Lname'])); 
            $User = $People->DbEscape($PeopleCon,($_POST['Uname']));
            $Gen = $People->DbEscape($PeopleCon,($_POST['Gen']));
            $Items = $People->DbEscape($PeopleCon,($_POST['Items']));

			//Validating all Data
			if ($Fname== '' || $Lname == '' || $User == '' || $Gen == '' || $Items == ''){
				$Msg = '<p class="alert alert-danger"> Please enter all data</p>';
			}else{
				
				$Date = $People->CurDate();
				//Checking user existence
                $Sql = "SELECT Uname FROM users WHERE Uname = $User";
                $Rs = $People->RunQuery($PeopleCon,$Sql);
                if (mysqli_num_rows($Rs) > 0){
                   $Msg = '<p class="alert alert-danger"> Username Already Exist </p>';
                }else{
                	//Inserting Data
                	$Sql1 = "INSERT INTO users VALUES('',$Fname,$Lname,$User,$Gen, $Items,
                                    '$Date','')";
                    $Rs1 = $People->RunQuery($PeopleCon,$Sql1);
                    if ($Rs1){
                        $Msg = '<p class="alert alert-success"> Record Added</p>';
                    }else{
                     	$Msg = '<p class="alert alert-danger">Record Not Added </p>';
                    }
                	
                }
			}
			
		}else{
			
			$Msg = '<p class="alert alert-danger"> Please Re-submit data</p>';
		}
	}
?>
            <div class="row">
                <section class="col-md-10 col-md-offset-1">
                   <div class="panel panel-default">
                        <div class="panel-body">
							
                            <h5>Please fill in the data below:</h5>
							<?php
								if(isset($Msg) && $Msg != ''){
									echo $Msg;
								}
							?>
						<div class="col-md-10">
								<form  method="POST"  enctype="multipart/form-data" >

									<input type="hidden" name="Token" value="<?php echo $People->GenToken();?>">

									<div class="form-group">
										<input class="form-control" type="text" name="Fname" placeholder="Please enter First Name" required
										maxlength="100" value="<?php if(isset($_POST['SaveBtn'])){ echo $People->CleanData($_POST['Fname']); } ?>" >
									</div>
									<div class="form-group">
										<input class="form-control" type="text" name="Lname" placeholder="Please enter Last Name" required
										maxlength="100" value="<?php if(isset($_POST['SaveBtn'])){ echo $People->CleanData($_POST['Lname']); } ?>" >
									</div>
									<div class="form-group">
										<input class="form-control" type="text" name="Uname" placeholder="Please enter Username" required
										maxlength="100" value="<?php if(isset($_POST['SaveBtn'])){ echo $People->CleanData($_POST['Uname']); } ?>" >
									</div>
									<div class="form-group">
										<Select class="form-control" name="Gen" required
											maxlength="100">
											<option value="">Please select Gender</option>
											<option value="Male" <?php echo (isset($_POST['Gen'])
												&& ($_POST['Gen']) == 'Male')? 'selected' :'';?>>Male</option>
											<option value="Female" <?php echo (isset($_POST['Gen'])
												&& ($_POST['Gen']) == 'Female')? 'selected' :'';?> >Female</option>
											
										</select>
									</div>
									<div class="form-group">
										<textarea rows="8" class="form-control" name="Items" placeholder="Please enter your Items here (eg. car, tv)" maxlength="800" required ><?php if(isset($_POST['SaveBtn'])){ echo $People->CleanData($_POST['Items']); } ?></textarea>
									
									</div>
									<div class="form-group">
										<input class="btn btn-success" type="submit" value="Save" name="SaveBtn" >
									</div>
								</form>
                        	</div>
                    	</div>
                    </div>
                </section>
                
            </div>  
<?php
	include_once('inc/Footer.php') ;
?>
