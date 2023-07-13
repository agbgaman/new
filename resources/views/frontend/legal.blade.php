@extends('frontend.layouts.app')

@section('content')
    <div class="homeslider owl-carousel owl-theme">
        <div class="item">
            <img src="{{asset('front/images/legal-banner.jpg')}}" alt="banner" />
            <div class="slide">
                <div class="col-md-6">

                    <div class="bcontent">
                        <div class="headings"><h2>Legal Policies</h2></div>
                        <p>At Globose Technology Solutions Private Limited, we are committed to maintaining the highest standards of ethical conduct and transparency. This page provides information about our global policies and practices.
                        </p>
                        <a href="#" class="morebtn border-white text-white">Get In Touch</a>
                        <a href="tel:+91 8107117527" class="morebtn bg-white ">+91 8107117527</a>
                        <a href="tel:+91 8107117527" class="morebtn bg-white ">+91 8107117527</a>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <section class="lightbg">
        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <div class="boxs bg-white p-4">
                        <h4>Privacy Statement</h4>
                        <p>We are dedicated to respecting your privacy. Our Privacy Statement informs you of our policy
                            and practices and of the choices you can make about the way your information is
                            collected.</p>
                        <a href="{{route('privacyStatement')}}">Learn more</a>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="boxs bg-white p-4">
                        <h4>Cookie Policy</h4>
                        <p>Our Cookie Policy informs you about our policy and practices with respect to our use of
                            cookies.</p>
                        <a href="{{route('cookiePolicy')}}">Learn more</a>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="boxs bg-white p-4">
                        <h4>Privacy Policy for Residents</h4>
                        <p>We have a specific Privacy Policy for our residents. This policy provides more details about
                            our global policies and statements.</p>
                        <a href="{{route('privacyPolicies')}}">Learn more</a>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="boxs bg-white p-4">
                        <h4>Global Ethical Sourcing & Modern Slavery Policy</h4>
                        <p>We are committed to ensuring respect for human rights and eradicating modern slavery. Our
                            Global Ethical Sourcing & Modern Slavery Policy provides more details about this
                            commitment.</p>
                        <a href="{{route('slaveryPolicy')}}">Learn more</a>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="boxs bg-white p-4">
                        <h4>Group Whistleblower / Speak Up Policy</h4>
                        <p>We encourage our employees and partners to speak up about any concerns related to proper
                            conduct. Our Group Whistleblower / Speak Up Policy explains how those concerns can be safely
                            reported.</p>
                        <a href="{{route('groupWhistleBlower')}}">Learn more</a>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="boxs bg-white p-4">
                        <h4>Anti-Corruption Policy</h4>
                        <p>At Globose Technology Solutions Private Limited, we hold ourselves to the highest standards
                            of ethical conduct. This includes a zero tolerance for bribery and corruption. Our
                            Anti-Corruption Policy provides more details about this commitment.</p>
                        <a href="{{route('antiCorruptionPolicy')}}">Learn more</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
