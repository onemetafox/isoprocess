<?php require_once('./config.php'); ?>
<?php $this->load->view('header');?>
<style>
	.display-hide {
        display: none;
    }
</style>

<section class="innerPageBanner aboutPageBanner">
    <div class="container">
        <div class="pageTitleBox wow fadeInUp">
            <h1>Payment</h1>
            <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry Lorem Ipsum has been the industry's standard dummy text.</p>
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
            <div class="row">
                <label class="radioBox">
                    <input type="radio" class="payment_method" name="payment_method" value="paypal" checked >
                    <span class="checkmark" style="margin-top:12px"></span>
                    <img style="width:225px" src="<?=base_url()?>assets/images/paypalbtn.png" alt="Buy now with PayPal" />
                </label>
            </div>
            <div class="row">
                <label class="radioBox">
                    <input type="radio" class="payment_method" name="payment_method" value="stripe">
                    <span class="checkmark" style="margin-top:12px"></span>
                    <img style="width:225px" alt="Visa Checkout" class="v-button" role="button" src="<?=base_url()?>assets/images/stripebtn.svg">
                </label>
            </div>
            <label class="checontainer" style="padding:0;">
                <input id ='agree' type="checkbox" style="float : left; margin-top: 6px;  position: relative; opacity: 5">
                <div style="margin-left : 25px; ">I Agree with <a target="_blank" href = "<?php echo base_url("index.php/auth/terms")?>"
                    style = "font-size: 17px; font-style: italic; text-decoration: underline; color: #a307a5;">Terms</a>
                    for QCIL
                </div>
            </label>
			<div class="CardBtn" id = 'div_card' style="display: none">
				<button id="checkout-button" onclick="pay()" style = "font-weight: 400; font-family: 'Poppins', sans-serif; background-color: #4c96f3; border: none; width: 100px; height: 46px; border-radius: 5px; color: white; margin-right: 40px;">Pay</button>
				<a href="<?php echo base_url('index.php/auth/reg_pay_plans')?>" class="Previous"><i class="fa fa-reply" aria-hidden="true"></i> Previous</a>
			</div><!--CardBtn-->
		<!-- Modal -->
		<div class="modal fade" id="myModal" role="dialog">
			<div class="modal-dialog">
				<div class="modal-content ModelInner">
					<div class="modal-header">
						<h5 class="modal-title">Stripe Payment</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<form id = "stripePayment" class="needs-validation" method="post" novalidate="novalidate" data-stripe-publishable-key="<?= $stripe['publishable_key'] ?>">
					<div class="modal-body">
						<div class="alert alert-danger alert-dismissible display-hide">
							<button type="button" class="close" data-dismiss="alert">&times;</button>
							<span id="errorMsg"></span>
						</div>
						<div class="alert alert-success alert-dismissible display-hide">
							<button type="button" class="close" data-dismiss="alert">&times;</button>
							<span id="successMsg"></span>
						</div>
						<div class="form-group text-center">
							<ul class="list-inline" style="text-align:center">
								<li class="list-inline-item"><i class="text-muted fa fa-cc-visa fa-2x"></i></li>
								<li class="list-inline-item"><i class="fa fa-cc-mastercard fa-2x"></i></li>
								<li class="list-inline-item"><i class="fa fa-cc-amex fa-2x"></i></li>
								<li class="list-inline-item"><i class="fa fa-cc-discover fa-2x"></i></li>
							</ul>
						</div>
						
						<div class="row">
							<div class="col-md-12">
								<label for="cc-name" class="control-label">Name on card</label>
								<input id="cc-name" name="cc-name" type="text" class="form-control cc-name valid" required>
								<div class="invalid-feedback">Please enter the name on card</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-12">
								<label for="cc-number" class="control-label">Card number</label>
								<input id="cc-number" name="cc-number" type="tel" class="form-control cc-number identified number" maxlength="19" value="4242424242424242" required>
								<!-- <div class="invalid-feedback">Please enter the card number</div> -->
							</div>
						</div>
						<div class="row">
							<div class="col-sm-4">
								<label for="cc-month" class="control-label">Month</label>
								<input id="cc-month" name="cc-month" type="tel" class="form-control cc-month number" value="12" placeholder="MM" maxlength="2" required>
								<!-- <div class="invalid-feedback">Please enter the exp. month</div> -->
							</div>
							<div class="col-sm-4">
								<label for="cc-year" class="control-label">Year</label>
								<input id="cc-year" name="cc-year" type="tel" class="form-control cc-year number" value="2020" placeholder="YYYY" maxlength="4" required>
								<!-- <div class="invalid-feedback">Please enter the exp. year</div> -->
							</div>
							<div class="col-sm-4">
								<label for="x_card_code" class="control-label">CVV/CVV2</label>
								<input id="x_card_code" name="x_card_code" type="tel" class="form-control cc-cvc number" value="123" maxlength="4" required>
								<!-- <div class="invalid-feedback">Please enter the security code</div> -->
							</div>
						</div>
						<br>
					</div>
					<div class="modal-footer">
						<button type="submit" class="btn btn-primary">Pay now</button>
						<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
					</div>
					</form>
				</div>
			</div>
		</div>
		</div><!--PaymentInner-->
	</div><!--container-->
