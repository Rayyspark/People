<?php
  //iniializing session
    if (!session_start()){
        session_start(); 
    }

	$Title = 'People';
    $PageTable = 'users';
	include('inc/Head.php'); 
    include_once('inc/Functions.php');
    include_once('paginate/config.php');

  
   
    // Class instance
    $People = new People();
    $PeopleCon = $People->GetCon();
    $Msg = ''; 

    //when Login button is clicked
    if (isset($_POST['LogBtn'])){
        $Tk = $_POST['Token'];

        if ($People->CheckToken($Tk)){

            unset($_SESSION['StrToken']);
            $Us = $_POST['Uname'];
            $User =  $People->DbEscape( $PeopleCon, $_POST['Uname']);
            $Pword = $People->Encrypt($_POST['Pword']);
           
           
            //$PDate =  $People->CurDate();
            $Msg = '<p class="alert alert-danger"> ok</p>';
            //Validating all Data
            if ($User == ''  || $Pword == ''  )
            {
                $Msg = '<p class="alert alert-danger"> Please enter all data</p>';
            }else{

                $Pword =  $People->DbEscape( $PeopleCon,$Pword);
                $Sql = "SELECT * FROM admins WHERE Username = $User AND Pword = $Pword";
                $Rs =  $People->RunQuery( $PeopleCon,$Sql);
                if (mysqli_num_rows($Rs) < 1){
                   $Msg = '<p class="alert alert-danger"> Sorry No Such User!!! </p>';
                }else{

                    //= '<p class="alert alert-success"> Login Successful</p>';
                    $_SESSION['Admin'] = ucfirst($Us);

                    $People->Redirect($_SESSION['Admin']);
                }
            }
            
        }else{
            $Msg = '<p class="alert alert-danger"> Please Re-submit data</p>';
        }
    
    //when Edit button is clicked
    }else if(isset($_POST['EditBtn'])){
        $Id = $_POST['MyId'];
        //Fetching User data
        $Sql = "SELECT * FROM users WHERE UserId=$Id";
        $Rs =  $People->RunQuery( $PeopleCon,$Sql);
        while ($Row = mysqli_fetch_assoc($Rs)) {
            $_SESSION['Fname'] = $People->CleanData($Row['Fname']);
            $_SESSION['Lname'] = $People->CleanData($Row['Lname']);
            $_SESSION['Uname'] = $People->CleanData($Row['Uname']);
            $_SESSION['Gen'] = $People->CleanData($Row['Gender']);
            $_SESSION['Items'] = $People->CleanData($Row['Items']);
        }
        $_SESSION['Id'] = $Id;
        header('location: EditPeople.php');

    //when Delete button is clicked
    }else if(isset($_POST['DelBtn'])){
       $Id = $_POST['MyId'];
       $Sql = "DELETE FROM users WHERE UserID = $Id";
       $Rs =  $People->RunQuery( $PeopleCon,$Sql);
        if (!$Rs){
            $_SESSION['Msg'] = '<p class="alert alert-danger">Sorry User Not Deleted </p>';
        }else{
            $_SESSION['Msg'] = '<p class="alert alert-success"> User Deleted </p>';
        }
    }


?>
        <div class="row">
            <section class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">
	                <div class="panel-body">
                            <?php 
                                include_once('LoginModal.php');
                                if(isset($_SESSION['Msg1'])){
                                    echo $_SESSION['Msg1'];
                                    unset($_SESSION['Msg1']);
                                }
                                if (!isset($_SESSION['Admin'])){
                                    if(isset($Msg) && $Msg != ''){
                                        echo $Msg;
                                        unset($Msg);
                                    }
                                    echo '<img src="images/Files.jpeg" class="img img-responsive">';
                                 echo '

                                    <h4>Hi Guest,</h4>Welcome to simple Record Keeping. You can Add, Edit, Delete and search people.
                                        Please login to Access this features';
                               }else if(isset($_SESSION['Admin'])){
                                    if(isset($_SESSION['Msg'])){
                                        //echo $_SESSION['Msg'];
                                        $People->Redirect1('index.php');
                                        unset($_SESSION['Msg']);
                                    }
                                        if ( $Results !='') {
                                            echo  '<h5>Hi '.$People->CleanData($_SESSION['Admin']).'</h5>
                                                <div  class="table-responsive">
                                                <table id="MyTbl" class="table table-striped " >
                                                <tr class="active">
                                                    <th >Fullname</th>
                                                    <th >Items</th>
                                                    <th >Action</th>
                                                </tr>';

                                            for( $i = 1; $i < count( $Results->data ); $i++ ){
                                            echo '<tr>
                                                    <td>'.$Results->data[$i]['Fname'].' '.$Results->data[$i]['Lname'].'
                                                    </td>
                                                    <td>'.$Results->data[$i]['Items'].'
                                                    </td>
                                                    <td>
                                                        <form  method="POST"  enctype="multipart/form-data" >
                                                             <input type="hidden" name="Token" value="'.$People->GenToken().'">
                                                            <input type="hidden" name="MyId" value="'.$Results->data[$i]['UserId'].'">
                                                            <button type="submit"name="EditBtn" class="btn glyphicon glyphicon-edit btn-info"> </button>
                                                            <button type="submit" name="DelBtn" class="MyDelBtn btn  glyphicon  glyphicon-trash btn-danger">  </button>
                                                        </form>
                                                    </td>
                                                </tr>';
                                            }
                                             echo '</table></div>
                                                    <div class="PageBg">'. $Paginator->createLinks($Links).'</div>';
                                        }
                                        else{
                                        unset($_SESSION['Msg']);
                                        echo '<p class="alert alert-danger"> No Record to Display, Please Add New Records</p>';;
                                    }
                                    
                                    
                                }
                        ?>
							
                     </div>
                </div>
            </section>
        </div>
      
<?php
	include_once('inc/Footer.php') ;
?>
