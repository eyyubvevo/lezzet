<?php

namespace App\Http\Controllers;

use App\Repositories\EloquentAboutRepository;
use App\Repositories\EloquentPageMetaTagRepository;
use Butschster\Head\Facades\Meta;
use Butschster\Head\MetaTags\Entities\Webmaster;

class AboutController extends Controller
{
    protected EloquentAboutRepository $aboutRepository;
    protected EloquentPageMetaTagRepository $pageMetaTagRepository;

    public function __construct(
        EloquentAboutRepository           $aboutRepository,
        EloquentPageMetaTagRepository     $pageMetaTagRepository
    )
    {
        $this->aboutRepository = $aboutRepository;
        $this->pageMetaTagRepository = $pageMetaTagRepository;
    }


    public function index()
    {
     /*   try {
            $about = $this->aboutRepository->first(
                filters: [],
                columns: ['id', 'title', 'content', 'image', 'created_at'],
                relations: [],
                joins: [],
                useCache: false
            );
            $filters = [
                'page_name' => ['operator' => '=', 'value' => 'Haqqımızda'],
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
            return view('front.about', compact('about'));
        } catch (\Exception $e) {
            flash()->addFlash('warning', 'Xəbərdarlıq', $e->getMessage(), ['timeout' => 3000, 'position' => 'top-center']);
        }*/
    }
}
