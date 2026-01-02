<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use Illuminate\Support\Facades\Log;
use Exception;

class CategoryController extends Controller
{
    public function index()
{
    //  جلب المعرف والاسم فقط
    $categories = Category::select('id', 'name')
                          ->withCount('books') // جلب عدد الكتب فقط بدل جلب الكتب نفسها
                          ->latest()
                          ->get();
                          
    return view('admin.categories.index', compact('categories'));
}

    public function create()
    {   
        return view('admin.categories.create');
    }

    public function store(StoreCategoryRequest $request)
    {
        try {
            Category::create($request->validated());

            return redirect()->route('categories.index')
                             ->with('success', 'تم إضافة نوع الكتاب الجديد بنجاح');

        } catch (Exception $e) {
            Log::error("Category Store Error: " . $e->getMessage());
          // اعادة المستخدم الى الصفحة التي جاء منها
            return redirect()->back()
                             ->withInput()// اخذ البيانات التي ارسلها المستخدم و ارسالها معه الى الصفحة السابقة حتى لا يعيد كتابة كل شيء
                             ->with('error', ' حدث خطأ أثناء الحفظ  يرجى اعادةالمحاولة لاحقاً');
        }
    }

    public function edit(Category $category)
    {
        return view('admin.categories.edit', compact('category'));
    }

    public function update(UpdateCategoryRequest $request, Category $category)
    {
        try {
            $category->update($request->validated());

            return redirect()->route('categories.index')
                             ->with('success', 'تم تحديث اسم التصنيف بنجاح');

        } catch (Exception $e) {
            Log::error("Category Update Error: " . $e->getMessage());

            return redirect()->back()
                             ->withInput()
                             ->with('error', 'فشل التحديث  قد يكون الاسم موجوداً مسبقاً');
        }
    }
    public function show(Category $category)
{
    $books = $category->books()->paginate(10); 

    return view('admin.categories.show', compact('category', 'books'));
}

    public function destroy(Category $category)
    {
        try {
            $category->delete();

            return redirect()->route('categories.index')
                             ->with('success', 'تم حذف التصنيف بنجاح');

        } catch (Exception $e) {
            Log::error("Category Delete Error: " . $e->getMessage());

            return redirect()->back()
                             ->with('error', 'عذراً لا يمكن حذف هذا التصنيف حالياً');
        }
    }
   
}
