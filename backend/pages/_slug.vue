<template>
  <div class="post-page-main">
    <Navbar/>
    <div class="container mx-auto lg:px-16 px-0">
      <div class="grid grid-cols-12 gap-4 lg:px-9 px-4 py-4">
        <!-- Left sidebar -->
        <div class="col-span-12 lg:col-span-9">
          <div class="grid grid-cols-12 gap-4">
            <div class="col-span-12">
              <div class="border-b-2 border-neutral-300">
                <!-- breadcrumbs -->
                <nav class="relative w-full flex flex-wrap items-center justify-between py-4 navbar navbar-expand-lg navbar-light">
                  <div class="container-fluid w-full flex flex-wrap items-center justify-between">
                    <nav class="bg-grey-light rounded-md w-full" aria-label="breadcrumb">
                      <ol class="list-reset flex">
<!--                        {{items}}-->
                        <li v-for="item in items"><a href="" class="text-slate-700 hover:text-gray-600" v-text="item.text"></a>
                          <span class="text-gray-500 mx-2">/</span>
                        </li>
                      </ol>
                    </nav>
                  </div>
                </nav>
                <!-- Details Title -->
                <span v-text="this.$route.params.slug" class="font-normal text-black text-4xl dark:text-gray-400 font-sans">

                            </span>
                <!-- News Author name and place name and date -->
                <span class="flex items-center space-x-4">
                                <div class="flex-shrink-0">
                                    <img class="w-14 h-14 rounded-full" src="https://bn.voicetv.tv/assets/images/logo.png" alt="Neil image">
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="font-normal text-black text-base dark:text-gray-400 line-clamp-2 font-sans">
                                        জেলা প্রতিনিধি, নেত্রকোণা
                                    </p>
                                    <p class="text-sm text-gray-500 truncate dark:text-gray-400 font-sans">
                                        ১০ মে ২০২২, ০৮:৩১ এএম
                                    </p>
                                </div>
                            </span>
                <!-- Socail share -->
                <div class="py-4 mt-4">

                  <button type="button" class="text-white bg-[#3b5998] hover:bg-[#3b5998]/90 focus:ring-4 focus:outline-none focus:ring-[#3b5998]/50 font-medium rounded-lg text-sm px-3 py-1 text-center inline-flex items-center dark:focus:ring-[#3b5998]/55 mr-2 mb-2">
                    <svg class="w-4 h-4 mr-2 -ml-1" aria-hidden="true" focusable="false" data-prefix="fab" data-icon="facebook-f" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512"><path fill="currentColor" d="M279.1 288l14.22-92.66h-88.91v-60.13c0-25.35 12.42-50.06 52.24-50.06h40.42V6.26S260.4 0 225.4 0c-73.22 0-121.1 44.38-121.1 124.7v70.62H22.89V288h81.39v224h100.2V288z"></path></svg>
                    Share
                  </button>
                  <button type="button" class="text-white bg-[#1da1f2] rounded-lg text-sm px-3 py-1 text-center mr-2 mb-2">
                    <svg class="w-4 h-4" aria-hidden="true" focusable="false" data-prefix="fab" data-icon="twitter" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path fill="currentColor" d="M459.4 151.7c.325 4.548 .325 9.097 .325 13.65 0 138.7-105.6 298.6-298.6 298.6-59.45 0-114.7-17.22-161.1-47.11 8.447 .974 16.57 1.299 25.34 1.299 49.06 0 94.21-16.57 130.3-44.83-46.13-.975-84.79-31.19-98.11-72.77 6.498 .974 12.99 1.624 19.82 1.624 9.421 0 18.84-1.3 27.61-3.573-48.08-9.747-84.14-51.98-84.14-102.1v-1.299c13.97 7.797 30.21 12.67 47.43 13.32-28.26-18.84-46.78-51.01-46.78-87.39 0-19.49 5.197-37.36 14.29-52.95 51.65 63.67 129.3 105.3 216.4 109.8-1.624-7.797-2.599-15.92-2.599-24.04 0-57.83 46.78-104.9 104.9-104.9 30.21 0 57.5 12.67 76.67 33.14 23.72-4.548 46.46-13.32 66.6-25.34-7.798 24.37-24.37 44.83-46.13 57.83 21.12-2.273 41.58-8.122 60.43-16.24-14.29 20.79-32.16 39.31-52.63 54.25z"></path></svg>

                  </button>
                  <button type="button" class="text-white bg-slate-50 rounded-lg text-sm px-3 py-1 text-center items-center mr-2 mb-2">
                    <svg xmlns="http://www.w3.org/2000/svg" x="0px" y="0px"
                         width="18" height="18"
                         viewBox="0 0 48 48"
                         style=" fill:#000000;"><path fill="#fff" d="M4.868,43.303l2.694-9.835C5.9,30.59,5.026,27.324,5.027,23.979C5.032,13.514,13.548,5,24.014,5c5.079,0.002,9.845,1.979,13.43,5.566c3.584,3.588,5.558,8.356,5.556,13.428c-0.004,10.465-8.522,18.98-18.986,18.98c-0.001,0,0,0,0,0h-0.008c-3.177-0.001-6.3-0.798-9.073-2.311L4.868,43.303z"></path><path fill="#fff" d="M4.868,43.803c-0.132,0-0.26-0.052-0.355-0.148c-0.125-0.127-0.174-0.312-0.127-0.483l2.639-9.636c-1.636-2.906-2.499-6.206-2.497-9.556C4.532,13.238,13.273,4.5,24.014,4.5c5.21,0.002,10.105,2.031,13.784,5.713c3.679,3.683,5.704,8.577,5.702,13.781c-0.004,10.741-8.746,19.48-19.486,19.48c-3.189-0.001-6.344-0.788-9.144-2.277l-9.875,2.589C4.953,43.798,4.911,43.803,4.868,43.803z"></path><path fill="#cfd8dc" d="M24.014,5c5.079,0.002,9.845,1.979,13.43,5.566c3.584,3.588,5.558,8.356,5.556,13.428c-0.004,10.465-8.522,18.98-18.986,18.98h-0.008c-3.177-0.001-6.3-0.798-9.073-2.311L4.868,43.303l2.694-9.835C5.9,30.59,5.026,27.324,5.027,23.979C5.032,13.514,13.548,5,24.014,5 M24.014,42.974C24.014,42.974,24.014,42.974,24.014,42.974C24.014,42.974,24.014,42.974,24.014,42.974 M24.014,42.974C24.014,42.974,24.014,42.974,24.014,42.974C24.014,42.974,24.014,42.974,24.014,42.974 M24.014,4C24.014,4,24.014,4,24.014,4C12.998,4,4.032,12.962,4.027,23.979c-0.001,3.367,0.849,6.685,2.461,9.622l-2.585,9.439c-0.094,0.345,0.002,0.713,0.254,0.967c0.19,0.192,0.447,0.297,0.711,0.297c0.085,0,0.17-0.011,0.254-0.033l9.687-2.54c2.828,1.468,5.998,2.243,9.197,2.244c11.024,0,19.99-8.963,19.995-19.98c0.002-5.339-2.075-10.359-5.848-14.135C34.378,6.083,29.357,4.002,24.014,4L24.014,4z"></path><path fill="#40c351" d="M35.176,12.832c-2.98-2.982-6.941-4.625-11.157-4.626c-8.704,0-15.783,7.076-15.787,15.774c-0.001,2.981,0.833,5.883,2.413,8.396l0.376,0.597l-1.595,5.821l5.973-1.566l0.577,0.342c2.422,1.438,5.2,2.198,8.032,2.199h0.006c8.698,0,15.777-7.077,15.78-15.776C39.795,19.778,38.156,15.814,35.176,12.832z"></path><path fill="#fff" fill-rule="evenodd" d="M19.268,16.045c-0.355-0.79-0.729-0.806-1.068-0.82c-0.277-0.012-0.593-0.011-0.909-0.011c-0.316,0-0.83,0.119-1.265,0.594c-0.435,0.475-1.661,1.622-1.661,3.956c0,2.334,1.7,4.59,1.937,4.906c0.237,0.316,3.282,5.259,8.104,7.161c4.007,1.58,4.823,1.266,5.693,1.187c0.87-0.079,2.807-1.147,3.202-2.255c0.395-1.108,0.395-2.057,0.277-2.255c-0.119-0.198-0.435-0.316-0.909-0.554s-2.807-1.385-3.242-1.543c-0.435-0.158-0.751-0.237-1.068,0.238c-0.316,0.474-1.225,1.543-1.502,1.859c-0.277,0.317-0.554,0.357-1.028,0.119c-0.474-0.238-2.002-0.738-3.815-2.354c-1.41-1.257-2.362-2.81-2.639-3.285c-0.277-0.474-0.03-0.731,0.208-0.968c0.213-0.213,0.474-0.554,0.712-0.831c0.237-0.277,0.316-0.475,0.474-0.791c0.158-0.317,0.079-0.594-0.04-0.831C20.612,19.329,19.69,16.983,19.268,16.045z" clip-rule="evenodd"></path></svg>
                  </button>
                  <button type="button" class="text-white bg-slate-50 rounded-lg text-sm px-3 py-1 text-center items-center mr-2 mb-2">
                    <svg height="18" viewBox="0 0 1792 1792" width="18" xmlns="http://www.w3.org/2000/svg"><path d="M1664 1632v-1088q0-13-9.5-22.5t-22.5-9.5h-1088q-13 0-22.5 9.5t-9.5 22.5v1088q0 13 9.5 22.5t22.5 9.5h1088q13 0 22.5-9.5t9.5-22.5zm128-1088v1088q0 66-47 113t-113 47h-1088q-66 0-113-47t-47-113v-1088q0-66 47-113t113-47h1088q66 0 113 47t47 113zm-384-384v160h-128v-160q0-13-9.5-22.5t-22.5-9.5h-1088q-13 0-22.5 9.5t-9.5 22.5v1088q0 13 9.5 22.5t22.5 9.5h160v128h-160q-66 0-113-47t-47-113v-1088q0-66 47-113t113-47h1088q66 0 113 47t47 113z"/></svg>
                  </button>
                  <!-- <button type="button" class="text-white bg-[#050708] hover:bg-[#050708]/90 focus:ring-4 focus:outline-none focus:ring-[#050708]/50 font-medium rounded-lg text-sm px-5 py-2.5 text-center inline-flex items-center dark:focus:ring-[#050708]/50 dark:hover:bg-[#050708]/30 mr-2 mb-2">
                      <svg class="w-5 h-5 mr-2 -ml-1" aria-hidden="true" focusable="false" data-prefix="fab" data-icon="apple" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512"><path fill="currentColor" d="M318.7 268.7c-.2-36.7 16.4-64.4 50-84.8-18.8-26.9-47.2-41.7-84.7-44.6-35.5-2.8-74.3 20.7-88.5 20.7-15 0-49.4-19.7-76.4-19.7C63.3 141.2 4 184.8 4 273.5q0 39.3 14.4 81.2c12.8 36.7 59 126.7 107.2 125.2 25.2-.6 43-17.9 75.8-17.9 31.8 0 48.3 17.9 76.4 17.9 48.6-.7 90.4-82.5 102.6-119.3-65.2-30.7-61.7-90-61.7-91.9zm-56.6-164.2c27.3-32.4 24.8-61.9 24-72.5-24.1 1.4-52 16.4-67.9 34.9-17.5 19.8-27.8 44.3-25.6 71.9 26.1 2 49.9-11.4 69.5-34.3z"></path></svg>
                      Sign in with Apple
                  </button> -->

                </div>
                <!-- End -->
              </div>
              <div class="py-4">
                <img src="https://mdbootstrap.com/img/new/slides/041.jpg" class="max-w-full h-auto" alt="..." />
                <figcaption class="font-sans flex items-center">
                  চেরাপুঞ্জি, ডাউকি থেকে। মেঘালয় বাংলাদেশের সীমান্তবর্তী রাজ্য হওয়ায় যাতায়াত ভাড়া, থাকা-খাওয়ার খরচ বেশ কম
                  <span class="text-red-500 mx-2">|</span>
                  <figure class="text-xs">
                    ছবি: পেক্সেলস
                  </figure>
                </figcaption>
                <div class="text-lg font-sans py-2.5">
                  পৃথিবীর সেরা ১০টি আশ্চর্যজনক ও পর্যটন বান্ধব জায়গার মধ্যে গ্রান্ড ক্যানিয়ন একটি।

                  গ্র্যান্ড ক্যানিয়ন এর অবস্থান আমেরিকার উত্তর অ্যারিজোনাতে।

                  গিরিখাতটি ২৭০ মাইল লম্বা, ১৮ মাইল চওড়া এবং এক মাইল পর্যন্ত গভীর ।

                  এটি বিশ্বের বৃহত্তম গিরিখাতগুলির মধ্যে একটি বিজ্ঞানীরা অনুমান করেছেন যে গিরিখাতটি ৫ থেকে ৬ মিলিয়ন বছর আগে তৈরি হয়েছিল যখন কলোরাডো নদী পাথরের স্তরগুলির মধ্য দিয়ে একটি চ্যানেল কাটা শুরু করেছিল।

                  মানুষ শেষ বরফ যুগ থেকে গিরিখাত এবং আশেপাশের অঞ্চলে বসবাস করে।১৫৪০ এর দশকে গ্র্যান্ড ক্যানিয়নে পৌঁছানো প্রথম ইউরোপীয়রা স্প্যানিশ অভিযাত্রী ।

                  ১৯১৯ সালে একটি সরকারী মার্কিন যুক্তরাষ্ট্রের জাতীয় উদ্যানে পরিণত হয় গ্র্যান্ড ক্যানিয়ন ।

                  প্রতি বছর ৫ মিলিয়নেরও বেশি লোক গ্র্যান্ড ক্যানিয়ন দেখার জন্য বেছে নেয়। এটি গ্রহের সবচেয়ে জাদুকরী গন্তব্যগুলির মধ্যে একটি। গ্র্যান্ড ক্যানিয়ন সম্পর্কে আপনি যতই পড়ুন না কেন, আপনি নিশ্চিত যে মন্ত্রমুগ্ধ হবেন।

                  বিশ্বের শীর্ষ ৭ আশ্চর্যের মধ্যে একটি হিসেবে বিবেচিত এবং চিত্তাকর্ষক শিল্পকর্মটি আকর্ষণীয়ভাবে পরিচিত। এর নিখুঁত আকার আপনাকে বাকরুদ্ধ করে দেবে এবং সূর্যোদয় বা সূর্যাস্তের সময় গিরিখাত পরিদর্শন আপনাকে বিমোহিত করে তুলবে, কারণ দৃশ্যগুলি নির্মল,সুন্দর,এবং বিশুদ্ধ।

                  ক্যানিয়নটি তিনটি রিম উত্তর, পশ্চিম ও দক্ষিণে বিভক্ত এবং তিনটিই অবিশ্বাস্যভাবে নতুন সব অভিজ্ঞতার ঝুলি বাড়াবে।

                  দক্ষিণ রিম হল ক্যানিয়নের সর্বাধিক পরিদর্শন করা জায়গা ।কারণ এটি বেশিরভাগ অঞ্চলের অবকাঠামো, নেটিভ আমেরিকান ধ্বংসাবশেষ,এবং ঐতিহাসিক নিদর্শনগুলি হোস্ট করে।

                  গিরিখাতের প্রাচীনতম পরিচিত শিলা, যাকে বলা হয় বিষ্ণু বেসমেন্ট রকস, অভ্যন্তরীণ গর্জের নীচের কাছে পাওয়া যায়। বিষ্ণু শিলাগুলি প্রায় ১.৭ বিলিয়ন বছর আগে তৈরি হয়েছিল যখন ম্যাগমা শক্ত হয়ে গিয়েছিল এবং এই অঞ্চলে- একসময় আগ্নেয়গিরির মহাসাগরের শৃঙ্খলে উত্তর আমেরিকা মহাদেশে যোগ দেয়।

                  ১-২ হাজার খ্রিস্টপূর্বাব্দের মধ্যে তৈরি শত শত ছোট বিভক্ত-টুইগ মূর্তি রয়েছে। এটি ক্যানিয়ন প্রাচীরের গুহায় আবিষ্কৃত হয়েছে। মূর্তিগুলি হরিণ এবং বিগহর্ন ভেড়ার আকৃতির।

                  নৃতাত্ত্বিকরা মনে করেন যে প্রাগৈতিহাসিক শিকারীরা সফল শিকার নিশ্চিত করার জন্য একটি আচারের অংশ হিসাবে গুহাগুলিতে মূর্তিগুলি ফেলে থাকতে পারে।

                  প্রথমে আগতরা ১৮৮০ এর দশকে গ্র্যান্ড ক্যানিয়নের রিমের চারপাশে বসতি স্থাপন শুরু করেছিলেন।তারা প্রথমে তামার ক্ষনি খুজেঁছিলো পরে বুঝতে পারে যে খনির চেয়ে পর্যটন বেশি লাভজনক।

                  গ্র্যান্ড ক্যানিয়ন ন্যাশনাল পার্কের একটি প্রত্যন্ত অংশের কাছে অবস্থিত হাভাসু জলপ্রপাতের সেরুলিয়ান পুল এবং লাল পাথর প্রতি বছর প্রায় কয়েক লক্ষ দর্শকদের আকর্ষণ করে।

                  রাষ্ট্রপতি বেঞ্জামিন হ্যারিসন ১৮৯৩ সালে গ্র্যান্ড ক্যানিয়নকে প্রথম ফেডারেল সুরক্ষা প্রদান করেন।থিওডোর রুজভেল্ট সাইটটি পরিদর্শন করেন এবং বলেন,গ্র্যান্ড ক্যানিয়ন আমাকে বিস্ময়ে পূর্ণ করে। এটি তুলনার বাইরে - বর্ণনার বাইরে, বিস্তৃত বিশ্ব জুড়ে একেবারে অতুলনীয়।

                  প্রকৃতির এই মহান আশ্চর্য এখন যেমন আছে তেমনই থাকুক। এর মহিমা,ও প্রেমময়তা নষ্ট করার জন্য কিছুই করবেন না।তবে আপনি যা করতে পারেন তা হল আপনার সন্তানদের জন্য এবং আপনার পরে আসা সকলের জন্য এটি রাখা, যা প্রত্যেক আমেরিকানক ও পর্যটকদের দেখা উচিত।

                  ১৯০১ সালের পর গ্র্যান্ড ক্যানিয়নে পর্যটন বৃদ্ধি পায়। তখনই নির্মাতারা সান্তা ফে রেলপথের একটি স্ফুর সম্পন্ন করেছিলেন যা ফ্ল্যাগস্টাফ অ্যারিজোনা থেকে পর্যটকদের নিয়ে যাবে নিকটতম প্রধান শহর গ্র্যান্ড ক্যানিয়ন গ্রামে, যা পর্যটকদের জন্য দক্ষিণ রিমের একটি সূচনা পয়েন্ট।

                  ২০১০ সালে, গ্র্যান্ড ক্যানিয়ন ন্যাশনাল পার্ক আমেরিকা দ্য বিউটিফুল কোয়ার্টার প্রোগ্রামের অধীনে নিজস্ব মুদ্রা দিয়ে সম্মানিত হয়েছিল। ২৬ ফেব্রুয়ারী ২০১৯ এ গ্র্যান্ড ক্যানিয়ন ন্যাশনাল পার্ক একটি জাতীয় উদ্যান হিসাবে ১০০ বছর উদযাপন করেছে।
                </div>
                <div class="lg:flex sm:inline overflow-auto whitespace-nowrap tag-scroll flex-row items-center my-2">
                  <a href="" class="mr-4 text-xs inline-flex items-center font-bold leading-sm uppercase px-3 py-1 bg-red-700 text-white">
                    নিউ মার্কেটে সংঘর্ষ
                  </a>
                  <a href="" class="mr-4 text-xs inline-flex items-center font-bold leading-sm uppercase px-3 py-1 bg-red-700 text-white">
                    বাজার দর
                  </a>
                  <a href="" class="mr-4 text-xs inline-flex items-center font-bold leading-sm uppercase px-3 py-1 bg-red-700 text-white">
                    যানজট
                  </a>
                </div>
              </div>
            </div>
            <!-- Bottom related news -->
            <div class="col-span-12">
              <!-- Heading Section -->
              <div class="grid grid-cols-6 gap-4 my-4 py-2 px-2 border-y-2 border-zinc-600">
                <div class="col-start-1 col-end-3">
                                <span class="font-normal text-gray-700 text-2xl dark:text-gray-400 font-sans">
                                    সম্পর্কিত
                                </span>
                </div>
              </div>
              <!-- content section -->
              <div class="grid grid-cols-12 gap-4 py-2">
                <div v-for="n in 3" :key="n" class="lg:col-span-4 col-span-12">
                  <SinglePost/>
                </div>
              </div>
              <!-- Add Setion -->
              <div class="grid grid-cols-1">
                <div class="col-span-1">
                  <div class="hidden lg:block">
                    <img src="https://tpc.googlesyndication.com/simgad/11738030998892677439" class="w-auto h-auto m-auto" alt="..." />
                  </div>
                </div>
              </div>

            </div>
            <!-- Bottom extra news -->
            <div class="col-span-12">
              <!-- Heading Section -->
              <div class="grid grid-cols-6 gap-4 my-4 py-2 px-2 border-y-2 border-zinc-600">
                <div class="col-start-1 col-end-3">
                                <span class="font-normal text-gray-700 text-2xl dark:text-gray-400 font-sans">
                                    আরও পড়ুন
                                </span>
                </div>
              </div>
              <!-- content section -->
              <div class="grid grid-cols-12 gap-4 py-2">
                <div v-for="n in 3" :key="n" class="lg:col-span-4 col-span-12">
                  <SinglePost/>
                </div>
              </div>

            </div>
          </div>
        </div>
        <!-- Right sidebar -->
        <div class="lg:col-span-3 col-span-12 lg:sticky static lg:h-178 h-auto top-1">
          <!-- Top Section -->
          <div class="grid grid-cols-12 gap-2">
            <div class="col-span-12">
              <!-- Ad Section -->
              <div class="hidden lg:block">
                <img src="https://tpc.googlesyndication.com/simgad/5460716425569058327" class="object-cover w-full h-auto" alt="..." />
              </div>
            </div>
            <div class="col-span-12">
              <!-- Heading section -->
              <div class="py-2 border-y-2 border-neutral-300">
                            <span class="font-normal text-gray-600 text-4xl dark:text-gray-400 font-sans">
                                আপনার জন্য
                            </span>
              </div>
              <!-- Content Section -->
              <div class="grid grid-cols-1 p-2 divide-y divide-zinc-300">
                <div v-for="n in 5" :key="n" class="col-span-1 py-2">
                  <SingleListImage/>
                </div>
              </div>
            </div>
          </div>
          <!-- bottom section -->
          <div class="grid grid-cols-12 gap-2">
            <div class="col-span-12">
              <!-- Heading section -->
              <div class="py-2 border-y-2 border-zinc-600">
                            <span class="font-normal text-black text-4xl dark:text-gray-400 font-sans">
                                ট্যুরিজম থেকে আরও
                            </span>
              </div>
              <!-- Content Section -->
              <div class="grid grid-cols-1 p-2 divide-y divide-zinc-300">
                <div v-for="n in 5" :key="n" class="col-span-1 py-2">
                  <SingleListImage/>
                </div>

              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <Footer/>
  </div>
