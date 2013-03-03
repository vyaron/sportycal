


            <table border="0px" cellspacing="0px" style="width:460px;margin:auto">
                <tr class="">
                    <td class="tdCorner tdBoxTopLeft"></td>
                    <td class="tdBoxTopMiddle"></td>
                    <td class="tdCorner tdBoxTopRight"></td>
                </tr>
                 <tr class="trBox">
                    <td class="tdBoxLeft"></td>
            
                    <td class="tdBox">
            
                        <center>
                        <h2>Login</h2><br/>
                        </center>      
                    	<div style="width:200px;margin:auto;">          
						<form action="<?php echo url_for('partner/login') ?>" method="POST">
					
						  <table cellpadding="5px" >
						    <?php echo $form ?>
						    <tr>
						      <td colspan="2" class="tar">
						        <input type="submit" value="Login" />
						      </td>
						    </tr>
						  </table>
						</form>
						</div>            
                        <br/>
                        <br/>
                        <center>
                        <a href='<?php echo url_for("main/index")?>' > Back to Home Page </a>
                        </center>
                    </td>
                    <td class="tdBoxRight">
    
                    </td>
                </tr>
                <tr class="">
                    <td class="tdCorner tdBoxBottomLeft"></td>
                    <td class="tdBoxBottomMiddle"></td>
                    <td class="tdCorner tdBoxBottomRight"></td>
                </tr>

            </table>



