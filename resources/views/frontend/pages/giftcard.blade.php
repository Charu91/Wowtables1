@extends('frontend.templates.details_pages')

@section('content')

    <form  method="POST" id="giftOrderFor" >
        <!--input type="hidden" name="time" id="fulltime"-->
        <input type="hidden" name="date" id="fulldate">
        <input type="hidden" name="reciever_name" id="reciever_name_p">
        <input type="hidden" name="reciever_email" id="reciever_email_p">
        <input type="hidden" name="is_need_to_be_submitted" id="is_need_to_be_submitted">
        <input type="hidden" name="way_of_order" id="way_of_order_p">
        <input type="hidden" name="exp_choosed" id="exp_choosed_id">
        <input type="hidden" name="send">
    </form>
    <div class="container giftcard-page">
        <div class="row">
            <div class="col-md-12">
                <div class="gift-cover">
                    <img src="/assets/img/gift_header.png" class="img-responsive">
                </div>
            </div>
            <div class="col-md-12 text-center title">
                <p class="lead">Gift your friends and loved ones a WowTables dining experience</p>
                <p>WowTables Gift Cards are a unique and exciting gifting option and can be bought for a particular experience or for a particular cash value.</p>
            </div>
            <div class="col-md-6 col-sm-6">
                <form class="form-horizontal gift-form" role="form" method="post" id='gift_form' action="{{URL::to('/')}}/gift_cards/checkout">
                    <div class="form-group">
                        <label for="inputName" class="col-sm-4 control-label">Receiver's Name:</label>
                        <div class="col-sm-8">
                            <input type="text" name="receiver_name" class="form-control" id="receiver_name" placeholder="Name" value="">
                            <label class="control-label error-code text-danger" id="error_receiver_name" style="display:none;"></label>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="inputEmail" class="col-sm-4 control-label">Receiver's Email:</label>
                        <div class="col-sm-8">
                            <input type="email" name="receiver_email" id="receiver_email" class="form-control" id="inputEmail" placeholder="Email" value="">
                            <label class="control-label error-code text-danger" id="error_receiver_email" style="display: none;"></label>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="inputGifttype" class="col-sm-4 control-label">Gift Card type:</label>
                        <div class="col-sm-8">
                            <div class="radio cash_card">
                                <label>
                                    <input type="radio" name="gift_opt" id="gift_cash" value="1" >
                                    Gift Card for a cash value
                                </label>
                            </div>
                            <div class="radio experience_card">
                                <label>
                                    <input type="radio" name="gift_opt" id="gift_exp" value="2">
                                    Gift Card for a particular dining experience
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group" id="gift_amounts_block">
                        <label for="inputAmount" class="col-sm-4 control-label">Amount:</label>
                        <div class="col-sm-8">
                            <div data-toggle="buttons" class="radio-btns">
                                <table>
                                    <tr>
                                        <td>
                                            <label class="btn btn-warning" style="width:100%">
                                                <input type="radio" name="options" id="select_amount_1" value="500"> Rs. 500
                                            </label>
                                        </td>
                                        <td>
                                            <label class="btn btn-warning " style="width:100%">
                                                <input type="radio" name="options" id="select_amount_2" value="1000"> Rs. 1000
                                            </label>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <label class="btn btn-warning " style="width:100%">
                                                <input type="radio" name="options" id="select_amount_3" value="1500"> Rs. 1500
                                            </label>
                                        </td>
                                        <td>
                                            <label class="btn btn-warning" style="width:100%">
                                                <input type="radio" name="options" id="select_amount_4" value="2000"> Rs. 2000
                                            </label>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="2" class="text-right">

                                            <input type="text" id="other_amount" name="other_amount" class="form-control" placeholder="Other Amounts" value=''>
                                        </td>
                                    </tr>
                                </table>
                                <label class="control-label error-code text-danger" id="other_amount_error"></label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group dining-experience" id="gift_dinning_experience">
                        <label for="inputDiningexperience" class="col-sm-4 control-label">Choose a Wowtables dining experience:</label>
                        <div class="col-sm-8">
                            <div class="btn-group btn-block">
                                <button data-toggle="dropdown" class="btn btn-default dropdown-toggle" type="button" id="gift_choose_city">
                                    Choose City
                                    <span class="caret"></span>
                                </button>
                                <ul class="dropdown-menu" id='city_list'>
                                    <?php foreach($cities as $city_id=>$cityName):?>
                                    <li>
                                        <a href="javascript:" class="list-group-item" data-cityID = "<?=$city_id;?>" rel="<?=$cityName;?>">
                                            <h5 class="list-group-item-heading"><?=ucfirst($cityName);?></h5>
                                        </a>
                                    </li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                            <div class="btn-group btn-block hidden">
                                <button data-toggle="dropdown" class="btn btn-default dropdown-toggle" type="button" id="gift_choose_exp">
                                    View Experiences
                                    <span class="caret"></span>
                                </button>
                                <ul class="dropdown-menu" id='location_list'>

                                </ul>
                            </div>
                            <div class="row">

                                <p class="col-md-12 hidden" id='one_price'>Experience price: <span><?=(isset($gift_order['choosed'])) ? 'Experience price: Rs. '.$choose_exp_price:'';?></span></p>
                                <span class="addons_price_listing" style="display:none;">

                                </span>
                                <p style="margin-top:5px;"><span class="col-md-5 col-xs-5">No of People:
                                    <?php $number_people = array(1,2,3,4,5,6,7,8,9,10);?>

                                    <select class="form-control" id="gift_no_people" name="gift_no_people">
                                        <?php foreach($number_people as $n):?>
                                        <option value="<?=$n?>">
                                            <?=$n?>
                                        </option>
                                        <?php endforeach ?>
                                    </select></span>

                                </p>
                                <p class="col-md-12">
                                <span id="addons_list"></span>
                                </p>
                                <p class="col-md-12">Total <span>(including taxes & charges)</span>: <span id="total"></span></p>
                                <div class="clearfix"></div>
                                <p class="col-md-12"><small><a id="link_to_experience_description" href="" target="_blank">View Experience Details</a></small></p>
                                <label class="control-label error-code text-danger" id="total_amount_error"></label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="inputGifttype" class="col-sm-4 control-label">Delivery Options:</label>
                        <div class="col-sm-8">
                            <div class="radio gift_send_email">
                                <label>
                                    <input type="radio" name="gift_send" id="gift_send_email" value="email"   >
                                    Send to reciever by email
                                </label>
                            </div>
                            <div class="radio gift_send_mail">
                                <label>
                                    <input type="radio" name="gift_send" id="gift_send_mail" value="mail" >
                                    Send by mail (Rs. 50 handling charges apply)
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group" id="mailing_address">
                        <div class="col-sm-12">
                            <label for="inputAddress" class="control-label">Mailing Address:</label>
                        </div>
                        <div class="col-sm-12">
                            <textarea name="mailing_address" class="form-control" rows="3" placeholder="Enter Mailing Address"></textarea>
                            <label class="control-label error-code text-danger" id="mailing_address_error" style="display:none;">Please enter a mailing address</label>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-12">
                            <label for="inputInstructions" class="control-label">Special Instructions:</label>
                        </div>
                        <div class="col-sm-12">
                            <textarea class="form-control" name="special_instructions" id="special_instructions" rows="3" placeholder="Enter Instructions"></textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-12 text-right">
                            <input type="hidden" name="amount" id="amount" value="">
                            <input type="hidden" name="amount1" value="">
                            <input type="hidden" name="grandTotal" id="grandTotal" value="">
                            <input type="hidden" name="experiencePrice" id="experiencePrice" value="">
                            <input type="hidden" name='userid' value='<?php echo Session::get('id');?>' id='userid'>
                            <button type="submit" class="btn btn-warning" id='gift_pay' >Proceed to Pay</button>

                        </div>
                    </div>

                    <input type="hidden" name="allow_guest" id="check_allow_guest" value="<?php echo (Session::get('id') > 0 ? "No" : "Yes");?>">
                    <input type="hidden" name="gift_choose_exp"  id="exp_sel_id" >
                </form>

            </div>
            <div class="col-md-6 col-sm-6">
                <div class="gift-content">
                    <p class="lead">About Wowtables Gift Cards</p>
                    <ul id='about_gourmet'>
                        <li>WowTables Gift Cards are not valid directly at any restaurant. They can only be used when making a reservation for a WowTables experience</li>
                        <li>To use your gift card, simply enter your gift card ID in the special requests box while making a reservation on WowTables.com or by calling our concierge desk to make a reservation</li>
                        <li>Gift Cards need not be physically carried at the time of reservation</li>
                        <li>Any a-la-carte orders will be charged separately at the restaurant and cannot be paid for with the gift card credit</li>
                        <li>Regular WowTables Gift Cards are valid for 1 year after the date of purchase.</li>
                        <li>Experience specific gift cards are valid for a period of Three Months and will convert to cash value gift cards if the relevant experience is no longer available</li>
                        <li>If you wish to receive the gift card yourself please enter your own email/mailing address</li>
                        <li>WowTables Gift Cards are valid at selected experiences</li>
                    </ul>
                    <a target="_blank" href="<?=URL::to('/');?>/{{strtolower($current_city)}}/giftcard-experiences" class="btn btn-warning gf_cart_buttons_left">Gift Card Valid Experiences</a>
                </div>

            </div>

        </div>
    </div>

@endsection