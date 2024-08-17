<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Repositories\EloquentPageMetaTagRepository;
use Butschster\Head\MetaTags\Entities\Webmaster;
use Illuminate\Http\Request;
use Butschster\Head\Facades\Meta;

class ProductController extends Controller
{
    protected EloquentPageMetaTagRepository $pageMetaTagRepository;

    public function __construct(
        EloquentPageMetaTagRepository $pageMetaTagRepository
    )
    {
        $this->pageMetaTagRepository = $pageMetaTagRepository;
    }


    public function index($slug)
    {
        // return app()->getLocale();
        $category = Category::where("slug->" . app()->getLocale(), $slug)->firstOrFail();
        $slug1=$category;
        $categoryAttributes = $category->attributes;

        $attributeProductCounts = [];

        foreach ($categoryAttributes as $attribute) {
            $attributeProductCounts[$attribute->id] = Product::where('category_id', $category->id)
                ->where('attribute_id', $attribute->id)
                ->count();
        }
        $filters = [
            'page_name' => ['operator' => '=', 'value' => 'Məhsullar'],
        ];
        $columns = ['id', 'title', 'description', 'keywords'];
            Meta::setTitleSeparator('->')
                ->prependTitle(setting('title'))
                ->setTitle($category->getTranslation('meta_title', app()->getLocale()))
                ->setDescription($category->getTranslation('meta_description', app()->getLocale()))
                ->setKeywords($category->getTranslation('meta_keywords', app()->getLocale()))
                ->setRobots('Vaqif,Shamil')
                ->setContentType('text/html')
                ->setViewport('width=device-width, initial-scale=1')
                ->addWebmaster(Webmaster::GOOGLE, 'f+e-Ww4=[Pp4wyEPLdVx4LxTsQ')
                ->setCanonical(config('app.url'));
  
        return view('front.products', compact('attributeProductCounts', 'category','slug1'));
    }

    public function show($categorySlug, $slug=null)
    {
        if($slug==null) return redirect()->back();

        try {
            $product = Product::where("slug->" . app()->getLocale(), $slug)->firstOrFail();
            $slug1 = Category::where("slug->" . app()->getLocale(), $categorySlug)->firstOrFail();
            $slug2=$product;
            if (!$product) {
                flash()->addFlash('warning', 'Xəbərdarlıq', 'Mövcud deyil', ['timeout' => 3000, 'position' => 'top-center']);
            }
            Meta::setTitleSeparator('->')
                ->prependTitle(setting('title'))
                ->setTitle($product->getTranslation('meta_title', app()->getLocale()))
                ->setDescription($product->getTranslation('meta_description', app()->getLocale()))
                ->setKeywords($product->getTranslation('meta_keywords', app()->getLocale()))
                ->setRobots('Vaqif,Shamil')
                ->setContentType('text/html')
                ->setViewport('width=device-width, initial-scale=1')
                ->addWebmaster(Webmaster::GOOGLE, 'f+e-Ww4=[Pp4wyEPLdVx4LxTsQ')
//                ->setFavicon()
                ->setCanonical(config('app.url'));
            return view('front.product-detail', compact('product','slug1','slug2'));
        } catch (\Exception $e) {
            flash()->addFlash('warning', 'Xəbərdarlıq', $e->getMessage(), ['timeout' => 3000, 'position' => 'top-center']);
        }
    }
}