</section><!--Pricing_Section-->
<form id="paypalPayment" action="<?= base_url()?>index.php/auth/paypalPayment" method="post">
    <input type="hidden" name = "id" value = "<?= $plan->plan_id?>">
</form>
<script src="<?= base_url()?>assets/js/main.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.blockUI/2.70/jquery.blockUI.min.js"></script>
<script type="text/javascript" src="https://js.stripe.com/v2/"></script>
<script>
    var payment_method = 'paypal';
    $(document).ready(function() {
        $('input[type=radio][name="payment_method"]').change(function() {
            if($(this).val() == 'paypal'){
                payment_method = 'paypal';
            }else{
                payment_method = 'stripe';
            }
                
        });

        $("#agree").change(function(){
            if($("#agree").prop("checked")){
                $("#div_card").css("display","block");
            }else{
                $("#div_card").css("display","none");
            }
        })

        $("#stripePayment").submit( function(event) {
            event.preventDefault();
            $.blockUI({ css: {
                border: 'none',
                padding: '15px',
                backgroundColor: '#000',
                '-webkit-border-radius': '10px',
                '-moz-border-radius': '10px',
                opacity: .5,
                color: '#fff'
            } });

            // createToken returns immediately - the supplied callback submits the form if there are no errors
            Stripe.card.createToken({
                number: $('.cc-number').val(),
                cvc: $('.cc-cvc').val(),
                exp_month: $('.cc-month').val(),
                exp_year: $('.cc-year').val(),
                name: $('.cc-name').val(),
            }, stripeResponseHandler);

            $.unblockUI();

            return false; // submit from callback
        });
    });
    
    var stripeForm = $('.needs-validation');

    // this identifies your website in the createToken call below
    Stripe.setPublishableKey(stripeForm.data('stripe-publishable-key'));

    function stripeResponseHandler(status, response) {
        var stripeError = $('.alert-danger');
        var stripeSuccess = $('.alert-success');
        if (response.error) {
            stripeError.show().delay(3000).fadeOut();
            $('#errorMsg').text(response.error.message);
        } else {
            var token = response['id'];
            stripeForm.append("<input type='hidden' name='stripeToken' value='" + token + "' />");
            var dataPost = stripeForm.serializeArray();

            $.post( '<?=base_url()?>' + "index.php/auth/add_purchase/<?= $plan->plan_id?>/stripe", dataPost, function(response) {
                $.unblockUI();
                if(response.status){
                    stripeForm[0].reset();
                    stripeSuccess.show().delay(3000).fadeOut();
                    $('#successMsg').text(response.msg);
                }else{
                    stripeError.show().delay(3000).fadeOut();
                    $('#errorMsg').text(response.msg);
                }
            }, "json");
        }
    }

    // only numbers are allowed
    $(".number").keydown(function (e) {
        // Allow: backspace, delete, tab, escape, enter and .
        if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190]) !== -1 ||
            // Allow: Ctrl+v, Command+V
            (e.keyCode == 118 && ( e.ctrlKey === true || e.metaKey === true ) ) ||

            // Allow: Ctrl+V, Command+V
            (e.keyCode == 86 && ( e.ctrlKey === true || e.metaKey === true ) ) ||

            // Allow: Ctrl+A, Command+V
            ((e.keyCode == 65 || e.keyCode == 97 || e.keyCode == 103 || e.keyCode == 99 || e.keyCode == 88 || e.keyCode == 120 )&& ( e.ctrlKey === true || e.metaKey === true ) ) ||

            // Allow: home, end, left, right, down, up
            (e.keyCode >= 35 && e.keyCode <= 40)) {
                // let it happen, don't do anything
                return;
        }
        // Ensure that it is a number and stop the keypress
        if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
            e.preventDefault();
        }
    });

    function pay(){
        if(payment_method == 'paypal'){
            $("#paypalPayment").submit();
        }else{
			$("#myModal").modal();
        }
    }
</script>
<?php $this->load->view('footer');?>