</template>

<script>
import Vue from 'vue';
import MultiPostListImageLeft from "~/components/widgets/MultiPostListImageLeft";
import MultiPostListImageInset from "~/components/widgets/MultiPostListImageInset";
import Advertisment from "~/components/frontend/Advertisment";
import {mdiFacebook, mdiYoutube, mdiTwitter, mdiInstagram, mdiLinkedin, mdiWhatsapp, mdiTagMultipleOutline, mdiPrinter, mdiPencil, mdiCalendar, mdiFormatFontSizeIncrease, mdiFormatFontSizeDecrease} from "@mdi/js";
import VueSocialSharing from 'vue-social-sharing'
Vue.use(VueSocialSharing);

import VueHtmlToPaper from 'vue-html-to-paper';
import Navbar2 from "@/components/widgets/Navbar2";
import MultiPostColumns from "@/components/widgets/MultiPostColumns";
import Navbar from "~/components/widgets/Navbar";
import Footer from "~/components/widgets/Footer";
const options = {
  name: 'blank',
  specs: [
    'fullscreen=yes',
    'titlebar=yes',
    'scrollbars=yes'
  ],
  styles: [
    'https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css',
    'https://unpkg.com/kidlat-css/css/kidlat.css'
  ]
}
Vue.use(VueHtmlToPaper, options);

