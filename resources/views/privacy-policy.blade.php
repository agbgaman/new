@extends('layouts.guest')

@section('content')
    <div class="container">
        <div class="row text-center">
            <div class="col-md-12">
                <div class="section-title">
                    <!-- SECTION TITLE -->
                    <div class="text-center mb-9 mt-9 pt-5" id="contact-row">

                        <div class="title">
                            <h6><span>{{ __('Privacy Policy') }}</span></h6>
                        </div>

                    </div> <!-- END SECTION TITLE -->
                </div>
            </div>
        </div>
    </div>

    <section id="about-wrapper">

        <div class="container">
            <div class="row justify-content-center background-white">
                <div class="col-md-10 col-sm-12 policy">                
                    <div class="card-body pt-9 pb-9"> 

                        <div class="mb-7">
                            {!! $pages['privacy'] !!}
                        </div>
                        <h3 class="">Consent Form&nbsp;:</h3>
    <p class="">
          You specifically consent and agree that we may provide all disclosures, agreements, contracts,
          periodic statements, receipts, modifications, amendments and
          all other evidence of our transactions with you on your behalf
          electronically (hereinafter all such documentation is referred to as "electronic record(s)").
          We may provide you any or all electronic records at the e-mail address provided on our registration
          form or we may post any or all electronic records at our website connect.dash.com.
          If we post electronic records on our website, they will be made available only to you or employees
          of Global Technology Solutions. We reserve the right to send any or all records to you in paper form to your current
          postal mailing address in our file.
    </p>
    <p>
    You have a right to receive a paper copy of any of these electronic records if applicable law specifically requires us to provide such documentation. You may withdraw your consent and revoke your agreement to receive records electronically. To request a paper copy or to withdraw your consent and agreement to receive records, write us at Level 6, 9 Help Street, Chatswood, NSW Australia 2067 . A fee to cancel this service or to request paper copies of these electronic records may be imposed. Any inconsistencies between the Electronic Records Consent Form (hereinafter "Consent Form") and any other agreements and disclosures applicable to any account you have with us shall be controlled by the terms and conditions of this Consent Form. Except to the extent that the terms and conditions of this Consent Form conflict with the agreements and disclosures applicable to your accounts with us shall remain in full force and effect. This Consent Form, including the validity of any signatures or consents, any claims or any disputes arising hereunder shall be construed in accordance with and governed by the Laws of the State of California.
    </p>
    <h3 class="">Equipment and Software Requirements &nbsp;:</h3>
    <p class="">
    To receive electronic records and to access our website, you need to a computer with 1) access to connect.dash.com's website, 2) an active email account, 3) a PC or Mac with access to the Internet, 4) Google Chrome 31 or higher (free software you can download) and 5) Adobe Acrobat Reader 9.0 or higher (free software you can download). By requesting any electronic services or transactions, by submitting any application or agreement to us electronically or by e-mailing us, you represent that you have such equipment and software and that you can download, access, read, review, print and store the electronic records we provide to you. If we change hardware or software requirements which will materially affect your ability to access electronic records, we will notify you.
    </p>

    <h3 class="">Electronic Signature &nbsp;:</h3>
    <p class="">
    By clicking "I Agree" below, you consent and agree to the terms and conditions in this Consent Form and your login credentials constitutes your electronic signature and acknowledgment that you have the requisite hardware and software necessary to access electronic records provided by us. Further, you agree that no certification authority or other third party verification will in any way affect the enforceability of your signature or any resulting contract between you and Global Technology Solutions.
    </p>

    <h3 class="">E-mail Communication &nbsp;:</h3>
    <p class="">
    You acknowledge and agree that the Internet is considered inherently insecure. Therefore, you agree that we have no liability to you whatsoever for any loss, claim or damages arising or in any way related to our response(s) to any e-mail or other electronic communication which we in good faith believe you have submitted to us. We have no duty to investigate the validity or to verify any e-mail or other electronic communication. We may respond to an e-mail communication provided by you to either the address provided with the communication or the e-mail address provided on our registration form.
    </p>
    <p class="">
    Any e-mail returned to us undelivered may be resent to you at any other e-mail address that we have in your file, unless you have previously informed us through electronic or written notice that an e-mail address is no longer valid.
    </p>   <p class="">
    Although we have no obligation to do so, we reserve the right to require authentication of e-mails or electronic communications. The decision to require authentication is in the sole discretion of Global Technology Solutions. We will have no obligation, liability or responsibility to you or any other person or company if we do not act upon or follow any instruction to us if a communication cannot be authenticated to our satisfaction.
    </p>

                        <div class="form-group mt-6 text-center">                        
                            <a href="{{ route('register') }}" class="btn btn-primary mr-2">{{ __("I Agree, Let's Sign Up") }}</a> 
                            <a href="{{ route('login') }}" class="btn btn-primary mr-2">{{ __("I Agree, Let's Login") }}</a>                               
                        </div>
                    
                    </div>       
                </div>
            </div>
        </div>
    </section>
    @section('js')
        <script src="{{URL::asset('js/minimize.js')}}"></script>
    @endsection
@endsection

