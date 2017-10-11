<?php
	$Title = 'Edit People';
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
	//when Update button is clicked
	if (isset($_POST['UpBtn'])){
      
        if (isset($_SESSION['StrToken'])){
            unset($_SESSION['StrToken']);
            //Cleaning Data
            $Id = $_SESSION['Id'];
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
                $Sql = "UPDATE users SET Fname = $Fname, Lname = $Lname, Uname = $User,
                		Gender = $Gen, Items =  $Items, UpdatedAt = '$Date' WHERE 
                		UserId = $Id ";

                $Rs = $People->RunQuery($PeopleCon,$Sql);
                if (!$Rs){
                   $Msg = '<p class="alert alert-danger">Record Not Updated</p>';
                }else{
                	$_SESSION['Msg1'] = '<p class="alert alert-success">Record Updated</p>';
                	header('location: index.php');
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
									<input type="hidden" name="MyId" value="<?php echo $_SESSION['Id']?>">
									
									<div class="form-group">
										<input class="form-control" type="text" name="Fname" placeholder="Please enter First Name" required
										maxlength="100" value="<?php if(isset($_POST['SaveBtn'])){ echo $People->CleanData($_POST['Fname']); }else{ echo $_SESSION["Fname"];} ?>" >
									</div>
									<div class="form-group">
										<input class="form-control" type="text" name="Lname" placeholder="Please enter Last Name" required
										maxlength="100" value="<?php if(isset($_POST['SaveBtn'])){ echo $People->CleanData($_POST['Lname']); }else{ echo $_SESSION["Lname"];}  ?>" >
									</div>
									<div class="form-group">
										<input class="form-control" type="text" name="Uname" placeholder="Please enter Username" required
										maxlength="100" value="<?php if(isset($_POST['SaveBtn'])){ echo $People->CleanData($_POST['Uname']); }else{ echo $_SESSION["Uname"];}  ?>" readonly >
									</div>
									<div class="form-group">
										<Select class="form-control" name="Gen" required
											maxlength="100">
											<option value="">Please select Gender</option>
											<option value="Male" <?php echo (isset($_POST['Gen'])
												&& ($_POST['Gen']) == 'Male')? 'selected' :'';
												echo (isset($_SESSION["Gen"]) && ($_SESSION["Gen"]) == 'Male')? 'selected':'';
												?>>Male</option>
											<option value="Female" <?php echo (isset($_POST['Gen'])
												&& ($_POST['Gen']) == 'Female')? 'selected' :'';
												echo (isset($_SESSION["Gen"]) && ($_SESSION["Gen"]) == 'Female')? 'selected':'';
											?> >Female</option>
											
										</select>
									</div>
									<div class="form-group">
										<textarea rows="8" class="form-control" name="Items" placeholder="Please enter your Items here (eg. car, tv)" maxlength="800" required ><?php if(isset($_POST['SaveBtn'])){ echo $People->CleanData($_POST['Items']); }else{ echo $_SESSION["Items"];} ?></textarea>
									
									</div>
									<div class="form-group">
										<input class="btn btn-success" type="submit" value="Update" name="UpBtn" >
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
