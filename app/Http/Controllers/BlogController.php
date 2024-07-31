<?php

namespace App\Http\Controllers;

use App\Repositories\EloquentNewsRepository;
use App\Repositories\EloquentPageMetaTagRepository;
use Butschster\Head\Facades\Meta;
use Butschster\Head\MetaTags\Entities\Webmaster;
use Illuminate\Http\Request;
use MetaTag;
use App\Models\News;


class BlogController extends Controller
{
    protected EloquentNewsRepository $newsRepository;
    protected EloquentPageMetaTagRepository $pageMetaTagRepository;

    public function __construct(
        EloquentNewsRepository $newsRepository,
        EloquentPageMetaTagRepository     $pageMetaTagRepository
    )
    {
        $this->newsRepository = $newsRepository;
        $this->pageMetaTagRepository = $pageMetaTagRepository;
    }

    public function index()
    {
        try {
            $news = $this->newsRepository->all(
                filters: ['status' => ['operator' => '=', 'value' => 1],],
                columns: ['id','slug', 'title', 'short_content','image', 'status', 'created_at'],
                orderBy: 'created_at',
                sortBy: 'DESC',
            );
            $filters = [
                'page_name' => ['operator' => '=', 'value' => 'Blog'],
            ];
            $columns = ['id', 'title', 'description', 'keywords'];
            $page_meta_tag = $this->pageMetaTagRepository->first(
                filters: $filters,
                columns: $columns,
            );
            if ($page_meta_tag) {
                Meta::setTitleSeparator('->')
                    ->prependTitle(setting('title'))
                    ->setTitle($page_meta_tag->getTranslation('title', app()->getLocale()))
                    ->setDescription($page_meta_tag->getTranslation('description', app()->getLocale()))
                    ->setKeywords($page_meta_tag->getTranslation('keywords', app()->getLocale()))
                    ->setRobots('Vaqif,Shamil')
                    ->setContentType('text/html')
                    ->setViewport('width=device-width, initial-scale=1')
                    ->addWebmaster(Webmaster::GOOGLE, 'f+e-Ww4=[Pp4wyEPLdVx4LxTsQ')
                    ->setCanonical(config('app.url'));
            }
            return view('front.news', compact('news'));
        } catch (\Exception $e) {
            flash()->addFlash('warning', 'Xəbərdarlıq', $e->getMessage(), ['timeout' => 3000, 'position' => 'top-center']);
        }
    }

    public function show($slug){
        try {
            // $new = $this->newsRepository->first(
            //     filters: ['status' => ['operator' => '=', 'value' => 1],  'slug' => ['operator' => '=', 'value' => $slug],],
            //     columns: ['id', 'title','slug', 'short_content','content', 'image','status', 'created_at'],
            // );
            $new = News::where("slug->" . app()->getLocale(), $slug)->firstOrFail();
            $slug1=$new;
            if (!$new) {
                flash()->addFlash('warning', 'Xəbərdarlıq', 'Mövcud deyil', ['timeout' => 3000, 'position' => 'top-center']);
            }
            Meta::setTitleSeparator('->')
                ->prependTitle(setting('title'))
                ->setTitle($new->getTranslation('meta_title',app()->getLocale()))
                ->setDescription($new->getTranslation('meta_description',app()->getLocale()))
                ->setKeywords($new->getTranslation('meta_keywords',app()->getLocale()))
                ->setRobots('Vaqif,Shamil')
                ->setContentType('text/html')
                ->setViewport('width=device-width, initial-scale=1')
                ->addWebmaster(Webmaster::GOOGLE, 'f+e-Ww4=[Pp4wyEPLdVx4LxTsQ')
//                ->setFavicon()
                ->setCanonical(config('app.url'));
            return view('front.news_detail', compact('new','slug1'));
        } catch (\Exception $e) {
            flash()->addFlash('warning', 'Xəbərdarlıq', $e->getMessage(), ['timeout' => 3000, 'position' => 'top-center']);
        }
    }
}