export default {
  name: "Slug",
  components: {Navbar, Footer, MultiPostColumns, Navbar2, Advertisment, MultiPostListImageInset, MultiPostListImageLeft},
  layout: 'frontend',
  data() {
    return {
      postFontSize: 1.2,
      post: null,
      errorResponse: null,
      loading: true,
      loaded: false,
      mdiTagMultipleOutline: mdiTagMultipleOutline,
      mdiLinkedin: mdiLinkedin,
      mdiYoutube: mdiYoutube,
      mdiTwitter: mdiTwitter,
      mdiInstagram: mdiInstagram,
      mdiFacebook: mdiFacebook,
      mdiPrinter: mdiPrinter,
      mdiWhatsapp: mdiWhatsapp,
      mdiCalendar: mdiCalendar,
      mdiPencil: mdiPencil,
      mdiFormatFontSizeIncrease: mdiFormatFontSizeIncrease,
      mdiFormatFontSizeDecrease: mdiFormatFontSizeDecrease,
      items: [
        {
          text: 'প্রচ্ছদ',
          disabled: false,
          to: '/',
        },
        {
          text: 'Category',
          disabled: true,
          href: '#',
        },
        {
          text: this.$route.params.slug,
          disabled: false,
          to: {name: 'slug', params: {id: this.$route.params.slug}},
        }
      ]
    };
  },
  methods: {
    getUrl() {
      return this.$config.baseURL+'/'+ this.$route.fullPath;
    },
    print() {
      // Pass the element id here
      this.$htmlToPaper('printMe');
    },
    showDate() {
      var date = new Date();
      var n = date.toDateString();
      var time = date.toLocaleTimeString();
      return n + ' ' + time;
    },
    formatDate(string) {
      var str = new Date(string).toLocaleString('bn-BD');
      return str.split(" ")[0];
    },
    formatDateHumanReadable(string) {
      const readable = new Date(string);
      let m = readable.getMonth(),
        d = readable.getDate().toLocaleString('bn'),
        y = readable.getFullYear().toLocaleString('bn').replace(',',''),
        //months = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
        months = ["জানুয়ারি", "ফেব্রুয়ারি", "মার্চ", "এপ্রিল", "মে", "জুন", "জুলাই", "আগস্ট", "সেপ্টেম্বর", "অক্টোবর", "নভেম্বর", "ডিসেম্বর"];
      let monthName = months[m];
      return monthName + " " + d + ", " + y;
    },
    replaceIframe(str) {
      const regex = /(<iframe.*?>.*?<\/iframe>)/g;
      return str.replace(regex, "");
    },
    formatForWeb(str) {
      str = this.replaceIframe(str);
      return str;
    },
    getImageSrc(src) {
      if (/^(?:[a-z]+:)?\/\//i.test(src)) {
        return src;
      }
      return this.$config.apiBaseURL + '/storage' + src;
    }
  },
  asyncData(context) {
    return context.$axios
      .get(
         '/frontend/get-post', {
          params: {
            slug: decodeURI(context.params.slug)
          }
        }
      )
      .then((res) => {
        if (res.data.length === 0 || res.data.error) {
          throw({ statusCode: 404, message: 'Post not found' })
        }

        let items = [
          {
            text: 'প্রচ্ছদ',
            disabled: false,
            href: '/',
          },
          {
            text: res.data.categories ? res.data.categories[0].title : 'শ্রেণী বহির্ভূত',
            disabled: false,
            to: {name: 'category-id', params: {id: res.data.categories[0].slug}},
          },
          {
            text: res.data.title,
            disabled: false,
            to: {name: 'slug', params: {id: context.params.slug}},
          },
        ];
        return { post: res.data, items: items }
      })
      .catch(error => {
        if (error.response) {
          // Request made and server responded
          //this.errorResponse = error.response.data;
          //this.errorCode = error.response.status;
        } else if (error.request) {
          // The request was made but no response was received
          //console.log(error.request);
        } if (error.statusCode === 404) {
          //return context.error({ statusCode: 404, message: error.message })
        } else {
          // Something happened in setting up the request that triggered an Error
          //return {error: error.message};
          //return context.error({ statusCode: 500, message: error.message })
        }
      });
  },
}
</script>

<style scoped>


</style>
