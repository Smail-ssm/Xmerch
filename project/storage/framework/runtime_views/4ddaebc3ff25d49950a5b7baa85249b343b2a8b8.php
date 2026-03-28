<?php

$pay_data = $gateway->convertAutoData();

?>

<?php if($payment == 'cod'): ?>

<input type="hidden" name="method" value="<?php echo e($gateway->title); ?>">

<?php endif; ?>

<?php if($payment == 'paypal'): ?>

  <input type="hidden" name="method" value="<?php echo e($gateway->name); ?>">

<?php endif; ?>

<?php if($payment == 'stripe'): ?>

  <input type="hidden" name="method" value="<?php echo e($gateway->name); ?>">

  <div class="row">

    <div class="col-lg-6">
      <input class="form-control card-elements" id="cardNumber" name="cardNumber" type="text" placeholder="<?php echo e(__('Card Number')); ?>" autocomplete="off"  autofocus oninput="validateCard(this.value);" />
      <span id="errCard"></span>
    </div>

    <div class="col-lg-6">
      <input class="form-control card-elements" id="cardCVC" name="cardCVC" type="text" placeholder="<?php echo e(__('Cvv')); ?>" autocomplete="off"  oninput="validateCVC(this.value);" />
      <span id="errCVC"></span>
    </div>

    <div class="col-lg-6">
       <input class="form-control card-elements" name="month" type="text" placeholder="<?php echo e(__('Month')); ?>"  />
    </div>

    <div class="col-lg-6">
      <input class="form-control card-elements" name="year" type="text" placeholder="<?php echo e(__('Year')); ?>"  />
    </div>

  </div>
                                <script type="text/javascript" src="<?php echo e(asset('assets/front/js/payvalid.js')); ?>"></script>
                                <script type="text/javascript" src="<?php echo e(asset('assets/front/js/paymin.js')); ?>"></script>
                                <script type="text/javascript" src="https://js.stripe.com/v2/"></script>
                                <script type="text/javascript" src="<?php echo e(asset('assets/front/js/payform.js')); ?>"></script>

  <script type="text/javascript">



    var cnstatus = false;
    var dateStatus = false;
    var cvcStatus = false;

    function validateCard(cn) {
      "use strict";
      cnstatus = Stripe.card.validateCardNumber(cn);
      if (!cnstatus) {
        $("#errCard").html('<?php echo e(__("Card number not valid")); ?>');
      } else {
        $("#errCard").html('');
      }
    }

    function validateCVC(cvc) {
      "use strict";
      cvcStatus = Stripe.card.validateCVC(cvc);
      if (!cvcStatus) {
        $("#errCVC").html('<?php echo e(__("CVC number not valid")); ?>');
      } else {
        $("#errCVC").html('');
      }

    }



  </script>

<?php endif; ?>


<?php if($payment == 'instamojo'): ?>

  <input type="hidden" name="method" value="<?php echo e($gateway->name); ?>">

<?php endif; ?>

<?php if($payment == 'razorpay'): ?>

  <input type="hidden" name="method" value="<?php echo e($gateway->name); ?>">

<?php endif; ?>

<?php if($payment == 'paytm'): ?>

  <input type="hidden" name="method" value="<?php echo e($gateway->name); ?>">

<?php endif; ?>


<?php if($payment == 'sslcommerz'): ?>

  <input type="hidden" name="method" value="<?php echo e($gateway->name); ?>">

<?php endif; ?>

<?php if($payment == 'flutterwave'): ?>

  <input type="hidden" name="method" value="<?php echo e($gateway->name); ?>">

<?php endif; ?>

<?php if($payment == 'paystack'): ?>

  <input type="hidden" name="txnid" id="ref_id" value="">
  <input type="hidden" name="sub" id="sub" value="0">
	<input type="hidden" name="method" value="<?php echo e($gateway->name); ?>">

<?php endif; ?>

<?php if($payment == 'voguepay'): ?>

  <input type="hidden" name="txnid" id="ref_id" value="">
  <input type="hidden" name="sub" id="sub" value="0">
	<input type="hidden" name="method" value="<?php echo e($gateway->name); ?>">

<?php endif; ?>

<?php if($payment == 'mollie'): ?>

  <input type="hidden" name="method" value="<?php echo e($gateway->name); ?>">

<?php endif; ?>

