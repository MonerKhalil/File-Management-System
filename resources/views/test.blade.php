<!DOCTYPE html>
<html lang="ar" dir="rtl">

        <head>
            @include("layouts.head.meta")
            @include('layouts.head.stylePackages')
            <link rel="stylesheet" href="{{ asset("System/assets/css/style-email.css") }}">
        </head>

        <body>
        <!-- Start Loader Page -->
        @include("components.loader.preLoader")
        <!-- End Loader Page -->

        <!-- Start Loader Page -->
        @include("components.loader.formLoader")
        <!-- End Loader Page -->

        <div id="Wrapper">
            <div class="fetch-data-language"
                 data-file-lang="Main"
                 data-file-primary="true"
                 data-file-content="{{ getLangFile("Main") }}"
            ></div>









            <div class="nk-content ">
                <div class="container-fluid">
                    <div class="nk-content-inner">
                        <div class="nk-content-body">
                            <div class="content-page wide-md m-auto">
                                <div class="nk-block-head nk-block-head-lg wide-sm">
                                    <div class="nk-block-head-content">
                                        <h2 class="nk-block-title fw-normal">Email Templates</h2>
                                        <div class="nk-block-des">
                                            <p class="lead">In DashLite, We included 10 clean and minimalist notification email templates that ready to use for your application to send emails. <strong class="text-primary">Stand-Alone html files found in packages</strong>.</p>
                                        </div>
                                    </div>
                                </div><!-- .nk-block-head -->



                                <!-- Tokens -->
                                <div class="nk-block">
                                    <div class="card">
                                        <div class="card-inner">
                                            <h4 class="title text-soft mb-4 overline-title">Centered Style - Welcome Type Email Template</h4>
                                            <table class="email-wraper">
                                                <tr>
                                                    <td class="py-5">
                                                        <table class="email-body text-center">
                                                            <tbody>
                                                                <tr>
                                                                    <td class="p-3 p-sm-5">
                                                                        <h5 class="email-heading email-heading-s1 mb-4">
                                                                            My Dear {user}
                                                                        </h5>
                                                                        <p class="fs-16px text-base">
                                                                            We Put Token Under Below Just Copy this And Paster In Field Application
                                                                        </p>
                                                                        <p>
                                                                            Token : {token}
                                                                        </p>
                                                                    </td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                </div><!-- .nk-block -->



                                <!-- Success Send -->
                                <div class="nk-block">
                                    <div class="card">
                                        <div class="card-inner">
                                            <h4 class="title text-soft mb-4 overline-title">Notification - KYC Approved Template</h4>
                                            <table class="email-wraper">
                                                <tr>
                                                    <td class="py-5">
                                                        <table class="email-body text-center">
                                                            <tbody>
                                                                <tr>
                                                                    <td class="px-3 px-sm-5 pt-3 pt-sm-5 pb-4">
                                                                        <img class="w-100px" src="./images/email/kyc-success.png"
                                                                             alt="Verified">
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td class="px-3 px-sm-5 pb-3 pb-sm-5">
                                                                        <h5 class="text-success mb-3">
                                                                            You Send Message Done
                                                                        </h5>
                                                                        <p>
                                                                            We have successfully sent a request and will respond as soon as possible .
                                                                        </p>
                                                                    </td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                </div><!-- .nk-block -->






                                <!-- View Request -->
                                <div class="nk-block">
                                    <div class="card">
                                        <div class="card-inner">
                                            <h4 class="title text-soft mb-4 overline-title">Simple News - Basic Newsletter Template</h4>
                                            <table class="email-wraper">
                                                <tr>
                                                    <td class="py-5">
                                                        <table class="email-body">
                                                            <tbody>
                                                                <tr>
                                                                    <td class="text-center px-3 px-sm-5 p-4">
                                                                        <img src="{iconImage}" alt="{alterIconImage}">
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td class="px-sm-5 pb-3">
                                                                        <p>
                                                                            The message was sent by the user called Amir, which includes the following content :
                                                                        </p>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td class="px-sm-5 pb-3">
                                                                        <table class="w-100">
                                                                            <tbody>
                                                                                <tr>
                                                                                    <td class="w-100">
                                                                                        <ul class="email-ul">
                                                                                            {questionList}
                                                                                        </ul>
                                                                                    </td>
                                                                                </tr>
                                                                            </tbody>
                                                                        </table>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td class="px-sm-5 pb-3 text-center">
                                                                        <a href="#" class="email-btn email-btn-sm mt-2">Read More</a>
                                                                    </td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                </div><!-- .nk-block -->











                                <!-- Success Send -->
                                <div class="nk-block">
                                    <div class="card">
                                        <div class="card-inner">
                                            <h4 class="title text-soft mb-4 overline-title">Notification - KYC Approved Template</h4>
                                            <table class="email-wraper">
                                                <tr>
                                                    <td class="py-5">
                                                        <table class="email-body text-center">
                                                            <tbody>
                                                                <tr>
                                                                    <td class="px-3 px-sm-5 pt-3 pt-sm-5 pb-4">
                                                                        <img class="w-100px" src="{imageProcess}"
                                                                             alt="Verified">
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td class="px-3 px-sm-5 pb-3 pb-sm-5">
                                                                        <h5 class="{colorText} mb-3">
                                                                            {titleProcess}
                                                                        </h5>
                                                                        <p>
                                                                            {bodyProcess}
                                                                        </p>
                                                                    </td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                </div><!-- .nk-block -->






                            </div><!-- .content-page -->
                        </div>
                    </div>
                </div>
            </div>














        </div>
        {{-- Scripts --}}
        @include("layouts.footers.footer-scripts")
        </body>
</html>
