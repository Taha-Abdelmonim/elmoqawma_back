<?php

namespace Database\Seeders;

use App\Models\AmountBank;
use App\Models\Bank;
use App\Models\Course;
use App\Models\Currency;
use App\Models\Offer;
use App\Models\PaymentMethod;
use App\Models\Review;
use App\Models\Section;
use App\Models\Setting;
use App\Models\Subject;
use App\Models\Teacher;
use App\Models\TeacherSectionSubject;
use Illuminate\Database\Seeder;

class GlobalSeeder extends Seeder
{
    public function run()
    {
        /*Section::create([
"name_ar" => "صف رئيسي",
"name_en" => "Main Section",
"description_ar" => "صف رئيسي",
"description_en" => "description class",
"photo" => "sections/st.jpg",
]);*/
        $section_1 = Section::create([
            "name_ar" => " الصف الاول الثانوي",
            "name_en" => "first class",
            "description_ar" => "الصف الاول",
            "description_en" => "first class",
            "photo" => "sections/st.jpg",
        ]);
        $section_2 = Section::create([
            "name_ar" => "الصف الثاني الثانوي",
            "name_en" => "second class",
            "description_ar" => "الصف الثاني",
            "description_en" => "second class",
            "photo" => "sections/nd.jpg",
        ]);
        $section_3 = Section::create([
            "name_ar" => "الصف الثالث الثانوي",
            "name_en" => "second class",
            "description_ar" => "الصف الثالث",
            "description_en" => "second class",
            "photo" => "sections/rd.jpg",
        ]);

        $subject_1 = Subject::create([
            "name_ar" => "الجغرافيا",
            "name_en" => "geyography",
            "photo" => "subjects/1.jpg",
        ]);
        $subject_2 = Subject::create([
            "name_ar" => "اللغة الانجليزية",
            "name_en" => "english language",
            "photo" => "subjects/2.jpg",
        ]);
        $subject_3 = Subject::create([
            "name_ar" => "الرياضيات",
            "name_en" => "math",
            "photo" => "subjects/3.jpg",
        ]);
        $teacher_1 = Teacher::create([
            "name_ar" => "أ/ناصر العراقي",
            "name_en" => "dr.nasser aleraky",
            "description_ar" => "لوريم إيبسوم(Lorem Ipsum) هو ببساطة نص شكلي (بمعنى أن الغاية هي الشكل وليس المحتوى) ويُستخدم في صناعات المطابع ودور النشر. كان لوريم إيبسوم ولايزال المعيار للنص الشكلي منذ القرن الخامس عشر",
            "description_en" => "Lorem Ipsum is simply dummy text (meaning the intent is form, not content) and is used by the printing and publishing industries. Lorem Ipsum has been the standard for dummy text since the 1500s",
            "email" => "teacher_one@" . env("DOMAIN"),
            "phone" => "000000000",
            "photo" => "teachers/1.png",
            //			'course_id' => 1,
        ]);
        TeacherSectionSubject::create([
            "section_id" => $section_1->id,
            "teacher_id" => $teacher_1->id,
        ]);
        TeacherSectionSubject::create([
            "section_id" => $section_2->id,
            "teacher_id" => $teacher_1->id,
        ]);
        TeacherSectionSubject::create([
            "section_id" => $section_3->id,
            "teacher_id" => $teacher_1->id,
        ]);
        TeacherSectionSubject::create([
            "subject_id" => $subject_1->id,
            "teacher_id" => $teacher_1->id,
        ]);
        TeacherSectionSubject::create([
            "subject_id" => $subject_2->id,
            "teacher_id" => $teacher_1->id,
        ]);
        TeacherSectionSubject::create([
            "subject_id" => $subject_3->id,
            "teacher_id" => $teacher_1->id,
        ]);
        $teacher_2 = Teacher::create([
            "name_ar" => "أ/مينا حنا",
            "name_en" => "dr.mena 7anna",
            "description_ar" => "لوريم إيبسوم(Lorem Ipsum) هو ببساطة نص شكلي (بمعنى أن الغاية هي الشكل وليس المحتوى) ويُستخدم في صناعات المطابع ودور النشر. كان لوريم إيبسوم ولايزال المعيار للنص الشكلي منذ القرن الخامس عشر",
            "description_en" => "Lorem Ipsum is simply dummy text (meaning the intent is form, not content) and is used by the printing and publishing industries. Lorem Ipsum has been the standard for dummy text since the 1500s",
            "email" => "teacher_two@" . env("DOMAIN"),
            "phone" => "000000000",
            "photo" => "teachers/2.png",
        ]);
        TeacherSectionSubject::create([
            "section_id" => $section_1->id,
            "teacher_id" => $teacher_2->id,
        ]);
        TeacherSectionSubject::create([
            "section_id" => $section_2->id,
            "teacher_id" => $teacher_2->id,
        ]);
        TeacherSectionSubject::create([
            "subject_id" => $subject_1->id,
            "teacher_id" => $teacher_2->id,
        ]);
        $teacher_3 = Teacher::create([
            "name_ar" => "أ/بيتر متياس",
            "name_en" => "dr.beetter metyas",
            "description_ar" => "لوريم إيبسوم(Lorem Ipsum) هو ببساطة نص شكلي (بمعنى أن الغاية هي الشكل وليس المحتوى) ويُستخدم في صناعات المطابع ودور النشر. كان لوريم إيبسوم ولايزال المعيار للنص الشكلي منذ القرن الخامس عشر",
            "description_en" => "Lorem Ipsum is simply dummy text (meaning the intent is form, not content) and is used by the printing and publishing industries. Lorem Ipsum has been the standard for dummy text since the 1500s",
            "email" => "teacher_two@" . env("DOMAIN"),
            "phone" => "000000000",
            "photo" => "teachers/3.png",
        ]);
        TeacherSectionSubject::create([
            "section_id" => $section_1->id,
            "teacher_id" => $teacher_3->id,
        ]);
        TeacherSectionSubject::create([
            "subject_id" => $subject_1->id,
            "teacher_id" => $teacher_3->id,
        ]);
        TeacherSectionSubject::create([
            "subject_id" => $subject_2->id,
            "teacher_id" => $teacher_3->id,
        ]);

        $egypt = Currency::create([
            "name" => "جنية",
            "currency_symbol" => "EGP",
            "currency_rate" => 1,
        ]);
        $saudi = Currency::create([
            "name" => "الريال",
            "currency_symbol" => "SAR",
            "currency_rate" => 8.24,
        ]);

        $course_1 = Course::create([
            "name" => "اول حصة",
            "subject_id" => $subject_2->id,
            "section_id" => $section_2->id,
            "teacher_id" => $teacher_2->id,
            "currency_id" => $egypt->id,
            "whatsapp" => "#",
            "telegram" => "#",
            "price" => "150",
            "sub_price" => "200",
            "discount" => "50",
            "subscribers" => "150",
            "subscription_duration" => 0,
            "stars" => "5",
            "status" => "1",
            "type" => 1,
            "collectionID" => "",
            "description_ar" => "لوريم إيبسوم(Lorem Ipsum) هو ببساطة نص شكلي (بمعنى أن الغاية هي الشكل وليس المحتوى) ويُستخدم في صناعات المطابع ودور النشر. كان لوريم إيبسوم ولايزال المعيار للنص الشكلي منذ القرن الخامس عشر",
            "description_en" => "Lorem Ipsum is simply dummy text (meaning the intent is form, not content) and is used by the printing and publishing industries. Lorem Ipsum has been the standard for dummy text since the 1500s",
            "photo" => "courses/1.jpg",
            "description_photo" => "courses/3.jpg",
        ]);
        $course_2 = Course::create([
            "name" => "ثاني حصة",
            "subject_id" => $subject_1->id,
            "section_id" => $section_1->id,
            "teacher_id" => $teacher_1->id,
            "currency_id" => $egypt->id,
            "whatsapp" => "#",
            "telegram" => "#",
            "price" => "150",
            "sub_price" => "200",
            "discount" => "50",
            "subscribers" => "150",
            "subscription_duration" => 0,
            "stars" => "5",
            "status" => "1",
            "type" => 0,
            "collectionID" => "",
            "description_ar" => "لوريم إيبسوم(Lorem Ipsum) هو ببساطة نص شكلي (بمعنى أن الغاية هي الشكل وليس المحتوى) ويُستخدم في صناعات المطابع ودور النشر. كان لوريم إيبسوم ولايزال المعيار للنص الشكلي منذ القرن الخامس عشر",
            "description_en" => "Lorem Ipsum is simply dummy text (meaning the intent is form, not content) and is used by the printing and publishing industries. Lorem Ipsum has been the standard for dummy text since the 1500s",
            "photo" => "courses/3.jpg",
            "description_photo" => "courses/2.jpg",
        ]);

        $offer = Offer::create([
            "name_ar" => "اول عرض",
            "name_en" => "first offer",
            "price" => 500,
            "photo" => "courses/1.jpg",
            "subscribers" => 150,
            "stars" => 5,
            "duration" => 5,
            "currency_id" => $egypt->id,
            "description_ar" => "لوريم إيبسوم(Lorem Ipsum) هو ببساطة نص شكلي (بمعنى أن الغاية هي الشكل وليس المحتوى) ويُستخدم في صناعات المطابع ودور النشر. كان لوريم إيبسوم ولايزال المعيار للنص الشكلي منذ القرن الخامس عشر",
            "description_en" => "Lorem Ipsum is simply dummy text (meaning the intent is form, not content) and is used by the printing and publishing industries. Lorem Ipsum has been the standard for dummy text since the 1500s",
        ]);
        $review = Review::create([
            "comment" => "لوريم إيبسوم(Lorem Ipsum) هو ببساطة نص شكلي (بمعنى أن الغاية هي الشكل وليس المحتوى) ويُستخدم في صناعات",
            "section_id" => $section_1->id,
            "user_id" => 1,
            "status" => 1,
        ]);

        $bank = Bank::create([
            "name_ar" => "الخزنة الرئيسية",
            "name_en" => "General Bank",
        ]);
        AmountBank::create([
            "amount" => 500,
            "currency_id" => $egypt->id,
            "bank_id" => $bank->id,
        ]);
        AmountBank::create([
            "amount" => 100,
            "currency_id" => $saudi->id,
            "bank_id" => $bank->id,
        ]);
        PaymentMethod::create([
            "name_ar" => "visa",
            "name_en" => "visa",
        ]);
        PaymentMethod::create([
            "name_ar" => "cash",
            "name_en" => "cash",
        ]);
        $nameSiteAR = trans("global.site_name_ar");
        $nameSiteEN = trans("global.site_name_en");
        // About Page
        Setting::create([
            "name" => "شركة " . $nameSiteAR,
            "key" => "site_company",
            "key_status" => 0,
            "type" => "html",
            "value_ar" =>
                '
        معا في <a href="http://elmoqawma.com/" target="_blank"><strong>' .
                $nameSiteAR .
                "</strong></a> نهدف إلى العمل من أجل بناء عائلة لديها الإمكانيات والقدرة على تطوير الذات بأسهل الطرق الممكنة، وذلك من خلال الاستفادة بالتقدم التكنولوجي فيما يفيد المصلحة العامة، ويعود فيما بعد على المملكة العربية بالخير فتتقدم المملكة بأهلها، نقدم في " .
                $nameSiteAR .
                ' الدورات المختلفة في مجالات عدة، بداية من المجالات الفنية، مرورا بمجالات العمل الحر والمستقبل وغيرها..
        <br >
        منذ بدايتنا و نحن نضع عنصر التميز والإبداع نصب أعيننا، فنحن نعمل منذ أكثر من 10 سنوات بفريق عمل ذو خبرة مؤهلة حيث أن لدينا فريقاً في مختلف التخصصات الهامة للمستقبل بشكل عام، وللأسرةبشكل خاص، وقد إجتمعنا في ميدان التخصص لنطور ونبتكر..
        <br >
        الأمر بالكامل لدينا معتمد على الشغف والعمل بحب في كل تخصص نقدمه لاخواننا وأخواتنا بالمملكة، ونهدف بذلك إلى تقديم ناتج مثمر ومفيد للأسر المنتجة وأيضا الفرد الواحد داخل المجتمع.
      ',
            "value_en" =>
                '
        Together in your <a href="http://elmoqawma.com/" target="_blank"><strong>' .
                $nameSiteEN .
                "</strong></a>, we aim to work towards building a family that has the capabilities and the ability to develop oneself in the easiest possible way, by taking advantage of technological progress in a way that benefits the public interest, and later returns to the Kingdom of Saudi Arabia with goodness, so that the Kingdom progresses with its people. We present in your " .
                $nameSiteEN .
                ' various courses in several fields Starting from the technical fields, passing through the fields of self-employment, the future, and others.
        <br >
        Since our inception, we have been keeping the element of excellence and creativity in mind. We have been working for more than 10 years with a team with qualified experience, as we have a team in various important disciplines for the future in general, and for the family in particular, and we have gathered in the field of specialization to develop and innovate..
        <br >
        Our entire matter is based on passion and work with love in every specialty that we offer to our brothers and sisters in the Kingdom, and we aim to provide a fruitful and useful product for productive families as well as the individual within society.
      ',
        ]);
        Setting::create([
            "name" => "عن " . $nameSiteAR,
            "key" => "about_company",
            "key_status" => 0,
            "type" => "html",
            "value_ar" =>
                '
       نحن مؤسسة رقمية متكاملة متخصصة في تقديم خدمات االتعليم عن بعد في مختلف المجالات كما أننا نقدم حلول مبتكرة للشركات والمؤسسات والأفراد للتعلم بواسطة التعليم عن بعد أونلاين كما أننا نقوم بتسليط الضوء على عملك ومحاولة المساعدة قدر الامكان لتقديم العون والخدمات التعليمية.
        <br >
        نحن معروفون بالتخطيط القوي والتنفيذ الإبداعي المدعوم بمحتوى أصلي وجذاب ويخلق تفاعل كبير من قِبل العملاء فإذا كنت تبحث عن شريك قوي وموثوق به وبنجاحاته ويمتلك خبرة كبيرة في التعليم عن بعد فإن ' .
                $nameSiteAR .
                ' خيارك الأفضل كما أننا نسعى جاهدين لفهم أهداف نشاط عميلنا أولا ثم يتم اتخاذ جميع القرارات مع وضع هذه الأهداف في الإعتبارحيث إن تعدد المحتوى التعليمي إذا لم يساعدك في تحقيق أهدافك فإنه قد يأتي بنتيجة عكسية وهو ما نسعى لتجنبه بشكل كبير نشارككم دائما التحديات التي تواجهنا ونعمل معا من أجل تكوين روابط وعائلة مترابطة . التواصل والمُتابعة الدائمة مع العملاء بالإضافة لخدمة ما بعد البيع ومساعدتك على تطوير الذات كفرد منتج ومفيد للمجتمع
        ',
            "value_en" => '
        We are an integrated digital institution specialized in providing distance education services in various fields. We also provide innovative solutions for companies, institutions and individuals to learn via online distance education. We also shed light on your work and try to help as much as possible to provide aid and educational services.
        <br >
        We are known for strong planning and creative implementation supported by original and attractive content that creates great customer interaction. If you are looking for a strong, reliable and successful partner with great experience in distance education, Dalilak is your best choice. We also strive to understand the objectives of our client\'s activity first, and then all decisions are taken. With these goals in mind, as the multiplicity of educational content, if it does not help you achieve your goals, it may backfire, which is what we seek to avoid greatly. We always share with you the challenges we face and work together to form bonds and an interdependent family. Constant communication and follow-up with customers, in addition to after-sales service, and helping you to develop yourself as a productive and beneficial individual to society
      ',
        ]);
        Setting::create([
            "name" => "رؤيتنا",
            "key" => "our_vision",
            "key_status" => 0,
            "type" => "text",
            "value_ar" => "أن نكون المزود الرائد للدورات التدريبية عبر الإنترنت في مجال الفنون والحرف اليدوية ومجالات المستقبل",
            "value_en" => "To become the leading and integrated company that reconciles all digital solutions and provides its services at the highest level of creativity and expertise in the Middle East",
        ]);
        Setting::create([
            "name" => "مهمتنا",
            "key" => "our_mission",
            "key_status" => 0,
            "type" => "text",
            "value_ar" => "لتقديم دورات عبر الإنترنت عالية الجودة وجذابة في الفنون والحرف اليدوية ومجالات المستقبل",
            "value_en" => "Our mission is to provide the right way for our customers to increase their presence in the market and pay attention to all the details that help them grow. We also provide the best solutions through the use of modern technology and continuous learning",
        ]);
        Setting::create([
            "name" => "أهدافنا",
            "key" => "our_target",
            "key_status" => 0,
            "type" => "text",
            "value_ar" => "اكبر منصة تعليمية في الشرق الاوسط",
            "value_en" => "Achieving the highest level of customer satisfaction and improving their presence in order to reach the ideal image that suits the level of business activity",
        ]);
        Setting::create([
            "name" => "وصف المنصة في footer",
            "key" => "description_footer",
            "key_status" => 0,
            "type" => "text",
            "value_ar" => "نحن مؤسسة رقمية متكاملة متخصصة في تقديم خدمات االتعليم عن بعد في مختلف المجالات كما أننا نقدم حلول مبتكرة للشركات والمؤسسات والأفراد للتعلم بواسطة التعليم عن بعد أونلاين كما أننا نقوم بتسليط الضوء على عملك ومحاولة المساعدة قدر الامكان لتقديم العون والخدمات التعليمية.",
            "value_en" =>
                "We are an integrated digital organization established in 2020 specialized in providing e-marketing services and designing commercial identities in addition to web solutions, programming, web hosting services and servers. We also provide innovative solutions for companies and institutions and develop them with new ideas and modern marketing methods that help spread and achieve goals. We also shed Highlight your work and try to show it in a way that attracts your target audience",
        ]);
        Setting::create([
            "name" => "سياسة الخصوصية",
            "key" => "privacy_policy",
            "key_status" => 0,
            "type" => "html",
            "value_ar" =>
                '<div><h1 class="text-center text-4xl font-bold dark:text-fpBlue border-b-2 w-fit mx-auto text-fpGreen">سياسة الخصوصية</h1><h3 class="my-6 text-2xl dark:text-fpLightBack text-fpGreen">تم آخر تحديث لسياسة الخصوصية هذه في 26 أبريل 2023.</h3><div class="space-y-5"><p class="leading-6 text-gray-700 dark:text-gray-300 text-md"> يجب أن تحمل جميع المصطلحات المكتوبة بحروف كبيرة المستخدمة وغير المحددة بطريقة أخرى هنا المعنى المنسوب لها في شروط استخدام https://elmoqawma.com/ </p><p class="leading-6 text-gray-700 dark:text-gray-300 text-md"> يتم توفير أي نسخة من سياسة الخصوصية هذه بأي لغة أخرى غير العربيه للراحة ، ويجب أن تفهم وتوافق على أن اللغة العربيه يجب أن تسود ، في حالة حدوث أي تعارض. </p><p class="leading-6 text-gray-700 dark:text-gray-300 text-md"> أنت متعاقد مع ' .
                $nameSiteAR .
                " ، وهي شركة تقع في نصر الدين الجيزه مصر . تحترم " .
                $nameSiteAR .
                ' حقوقك فيما يتعلق بالخصوصية والمعلومات الشخصية الخاصة بك ، ونحن نقدم سياسة الخصوصية هذه لمساعدتك على فهم كيفية جمع المعلومات التي نحصل عليها منك أو عنك عند استخدام خدماتنا ، واستخدامها ، ومشاركتها ، وتخزينها . </p><p class="leading-6 text-gray-700 dark:text-gray-300 text-md"> تنطبق سياسة الخصوصية هذه على استخدامك لهذه الخدمات. كما هو مستخدم هنا ، يعني مصطلح "استخدام" استخدام الخدمات أو الوصول إليها أو تثبيتها أو تسجيل الدخول إليها أو الاتصال بها أو تنزيلها أو زيارتها أو تصفحها. علاوة على ذلك ، يُشار إلى أي معلومات تتعلق بأخذ الدورات و / أو مشاهدة المحادثات المضمنة على سبيل المثال لا الحصر ، والتبادلات مع مدربي الدورات / المحادثات ، والطلاب الآخرين ، والإجابات ، والمقالات ، والعناصر الأخرى المقدمة لتلبية متطلبات الدورة ، باسم "معلومات الدورة التدريبية . " </p><p class="leading-6 text-gray-700 dark:text-gray-300 text-md"> من خلال هذا البيان " سياسة الخصوصية " يمكنك التعرف على من يمكنهم استخدام منصتنا ، ماذا نفعل ببياناتك الشخصية ، ما هي الحقوق الخاصة بمستخدمينا ، وأي أسئلة أخرى متعلقة بخصوصية المستخدمين ، لذا سنعمل دائماً على تطوير هذا البيان وهذه المعلومات كلما دعت الحاجة ، أو كلما أضفنا خدمة جديدة تؤثر على هذه السياسة ، وسيمكنكم دائماً التعرف على تاريخ التجديد من خلال أول جملة في هذا البيان حيث ستجدون مكتوب " آخر تحديث " وبجوارها التاريخ . </p><p class="leading-6 text-gray-700 dark:text-gray-300 text-md"> تهدف منصه ' .
                $nameSiteAR .
                ' منصة مصرية ومقرها مصر ، تعمل في مجال التدريب الحرفي والتعليم الفني عن بعد وبطريقه مباشرة ، نقدم العديد من الدورات التدريبية للأفراد والمؤسسات داخل المجالات الفنيه المختلفه ، نقدم ذلك من خلال خدمات البث المباشر ، ومن خلال نظام الدورات التدريبية المسجلة المدفوعة ومن خلال التدريب الفعلي والورش التدريبية هنا هذا بخلاف مجموعة من الخدمات الاستشارية والتي تساعد المتدربين على تحويل ما يتعلموه لمصدر دخل مباشر لهم خاص بهم . </p></div><div class="mt-10"><h3 class="my-6 text-2xl dark:text-fpBlue text-fpGreen">المعلومات التي نجمعها</h3><ul class="list-circle mr-10 space-y-2 text-xl dark:text-gray-300"><li>اسمك</li><li>عنوان بريدك الالكتروني</li><li>رقم هاتفك المحمول</li><li>عنوانك ( ان تم الاحتياج له )</li><li>عمرك " اختياري "</li><li>حسابك علي فيس بوك او جوجل " اختياري "</li></ul></div><h3 class="mb-6 mt-20 text-2xl dark:text-fpBlue text-fpGreen">سبب جمع هذه البيانات :</h3><div class="space-y-5"><p class="leading-6 text-gray-700 dark:text-gray-300 text-md"> نقوم بجمع هذه البيانات من أجل القدرة على التواصل مع عملائنا بشكل فعال ، وتقديم خدمات المتابعة بخصوص أحدث الدورات ونحوها ، وكذا ارسال وتغيير كلمة السر والتأكد من ملكية الحساب لدى المتدرب والذي يكون له الحق في ملكيه الدورات التدريبية المشترك بها ولكن من خلال منصه ' .
                $nameSiteAR .
                " فقط ولا يحق له استخدامها او تسجيلها او بيعها لاحد وان تم هذا يتم عن طريق مخالفه قانونيا ليس ل" .
                $nameSiteAR .
                ' أي مسأله قانونية بشأنها وفي حاله التعرض لسرقه المحتواه واستخدامه بشكل غير لائق يحق لنا المطالبه بتعويضات عن ذلك . </p></div><h3 class="my-6 text-2xl dark:text-fpBlue text-fpGreen">كيف نتعامل مع بياناتك الشخصية :</h3><div class="space-y-5"><p class="leading-6 text-gray-700 dark:text-gray-300 text-md"> لا نستخدم بيانات الشخصية ولا نشاركها مع أحد جهة كانت او فرد ، فقط نستخدمها في الترويج لمنتجاتنا وخدماتنا المباشرة في حالة موافقتك على ذلك ، أم بخلاف ذلك فلا يتم استخدام البيانات مطلقا وفي حاله استخدامها يتم لوصول كافه المعلومات الفنيه لديكم واذا تمت الممانعه يتم ارسال عبر الميل الخاص ب' .
                $nameSiteAR .
                ' عدم استخدام بيانتك الخاصه ولا يتم استخدامها نهائيا  </p></div><h3 class="my-6 text-2xl dark:text-fpBlue text-fpGreen">المدة التي نحتفظ بها ببياناتك الشخصية واساليب حفظ المعلومات :</h3><div class="space-y-5"><p class="leading-6 text-gray-700 dark:text-gray-300 text-md"> تتخذ ' .
                $nameSiteAR .
                " تدابير أمنية معقولة تجاريًا للحماية من الوصول غير المصرح به إلى البيانات الحساسة التي تشاركها أو الكشف عنها أو إتلافها أو التغيير غير المصرح به أو الكشف عنها أو إتلافها ، ونقوم بجمعها وتخزينها. قد تتضمن إجراءات الأمان هذه ممارسات مثل الاحتفاظ ببياناتك الحساسة على خادم آمن خلف جدار حماية ، ونقل معلومات حساسة (مثل رقم بطاقة الائتمان) التي يتم إدخالها في خدماتنا باستخدام تقنية طبقة المقابس الآمنة (SSL) ، والمراجعات الداخلية لجمع البيانات لدينا الممارسات والأنظمة الأساسية ، بالإضافة إلى إجراءات الأمان المادية للحماية من الوصول غير المصرح به إلى الأنظمة التي نخزن فيها معلوماتك. ومع ذلك ، لسوء الحظ ، لا يمكن تأمين أي نظام بنسبة 100٪ عالميا ، ولا يمكننا ضمان أن الاتصالات بينك وبين " .
                $nameSiteAR .
                ' أو الخدمات أو أي معلومات مقدمة لنا حول المعلومات التي نجمعها من خلال الخدمات ستكون خالية من الوصول غير المصرح به من قبل أطراف ثالثة . قد يؤدي الدخول أو الاستخدام غير المصرح به ، وفشل الأجهزة أو البرامج ، وعوامل أخرى إلى تعريض أمان معلومات المستخدم للخطر في أي وقت. كلمة المرور الخاصة بك هي عنصر مهم في نظام الأمان لدينا. على هذا النحو ، تقع على عاتقك مسؤولية حمايتها. لا تشاركه مع أي طرف ثالث. إذا تم اختراق كلمة المرور الخاصة بك لأي سبب من الأسباب ، فيجب عليك تغييرها على الفور والاتصال بـ  مع أي مخاوف. نحتفظ بالبيانات الشخصية سواء استمر الشخص في استخدام موقعنا أو توقف عن استخدامه ، حتى يُرسل لنا طلباً على البريد الالكتروني المخصص لذلك رسالة بعنوان : حذف البيانات الشخصية ، ومكتوب في تفاصيل الرسالة بيانات الحساب  </p></div><h3 class="my-6 text-2xl dark:text-fpBlue text-fpGreen">آلية التعامل مع بيانات الدفع الخاصة بك :</h3><div class="space-y-5"><p class="leading-6 text-gray-700 dark:text-gray-300 text-md"> نحن متعاقدين مع عدد من وسائل الدفع التي تقدم خدماتها في السوق المصري وغيره من الأسواق ، وقد حصلت هذه الوسائل على رخصة للعمل بالسوق المصري من البنك المركزي ، تحمي هذه الوسائل بيانات بالطرق العالمية المتبعة ، لذا فنحن نقدم لك خدمات دفع آمنة . </p></div><h3 class="my-6 text-2xl dark:text-fpBlue text-fpGreen">تحديث سياسة الخصوصية :</h3><div class="space-y-5"><p class="leading-6 text-gray-700 dark:text-gray-300 text-md"> أي تحديث جوهري في سياسة الخصوصية يتم ارسال علي الحساب المسجل لدينا من أجمل ضمان اطلاع عملائنا عليه ، وفي حالة التحديثات البسيطة التلقائية لن يتم ارسال بريد الكتروني لذا يمكنكم باستمرار متابعة التحديثات من خلال هذه الصفحة ومتابعة تاريخ آخر تحديث . </p></div></div>',
            "value_en" =>
                '<div><h1 class="text-center text-4xl font-bold dark:text-fpBlue border-b-2 w-fit mx-auto text-fpGreen">Privacy Policy</h1><h3 class=" my-6 text-2xl dark:text-fpLightBack text-fpGreen">This privacy policy was last updated on April 26, 2023.</h3><div class="space-y-5"><p class="leading- 6 text-gray-700 dark:text-gray-300 text-md">All capitalized terms used and not otherwise defined herein shall have the meaning given to them in the https://elmoqawma.com/ Terms of Use</ p><p class="leading-6 text-gray-700 dark:text-gray-300 text-md"> Any version of this Privacy Policy is provided in any language other than Arabic for convenience, and you must understand and agree that Arabic You shall prevail, in the event of any conflict. </p><p class="leading-6 text-gray-700 dark:text-gray-300 text-md">You are contracting with ' .
                $nameSiteAR .
                ", a company located in Nasr El Din Giza, Egypt. Respect " .
                $nameSiteAR .
                'Your rights regarding your privacy and personal information We provide this Privacy Policy to help you understand how we collect, use, share and store the information we obtain from or about you when you use our Services. </p><p class="leading-6 text-gray-700 dark:text-gray-300 text-md"> This Privacy Policy applies to your use of these Services. As used herein, the term “use” means using, accessing, installing, logging into, connecting to, downloading, visiting or browsing the Services. Furthermore, any information relating to taking courses and/or viewing included talks such as but not limited to, exchanges with course instructors/talks, other students, answers, essays, and other items submitted to fulfill course requirements, is referred to as “Course Information.” " </p><p class="leading-6 text-gray-700 dark:text-gray-300 text-md"> Through this statement “Privacy Policy” you can learn about who can use our platform, what we do with your personal data What are the rights of our users, and any other questions related to user privacy, so we will always work to develop this statement and this information whenever the need arises, or whenever we add a new service that affects this policy, and you will always be able to know the renewal date through the first sentence in this statement. Where you will find “Last Updated” written next to it and the date. </p><p class="leading-6 text-gray-700 dark:text-gray-300 text-md"> Its platform aims to... ' .
                $nameSiteAR .
                'An Egyptian platform based in Egypt, working in the field of craft training and technical education remotely and directly. We offer many training courses for individuals and institutions within various technical fields. We offer this through live broadcast services, through the system of paid recorded training courses, and through actual training and workshops. The training here is in addition to a group of consulting services that help trainees transform what they learn into a direct source of income for themselves. </p></div><div class="mt-10"><h3 class="my-6 text-2xl dark:text-fpBlue text-fpGreen">Information we collect</h3><ul class= "list-circle mr-10 space-y-2 text-xl dark:text-gray-300"><li>Your name</li><li>Your email address</li><li>Your mobile number</li li><li>Your address (if needed)</li><li>Your age (optional)</li><li>Your Facebook or Google account (optional)</li></ul></div <h3 class="mb-6 mt-20 text-2xl dark:text-fpBlue text-fpGreen">The reason for collecting this data:</h3><div class="space-y-5"><p class= "leading-6 text-gray-700 dark:text-gray-300 text-md"> We collect this data in order to be able to communicate with our customers effectively, provide follow-up services regarding the latest courses and the like, as well as send and change the password and ensure Ownership of the account with the trainee, who has the right to own the training courses in which he participates, but through the \'platform' .
                $nameSiteAR .
                "Only he has no right to use, register or sell it to anyone, and if this is done in violation of the law, it is not for " .
                $nameSiteAR .
                'Any legal issue regarding it, and in the event that the content is stolen and used inappropriately, we have the right to demand compensation for that. </p></div><h3 class="my-6 text-2xl dark:text-fpBlue text-fpGreen">How we handle your personal data:</h3><div class="space-y-5" <p class="leading-6 text-gray-700 dark:text-gray-300 text-md"> We do not use personal data and do not share it with any entity or individual. We only use it to promote our direct products and services if you agree to Otherwise, the data is not used at all, and if it is used, all technical information will reach you, and if you object, it will be sent via your email' .
                $nameSiteAR .
                ' Your private data is not used and it will not be used at all. </p></div><h3 class="my-6 text-2xl dark:text-fpBlue text-fpGreen">The period for which we keep your personal data and the methods of storing information:</ h3><div class="space-y-5"><p class="leading-6 text-gray-700 dark:text-gray-300 text-md"> Take ' .
                $nameSiteAR .
                " We use commercially reasonable security measures to protect against unauthorized access, disclosure or destruction of, or unauthorized alteration, disclosure or destruction of, the sensitive data you share that we collect and store. These security measures may include practices such as keeping your sensitive data on a secure server behind a firewall, transmitting sensitive information (such as a credit card number) entered into our Services using Secure Socket Layer (SSL) technology, internal reviews of our data collection practices and platforms, as well as To physical security measures to protect against burglary",
        ]);
        Setting::create([
            "name" => "facebook",
            "key" => "facebook",
            "key_status" => 0,
            "type" => "text",
            "value_ar" => "https://web.facebook.com/profile.php?id=100065101106780",
            "value_en" => "https://web.facebook.com/profile.php?id=100065101106780",
        ]);
        Setting::create([
            "name" => "telegram",
            "key" => "telegram",
            "key_status" => 0,
            "type" => "text",
            "value_ar" => "https://t.me/Taha_Abdelmoneim",
            "value_en" => "https://t.me/Taha_Abdelmoneim",
        ]);
        Setting::create([
            "name" => "instagram",
            "key" => "instagram",
            "key_status" => 0,
            "type" => "text",
            "value_ar" => "https://www.instagram.com/",
            "value_en" => "https://www.instagram.com/",
        ]);
        Setting::create([
            "name" => "youtube",
            "key" => "youtube",
            "key_status" => 0,
            "type" => "text",
            "value_ar" => "https://www.youtube.com/channel/UCHXU8cbhrwQ63ggqk24ZEDA",
            "value_en" => "https://www.youtube.com/channel/UCHXU8cbhrwQ63ggqk24ZEDA",
        ]);
        Setting::create([
            "name" => "whatsapp",
            "key" => "whatsapp",
            "key_status" => 0,
            "type" => "text",
            "value_ar" => "01125119667",
            "value_en" => "01125119667",
        ]);
        Setting::create([
            "name" => "phone",
            "key" => "phone",
            "key_status" => 0,
            "type" => "text",
            "value_ar" => "+201125119667",
            "value_en" => "+201125119667",
        ]);
        Setting::create([
            "name" => "Vodafone Cash",
            "key" => "vodafone_cash",
            "key_status" => 0,
            "type" => "text",
            "value_ar" => "+201125119667",
            "value_en" => "+201125119667",
        ]);
        Setting::create([
            "name" => "Instapay",
            "key" => "instapay",
            "key_status" => 0,
            "type" => "text",
            "value_ar" => "01090770686",
            "value_en" => "01090770686",
        ]);
        Setting::create([
            "name" => "email",
            "key" => "email",
            "key_status" => 0,
            "type" => "text",
            "value_ar" => "info@elmoqawma.com",
            "value_en" => "info@elmoqawma.com",
        ]);
        Setting::create([
            "name" => "نص الإعلانات في اعلي المنصة",
            "key" => "top_header",
            "key_status" => 0,
            "type" => "text",
            "value_ar" => '["' . $nameSiteAR . ' هيا منصه تعليمية 🌍🥇", "توفر العديد من الدورات في  جميع المجالات 💯🎉", "انضم معنا الآن اكثر من 50 دورة تعليمية 🤳💻"]',
            "value_en" => '["' . $nameSiteEN . 'Imprint is an educational platform 🌍🥇", "It provides many courses in all fields 💯🎉", "Join more than 50 educational courses with us now 🤳💻 🤳💻"]',
        ]);
        Setting::create([
            "name" => "title_hero",
            "key" => "title_hero",
            "key_status" => 0,
            "type" => "text",
            "value_ar" => "منصة $nameSiteAR التعليميةَ",
            "value_en" => "$nameSiteEN educational platform",
        ]);
        Setting::create([
            "name" => "description_hero",
            "key" => "description_hero",
            "key_status" => 0,
            "type" => "text",
            "value_ar" => "هناك حقيقة مثبتة منذ زمن طويل وهي أن المحتوى المقروء لصفحة ما سيلهي القارئ عن التركيز على الشكل الخارجي للنص أو شكل توضع الفقرات في الصفحة التي يقرأها. ولذلك يتم استخدام طريقة لوريم إيبسوم لأنها تعطي توزيعاَ طبيعياَ",
            "value_en" =>
                "There is a long-established fact that the readable content of a page will distract the reader from focusing on the external appearance of the text or the way the paragraphs are placed on the page he is reading. Therefore, the Lorem Ipsum method is used because it gives a normal distribution",
        ]);
        Setting::create([
            "name" => "وصف الميتا",
            "key" => "meta_description",
            "key_status" => 0,
            "type" => "text",
            "value_ar" => "$nameSiteAR فريق ذو خبرة عالية في عالم البرمجيات والتسويق الالكتروني والإستشارات الرقمية . وجعلنا تطوير أعمالك هي بصمتنا في عالم تصميم المواقع الإلكترونية والمتاجر الإلكترونية وتطبيقات الهواتف والتسويق الإلكتروني والحلول البرمجية والرقمية",
            "value_en" => "The imprint of a highly experienced team in the world of software, e-marketing and digital consulting. We have made developing your business our mark in the world of website design, e-stores, phone applications, e-marketing, and software and digital solutions.",
        ]);
        Setting::create([
            "name" => "الكلمات المفتاحية للموقع",
            "key" => "meta_keywords",
            "key_status" => 0,
            "type" => "text",
            "value_ar" => "$nameSiteAR , مواقع, تصميم مواقع الكترونية, تصميم مواقع الانترنت, تصميم مواقع, شركة تصميم مواقع, تطبيقات, تسويق, تسويق بالمحتوى, شركة تسويق الكتروني, تسويق الكتروني, طريقة تسويق منتج عبر النت, شركة تسويق رقمي, تسويق عملك عبر الإنترنت, مصمم جرافيك",
            "value_en" =>
                "Imprint, websites, website design, website design, website design, website design company, applications, marketing, content marketing, electronic marketing company, electronic marketing, method of marketing a product over the internet, digital marketing company, marketing your business online, graphic designer",
        ]);
        Setting::create([
            "name" => "صورة افاتار الصفحة الرئيسية اول قسم",
            "key" => "avatar_hero",
            "key_status" => 0,
            "type" => "photo",
            "photo" => "settings/avatar_hero.png",
        ]);
        Setting::create([
            "name" => "خلفية الصفحة الرئيسية اول قسم",
            "key" => "bg_hero",
            "key_status" => 0,
            "type" => "photo",
            "photo" => "settings/bg_hero.png",
        ]);
        Setting::create([
            "name" => "خلفية الكورسات والعروض",
            "key" => "bg_course",
            "key_status" => 0,
            "type" => "photo",
            "photo" => "settings/bg_course.png",
        ]);
        Setting::create([
            "name" => "صوره تواصل معنا",
            "key" => "bg_contact",
            "key_status" => 0,
            "type" => "photo",
            "photo" => "settings/contact.svg",
        ]);
        Setting::create([
            "name" => "صوره التقييمات",
            "key" => "bg_review",
            "key_status" => 0,
            "type" => "photo",
            "photo" => "settings/review.svg",
        ]);
        Setting::create([
            "name" => "صوره تسجيل الدخول",
            "key" => "bg_login",
            "key_status" => 0,
            "type" => "photo",
            "photo" => "settings/login.svg",
        ]);
        Setting::create([
            "name" => "صوره إنشاء حساب جديد",
            "key" => "bg_register",
            "key_status" => 0,
            "type" => "photo",
            "photo" => "settings/register.svg",
        ]);
    }
}
