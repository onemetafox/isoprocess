<?php $this->load->view('header');?>

<section class="innerPageBanner aboutPageBanner">
    <div class="container">
        <div class="pageTitleBox wow fadeInUp">
            <h1>Pricing</h1>
            <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry Lorem Ipsum has been the industry's
            standard dummy text.</p>
        </div>
    </div>
</section>
<!--innerPageBanner-->
<section class="Pricing_Section">
    <div class="container">
        <ul class="nav nav-tabs">
            <li class="active"><a data-toggle="tab" href="#home">Monthly</a></li>
            <li><a data-toggle="tab" href="#menu1">Annually</a></li>
        </ul>
        <div class="tab-content">
            <div id="home" class="tab-pane fade in active">
            <?php foreach ($month_plan as $val):?>
                <div class="col-sm-3 col-md-3 col-xs-6">
                    <div class="PricingWrapper">
                        <div class="pricing"> 
                        <?php if(strtolower($val->plan_name) == 'silver'):?>
                            <div class="PricingTop">
                        <?php endif; ?>
                        <?php if(strtolower($val->plan_name) == 'unlimit'):?>
                            <div class="PricingTop PricingTop1">
                        <?php endif; ?>
                        <?php if(strtolower($val->plan_name) == 'gold'):?>
                            <div class="PricingTop PricingTop2">
                        <?php endif; ?>
                                <h3><?php echo $val->plan_name?></h3>
                            </div><!--PricingTop-->
                            <div class="PricingMiddle">
                                <ul>
                                    <li>$<?php echo $val->total_amount?></li>
                                    <li>Limiation of consultant:<br><?php echo $val->no_of_user?></li>         
                                </ul>
                                <a href="<?php echo base_url('index.php/auth/load_plan/'.$val->plan_id)?>" class="SignUp">Select Plan</a>
                            </div><!--PricingMiddle-->
                        </div><!--pricing-->
                    </div><!--PricingWrapper-->
                </div><!--col-sm-3-->
            <?php endforeach; ?>
            <?php if(empty($this->session->userdata('plan_id'))):?>
                <?php if(!empty($trial_plan)):?>
                <div class="col-sm-3 col-md-3 col-xs-6">
                    <div class="PricingWrapper">
                        <div class="pricing">
                            <div class="PricingTop PricingTop3">
                                <h3><?php echo $trial_plan->plan_name?></h3>
                            </div><!--PricingTop-->
                            <div class="PricingMiddle">
                                <ul>
                                    <li>You will expire in 14 Days</li>
                                    <li>Limiation of consultant:<br><?php echo $trial_plan->no_of_user?></li>
                                </ul>
                                <a href="<?php echo base_url('index.php/auth/load_plan/'.$trial_plan->plan_id)?>" class="SignUp">Select Plan</a>
                            </div><!--PricingMiddle-->
                        </div><!--pricing-->
                    </div><!--PricingWrapper-->      
                </div><!--col-sm-3-->
                <?php endif;?>
            <?php endif;?>
        </div>
        <div id="menu1" class="tab-pane fade">
        <?php foreach ($year_plan as $val):?>
            <div class="col-sm-3 col-md-3 col-xs-6">
                <div class="PricingWrapper">
                    <div class="pricing">
                    <?php if(strtolower($val->plan_name) == 'silver'):?>
                        <div class="PricingTop">
                    <?php endif; ?>
                    <?php if(strtolower($val->plan_name) == 'unlimit'):?>
                        <div class="PricingTop PricingTop1">
                    <?php endif; ?>
                    <?php if(strtolower($val->plan_name) == 'gold'):?>
                        <div class="PricingTop PricingTop2">
                    <?php endif; ?>
                            <h3><?php echo $val->plan_name?></h3>
                        </div><!--PricingTop-->
                        <div class="PricingMiddle">
                            <ul>
                                <li>$<?php echo $val->total_amount?></li>
                                <li>Limiation of consultant:<br><?php echo $val->no_of_user?></li>         
                            </ul>
                            <a href="<?php echo base_url('index.php/auth/load_plan/'.$val->plan_id)?>" class="SignUp">Select Plan</a>
                        </div><!--PricingMiddle-->
                    </div><!--pricing-->
                </div><!--PricingWrapper-->
            </div><!--col-sm-3-->
        <?php endforeach; ?>
        <?php if(empty($this->session->userdata('plan_id'))):?>
            <?php if(!empty($trial_plan)):?>
            <div class="col-sm-3 col-md-3 col-xs-6">
                <div class="PricingWrapper">
                    <div class="pricing">
                        <div class="PricingTop PricingTop3">
                            <h3><?php echo $trial_plan->plan_name?></h3>
                        </div><!--PricingTop-->
                        <div class="PricingMiddle">
                            <ul>
                                <li>You will expire in 14 Days</li>
                                <li>Limiation of consultant:<br><?php echo $trial_plan->no_of_user?></li>
                            </ul>
                            <a href="<?php echo base_url('index.php/auth/load_plan/'.$trial_plan->plan_id)?>" class="SignUp">Select Plan</a>
                        </div><!--PricingMiddle-->
                    </div><!--pricing-->
                </div><!--PricingWrapper-->      
            </div><!--col-sm-3-->
            <?php endif;?>
        <?php endif;?>
    </div>
</div>
</div><!--container-->
</section><!--Pricing_Section-->
<?php $this->load->view('footer');?>