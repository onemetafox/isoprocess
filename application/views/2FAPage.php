<?php $this->load->view('header');?>
<style type="text/css">
	.radio>label{font-size: 13px!important}
</style>
<?php 
	$CI =& get_instance();
	$userData = $CI->session->userdata('temp_user');
?>
<section class="LoginBox">
   <form name="login_form" action="<?= base_url('index.php/auth/submitAnswer') ?>" method="post">
	   	<div class="LoginInner wow zoomIn">
		    <div class="form-group">
			     <div class="col-sm-12 LoginImg">
					  <img src="<?php echo base_url()?>assets/home/Images/userimg.png">
					  <h5 class="content-group-lg">Answer Your Security Question</h5>
					  <small class="display-block" style="display: block;margin-top: 3px;color: red;font-weight:700;font-size:20px;">
					    <?php echo validation_errors();
								if ($this->session->flashdata('message')) {
									echo $this->session->flashdata('message');
									$this->session->unset_userdata('message');
								}
					    ?>
					  </small>
			     </div>
		      	 <input type="text" style="outline:none;border: 1px solid #e1e4e5;width: 80%;padding: 10px 12px;" name="security[question]" placeholder="Security Question" value="<?= $userData->security_question; ?>" />
		    </div>
		    <div class="form-group">
		      <input type="text" style="outline:none;border: 1px solid #e1e4e5;width: 80%;padding: 10px 12px;" name="security[answer]" placeholder="Enter your security answer">
		    </div>
		    <a href="javascript:login()" class="hvr-bounce-in">Submit</a>
		    <!-- <span>Forgot Your Password</span> -->
	   </div><!--LoginInner-->
   </form>
</section><!--LoginBox-->
<?php $this->load->view('footer');?>
<script type="text/javascript">
function login(){
	document.login_form.submit();
}
</script>