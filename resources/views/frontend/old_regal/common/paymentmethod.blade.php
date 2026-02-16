
@extends('frontend.layouts.app')

@section('content')

 <div class="main-container container">
    <ul class="breadcrumb">
      <li><a href="#"><i class="fa fa-home"></i></a></li>
      <li><a href="#">Payment Method</a></li>
    </ul>
    <div class="row">
      <div class="col-md-3"></div>
      <div class="col-md-9">
        <div class="panel panel-default">
          <div class="panel-heading">
            <h4><i class="fa fa-shopping-cart"></i> Payment Method</h4>
          </div>
        <form action="">
            <div class="panel-body">
              <div class="please-panel">
                <p>Please select the preferred Payment method to use on this order.</p>
                <p>All transactions are secure and encrypted, and we neverstore. To learn more, please view our privacy policy. </p>
              </div>
               <div class="redio-btm-area_one">
                 <div class="debit-single">
                  <input type="radio" id="test9" name="radio-group-one" checked>
                   <label for="test9"><strong><span style="color: #337ab7; margin-right: 6px; margin-bottom: 5px">Channel 1: </span></strong></label>
                  <strong>Debit or Credit Card</strong>
                  <div class="acrd-dvt">
                   <ul>
                     <li><img src="image/pay/visa.png" alt=""></li>
                     <li><img src="image/pay/mastercardd.png" alt=""></li>
                   </ul>
                   </div>
                   <span>(You can use any bank Debit & Credit card oevr here)</span>
                </div>

                <div class="debit-single">
                   <input type="radio" id="test8" name="radio-group-one">
                   <label for="test8"><strong><span style="color: #337ab7; margin-right: 6px;">Channel 2: </span></strong></label>
                   <strong>Debit or Credit Card</strong>
                   <div class="acrd-dvt">
                   <ul>
                     <li><img src="image/pay/visa.png" alt=""></li>
                     <li><img src="image/pay/mastercardd.png" alt=""></li>
                   </ul>
                   </div>
                   <span>(You can use any bank Debit & Credit card oevr here)</span>
                </div>

                <div class="debit-single">
                   <input type="radio" id="test3" name="radio-group-one">
                   <label for="test3">
                    <div class="acrd-dvt">
                     <ul>
                       <li><img src="image/pay/rocket.png" alt=""></li>
                     </ul>
                     </div>
                   </label>
                </div>
                <div class="debit-single">
                   <input type="radio" id="test4" name="radio-group-one">
                   <label for="test4">
                    <div class="acrd-dvt">
                     <ul>
                       <li><img src="image/pay/Bikash.png" alt=""></li>
                     </ul>
                     </div>
                   </label>
                </div>
              </div>
              <div class="checkbox">
                <label>
                  <input type="checkbox" checked="checked" value="1" name="shipping_address">
                  I have read and agree to the  <strong>Terms & Conditions</strong></label>
                </div>
            </div>
            <div class="panel-footer" style="overflow: hidden; border-top: none;">
              <div class="buttons carring-btn-gp">
                <div class="pull-left"><a href="checkout.html" class="btn btn-back-one ">Back</a></div>
                <div class="pull-right"><a href="order-review.html" class="btn  btn-back-two">Next</a></div>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

@endsection