<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Book;
use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class LibraryDataSeeder extends Seeder
{
    public function run()
    {
        $categories = [
            ['id' => 1, 'name' => 'novels'],
            ['id' => 2, 'name' => 'Psychology'],
            ['id' => 3, 'name' => 'Business & Finance'],
        ];

        foreach ($categories as $cat) {
            Category::updateOrCreate(['id' => $cat['id']], ['name' => $cat['name']]);
        }

        $books = [
            [
                'id' => 2,
                'category_id' => 3,
                'title' => 'Good to Great',
                'author' => 'جيم كولينز',
                'description' => 'دراسة معمقة حول سبب نجاح بعض الشركات في التحول من أداء عادي إلى أداء استثنائي مستمر لسنوات طويلة، بينما تفشل شركات أخرى',
                'publish_year' => 2001,
                'language' => 'الإنجليزية',
                'copies_number' => 1,
                'cover_image' => 'books/covers/fmtF4RY3G6wFVQOGOIfpvu2VLJllflm3fcWno.jpg',
            ],
            [
                'id' => 3,
                'category_id' => 2,
                'title' => 'The Psychology of Money',
                'author' => 'مورغان هاوزل',
                'description' => 'وضح الكتاب أن النجاح المالي لا يتعلق بالذكاء فقط، بل بالسلوك البشري. يقدم 19 قصة قصيرة تشرح كيف نفكر في المال، الحظ، والجشغ',
                'publish_year' => 2020,
                'language' => 'الإنجليزية',
                'copies_number' => 4,
                'cover_image' => 'books/covers/njpbcdekoV5lZUdCaR1217Ctlu8xrL4C4VMNK.jpg',
            ],
            [
                'id' => 4,
                'category_id' => 1,
                'title' => 'عداء الطائرة الورقية',
                'author' => 'خالد حسيني',
                'description' => 'قصة مؤثرة عن الصداقة، الخيانة، والبحث عن المغفرة. تدور الأحداث بين "أمير" و"حسن" في أفغانستان، وكيف يمكن لحدث واحد في الطفولة أن يغير حياة الإنسان للأبد. رحلة من الذنب إلى التكفير عن الخطأ.',
                'publish_year' => 2003,
                'language' => 'العربية',
                'copies_number' => 44,
                'cover_image' => 'books/covers/6hlrHGWez3BFj4YW4fnFwgAa8TYSRrHInaXBn.jpg',
            ],
            [
                'id' => 5,
                'category_id' => 1,
                'title' => 'ساق البامبو',
                'author' => 'سعود السنعوسي',
                'description' => 'رواية عميقة تبحث في الهوية والانتماء من خلال "عيسى/هوزيه" المولود لأب كويتي وأم فلبينية. رحلة شاب يبحث عن "وطنه" بين بلدين وثقافتين، في مواجهة رفض المجتمع',
                'publish_year' => 2012,
                'language' => 'العربية',
                'copies_number' => 55,
                'cover_image' => 'books/covers/8ulQ4IAjEyL7hM5R4bAN7rgXQ5HA3RdqaQVib.jpg',
            ],
            [
                'id' => 6,
                'category_id' => 1,
                'title' => 'وبعد',
                'author' => 'غيوم ميسو',
                'description' => 'قصة مشوقة تمزج بين الواقع والغموض، تدور حول محامٍ ناجح يواجه تجربة غريبة تغير نظرته للحياة والموت، وتجعله يسعى لإصلاح علاقاته قبل فوات الأوان',
                'publish_year' => 2004,
                'language' => 'العربية',
                'copies_number' => 1,
                'cover_image' => 'books/covers/eEHIfFWhZJ|Mknhk92TG98Vb9VSPdPMLEa7RY.jpg',
            ],
            [
                'id' => 7,
                'category_id' => 1,
                'title' => 'ألف شمس مشرقة',
                'author' => 'خالد حسيني',
                'description' => 'ملحمة إنسانية مؤثرة عن الصداقة والقوة بين امرأتين (مريم وليلى) تجمعهما الظروف القاسية في أفغانستان خلال الحروب. قصة عن التضحية والأمل الذي يسطع وسط الظلام',
                'publish_year' => 2007,
                'language' => 'العربية',
                'copies_number' => 0,
                'cover_image' => 'books/covers/1sfDR2wQjqlbBFpzvUHwsnPwuZxwtT5n0z.jpg',
            ],
        ];

        foreach ($books as $bookData) {
            Book::updateOrCreate(['id' => $bookData['id']], $bookData);
        }

    }
}