<?php if($payment == 'authorize.net'): ?>

  <input type="hidden" name="method" value="<?php echo e($gateway->name); ?>">

  <div class="row">

    <div class="col-lg-6">
      <input class="form-control card-elements" name="cardNumber" type="text" id="cardNumber" placeholder="<?php echo e(__('Card Number')); ?>" autocomplete="off" autofocus oninput="validateCard(this.value);"/>
      <span id="errCard"></span>
    </div>

    <div class="col-lg-6">
      <input class="form-control card-elements" name="cardCode" id="cardCVC" type="text" placeholder="<?php echo e(__('Card Code')); ?>" autocomplete="off" oninput="validateCVC(this.value);"/>
      <span id="errCVC"></span>
    </div>

    <div class="col-lg-6">
      <input class="form-control card-elements" name="month" type="text" placeholder="<?php echo e(__('Month')); ?>"  />
    </div>

    <div class="col-lg-6">
      <input class="form-control card-elements" name="year" type="text" placeholder="<?php echo e(__('Year')); ?>"  />
    </div>

  </div>

  <script type="text/javascript" src="<?php echo e(asset('assets/front/js/payvalid.js')); ?>"></script>
  <script type="text/javascript" src="<?php echo e(asset('assets/front/js/paymin.js')); ?>"></script>
  <script type="text/javascript" src="https://js.stripe.com/v2/"></script>
  <script type="text/javascript" src="<?php echo e(asset('assets/front/js/payform.js')); ?>"></script>

  <script type="text/javascript">



    var cnstatus = false;
    var dateStatus = false;
    var cvcStatus = false;

    function validateCard(cn) {
      "use strict";
      cnstatus = Stripe.card.validateCardNumber(cn);
      if (!cnstatus) {
        $("#errCard").html('<?php echo e(__("Card number not valid")); ?>');
      } else {
        $("#errCard").html('');
      }
    }

    function validateCVC(cvc) {
      "use strict";
      cvcStatus = Stripe.card.validateCVC(cvc);
      if (!cvcStatus) {
        $("#errCVC").html('<?php echo e(__("CVC number not valid")); ?>');
      } else {
        $("#errCVC").html('');
      }

    }



  </script>


<?php endif; ?>

<?php if($payment == '2checkout'): ?>

  <input type="hidden" name="method" value="<?php echo e($gateway->name); ?>">

  <input id="token" name="token" type="hidden" value="">

  <div class="row">

    <div class="col-lg-6">
      <input class="form-control" id="ccNo" name="cardNumber" type="text" placeholder="<?php echo e(__('Card Number')); ?>" autocomplete="off" />
    </div>

    <div class="col-lg-6">
      <input class="form-control" id="cvv" name="cardCVC" type="text" placeholder="<?php echo e(__('Cvv')); ?>" autocomplete="off" />
    </div>

    <div class="col-lg-6">
      <input class="form-control" id="expMonth" name="month" type="text" placeholder="<?php echo e(__('Month')); ?>"  />
    </div>

    <div class="col-lg-6">
      <input class="form-control" id="expYear" name="year" type="text" placeholder="<?php echo e(__('Year')); ?>"  />
    </div>

  </div>

  <script>

    // Called when token created successfully.
    var successCallback = function(data) {
      var myForm = document.getElementById('twocheckout');

      // Set the token as the value for the token input
      myForm.token.value = data.response.token.token;

      // IMPORTANT: Here we call `submit()` on the form element directly instead of using jQuery to prevent and infinite token request loop.
      myForm.submit();
    };

    // Called when token creation fails.
    var errorCallback = function(data) {
      if (data.errorCode === 200) {tokenRequest();} else {alert(data.errorMsg);}
    };

    var tokenRequest = function() {
      // Setup token request arguments
      var args = {
        sellerId: "<?php echo e($pay_data['seller_id']); ?>",
        publishableKey: "<?php echo e($pay_data['public_key']); ?>",
        ccNo: $("#ccNo").val(),
        cvv: $("#cvv").val(),
        expMonth: $("#expMonth").val(),
        expYear: $("#expYear").val()
      };

      // Make the token request
      TCO.requestToken(successCallback, errorCallback, args);
    };

    $(function() {
      // Pull in the public encryption key for our environment
      <?php if($pay_data['sandbox_check'] == 1): ?>
        TCO.loadPubKey('sandbox');
      <?php else: ?>
        TCO.loadPubKey('production');
      <?php endif; ?>

      $(".checkoutform").submit(function(e) {
        // Call our token request function
        tokenRequest();
        // Prevent form from submitting
        return false;
      });
    });



  </script>

<?php endif; ?>

