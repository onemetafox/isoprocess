<?php require_once('./config.php'); ?>
<?php $this->load->view('header');?>
<section class="innerPageBanner aboutPageBanner">

	<div class="container">

		<div class="pageTitleBox wow fadeInUp">

			<h1>Payment</h1>

			<p>Lorem Ipsum is simply dummy text of the printing and typesetting industry Lorem Ipsum has been the industry's

				standard dummy text.</p>

		</div>

	</div>

</section>

<!--innerPageBanner-->



<section class="Paymet_Section">

<div class="container">

<div class="PaymentInner wow zoomIn">

  <img src="<?php echo base_url()?>assets/home/Images/payImg.png">

  <h1>Payment</h1>

  <label class="checontainer" style="padding:0;">You must pay $<?php echo !empty($plan)?$plan->total_amount:''?></label>

  <label class="checontainer">stripe payment 

  <input type="radio" checked="checked" name="radio">

  <span class="checkmark"></span>

</label>
    <label class="checontainer" style="padding:0;">
        <input id ='agree' type="checkbox" style="float : left; margin-top: 6px;  position: relative; opacity: 5"
               onclick="if(this.checked){document.getElementById('div_card').style.removeProperty('display');
                   } else {document.getElementById('div_card').setAttribute('style', 'display:none')}"
        >
        <div style="margin-left : 25px; ">I Agree with <a target="_blank" href = "<?php echo base_url("index.php/auth/terms")?>"
            style = "font-size: 17px; font-style: italic; text-decoration: underline; color: #a307a5;">Terms</a>
            for QCIL
        </div>
    </label>

<div class="CardBtn" id = 'div_card' style="display: none">

    <button id="checkout-button" style = "font-weight: 400; font-family: 'Poppins', sans-serif; background-color: #4c96f3; border: none; width: 100px; height: 46px; border-radius: 5px; color: white; margin-right: 40px;">Pay</button>
    <a href="<?php echo base_url('index.php/auth/reg_pay_plans')?>" class="Previous"><i class="fa fa-reply" aria-hidden="true"></i> Previous</a>

    <script>
        var handler = StripeCheckout.configure({
            key: "<?php echo $stripe['publishable_key'];?>",
            token: function(token) {
                document.getElementById('checkout-button').style.backgroundColor = "gray";
                document.getElementById('checkout-button').setAttribute('disabled', 'disabled');
                $.ajax({
                    type : 'POST',
                    url : "<?php echo base_url('index.php/auth/sendNotification')?>"
                });
            }
        });

        document.getElementById('checkout-button').addEventListener('click', function(e) {
            // Open Checkout with further options
            handler.open({
                name: 'Name of Product',
                description: 'Access for a year',
                amount: '<?php echo $plan->total_amount?>'
            });
            e.preventDefault();
        });



        // Close Checkout on page navigation
        window.addEventListener('popstate', function() {
            handler.close();
        });
    </script>


 <!-- Modal -->

  <div class="modal fade" id="myModal" role="dialog">

    <div class="modal-dialog">

    

      <!-- Modal content-->

      <div class="modal-content ModelInner">

        <div class="modal-header">

          <button type="button" class="close" data-dismiss="modal">&times;</button>

        </div>

        <div class="modal-body">

         <div class="PaymentMethod">

  <div class="Payment_Wrapper PaymentMethod">

   <h1>Payment Details</h1>

   <div class="Payment_Wrapper_inner">

   <div class="form-group">

    <div class="col-sm-12"><label>CARD NUMBER</label>

      <input type="text" placeholder="Valid Card Number"></div>

   </div>

   

   <div class="row matop">

   <div class="col-sm-6">

    <div class="col-sm-12"> <label>EXPIRY DATE</label></div>

      <div class="nil">

    <div class="col-sm-6">

        <input type="text" placeholder="Month">

   </div>

   <div class="col-sm-6">

     <input type="text" placeholder="Year">

   </div>

   </div>

  </div><!--col-sm-6-->

   

   <div class="col-sm-6">

    <div class="col-sm-12"><label>CV CODE</label>

     <input type="text" placeholder="CV"></div>

   </div>

   

   </div>

   <div class="col-sm-6 checkboxMin">

    <label class="CheckBoxs">Remember Me

  <input type="checkbox" checked="checked">

  <span class="checkmark"></span>

</label>

   </div>

   <div class="col-sm-12"><a class="Pay_btn">Pay $<?php echo $plan->price?></a></div>

   </div><!--Payment_Wrapper_inner-->

  

</div><!--Payment_Wrapper-->

    

</div>

        </div>

        <div class="modal-footer">

          

        </div>

      </div>

      

    </div>

  </div>



</div><!--CardBtn-->



</div><!--PaymentInner-->



</div><!--container-->

</section><!--Pricing_Section-->
<?php $this->load->view('footer');?>