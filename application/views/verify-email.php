<?php $this->load->view('header');?>
<style type="text/css">
	.radio>label{font-size: 13px!important}
</style>
<section class="LoginBox">
   	<div class="LoginInner wow zoomIn">
	    <div class="form-group">
		     <div class="col-sm-12 LoginImg">
				  <img src="<?php echo base_url()?>assets/home/Images/userimg.png">
				  <!-- <h5 class="content-group-lg">Email Verified</h5> -->
			 </div>
			<p class="congrats-msg">CONGRATS! <br/> YOUR EMAIL IS VERIFIED</p>
			<p class="thankyou-msg">THANK YOU FOR YOUR CHOICE</p>
		</div>
	    <a href="<?= base_url() ?>index.php/Welcome/login" class="hvr-bounce-in">Login</a>
   </div>
</section>
<?php $this->load->view('footer');?>
