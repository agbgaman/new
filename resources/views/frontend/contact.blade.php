@extends('frontend.layouts.app')

@section('content')

    <div class="homeslider owl-carousel owl-theme">
        <div class="item">
            <img src="https://dash.gts.ai/front/images/contactbg.jpg" alt="banner"/>
            <div class="slide">
                <div class="col-lg-6 col-sm-9">

                    <div class="bcontent">
                        <div class="headings"><span>Get In Touch With Us</span> Contact Us</div>
                        <p>WELCOME TO THE GTS HELPDESK!</p>
                    </div>
                </div>
            </div>
        </div>

    </div>


    <section class="bg-white">
        <div class="container">
            <div class="contactsec lightbg">
                <div class="address" data-aos="fade-up" data-aos-duration="500" data-aos-offset="0"
                     data-aos-easing="ease-in-back">
                    <div>
                        <h3>Get In Touch</h3>
                        <p>Fill up the form and our Team will get back <br>to you within 24 hours.</p>
                    </div>

                    <div>
                        <p>
                            <a href="tel:+0123 4567 8910"> <i class="fa fa-phone"></i> +91-8824335106</a>
                            <a href="tel:+91-8824335106">WhatsApp <i class="fa fa-whatsapp"></i></a>
                        </p>
                    </div>

                    <div>
                        <p><a href="mailto:info@gts.ai"> <i class="fa fa-envelope"></i> info@gts.ai</a></p>
                    </div>

                    <div>
                        <p><i class="fa fa-map"></i> <span>Address:TC-321-325, R-Tech Capital Highstreet,
                        Phool Bagh, Bhiwadi, Alwar
                        (RJ.)- 301019</span></p>

                    </div>

                    <div class="sociallinks" data-aos="fade-up" data-aos-duration="800" data-aos-offset="0"
                         data-aos-easing="ease-in-back">
                        <ul class="d-flex">
                            <li><a href="https://www.facebook.com/GloboseTechnologySolutions/"><i class="fa fa-facebook"></i></a></li>
                            <li><a href="https://twitter.com/GTS_AI/"><i class="fa fa-twitter"></i></a></li>
                            <li><a href="https://www.instagram.com/gts_ai_data/"><i class="fa fa-instagram"></i></a></li>
                            <li><a href="https://www.linkedin.com/company/gtsaidata/"><i class="fa fa-linkedin"></i></a></li>
                            <li><a href="https://gts.ai/"><i class="fa fa-google"></i></a></li>
                            <li><a href="https://www.youtube.com/@gtsaidata7850"><i class="fa fa-youtube"></i></a></li>
                        </ul>
                    </div>

                </div>
                <div class="formsec bg-white p-4" data-aos="fade-up" data-aos-duration="1000" data-aos-offset="0"
                     data-aos-easing="ease-in-back">
                    <div class="form-group">
                        <label>Your Name</label>
                        <input type="text" name="" class=""/>
                    </div>
                    <div class="form-group">
                        <label>Mail</label>
                        <input type="email" name="" class=""/>
                    </div>
                    <div class="form-group">
                        <label>Message</label>
                        <textarea placeholder="Message"></textarea>
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn bluebtn">Send Message</button>
                    </div>
                </div>


            </div>
        </div>
    </section>

@endsection
