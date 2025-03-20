@extends('frontend.index')

@section('content')
    <div class="carousel-container hero">
        <div class="container-fluid max-width px-md-0 px-sm-0 px-xs-0 slider">
            <div class="d-xs-block d-sm-none">
                <ul class="carousel row px-0"></ul>
            </div>
            <div class="d-none d-sm-block carousel-desktop">
                <ul class="carousel row px-0">
                    @foreach($banners as $banner)
                        <li class="col-12 carousel-item px-0">
                            <a href="{{ $banner->link }}">
                                <img class="hot-link" src="{{ $banner->image }}" width="1230" height="260"/>
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
    <section class="main-content content-section homepage-section">
        <div class="container">
            <div class="row">
                <div class="article">
                    <div class="col-md-9 col-sm-12 col-xs-12">
                        <!-- START Item Blocks -->
                        <div class="item-bolcks homepage-blocks">
                            <coupon-card
                                    :coupon="{&quot;url&quot;:&quot;\/go\/3586141&quot;,&quot;modal_url&quot;:&quot;\/stores\/samsung-promo-codes\/?_c=3586141&quot;}"
                                    :position="1" inline-template>
                                <div dusk="coupon-card" @click="openCouponPage('card')" class="tw-cursor-auto">
                                    <!--  xl:tw-h-full -->
                                    <div class="tw-rounded-sm tw-shadow tw-bg-white sm:tw-p-4 tw-p-2 sm:tw-mb-4 tw-mb-2 xl:tw-h-full">
                                        <div class="tw-flex lato tw-h-full">
                                            <div @click.stop
                                                 class="tw-text-center xl:tw-w-1/6 tw-w-1/4 xl:tw-min-w-1/6 tw-min-w-1/4">
                                                <a href="https://couponcause.com/stores/samsung-promo-codes/"
                                                   title="Samsung Coupons and Promo Codes"
                                                   class="card-logo tw-flex tw-justify-center tw-items-center tw-h-full">
                                                    <img class="sized-img"
                                                         src="https://cdn.couponcause.com/logos/180x110/samsung-promo-codes-180x110-1518438575.webp"
                                                         alt="Samsung Coupons and Promo Codes" width="180" height="110"
                                                         loading="lazy">
                                                </a>
                                            </div>
                                            <div class=" tw-flex tw-leading-tight tw-w-5/6 sm:tw-ml-6 tw-ml-4">
                                                <div class="tw-flex-grow tw-pr-4 coupon-card-inner-container">
                                                    <a id="testingId3" dusk="coupon-card-label" href="/go/3586141"
                                                       onclick="gtag_report_conversion('/go/3586141')"
                                                       @click.stop.prevent="openCouponPage('label')"
                                                       class="tw-text-grey-darker sm:tw-text-xl tw-text-lg tw-font-medium hover:tw-text-blue lato"
                                                       rel="nofollow">Save Up to $1300 on The Frame TV
                                                    </a>
                                                    <div class="sm:tw-leading-loose tw-leading-normal">
                                                        <p class="tw-text-grey sm:tw-text-base tw-text-xs lato">Expires
                                                            Mar 17</p>
                                                        <span class="tw-text-green sm:tw-border sm:tw-border-green sm:tw-text-green sm:tw-text-base tw-text-xs lato sm:tw-rounded sm:tw-px-2 sm:tw-py-1">Verified</span>
                                                        <span class="tw-text-orange  sm:tw-border sm:tw-border-orange  sm:tw-text-orange  sm:tw-text-base tw-text-xs lato sm:tw-rounded sm:tw-px-2 sm:tw-py-1">Featured</span>
                                                        <div @click.stop="toggleCouponDetails()"
                                                             class="tw-relative tw-hidden lg:tw-block tw-text-grey-darker tw-font-light tw-cursor-pointer">
                                                            <span>Details: <i
                                                                        :class="[showDetails ? 'fa-caret-down' : 'fa-caret-right', 'fa']"></i></span>
                                                            <p ref="details" v-if="showDetails"
                                                               class="tw-absolute tw-border tw-rounded-sm tw-bg-white tw-text-sm tw-py-2 tw-px-4 tw-w-64 tw-z-20"
                                                               v-cloak>No Promo Code Needed. Click &quot;Get Offer&quot;
                                                                To Activate This Deal. Exclusions May Apply</p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div @click.stop="openCouponPage('cta')"
                                                     onclick="gtag_report_conversion('/go/3586141')"
                                                     class="tw-hidden md:tw-flex tw-items-start tw-whitespace-no-wrap tw-min-w-1/4">
                                                    <div class="tw-relative tw-rounded-sm tw-overflow-hidden tw-cursor-pointer tw-w-full lato"
                                                         rel="nofollow">
                                                        <div class="tw-bg-blue hover:tw-bg-blue-dark tw-text-center tw-text-white tw-py-2 tw-px-4">
                                                            Get Offer
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </coupon-card>
                            <coupon-card
                                    :coupon="{&quot;url&quot;:&quot;\/go\/3054432&quot;,&quot;modal_url&quot;:&quot;\/stores\/trip-coupon\/?_c=3054432&quot;}"
                                    :position="2" inline-template>
                                <div dusk="coupon-card" @click="openCouponPage('card')" class="tw-cursor-auto">
                                    <!--  xl:tw-h-full -->
                                    <div class="tw-rounded-sm tw-shadow tw-bg-white sm:tw-p-4 tw-p-2 sm:tw-mb-4 tw-mb-2 xl:tw-h-full">
                                        <div class="tw-flex lato tw-h-full">
                                            <div @click.stop
                                                 class="tw-text-center xl:tw-w-1/6 tw-w-1/4 xl:tw-min-w-1/6 tw-min-w-1/4">
                                                <a href="https://couponcause.com/stores/trip-coupon/"
                                                   title="Trip.com Coupons and Promo Codes"
                                                   class="card-logo tw-flex tw-justify-center tw-items-center tw-h-full">
                                                    <img class="sized-img"
                                                         src="https://cdn.couponcause.com/logos/180x110/trip-coupon-180x110-1688582518.webp"
                                                         alt="Trip.com Coupons and Promo Codes" width="180" height="110"
                                                         loading="lazy">
                                                </a>
                                            </div>
                                            <div class=" tw-flex tw-leading-tight tw-w-5/6 sm:tw-ml-6 tw-ml-4">
                                                <div class="tw-flex-grow tw-pr-4 coupon-card-inner-container">
                                                    <a id="testingId3" dusk="coupon-card-label" href="/go/3054432"
                                                       onclick="gtag_report_conversion('/go/3054432')"
                                                       @click.stop.prevent="openCouponPage('label')"
                                                       class="tw-text-grey-darker sm:tw-text-xl tw-text-lg tw-font-medium hover:tw-text-blue lato"
                                                       rel="nofollow">Trip.com Rewards: Up to 20% Off Hotels + 40% More
                                                        Trip Coins with Free Signup
                                                    </a>
                                                    <div class="sm:tw-leading-loose tw-leading-normal">
                                                        <p class="tw-text-grey sm:tw-text-base tw-text-xs lato">Ongoing
                                                            Offer</p>
                                                        <span class="tw-text-green sm:tw-border sm:tw-border-green sm:tw-text-green sm:tw-text-base tw-text-xs lato sm:tw-rounded sm:tw-px-2 sm:tw-py-1">Verified</span>
                                                        <div @click.stop="toggleCouponDetails()"
                                                             class="tw-relative tw-hidden lg:tw-block tw-text-grey-darker tw-font-light tw-cursor-pointer">
                                                            <span>Details: <i
                                                                        :class="[showDetails ? 'fa-caret-down' : 'fa-caret-right', 'fa']"></i></span>
                                                            <p ref="details" v-if="showDetails"
                                                               class="tw-absolute tw-border tw-rounded-sm tw-bg-white tw-text-sm tw-py-2 tw-px-4 tw-w-64 tw-z-20"
                                                               v-cloak>No Promo Code Needed. Click &quot;Get Offer&quot;
                                                                To Activate This Deal. Exclusions May Apply</p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div @click.stop="openCouponPage('cta')"
                                                     onclick="gtag_report_conversion('/go/3054432')"
                                                     class="tw-hidden md:tw-flex tw-items-start tw-whitespace-no-wrap tw-min-w-1/4">
                                                    <div class="tw-relative tw-rounded-sm tw-overflow-hidden tw-cursor-pointer tw-w-full lato"
                                                         rel="nofollow">
                                                        <div class="tw-bg-blue hover:tw-bg-blue-dark tw-text-center tw-text-white tw-py-2 tw-px-4">
                                                            Get Offer
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </coupon-card>
                            <coupon-card
                                    :coupon="{&quot;url&quot;:&quot;\/go\/1166893&quot;,&quot;modal_url&quot;:&quot;\/stores\/udemy-coupon-codes\/?_c=1166893&quot;}"
                                    :position="3" inline-template>
                                <div dusk="coupon-card" @click="openCouponPage('card')" class="tw-cursor-auto">
                                    <!--  xl:tw-h-full -->
                                    <div class="tw-rounded-sm tw-shadow tw-bg-white sm:tw-p-4 tw-p-2 sm:tw-mb-4 tw-mb-2 xl:tw-h-full">
                                        <div class="tw-flex lato tw-h-full">
                                            <div @click.stop
                                                 class="tw-text-center xl:tw-w-1/6 tw-w-1/4 xl:tw-min-w-1/6 tw-min-w-1/4">
                                                <a href="https://couponcause.com/stores/udemy-coupon-codes/"
                                                   title="Udemy Coupons and Promo Codes"
                                                   class="card-logo tw-flex tw-justify-center tw-items-center tw-h-full">
                                                    <img class="sized-img"
                                                         src="https://cdn.couponcause.com/logos/180x110/udemy-coupon-codes-180x110-1626287919.webp"
                                                         alt="Udemy Coupons and Promo Codes" width="180" height="110"
                                                         loading="lazy">
                                                </a>
                                            </div>
                                            <div class=" tw-flex tw-leading-tight tw-w-5/6 sm:tw-ml-6 tw-ml-4">
                                                <div class="tw-flex-grow tw-pr-4 coupon-card-inner-container">
                                                    <a id="testingId3" dusk="coupon-card-label" href="/go/1166893"
                                                       onclick="gtag_report_conversion('/go/1166893')"
                                                       @click.stop.prevent="openCouponPage('label')"
                                                       class="tw-text-grey-darker sm:tw-text-xl tw-text-lg tw-font-medium hover:tw-text-blue lato"
                                                       rel="nofollow">Up To 60% Off - Courses Start At $14.99
                                                    </a>
                                                    <div class="sm:tw-leading-loose tw-leading-normal">
                                                        <p class="tw-text-grey sm:tw-text-base tw-text-xs lato">Ongoing
                                                            Offer</p>
                                                        <span class="tw-text-green sm:tw-border sm:tw-border-green sm:tw-text-green sm:tw-text-base tw-text-xs lato sm:tw-rounded sm:tw-px-2 sm:tw-py-1">Verified</span>
                                                        <span class="tw-text-orange  sm:tw-border sm:tw-border-orange  sm:tw-text-orange  sm:tw-text-base tw-text-xs lato sm:tw-rounded sm:tw-px-2 sm:tw-py-1">Featured</span>
                                                        <div @click.stop="toggleCouponDetails()"
                                                             class="tw-relative tw-hidden lg:tw-block tw-text-grey-darker tw-font-light tw-cursor-pointer">
                                                            <span>Details: <i
                                                                        :class="[showDetails ? 'fa-caret-down' : 'fa-caret-right', 'fa']"></i></span>
                                                            <p ref="details" v-if="showDetails"
                                                               class="tw-absolute tw-border tw-rounded-sm tw-bg-white tw-text-sm tw-py-2 tw-px-4 tw-w-64 tw-z-20"
                                                               v-cloak>Click &quot;Show Coupon Code&quot; To Activate
                                                                This Deal. Exclusions May Apply</p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div @click.stop="openCouponPage('cta')"
                                                     onclick="gtag_report_conversion('/go/1166893')"
                                                     class="tw-hidden md:tw-flex tw-items-start tw-whitespace-no-wrap tw-min-w-1/4">
                                                    <div class="tw-relative tw-rounded-sm tw-overflow-hidden tw-cursor-pointer tw-w-full lato"
                                                         rel="nofollow">
                                                        <div class="tw-bg-grey-lighter tw-border tw-text-grey-dark tw-text-right tw-w-full tw-p-2 ">
                                                            OFFER APPLIED
                                                        </div>
                                                        <div class="tw-absolute tw-pin-t tw-pin-l tw-bg-blue hover:tw-bg-blue-dark tw-border tw-border-blue tw-text-center tw-text-white tw-min-w-5/6 tw-p-2">
                                                            Show Code
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </coupon-card>
                            <coupon-card
                                    :coupon="{&quot;url&quot;:&quot;\/go\/1202633&quot;,&quot;modal_url&quot;:&quot;\/stores\/cettire-promo-codes\/?_c=1202633&quot;}"
                                    :position="4" inline-template>
                                <div dusk="coupon-card" @click="openCouponPage('card')" class="tw-cursor-auto">
                                    <!--  xl:tw-h-full -->
                                    <div class="tw-rounded-sm tw-shadow tw-bg-white sm:tw-p-4 tw-p-2 sm:tw-mb-4 tw-mb-2 xl:tw-h-full">
                                        <div class="tw-flex lato tw-h-full">
                                            <div @click.stop
                                                 class="tw-text-center xl:tw-w-1/6 tw-w-1/4 xl:tw-min-w-1/6 tw-min-w-1/4">
                                                <a href="https://couponcause.com/stores/cettire-promo-codes/"
                                                   title="Cettire Coupons and Promo Codes"
                                                   class="card-logo tw-flex tw-justify-center tw-items-center tw-h-full">
                                                    <img class="sized-img"
                                                         src="https://cdn.couponcause.com/logos/180x110/cettire-promo-codes-180x110-1537451578.webp"
                                                         alt="Cettire Coupons and Promo Codes" width="180" height="110"
                                                         loading="lazy">
                                                </a>
                                            </div>
                                            <div class=" tw-flex tw-leading-tight tw-w-5/6 sm:tw-ml-6 tw-ml-4">
                                                <div class="tw-flex-grow tw-pr-4 coupon-card-inner-container">
                                                    <a id="testingId3" dusk="coupon-card-label" href="/go/1202633"
                                                       onclick="gtag_report_conversion('/go/1202633')"
                                                       @click.stop.prevent="openCouponPage('label')"
                                                       class="tw-text-grey-darker sm:tw-text-xl tw-text-lg tw-font-medium hover:tw-text-blue lato"
                                                       rel="nofollow">Up To 50% Off New Season
                                                    </a>
                                                    <div class="sm:tw-leading-loose tw-leading-normal">
                                                        <p class="tw-text-grey sm:tw-text-base tw-text-xs lato">Ongoing
                                                            Offer</p>
                                                        <div @click.stop="toggleCouponDetails()"
                                                             class="tw-relative tw-hidden lg:tw-block tw-text-grey-darker tw-font-light tw-cursor-pointer">
                                                            <span>Details: <i
                                                                        :class="[showDetails ? 'fa-caret-down' : 'fa-caret-right', 'fa']"></i></span>
                                                            <p ref="details" v-if="showDetails"
                                                               class="tw-absolute tw-border tw-rounded-sm tw-bg-white tw-text-sm tw-py-2 tw-px-4 tw-w-64 tw-z-20"
                                                               v-cloak>No Promo Code Needed. Click &quot;Get Offer&quot;
                                                                To Activate This Deal. Exclusions May Apply</p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div @click.stop="openCouponPage('cta')"
                                                     onclick="gtag_report_conversion('/go/1202633')"
                                                     class="tw-hidden md:tw-flex tw-items-start tw-whitespace-no-wrap tw-min-w-1/4">
                                                    <div class="tw-relative tw-rounded-sm tw-overflow-hidden tw-cursor-pointer tw-w-full lato"
                                                         rel="nofollow">
                                                        <div class="tw-bg-blue hover:tw-bg-blue-dark tw-text-center tw-text-white tw-py-2 tw-px-4">
                                                            Get Offer
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </coupon-card>
                            <coupon-card
                                    :coupon="{&quot;url&quot;:&quot;\/go\/1156608&quot;,&quot;modal_url&quot;:&quot;\/stores\/target\/?_c=1156608&quot;}"
                                    :position="5" inline-template>
                                <div dusk="coupon-card" @click="openCouponPage('card')" class="tw-cursor-auto">
                                    <!--  xl:tw-h-full -->
                                    <div class="tw-rounded-sm tw-shadow tw-bg-white sm:tw-p-4 tw-p-2 sm:tw-mb-4 tw-mb-2 xl:tw-h-full">
                                        <div class="tw-flex lato tw-h-full">
                                            <div @click.stop
                                                 class="tw-text-center xl:tw-w-1/6 tw-w-1/4 xl:tw-min-w-1/6 tw-min-w-1/4">
                                                <a href="https://couponcause.com/stores/target/"
                                                   title="Target Coupons and Promo Codes"
                                                   class="card-logo tw-flex tw-justify-center tw-items-center tw-h-full">
                                                    <img class="sized-img"
                                                         src="https://cdn.couponcause.com/logos/180x110/target-180x110-1513265739.webp"
                                                         alt="Target Coupons and Promo Codes" width="180" height="110"
                                                         loading="lazy">
                                                </a>
                                            </div>
                                            <div class=" tw-flex tw-leading-tight tw-w-5/6 sm:tw-ml-6 tw-ml-4">
                                                <div class="tw-flex-grow tw-pr-4 coupon-card-inner-container">
                                                    <a id="testingId3" dusk="coupon-card-label" href="/go/1156608"
                                                       onclick="gtag_report_conversion('/go/1156608')"
                                                       @click.stop.prevent="openCouponPage('label')"
                                                       class="tw-text-grey-darker sm:tw-text-xl tw-text-lg tw-font-medium hover:tw-text-blue lato"
                                                       rel="nofollow">Up to 30% Off Home Items
                                                    </a>
                                                    <div class="sm:tw-leading-loose tw-leading-normal">
                                                        <p class="tw-text-grey sm:tw-text-base tw-text-xs lato">Ongoing
                                                            Offer</p>
                                                        <div @click.stop="toggleCouponDetails()"
                                                             class="tw-relative tw-hidden lg:tw-block tw-text-grey-darker tw-font-light tw-cursor-pointer">
                                                            <span>Details: <i
                                                                        :class="[showDetails ? 'fa-caret-down' : 'fa-caret-right', 'fa']"></i></span>
                                                            <p ref="details" v-if="showDetails"
                                                               class="tw-absolute tw-border tw-rounded-sm tw-bg-white tw-text-sm tw-py-2 tw-px-4 tw-w-64 tw-z-20"
                                                               v-cloak>No Promo Code Needed. Click &quot;Get Offer&quot;
                                                                To Activate This Deal. Exclusions May Apply</p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div @click.stop="openCouponPage('cta')"
                                                     onclick="gtag_report_conversion('/go/1156608')"
                                                     class="tw-hidden md:tw-flex tw-items-start tw-whitespace-no-wrap tw-min-w-1/4">
                                                    <div class="tw-relative tw-rounded-sm tw-overflow-hidden tw-cursor-pointer tw-w-full lato"
                                                         rel="nofollow">
                                                        <div class="tw-bg-blue hover:tw-bg-blue-dark tw-text-center tw-text-white tw-py-2 tw-px-4">
                                                            Get Offer
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </coupon-card>
                            <coupon-card
                                    :coupon="{&quot;url&quot;:&quot;\/go\/3590221&quot;,&quot;modal_url&quot;:&quot;\/stores\/pandora-promo-codes\/?_c=3590221&quot;}"
                                    :position="6" inline-template>
                                <div dusk="coupon-card" @click="openCouponPage('card')" class="tw-cursor-auto">
                                    <!--  xl:tw-h-full -->
                                    <div class="tw-rounded-sm tw-shadow tw-bg-white sm:tw-p-4 tw-p-2 sm:tw-mb-4 tw-mb-2 xl:tw-h-full">
                                        <div class="tw-flex lato tw-h-full">
                                            <div @click.stop
                                                 class="tw-text-center xl:tw-w-1/6 tw-w-1/4 xl:tw-min-w-1/6 tw-min-w-1/4">
                                                <a href="https://couponcause.com/stores/pandora-promo-codes/"
                                                   title="Pandora Coupons and Promo Codes"
                                                   class="card-logo tw-flex tw-justify-center tw-items-center tw-h-full">
                                                    <img class="sized-img"
                                                         src="https://cdn.couponcause.com/logos/180x110/prodege-48-10233-180x110-1661443435.webp"
                                                         alt="Pandora Coupons and Promo Codes" width="180" height="110"
                                                         loading="lazy">
                                                </a>
                                            </div>
                                            <div class=" tw-flex tw-leading-tight tw-w-5/6 sm:tw-ml-6 tw-ml-4">
                                                <div class="tw-flex-grow tw-pr-4 coupon-card-inner-container">
                                                    <a id="testingId3" dusk="coupon-card-label" href="/go/3590221"
                                                       onclick="gtag_report_conversion('/go/3590221')"
                                                       @click.stop.prevent="openCouponPage('label')"
                                                       class="tw-text-grey-darker sm:tw-text-xl tw-text-lg tw-font-medium hover:tw-text-blue lato"
                                                       rel="nofollow">Get 10% off and earn points on your next Disney
                                                        purchase! Join My Pandora.
                                                    </a>
                                                    <div class="sm:tw-leading-loose tw-leading-normal">
                                                        <p class="tw-text-grey sm:tw-text-base tw-text-xs lato">Expires
                                                            Apr 3</p>
                                                        <div @click.stop="toggleCouponDetails()"
                                                             class="tw-relative tw-hidden lg:tw-block tw-text-grey-darker tw-font-light tw-cursor-pointer">
                                                            <span>Details: <i
                                                                        :class="[showDetails ? 'fa-caret-down' : 'fa-caret-right', 'fa']"></i></span>
                                                            <p ref="details" v-if="showDetails"
                                                               class="tw-absolute tw-border tw-rounded-sm tw-bg-white tw-text-sm tw-py-2 tw-px-4 tw-w-64 tw-z-20"
                                                               v-cloak>No Promo Code Needed. Click &quot;Get Offer&quot;
                                                                To Activate This Deal. Exclusions May Apply</p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div @click.stop="openCouponPage('cta')"
                                                     onclick="gtag_report_conversion('/go/3590221')"
                                                     class="tw-hidden md:tw-flex tw-items-start tw-whitespace-no-wrap tw-min-w-1/4">
                                                    <div class="tw-relative tw-rounded-sm tw-overflow-hidden tw-cursor-pointer tw-w-full lato"
                                                         rel="nofollow">
                                                        <div class="tw-bg-blue hover:tw-bg-blue-dark tw-text-center tw-text-white tw-py-2 tw-px-4">
                                                            Get Offer
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </coupon-card>
                            <coupon-card
                                    :coupon="{&quot;url&quot;:&quot;\/go\/25798&quot;,&quot;modal_url&quot;:&quot;\/stores\/casetify\/?_c=25798&quot;}"
                                    :position="7" inline-template>
                                <div dusk="coupon-card" @click="openCouponPage('card')" class="tw-cursor-auto">
                                    <!--  xl:tw-h-full -->
                                    <div class="tw-rounded-sm tw-shadow tw-bg-white sm:tw-p-4 tw-p-2 sm:tw-mb-4 tw-mb-2 xl:tw-h-full">
                                        <div class="tw-flex lato tw-h-full">
                                            <div @click.stop
                                                 class="tw-text-center xl:tw-w-1/6 tw-w-1/4 xl:tw-min-w-1/6 tw-min-w-1/4">
                                                <a href="https://couponcause.com/stores/casetify/"
                                                   title="Casetify Coupons and Promo Codes"
                                                   class="card-logo tw-flex tw-justify-center tw-items-center tw-h-full">
                                                    <img class="sized-img"
                                                         src="https://cdn.couponcause.com/logos/180x110/casetify-180x110-1588878651.webp"
                                                         alt="Casetify Coupons and Promo Codes" width="180" height="110"
                                                         loading="lazy">
                                                </a>
                                            </div>
                                            <div class=" tw-flex tw-leading-tight tw-w-5/6 sm:tw-ml-6 tw-ml-4">
                                                <div class="tw-flex-grow tw-pr-4 coupon-card-inner-container">
                                                    <a id="testingId3" dusk="coupon-card-label" href="/go/25798"
                                                       onclick="gtag_report_conversion('/go/25798')"
                                                       @click.stop.prevent="openCouponPage('label')"
                                                       class="tw-text-grey-darker sm:tw-text-xl tw-text-lg tw-font-medium hover:tw-text-blue lato"
                                                       rel="nofollow">Free Shipping on All Orders $40+
                                                    </a>
                                                    <div class="sm:tw-leading-loose tw-leading-normal">
                                                        <p class="tw-text-grey sm:tw-text-base tw-text-xs lato">Ongoing
                                                            Offer</p>
                                                        <div @click.stop="toggleCouponDetails()"
                                                             class="tw-relative tw-hidden lg:tw-block tw-text-grey-darker tw-font-light tw-cursor-pointer">
                                                            <span>Details: <i
                                                                        :class="[showDetails ? 'fa-caret-down' : 'fa-caret-right', 'fa']"></i></span>
                                                            <p ref="details" v-if="showDetails"
                                                               class="tw-absolute tw-border tw-rounded-sm tw-bg-white tw-text-sm tw-py-2 tw-px-4 tw-w-64 tw-z-20"
                                                               v-cloak>No Promo Code Needed. Click &quot;Get Offer&quot;
                                                                To Activate This Deal. Exclusions May Apply</p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div @click.stop="openCouponPage('cta')"
                                                     onclick="gtag_report_conversion('/go/25798')"
                                                     class="tw-hidden md:tw-flex tw-items-start tw-whitespace-no-wrap tw-min-w-1/4">
                                                    <div class="tw-relative tw-rounded-sm tw-overflow-hidden tw-cursor-pointer tw-w-full lato"
                                                         rel="nofollow">
                                                        <div class="tw-bg-blue hover:tw-bg-blue-dark tw-text-center tw-text-white tw-py-2 tw-px-4">
                                                            Get Offer
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </coupon-card>
                            <coupon-card
                                    :coupon="{&quot;url&quot;:&quot;\/go\/1034007&quot;,&quot;modal_url&quot;:&quot;\/stores\/best-buy\/?_c=1034007&quot;}"
                                    :position="8" inline-template>
                                <div dusk="coupon-card" @click="openCouponPage('card')" class="tw-cursor-auto">
                                    <!--  xl:tw-h-full -->
                                    <div class="tw-rounded-sm tw-shadow tw-bg-white sm:tw-p-4 tw-p-2 sm:tw-mb-4 tw-mb-2 xl:tw-h-full">
                                        <div class="tw-flex lato tw-h-full">
                                            <div @click.stop
                                                 class="tw-text-center xl:tw-w-1/6 tw-w-1/4 xl:tw-min-w-1/6 tw-min-w-1/4">
                                                <a href="https://couponcause.com/stores/best-buy/"
                                                   title="Best Buy Coupons and Promo Codes"
                                                   class="card-logo tw-flex tw-justify-center tw-items-center tw-h-full">
                                                    <img class="sized-img"
                                                         src="https://cdn.couponcause.com/logos/180x110/best-buy-180x110-1569994185.webp"
                                                         alt="Best Buy Coupons and Promo Codes" width="180" height="110"
                                                         loading="lazy">
                                                </a>
                                            </div>
                                            <div class=" tw-flex tw-leading-tight tw-w-5/6 sm:tw-ml-6 tw-ml-4">
                                                <div class="tw-flex-grow tw-pr-4 coupon-card-inner-container">
                                                    <a id="testingId3" dusk="coupon-card-label" href="/go/1034007"
                                                       onclick="gtag_report_conversion('/go/1034007')"
                                                       @click.stop.prevent="openCouponPage('label')"
                                                       class="tw-text-grey-darker sm:tw-text-xl tw-text-lg tw-font-medium hover:tw-text-blue lato"
                                                       rel="nofollow">20% Off On Select Cell Phone Accessories
                                                    </a>
                                                    <div class="sm:tw-leading-loose tw-leading-normal">
                                                        <p class="tw-text-grey sm:tw-text-base tw-text-xs lato">Ongoing
                                                            Offer</p>
                                                        <span class="tw-text-green sm:tw-border sm:tw-border-green sm:tw-text-green sm:tw-text-base tw-text-xs lato sm:tw-rounded sm:tw-px-2 sm:tw-py-1">Verified</span>
                                                        <div @click.stop="toggleCouponDetails()"
                                                             class="tw-relative tw-hidden lg:tw-block tw-text-grey-darker tw-font-light tw-cursor-pointer">
                                                            <span>Details: <i
                                                                        :class="[showDetails ? 'fa-caret-down' : 'fa-caret-right', 'fa']"></i></span>
                                                            <p ref="details" v-if="showDetails"
                                                               class="tw-absolute tw-border tw-rounded-sm tw-bg-white tw-text-sm tw-py-2 tw-px-4 tw-w-64 tw-z-20"
                                                               v-cloak>Click &quot;Show Coupon Code&quot; To Activate
                                                                This Deal. Exclusions May Apply</p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div @click.stop="openCouponPage('cta')"
                                                     onclick="gtag_report_conversion('/go/1034007')"
                                                     class="tw-hidden md:tw-flex tw-items-start tw-whitespace-no-wrap tw-min-w-1/4">
                                                    <div class="tw-relative tw-rounded-sm tw-overflow-hidden tw-cursor-pointer tw-w-full lato"
                                                         rel="nofollow">
                                                        <div class="tw-bg-grey-lighter tw-border tw-text-grey-dark tw-text-right tw-w-full tw-p-2 ">
                                                            mobileaccysave20
                                                        </div>
                                                        <div class="tw-absolute tw-pin-t tw-pin-l tw-bg-blue hover:tw-bg-blue-dark tw-border tw-border-blue tw-text-center tw-text-white tw-min-w-5/6 tw-p-2">
                                                            Show Code
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </coupon-card>
                            <coupon-card
                                    :coupon="{&quot;url&quot;:&quot;\/go\/3501341&quot;,&quot;modal_url&quot;:&quot;\/stores\/quest-promo-codes\/?_c=3501341&quot;}"
                                    :position="9" inline-template>
                                <div dusk="coupon-card" @click="openCouponPage('card')" class="tw-cursor-auto">
                                    <!--  xl:tw-h-full -->
                                    <div class="tw-rounded-sm tw-shadow tw-bg-white sm:tw-p-4 tw-p-2 sm:tw-mb-4 tw-mb-2 xl:tw-h-full">
                                        <div class="tw-flex lato tw-h-full">
                                            <div @click.stop
                                                 class="tw-text-center xl:tw-w-1/6 tw-w-1/4 xl:tw-min-w-1/6 tw-min-w-1/4">
                                                <a href="https://couponcause.com/stores/quest-promo-codes/"
                                                   title="Quest Coupons and Promo Codes"
                                                   class="card-logo tw-flex tw-justify-center tw-items-center tw-h-full">
                                                    <img class="sized-img"
                                                         src="https://cdn.couponcause.com/logos/180x110/quest-promo-codes-180x110-1714669437.webp"
                                                         alt="Quest Coupons and Promo Codes" width="180" height="110"
                                                         loading="lazy">
                                                </a>
                                            </div>
                                            <div class=" tw-flex tw-leading-tight tw-w-5/6 sm:tw-ml-6 tw-ml-4">
                                                <div class="tw-flex-grow tw-pr-4 coupon-card-inner-container">
                                                    <a id="testingId3" dusk="coupon-card-label" href="/go/3501341"
                                                       onclick="gtag_report_conversion('/go/3501341')"
                                                       @click.stop.prevent="openCouponPage('label')"
                                                       class="tw-text-grey-darker sm:tw-text-xl tw-text-lg tw-font-medium hover:tw-text-blue lato"
                                                       rel="nofollow">Get 10% off on your first purchase
                                                    </a>
                                                    <div class="sm:tw-leading-loose tw-leading-normal">
                                                        <p class="tw-text-grey sm:tw-text-base tw-text-xs lato">Ongoing
                                                            Offer</p>
                                                        <span class="tw-text-green sm:tw-border sm:tw-border-green sm:tw-text-green sm:tw-text-base tw-text-xs lato sm:tw-rounded sm:tw-px-2 sm:tw-py-1">Verified</span>
                                                        <div @click.stop="toggleCouponDetails()"
                                                             class="tw-relative tw-hidden lg:tw-block tw-text-grey-darker tw-font-light tw-cursor-pointer">
                                                            <span>Details: <i
                                                                        :class="[showDetails ? 'fa-caret-down' : 'fa-caret-right', 'fa']"></i></span>
                                                            <p ref="details" v-if="showDetails"
                                                               class="tw-absolute tw-border tw-rounded-sm tw-bg-white tw-text-sm tw-py-2 tw-px-4 tw-w-64 tw-z-20"
                                                               v-cloak>Exclusions apply. Offer valid on first purchase
                                                                on questhealth.com only and cannot be applied to
                                                                previous purchases or combined with any other offer.</p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div @click.stop="openCouponPage('cta')"
                                                     onclick="gtag_report_conversion('/go/3501341')"
                                                     class="tw-hidden md:tw-flex tw-items-start tw-whitespace-no-wrap tw-min-w-1/4">
                                                    <div class="tw-relative tw-rounded-sm tw-overflow-hidden tw-cursor-pointer tw-w-full lato"
                                                         rel="nofollow">
                                                        <div class="tw-bg-blue hover:tw-bg-blue-dark tw-text-center tw-text-white tw-py-2 tw-px-4">
                                                            Get Offer
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </coupon-card>
                            <coupon-card
                                    :coupon="{&quot;url&quot;:&quot;\/go\/2916975&quot;,&quot;modal_url&quot;:&quot;\/stores\/e-file-promo-codes\/?_c=2916975&quot;}"
                                    :position="10" inline-template>
                                <div dusk="coupon-card" @click="openCouponPage('card')" class="tw-cursor-auto">
                                    <!--  xl:tw-h-full -->
                                    <div class="tw-rounded-sm tw-shadow tw-bg-white sm:tw-p-4 tw-p-2 sm:tw-mb-4 tw-mb-2 xl:tw-h-full">
                                        <div class="tw-flex lato tw-h-full">
                                            <div @click.stop
                                                 class="tw-text-center xl:tw-w-1/6 tw-w-1/4 xl:tw-min-w-1/6 tw-min-w-1/4">
                                                <a href="https://couponcause.com/stores/e-file-promo-codes/"
                                                   title="E-file.com Coupons and Promo Codes"
                                                   class="card-logo tw-flex tw-justify-center tw-items-center tw-h-full">
                                                    <img class="sized-img"
                                                         src="https://cdn.couponcause.com/logos/180x110/e-file-promo-codes-180x110-1583163626.webp"
                                                         alt="E-file.com Coupons and Promo Codes" width="180"
                                                         height="110" loading="lazy">
                                                </a>
                                            </div>
                                            <div class=" tw-flex tw-leading-tight tw-w-5/6 sm:tw-ml-6 tw-ml-4">
                                                <div class="tw-flex-grow tw-pr-4 coupon-card-inner-container">
                                                    <a id="testingId3" dusk="coupon-card-label" href="/go/2916975"
                                                       onclick="gtag_report_conversion('/go/2916975')"
                                                       @click.stop.prevent="openCouponPage('label')"
                                                       class="tw-text-grey-darker sm:tw-text-xl tw-text-lg tw-font-medium hover:tw-text-blue lato"
                                                       rel="nofollow">50% Off Select E-filing
                                                    </a>
                                                    <div class="sm:tw-leading-loose tw-leading-normal">
                                                        <p class="tw-text-grey sm:tw-text-base tw-text-xs lato">Ongoing
                                                            Offer</p>
                                                        <span class="tw-text-green sm:tw-border sm:tw-border-green sm:tw-text-green sm:tw-text-base tw-text-xs lato sm:tw-rounded sm:tw-px-2 sm:tw-py-1">Verified</span>
                                                        <div @click.stop="toggleCouponDetails()"
                                                             class="tw-relative tw-hidden lg:tw-block tw-text-grey-darker tw-font-light tw-cursor-pointer">
                                                            <span>Details: <i
                                                                        :class="[showDetails ? 'fa-caret-down' : 'fa-caret-right', 'fa']"></i></span>
                                                            <p ref="details" v-if="showDetails"
                                                               class="tw-absolute tw-border tw-rounded-sm tw-bg-white tw-text-sm tw-py-2 tw-px-4 tw-w-64 tw-z-20"
                                                               v-cloak>Click &quot;Show Coupon Code&quot; To Activate
                                                                This Deal. Exclusions May Apply</p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div @click.stop="openCouponPage('cta')"
                                                     onclick="gtag_report_conversion('/go/2916975')"
                                                     class="tw-hidden md:tw-flex tw-items-start tw-whitespace-no-wrap tw-min-w-1/4">
                                                    <div class="tw-relative tw-rounded-sm tw-overflow-hidden tw-cursor-pointer tw-w-full lato"
                                                         rel="nofollow">
                                                        <div class="tw-bg-grey-lighter tw-border tw-text-grey-dark tw-text-right tw-w-full tw-p-2 ">
                                                            1EFILE50
                                                        </div>
                                                        <div class="tw-absolute tw-pin-t tw-pin-l tw-bg-blue hover:tw-bg-blue-dark tw-border tw-border-blue tw-text-center tw-text-white tw-min-w-5/6 tw-p-2">
                                                            Show Code
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </coupon-card>
                            <coupon-card
                                    :coupon="{&quot;url&quot;:&quot;\/go\/3588547&quot;,&quot;modal_url&quot;:&quot;\/stores\/cheapoair\/?_c=3588547&quot;}"
                                    :position="11" inline-template>
                                <div dusk="coupon-card" @click="openCouponPage('card')" class="tw-cursor-auto">
                                    <!--  xl:tw-h-full -->
                                    <div class="tw-rounded-sm tw-shadow tw-bg-white sm:tw-p-4 tw-p-2 sm:tw-mb-4 tw-mb-2 xl:tw-h-full">
                                        <div class="tw-flex lato tw-h-full">
                                            <div @click.stop
                                                 class="tw-text-center xl:tw-w-1/6 tw-w-1/4 xl:tw-min-w-1/6 tw-min-w-1/4">
                                                <a href="https://couponcause.com/stores/cheapoair/"
                                                   title="CheapOair.com Coupons and Promo Codes"
                                                   class="card-logo tw-flex tw-justify-center tw-items-center tw-h-full">
                                                    <img class="sized-img"
                                                         src="https://cdn.couponcause.com/logos/180x110/cheapoair-180x110-1509096211.webp"
                                                         alt="CheapOair.com Coupons and Promo Codes" width="180"
                                                         height="110" loading="lazy">
                                                </a>
                                            </div>
                                            <div class=" tw-flex tw-leading-tight tw-w-5/6 sm:tw-ml-6 tw-ml-4">
                                                <div class="tw-flex-grow tw-pr-4 coupon-card-inner-container">
                                                    <a id="testingId3" dusk="coupon-card-label" href="/go/3588547"
                                                       onclick="gtag_report_conversion('/go/3588547')"
                                                       @click.stop.prevent="openCouponPage('label')"
                                                       class="tw-text-grey-darker sm:tw-text-xl tw-text-lg tw-font-medium hover:tw-text-blue lato"
                                                       rel="nofollow">Save up to $45 Off Fees on Flights
                                                    </a>
                                                    <div class="sm:tw-leading-loose tw-leading-normal">
                                                        <p class="tw-text-grey sm:tw-text-base tw-text-xs lato">Expires
                                                            Apr 1</p>
                                                        <span class="tw-text-green sm:tw-border sm:tw-border-green sm:tw-text-green sm:tw-text-base tw-text-xs lato sm:tw-rounded sm:tw-px-2 sm:tw-py-1">Verified</span>
                                                        <span class="tw-text-blue sm:tw-border sm:tw-border-blue sm:tw-text-blue sm:tw-text-base tw-text-xs lato sm:tw-rounded sm:tw-px-2 sm:tw-py-1">Exclusive</span>
                                                        <span class="tw-text-orange  sm:tw-border sm:tw-border-orange  sm:tw-text-orange  sm:tw-text-base tw-text-xs lato sm:tw-rounded sm:tw-px-2 sm:tw-py-1">Featured</span>
                                                        <div @click.stop="toggleCouponDetails()"
                                                             class="tw-relative tw-hidden lg:tw-block tw-text-grey-darker tw-font-light tw-cursor-pointer">
                                                            <span>Details: <i
                                                                        :class="[showDetails ? 'fa-caret-down' : 'fa-caret-right', 'fa']"></i></span>
                                                            <p ref="details" v-if="showDetails"
                                                               class="tw-absolute tw-border tw-rounded-sm tw-bg-white tw-text-sm tw-py-2 tw-px-4 tw-w-64 tw-z-20"
                                                               v-cloak>Click &quot;Show Coupon Code&quot; To Activate
                                                                This Deal. Exclusions May Apply</p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div @click.stop="openCouponPage('cta')"
                                                     onclick="gtag_report_conversion('/go/3588547')"
                                                     class="tw-hidden md:tw-flex tw-items-start tw-whitespace-no-wrap tw-min-w-1/4">
                                                    <div class="tw-relative tw-rounded-sm tw-overflow-hidden tw-cursor-pointer tw-w-full lato"
                                                         rel="nofollow">
                                                        <div class="tw-bg-grey-lighter tw-border tw-text-grey-dark tw-text-right tw-w-full tw-p-2 ">
                                                            CAUSE45
                                                        </div>
                                                        <div class="tw-absolute tw-pin-t tw-pin-l tw-bg-blue hover:tw-bg-blue-dark tw-border tw-border-blue tw-text-center tw-text-white tw-min-w-5/6 tw-p-2">
                                                            Show Code
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </coupon-card>
                            <coupon-card
                                    :coupon="{&quot;url&quot;:&quot;\/go\/1160133&quot;,&quot;modal_url&quot;:&quot;\/stores\/amazon\/?_c=1160133&quot;}"
                                    :position="12" inline-template>
                                <div dusk="coupon-card" @click="openCouponPage('card')" class="tw-cursor-auto">
                                    <!--  xl:tw-h-full -->
                                    <div class="tw-rounded-sm tw-shadow tw-bg-white sm:tw-p-4 tw-p-2 sm:tw-mb-4 tw-mb-2 xl:tw-h-full">
                                        <div class="tw-flex lato tw-h-full">
                                            <div @click.stop
                                                 class="tw-text-center xl:tw-w-1/6 tw-w-1/4 xl:tw-min-w-1/6 tw-min-w-1/4">
                                                <a href="https://couponcause.com/stores/amazon/"
                                                   title="Amazon Coupons and Promo Codes"
                                                   class="card-logo tw-flex tw-justify-center tw-items-center tw-h-full">
                                                    <img class="sized-img"
                                                         src="https://cdn.couponcause.com/logos/180x110/amazon.comlogo.webp"
                                                         alt="Amazon Coupons and Promo Codes" width="180" height="110"
                                                         loading="lazy">
                                                </a>
                                            </div>
                                            <div class=" tw-flex tw-leading-tight tw-w-5/6 sm:tw-ml-6 tw-ml-4">
                                                <div class="tw-flex-grow tw-pr-4 coupon-card-inner-container">
                                                    <a id="testingId3" dusk="coupon-card-label" href="/go/1160133"
                                                       onclick="gtag_report_conversion('/go/1160133')"
                                                       @click.stop.prevent="openCouponPage('label')"
                                                       class="tw-text-grey-darker sm:tw-text-xl tw-text-lg tw-font-medium hover:tw-text-blue lato"
                                                       rel="nofollow">Save With Amazon Coupons &amp; Promo Codes
                                                    </a>
                                                    <div class="sm:tw-leading-loose tw-leading-normal">
                                                        <p class="tw-text-grey sm:tw-text-base tw-text-xs lato">Ongoing
                                                            Offer</p>
                                                        <span class="tw-text-green sm:tw-border sm:tw-border-green sm:tw-text-green sm:tw-text-base tw-text-xs lato sm:tw-rounded sm:tw-px-2 sm:tw-py-1">Verified</span>
                                                        <span class="tw-text-orange  sm:tw-border sm:tw-border-orange  sm:tw-text-orange  sm:tw-text-base tw-text-xs lato sm:tw-rounded sm:tw-px-2 sm:tw-py-1">Featured</span>
                                                        <div @click.stop="toggleCouponDetails()"
                                                             class="tw-relative tw-hidden lg:tw-block tw-text-grey-darker tw-font-light tw-cursor-pointer">
                                                            <span>Details: <i
                                                                        :class="[showDetails ? 'fa-caret-down' : 'fa-caret-right', 'fa']"></i></span>
                                                            <p ref="details" v-if="showDetails"
                                                               class="tw-absolute tw-border tw-rounded-sm tw-bg-white tw-text-sm tw-py-2 tw-px-4 tw-w-64 tw-z-20"
                                                               v-cloak>No Promo Code Needed. Click &quot;Get Offer&quot;
                                                                To Activate This Deal. Exclusions May Apply</p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div @click.stop="openCouponPage('cta')"
                                                     onclick="gtag_report_conversion('/go/1160133')"
                                                     class="tw-hidden md:tw-flex tw-items-start tw-whitespace-no-wrap tw-min-w-1/4">
                                                    <div class="tw-relative tw-rounded-sm tw-overflow-hidden tw-cursor-pointer tw-w-full lato"
                                                         rel="nofollow">
                                                        <div class="tw-bg-blue hover:tw-bg-blue-dark tw-text-center tw-text-white tw-py-2 tw-px-4">
                                                            Get Offer
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </coupon-card>
                            <coupon-card
                                    :coupon="{&quot;url&quot;:&quot;\/go\/2519745&quot;,&quot;modal_url&quot;:&quot;\/stores\/eyebuydirect-promo-codes\/?_c=2519745&quot;}"
                                    :position="13" inline-template>
                                <div dusk="coupon-card" @click="openCouponPage('card')" class="tw-cursor-auto">
                                    <!--  xl:tw-h-full -->
                                    <div class="tw-rounded-sm tw-shadow tw-bg-white sm:tw-p-4 tw-p-2 sm:tw-mb-4 tw-mb-2 xl:tw-h-full">
                                        <div class="tw-flex lato tw-h-full">
                                            <div @click.stop
                                                 class="tw-text-center xl:tw-w-1/6 tw-w-1/4 xl:tw-min-w-1/6 tw-min-w-1/4">
                                                <a href="https://couponcause.com/stores/eyebuydirect-promo-codes/"
                                                   title="EyeBuyDirect Coupons and Promo Codes"
                                                   class="card-logo tw-flex tw-justify-center tw-items-center tw-h-full">
                                                    <img class="sized-img"
                                                         src="https://cdn.couponcause.com/logos/180x110/eyebuydirect-promo-codes-180x110-1658793355.png"
                                                         alt="EyeBuyDirect Coupons and Promo Codes" width="180"
                                                         height="110" loading="lazy">
                                                </a>
                                            </div>
                                            <div class=" tw-flex tw-leading-tight tw-w-5/6 sm:tw-ml-6 tw-ml-4">
                                                <div class="tw-flex-grow tw-pr-4 coupon-card-inner-container">
                                                    <a id="testingId3" dusk="coupon-card-label" href="/go/2519745"
                                                       onclick="gtag_report_conversion('/go/2519745')"
                                                       @click.stop.prevent="openCouponPage('label')"
                                                       class="tw-text-grey-darker sm:tw-text-xl tw-text-lg tw-font-medium hover:tw-text-blue lato"
                                                       rel="nofollow">*Exclusive* - Extra $40 Off All Orders $130
                                                    </a>
                                                    <div class="sm:tw-leading-loose tw-leading-normal">
                                                        <p class="tw-text-grey sm:tw-text-base tw-text-xs lato">Ongoing
                                                            Offer</p>
                                                        <span class="tw-text-blue sm:tw-border sm:tw-border-blue sm:tw-text-blue sm:tw-text-base tw-text-xs lato sm:tw-rounded sm:tw-px-2 sm:tw-py-1">Exclusive</span>
                                                        <span class="tw-text-orange  sm:tw-border sm:tw-border-orange  sm:tw-text-orange  sm:tw-text-base tw-text-xs lato sm:tw-rounded sm:tw-px-2 sm:tw-py-1">Featured</span>
                                                        <div @click.stop="toggleCouponDetails()"
                                                             class="tw-relative tw-hidden lg:tw-block tw-text-grey-darker tw-font-light tw-cursor-pointer">
                                                            <span>Details: <i
                                                                        :class="[showDetails ? 'fa-caret-down' : 'fa-caret-right', 'fa']"></i></span>
                                                            <p ref="details" v-if="showDetails"
                                                               class="tw-absolute tw-border tw-rounded-sm tw-bg-white tw-text-sm tw-py-2 tw-px-4 tw-w-64 tw-z-20"
                                                               v-cloak>* Offer cannot be combined with any other coupons
                                                                or discounts
                                                                * Maximum 6 frames per order
                                                                * Offer subject to adjustment due to modifications,
                                                                returns, cancellations, and exchanges.
                                                                * Offer may not be used on Ray-Ban or Oakley frames.</p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div @click.stop="openCouponPage('cta')"
                                                     onclick="gtag_report_conversion('/go/2519745')"
                                                     class="tw-hidden md:tw-flex tw-items-start tw-whitespace-no-wrap tw-min-w-1/4">
                                                    <div class="tw-relative tw-rounded-sm tw-overflow-hidden tw-cursor-pointer tw-w-full lato"
                                                         rel="nofollow">
                                                        <div class="tw-bg-grey-lighter tw-border tw-text-grey-dark tw-text-right tw-w-full tw-p-2 ">
                                                            CAUSE40FORU
                                                        </div>
                                                        <div class="tw-absolute tw-pin-t tw-pin-l tw-bg-blue hover:tw-bg-blue-dark tw-border tw-border-blue tw-text-center tw-text-white tw-min-w-5/6 tw-p-2">
                                                            Show Code
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </coupon-card>
                            <coupon-card
                                    :coupon="{&quot;url&quot;:&quot;\/go\/1871077&quot;,&quot;modal_url&quot;:&quot;\/stores\/chewy-promo-codes\/?_c=1871077&quot;}"
                                    :position="14" inline-template>
                                <div dusk="coupon-card" @click="openCouponPage('card')" class="tw-cursor-auto">
                                    <!--  xl:tw-h-full -->
                                    <div class="tw-rounded-sm tw-shadow tw-bg-white sm:tw-p-4 tw-p-2 sm:tw-mb-4 tw-mb-2 xl:tw-h-full">
                                        <div class="tw-flex lato tw-h-full">
                                            <div @click.stop
                                                 class="tw-text-center xl:tw-w-1/6 tw-w-1/4 xl:tw-min-w-1/6 tw-min-w-1/4">
                                                <a href="https://couponcause.com/stores/chewy-promo-codes/"
                                                   title="Chewy Coupons and Promo Codes"
                                                   class="card-logo tw-flex tw-justify-center tw-items-center tw-h-full">
                                                    <img class="sized-img"
                                                         src="https://cdn.couponcause.com/logos/180x110/chewy-promo-codes-180x110-1566481433.webp"
                                                         alt="Chewy Coupons and Promo Codes" width="180" height="110"
                                                         loading="lazy">
                                                </a>
                                            </div>
                                            <div class=" tw-flex tw-leading-tight tw-w-5/6 sm:tw-ml-6 tw-ml-4">
                                                <div class="tw-flex-grow tw-pr-4 coupon-card-inner-container">
                                                    <a id="testingId3" dusk="coupon-card-label" href="/go/1871077"
                                                       onclick="gtag_report_conversion('/go/1871077')"
                                                       @click.stop.prevent="openCouponPage('label')"
                                                       class="tw-text-grey-darker sm:tw-text-xl tw-text-lg tw-font-medium hover:tw-text-blue lato"
                                                       rel="nofollow">20% Off Your First Pharmacy Order
                                                    </a>
                                                    <div class="sm:tw-leading-loose tw-leading-normal">
                                                        <p class="tw-text-grey sm:tw-text-base tw-text-xs lato">Ongoing
                                                            Offer</p>
                                                        <div @click.stop="toggleCouponDetails()"
                                                             class="tw-relative tw-hidden lg:tw-block tw-text-grey-darker tw-font-light tw-cursor-pointer">
                                                            <span>Details: <i
                                                                        :class="[showDetails ? 'fa-caret-down' : 'fa-caret-right', 'fa']"></i></span>
                                                            <p ref="details" v-if="showDetails"
                                                               class="tw-absolute tw-border tw-rounded-sm tw-bg-white tw-text-sm tw-py-2 tw-px-4 tw-w-64 tw-z-20"
                                                               v-cloak>Click &quot;Show Coupon Code&quot; To Activate
                                                                This Deal. Exclusions May Apply</p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div @click.stop="openCouponPage('cta')"
                                                     onclick="gtag_report_conversion('/go/1871077')"
                                                     class="tw-hidden md:tw-flex tw-items-start tw-whitespace-no-wrap tw-min-w-1/4">
                                                    <div class="tw-relative tw-rounded-sm tw-overflow-hidden tw-cursor-pointer tw-w-full lato"
                                                         rel="nofollow">
                                                        <div class="tw-bg-grey-lighter tw-border tw-text-grey-dark tw-text-right tw-w-full tw-p-2 ">
                                                            RX20
                                                        </div>
                                                        <div class="tw-absolute tw-pin-t tw-pin-l tw-bg-blue hover:tw-bg-blue-dark tw-border tw-border-blue tw-text-center tw-text-white tw-min-w-5/6 tw-p-2">
                                                            Show Code
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </coupon-card>
                            <coupon-card
                                    :coupon="{&quot;url&quot;:&quot;\/go\/3000002&quot;,&quot;modal_url&quot;:&quot;\/stores\/taskrabbit-promo-codes\/?_c=3000002&quot;}"
                                    :position="15" inline-template>
                                <div dusk="coupon-card" @click="openCouponPage('card')" class="tw-cursor-auto">
                                    <!--  xl:tw-h-full -->
                                    <div class="tw-rounded-sm tw-shadow tw-bg-white sm:tw-p-4 tw-p-2 sm:tw-mb-4 tw-mb-2 xl:tw-h-full">
                                        <div class="tw-flex lato tw-h-full">
                                            <div @click.stop
                                                 class="tw-text-center xl:tw-w-1/6 tw-w-1/4 xl:tw-min-w-1/6 tw-min-w-1/4">
                                                <a href="https://couponcause.com/stores/taskrabbit-promo-codes/"
                                                   title="Taskrabbit Coupons and Promo Codes"
                                                   class="card-logo tw-flex tw-justify-center tw-items-center tw-h-full">
                                                    <img class="sized-img"
                                                         src="https://cdn.couponcause.com/logos/180x110/taskrabbit-promo-codes-180x110-1700530767.webp"
                                                         alt="Taskrabbit Coupons and Promo Codes" width="180"
                                                         height="110" loading="lazy">
                                                </a>
                                            </div>
                                            <div class=" tw-flex tw-leading-tight tw-w-5/6 sm:tw-ml-6 tw-ml-4">
                                                <div class="tw-flex-grow tw-pr-4 coupon-card-inner-container">
                                                    <a id="testingId3" dusk="coupon-card-label" href="/go/3000002"
                                                       onclick="gtag_report_conversion('/go/3000002')"
                                                       @click.stop.prevent="openCouponPage('label')"
                                                       class="tw-text-grey-darker sm:tw-text-xl tw-text-lg tw-font-medium hover:tw-text-blue lato"
                                                       rel="nofollow">$10 Off Your First Task with Email Signup
                                                    </a>
                                                    <div class="sm:tw-leading-loose tw-leading-normal">
                                                        <p class="tw-text-grey sm:tw-text-base tw-text-xs lato">Ongoing
                                                            Offer</p>
                                                        <span class="tw-text-green sm:tw-border sm:tw-border-green sm:tw-text-green sm:tw-text-base tw-text-xs lato sm:tw-rounded sm:tw-px-2 sm:tw-py-1">Verified</span>
                                                        <div @click.stop="toggleCouponDetails()"
                                                             class="tw-relative tw-hidden lg:tw-block tw-text-grey-darker tw-font-light tw-cursor-pointer">
                                                            <span>Details: <i
                                                                        :class="[showDetails ? 'fa-caret-down' : 'fa-caret-right', 'fa']"></i></span>
                                                            <p ref="details" v-if="showDetails"
                                                               class="tw-absolute tw-border tw-rounded-sm tw-bg-white tw-text-sm tw-py-2 tw-px-4 tw-w-64 tw-z-20"
                                                               v-cloak>No Promo Code Needed. Click &quot;Get Offer&quot;
                                                                To Activate This Deal. Exclusions May Apply</p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div @click.stop="openCouponPage('cta')"
                                                     onclick="gtag_report_conversion('/go/3000002')"
                                                     class="tw-hidden md:tw-flex tw-items-start tw-whitespace-no-wrap tw-min-w-1/4">
                                                    <div class="tw-relative tw-rounded-sm tw-overflow-hidden tw-cursor-pointer tw-w-full lato"
                                                         rel="nofollow">
                                                        <div class="tw-bg-blue hover:tw-bg-blue-dark tw-text-center tw-text-white tw-py-2 tw-px-4">
                                                            Get Offer
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </coupon-card>
                            <coupon-card
                                    :coupon="{&quot;url&quot;:&quot;\/go\/33625&quot;,&quot;modal_url&quot;:&quot;\/stores\/lyft-promo-codes\/?_c=33625&quot;}"
                                    :position="16" inline-template>
                                <div dusk="coupon-card" @click="openCouponPage('card')" class="tw-cursor-auto">
                                    <!--  xl:tw-h-full -->
                                    <div class="tw-rounded-sm tw-shadow tw-bg-white sm:tw-p-4 tw-p-2 sm:tw-mb-4 tw-mb-2 xl:tw-h-full">
                                        <div class="tw-flex lato tw-h-full">
                                            <div @click.stop
                                                 class="tw-text-center xl:tw-w-1/6 tw-w-1/4 xl:tw-min-w-1/6 tw-min-w-1/4">
                                                <a href="https://couponcause.com/stores/lyft-promo-codes/"
                                                   title="lyft Coupons and Promo Codes"
                                                   class="card-logo tw-flex tw-justify-center tw-items-center tw-h-full">
                                                    <img class="sized-img"
                                                         src="https://cdn.couponcause.com/logos/180x110/lyft-promo-codes-180x110-1510741420.webp"
                                                         alt="lyft Coupons and Promo Codes" width="180" height="110"
                                                         loading="lazy">
                                                </a>
                                            </div>
                                            <div class=" tw-flex tw-leading-tight tw-w-5/6 sm:tw-ml-6 tw-ml-4">
                                                <div class="tw-flex-grow tw-pr-4 coupon-card-inner-container">
                                                    <a id="testingId3" dusk="coupon-card-label" href="/go/33625"
                                                       onclick="gtag_report_conversion('/go/33625')"
                                                       @click.stop.prevent="openCouponPage('label')"
                                                       class="tw-text-grey-darker sm:tw-text-xl tw-text-lg tw-font-medium hover:tw-text-blue lato"
                                                       rel="nofollow">$50 in New User Credits
                                                    </a>
                                                    <div class="sm:tw-leading-loose tw-leading-normal">
                                                        <p class="tw-text-grey sm:tw-text-base tw-text-xs lato">Ongoing
                                                            Offer</p>
                                                        <span class="tw-text-green sm:tw-border sm:tw-border-green sm:tw-text-green sm:tw-text-base tw-text-xs lato sm:tw-rounded sm:tw-px-2 sm:tw-py-1">Verified</span>
                                                        <span class="tw-text-orange  sm:tw-border sm:tw-border-orange  sm:tw-text-orange  sm:tw-text-base tw-text-xs lato sm:tw-rounded sm:tw-px-2 sm:tw-py-1">Featured</span>
                                                        <div @click.stop="toggleCouponDetails()"
                                                             class="tw-relative tw-hidden lg:tw-block tw-text-grey-darker tw-font-light tw-cursor-pointer">
                                                            <span>Details: <i
                                                                        :class="[showDetails ? 'fa-caret-down' : 'fa-caret-right', 'fa']"></i></span>
                                                            <p ref="details" v-if="showDetails"
                                                               class="tw-absolute tw-border tw-rounded-sm tw-bg-white tw-text-sm tw-py-2 tw-px-4 tw-w-64 tw-z-20"
                                                               v-cloak>Click &quot;Show Coupon Code&quot; To Activate
                                                                This Deal. Exclusions May Apply</p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div @click.stop="openCouponPage('cta')"
                                                     onclick="gtag_report_conversion('/go/33625')"
                                                     class="tw-hidden md:tw-flex tw-items-start tw-whitespace-no-wrap tw-min-w-1/4">
                                                    <div class="tw-relative tw-rounded-sm tw-overflow-hidden tw-cursor-pointer tw-w-full lato"
                                                         rel="nofollow">
                                                        <div class="tw-bg-grey-lighter tw-border tw-text-grey-dark tw-text-right tw-w-full tw-p-2 ">
                                                            GETT
                                                        </div>
                                                        <div class="tw-absolute tw-pin-t tw-pin-l tw-bg-blue hover:tw-bg-blue-dark tw-border tw-border-blue tw-text-center tw-text-white tw-min-w-5/6 tw-p-2">
                                                            Show Code
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </coupon-card>
                            <coupon-card
                                    :coupon="{&quot;url&quot;:&quot;\/go\/2940967&quot;,&quot;modal_url&quot;:&quot;\/stores\/beyond-body-promo-codes\/?_c=2940967&quot;}"
                                    :position="17" inline-template>
                                <div dusk="coupon-card" @click="openCouponPage('card')" class="tw-cursor-auto">
                                    <!--  xl:tw-h-full -->
                                    <div class="tw-rounded-sm tw-shadow tw-bg-white sm:tw-p-4 tw-p-2 sm:tw-mb-4 tw-mb-2 xl:tw-h-full">
                                        <div class="tw-flex lato tw-h-full">
                                            <div @click.stop
                                                 class="tw-text-center xl:tw-w-1/6 tw-w-1/4 xl:tw-min-w-1/6 tw-min-w-1/4">
                                                <a href="https://couponcause.com/stores/beyond-body-promo-codes/"
                                                   title="Beyond Body Coupons and Promo Codes"
                                                   class="card-logo tw-flex tw-justify-center tw-items-center tw-h-full">
                                                    <img class="sized-img"
                                                         src="https://cdn.couponcause.com/logos/180x110/beyond-body-promo-codes-180x110-1626713011.webp"
                                                         alt="Beyond Body Coupons and Promo Codes" width="180"
                                                         height="110" loading="lazy">
                                                </a>
                                            </div>
                                            <div class=" tw-flex tw-leading-tight tw-w-5/6 sm:tw-ml-6 tw-ml-4">
                                                <div class="tw-flex-grow tw-pr-4 coupon-card-inner-container">
                                                    <a id="testingId3" dusk="coupon-card-label" href="/go/2940967"
                                                       onclick="gtag_report_conversion('/go/2940967')"
                                                       @click.stop.prevent="openCouponPage('label')"
                                                       class="tw-text-grey-darker sm:tw-text-xl tw-text-lg tw-font-medium hover:tw-text-blue lato"
                                                       rel="nofollow">Up to 60% Off Your Order
                                                    </a>
                                                    <div class="sm:tw-leading-loose tw-leading-normal">
                                                        <p class="tw-text-grey sm:tw-text-base tw-text-xs lato">Ongoing
                                                            Offer</p>
                                                        <span class="tw-text-green sm:tw-border sm:tw-border-green sm:tw-text-green sm:tw-text-base tw-text-xs lato sm:tw-rounded sm:tw-px-2 sm:tw-py-1">Verified</span>
                                                        <span class="tw-text-blue sm:tw-border sm:tw-border-blue sm:tw-text-blue sm:tw-text-base tw-text-xs lato sm:tw-rounded sm:tw-px-2 sm:tw-py-1">Exclusive</span>
                                                        <span class="tw-text-orange  sm:tw-border sm:tw-border-orange  sm:tw-text-orange  sm:tw-text-base tw-text-xs lato sm:tw-rounded sm:tw-px-2 sm:tw-py-1">Featured</span>
                                                        <div @click.stop="toggleCouponDetails()"
                                                             class="tw-relative tw-hidden lg:tw-block tw-text-grey-darker tw-font-light tw-cursor-pointer">
                                                            <span>Details: <i
                                                                        :class="[showDetails ? 'fa-caret-down' : 'fa-caret-right', 'fa']"></i></span>
                                                            <p ref="details" v-if="showDetails"
                                                               class="tw-absolute tw-border tw-rounded-sm tw-bg-white tw-text-sm tw-py-2 tw-px-4 tw-w-64 tw-z-20"
                                                               v-cloak>No Promo Code Needed. Click &quot;Get Offer&quot;
                                                                To Activate This Deal. Exclusions May Apply</p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div @click.stop="openCouponPage('cta')"
                                                     onclick="gtag_report_conversion('/go/2940967')"
                                                     class="tw-hidden md:tw-flex tw-items-start tw-whitespace-no-wrap tw-min-w-1/4">
                                                    <div class="tw-relative tw-rounded-sm tw-overflow-hidden tw-cursor-pointer tw-w-full lato"
                                                         rel="nofollow">
                                                        <div class="tw-bg-blue hover:tw-bg-blue-dark tw-text-center tw-text-white tw-py-2 tw-px-4">
                                                            Get Offer
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </coupon-card>
                            <coupon-card
                                    :coupon="{&quot;url&quot;:&quot;\/go\/177701&quot;,&quot;modal_url&quot;:&quot;\/stores\/coinbase-promo-codes\/?_c=177701&quot;}"
                                    :position="18" inline-template>
                                <div dusk="coupon-card" @click="openCouponPage('card')" class="tw-cursor-auto">
                                    <!--  xl:tw-h-full -->
                                    <div class="tw-rounded-sm tw-shadow tw-bg-white sm:tw-p-4 tw-p-2 sm:tw-mb-4 tw-mb-2 xl:tw-h-full">
                                        <div class="tw-flex lato tw-h-full">
                                            <div @click.stop
                                                 class="tw-text-center xl:tw-w-1/6 tw-w-1/4 xl:tw-min-w-1/6 tw-min-w-1/4">
                                                <a href="https://couponcause.com/stores/coinbase-promo-codes/"
                                                   title="Coinbase Coupons and Promo Codes"
                                                   class="card-logo tw-flex tw-justify-center tw-items-center tw-h-full">
                                                    <img class="sized-img"
                                                         src="https://cdn.couponcause.com/logos/180x110/coinbase-promo-codes-180x110-1653328077.webp"
                                                         alt="Coinbase Coupons and Promo Codes" width="180" height="110"
                                                         loading="lazy">
                                                </a>
                                            </div>
                                            <div class=" tw-flex tw-leading-tight tw-w-5/6 sm:tw-ml-6 tw-ml-4">
                                                <div class="tw-flex-grow tw-pr-4 coupon-card-inner-container">
                                                    <a id="testingId3" dusk="coupon-card-label" href="/go/177701"
                                                       onclick="gtag_report_conversion('/go/177701')"
                                                       @click.stop.prevent="openCouponPage('label')"
                                                       class="tw-text-grey-darker sm:tw-text-xl tw-text-lg tw-font-medium hover:tw-text-blue lato"
                                                       rel="nofollow">Get $5 in Bitcoin when you make your first trade.
                                                        Terms Apply.
                                                    </a>
                                                    <div class="sm:tw-leading-loose tw-leading-normal">
                                                        <p class="tw-text-grey sm:tw-text-base tw-text-xs lato">Ongoing
                                                            Offer</p>
                                                        <span class="tw-text-green sm:tw-border sm:tw-border-green sm:tw-text-green sm:tw-text-base tw-text-xs lato sm:tw-rounded sm:tw-px-2 sm:tw-py-1">Verified</span>
                                                        <span class="tw-text-orange  sm:tw-border sm:tw-border-orange  sm:tw-text-orange  sm:tw-text-base tw-text-xs lato sm:tw-rounded sm:tw-px-2 sm:tw-py-1">Featured</span>
                                                        <div @click.stop="toggleCouponDetails()"
                                                             class="tw-relative tw-hidden lg:tw-block tw-text-grey-darker tw-font-light tw-cursor-pointer">
                                                            <span>Details: <i
                                                                        :class="[showDetails ? 'fa-caret-down' : 'fa-caret-right', 'fa']"></i></span>
                                                            <p ref="details" v-if="showDetails"
                                                               class="tw-absolute tw-border tw-rounded-sm tw-bg-white tw-text-sm tw-py-2 tw-px-4 tw-w-64 tw-z-20"
                                                               v-cloak>No Promo Code Needed. Click &quot;Get Offer&quot;
                                                                To Activate This Deal. Exclusions May Apply</p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div @click.stop="openCouponPage('cta')"
                                                     onclick="gtag_report_conversion('/go/177701')"
                                                     class="tw-hidden md:tw-flex tw-items-start tw-whitespace-no-wrap tw-min-w-1/4">
                                                    <div class="tw-relative tw-rounded-sm tw-overflow-hidden tw-cursor-pointer tw-w-full lato"
                                                         rel="nofollow">
                                                        <div class="tw-bg-blue hover:tw-bg-blue-dark tw-text-center tw-text-white tw-py-2 tw-px-4">
                                                            Get Offer
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </coupon-card>
                            <coupon-card
                                    :coupon="{&quot;url&quot;:&quot;\/go\/819002&quot;,&quot;modal_url&quot;:&quot;\/stores\/intermix-promo-codes\/?_c=819002&quot;}"
                                    :position="19" inline-template>
                                <div dusk="coupon-card" @click="openCouponPage('card')" class="tw-cursor-auto">
                                    <!--  xl:tw-h-full -->
                                    <div class="tw-rounded-sm tw-shadow tw-bg-white sm:tw-p-4 tw-p-2 sm:tw-mb-4 tw-mb-2 xl:tw-h-full">
                                        <div class="tw-flex lato tw-h-full">
                                            <div @click.stop
                                                 class="tw-text-center xl:tw-w-1/6 tw-w-1/4 xl:tw-min-w-1/6 tw-min-w-1/4">
                                                <a href="https://couponcause.com/stores/intermix-promo-codes/"
                                                   title="Intermix Coupons and Promo Codes"
                                                   class="card-logo tw-flex tw-justify-center tw-items-center tw-h-full">
                                                    <img class="sized-img"
                                                         src="https://cdn.couponcause.com/logos/180x110/intermix-promo-codes-180x110-1569333642.webp"
                                                         alt="Intermix Coupons and Promo Codes" width="180" height="110"
                                                         loading="lazy">
                                                </a>
                                            </div>
                                            <div class=" tw-flex tw-leading-tight tw-w-5/6 sm:tw-ml-6 tw-ml-4">
                                                <div class="tw-flex-grow tw-pr-4 coupon-card-inner-container">
                                                    <a id="testingId3" dusk="coupon-card-label" href="/go/819002"
                                                       onclick="gtag_report_conversion('/go/819002')"
                                                       @click.stop.prevent="openCouponPage('label')"
                                                       class="tw-text-grey-darker sm:tw-text-xl tw-text-lg tw-font-medium hover:tw-text-blue lato"
                                                       rel="nofollow">Join The Intermix Mailing List and Receive 15% Off
                                                        Your First Full-Price Purchase
                                                    </a>
                                                    <div class="sm:tw-leading-loose tw-leading-normal">
                                                        <p class="tw-text-grey sm:tw-text-base tw-text-xs lato">Ongoing
                                                            Offer</p>
                                                        <div @click.stop="toggleCouponDetails()"
                                                             class="tw-relative tw-hidden lg:tw-block tw-text-grey-darker tw-font-light tw-cursor-pointer">
                                                            <span>Details: <i
                                                                        :class="[showDetails ? 'fa-caret-down' : 'fa-caret-right', 'fa']"></i></span>
                                                            <p ref="details" v-if="showDetails"
                                                               class="tw-absolute tw-border tw-rounded-sm tw-bg-white tw-text-sm tw-py-2 tw-px-4 tw-w-64 tw-z-20"
                                                               v-cloak>No Promo Code Needed. Click &quot;Get Offer&quot;
                                                                To Activate This Deal. Exclusions May Apply</p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div @click.stop="openCouponPage('cta')"
                                                     onclick="gtag_report_conversion('/go/819002')"
                                                     class="tw-hidden md:tw-flex tw-items-start tw-whitespace-no-wrap tw-min-w-1/4">
                                                    <div class="tw-relative tw-rounded-sm tw-overflow-hidden tw-cursor-pointer tw-w-full lato"
                                                         rel="nofollow">
                                                        <div class="tw-bg-blue hover:tw-bg-blue-dark tw-text-center tw-text-white tw-py-2 tw-px-4">
                                                            Get Offer
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </coupon-card>
                            <coupon-card
                                    :coupon="{&quot;url&quot;:&quot;\/go\/3268913&quot;,&quot;modal_url&quot;:&quot;\/stores\/cricut-promo-codes\/?_c=3268913&quot;}"
                                    :position="20" inline-template>
                                <div dusk="coupon-card" @click="openCouponPage('card')" class="tw-cursor-auto">
                                    <!--  xl:tw-h-full -->
                                    <div class="tw-rounded-sm tw-shadow tw-bg-white sm:tw-p-4 tw-p-2 sm:tw-mb-4 tw-mb-2 xl:tw-h-full">
                                        <div class="tw-flex lato tw-h-full">
                                            <div @click.stop
                                                 class="tw-text-center xl:tw-w-1/6 tw-w-1/4 xl:tw-min-w-1/6 tw-min-w-1/4">
                                                <a href="https://couponcause.com/stores/cricut-promo-codes/"
                                                   title="Cricut Coupons and Promo Codes"
                                                   class="card-logo tw-flex tw-justify-center tw-items-center tw-h-full">
                                                    <img class="sized-img"
                                                         src="https://cdn.couponcause.com/logos/180x110/cricut-promo-codes-180x110-1586557082.webp"
                                                         alt="Cricut Coupons and Promo Codes" width="180" height="110"
                                                         loading="lazy">
                                                </a>
                                            </div>
                                            <div class=" tw-flex tw-leading-tight tw-w-5/6 sm:tw-ml-6 tw-ml-4">
                                                <div class="tw-flex-grow tw-pr-4 coupon-card-inner-container">
                                                    <a id="testingId3" dusk="coupon-card-label" href="/go/3268913"
                                                       onclick="gtag_report_conversion('/go/3268913')"
                                                       @click.stop.prevent="openCouponPage('label')"
                                                       class="tw-text-grey-darker sm:tw-text-xl tw-text-lg tw-font-medium hover:tw-text-blue lato"
                                                       rel="nofollow">*Exclusive* 20% Materials And Accessories
                                                    </a>
                                                    <div class="sm:tw-leading-loose tw-leading-normal">
                                                        <p class="tw-text-grey sm:tw-text-base tw-text-xs lato">Ongoing
                                                            Offer</p>
                                                        <span class="tw-text-blue sm:tw-border sm:tw-border-blue sm:tw-text-blue sm:tw-text-base tw-text-xs lato sm:tw-rounded sm:tw-px-2 sm:tw-py-1">Exclusive</span>
                                                        <div @click.stop="toggleCouponDetails()"
                                                             class="tw-relative tw-hidden lg:tw-block tw-text-grey-darker tw-font-light tw-cursor-pointer">
                                                            <span>Details: <i
                                                                        :class="[showDetails ? 'fa-caret-down' : 'fa-caret-right', 'fa']"></i></span>
                                                            <p ref="details" v-if="showDetails"
                                                               class="tw-absolute tw-border tw-rounded-sm tw-bg-white tw-text-sm tw-py-2 tw-px-4 tw-w-64 tw-z-20"
                                                               v-cloak>Click &quot;Show Coupon Code&quot; To Activate
                                                                This Deal. Exclusions May Apply</p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div @click.stop="openCouponPage('cta')"
                                                     onclick="gtag_report_conversion('/go/3268913')"
                                                     class="tw-hidden md:tw-flex tw-items-start tw-whitespace-no-wrap tw-min-w-1/4">
                                                    <div class="tw-relative tw-rounded-sm tw-overflow-hidden tw-cursor-pointer tw-w-full lato"
                                                         rel="nofollow">
                                                        <div class="tw-bg-grey-lighter tw-border tw-text-grey-dark tw-text-right tw-w-full tw-p-2 ">
                                                            Couponcause
                                                        </div>
                                                        <div class="tw-absolute tw-pin-t tw-pin-l tw-bg-blue hover:tw-bg-blue-dark tw-border tw-border-blue tw-text-center tw-text-white tw-min-w-5/6 tw-p-2">
                                                            Show Code
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </coupon-card>
                            <coupon-card
                                    :coupon="{&quot;url&quot;:&quot;\/go\/3582609&quot;,&quot;modal_url&quot;:&quot;\/stores\/total-wine-promo-codes\/?_c=3582609&quot;}"
                                    :position="21" inline-template>
                                <div dusk="coupon-card" @click="openCouponPage('card')" class="tw-cursor-auto">
                                    <!--  xl:tw-h-full -->
                                    <div class="tw-rounded-sm tw-shadow tw-bg-white sm:tw-p-4 tw-p-2 sm:tw-mb-4 tw-mb-2 xl:tw-h-full">
                                        <div class="tw-flex lato tw-h-full">
                                            <div @click.stop
                                                 class="tw-text-center xl:tw-w-1/6 tw-w-1/4 xl:tw-min-w-1/6 tw-min-w-1/4">
                                                <a href="https://couponcause.com/stores/total-wine-promo-codes/"
                                                   title="Total Wine Coupons and Promo Codes"
                                                   class="card-logo tw-flex tw-justify-center tw-items-center tw-h-full">
                                                    <img class="sized-img"
                                                         src="https://cdn.couponcause.com/logos/180x110/total-wine-promo-codes-180x110-1669856626.webp"
                                                         alt="Total Wine Coupons and Promo Codes" width="180"
                                                         height="110" loading="lazy">
                                                </a>
                                            </div>
                                            <div class=" tw-flex tw-leading-tight tw-w-5/6 sm:tw-ml-6 tw-ml-4">
                                                <div class="tw-flex-grow tw-pr-4 coupon-card-inner-container">
                                                    <a id="testingId3" dusk="coupon-card-label" href="/go/3582609"
                                                       onclick="gtag_report_conversion('/go/3582609')"
                                                       @click.stop.prevent="openCouponPage('label')"
                                                       class="tw-text-grey-darker sm:tw-text-xl tw-text-lg tw-font-medium hover:tw-text-blue lato"
                                                       rel="nofollow">Save $9 when you buy a trio of select, highly
                                                        rated red wines.
                                                    </a>
                                                    <div class="sm:tw-leading-loose tw-leading-normal">
                                                        <p class="tw-text-red sm:tw-text-base tw-text-xs lato">Last
                                                            Day!</p>
                                                        <div @click.stop="toggleCouponDetails()"
                                                             class="tw-relative tw-hidden lg:tw-block tw-text-grey-darker tw-font-light tw-cursor-pointer">
                                                            <span>Details: <i
                                                                        :class="[showDetails ? 'fa-caret-down' : 'fa-caret-right', 'fa']"></i></span>
                                                            <p ref="details" v-if="showDetails"
                                                               class="tw-absolute tw-border tw-rounded-sm tw-bg-white tw-text-sm tw-py-2 tw-px-4 tw-w-64 tw-z-20"
                                                               v-cloak>*See site for details.</p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div @click.stop="openCouponPage('cta')"
                                                     onclick="gtag_report_conversion('/go/3582609')"
                                                     class="tw-hidden md:tw-flex tw-items-start tw-whitespace-no-wrap tw-min-w-1/4">
                                                    <div class="tw-relative tw-rounded-sm tw-overflow-hidden tw-cursor-pointer tw-w-full lato"
                                                         rel="nofollow">
                                                        <div class="tw-bg-blue hover:tw-bg-blue-dark tw-text-center tw-text-white tw-py-2 tw-px-4">
                                                            Get Offer
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </coupon-card>
                            <coupon-card
                                    :coupon="{&quot;url&quot;:&quot;\/go\/2813465&quot;,&quot;modal_url&quot;:&quot;\/stores\/home-depot-coupons\/?_c=2813465&quot;}"
                                    :position="22" inline-template>
                                <div dusk="coupon-card" @click="openCouponPage('card')" class="tw-cursor-auto">
                                    <!--  xl:tw-h-full -->
                                    <div class="tw-rounded-sm tw-shadow tw-bg-white sm:tw-p-4 tw-p-2 sm:tw-mb-4 tw-mb-2 xl:tw-h-full">
                                        <div class="tw-flex lato tw-h-full">
                                            <div @click.stop
                                                 class="tw-text-center xl:tw-w-1/6 tw-w-1/4 xl:tw-min-w-1/6 tw-min-w-1/4">
                                                <a href="https://couponcause.com/stores/home-depot-coupons/"
                                                   title="Home Depot Coupons and Promo Codes"
                                                   class="card-logo tw-flex tw-justify-center tw-items-center tw-h-full">
                                                    <img class="sized-img"
                                                         src="https://cdn.couponcause.com/logos/180x110/home-depot-coupons-180x110-1675457045.webp"
                                                         alt="Home Depot Coupons and Promo Codes" width="180"
                                                         height="110" loading="lazy">
                                                </a>
                                            </div>
                                            <div class=" tw-flex tw-leading-tight tw-w-5/6 sm:tw-ml-6 tw-ml-4">
                                                <div class="tw-flex-grow tw-pr-4 coupon-card-inner-container">
                                                    <a id="testingId3" dusk="coupon-card-label" href="/go/2813465"
                                                       onclick="gtag_report_conversion('/go/2813465')"
                                                       @click.stop.prevent="openCouponPage('label')"
                                                       class="tw-text-grey-darker sm:tw-text-xl tw-text-lg tw-font-medium hover:tw-text-blue lato"
                                                       rel="nofollow">$5 Off Your First Purchase $50+ with Email Signup
                                                    </a>
                                                    <div class="sm:tw-leading-loose tw-leading-normal">
                                                        <p class="tw-text-grey sm:tw-text-base tw-text-xs lato">Ongoing
                                                            Offer</p>
                                                        <span class="tw-text-green sm:tw-border sm:tw-border-green sm:tw-text-green sm:tw-text-base tw-text-xs lato sm:tw-rounded sm:tw-px-2 sm:tw-py-1">Verified</span>
                                                        <span class="tw-text-orange  sm:tw-border sm:tw-border-orange  sm:tw-text-orange  sm:tw-text-base tw-text-xs lato sm:tw-rounded sm:tw-px-2 sm:tw-py-1">Featured</span>
                                                        <div @click.stop="toggleCouponDetails()"
                                                             class="tw-relative tw-hidden lg:tw-block tw-text-grey-darker tw-font-light tw-cursor-pointer">
                                                            <span>Details: <i
                                                                        :class="[showDetails ? 'fa-caret-down' : 'fa-caret-right', 'fa']"></i></span>
                                                            <p ref="details" v-if="showDetails"
                                                               class="tw-absolute tw-border tw-rounded-sm tw-bg-white tw-text-sm tw-py-2 tw-px-4 tw-w-64 tw-z-20"
                                                               v-cloak>At the bottom of the Home Depot homepage is a
                                                                signup form for their newsletter. Enter your information
                                                                and within 5 minutes you will receive an email from Home
                                                                Depot containing your coupon code for $5 off your first
                                                                $50+ order.</p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div @click.stop="openCouponPage('cta')"
                                                     onclick="gtag_report_conversion('/go/2813465')"
                                                     class="tw-hidden md:tw-flex tw-items-start tw-whitespace-no-wrap tw-min-w-1/4">
                                                    <div class="tw-relative tw-rounded-sm tw-overflow-hidden tw-cursor-pointer tw-w-full lato"
                                                         rel="nofollow">
                                                        <div class="tw-bg-blue hover:tw-bg-blue-dark tw-text-center tw-text-white tw-py-2 tw-px-4">
                                                            Get Offer
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </coupon-card>
                            <coupon-card
                                    :coupon="{&quot;url&quot;:&quot;\/go\/3575436&quot;,&quot;modal_url&quot;:&quot;\/stores\/eufy-promo-codes\/?_c=3575436&quot;}"
                                    :position="23" inline-template>
                                <div dusk="coupon-card" @click="openCouponPage('card')" class="tw-cursor-auto">
                                    <!--  xl:tw-h-full -->
                                    <div class="tw-rounded-sm tw-shadow tw-bg-white sm:tw-p-4 tw-p-2 sm:tw-mb-4 tw-mb-2 xl:tw-h-full">
                                        <div class="tw-flex lato tw-h-full">
                                            <div @click.stop
                                                 class="tw-text-center xl:tw-w-1/6 tw-w-1/4 xl:tw-min-w-1/6 tw-min-w-1/4">
                                                <a href="https://couponcause.com/stores/eufy-promo-codes/"
                                                   title="Eufy    Coupons and Promo Codes"
                                                   class="card-logo tw-flex tw-justify-center tw-items-center tw-h-full">
                                                    <img class="sized-img"
                                                         src="https://cdn.couponcause.com/logos/180x110/eufy-promo-codes-180x110-1698783609.webp"
                                                         alt="Eufy    Coupons and Promo Codes" width="180" height="110"
                                                         loading="lazy">
                                                </a>
                                            </div>
                                            <div class=" tw-flex tw-leading-tight tw-w-5/6 sm:tw-ml-6 tw-ml-4">
                                                <div class="tw-flex-grow tw-pr-4 coupon-card-inner-container">
                                                    <a id="testingId3" dusk="coupon-card-label" href="/go/3575436"
                                                       onclick="gtag_report_conversion('/go/3575436')"
                                                       @click.stop.prevent="openCouponPage('label')"
                                                       class="tw-text-grey-darker sm:tw-text-xl tw-text-lg tw-font-medium hover:tw-text-blue lato"
                                                       rel="nofollow">15% off on most eufy security items &amp; robot
                                                        vacuums
                                                    </a>
                                                    <div class="sm:tw-leading-loose tw-leading-normal">
                                                        <p class="tw-text-grey sm:tw-text-base tw-text-xs lato">Expires
                                                            Mar 30</p>
                                                        <span class="tw-text-orange  sm:tw-border sm:tw-border-orange  sm:tw-text-orange  sm:tw-text-base tw-text-xs lato sm:tw-rounded sm:tw-px-2 sm:tw-py-1">Featured</span>
                                                        <div @click.stop="toggleCouponDetails()"
                                                             class="tw-relative tw-hidden lg:tw-block tw-text-grey-darker tw-font-light tw-cursor-pointer">
                                                            <span>Details: <i
                                                                        :class="[showDetails ? 'fa-caret-down' : 'fa-caret-right', 'fa']"></i></span>
                                                            <p ref="details" v-if="showDetails"
                                                               class="tw-absolute tw-border tw-rounded-sm tw-bg-white tw-text-sm tw-py-2 tw-px-4 tw-w-64 tw-z-20"
                                                               v-cloak>excluding accessories and new releases</p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div @click.stop="openCouponPage('cta')"
                                                     onclick="gtag_report_conversion('/go/3575436')"
                                                     class="tw-hidden md:tw-flex tw-items-start tw-whitespace-no-wrap tw-min-w-1/4">
                                                    <div class="tw-relative tw-rounded-sm tw-overflow-hidden tw-cursor-pointer tw-w-full lato"
                                                         rel="nofollow">
                                                        <div class="tw-bg-grey-lighter tw-border tw-text-grey-dark tw-text-right tw-w-full tw-p-2 ">
                                                            EUFY15
                                                        </div>
                                                        <div class="tw-absolute tw-pin-t tw-pin-l tw-bg-blue hover:tw-bg-blue-dark tw-border tw-border-blue tw-text-center tw-text-white tw-min-w-5/6 tw-p-2">
                                                            Show Code
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </coupon-card>
                            <coupon-card
                                    :coupon="{&quot;url&quot;:&quot;\/go\/3437600&quot;,&quot;modal_url&quot;:&quot;\/stores\/sodastream-promo-codes\/?_c=3437600&quot;}"
                                    :position="24" inline-template>
                                <div dusk="coupon-card" @click="openCouponPage('card')" class="tw-cursor-auto">
                                    <!--  xl:tw-h-full -->
                                    <div class="tw-rounded-sm tw-shadow tw-bg-white sm:tw-p-4 tw-p-2 sm:tw-mb-4 tw-mb-2 xl:tw-h-full">
                                        <div class="tw-flex lato tw-h-full">
                                            <div @click.stop
                                                 class="tw-text-center xl:tw-w-1/6 tw-w-1/4 xl:tw-min-w-1/6 tw-min-w-1/4">
                                                <a href="https://couponcause.com/stores/sodastream-promo-codes/"
                                                   title="SodaStream Coupons and Promo Codes"
                                                   class="card-logo tw-flex tw-justify-center tw-items-center tw-h-full">
                                                    <img class="sized-img"
                                                         src="https://cdn.couponcause.com/logos/180x110/sodastream-promo-codes-180x110-1718900966.webp"
                                                         alt="SodaStream Coupons and Promo Codes" width="180"
                                                         height="110" loading="lazy">
                                                </a>
                                            </div>
                                            <div class=" tw-flex tw-leading-tight tw-w-5/6 sm:tw-ml-6 tw-ml-4">
                                                <div class="tw-flex-grow tw-pr-4 coupon-card-inner-container">
                                                    <a id="testingId3" dusk="coupon-card-label" href="/go/3437600"
                                                       onclick="gtag_report_conversion('/go/3437600')"
                                                       @click.stop.prevent="openCouponPage('label')"
                                                       class="tw-text-grey-darker sm:tw-text-xl tw-text-lg tw-font-medium hover:tw-text-blue lato"
                                                       rel="nofollow">10% Off Sitewide
                                                    </a>
                                                    <div class="sm:tw-leading-loose tw-leading-normal">
                                                        <p class="tw-text-grey sm:tw-text-base tw-text-xs lato">Ongoing
                                                            Offer</p>
                                                        <span class="tw-text-green sm:tw-border sm:tw-border-green sm:tw-text-green sm:tw-text-base tw-text-xs lato sm:tw-rounded sm:tw-px-2 sm:tw-py-1">Verified</span>
                                                        <div @click.stop="toggleCouponDetails()"
                                                             class="tw-relative tw-hidden lg:tw-block tw-text-grey-darker tw-font-light tw-cursor-pointer">
                                                            <span>Details: <i
                                                                        :class="[showDetails ? 'fa-caret-down' : 'fa-caret-right', 'fa']"></i></span>
                                                            <p ref="details" v-if="showDetails"
                                                               class="tw-absolute tw-border tw-rounded-sm tw-bg-white tw-text-sm tw-py-2 tw-px-4 tw-w-64 tw-z-20"
                                                               v-cloak>Click &quot;Show Coupon Code&quot; To Activate
                                                                This Deal. Exclusions May Apply</p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div @click.stop="openCouponPage('cta')"
                                                     onclick="gtag_report_conversion('/go/3437600')"
                                                     class="tw-hidden md:tw-flex tw-items-start tw-whitespace-no-wrap tw-min-w-1/4">
                                                    <div class="tw-relative tw-rounded-sm tw-overflow-hidden tw-cursor-pointer tw-w-full lato"
                                                         rel="nofollow">
                                                        <div class="tw-bg-grey-lighter tw-border tw-text-grey-dark tw-text-right tw-w-full tw-p-2 ">
                                                            WELCOME10
                                                        </div>
                                                        <div class="tw-absolute tw-pin-t tw-pin-l tw-bg-blue hover:tw-bg-blue-dark tw-border tw-border-blue tw-text-center tw-text-white tw-min-w-5/6 tw-p-2">
                                                            Show Code
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </coupon-card>
                            <coupon-card
                                    :coupon="{&quot;url&quot;:&quot;\/go\/3589447&quot;,&quot;modal_url&quot;:&quot;\/stores\/kitchenaid\/?_c=3589447&quot;}"
                                    :position="25" inline-template>
                                <div dusk="coupon-card" @click="openCouponPage('card')" class="tw-cursor-auto">
                                    <!--  xl:tw-h-full -->
                                    <div class="tw-rounded-sm tw-shadow tw-bg-white sm:tw-p-4 tw-p-2 sm:tw-mb-4 tw-mb-2 xl:tw-h-full">
                                        <div class="tw-flex lato tw-h-full">
                                            <div @click.stop
                                                 class="tw-text-center xl:tw-w-1/6 tw-w-1/4 xl:tw-min-w-1/6 tw-min-w-1/4">
                                                <a href="https://couponcause.com/stores/kitchenaid/"
                                                   title="KitchenAid Coupons and Promo Codes"
                                                   class="card-logo tw-flex tw-justify-center tw-items-center tw-h-full">
                                                    <img class="sized-img"
                                                         src="https://cdn.couponcause.com/logos/180x110/kitchenaid-180x110-1510637426.webp"
                                                         alt="KitchenAid Coupons and Promo Codes" width="180"
                                                         height="110" loading="lazy">
                                                </a>
                                            </div>
                                            <div class=" tw-flex tw-leading-tight tw-w-5/6 sm:tw-ml-6 tw-ml-4">
                                                <div class="tw-flex-grow tw-pr-4 coupon-card-inner-container">
                                                    <a id="testingId3" dusk="coupon-card-label" href="/go/3589447"
                                                       onclick="gtag_report_conversion('/go/3589447')"
                                                       @click.stop.prevent="openCouponPage('label')"
                                                       class="tw-text-grey-darker sm:tw-text-xl tw-text-lg tw-font-medium hover:tw-text-blue lato"
                                                       rel="nofollow">Save An Additional 10% Off On Select Appliances
                                                    </a>
                                                    <div class="sm:tw-leading-loose tw-leading-normal">
                                                        <p class="tw-text-grey sm:tw-text-base tw-text-xs lato">Expires
                                                            Mar 12</p>
                                                        <span class="tw-text-green sm:tw-border sm:tw-border-green sm:tw-text-green sm:tw-text-base tw-text-xs lato sm:tw-rounded sm:tw-px-2 sm:tw-py-1">Verified</span>
                                                        <span class="tw-text-orange  sm:tw-border sm:tw-border-orange  sm:tw-text-orange  sm:tw-text-base tw-text-xs lato sm:tw-rounded sm:tw-px-2 sm:tw-py-1">Featured</span>
                                                        <div @click.stop="toggleCouponDetails()"
                                                             class="tw-relative tw-hidden lg:tw-block tw-text-grey-darker tw-font-light tw-cursor-pointer">
                                                            <span>Details: <i
                                                                        :class="[showDetails ? 'fa-caret-down' : 'fa-caret-right', 'fa']"></i></span>
                                                            <p ref="details" v-if="showDetails"
                                                               class="tw-absolute tw-border tw-rounded-sm tw-bg-white tw-text-sm tw-py-2 tw-px-4 tw-w-64 tw-z-20"
                                                               v-cloak>Save an additional 10% off on select appliances
                                                                with code ENJOY10.
                                                                *Ends March 12, 2025 at 11:59 PM EST. Excludes ground
                                                                shipped products and express delivery. Discount taken
                                                                off sale and regular price excluding taxes, delivery,
                                                                install/uninstall and haul-away. Only valid for new
                                                                orders on Maytag.com. Offer subject to change. No cash
                                                                value. While supplies last.</p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div @click.stop="openCouponPage('cta')"
                                                     onclick="gtag_report_conversion('/go/3589447')"
                                                     class="tw-hidden md:tw-flex tw-items-start tw-whitespace-no-wrap tw-min-w-1/4">
                                                    <div class="tw-relative tw-rounded-sm tw-overflow-hidden tw-cursor-pointer tw-w-full lato"
                                                         rel="nofollow">
                                                        <div class="tw-bg-grey-lighter tw-border tw-text-grey-dark tw-text-right tw-w-full tw-p-2 ">
                                                            ENJOY10
                                                        </div>
                                                        <div class="tw-absolute tw-pin-t tw-pin-l tw-bg-blue hover:tw-bg-blue-dark tw-border tw-border-blue tw-text-center tw-text-white tw-min-w-5/6 tw-p-2">
                                                            Show Code
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </coupon-card>
                            <coupon-card
                                    :coupon="{&quot;url&quot;:&quot;\/go\/3564851&quot;,&quot;modal_url&quot;:&quot;\/stores\/urban-stems-promo-codes\/?_c=3564851&quot;}"
                                    :position="26" inline-template>
                                <div dusk="coupon-card" @click="openCouponPage('card')" class="tw-cursor-auto">
                                    <!--  xl:tw-h-full -->
                                    <div class="tw-rounded-sm tw-shadow tw-bg-white sm:tw-p-4 tw-p-2 sm:tw-mb-4 tw-mb-2 xl:tw-h-full">
                                        <div class="tw-flex lato tw-h-full">
                                            <div @click.stop
                                                 class="tw-text-center xl:tw-w-1/6 tw-w-1/4 xl:tw-min-w-1/6 tw-min-w-1/4">
                                                <a href="https://couponcause.com/stores/urban-stems-promo-codes/"
                                                   title="UrbanStems Coupons and Promo Codes"
                                                   class="card-logo tw-flex tw-justify-center tw-items-center tw-h-full">
                                                    <img class="sized-img"
                                                         src="https://cdn.couponcause.com/logos/180x110/urban-stems-promo-codes-180x110-1544641081.webp"
                                                         alt="UrbanStems Coupons and Promo Codes" width="180"
                                                         height="110" loading="lazy">
                                                </a>
                                            </div>
                                            <div class=" tw-flex tw-leading-tight tw-w-5/6 sm:tw-ml-6 tw-ml-4">
                                                <div class="tw-flex-grow tw-pr-4 coupon-card-inner-container">
                                                    <a id="testingId3" dusk="coupon-card-label" href="/go/3564851"
                                                       onclick="gtag_report_conversion('/go/3564851')"
                                                       @click.stop.prevent="openCouponPage('label')"
                                                       class="tw-text-grey-darker sm:tw-text-xl tw-text-lg tw-font-medium hover:tw-text-blue lato"
                                                       rel="nofollow">*Exclusive* - 15% Off Sitewide
                                                    </a>
                                                    <div class="sm:tw-leading-loose tw-leading-normal">
                                                        <p class="tw-text-grey sm:tw-text-base tw-text-xs lato">Expires
                                                            Dec 31</p>
                                                        <span class="tw-text-green sm:tw-border sm:tw-border-green sm:tw-text-green sm:tw-text-base tw-text-xs lato sm:tw-rounded sm:tw-px-2 sm:tw-py-1">Verified</span>
                                                        <span class="tw-text-blue sm:tw-border sm:tw-border-blue sm:tw-text-blue sm:tw-text-base tw-text-xs lato sm:tw-rounded sm:tw-px-2 sm:tw-py-1">Exclusive</span>
                                                        <span class="tw-text-orange  sm:tw-border sm:tw-border-orange  sm:tw-text-orange  sm:tw-text-base tw-text-xs lato sm:tw-rounded sm:tw-px-2 sm:tw-py-1">Featured</span>
                                                        <div @click.stop="toggleCouponDetails()"
                                                             class="tw-relative tw-hidden lg:tw-block tw-text-grey-darker tw-font-light tw-cursor-pointer">
                                                            <span>Details: <i
                                                                        :class="[showDetails ? 'fa-caret-down' : 'fa-caret-right', 'fa']"></i></span>
                                                            <p ref="details" v-if="showDetails"
                                                               class="tw-absolute tw-border tw-rounded-sm tw-bg-white tw-text-sm tw-py-2 tw-px-4 tw-w-64 tw-z-20"
                                                               v-cloak>Excludes subscriptions</p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div @click.stop="openCouponPage('cta')"
                                                     onclick="gtag_report_conversion('/go/3564851')"
                                                     class="tw-hidden md:tw-flex tw-items-start tw-whitespace-no-wrap tw-min-w-1/4">
                                                    <div class="tw-relative tw-rounded-sm tw-overflow-hidden tw-cursor-pointer tw-w-full lato"
                                                         rel="nofollow">
                                                        <div class="tw-bg-grey-lighter tw-border tw-text-grey-dark tw-text-right tw-w-full tw-p-2 ">
                                                            CCAUSE15
                                                        </div>
                                                        <div class="tw-absolute tw-pin-t tw-pin-l tw-bg-blue hover:tw-bg-blue-dark tw-border tw-border-blue tw-text-center tw-text-white tw-min-w-5/6 tw-p-2">
                                                            Show Code
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </coupon-card>
                            <coupon-card
                                    :coupon="{&quot;url&quot;:&quot;\/go\/2696858&quot;,&quot;modal_url&quot;:&quot;\/stores\/siriusxm\/?_c=2696858&quot;}"
                                    :position="27" inline-template>
                                <div dusk="coupon-card" @click="openCouponPage('card')" class="tw-cursor-auto">
                                    <!--  xl:tw-h-full -->
                                    <div class="tw-rounded-sm tw-shadow tw-bg-white sm:tw-p-4 tw-p-2 sm:tw-mb-4 tw-mb-2 xl:tw-h-full">
                                        <div class="tw-flex lato tw-h-full">
                                            <div @click.stop
                                                 class="tw-text-center xl:tw-w-1/6 tw-w-1/4 xl:tw-min-w-1/6 tw-min-w-1/4">
                                                <a href="https://couponcause.com/stores/siriusxm/"
                                                   title="SiriusXM Coupons and Promo Codes"
                                                   class="card-logo tw-flex tw-justify-center tw-items-center tw-h-full">
                                                    <img class="sized-img"
                                                         src="https://cdn.couponcause.com/logos/180x110/siriusxm_logo.webp"
                                                         alt="SiriusXM Coupons and Promo Codes" width="180" height="110"
                                                         loading="lazy">
                                                </a>
                                            </div>
                                            <div class=" tw-flex tw-leading-tight tw-w-5/6 sm:tw-ml-6 tw-ml-4">
                                                <div class="tw-flex-grow tw-pr-4 coupon-card-inner-container">
                                                    <a id="testingId3" dusk="coupon-card-label" href="/go/2696858"
                                                       onclick="gtag_report_conversion('/go/2696858')"
                                                       @click.stop.prevent="openCouponPage('label')"
                                                       class="tw-text-grey-darker sm:tw-text-xl tw-text-lg tw-font-medium hover:tw-text-blue lato"
                                                       rel="nofollow">12 Months for $4.99/Month
                                                    </a>
                                                    <div class="sm:tw-leading-loose tw-leading-normal">
                                                        <p class="tw-text-grey sm:tw-text-base tw-text-xs lato">Ongoing
                                                            Offer</p>
                                                        <div @click.stop="toggleCouponDetails()"
                                                             class="tw-relative tw-hidden lg:tw-block tw-text-grey-darker tw-font-light tw-cursor-pointer">
                                                            <span>Details: <i
                                                                        :class="[showDetails ? 'fa-caret-down' : 'fa-caret-right', 'fa']"></i></span>
                                                            <p ref="details" v-if="showDetails"
                                                               class="tw-absolute tw-border tw-rounded-sm tw-bg-white tw-text-sm tw-py-2 tw-px-4 tw-w-64 tw-z-20"
                                                               v-cloak>No Promo Code Needed. Click &quot;Get Offer&quot;
                                                                To Activate This Deal. Exclusions May Apply</p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div @click.stop="openCouponPage('cta')"
                                                     onclick="gtag_report_conversion('/go/2696858')"
                                                     class="tw-hidden md:tw-flex tw-items-start tw-whitespace-no-wrap tw-min-w-1/4">
                                                    <div class="tw-relative tw-rounded-sm tw-overflow-hidden tw-cursor-pointer tw-w-full lato"
                                                         rel="nofollow">
                                                        <div class="tw-bg-blue hover:tw-bg-blue-dark tw-text-center tw-text-white tw-py-2 tw-px-4">
                                                            Get Offer
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </coupon-card>
                            <coupon-card
                                    :coupon="{&quot;url&quot;:&quot;\/go\/3395412&quot;,&quot;modal_url&quot;:&quot;\/stores\/lenscrafters-promo-codes\/?_c=3395412&quot;}"
                                    :position="28" inline-template>
                                <div dusk="coupon-card" @click="openCouponPage('card')" class="tw-cursor-auto">
                                    <!--  xl:tw-h-full -->
                                    <div class="tw-rounded-sm tw-shadow tw-bg-white sm:tw-p-4 tw-p-2 sm:tw-mb-4 tw-mb-2 xl:tw-h-full">
                                        <div class="tw-flex lato tw-h-full">
                                            <div @click.stop
                                                 class="tw-text-center xl:tw-w-1/6 tw-w-1/4 xl:tw-min-w-1/6 tw-min-w-1/4">
                                                <a href="https://couponcause.com/stores/lenscrafters-promo-codes/"
                                                   title="LensCrafters Coupons and Promo Codes"
                                                   class="card-logo tw-flex tw-justify-center tw-items-center tw-h-full">
                                                    <img class="sized-img"
                                                         src="https://cdn.couponcause.com/logos/180x110/lenscrafters-promo-codes-180x110-1703191224.webp"
                                                         alt="LensCrafters Coupons and Promo Codes" width="180"
                                                         height="110" loading="lazy">
                                                </a>
                                            </div>
                                            <div class=" tw-flex tw-leading-tight tw-w-5/6 sm:tw-ml-6 tw-ml-4">
                                                <div class="tw-flex-grow tw-pr-4 coupon-card-inner-container">
                                                    <a id="testingId3" dusk="coupon-card-label" href="/go/3395412"
                                                       onclick="gtag_report_conversion('/go/3395412')"
                                                       @click.stop.prevent="openCouponPage('label')"
                                                       class="tw-text-grey-darker sm:tw-text-xl tw-text-lg tw-font-medium hover:tw-text-blue lato"
                                                       rel="nofollow">$10 Off Any Order
                                                    </a>
                                                    <div class="sm:tw-leading-loose tw-leading-normal">
                                                        <p class="tw-text-grey sm:tw-text-base tw-text-xs lato">Ongoing
                                                            Offer</p>
                                                        <span class="tw-text-green sm:tw-border sm:tw-border-green sm:tw-text-green sm:tw-text-base tw-text-xs lato sm:tw-rounded sm:tw-px-2 sm:tw-py-1">Verified</span>
                                                        <div @click.stop="toggleCouponDetails()"
                                                             class="tw-relative tw-hidden lg:tw-block tw-text-grey-darker tw-font-light tw-cursor-pointer">
                                                            <span>Details: <i
                                                                        :class="[showDetails ? 'fa-caret-down' : 'fa-caret-right', 'fa']"></i></span>
                                                            <p ref="details" v-if="showDetails"
                                                               class="tw-absolute tw-border tw-rounded-sm tw-bg-white tw-text-sm tw-py-2 tw-px-4 tw-w-64 tw-z-20"
                                                               v-cloak>Click &quot;Show Coupon Code&quot; To Activate
                                                                This Deal. Exclusions May Apply</p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div @click.stop="openCouponPage('cta')"
                                                     onclick="gtag_report_conversion('/go/3395412')"
                                                     class="tw-hidden md:tw-flex tw-items-start tw-whitespace-no-wrap tw-min-w-1/4">
                                                    <div class="tw-relative tw-rounded-sm tw-overflow-hidden tw-cursor-pointer tw-w-full lato"
                                                         rel="nofollow">
                                                        <div class="tw-bg-grey-lighter tw-border tw-text-grey-dark tw-text-right tw-w-full tw-p-2 ">
                                                            OPTHY10
                                                        </div>
                                                        <div class="tw-absolute tw-pin-t tw-pin-l tw-bg-blue hover:tw-bg-blue-dark tw-border tw-border-blue tw-text-center tw-text-white tw-min-w-5/6 tw-p-2">
                                                            Show Code
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </coupon-card>
                            <coupon-card
                                    :coupon="{&quot;url&quot;:&quot;\/go\/2928088&quot;,&quot;modal_url&quot;:&quot;\/stores\/temu-promo-codes\/?_c=2928088&quot;}"
                                    :position="29" inline-template>
                                <div dusk="coupon-card" @click="openCouponPage('card')" class="tw-cursor-auto">
                                    <!--  xl:tw-h-full -->
                                    <div class="tw-rounded-sm tw-shadow tw-bg-white sm:tw-p-4 tw-p-2 sm:tw-mb-4 tw-mb-2 xl:tw-h-full">
                                        <div class="tw-flex lato tw-h-full">
                                            <div @click.stop
                                                 class="tw-text-center xl:tw-w-1/6 tw-w-1/4 xl:tw-min-w-1/6 tw-min-w-1/4">
                                                <a href="https://couponcause.com/stores/temu-promo-codes/"
                                                   title="Temu Coupons and Promo Codes"
                                                   class="card-logo tw-flex tw-justify-center tw-items-center tw-h-full">
                                                    <img class="sized-img"
                                                         src="https://cdn.couponcause.com/logos/180x110/temu-promo-codes-180x110-1676405114.webp"
                                                         alt="Temu Coupons and Promo Codes" width="180" height="110"
                                                         loading="lazy">
                                                </a>
                                            </div>
                                            <div class=" tw-flex tw-leading-tight tw-w-5/6 sm:tw-ml-6 tw-ml-4">
                                                <div class="tw-flex-grow tw-pr-4 coupon-card-inner-container">
                                                    <a id="testingId3" dusk="coupon-card-label" href="/go/2928088"
                                                       onclick="gtag_report_conversion('/go/2928088')"
                                                       @click.stop.prevent="openCouponPage('label')"
                                                       class="tw-text-grey-darker sm:tw-text-xl tw-text-lg tw-font-medium hover:tw-text-blue lato"
                                                       rel="nofollow">30% Off First Order of $39+
                                                    </a>
                                                    <div class="sm:tw-leading-loose tw-leading-normal">
                                                        <p class="tw-text-grey sm:tw-text-base tw-text-xs lato">Ongoing
                                                            Offer</p>
                                                        <span class="tw-text-green sm:tw-border sm:tw-border-green sm:tw-text-green sm:tw-text-base tw-text-xs lato sm:tw-rounded sm:tw-px-2 sm:tw-py-1">Verified</span>
                                                        <span class="tw-text-orange  sm:tw-border sm:tw-border-orange  sm:tw-text-orange  sm:tw-text-base tw-text-xs lato sm:tw-rounded sm:tw-px-2 sm:tw-py-1">Featured</span>
                                                        <div @click.stop="toggleCouponDetails()"
                                                             class="tw-relative tw-hidden lg:tw-block tw-text-grey-darker tw-font-light tw-cursor-pointer">
                                                            <span>Details: <i
                                                                        :class="[showDetails ? 'fa-caret-down' : 'fa-caret-right', 'fa']"></i></span>
                                                            <p ref="details" v-if="showDetails"
                                                               class="tw-absolute tw-border tw-rounded-sm tw-bg-white tw-text-sm tw-py-2 tw-px-4 tw-w-64 tw-z-20"
                                                               v-cloak>Click &quot;Show Coupon Code&quot; To Activate
                                                                This Deal. Exclusions May Apply</p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div @click.stop="openCouponPage('cta')"
                                                     onclick="gtag_report_conversion('/go/2928088')"
                                                     class="tw-hidden md:tw-flex tw-items-start tw-whitespace-no-wrap tw-min-w-1/4">
                                                    <div class="tw-relative tw-rounded-sm tw-overflow-hidden tw-cursor-pointer tw-w-full lato"
                                                         rel="nofollow">
                                                        <div class="tw-bg-grey-lighter tw-border tw-text-grey-dark tw-text-right tw-w-full tw-p-2 ">
                                                            NEWYEAR10
                                                        </div>
                                                        <div class="tw-absolute tw-pin-t tw-pin-l tw-bg-blue hover:tw-bg-blue-dark tw-border tw-border-blue tw-text-center tw-text-white tw-min-w-5/6 tw-p-2">
                                                            Show Code
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </coupon-card>
                            <coupon-card
                                    :coupon="{&quot;url&quot;:&quot;\/go\/3587868&quot;,&quot;modal_url&quot;:&quot;\/stores\/hoka-promo-codes\/?_c=3587868&quot;}"
                                    :position="30" inline-template>
                                <div dusk="coupon-card" @click="openCouponPage('card')" class="tw-cursor-auto">
                                    <!--  xl:tw-h-full -->
                                    <div class="tw-rounded-sm tw-shadow tw-bg-white sm:tw-p-4 tw-p-2 sm:tw-mb-4 tw-mb-2 xl:tw-h-full">
                                        <div class="tw-flex lato tw-h-full">
                                            <div @click.stop
                                                 class="tw-text-center xl:tw-w-1/6 tw-w-1/4 xl:tw-min-w-1/6 tw-min-w-1/4">
                                                <a href="https://couponcause.com/stores/hoka-promo-codes/"
                                                   title="Hoka Coupons and Promo Codes"
                                                   class="card-logo tw-flex tw-justify-center tw-items-center tw-h-full">
                                                    <img class="sized-img"
                                                         src="https://cdn.couponcause.com/logos/180x110/hoka-one-one-promo-codes-180x110-1712072969.webp"
                                                         alt="Hoka Coupons and Promo Codes" width="180" height="110"
                                                         loading="lazy">
                                                </a>
                                            </div>
                                            <div class=" tw-flex tw-leading-tight tw-w-5/6 sm:tw-ml-6 tw-ml-4">
                                                <div class="tw-flex-grow tw-pr-4 coupon-card-inner-container">
                                                    <a id="testingId3" dusk="coupon-card-label" href="/go/3587868"
                                                       onclick="gtag_report_conversion('/go/3587868')"
                                                       @click.stop.prevent="openCouponPage('label')"
                                                       class="tw-text-grey-darker sm:tw-text-xl tw-text-lg tw-font-medium hover:tw-text-blue lato"
                                                       rel="nofollow">Our fan favorite Clifton 9 is now on sale. Get it
                                                        while you can.
                                                    </a>
                                                    <div class="sm:tw-leading-loose tw-leading-normal">
                                                        <p class="tw-text-grey sm:tw-text-base tw-text-xs lato">Expires
                                                            Mar 31</p>
                                                        <div @click.stop="toggleCouponDetails()"
                                                             class="tw-relative tw-hidden lg:tw-block tw-text-grey-darker tw-font-light tw-cursor-pointer">
                                                            <span>Details: <i
                                                                        :class="[showDetails ? 'fa-caret-down' : 'fa-caret-right', 'fa']"></i></span>
                                                            <p ref="details" v-if="showDetails"
                                                               class="tw-absolute tw-border tw-rounded-sm tw-bg-white tw-text-sm tw-py-2 tw-px-4 tw-w-64 tw-z-20"
                                                               v-cloak>No Promo Code Needed. Click &quot;Get Offer&quot;
                                                                To Activate This Deal. Exclusions May Apply</p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div @click.stop="openCouponPage('cta')"
                                                     onclick="gtag_report_conversion('/go/3587868')"
                                                     class="tw-hidden md:tw-flex tw-items-start tw-whitespace-no-wrap tw-min-w-1/4">
                                                    <div class="tw-relative tw-rounded-sm tw-overflow-hidden tw-cursor-pointer tw-w-full lato"
                                                         rel="nofollow">
                                                        <div class="tw-bg-blue hover:tw-bg-blue-dark tw-text-center tw-text-white tw-py-2 tw-px-4">
                                                            Get Offer
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </coupon-card>
                            <coupon-card
                                    :coupon="{&quot;url&quot;:&quot;\/go\/1327048&quot;,&quot;modal_url&quot;:&quot;\/stores\/bitdefender\/?_c=1327048&quot;}"
                                    :position="31" inline-template>
                                <div dusk="coupon-card" @click="openCouponPage('card')" class="tw-cursor-auto">
                                    <!--  xl:tw-h-full -->
                                    <div class="tw-rounded-sm tw-shadow tw-bg-white sm:tw-p-4 tw-p-2 sm:tw-mb-4 tw-mb-2 xl:tw-h-full">
                                        <div class="tw-flex lato tw-h-full">
                                            <div @click.stop
                                                 class="tw-text-center xl:tw-w-1/6 tw-w-1/4 xl:tw-min-w-1/6 tw-min-w-1/4">
                                                <a href="https://couponcause.com/stores/bitdefender/"
                                                   title="BitDefender Coupons and Promo Codes"
                                                   class="card-logo tw-flex tw-justify-center tw-items-center tw-h-full">
                                                    <img class="sized-img"
                                                         src="https://cdn.couponcause.com/logos/180x110/bitdefender-180x110-1508911267.webp"
                                                         alt="BitDefender Coupons and Promo Codes" width="180"
                                                         height="110" loading="lazy">
                                                </a>
                                            </div>
                                            <div class=" tw-flex tw-leading-tight tw-w-5/6 sm:tw-ml-6 tw-ml-4">
                                                <div class="tw-flex-grow tw-pr-4 coupon-card-inner-container">
                                                    <a id="testingId3" dusk="coupon-card-label" href="/go/1327048"
                                                       onclick="gtag_report_conversion('/go/1327048')"
                                                       @click.stop.prevent="openCouponPage('label')"
                                                       class="tw-text-grey-darker sm:tw-text-xl tw-text-lg tw-font-medium hover:tw-text-blue lato"
                                                       rel="nofollow">Up to 60% Off Exclusive BitDefender Deals
                                                    </a>
                                                    <div class="sm:tw-leading-loose tw-leading-normal">
                                                        <p class="tw-text-grey sm:tw-text-base tw-text-xs lato">Ongoing
                                                            Offer</p>
                                                        <span class="tw-text-green sm:tw-border sm:tw-border-green sm:tw-text-green sm:tw-text-base tw-text-xs lato sm:tw-rounded sm:tw-px-2 sm:tw-py-1">Verified</span>
                                                        <span class="tw-text-orange  sm:tw-border sm:tw-border-orange  sm:tw-text-orange  sm:tw-text-base tw-text-xs lato sm:tw-rounded sm:tw-px-2 sm:tw-py-1">Featured</span>
                                                        <div @click.stop="toggleCouponDetails()"
                                                             class="tw-relative tw-hidden lg:tw-block tw-text-grey-darker tw-font-light tw-cursor-pointer">
                                                            <span>Details: <i
                                                                        :class="[showDetails ? 'fa-caret-down' : 'fa-caret-right', 'fa']"></i></span>
                                                            <p ref="details" v-if="showDetails"
                                                               class="tw-absolute tw-border tw-rounded-sm tw-bg-white tw-text-sm tw-py-2 tw-px-4 tw-w-64 tw-z-20"
                                                               v-cloak>Click &quot;Show Coupon Code&quot; To Activate
                                                                This Deal. Exclusions May Apply</p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div @click.stop="openCouponPage('cta')"
                                                     onclick="gtag_report_conversion('/go/1327048')"
                                                     class="tw-hidden md:tw-flex tw-items-start tw-whitespace-no-wrap tw-min-w-1/4">
                                                    <div class="tw-relative tw-rounded-sm tw-overflow-hidden tw-cursor-pointer tw-w-full lato"
                                                         rel="nofollow">
                                                        <div class="tw-bg-grey-lighter tw-border tw-text-grey-dark tw-text-right tw-w-full tw-p-2 ">
                                                            OFFER APPLIED
                                                        </div>
                                                        <div class="tw-absolute tw-pin-t tw-pin-l tw-bg-blue hover:tw-bg-blue-dark tw-border tw-border-blue tw-text-center tw-text-white tw-min-w-5/6 tw-p-2">
                                                            Show Code
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </coupon-card>
                            <coupon-card
                                    :coupon="{&quot;url&quot;:&quot;\/go\/3437212&quot;,&quot;modal_url&quot;:&quot;\/stores\/louisville-slugger-promo-codes\/?_c=3437212&quot;}"
                                    :position="32" inline-template>
                                <div dusk="coupon-card" @click="openCouponPage('card')" class="tw-cursor-auto">
                                    <!--  xl:tw-h-full -->
                                    <div class="tw-rounded-sm tw-shadow tw-bg-white sm:tw-p-4 tw-p-2 sm:tw-mb-4 tw-mb-2 xl:tw-h-full">
                                        <div class="tw-flex lato tw-h-full">
                                            <div @click.stop
                                                 class="tw-text-center xl:tw-w-1/6 tw-w-1/4 xl:tw-min-w-1/6 tw-min-w-1/4">
                                                <a href="https://couponcause.com/stores/louisville-slugger-promo-codes/"
                                                   title="Louisville Slugger  Coupons and Promo Codes"
                                                   class="card-logo tw-flex tw-justify-center tw-items-center tw-h-full">
                                                    <img class="sized-img"
                                                         src="https://cdn.couponcause.com/uploads/120x60-transparent.png"
                                                         alt="Louisville Slugger  Coupons and Promo Codes" width="180"
                                                         height="110" loading="lazy">
                                                </a>
                                            </div>
                                            <div class=" tw-flex tw-leading-tight tw-w-5/6 sm:tw-ml-6 tw-ml-4">
                                                <div class="tw-flex-grow tw-pr-4 coupon-card-inner-container">
                                                    <a id="testingId3" dusk="coupon-card-label" href="/go/3437212"
                                                       onclick="gtag_report_conversion('/go/3437212')"
                                                       @click.stop.prevent="openCouponPage('label')"
                                                       class="tw-text-grey-darker sm:tw-text-xl tw-text-lg tw-font-medium hover:tw-text-blue lato"
                                                       rel="nofollow">Up to 50% off Louisville Slugger Bats and Gear!
                                                    </a>
                                                    <div class="sm:tw-leading-loose tw-leading-normal">
                                                        <p class="tw-text-grey sm:tw-text-base tw-text-xs lato">Ongoing
                                                            Offer</p>
                                                        <div @click.stop="toggleCouponDetails()"
                                                             class="tw-relative tw-hidden lg:tw-block tw-text-grey-darker tw-font-light tw-cursor-pointer">
                                                            <span>Details: <i
                                                                        :class="[showDetails ? 'fa-caret-down' : 'fa-caret-right', 'fa']"></i></span>
                                                            <p ref="details" v-if="showDetails"
                                                               class="tw-absolute tw-border tw-rounded-sm tw-bg-white tw-text-sm tw-py-2 tw-px-4 tw-w-64 tw-z-20"
                                                               v-cloak>No Promo Code Needed. Click &quot;Get Offer&quot;
                                                                To Activate This Deal. Exclusions May Apply</p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div @click.stop="openCouponPage('cta')"
                                                     onclick="gtag_report_conversion('/go/3437212')"
                                                     class="tw-hidden md:tw-flex tw-items-start tw-whitespace-no-wrap tw-min-w-1/4">
                                                    <div class="tw-relative tw-rounded-sm tw-overflow-hidden tw-cursor-pointer tw-w-full lato"
                                                         rel="nofollow">
                                                        <div class="tw-bg-blue hover:tw-bg-blue-dark tw-text-center tw-text-white tw-py-2 tw-px-4">
                                                            Get Offer
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </coupon-card>
                            <coupon-card
                                    :coupon="{&quot;url&quot;:&quot;\/go\/3456196&quot;,&quot;modal_url&quot;:&quot;\/stores\/evoshield-promo-codes\/?_c=3456196&quot;}"
                                    :position="33" inline-template>
                                <div dusk="coupon-card" @click="openCouponPage('card')" class="tw-cursor-auto">
                                    <!--  xl:tw-h-full -->
                                    <div class="tw-rounded-sm tw-shadow tw-bg-white sm:tw-p-4 tw-p-2 sm:tw-mb-4 tw-mb-2 xl:tw-h-full">
                                        <div class="tw-flex lato tw-h-full">
                                            <div @click.stop
                                                 class="tw-text-center xl:tw-w-1/6 tw-w-1/4 xl:tw-min-w-1/6 tw-min-w-1/4">
                                                <a href="https://couponcause.com/stores/evoshield-promo-codes/"
                                                   title="EvoShield  Coupons and Promo Codes"
                                                   class="card-logo tw-flex tw-justify-center tw-items-center tw-h-full">
                                                    <img class="sized-img"
                                                         src="https://cdn.couponcause.com/logos/180x110/evoshield-promo-codes-180x110-1738960234.webp"
                                                         alt="EvoShield  Coupons and Promo Codes" width="180"
                                                         height="110" loading="lazy">
                                                </a>
                                            </div>
                                            <div class=" tw-flex tw-leading-tight tw-w-5/6 sm:tw-ml-6 tw-ml-4">
                                                <div class="tw-flex-grow tw-pr-4 coupon-card-inner-container">
                                                    <a id="testingId3" dusk="coupon-card-label" href="/go/3456196"
                                                       onclick="gtag_report_conversion('/go/3456196')"
                                                       @click.stop.prevent="openCouponPage('label')"
                                                       class="tw-text-grey-darker sm:tw-text-xl tw-text-lg tw-font-medium hover:tw-text-blue lato"
                                                       rel="nofollow">25% Off Select Accessories &amp; Apparel
                                                    </a>
                                                    <div class="sm:tw-leading-loose tw-leading-normal">
                                                        <p class="tw-text-grey sm:tw-text-base tw-text-xs lato">Ongoing
                                                            Offer</p>
                                                        <span class="tw-text-green sm:tw-border sm:tw-border-green sm:tw-text-green sm:tw-text-base tw-text-xs lato sm:tw-rounded sm:tw-px-2 sm:tw-py-1">Verified</span>
                                                        <span class="tw-text-orange  sm:tw-border sm:tw-border-orange  sm:tw-text-orange  sm:tw-text-base tw-text-xs lato sm:tw-rounded sm:tw-px-2 sm:tw-py-1">Featured</span>
                                                        <div @click.stop="toggleCouponDetails()"
                                                             class="tw-relative tw-hidden lg:tw-block tw-text-grey-darker tw-font-light tw-cursor-pointer">
                                                            <span>Details: <i
                                                                        :class="[showDetails ? 'fa-caret-down' : 'fa-caret-right', 'fa']"></i></span>
                                                            <p ref="details" v-if="showDetails"
                                                               class="tw-absolute tw-border tw-rounded-sm tw-bg-white tw-text-sm tw-py-2 tw-px-4 tw-w-64 tw-z-20"
                                                               v-cloak>Click &quot;Show Coupon Code&quot; To Activate
                                                                This Deal. Exclusions May Apply</p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div @click.stop="openCouponPage('cta')"
                                                     onclick="gtag_report_conversion('/go/3456196')"
                                                     class="tw-hidden md:tw-flex tw-items-start tw-whitespace-no-wrap tw-min-w-1/4">
                                                    <div class="tw-relative tw-rounded-sm tw-overflow-hidden tw-cursor-pointer tw-w-full lato"
                                                         rel="nofollow">
                                                        <div class="tw-bg-grey-lighter tw-border tw-text-grey-dark tw-text-right tw-w-full tw-p-2 ">
                                                            EPTXD62
                                                        </div>
                                                        <div class="tw-absolute tw-pin-t tw-pin-l tw-bg-blue hover:tw-bg-blue-dark tw-border tw-border-blue tw-text-center tw-text-white tw-min-w-5/6 tw-p-2">
                                                            Show Code
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </coupon-card>
                            <coupon-card
                                    :coupon="{&quot;url&quot;:&quot;\/go\/2813465&quot;,&quot;modal_url&quot;:&quot;\/stores\/home-depot-coupons\/?_c=2813465&quot;}"
                                    :position="34" inline-template>
                                <div dusk="coupon-card" @click="openCouponPage('card')" class="tw-cursor-auto">
                                    <!--  xl:tw-h-full -->
                                    <div class="tw-rounded-sm tw-shadow tw-bg-white sm:tw-p-4 tw-p-2 sm:tw-mb-4 tw-mb-2 xl:tw-h-full">
                                        <div class="tw-flex lato tw-h-full">
                                            <div @click.stop
                                                 class="tw-text-center xl:tw-w-1/6 tw-w-1/4 xl:tw-min-w-1/6 tw-min-w-1/4">
                                                <a href="https://couponcause.com/stores/home-depot-coupons/"
                                                   title="Home Depot Coupons and Promo Codes"
                                                   class="card-logo tw-flex tw-justify-center tw-items-center tw-h-full">
                                                    <img class="sized-img"
                                                         src="https://cdn.couponcause.com/logos/180x110/home-depot-coupons-180x110-1675457045.webp"
                                                         alt="Home Depot Coupons and Promo Codes" width="180"
                                                         height="110" loading="lazy">
                                                </a>
                                            </div>
                                            <div class=" tw-flex tw-leading-tight tw-w-5/6 sm:tw-ml-6 tw-ml-4">
                                                <div class="tw-flex-grow tw-pr-4 coupon-card-inner-container">
                                                    <a id="testingId3" dusk="coupon-card-label" href="/go/2813465"
                                                       onclick="gtag_report_conversion('/go/2813465')"
                                                       @click.stop.prevent="openCouponPage('label')"
                                                       class="tw-text-grey-darker sm:tw-text-xl tw-text-lg tw-font-medium hover:tw-text-blue lato"
                                                       rel="nofollow">$5 Off Your First Purchase $50+ with Email Signup
                                                    </a>
                                                    <div class="sm:tw-leading-loose tw-leading-normal">
                                                        <p class="tw-text-grey sm:tw-text-base tw-text-xs lato">Ongoing
                                                            Offer</p>
                                                        <span class="tw-text-green sm:tw-border sm:tw-border-green sm:tw-text-green sm:tw-text-base tw-text-xs lato sm:tw-rounded sm:tw-px-2 sm:tw-py-1">Verified</span>
                                                        <span class="tw-text-orange  sm:tw-border sm:tw-border-orange  sm:tw-text-orange  sm:tw-text-base tw-text-xs lato sm:tw-rounded sm:tw-px-2 sm:tw-py-1">Featured</span>
                                                        <div @click.stop="toggleCouponDetails()"
                                                             class="tw-relative tw-hidden lg:tw-block tw-text-grey-darker tw-font-light tw-cursor-pointer">
                                                            <span>Details: <i
                                                                        :class="[showDetails ? 'fa-caret-down' : 'fa-caret-right', 'fa']"></i></span>
                                                            <p ref="details" v-if="showDetails"
                                                               class="tw-absolute tw-border tw-rounded-sm tw-bg-white tw-text-sm tw-py-2 tw-px-4 tw-w-64 tw-z-20"
                                                               v-cloak>At the bottom of the Home Depot homepage is a
                                                                signup form for their newsletter. Enter your information
                                                                and within 5 minutes you will receive an email from Home
                                                                Depot containing your coupon code for $5 off your first
                                                                $50+ order.</p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div @click.stop="openCouponPage('cta')"
                                                     onclick="gtag_report_conversion('/go/2813465')"
                                                     class="tw-hidden md:tw-flex tw-items-start tw-whitespace-no-wrap tw-min-w-1/4">
                                                    <div class="tw-relative tw-rounded-sm tw-overflow-hidden tw-cursor-pointer tw-w-full lato"
                                                         rel="nofollow">
                                                        <div class="tw-bg-blue hover:tw-bg-blue-dark tw-text-center tw-text-white tw-py-2 tw-px-4">
                                                            Get Offer
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </coupon-card>
                            <coupon-card
                                    :coupon="{&quot;url&quot;:&quot;\/go\/3352428&quot;,&quot;modal_url&quot;:&quot;\/stores\/raw-generation\/?_c=3352428&quot;}"
                                    :position="35" inline-template>
                                <div dusk="coupon-card" @click="openCouponPage('card')" class="tw-cursor-auto">
                                    <!--  xl:tw-h-full -->
                                    <div class="tw-rounded-sm tw-shadow tw-bg-white sm:tw-p-4 tw-p-2 sm:tw-mb-4 tw-mb-2 xl:tw-h-full">
                                        <div class="tw-flex lato tw-h-full">
                                            <div @click.stop
                                                 class="tw-text-center xl:tw-w-1/6 tw-w-1/4 xl:tw-min-w-1/6 tw-min-w-1/4">
                                                <a href="https://couponcause.com/stores/raw-generation/"
                                                   title="Raw Generation Coupons and Promo Codes"
                                                   class="card-logo tw-flex tw-justify-center tw-items-center tw-h-full">
                                                    <img class="sized-img"
                                                         src="https://cdn.couponcause.com/logos/180x110/raw-generation-180x110-1710965715.webp"
                                                         alt="Raw Generation Coupons and Promo Codes" width="180"
                                                         height="110" loading="lazy">
                                                </a>
                                            </div>
                                            <div class=" tw-flex tw-leading-tight tw-w-5/6 sm:tw-ml-6 tw-ml-4">
                                                <div class="tw-flex-grow tw-pr-4 coupon-card-inner-container">
                                                    <a id="testingId3" dusk="coupon-card-label" href="/go/3352428"
                                                       onclick="gtag_report_conversion('/go/3352428')"
                                                       @click.stop.prevent="openCouponPage('label')"
                                                       class="tw-text-grey-darker sm:tw-text-xl tw-text-lg tw-font-medium hover:tw-text-blue lato"
                                                       rel="nofollow">*Exclusive* 5% Off Sitewide
                                                    </a>
                                                    <div class="sm:tw-leading-loose tw-leading-normal">
                                                        <p class="tw-text-grey sm:tw-text-base tw-text-xs lato">Ongoing
                                                            Offer</p>
                                                        <div @click.stop="toggleCouponDetails()"
                                                             class="tw-relative tw-hidden lg:tw-block tw-text-grey-darker tw-font-light tw-cursor-pointer">
                                                            <span>Details: <i
                                                                        :class="[showDetails ? 'fa-caret-down' : 'fa-caret-right', 'fa']"></i></span>
                                                            <p ref="details" v-if="showDetails"
                                                               class="tw-absolute tw-border tw-rounded-sm tw-bg-white tw-text-sm tw-py-2 tw-px-4 tw-w-64 tw-z-20"
                                                               v-cloak>Click &quot;Show Coupon Code&quot; To Activate
                                                                This Deal. Exclusions May Apply</p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div @click.stop="openCouponPage('cta')"
                                                     onclick="gtag_report_conversion('/go/3352428')"
                                                     class="tw-hidden md:tw-flex tw-items-start tw-whitespace-no-wrap tw-min-w-1/4">
                                                    <div class="tw-relative tw-rounded-sm tw-overflow-hidden tw-cursor-pointer tw-w-full lato"
                                                         rel="nofollow">
                                                        <div class="tw-bg-grey-lighter tw-border tw-text-grey-dark tw-text-right tw-w-full tw-p-2 ">
                                                            COUPONCAUSES
                                                        </div>
                                                        <div class="tw-absolute tw-pin-t tw-pin-l tw-bg-blue hover:tw-bg-blue-dark tw-border tw-border-blue tw-text-center tw-text-white tw-min-w-5/6 tw-p-2">
                                                            Show Code
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </coupon-card>
                        </div>
                        <!-- END Item Blocks -->
                    </div>
                </div>
                <aside>
                    <div class="col-md-3 col-sm-12 col-xs-12">
                        <div class="sidebar-widget">
                            <div class="widget-content text-center">
                                <h3 class="widget-title">Featured Cause</h3>
                                <a href="https://www.lafoodbank.org/"><img
                                            src="https://cdn.couponcause.com/causes/la-food-bank-logo.webp"
                                            alt="Los Angeles Food Bank" class="img-responsive featured-cause-img"
                                            width="150" height="150" loading="lazy"></a>
                                <div class="widget-overlay">
                                    <p>The LA Food Bank is dedicated to mobilizing resources to fight hunger in the Los
                                        Angeles community. Since it’s creation 40 years ago it has been able to
                                        distribute over 1 billion pounds of foods to people in need. Every $1 donated
                                        provides four meals to the hungry. Every year the LA Food Bank feeds 1 million
                                        people in Los Angeles County.</p>
                                </div>
                            </div>
                        </div>
                        <div class="sidebar-widget">
                            <div class="widget-content text-center">
                                <h3 class="widget-title">About Coupon Cause</h3>
                                <!-- <p>Raised this Month</p>
          <h2 class="fonth1 colorBlue">$0.00</h2> -->
                                <p>Total Raised</p>
                                <h2 class="fonth1 colorBlue">$911,962.67</h2>
                                <p>At Coupon Cause our mission is to provide more than just the largest selection of
                                    daily, verified and updated online coupon codes from top retailers for our users. We
                                    give you the opportunity to also give back and pay the savings forward! A portion of
                                    all company revenue is donated to featured charities such as &quot;The Los Angeles
                                    Regional Food Bank&quot;, Surfrider Foundation and more!</p>
                            </div>
                        </div>
                        <div class="sidebar-widget">
                            <div class="widget-content text-center">
                                <h3 class="widget-title">Top Stores</h3>
                                <div class="row">
                                    <div class="col-md-6 col-sm-6 col-xs-6">
                                        <a href="/stores/samsung-promo-codes/"><img
                                                    src="https://cdn.couponcause.com/logos/88x31/samsung-coupons-logo.webp"
                                                    alt="All Samsung Coupons & Promo Codes"
                                                    class="img-responsive top-stories-logo" width="88" height="31"
                                                    loading="lazy"></a>
                                    </div>
                                    <div class="col-md-6 col-sm-6 col-xs-6">
                                        <a href="/stores/best-buy/"><img
                                                    src="https://cdn.couponcause.com/logos/88x31/best-buy-88x31-1569994185.webp"
                                                    alt="All Best Buy Coupons & Promo Codes"
                                                    class="img-responsive top-stories-logo" width="88" height="31"
                                                    loading="lazy"></a>
                                    </div>
                                    <div class="col-md-6 col-sm-6 col-xs-6">
                                        <a href="/stores/nike/"><img
                                                    src="https://cdn.couponcause.com/logos/88x31/nike-88x31.webp"
                                                    alt="All Nike Coupons & Promo Codes"
                                                    class="img-responsive top-stories-logo" width="88" height="31"
                                                    loading="lazy"></a>
                                    </div>
                                    <div class="col-md-6 col-sm-6 col-xs-6">
                                        <a href="/stores/amazon/"><img
                                                    src="https://cdn.couponcause.com/logos/88x31/amazon-88x31-1506624281.webp"
                                                    alt="All Amazon Coupons & Promo Codes"
                                                    class="img-responsive top-stories-logo" width="88" height="31"
                                                    loading="lazy"></a>
                                    </div>
                                    <div class="col-md-6 col-sm-6 col-xs-6">
                                        <a href="/stores/walmart-coupons/"><img
                                                    src="https://cdn.couponcause.com/logos/88x31/walmart-coupons-logo.webp"
                                                    alt="All Walmart Coupons & Promo Codes"
                                                    class="img-responsive top-stories-logo" width="88" height="31"
                                                    loading="lazy"></a>
                                    </div>
                                    <div class="col-md-6 col-sm-6 col-xs-6">
                                        <a href="/stores/hp-us-promo-codes/"><img
                                                    src="https://cdn.couponcause.com/logos/88x31/hp-88x31-1532732071.webp"
                                                    alt="All HP (US) Coupons & Promo Codes"
                                                    class="img-responsive top-stories-logo" width="88" height="31"
                                                    loading="lazy"></a>
                                    </div>
                                    <div class="col-md-6 col-sm-6 col-xs-6">
                                        <a href="/stores/target/"><img
                                                    src="https://cdn.couponcause.com/logos/88x31/target-88x31-1513265738.webp"
                                                    alt="All Target Coupons & Promo Codes"
                                                    class="img-responsive top-stories-logo" width="88" height="31"
                                                    loading="lazy"></a>
                                    </div>
                                    <div class="col-md-6 col-sm-6 col-xs-6">
                                        <a href="/stores/kitchenaid/"><img
                                                    src="https://cdn.couponcause.com/logos/88x31/kitchenaid-coupons-logo.webp"
                                                    alt="All KitchenAid Coupons & Promo Codes"
                                                    class="img-responsive top-stories-logo" width="88" height="31"
                                                    loading="lazy"></a>
                                    </div>
                                    <div class="col-md-6 col-sm-6 col-xs-6">
                                        <a href="/stores/home-depot-coupons/"><img
                                                    src="https://cdn.couponcause.com/logos/88x31/home-depot-coupons-88x31-1675457027.webp"
                                                    alt="All Home Depot Coupons & Promo Codes"
                                                    class="img-responsive top-stories-logo" width="88" height="31"
                                                    loading="lazy"></a>
                                    </div>
                                    <div class="col-md-6 col-sm-6 col-xs-6">
                                        <a href="/stores/macys/"><img
                                                    src="https://cdn.couponcause.com/logos/88x31/macys-88x31-1510744150.webp"
                                                    alt="All Macy&#039;s Coupons & Promo Codes"
                                                    class="img-responsive top-stories-logo" width="88" height="31"
                                                    loading="lazy"></a>
                                    </div>
                                    <div class="col-md-6 col-sm-6 col-xs-6">
                                        <a href="/stores/bed-bath-beyond-coupons/"><img
                                                    src="https://cdn.couponcause.com/logos/88x31/bed-bath-beyond-coupons-88x31-1508839159.webp"
                                                    alt="All Bed Bath &amp; Beyond Coupons & Promo Codes"
                                                    class="img-responsive top-stories-logo" width="88" height="31"
                                                    loading="lazy"></a>
                                    </div>
                                    <div class="col-md-6 col-sm-6 col-xs-6">
                                        <a href="/stores/chewy-promo-codes/"><img
                                                    src="https://cdn.couponcause.com/logos/88x31/chewy-promo-codes-88x31-1566481433.webp"
                                                    alt="All Chewy Coupons & Promo Codes"
                                                    class="img-responsive top-stories-logo" width="88" height="31"
                                                    loading="lazy"></a>
                                    </div>
                                    <div class="col-md-6 col-sm-6 col-xs-6">
                                        <a href="/stores/sonos-coupons/"><img
                                                    src="https://cdn.couponcause.com/logos/88x31/sonos-coupons-88x31-1513156903.webp"
                                                    alt="All Sonos Coupons & Promo Codes"
                                                    class="img-responsive top-stories-logo" width="88" height="31"
                                                    loading="lazy"></a>
                                    </div>
                                    <div class="col-md-6 col-sm-6 col-xs-6">
                                        <a href="/stores/pandora-promo-codes/"><img
                                                    src="https://cdn.couponcause.com/logos/88x31/prodege-48-10233-88x31-1661443425.webp"
                                                    alt="All Pandora Coupons & Promo Codes"
                                                    class="img-responsive top-stories-logo" width="88" height="31"
                                                    loading="lazy"></a>
                                    </div>
                                    <div class="col-md-6 col-sm-6 col-xs-6">
                                        <a href="/stores/eyebuydirect-promo-codes/"><img
                                                    src="https://cdn.couponcause.com/logos/88x31/eyebuydirect-promo-codes-88x31-1658255727.webp"
                                                    alt="All EyeBuyDirect Coupons & Promo Codes"
                                                    class="img-responsive top-stories-logo" width="88" height="31"
                                                    loading="lazy"></a>
                                    </div>
                                    <div class="col-md-6 col-sm-6 col-xs-6">
                                        <a href="/stores/hoka-promo-codes/"><img
                                                    src="https://cdn.couponcause.com/logos/88x31/hoka-promo-codes-88x31-1731355396.webp"
                                                    alt="All Hoka Coupons & Promo Codes"
                                                    class="img-responsive top-stories-logo" width="88" height="31"
                                                    loading="lazy"></a>
                                    </div>
                                    <div class="col-md-6 col-sm-6 col-xs-6">
                                        <a href="/stores/casetify/"><img
                                                    src="https://cdn.couponcause.com/logos/88x31/casetify-88x31-1588878651.webp"
                                                    alt="All Casetify Coupons & Promo Codes"
                                                    class="img-responsive top-stories-logo" width="88" height="31"
                                                    loading="lazy"></a>
                                    </div>
                                    <div class="col-md-6 col-sm-6 col-xs-6">
                                        <a href="/stores/cheapoair/"><img
                                                    src="https://cdn.couponcause.com/logos/88x31/cheapoair-88x31-1509096210.webp"
                                                    alt="All CheapOair.com Coupons & Promo Codes"
                                                    class="img-responsive top-stories-logo" width="88" height="31"
                                                    loading="lazy"></a>
                                    </div>
                                    <div class="col-md-6 col-sm-6 col-xs-6">
                                        <a href="/stores/urban-stems-promo-codes/"><img
                                                    src="https://cdn.couponcause.com/logos/88x31/urban-stems-promo-codes-88x31-1544641081.webp"
                                                    alt="All UrbanStems Coupons & Promo Codes"
                                                    class="img-responsive top-stories-logo" width="88" height="31"
                                                    loading="lazy"></a>
                                    </div>
                                    <div class="col-md-6 col-sm-6 col-xs-6">
                                        <a href="/stores/lenscrafters-promo-codes/"><img
                                                    src="https://cdn.couponcause.com/logos/88x31/lenscrafters-promo-codes-88x31-1703191213.webp"
                                                    alt="All LensCrafters Coupons & Promo Codes"
                                                    class="img-responsive top-stories-logo" width="88" height="31"
                                                    loading="lazy"></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </aside>
            </div>
        </div>
    </section>
    <section class="container">
        <div class="tw-bg-white tw-mb-8">
            <h3>Popular Posts From Our Blog</h3>
            <hr class="border-line"/>
            <div class="tw-flex tw-flex-wrap">
                @foreach($list_post_popular as $post_popular)
                    <div class="tw-flex tw-flex-col tw-flex-no-shrink tw-justify-between tw-border tw-w-full tw-mb-2 tw-p-2 tw-mr-2 sm:tw-flex-1 sm:tw-w-auto sm:tw-mb-0">
                        <a href="{{ $post_popular->getUrl() }}">
                            <div class="tw-flex tw-items-center tw-bg-black">
                                <img class="sized-img tw-rounded-sm tw-w-full"
                                     src="{{ $post_popular->image }}"
                                     width="800" height="450" loading="lazy">
                            </div>
                            <h5 class="tw-text-xl tw-text-grey-darkest tw-mt-2">
                                {{ $post_popular->name }}
                            </h5>
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
        <div class="popular-stories">
            <h3>New And Trending Stores</h3>
            <hr class="border-line"/>
            <div class="row">
                @foreach(range(1, 18) as $store)
                    <div class="col-md-2 col-sm-2 col-xs-4 stores-item">
                        <a href="#!">
                            <img src="https://cdn.couponcause.com/logos/180x110/samsung-promo-codes-180x110-1518438575.webp"
                                 alt="Pretty Litter Coupons" class="sized-img img-responsive" width="180"
                                 height="110"
                                 loading="lazy">
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
    <section class="happy-hunting-section">
        <div class="container">
            <div class="happy-hunting">
                <h2 class="title">Find The Perfect Deal</h2>
                <p class="sub-heading">
                    Check out curated deals from some of the most popular categories at CouponCause.com!
                </p>
                <div class="col-lg-12 happy-hunting-heading-list">
                    @foreach($all_category_coupons as $category_coupons)
                        <div class="col-md-3 col-sm-6 col-xs-6">
                            <ul class="list-unstyled">
                                @foreach($category_coupons as $category_coupon)
                                    <li>
                                        <a href="{{ $category_coupon['href'] }}">{{ $category_coupon['name'] }}</a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>
    <section>
        <div class="container">
            <div class="row">
                <div class="article">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="stores-pagination">
                            <div class="col-md-2">
                                <h3 class="pagination-title">Browse Stores</h3>
                            </div>
                            <div class="col-md-10">
                                <nav aria-label="Page navigation">
                                    <ul class="pagination">
                                        @foreach(range('A','Z') as $letter)
                                            <li>
                                                <a href="{{ route('store_all') }}?letter={{ $letter }}">{{ $letter }}</a>
                                            </li>
                                        @endforeach
                                        <li>
                                            <a href="{{ route('store_all') }}?letter=0-9">0-9</a>
                                        </li>
                                    </ul>
                                </nav>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('bottom')

@endpush
