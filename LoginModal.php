
        <div class="modal fade"  data-backdrop="static" data-keyboard="false" id="MyModal" tabindex="-1">
                    <div class="modal-dialog modal-sm">
                        <div class="modal-content">
                        	<div class="modal-header">
                            	<button class="close ModCloseBtn" >&times;</button>
                            	<h4 class="modal-title" id="Title">Please enter Login data here</h4>
                            </div>
                            <div class="modal-body">
                                 <form action="" method="POST" enctype="multipart/form-data">
                                    <input type="hidden" name="Token" value="<?php echo $People->GenToken();?>" >
                                    <p id="Msg0" class="alert alert-danger">Please enter username</p>
                                    <div class="form-group">
                                        <input class="form-control" id="Username" type="text" name="Uname" 
										placeholder="Please enter Username" required  maxlength="50" value="<?php if(isset($_POST['SaveBtn'])){ echo $People->CleanData($_POST['Uname']); } ?>"  >
                                    </div>

                                    <p id="Msg1" class="alert alert-danger">Please enter Password</p>
                                    <div class="form-group">
                                        <input class="form-control" id="Pword" type="password" name="Pword" 
										placeholder="Please enter Password" required  maxlength="20" >
                                    </div>
                                    <div class="modal-footer">  
                                        <div class="form-group">
                                            <button class="btn btn-info pull-left" id="LoginBtn" name="LogBtn" >Log in</button>
                                            
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
            </div> 