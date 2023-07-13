@extends('frontend.layouts.app')

@section('content')
    <div class="homeslider owl-carousel owl-theme">
        <div class="item">
            <img src="https://dash.gts.ai/front/images/legal-banner.jpg" alt="banner"/>
            <div class="slide">
                <div class="col-lg-6 col-sm-9">

                    <div class="bcontent">
                        <div class="headings">Customer FAQ – Frequently Asked Questions</div>
                        <p>Still have questions? Before contacting us you might want to look through the questions and
                            answers in this section.</p>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <section class="lightbg faqbg">
        <div class="container">
            <div class="faqsec">
                <div class="leftlink">
                    <h3>FAQ FOR CUSTOMER</h3>
                    <ul>
                        <li><a href="#">REGISTRATION AND LOGIN</a></li>
                        <li><a href="#">ACCOUNT</a></li>
                        <li><a href="#">JOBS</a></li>
                    </ul>
                </div>

                <div class="faqlist">
                    <div class="faqbox">
                        <h2>REGISTRATION AND LOGIN</h2>
                        <ul>
                            <li><a href="#">How do I register?</a>
                                <p>There are two ways to sign up with DASH: via the DASH app or on the website.</p>
                            </li>
                            <li><a href="#">Can I work for DASH only from the US and Germany?</a>
                                <p>Freelancers can work from many different countries, although registration for certain countries may be temporarily disabled.</p>
                            </li>
                            <li><a href="#">Can I work for DASH while I am away from my home country?</a>
                                <p>Yes, but logging in from a country other than your registered home country may flag your account as suspicious.</p>
                            </li>
                            <li><a href="#">What should I do if my account has been suspended immediately after I registered?</a>
                                <p>New accounts may be temporarily suspended as part of a routine check. If this happens, don't panic. More details on how to handle this situation can be found on the DASH support page.</p>
                            </li>
                            <li><a href="#">Can I view DASH’s general business terms before I register?</a>
                                <p>Yes, the general business terms and the data protection terms can be viewed on the DASH website.</p>
                            </li>
                            <li><a href="#">What does "Registration for your home country is not available at the moment" mean?</a>
                                <p>This message means that you cannot register with DASH for the time being due to temporary registration restrictions for certain countries.</p>
                            </li>
                            <li><a href="#">How do I get my username and password?</a>
                                <p>You can choose an individual username and password during the registration process.</p>
                            </li>
                            <li><a href="#">What should I do if I haven't received my activation email?</a>
                                <p>If you haven't received your activation email, please check your spam folder or contact DASH support for further assistance.</p>
                            </li>
                            <li><a href="#">Which URL can I use to sign in to DASH?</a>
                                <p>You can either use a direct link to the DASH Workplace or sign in via the login area at the upper right corner of the DASH website.</p>
                            </li>
                            <li><a href="#">Can I reactivate my account if I've canceled it?</a>
                                <p>Accounts can only be reactivated within 40 days after deactivation. After this period, the personal data associated with the account is permanently deleted.</p>
                            </li>
                        </ul>


                        <h3>Questions regarding individual orders</h3>
                    </div>


                    <div class="faqlist">
                        <div class="faqbox">
                            <h2>Account</h2>
                            <ul>
                                <li>
                                    <a href="#">How can I add or change languages in my profile?</a>
                                    <p>Language adjustments can only be made by the DASH support team.</p>
                                </li>
                                <li>
                                    <a href="#">How can I cancel or delete my DASH account?</a>
                                    <p>To delete your account, log into the DASH workplace in your browser, click on the "user settings" link, and follow the instructions provided.</p>
                                </li>
                                <li>
                                    <a href="#">What if my native language is not available for selection?</a>
                                    <p>DASH tries to include as many languages as possible. However, if your native language is not available, contact DASH support for assistance.</p>
                                </li>
                                <li>
                                    <a href="#">What should I do if I forgot or lost my DASH password?</a>
                                    <p>If you have forgotten or lost your password, use the "Forgot Password" routine to easily set a new one.</p>
                                </li>
                                <li>
                                    <a href="#">Can I change my email address and my username?</a>
                                    <p>Yes, these changes can only be made by DASH support staff. Contact them via a new support ticket to request these changes.</p>
                                </li>
                                <li>
                                    <a href="#">How can I change my date of birth in my user profile?</a>
                                    <p>If you need to change your date of birth in your profile, contact DASH via the contact form.</p>
                                </li>
                                <li>
                                    <a href="#">What if I can't add a picture to my profile?</a>
                                    <p>Make sure the file size of the picture does not exceed the maximum limit and that it is in the correct format (JPEG, PNG, etc.). If you still encounter issues, contact DASH support for assistance.</p>
                                </li>
                                <li>
                                    <a href="#">Why does my profile not show a 100% completion rate?</a>
                                    <p>It is not mandatory to fill in all profile information. Your account will work fine even if you only provide your language skills. However, if you want to improve your chances of getting selected for more jobs, it's recommended to provide as much information as possible.</p>
                                </li>
                                <li>
                                    <a href="#">I referred a friend to DASH but did not receive my bonus. What should I do?</a>
                                    <p>You can see how many people you have referred under the "Recruit Clickworkers" menu. You will receive your bonus once your friend has been paid a certain amount.</p>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <div class="faqbox">
                        <h2>Jobs</h2>
                        <ul>
                            <li>
                                <a href="#">What types of jobs are available on DASH?</a>
                                <p>DASH offers a variety of freelance jobs across different industries and fields. The available jobs can vary depending on the client's requirements and project needs.</p>
                            </li>
                            <li>
                                <a href="#">How can I find and apply for jobs on DASH?</a>
                                <p>After logging in to your DASH account, you can browse the available jobs on the platform and apply to the ones that match your skills and interests. DASH provides search and filtering options to help you find relevant jobs.</p>
                            </li>
                            <li>
                                <a href="#">How much can I earn from jobs on DASH?</a>
                                <p>The earnings from jobs on DASH can vary depending on factors such as the type of project, complexity, and your skills and experience. Each job listing provides details about the payment structure and rates.</p>
                            </li>
                            <li>
                                <a href="#">How do I get paid for the work I complete on DASH?</a>
                                <p>DASH pays freelancers in accordance with their payment schedule, which can vary. You may need to set up your payment details and reach a minimum payable amount before receiving payment.</p>
                            </li>
                        </ul>
                    </div>

                    <div class="faqbox">

                        <p>Invoices are issued at the end of the month for the results rendered until then, and will be sent to you by mail or email.</p>
                        <h3>Questions regarding the Self-Service Marketplace</h3>
                        <p>Information around the use of our Self-Service Marketplace, as well as the possibility to contact our marketplace customer support, is available at our</p>
                        <a href="" class="btn bluebtn">Customer Support Desk</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