<?php if($payment == 'mercadopago'): ?>

  <input type="hidden" name="method" value="<?php echo e($gateway->name); ?>">

  <div class="row">

    <div class="col-lg-6">
      <input class="form-control" type="text" placeholder="<?php echo e(__('Credit Card Number')); ?>" id="cardNumber" data-checkout="cardNumber" onselectstart="return false" autocomplete=off required />
    </div>

    <div class="col-lg-6">
      <input class="form-control" type="text" id="securityCode" data-checkout="securityCode" placeholder="<?php echo e(__('Security Code')); ?>" onselectstart="return false" autocomplete=off required />
    </div>

    <div class="col-lg-6">
      <input class="form-control" type="text" id="cardExpirationMonth" data-checkout="cardExpirationMonth" placeholder="<?php echo e(__('Expiration Month')); ?>" autocomplete=off required />
    </div>

    <div class="col-lg-6">
    <input class="form-control" type="text" id="cardExpirationYear" data-checkout="cardExpirationYear" placeholder="<?php echo e(__('Expiration Year')); ?>" autocomplete=off required />
    </div>

    <div class="col-lg-6">
      <input class="form-control" type="text" id="cardholderName" data-checkout="cardholderName" placeholder="<?php echo e(__('Card Holder Name')); ?>" required />
    </div>

    <div class="col-lg-6">
      <div class="col-lg-12">
        <div class="row">

            <select class="form-control col-lg-9 pl-0" id="docType" data-checkout="docType" required>
            </select>
        </div>
      </div>
    </div>

    <div class="col-lg-6">
      <input class="form-control" type="text" id="docNumber" data-checkout="docNumber" placeholder="<?php echo e(__('Document Number')); ?>" required />
    </div>

  </div>


    <input type="hidden" id="installments" value="1"/>
    <input type="hidden" name="amount" id="amount"/>
    <input type="hidden" name="description"/>
    <input type="hidden" name="paymentMethodId" />


  <script>



      window.Mercadopago.setPublishableKey("<?php echo e($pay_data['public_key']); ?>");
      window.Mercadopago.getIdentificationTypes();

      function addEvent(to, type, fn){
        if(document.addEventListener){
            to.addEventListener(type, fn, false);
        } else if(document.attachEvent){
            to.attachEvent('on'+type, fn);
        } else {
            to['on'+type] = fn;
        }
    };

addEvent(document.querySelector('#cardNumber'), 'keyup', guessingPaymentMethod);
addEvent(document.querySelector('#cardNumber'), 'change', guessingPaymentMethod);

function getBin() {
    var ccNumber = document.querySelector('input[data-checkout="cardNumber"]');
    return ccNumber.value.replace(/[ .-]/g, '').slice(0, 6);
};

function guessingPaymentMethod(event) {
    var bin = getBin();

    if (event.type == "keyup") {
        if (bin.length >= 6) {
            window.Mercadopago.getPaymentMethod({
                "bin": bin
            }, setPaymentMethodInfo);
        }
    } else {
        setTimeout(function() {
            if (bin.length >= 6) {
                window.Mercadopago.getPaymentMethod({
                    "bin": bin
                }, setPaymentMethodInfo);
            }
        }, 100);
    }
};

function setPaymentMethodInfo(status, response) {
    if (status == 200) {
        const paymentMethodElement = document.querySelector('input[name=paymentMethodId]');

        if (paymentMethodElement) {
            paymentMethodElement.value = response[0].id;
        } else {
            const input = document.createElement('input');
            input.setAttribute('name', 'paymentMethodId');
            input.setAttribute('type', 'hidden');
            input.setAttribute('value', response[0].id);

            form.appendChild(input);
        }

        Mercadopago.getInstallments({
            "bin": getBin(),
            "amount": parseFloat(document.querySelector('#amount').value),
        }, setInstallmentInfo);

    } else {
        alert(`payment method info error: ${response}`);
    }
};


doSubmit = false;
addEvent(document.querySelector('#mercadopago'), 'submit', doPay);
function doPay(event){
    event.preventDefault();
    if(!doSubmit){
        var $form = document.querySelector('#mercadopago');

        window.Mercadopago.createToken($form, sdkResponseHandler); // The function "sdkResponseHandler" is defined below

        return false;
    }
};

function sdkResponseHandler(status, response) {
    if (status != 200 && status != 201) {
        alert("Some of your information is wrong!");
        $('#preloader').hide();

    }else{
        var form = document.querySelector('#mercadopago');
        var card = document.createElement('input');
        card.setAttribute('name', 'token');
        card.setAttribute('type', 'hidden');
        card.setAttribute('value', response.id);
        form.appendChild(card);
        doSubmit=true;
        form.submit();
    }
};


function setInstallmentInfo(status, response) {
        var selectorInstallments = document.querySelector("#installments"),
        fragment = document.createDocumentFragment();
        selectorInstallments.length = 0;

        if (response.length > 0) {
            var option = new Option("Escolha...", '-1'),
            payerCosts = response[0].payer_costs;
            fragment.appendChild(option);

            for (var i = 0; i < payerCosts.length; i++) {
                fragment.appendChild(new Option(payerCosts[i].recommended_message, payerCosts[i].installments));
            }

            selectorInstallments.appendChild(fragment);
            selectorInstallments.removeAttribute('disabled');
        }
    };



  </script>

<?php endif; ?>


<?php if($payment == 'other'): ?>

  <input type="hidden" name="method" value="<?php echo e($gateway->title); ?>">

  <div class="row" >

    <div class="col-lg-12 pb-2">

      <?php echo clean($gateway->details , array('Attr.EnableID' => true)); ?>


    </div>


    <div class="col-lg-6">
      <label><?php echo e(__('Transaction ID#')); ?> *</label>
      <input class="form-control" name="txnid" type="text" required="" placeholder="<?php echo e(__('Transaction ID#')); ?>"  />
    </div>

  </div>

<?php endif; ?>
<?php /**PATH C:\laragon\www\xmerch\project\resources\views\load\payment.blade.php ENDPATH**/ ?>